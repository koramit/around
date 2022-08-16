<?php

namespace App\Jobs\Procedures\AcuteHemodialysis;

use App\Models\EventBasedNotification;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyDialysisStatusToAuthor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected AcuteHemodialysisOrderNote $order;

    protected EventBasedNotification $event;

    /** @TODO review id hardcode */
    public function __construct(AcuteHemodialysisOrderNote $order)
    {
        $this->order = $order;
        $this->event = cache()->rememberForever('event-based-notification-alert-session-updates', function () {
            return EventBasedNotification::query()->find(4);
        });
    }

    public function handle(): void
    {
        $author = $this->order->author;
        if ($author->mute_notification) {
            return;
        }

        if (
            $author->subscriptions()
                ->where('subscribable_type', EventBasedNotification::class)
                ->where('subscribable_id', $this->event->id)
                ->count() === 0
        ) {
            return;
        }

        $author->notify(new $this->event->notification_class_name($this->order));
    }
}
