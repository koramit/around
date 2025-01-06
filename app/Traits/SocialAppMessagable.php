<?php

namespace App\Traits;

use App\Models\User;
use App\Notifications\Channels\LINEChannel;
use App\Notifications\Messages\LINEMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

trait SocialAppMessagable
{
    protected string $message;

    protected ?string $magicLink;

    public function via(mixed $notifiable): array|string
    {
        if (! $notifiable instanceof User) {
            return [];
        }

        return [LINEChannel::class];
    }

    public function toLINE(mixed $notifiable): LINEMessage
    {
        if (! $this->magicLink) {
            return (new LINEMessage)->text($this->message);
        }

        $token = Str::random(32);
        $until = now()->addMinutes(15);
        cache()->put('magic-link-token-'.$token, $this->magicLink, $until);
        $signedUrl = URL::temporarySignedRoute('magic-link', $until, [
            'user' => $notifiable->hashed_key,
            'token' => $token,
        ]);

        return (new LINEMessage)->text($this->message."\n\nlink หมดอายุภายใน 15 นาที\n\n".$signedUrl);
    }
}
