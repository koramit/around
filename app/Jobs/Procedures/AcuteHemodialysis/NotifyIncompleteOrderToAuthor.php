<?php

namespace App\Jobs\Procedures\AcuteHemodialysis;

use App\Casts\AcuteHemodialysisOrderStatus;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Notifications\Procedures\AcuteHemodialysis\AlertIncompleteOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyIncompleteOrderToAuthor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $tomorrow = today('Asia/Bangkok')->addDay();

        AcuteHemodialysisOrderNote::query()
            ->where('date_note', $tomorrow)
            ->where('status', (new AcuteHemodialysisOrderStatus())->getCode('draft'))
            ->get()
            ->each(function (AcuteHemodialysisOrderNote $order) {
                $order->author->notify(new AlertIncompleteOrder($order));
            });
    }
}
