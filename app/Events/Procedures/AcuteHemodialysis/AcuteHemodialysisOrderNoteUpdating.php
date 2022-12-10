<?php

namespace App\Events\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
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
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
