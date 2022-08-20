<?php

namespace App\Contracts;

use App\Notifications\Messages\LINEMessage;

interface MessagingApp
{
    public function via(mixed $notifiable): array|string;

    public function toLINE(mixed $notifiable): LINEMessage;
}
