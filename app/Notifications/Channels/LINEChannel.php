<?php

namespace App\Notifications\Channels;

use App\Traits\LINECallable;
use Illuminate\Notifications\Notification;

class LINEChannel
{
    use LINECallable;

    /** @TODO optimize query count */
    public function send(mixed $notifiable, Notification $notification): void
    {
        if (! $bot = $notifiable->chatBots()->wherePivot('active', true)->first()) {
            return;
        }

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $message = $notification->toLINE($notifiable);

        if (! $profile = $notifiable->socialProfiles()
            ->activeLoginByProviderId(1)
            ->first()) {
            return;
        }

        $payload = $this->pushMessage($bot, $profile->profile_id, $message->getMessages());
        $this->log($notifiable->id, $bot->id, $payload, 'push');
    }
}
