<?php

namespace App\Listeners;

use App\Events\DocumentChangeRequestCreated;
use App\Jobs\NotifyNewRequestToSubscribers;

class NotifyNewRequest
{
    protected array $changeableEventIds = [
        'App\Models\Notes\AcuteHemodialysisOrderNote' => 3,
    ];

    public function handle(DocumentChangeRequestCreated $event): void
    {
        if (! $eventId = $this->changeableEventIds[$event->changeRequest->changeable_type] ?? null) {
            return;
        }

        NotifyNewRequestToSubscribers::dispatchAfterResponse($event->changeRequest->changeable, $eventId);
    }
}
