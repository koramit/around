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
        $events = collect(cache()->pull('notification-queue', []));
        if ($events->count() === 0) {
            return;
        }

        $events->each(function ($event) {
            User::query()
                ->whereIn('id', $event['subscribers'])
                ->get()
                ->each(fn ($user) => $user->notify($event['notification']));
        });
    }
}
