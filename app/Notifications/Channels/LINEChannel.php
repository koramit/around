<?php

namespace App\Notifications\Channels;

use App\Notifications\Messages\LINEMessage;
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
        /** @var LINEMessage $message */
        $message = $notification->toLINE($notifiable);

        $chatLog = $notifiable->chatLogs()
            ->where('mode', 2) // read
            ->where('created_at', '>=', now()->addDays(-1))
            ->first();

        if ($chatLog && isset($chatLog->payload['events'][0]['replyToken'])) {
            $replyToken = $chatLog->payload['events'][0]['replyToken'];
            $payload = $this->replyMessage($bot, $replyToken, $message->getMessages());
            $mode = 'reply';
        } else {
            if (! $profile = $notifiable->socialProfiles()
                ->activeLoginByProviderId(1)
                ->first()) {
                return;
            }
            $payload = $this->pushMessage($bot, $profile->profile_id, $message->getMessages());
            $mode = 'push';
        }

        $this->log($notifiable->id, $bot->id, $payload, $mode);
    }
}
