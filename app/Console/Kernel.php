<?php

namespace App\Console;

use App\Jobs\NotifyDiscussionUpdates;
use App\Jobs\ProcessNotificationQueue;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->job(new ProcessNotificationQueue())->everyMinute();
        $schedule->job(new NotifyDiscussionUpdates())->everyTenMinutes();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
