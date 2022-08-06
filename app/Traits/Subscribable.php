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
            'subscribable_id' => $channel->id
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
            'icon' => $config['subscribed'] ? 'bell-slash' : 'bell',
            'type' => 'subscribe-clicked',
            'action' => $config,
            'label' => $config['subscribed'] ? 'Unsubscribe' : 'Subscribe',
            'can' => true,
        ];
    }
}
