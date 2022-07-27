<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\User;

class CreateOrderShortcutAction
{
    public function __invoke(string $hashedKey, User $user): bool
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return true; // call api
        }

        cache()->put("acute-hemodialysis-create-order-shortcut-session-$user->id", $hashedKey, now()->addMinutes(config('session.lifetime')));

        return true;
    }
}
