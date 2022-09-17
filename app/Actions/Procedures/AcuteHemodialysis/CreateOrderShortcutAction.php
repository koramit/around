<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Traits\AvatarLinkable;

class CreateOrderShortcutAction
{
    use AvatarLinkable;

    public function __invoke(string $hashedKey, mixed $user): bool
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        cache()->put("acute-hemodialysis-create-order-shortcut-session-$user->id", $hashedKey, now()->addMinutes(config('session.lifetime')));

        return true;
    }
}
