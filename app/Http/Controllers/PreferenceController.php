<?php

namespace App\Http\Controllers;

use App\Actions\User\PreferencesUpdateAction;
use App\APIs\LINELoginAPI;
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

        $providerId = $request->user()->profile['line_bot_service_provider_id'] ?? 0;
        $lineLinked = $request->user()->socialProfiles()->activeLineLogin()->count() > 0;
        if ($lineLinked) {
            $lineBotActive = $request->user()->chatBots()->filterByProviderId($providerId)->wherePivot('active', true)->count();
        } else {
            $lineBotActive = false;
        }

        if ($lineLinked && ! $lineBotActive) {
            $bot = ChatBot::query()->minUserCountByProviderId($providerId)->first(); // social_provider_id
            $addFriendLink = $bot ? $bot->configs['add_friend_base_url'].$bot->configs['basic_id'] : null;
        } else {
            $addFriendLink = null;
        }

        return Inertia::render('User/PreferencePage')->with([
            'configs' => [
                'can' => [
                    'link_line' => ! $lineLinked,
                    'add_line' => $lineLinked && ! $lineBotActive,
                ],
                'routes' => [
                    'link_line' => LINELoginAPI::getConfigs() ? route('social-link.create', 'line') : null,
                    'add_line' => $addFriendLink,
                ],
            ],
        ]);
    }

    public function update(Request $request)
    {
        return (new PreferencesUpdateAction)($request->all(), $request->user());
    }
}
