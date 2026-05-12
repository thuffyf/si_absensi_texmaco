<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = Teacher::pluck('id', 'nip');

        $schedules = [
            [
                'teacher_nip' => '196812251992031003',
                'class_name' => 'XII IPA 1',
                'subject' => 'Matematika',
                'day_of_week' => 'Senin',
                'start_time' => '07:00',
                'end_time' => '08:30',
                'total_students' => 42,
                'status' => 'aktif',
            ],
            [
                'teacher_nip' => '197503141998032001',
                'class_name' => 'XII IPA 2',
                'subject' => 'Fisika',
                'day_of_week' => 'Senin',
                'start_time' => '08:45',
                'end_time' => '10:15',
                'total_students' => 40,
                'status' => 'aktif',
            ],
            [
                'teacher_nip' => '197001151995031001',
                'class_name' => 'XII IPS 1',
                'subject' => 'Sejarah',
                'day_of_week' => 'Senin',
                'start_time' => '10:30',
                'end_time' => '12:00',
                'total_students' => 38,
                'status' => 'idle',
            ],
            [
                'teacher_nip' => '197806231999031001',
                'class_name' => 'XI IPA 1',
                'subject' => 'Kimia',
                'day_of_week' => 'Selasa',
                'start_time' => '07:00',
                'end_time' => '08:30',
                'total_students' => 41,
                'status' => 'aktif',
            ],
            [
                'teacher_nip' => '198203241998032001',
                'class_name' => 'XII IPA 1',
                'subject' => 'Biologi',
                'day_of_week' => 'Selasa',
                'start_time' => '08:45',
                'end_time' => '10:15',
                'total_students' => 41,
                'status' => 'aktif',
            ],
        ];

        foreach ($schedules as $schedule) {
            $teacherId = $teachers->get($schedule['teacher_nip']);
            if (!$teacherId) {
                continue;
            }

            Schedule::updateOrCreate(
                [
                    'teacher_id' => $teacherId,
                    'class_name' => $schedule['class_name'],
                    'day_of_week' => $schedule['day_of_week'],
                    'start_time' => $schedule['start_time'],
                ],
                [
                    'teacher_id' => $teacherId,
                    'class_name' => $schedule['class_name'],
                    'subject' => $schedule['subject'],
                    'day_of_week' => $schedule['day_of_week'],
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                    'total_students' => $schedule['total_students'],
                    'status' => $schedule['status'],
                ]
            );
        }
    }
}
