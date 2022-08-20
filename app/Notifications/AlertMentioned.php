<?php

namespace App\Notifications;

use App\Traits\SocialAppMessagable;
use Illuminate\Notifications\Notification;

class AlertMentioned extends Notification implements \App\Contracts\MessagingApp
{
    use SocialAppMessagable;

    public function __construct(mixed $channel)
    {
        $this->message = "มีข้อความถึงคุณใน discussion เคส {$channel->meta['name']}";
        $this->magicLink = $channel->discussion_route;
    }
}
