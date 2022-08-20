<?php

namespace App\Console;

use App\Jobs\NotifyDiscussionUpdates;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->job(new NotifyDiscussionUpdates())->everyTenMinutes();
        // notify incomplete acute HD order to author
        // unsubscribe from inactive channel
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
