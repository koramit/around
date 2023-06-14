<?php

namespace App\Events\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcuteHemodialysisOrderNoteUpdating
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public AcuteHemodialysisOrderNote $subscribable;

    public function __construct(AcuteHemodialysisOrderNote $subscribable)
    {
        $this->subscribable = $subscribable;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel|PrivateChannel|array
    {
        return new PrivateChannel('channel-name');
    }
}
