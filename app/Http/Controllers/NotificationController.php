<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifications(): JsonResponse
    {
        $user = Auth::user();
        $notifications = [];

        if (!$user) {
            return response()->json(['notifications' => []]);
        }

        $now = Carbon::now('Asia/Jakarta');

        // Student notifications
        if ($user->role === 'siswa') {
            $student = Student::where('email', $user->email)->first();
            
            if ($student) {
                // Attendance reminder: 10 minutes before 9 AM
                if ($now->hour === 8 && $now->minute >= 50 && $now->minute <= 59) {
                    $today = Carbon::today()->toDateString();
                    $hasAttendance = Attendance::where('student_id', $student->id)
                        ->where('attendance_date', $today)
                        ->where('attendance_time', '!=', '00:00:00')
                        ->exists();
                    
                    if (!$hasAttendance) {
                        $notifications[] = [
                            'id' => 'attendance_reminder_' . $now->format('Hi'),
                            'type' => 'warning',
                            'title' => 'Pengingat Absen',
                            'message' => 'Absen akan ditutup dalam 10 menit. Silakan tap-in segera!',
                        ];
                    }
                }

                // Leave request responses
                $recentResponses = LeaveRequest::where('student_id', $student->id)
                    ->whereNotNull('responded_at')
                    ->where('responded_at', '>=', Carbon::now()->subMinutes(30))
                    ->whereIn('status', ['approved', 'rejected'])
                    ->get();

                foreach ($recentResponses as $request) {
                    $notifications[] = [
                        'id' => 'leave_response_' . $request->id,
                        'type' => $request->status === 'approved' ? 'success' : 'error',
                        'title' => 'Permintaan ' . ucfirst($request->type),
                        'message' => $request->status === 'approved' 
                            ? 'Permintaan Anda disetujui.' 
                            : 'Permintaan Anda ditolak: ' . ($request->rejection_reason ?? 'Alasan tidak disebutkan'),
                    ];
                }
            }
        }

        // Teacher notifications
        if ($user->role === 'guru') {
            // New leave requests from students
            $newRequests = LeaveRequest::with('student')
                ->where('status', 'pending_teacher')
                ->where('requested_at', '>=', Carbon::now()->subMinutes(30))
                ->orderByDesc('requested_at')
                ->get();

            foreach ($newRequests as $request) {
                $notifications[] = [
                    'id' => 'new_request_' . $request->id,
                    'type' => 'info',
                    'title' => 'Permintaan Baru',
                    'message' => "{$request->student->name} mengajukan {$request->type}.",
                ];
            }
        }

        // Admin notifications
        if (in_array($user->role, ['admin', 'tata_usaha'])) {
            // Leave requests from teachers (pending_admin)
            $newRequests = LeaveRequest::with('student')
                ->where('status', 'pending_admin')
                ->where('requested_at', '>=', Carbon::now()->subMinutes(30))
                ->orderByDesc('requested_at')
                ->get();

            foreach ($newRequests as $request) {
                $notifications[] = [
                    'id' => 'admin_request_' . $request->id,
                    'type' => 'info',
                    'title' => 'Permintaan Baru',
                    'message' => "{$request->student->name} mengajukan {$request->type} (dari Guru).",
                ];
            }
        }

        return response()->json(['notifications' => $notifications]);
    }
    public function teacherApprovals()
    {
        $requests = LeaveRequest::query()
            ->with('student')
            ->where('status', 'pending_teacher')
            ->orderByDesc('requested_at')
            ->get();

        return view('notifications.guru-persetujuan', [
            'pending' => $requests,
        ]);
    }

    public function teacherMonitoring()
    {
        $requests = LeaveRequest::query()
            ->with('student')
            ->orderByDesc('requested_at')
            ->get();

        return view('teachers.monitoring', [
            'requests' => $requests,
        ]);
    }

    public function teacherApprove(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $leaveRequest->update([
            'status' => 'approved',
            'responded_at' => Carbon::now(),
            'response_note' => $request->string('response_note')->toString() ?: null,
        ]);

        // Loop through the requested dates and create attendance records
        $startDate = $leaveRequest->start_date ?? Carbon::parse($leaveRequest->request_date ?? Carbon::today());
        $endDate = $leaveRequest->end_date ?? $startDate;

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $existing = Attendance::where('student_id', $leaveRequest->student_id)
                ->where('attendance_date', $date->toDateString())
                ->first();

            // Jangan timpa jika siswa ternyata sudah absen hadir secara fisik
            if (!($existing && $existing->status === 'hadir')) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $leaveRequest->student_id,
                        'attendance_date' => $date->toDateString(),
                    ],
                    [
                        'status' => $leaveRequest->type,
                        'attendance_time' => '00:00:00',
                        'note' => $leaveRequest->reason,
                    ]
                );
            }
        }

        return redirect()
            ->route('notifications.guru-approvals')
            ->with('success', 'Permintaan disetujui. Absensi telah dicatat.');
    }

    public function teacherReject(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $leaveRequest->update([
            'status' => 'pending_admin',
            'responded_at' => Carbon::now(),
            'rejection_reason' => $request->string('rejection_reason')->toString() ?: 'Ditolak oleh Guru',
        ]);

        return redirect()
            ->route('notifications.guru-approvals')
            ->with('success', 'Permintaan ditolak dan diteruskan ke Admin untuk review.');
    }

    public function tuApprovals()
    {
        $requests = LeaveRequest::query()
            ->with('student')
            ->where('status', 'pending_admin')
            ->orderByDesc('requested_at')
            ->get();

        return view('notifications.tu-persetujuan', [
            'pending' => $requests,
        ]);
    }

    public function tuApprove(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $leaveRequest->update([
            'status' => 'approved',
            'responded_at' => Carbon::now(),
            'response_note' => $request->string('response_note')->toString() ?: null,
        ]);

        // Loop through the requested dates and create attendance records
        $startDate = $leaveRequest->start_date ?? Carbon::parse($leaveRequest->request_date ?? Carbon::today());
        $endDate = $leaveRequest->end_date ?? $startDate;

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $existing = Attendance::where('student_id', $leaveRequest->student_id)
                ->where('attendance_date', $date->toDateString())
                ->first();

            // Jangan timpa jika siswa ternyata sudah absen hadir secara fisik
            if (!($existing && $existing->status === 'hadir')) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $leaveRequest->student_id,
                        'attendance_date' => $date->toDateString(),
                    ],
                    [
                        'status' => $leaveRequest->type,
                        'attendance_time' => '00:00:00',
                        'note' => $leaveRequest->reason,
                    ]
                );
            }
        }

        return redirect()
            ->route('notifications.tu-approvals')
            ->with('success', 'Permintaan disetujui. Absensi telah dicatat.');
    }

    public function tuReject(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $leaveRequest->update([
            'status' => 'rejected',
            'responded_at' => Carbon::now(),
            'rejection_reason' => $request->string('rejection_reason')->toString() ?: 'Ditolak oleh TU',
        ]);

        // Create attendance record as alpa
        $startDate = $leaveRequest->start_date ?? Carbon::parse($leaveRequest->request_date ?? Carbon::today());
        $endDate = $leaveRequest->end_date ?? $startDate;

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $existing = Attendance::where('student_id', $leaveRequest->student_id)
                ->where('attendance_date', $date->toDateString())
                ->first();

            if (!($existing && $existing->status === 'hadir')) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $leaveRequest->student_id,
                        'attendance_date' => $date->toDateString(),
                    ],
                    [
                        'status' => 'alpa',
                        'attendance_time' => '00:00:00',
                        'note' => $leaveRequest->rejection_reason,
                    ]
                );
            }
        }

        return redirect()
            ->route('notifications.tu-approvals')
            ->with('success', 'Permintaan ditolak. Siswa dianggap alpa.');
    }
}
