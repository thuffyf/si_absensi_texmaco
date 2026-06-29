<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class MarkAbsentStudents extends Command
{
    protected $signature = 'app:mark-absent-students {--date= : Tanggal target (Y-m-d), default kemarin}';

    protected $description = 'Tandai siswa yang tidak hadir, izin, atau sakit pada tanggal tertentu sebagai alpa.';

    public function handle(): int
    {
        // Default: tandai kemarin (karena command jalan jam 08:00 pagi hari ini,
        // berarti hari kemarin sudah selesai dan bisa dihitung final)
        $targetDateStr = $this->option('date')
            ? Carbon::parse($this->option('date'))->toDateString()
            : Carbon::yesterday('Asia/Jakarta')->toDateString();

        $targetDate = Carbon::parse($targetDateStr);

        // Jangan proses hari ini atau masa depan
        if ($targetDate->isSameDay(Carbon::today('Asia/Jakarta')) || $targetDate->isFuture()) {
            $this->warn('Tanggal target tidak boleh hari ini atau masa depan.');
            return self::FAILURE;
        }

        $this->info("Memproses alpa untuk tanggal: {$targetDateStr}");

        // Ambil semua siswa aktif
        $students = Student::where('status', 'aktif')->get();

        // Ambil semua attendance di tanggal tersebut (index by student_id)
        $existingAttendances = Attendance::whereDate('attendance_date', $targetDate)
            ->pluck('status', 'student_id');

        $marked = 0;
        $skipped = 0;

        foreach ($students as $student) {
            // Jika sudah ada record (hadir/izin/sakit/alpa) -> skip
            if ($existingAttendances->has($student->id)) {
                $skipped++;
                continue;
            }

            // Tidak ada record sama sekali -> tandai alpa
            Attendance::create([
                'student_id'      => $student->id,
                'attendance_date' => $targetDateStr,
                'attendance_time' => '00:00:00',
                'status'          => 'alpa',
                'note'            => 'Otomatis: tidak ada catatan kehadiran.',
            ]);

            $marked++;
        }

        $this->info("Selesai. Ditandai alpa: {$marked} siswa. Dilewati: {$skipped} siswa.");

        return self::SUCCESS;
    }
}
