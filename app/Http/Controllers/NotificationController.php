<?php

namespace App\Http\Controllers;

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
            ->where('status', 'pending')
            ->orderByDesc('requested_at')
            ->get();

        return view('notifications.guru-persetujuan', [
            'pending' => $requests,
        ]);
    }

    public function approve(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $leaveRequest->update([
            'status' => 'approved',
            'responded_at' => Carbon::now(),
            'response_note' => $request->string('response_note')->toString() ?: null,
        ]);

        return redirect()
            ->route('notifications.guru-approvals')
            ->with('success', 'Notifikasi berhasil disetujui.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $leaveRequest->update([
            'status' => 'rejected',
            'responded_at' => Carbon::now(),
            'response_note' => $request->string('response_note')->toString() ?: null,
        ]);

        return redirect()
            ->route('notifications.guru-approvals')
            ->with('success', 'Notifikasi berhasil ditolak.');
    }
}
