<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\NfcDevice;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MobileAttendanceController extends Controller
{
    public function tap(Request $request)
    {
        $data = $request->validate([
            'uid_kartu' => 'required|string',
            'device_id' => 'nullable|exists:nfc_devices,id',
            'status' => 'nullable|in:hadir,izin,sakit,alpha,late',
            'note' => 'nullable|string|max:255',
        ]);

        $student = Student::where('uid_kartu', $data['uid_kartu'])->first();
        if (!$student) {
            return response()->json(['message' => 'UID tidak terdaftar.'], 404);
        }

        $now = Carbon::now();
        $dayNames = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $todayDayName = $dayNames[$now->format('l')] ?? $now->format('l');
        $schedule = Schedule::query()
            ->where('class_name', $student->class_name)
            ->where('day_of_week', $todayDayName)
            ->where('status', 'aktif')
            ->orderBy('start_time')
            ->get()
            ->first(function (Schedule $item) use ($now) {
                $startTime = $item->start_time instanceof Carbon
                    ? $item->start_time->copy()
                    : Carbon::parse((string) $item->start_time);
                $endTime = $item->end_time instanceof Carbon
                    ? $item->end_time->copy()
                    : Carbon::parse((string) $item->end_time);
                $startTime->setDate($now->year, $now->month, $now->day);
                $endTime->setDate($now->year, $now->month, $now->day);
                return $now->between($startTime, $endTime, true);
            });
        $status = $data['status'] ?? 'hadir';

        $attendance = Attendance::create([
            'student_id' => $student->id,
            'device_id' => $data['device_id'] ?? null,
            'schedule_id' => $schedule?->id,
            'attendance_date' => $now->toDateString(),
            'attendance_time' => $now->format('H:i:s'),
            'status' => $status,
            'note' => $data['note'] ?? null,
        ]);

        if (!empty($data['device_id'])) {
            $device = NfcDevice::find($data['device_id']);
            if ($device) {
                $device->update([
                    'last_seen_at' => $now,
                    'last_scan_at' => $now,
                    'scan_today' => ($device->scan_today ?? 0) + 1,
                ]);
            }
        }

        return response()->json([
            'message' => 'Absensi tercatat.',
            'attendance_id' => $attendance->id,
            'student' => [
                'name' => $student->name,
                'nis' => $student->nis,
                'class_name' => $student->class_name,
            ],
            'status' => $attendance->status,
            'time' => $attendance->attendance_time,
        ]);
    }
}
