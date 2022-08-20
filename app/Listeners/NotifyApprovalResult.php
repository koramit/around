<?php

namespace App\Listeners;

use App\Events\DocumentChangeRequestUpdating;
use App\Jobs\NotifyRequestApprovalResult;

class NotifyApprovalResult
{
    public function handle(DocumentChangeRequestUpdating $event): void
    {
        $old = $event->changeRequest->getOriginal();
        $new = $event->changeRequest->getAttributes();

        // status [2,3] -> [2 => 'approved', 3 => 'disapproved']
        $interestedStatuses = collect(['approved', 'disapproved']);
        if (
            $new['status'] === $old['status']
            || ! $interestedStatuses->contains($event->changeRequest->status)
        ) {
            return;
        }

        NotifyRequestApprovalResult::dispatchAfterResponse($event->changeRequest);
    }
}
