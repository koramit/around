<?php

namespace App\Listeners;

use App\Events\Procedures\AcuteHemodialysis\AcuteHemodialysisOrderNoteUpdating;
use App\Jobs\AutoUnsubscribeToChannel;

class HandleChangeToInactiveStatus
{
    public function handle(AcuteHemodialysisOrderNoteUpdating $event): void
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

        AutoUnsubscribeToChannel::dispatchAfterResponse($event->subscribable->subscription);
    }
}
