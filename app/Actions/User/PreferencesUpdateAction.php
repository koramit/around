<?php

namespace App\Actions\User;

use App\Traits\AvatarLinkable;
use Hashids\Hashids;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

class PreferencesUpdateAction
{
    use AvatarLinkable;

    public function __invoke(array $data, mixed $user): array
    {
        $link = $this->shouldLinkAvatar();
        if ($link !== false) {
            return $link;
        }

        if (isset($data['home_page'])) {
            if (! Route::has($data['home_page'])) {
                throw ValidationException::withMessages(['home_page' => 'route not define']);
            }
            $user->home_page = $data['home_page'];

            return ['ok' => true];
        }

        if (isset($data['subscriptions'])) {
            $eventIds = collect($data['subscriptions'])->map(fn ($key) => app(Hashids::class)->decode($key)[0] ?? 0);
            $user->subscriptions()->sync($eventIds);

            return ['ok' => true];
        }

        if (isset($data['notification'])) {
            if (isset($data['notification']['mute'])) {
                $user->preferences['mute'] = $data['notification']['mute'];
            }
            if (isset($data['notification']['notify_approval_result'])) {
                $user->preferences['notify_approval_result'] = $data['notification']['notify_approval_result'];
            }
            if (isset($data['notification']['auto_subscribe_to_channel'])) {
                $user->preferences['auto_subscribe_to_channel'] = $data['notification']['auto_subscribe_to_channel'];
            }
            if (isset($data['notification']['auto_unsubscribe_to_channel'])) {
                $user->preferences['auto_unsubscribe_to_channel'] = $data['notification']['auto_unsubscribe_to_channel'];
            }
            $user->save();

            return ['ok' => true];
        }

        return [];
    }
}
