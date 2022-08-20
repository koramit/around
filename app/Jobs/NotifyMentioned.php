<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\AlertMentioned;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyMentioned implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected User $mentionable,
        protected mixed $channel,
    ) {
    }

    public function handle(): void
    {
        $this->mentionable->notify(new AlertMentioned($this->channel));
    }
}
