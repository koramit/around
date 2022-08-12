<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessNotificationQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $notifications = collect(cache()->pull('notification-queue', []));
        if ($notifications->count() === 0) {
            return;
        }

        $notifications->each(function ($n) {
            User::query()
                ->whereIn('id', $n['subscribers'])
                ->get()
                ->each(fn ($u) => $u->notify($n['notification']));
        });
    }
}
