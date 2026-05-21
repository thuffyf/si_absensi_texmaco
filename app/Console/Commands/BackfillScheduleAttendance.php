<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Schedule;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class BackfillScheduleAttendance extends Command
{
    protected $signature = 'attendance:backfill-schedule {--from=} {--until=} {--dry-run}';

    protected $description = 'Backfill schedule_id for attendance records based on class, day, and time window.';

    public function handle(): int
    {
        $from = $this->option('from');
        $until = $this->option('until');

        $fromDate = $from ? Carbon::parse($from)->toDateString() : Carbon::now()->toDateString();
        $untilDate = $until ? Carbon::parse($until)->toDateString() : $fromDate;

        if (Carbon::parse($fromDate)->gt(Carbon::parse($untilDate))) {
            $this->error('Tanggal mulai harus <= tanggal akhir.');
            return self::FAILURE;
        }

        $records = Attendance::query()
            ->whereNull('schedule_id')
            ->whereBetween('attendance_date', [$fromDate, $untilDate])
            ->whereNotNull('attendance_time')
            ->with('student')
            ->orderBy('attendance_date')
            ->orderBy('attendance_time')
            ->get();

        if ($records->isEmpty()) {
            $this->info('Tidak ada data absensi yang perlu di-backfill.');
            return self::SUCCESS;
        }

        $dayNames = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        $matched = 0;
        $skipped = 0;
        $dryRun = (bool) $this->option('dry-run');

        foreach ($records as $record) {
            $student = $record->student;
            if (!$student || empty($student->class_name)) {
                $skipped++;
                continue;
            }

            $recordDate = Carbon::parse((string) $record->attendance_date);
            $dayName = $dayNames[$recordDate->format('l')] ?? $recordDate->format('l');
            $attendanceTime = Carbon::parse(
                $recordDate->format('Y-m-d') . ' ' . (string) $record->attendance_time
            );

            $schedule = Schedule::query()
                ->where('class_name', $student->class_name)
                ->where('day_of_week', $dayName)
                ->where('status', 'aktif')
                ->orderBy('start_time')
                ->get()
                ->first(function (Schedule $item) use ($attendanceTime, $recordDate) {
                    $startTime = $item->start_time instanceof Carbon
                        ? $item->start_time->copy()
                        : Carbon::parse((string) $item->start_time);
                    $endTime = $item->end_time instanceof Carbon
                        ? $item->end_time->copy()
                        : Carbon::parse((string) $item->end_time);

                    $startTime->setDate($recordDate->year, $recordDate->month, $recordDate->day);
                    $endTime->setDate($recordDate->year, $recordDate->month, $recordDate->day);

                    return $attendanceTime->between($startTime, $endTime, true);
                });

            if (!$schedule) {
                $skipped++;
                continue;
            }

            if (!$dryRun) {
                $record->schedule_id = $schedule->id;
                $record->save();
            }

            $matched++;
        }

        $this->line("Matched: {$matched}");
        $this->line("Skipped: {$skipped}");

        if ($dryRun) {
            $this->info('Dry run: tidak ada perubahan yang disimpan.');
        }

        return self::SUCCESS;
    }
}
