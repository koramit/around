<?php

namespace App\Listeners;

use App\Jobs\AutoUnsubscribeToChannel;

class HandleChangeToInactiveStatus
{
    public function handle(mixed $event): void
    {
        $old = $event->subscribable->getOriginal();
        $new = $event->subscribable->getAttributes();

        $interestedStatuses = collect(['canceled', 'expired', 'archived', 'discharged']);
        if (
            $new['status'] === $old['status']
            || $interestedStatuses->doesntContain($event->subscribable->status)
        ) {
            return;
        }

        AutoUnsubscribeToChannel::dispatchAfterResponse($event->subscription);
    }
}
