<?php

namespace App\Http\Controllers;

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
        ]);

        $data['status'] = 'pending';
        $data['requested_at'] = Carbon::now();

        LeaveRequest::create($data);

        return back()->with('success', 'Request izin/sakit berhasil dibuat.');
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
