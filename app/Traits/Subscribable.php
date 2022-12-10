<?php

namespace App\Traits;

use App\Models\Subscription;
use App\Models\User;

trait Subscribable
{
    protected function getSubscriptionConfig(mixed $channel, ?User $user = null): array
    {
        $subscription = Subscription::query()->firstOrCreate([
            'subscribable_type' => $channel::class,
            'subscribable_id' => $channel->id,
        ]);

        $subscribed = $subscription->subscribers()->where('id', $user?->id)->count() > 0;

        return [
            'subscription_id' => $subscription->hashed_key,
            'route' => route('subscriptions.store'),
            'subscribed' => $subscribed,
        ];
    }

    /* @TODO implement can */
    protected function getSubscriptionActionMenu(mixed $channel, ?User $user = null): array
    {
        $config = $this->getSubscriptionConfig($channel, $user);

        return [
            'as' => 'button',
            'icon' => $config['subscribed'] ? 'bell-slash' : 'bell',
            'name' => 'subscribe-clicked',
            'config' => $config,
            'label' => $config['subscribed'] ? 'Unsubscribe' : 'Subscribe',
            'can' => true,
        ];
    }

    protected function getCommentRoutes(mixed $model): array
    {
        return [
            'commentable_type' => $model::class,
            'commentable_id' => $model->hashed_key,
            'routes' => [
                'reply_index' => route('comments.reply-oriented.index'),
                'reply_store' => route('comments.reply-oriented.store'),
                'timeline_index' => route('comments.timeline-oriented.index'),
                'timeline_store' => route('comments.timeline-oriented.store'),
            ],
        ];
    }
}
