<?php

namespace App\Jobs;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoUnsubscribeToChannel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Subscription $subscription,
    ) {}

    public function handle(): void
    {
        $detachSubs = $this->subscription->subscribers()
            ->where('preferences->auto_unsubscribe_to_channel', true)
            ->pluck('id');

        $this->subscription->subscribers()->detach($detachSubs);
    }
}
