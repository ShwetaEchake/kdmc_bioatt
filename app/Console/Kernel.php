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
        // $schedule->command('punches:import')->everyFifteenMinutes();
        $schedule->command('punches:medical-leave-entry')->dailyAt('01:00');
        $schedule->command('punches:calculate-duration')->everyFiveMinutes();
        $schedule->command('punches:set-latemark')->everyFifteenMinutes();
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
