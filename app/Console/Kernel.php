<?php

namespace App\Console;

use App\Jobs\NotifyDiscussionUpdates;
use App\Jobs\Procedures\AcuteHemodialysis\NotifyIncompleteOrderToAuthor;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new NotifyDiscussionUpdates())->everyTenMinutes();

        /* Acute Hemodialysis */
        $schedule->command('acute-hd:assign-an')->timezone('Asia/Bangkok')->at('13:00');
        $schedule->job(new NotifyIncompleteOrderToAuthor())->timezone('Asia/Bangkok')->at('20:00');
        $schedule->job(new NotifyIncompleteOrderToAuthor())->timezone('Asia/Bangkok')->at('20:30');
        /** unsubscribe from inactive channel */
        // @TODO auto archived/expired acute HD case/order
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
