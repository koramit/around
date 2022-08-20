<?php

namespace App\Jobs;

use App\Models\DocumentChangeRequest;
use App\Notifications\MessagingApp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyRequestApprovalResult implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected DocumentChangeRequest $changeRequest,
    ) {
    }

    public function handle(): void
    {
        $requester = $this->changeRequest->requester;
        if ($requester->mute_notification || ! $requester->notify_approval_result) {
            return;
        }

        $message = "{$this->changeRequest->change_request_text} {$this->changeRequest->status} แล้ว";

        if ($this->changeRequest->status === 'disapproved') {
            $changeable = $this->changeRequest->changeable;
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            $reason = $changeable->actionLogs()
                ->where('action', 12) // disapprove
                ->first()
                ?->payload['reason'] ?? '😅';

            $message .= "เนื่องจาก $reason";
        }

        $requester->notify(new MessagingApp($message));
    }
}
