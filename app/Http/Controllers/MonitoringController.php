<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\NfcDevice;
use Illuminate\Support\Carbon;

class MonitoringController extends Controller
{
    public function nfc()
    {
        $today = Carbon::now()->toDateString();

        $attendances = Attendance::query()
            ->with(['student', 'device'])
            ->whereDate('attendance_date', $today)
            ->orderByDesc('attendance_time')
            ->orderByDesc('id')
            ->limit(40)
            ->get();

        $events = $attendances->map(function ($attendance) {
            $student = $attendance->student;
            $device = $attendance->device;
            $status = $attendance->status ?? 'hadir';

            $badgeClass = match ($status) {
                'hadir' => 'badge-success',
                'izin' => 'badge-warning',
                'sakit' => 'badge-danger',
                'alpha' => 'badge-danger',
                default => 'badge-info',
            };

            $statusLabel = match ($status) {
                'hadir' => 'Hadir',
                'izin' => 'Izin',
                'sakit' => 'Sakit',
                'alpha' => 'Alpha',
                default => ucfirst($status),
            };

            $timeLabel = $attendance->attendance_time
                ? substr((string) $attendance->attendance_time, 0, 8)
                : '-';

            return [
                'student_name' => $student?->name ?? '—',
                'nis' => $student?->nis ?? '-',
                'class_name' => $student?->class_name ?? '-',
                'uid_kartu' => $student?->uid_kartu ?? '-',
                'device_name' => $device?->name ?? 'Pintu Utama',
                'status' => $status,
                'status_label' => $statusLabel,
                'badge_class' => $badgeClass,
                'time' => $timeLabel,
            ];
        });

        $totalScans = $attendances->whereNotNull('attendance_time')->count();
        $successCount = $attendances->where('status', 'hadir')->count();
        $failedCount = $attendances->whereIn('status', ['alpha', 'izin', 'sakit'])->count();
        $unknownCount = 0;

        $devices = NfcDevice::orderBy('name')->get();

        return view('monitoring.nfc', [
            'events' => $events,
            'totalScans' => $totalScans,
            'successCount' => $successCount,
            'failedCount' => $failedCount,
            'unknownCount' => $unknownCount,
            'devices' => $devices,
        ]);
    }
}
