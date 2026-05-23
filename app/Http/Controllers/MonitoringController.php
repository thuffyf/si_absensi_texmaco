<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\NfcDevice;
use App\Models\ScanAttempt;
use Illuminate\Support\Carbon;

class MonitoringController extends Controller
{
    public function nfc()
    {
        $today = Carbon::now()->toDateString();

        // Get successful attendances
        $attendances = Attendance::query()
            ->with(['student', 'device'])
            ->whereDate('attendance_date', $today)
            ->orderByDesc('attendance_time')
            ->orderByDesc('id')
            ->limit(40)
            ->get();

        // Get unregistered card scans
        $unregisteredScans = ScanAttempt::query()
            ->with('device')
            ->whereDate('scanned_at', $today)
            ->where('status', 'unregistered')
            ->orderByDesc('scanned_at')
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        // Combine events
        $events = collect();

        // Add attendance events
        foreach ($attendances as $attendance) {
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

            $events->push([
                'student_name' => $student?->name ?? '—',
                'nis' => $student?->nis ?? '-',
                'class_name' => $student?->class_name ?? '-',
                'uid_kartu' => $student?->uid_kartu ?? '-',
                'device_name' => $device?->name ?? 'Ruang Lab TEI',
                'status' => $status,
                'status_label' => $statusLabel,
                'badge_class' => $badgeClass,
                'time' => $timeLabel,
                'is_unregistered' => false,
            ]);
        }

        // Add unregistered card events
        foreach ($unregisteredScans as $scan) {
            $device = $scan->device;
            $timeLabel = $scan->scanned_at ? $scan->scanned_at->format('H:i:s') : '-';

            $events->push([
                'student_name' => 'Kartu Tidak Terdaftar',
                'nis' => '-',
                'class_name' => '-',
                'uid_kartu' => $scan->uid_kartu,
                'device_name' => $device?->name ?? 'Ruang Lab TEI',
                'status' => 'unregistered',
                'status_label' => 'Tidak Terdaftar',
                'badge_class' => 'badge-warning',
                'time' => $timeLabel,
                'is_unregistered' => true,
            ]);
        }

        // Sort all events by time
        $events = $events->sortByDesc(function ($event) {
            return $event['time'];
        })->values();

        $totalScans = $attendances->whereNotNull('attendance_time')->count() + $unregisteredScans->count();
        $successCount = $attendances->where('status', 'hadir')->count();
        $failedCount = $attendances->whereIn('status', ['alpha', 'izin', 'sakit'])->count();
        $unknownCount = $unregisteredScans->count();

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
