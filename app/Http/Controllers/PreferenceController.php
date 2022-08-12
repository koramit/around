<?php

namespace App\Http\Controllers;

use App\Actions\User\PreferencesUpdateAction;
use App\Models\ChatBot;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PreferenceController extends Controller
{
    public function show(Request $request)
    {
        session()->flash('page-title', __('Preferences'));
        session()->flash('main-menu-links', collect([
            ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => $request->user()->can('view_any_patients')],
            ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => $request->user()->can('view_any_patients')],
            ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures.index'), 'can' => $request->user()->can('view_any_patients')],
        ])->filter(fn ($link) => $link['can'])->values());
        session()->flash('action-menu', []);

        $user = $request->user();

        // LINE config
        // for now all user share the same LINE provider
        $lineProfile = $user->activeLINEProfile();
        $lineLinked = (bool) $lineProfile;
        $addFriendLink = null;
        $lineBotActive = false;
        if ($lineLinked) {
            if (isset($user->profile['line_bot_id'])) { // bot was assigned
                // get active
                $bot = ChatBot::query()->findByUnhashKey($user->profile['line_bot_id'])->first();
                $lineBotActive = (bool) $user->activeLINEBot($lineProfile); // unfollowed
            } else { // rotate and assign bot to user
                $bot = ChatBot::query()->minUserCountByProviderId($lineProfile->social_provider_id)->first();
                if ($bot) { // make sure there is bot available
                    $bot->update(['user_count' => $bot->user_count + 1]);
                    $user->update(['profile->line_bot_id' => $bot->hashed_key]);
                }
            }
            $addFriendLink = $bot ? $bot->configs['add_friend_base_url'].$bot->configs['basic_id'] : null;
        }

        return Inertia::render('User/PreferencePage')->with([
            'configs' => [
                'can' => [
                    'link_line' => ! $lineLinked,
                    'add_line' => $addFriendLink !== null,
                ],
                'routes' => [
                    'link_line' => $lineLinked ? route('social-link.create', $lineProfile->socialProvider->hashed_key) : null,
                    'add_line' => $addFriendLink,
                ],
                'friends' => [
                    'line' => $lineBotActive,
                ],
            ],
        ]);
    }

    public function update(Request $request)
    {
        return (new PreferencesUpdateAction)($request->all(), $request->user());
    }

    // event based notification
    protected function getEventBasedNotification()
    {
        $notifications = [

        ];
    }
}
