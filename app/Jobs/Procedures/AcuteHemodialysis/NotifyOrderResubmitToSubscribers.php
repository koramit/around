<?php

namespace App\Jobs\Procedures\AcuteHemodialysis;

use App\Models\EventBasedNotification;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Resources\Registry;
use App\Traits\EventBasedNotifiable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyOrderResubmitToSubscribers implements ShouldQueue
{
    use Dispatchable, EventBasedNotifiable, InteractsWithQueue, Queueable, SerializesModels;

    protected AcuteHemodialysisOrderNote $order;

    protected EventBasedNotification $event;

    public function __construct(AcuteHemodialysisOrderNote $order)
    {
        $this->order = $order;
        $this->event = cache()->rememberForever('event-based-notification-acute-hd-alert-order-resubmit', function () {
            $registry = cache()->rememberForever('registry-acute-hd', fn () => Registry::query()->where('name', 'acute_hd')->first());

            return EventBasedNotification::query()
                ->where('name', 'alert_order_resubmit')
                ->where('registry_id', $registry->id)
                ->first();
        });
    }

    public function handle(): void
    {
        $this->notifySubscribers($this->event->id, $this->order);
    }
}
