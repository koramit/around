<?php

namespace App\Events\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Subscription;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcuteHemodialysisOrderNoteUpdating
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Subscription $subscription;

    public AcuteHemodialysisOrderNote $subscribable;

    public function __construct(AcuteHemodialysisOrderNote $subscribable)
    {
        $this->subscribable = $subscribable;
        $this->subscription = Subscription::query()
            ->where('subscribable_type', $subscribable::class)
            ->where('subscribable_id', $subscribable->id)
            ->first();
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
