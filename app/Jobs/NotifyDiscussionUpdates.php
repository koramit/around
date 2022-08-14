<?php

namespace App\Jobs;

use App\Models\Comment;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\DiscussionUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyDiscussionUpdates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $notifications = Comment::query()
            ->select(['commentable_type', 'commentable_id', 'commentator_id'])
            ->whereBetween('created_at', [now()->addMinutes(-15), now()])
            ->distinct()
            ->get()
            ->map(function ($c) {
                $sub = Subscription::query()
                    ->where('subscribable_type', $c->commentable_type)
                    ->where('subscribable_id', $c->commentable_id)
                    ->first();

                $subscribers = $sub->subscribers()
                     ->where('preferences->mute', false)
                     ->where('subscriber_id', '<>', $c->commentator_id)
                     ->pluck('id');

                $channel = $sub->subscribable;

                $message = ! empty($channel->meta)
                    ? "มีข้อความใหม่ใน discussion เคส {$channel->meta['name']}\n$channel->discussion_route"
                    : 'มีข้อความใหม่ใน discussion เคส error 😅';

                return [
                    'subscribers' => $subscribers,
                    'message' => $message,
                ];
            });

        $messageUser = [];
        $allUserIds = [];
        foreach ($notifications as $notification) {
            foreach ($notification['subscribers'] as $userId) {
                $allUserIds[] = $userId;
                $messageUser[$userId][] = $notification['message'];
            }
        }

        User::query()
            ->select('id')
            ->with('activeLINEProfile')
            ->withActiveChatBots()
            ->whereIn('id', $allUserIds)
            ->get()
            ->each(function ($u) use ($messageUser) {
                $merged = collect($messageUser[$u->id])->unique()->values()->join("\n\n");
                $u->notify(new DiscussionUpdate($merged));
            });
    }
}
