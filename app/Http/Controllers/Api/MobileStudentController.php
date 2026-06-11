<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Student;
use App\Support\PublicStorageUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MobileStudentController extends Controller
{
    public function profile(Request $request)
    {
        $student = $this->resolveStudent($request);
        if (!$student) {
            return response()->json(['message' => 'Token tidak valid.'], 401);
        }

        if (!$student->uid_kartu) {
            return response()->json(['message' => 'UID siswa belum diatur oleh admin.'], 422);
        }

        return response()->json([
            'message' => 'Profil siswa.',
            'user' => [
                'name' => $student->name,
                'nis' => $student->nis,
                'class_name' => $student->class_name,
                'major' => $student->major,
                'email' => $student->email,
                'phone' => $student->phone,
                'status' => $student->status,
                'date_of_birth' => $student->date_of_birth?->toDateString(),
                'uid_kartu' => $student->uid_kartu,
            ],
            'uid_kartu' => $student->uid_kartu,
        ]);
    }

    public function summary(Request $request)
    {
        $student = $this->resolveStudent($request);
        if (!$student) {
            return response()->json(['message' => 'Token tidak valid.'], 401);
        }

        $from = $request->query('from');
        $until = $request->query('until');

        $fromDate = $from ? Carbon::parse($from)->startOfDay() : Carbon::now()->startOfMonth();
        $untilDate = $until ? Carbon::parse($until)->endOfDay() : Carbon::now()->endOfMonth();

        $records = Attendance::query()
            ->where('student_id', $student->id)
            ->whereBetween('attendance_date', [$fromDate->toDateString(), $untilDate->toDateString()])
            ->get();

        $counts = $records->countBy('status');
        $hadir = $counts->get('hadir', 0);
        $izin = $counts->get('izin', 0);
        $sakit = $counts->get('sakit', 0);
        $alpa = $counts->get('alpa', 0);

        return response()->json([
            'message' => 'Ringkasan absensi siswa.',
            'summary' => [
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpa' => $alpa,
                'total' => $hadir + $izin + $sakit + $alpa,
            ],
            'period' => [
                'from' => $fromDate->toDateString(),
                'until' => $untilDate->toDateString(),
            ],
        ]);
    }

    public function absensi(Request $request)
    {
        $student = $this->resolveStudent($request);
        if (!$student) {
            return response()->json(['message' => 'Token tidak valid.'], 401);
        }

        $from = $request->query('from');
        $until = $request->query('until');

        $fromDate = $from ? Carbon::parse($from)->startOfDay() : Carbon::now()->startOfMonth();
        $untilDate = $until ? Carbon::parse($until)->endOfDay() : Carbon::now()->endOfMonth();

        $records = Attendance::query()
            ->where('student_id', $student->id)
            ->whereBetween('attendance_date', [$fromDate->toDateString(), $untilDate->toDateString()])
            ->orderByDesc('attendance_date')
            ->orderByDesc('attendance_time')
            ->get();

        return response()->json([
            'message' => 'Data absensi siswa.',
            'data' => $records->map(function ($record) {
                return [
                    'tanggal' => $record->attendance_date?->format('Y-m-d'),
                    'waktu' => $record->attendance_time,
                    'status' => $record->status,
                    'keterangan' => $record->note,
                ];
            }),
            'period' => [
                'from' => $fromDate->toDateString(),
                'until' => $untilDate->toDateString(),
            ],
        ]);
    }

    public function leaveRequests(Request $request)
    {
        $student = $this->resolveStudent($request);
        if (!$student) {
            return response()->json(['message' => 'Token tidak valid.'], 401);
        }

        $requests = LeaveRequest::query()
            ->where('student_id', $student->id)
            ->orderByDesc('requested_at')
            ->orderByDesc('id')
            ->limit(30)
            ->get();

        return response()->json([
            'message' => 'Data pengajuan izin/sakit siswa.',
            'data' => $requests->map(fn ($leaveRequest) => [
                'id' => $leaveRequest->id,
                'type' => $leaveRequest->type,
                'start_date' => $leaveRequest->start_date?->toDateString(),
                'end_date' => $leaveRequest->end_date?->toDateString(),
                'reason' => $leaveRequest->reason,
                'status' => $leaveRequest->status,
                'requested_at' => $leaveRequest->requested_at?->toDateTimeString(),
                'responded_at' => $leaveRequest->responded_at?->toDateTimeString(),
                'response_note' => $leaveRequest->response_note,
                'rejection_reason' => $leaveRequest->rejection_reason,
                'photo' => $leaveRequest->photo,
                'photo_url' => PublicStorageUrl::storageUrl($leaveRequest->photo),
            ]),
        ]);
    }

    public function storeLeaveRequest(Request $request)
    {
        $student = $this->resolveStudent($request);
        if (!$student) {
            return response()->json(['message' => 'Token tidak valid.'], 401);
        }

        $data = $request->validate([
            'type' => 'required|in:izin,sakit',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $startDate = Carbon::parse($data['start_date'])->toDateString();
        $endDate = isset($data['end_date']) && $data['end_date']
            ? Carbon::parse($data['end_date'])->toDateString()
            : $startDate;

        $existingAttendance = Attendance::query()
            ->where('student_id', $student->id)
            ->whereDate('attendance_date', $startDate)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'message' => 'Absensi untuk tanggal ini sudah ada. Pengajuan tidak bisa dibuat.',
            ], 422);
        }

        $existingRequest = LeaveRequest::query()
            ->where('student_id', $student->id)
            ->whereDate('start_date', $startDate)
            ->whereIn('status', ['pending_teacher', 'pending_admin', 'approved'])
            ->first();

        if ($existingRequest) {
            return response()->json([
                'message' => 'Pengajuan untuk tanggal ini masih aktif atau sudah disetujui.',
            ], 422);
        }

        $photoPath = null;
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $photoPath = $request->file('photo')->store('leave-requests', 'public');
        }

        $leaveRequest = LeaveRequest::create([
            'student_id' => $student->id,
            'type' => $data['type'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'request_date' => $startDate,
            'reason' => $data['reason'],
            'status' => 'pending_admin',
            'requested_at' => Carbon::now(),
            'photo' => $photoPath,
        ]);

        return response()->json([
            'message' => 'Pengajuan berhasil dikirim ke TU.',
            'data' => [
                'id' => $leaveRequest->id,
                'type' => $leaveRequest->type,
                'start_date' => $leaveRequest->start_date?->toDateString(),
                'end_date' => $leaveRequest->end_date?->toDateString(),
                'reason' => $leaveRequest->reason,
                'status' => $leaveRequest->status,
                'requested_at' => $leaveRequest->requested_at?->toDateTimeString(),
                'photo' => $leaveRequest->photo,
                'photo_url' => PublicStorageUrl::storageUrl($leaveRequest->photo),
            ],
        ], 201);
    }

    private function resolveStudent(Request $request): ?Student
    {
        $token = $request->bearerToken();
        if (!$token) {
            return null;
        }

        $hash = hash('sha256', $token);
        return Student::where('api_token', $hash)->first();
    }
}
