<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class ScheduleTestSeeder extends Seeder
{
    public function run(): void
    {
        // Get some teachers
        $teachers = Teacher::take(3)->get();

        if ($teachers->isEmpty()) {
            echo "No teachers found. Please run TeacherSeeder first.\n";
            return;
        }

        // Sample schedules for X TEI on Kamis (Thursday)
        $schedules = [
            [
                'teacher_id' => $teachers[0]->id,
                'class_name' => 'X TEI',
                'subject' => 'PKN',
                'day_of_week' => 'Kamis',
                'start_time' => '07:00',
                'end_time' => '08:00',
                'total_students' => 30,
                'status' => 'aktif',
            ],
            [
                'teacher_id' => $teachers[1]->id,
                'class_name' => 'X TEI',
                'subject' => 'MTK',
                'day_of_week' => 'Kamis',
                'start_time' => '08:00',
                'end_time' => '09:00',
                'total_students' => 30,
                'status' => 'aktif',
            ],
            [
                'teacher_id' => $teachers[2]->id,
                'class_name' => 'X TEI',
                'subject' => 'IPA',
                'day_of_week' => 'Kamis',
                'start_time' => '09:00',
                'end_time' => '10:00',
                'total_students' => 30,
                'status' => 'aktif',
            ],
        ];

        foreach ($schedules as $schedule) {
            Schedule::firstOrCreate(
                [
                    'class_name' => $schedule['class_name'],
                    'subject' => $schedule['subject'],
                    'day_of_week' => $schedule['day_of_week'],
                ],
                $schedule
            );
        }
    }
}
