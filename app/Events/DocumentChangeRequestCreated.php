<?php

namespace App\Events;

use App\Models\DocumentChangeRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentChangeRequestCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public DocumentChangeRequest $changeRequest,
    ) {}

    public function broadcastOn(): Channel|array
    {
        return new PrivateChannel('channel-name');
    }
}
