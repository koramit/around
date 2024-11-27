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
        $schedule->job(new NotifyDiscussionUpdates)->everyTenMinutes();

        /* Acute Hemodialysis */
        $schedule->command('acute-hd:dismiss-case')->timezone('Asia/Bangkok')->at('00:01');
        $schedule->command('acute-hd:assign-an')->timezone('Asia/Bangkok')->at('11:00');
        $schedule->command('acute-hd:remind-incomplete-case notify')->timezone('Asia/Bangkok')->at('13:00');
        $schedule->command('acute-hd:remind-incomplete-case report')->timezone('Asia/Bangkok')->at('18:00');
        $schedule->job(new NotifyIncompleteOrderToAuthor)->timezone('Asia/Bangkok')->at('20:00');
        $schedule->job(new NotifyIncompleteOrderToAuthor)->timezone('Asia/Bangkok')->at('20:30');
        $schedule->command('acute-hd:assign-an')->timezone('Asia/Bangkok')->at('23:32');
        /* @TODO auto unsubscribe from inactive channel */
        /* @TODO refactor command as Job interface */

        /* Admission */
        $schedule->command('admission:update')->timezone('Asia/Bangkok')->at('06:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
