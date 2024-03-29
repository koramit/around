<?php

namespace App\Actions\User;

use App\Extensions\Auth\AvatarUser;
use App\Models\ChatBot;
use App\Models\EventBasedNotification;
use App\Models\SocialProvider;
use App\Models\User;
use App\Traits\AvatarLinkable;
use App\Traits\FlashDataGeneratable;
use Illuminate\Database\Eloquent\Collection;

class PreferencesShowAction
{
    use AvatarLinkable, FlashDataGeneratable;

    public function __invoke(User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        // LINE config
        // for now all user share the same LINE provider
        $lineProvider = SocialProvider::query()->where('platform', 1)->first();
        $lineProfile = $user->activeLINEProfile;
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

        if (! isset($user->preferences['mute'])) {
            $user->preferences['mute'] = false;
            $user->preferences['auto_subscribe_to_channel'] = false;
            $user->preferences['auto_unsubscribe_to_channel'] = false;
            $user->save();
        }

        $preferences = [
            'appearance' => [
                'zen_mode' => $user->preferences['zen_mode'],
                'home_page' => $user->preferences['home_page'],
                'items_per_page' => $user->preferences['items_per_page'],
                'font_scale_index' => $user->preferences['font_scale_index'],
            ],
            // @todo: remove this after migrate to postgreSQL
            'notification' => [
                'mute' => $user->preferences['mute'] ?? false,
                'notify_approval_result' => $user->preferences['notify_approval_result'] ?? true,
                'auto_subscribe_to_channel' => $user->preferences['auto_subscribe_to_channel'] ?? false,
                'auto_unsubscribe_to_channel' => $user->preferences['auto_unsubscribe_to_channel'] ?? false,
            ],
        ];

        return [
            'flash' => $this->getFlash(__('Preferences'), $user),
            'props' => [
                'preferences' => $preferences,
                'configs' => [
                    'can' => [
                        'link_line' => ! $lineLinked,
                        'add_line' => $addFriendLink !== null && ! $lineBotActive,
                    ],
                    'routes' => [
                        'link_line' => (! $lineLinked && $lineProvider) ? route('social-link.create', $lineProvider->hashed_key) : null,
                        'add_line' => $addFriendLink,
                        'update' => route('preferences.update'),
                    ],
                    'friends' => [
                        'line' => $lineBotActive,
                    ],
                    'event_based_notifications' => $this->getEventBasedNotifications($user),
                    'subscribed_channels' => $this->getChannelBasedNotifications($user),
                ],
            ],
        ];
    }

    // event based notification
    protected function getEventBasedNotifications(User $user): Collection
    {
        $subscribedEvents = $user->subscriptions()
            ->where('subscribable_type', EventBasedNotification::class)
            ->pluck('subscribable_id');

        return EventBasedNotification::query()
            ->select(['id', 'name', 'registry_id'])
            ->with('subscription:id,subscribable_type,subscribable_id')
            ->withRegistryName()
            ->whereIn('ability_id', $user->abilities_id)
            ->get()
            ->transform(fn ($e) => [
                'id' => $e->subscription->hashed_key,
                'label' => __($e->name),
                'subscribed' => $subscribedEvents->contains($e->id),
                'registry' => __($e->registry_name),
            ])->groupBy('registry');
    }

    protected function getChannelBasedNotifications(User $user): Collection
    {
        return $user->subscriptions()
            ->with('subscribable:id,meta')
            ->where('subscribable_type', '<>', EventBasedNotification::class)
            ->get()
            ->transform(function ($s) {
                return [
                    'id' => $s->hashed_key,
                    'label' => $s->subscribable->title,
                    'type' => match ($s->subscribable_type) {
                        'App\Models\Registries\AcuteHemodialysisCaseRecord' => 'Acute Hemodialysis Case',
                        'App\Models\Notes\AcuteHemodialysisOrderNote' => 'Acute Hemodialysis Order',
                        default => 'ungrouped',
                    },
                    'subscribed' => true,
                ];
            })
            ->sortBy('type')
            ->groupBy('type');
    }
}
