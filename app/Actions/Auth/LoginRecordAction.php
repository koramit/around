<?php

namespace App\Actions\Auth;

use App\Models\LoginRecord;
use App\Models\User;
use Jenssegers\Agent\Agent;

class LoginRecordAction
{
    public function __invoke(?string $ip, Agent $agent, User $user)
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        if ($agent->isMobile()) {
            $type = 1;
        } elseif ($agent->isTablet()) {
            $type = 2;
        } elseif ($agent->isDesktop()) {
            $type = 3;
        } else {
            $type = 0;
        }

        return LoginRecord::query()
            ->create([
                'ip_address' => $ip,
                'device' => $agent->device(),
                'type' => $type,
                'browser' => $agent->browser(),
                'browser_version' => $agent->version($agent->browser()),
                'platform' => $agent->platform(),
                'platform_version' => $agent->version($agent->platform()),
                'robot' => $agent->isRobot() ? $agent->robot() : null,
                'user_id' => $user->id,
            ]);
    }
}
