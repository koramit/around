<?php

namespace App\Actions\Auth;

use App\Models\LoginRecord;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class LogoutRecordAction
{
    public function __invoke(User $user)
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        if ($loginRecordId = Session::get('login_record_id')) {
            LoginRecord::query()->find($loginRecordId)->touch();
        }
    }
}
