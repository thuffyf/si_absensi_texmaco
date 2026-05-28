<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::query()->with('student');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        $requests = $query->orderByDesc('requested_at')->get();
        $pending = $requests->where('status', 'pending');
        $approved = $requests->where('status', 'approved');
        $rejected = $requests->where('status', 'rejected');
        $students = Student::orderBy('name')->get();

        return view('requests.izin-sakit', compact('pending', 'approved', 'rejected', 'students'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:izin,sakit',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Check if attendance already exists for this date
        $existingAttendance = Attendance::where('student_id', $data['student_id'])
            ->whereDate('attendance_date', $data['start_date'])
            ->first();

        if ($existingAttendance) {
            return back()->with('error', 'Anda sudah melakukan absensi untuk tanggal ini. Tidak bisa request izin/sakit.');
        }

        // Check if leave request already exists for this date
        $existingRequest = LeaveRequest::where('student_id', $data['student_id'])
            ->whereDate('request_date', $data['start_date'])
            ->whereIn('status', ['pending_teacher', 'pending_admin', 'approved'])
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'Anda sudah memiliki request ' . $existingRequest->type . ' untuk tanggal ini.');
        }

        $data['status'] = 'pending_teacher';
        $data['requested_at'] = Carbon::now();
        $data['request_date'] = Carbon::today()->toDateString();

        // Handle photo upload
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $photoPath = $request->file('photo')->store('leave-requests', 'public');
            $data['photo'] = $photoPath;
        }

        LeaveRequest::create($data);

        return back()->with('success', 'Request izin/sakit berhasil dibuat dan dikirim ke Guru untuk persetujuan.');
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'approved',
            'responded_at' => Carbon::now(),
            'response_note' => $request->string('response_note')->toString() ?: null,
        ]);

        return back()->with('success', 'Request berhasil disetujui.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'rejected',
            'responded_at' => Carbon::now(),
            'response_note' => $request->string('response_note')->toString() ?: null,
        ]);

        return back()->with('success', 'Request berhasil ditolak.');
    }
}
