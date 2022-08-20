<?php

namespace App\Notifications;

use App\Contracts\MessagingApp as MessagingAppInterface;
use App\Traits\SocialAppMessagable;
use Illuminate\Notifications\Notification;

class MessagingApp extends Notification implements MessagingAppInterface
{
    use SocialAppMessagable;

    public function __construct(string $message, ?string $magicLink = null)
    {
        $this->message = $message;
        $this->magicLink = $magicLink;
    }
}
