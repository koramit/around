<?php

namespace App\Http\Controllers;

use App\Actions\User\PreferencesUpdateAction;
use App\Models\ChatBot;
use App\Models\EventBasedNotification;
use App\Models\SocialProvider;
use App\Models\User;
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
        $lineProvider = SocialProvider::query()->where('platform', 1)->first();
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
                    'link_line' => ! $lineLinked ? route('social-link.create', $lineProvider->hashed_key) : null,
                    'add_line' => $addFriendLink,
                ],
                'friends' => [
                    'line' => $lineBotActive,
                ],
                // 'event_based_notifications' => $this->getEventBasedNotifications($user),
                // 'subscribed_channels' => $this->getChannelBasedNotifications($user),
            ],
        ]);
    }

    public function update(Request $request)
    {
        return (new PreferencesUpdateAction)($request->all(), $request->user());
    }

    // event based notification
    protected function getEventBasedNotifications(User $user)
    {
        $subscribedEvents = $user->subscriptions()
            ->where('subscribable_type', EventBasedNotification::class)
            ->pluck('id');

        return EventBasedNotification::query()
            ->select(['id', 'name', 'locale', 'registry_id'])
            ->with('subscription:id,subscribable_type,subscribable_id')
            ->withRegistryName()
            ->whereIn('ability_id', $user->abilities_id)
            ->get()
            ->transform(fn ($e) => [
                'id' => $e->subscription->hashed_key,
                'label' => $e->locale['en']['label'],
                'subscribed' => $subscribedEvents->contains($e->id),
                'registry' => $e->registry_name,
            ]);
    }

    protected function getChannelBasedNotifications(User $user)
    {
        return $user->subscriptions()
            ->with('subscribable:id,meta')
            ->where('subscribable_type', '<>', EventBasedNotification::class)
            ->get()
            ->transform(fn ($s) => [
                'id' => $s->hashed_key,
                'label' => $s->subscribable->title,
            ]);
    }
}
