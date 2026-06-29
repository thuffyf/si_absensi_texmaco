<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Tandai siswa alpa: jalankan jam 00:05 dini hari untuk memproses kemarin
        $schedule->command('app:mark-absent-students')
            ->dailyAt('00:05')
            ->timezone('Asia/Jakarta')
            ->description('Tandai siswa alpa otomatis setiap tengah malam');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
