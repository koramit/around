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
        if (! $profile = $notifiable->activeLINEProfile()) { // get user social profile id
            return;
        }

        if (! $bot = $notifiable->activeLINEBot($profile)) { // get bot token
            return;
        }

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $message = $notification->toLINE($notifiable);
        $payload = $this->pushMessage($bot, $profile->profile_id, $message->getMessages());
        $this->log($notifiable->id, $bot->id, $payload, 'push');
    }
}
