<?php

namespace App\Jobs;

use App\Traits\EventBasedNotifiable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyNewRequestToSubscribers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, EventBasedNotifiable;

    protected mixed $changeable;

    protected int $eventId;

    public function __construct(mixed $changeable, int $eventId)
    {
        $this->changeable = $changeable;
        $this->eventId = $eventId;
    }

    public function handle(): void
    {
        $this->notifySubscribers($this->eventId, $this->changeable);
    }
}
