<?php

namespace App\Traits;

use App\Models\EventBasedNotification;
use App\Models\Subscription;

trait EventBasedNotifiable
{
    private function notifySubscribers(int $eventId, mixed $resource): void
    {
        // subscribers
        if (! $sub = Subscription::query()
            ->where('subscribable_type', EventBasedNotification::class)
            ->where('subscribable_id', $eventId)
            ->whereHas('subscribers')
            ->first()
        ) {
            return;
        }

        /** @var EventBasedNotification $subscribable */
        $subscribable = $sub->subscribable;
        $notification = new $subscribable->notification_class_name($resource);

        $sub->subscribers()
            ->where('preferences->mute', false)
            ->get()
            ->each(function ($user) use ($notification) {
                $user->notify($notification);
            });
    }
}
