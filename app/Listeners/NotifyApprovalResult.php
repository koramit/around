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
        if ($new['status'] === $old['status'] || ! collect([2, 3])->contains($new['status'])) {
            return;
        }

        NotifyRequestApprovalResult::dispatchAfterResponse($event->changeRequest);
    }
}
