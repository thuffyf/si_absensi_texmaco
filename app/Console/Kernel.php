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
        // Mark students as alpa if they haven't scanned by 8:00 AM
        $schedule->command('app:mark-absent-students')
            ->dailyAt('08:00')
            ->timezone('Asia/Jakarta')
            ->description('Mark absent students as alpa at 8:00 AM');
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
