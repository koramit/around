<?php

namespace App\Actions\Auth;

use App\Models\LoginRecord;
use App\Models\User;

class LogoutRecordAction
{
    public function __invoke(User $user)
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        if (
            $login = LoginRecord::query()
                ->where('user_id', $user->id)
                ->latest()
                ->first()
        ) {
            $login->touch();
        }
    }
}
