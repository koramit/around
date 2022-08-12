<?php

namespace App\Notifications;

use App\Models\User;
use App\Notifications\Channels\LINEChannel;
use App\Notifications\Messages\LINEMessage;
use Illuminate\Notifications\Notification;

class MessagingApp extends Notification
{
    public function __construct(
        protected string $message,
    ) {
    }

    public function via(mixed $notifiable): array|string
    {
        if (! $notifiable instanceof User) {
            return [];
        }

        return [LINEChannel::class];
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function toLINE(mixed $notifiable): LINEMessage
    {
        return (new LINEMessage())->text($this->message);
    }
}
