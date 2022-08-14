<?php

namespace App\Notifications;

class DiscussionUpdate extends MessagingApp
{
    public function __construct(string $message)
    {
        $this->message = $message;
    }
}
