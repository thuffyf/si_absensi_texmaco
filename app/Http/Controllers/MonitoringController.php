<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\NfcDevice;
use App\Models\ScanAttempt;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;

class MonitoringController extends Controller
{
    public function nfc()
    {
        return view('monitoring.nfc', $this->buildNfcPayload(includeDevicesAsModels: true));
    }

    /**
     * Endpoint untuk perangkat NFC / aplikasi mobile (butuh API key).
     */
    public function nfcData(): JsonResponse
    {
        return response()->json($this->buildNfcPayload(includeDevicesAsModels: false));
    }

    /**
     * Endpoint untuk halaman web monitoring (pakai auth/session), TANPA API key.
     */
    public function nfcWebData(): JsonResponse
    {
        return response()->json($this->buildNfcPayload(includeDevicesAsModels: false));
    }

    /**
     * @return array{
     *   events:\Illuminate\Support\Collection<int, array<string,mixed>>,
     *   totalScans:int,
     *   successCount:int,
     *   failedCount:int,
     *   unknownCount:int,
     *   devices:mixed
     * }
     */
    private function buildNfcPayload(bool $includeDevicesAsModels): array
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

        $events = collect();

        foreach ($attendances as $attendance) {
            $student = $attendance->student;
            $device = $attendance->device;
            $status = $attendance->status ?? 'hadir';

            $statusLabel = match ($status) {
                'hadir' => 'Hadir',
                'izin' => 'Izin',
                'sakit' => 'Sakit',
                'alpa' => 'Alpa',
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
                'time' => $timeLabel,
                'is_unregistered' => false,
            ]);
        }

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
                'time' => $timeLabel,
                'is_unregistered' => true,
            ]);
        }

        // Sort all events by time (HH:MM:SS)
        $events = $events->sortByDesc(fn ($event) => $event['time'])->values();

        $totalScans = $attendances->whereNotNull('attendance_time')->count() + $unregisteredScans->count();
        $successCount = $attendances->where('status', 'hadir')->count();
        $failedCount = $attendances->whereIn('status', ['alpa', 'izin', 'sakit'])->count();
        $unknownCount = $unregisteredScans->count();

        $devicesList = NfcDevice::orderBy('name')->get();
        foreach ($devicesList as $device) {
            $unregisteredCount = ScanAttempt::where('device_id', $device->id)
                ->whereDate('scanned_at', $today)
                ->where('status', 'unregistered')
                ->count();

            $attendanceCount = Attendance::where('device_id', $device->id)
                ->whereDate('attendance_date', $today)
                ->count();

            $device->scan_today = $unregisteredCount + $attendanceCount;

            // Tentukan status berdasarkan last_seen_at
            $now = Carbon::now();
            $lastSeen = $device->last_seen_at;

            if ($lastSeen && $now->diffInMinutes($lastSeen) <= 5) {
                // Perangkat terlihat dalam 5 menit terakhir = online
                $device->status = 'online';
            } elseif ($lastSeen && $now->diffInMinutes($lastSeen) <= 15) {
                // Perangkat terlihat 5-15 menit lalu = idle
                $device->status = 'idle';
            } else {
                // Perangkat tidak terlihat lebih dari 15 menit = offline
                $device->status = 'offline';
            }
        }

        $devices = $includeDevicesAsModels
            ? $devicesList
            : $devicesList->map(fn ($device) => [
                'id' => $device->id,
                'name' => $device->name,
                'status' => $device->status,
                'scan_today' => $device->scan_today,
            ]);

        return [
            'events' => $events,
            'totalScans' => $totalScans,
            'successCount' => $successCount,
            'failedCount' => $failedCount,
            'unknownCount' => $unknownCount,
            'devices' => $devices,
        ];
    }
}
