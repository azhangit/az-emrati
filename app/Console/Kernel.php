<?php

namespace App\Console;

use App\Jobs\RefreshInstagramAccessTokenJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // yahan custom command classes ho to add karte hain (ham closure use kar rahe hain)
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // pehle se existing job
        $schedule->command('send:scheduled-subscription-emails')->everyMinute();

        // NEW: instagram feed ko warm-up / refresh karne ka cron
        $schedule->command('refresh:instagram')->hourly();
        
        // Refresh Instagram Access Token daily
        $schedule->job(new RefreshInstagramAccessTokenJob())
            ->dailyAt('02:00')
            ->withoutOverlapping();

        // agar chaho to everyFifteenMinutes() bhi kar sakte ho
        // $schedule->command('refresh:instagram')->everyFifteenMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
