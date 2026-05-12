<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\NfcDevice;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::orderBy('nim')->get();
        $deviceId = NfcDevice::orderBy('id')->value('id');
        $scheduleId = Schedule::orderBy('id')->value('id');
        $today = Carbon::today();

        $statusRotation = ['hadir', 'hadir', 'hadir', 'izin', 'sakit', 'alpha'];

        foreach ($students as $index => $student) {
            $status = $statusRotation[$index % count($statusRotation)];

            Attendance::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'attendance_date' => $today->toDateString(),
                ],
                [
                    'device_id' => $deviceId,
                    'schedule_id' => $scheduleId,
                    'attendance_time' => $status === 'hadir' ? '07:30:00' : null,
                    'status' => $status,
                    'note' => $status === 'alpha' ? 'Tidak hadir tanpa keterangan' : null,
                ]
            );
        }
    }
}
