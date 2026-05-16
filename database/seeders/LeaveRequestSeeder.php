<?php

namespace Database\Seeders;

use App\Models\LeaveRequest;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class LeaveRequestSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::pluck('id', 'nis');
        $now = Carbon::now();

        $requests = [
            [
            'nis' => '12001',
                'type' => 'izin',
                'start_date' => $now->copy()->subDays(1)->toDateString(),
                'end_date' => $now->copy()->toDateString(),
                'reason' => 'Mengikuti acara lomba tingkat kabupaten.',
                'status' => 'pending',
                'requested_at' => $now->copy()->subHours(6),
            ],
            [
                'nis' => '12002',
                'type' => 'sakit',
                'start_date' => $now->copy()->toDateString(),
                'end_date' => $now->copy()->toDateString(),
                'reason' => 'Demam tinggi dan batuk, perlu istirahat.',
                'status' => 'pending',
                'requested_at' => $now->copy()->subHours(4),
            ],
            [
                'nis' => '12004',
                'type' => 'izin',
                'start_date' => $now->copy()->subDays(3)->toDateString(),
                'end_date' => $now->copy()->subDays(2)->toDateString(),
                'reason' => 'Mengikuti acara keagamaan.',
                'status' => 'approved',
                'requested_at' => $now->copy()->subDays(4),
                'responded_at' => $now->copy()->subDays(3),
            ],
            [
                'nis' => '12005',
                'type' => 'sakit',
                'start_date' => $now->copy()->subDays(5)->toDateString(),
                'end_date' => $now->copy()->subDays(5)->toDateString(),
                'reason' => 'Sakit dan perlu istirahat di rumah.',
                'status' => 'approved',
                'requested_at' => $now->copy()->subDays(6),
                'responded_at' => $now->copy()->subDays(5),
            ],
            [
                'nis' => '12003',
                'type' => 'izin',
                'start_date' => $now->copy()->subDays(2)->toDateString(),
                'end_date' => $now->copy()->subDays(2)->toDateString(),
                'reason' => 'Pulang awal karena keperluan keluarga.',
                'status' => 'rejected',
                'requested_at' => $now->copy()->subDays(2),
                'responded_at' => $now->copy()->subDays(2)->addHours(2),
            ],
        ];

        foreach ($requests as $request) {
            $studentId = $students->get($request['nis']);
            if (!$studentId) {
                continue;
            }

            LeaveRequest::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'type' => $request['type'],
                    'start_date' => $request['start_date'],
                ],
                [
                    'student_id' => $studentId,
                    'type' => $request['type'],
                    'start_date' => $request['start_date'],
                    'end_date' => $request['end_date'],
                    'reason' => $request['reason'],
                    'status' => $request['status'],
                    'requested_at' => $request['requested_at'],
                    'responded_at' => $request['responded_at'] ?? null,
                ]
            );
        }
    }
}
