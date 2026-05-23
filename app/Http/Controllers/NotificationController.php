<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NotificationController extends Controller
{
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

    public function teacherApprove(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $leaveRequest->update([
            'status' => 'pending_tu',
            'responded_at' => Carbon::now(),
            'response_note' => $request->string('response_note')->toString() ?: null,
        ]);

        return redirect()
            ->route('notifications.guru-approvals')
            ->with('success', 'Permintaan disetujui dan diteruskan ke TU.');
    }

    public function teacherReject(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $leaveRequest->update([
            'status' => 'rejected',
            'responded_at' => Carbon::now(),
            'rejection_reason' => $request->string('rejection_reason')->toString() ?: 'Ditolak oleh Guru',
        ]);

        return redirect()
            ->route('notifications.guru-approvals')
            ->with('success', 'Permintaan ditolak.');
    }

    public function tuApprovals()
    {
        $requests = LeaveRequest::query()
            ->with('student')
            ->where('status', 'pending_tu')
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

        // Create attendance record for approved leave
        Attendance::updateOrCreate(
            [
                'student_id' => $leaveRequest->student_id,
                'attendance_date' => $leaveRequest->request_date ?? Carbon::today()->toDateString(),
            ],
            [
                'status' => $leaveRequest->type,
                'attendance_time' => '00:00:00',
                'note' => $leaveRequest->reason,
            ]
        );

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

        return redirect()
            ->route('notifications.tu-approvals')
            ->with('success', 'Permintaan ditolak.');
    }
}
