<?php

namespace App\Actions\Auth;

use App\Models\LoginRecord;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;

class LoginRecordAction
{
    public function __invoke(?string $ip, Agent $agent, User $user, string $provider = 'ad', ?int $daysBeforePasswordExpired = null): void
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return; // call api
        }

        if ($daysBeforePasswordExpired) {
            $user->update([
                'profile->password_expiration_date' => now()->addDays($daysBeforePasswordExpired + 1),
            ]);
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

        $loginRecord = LoginRecord::query()
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
                'provider' => $provider,
            ]);

       Session::put(['login_record_id' => $loginRecord->id]);
    }
}
