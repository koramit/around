<?php

namespace App\Actions\User;

use App\Models\User;
use Hashids\Hashids;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

class PreferencesUpdateAction
{
    public function __invoke(array $data, User $user): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        if (isset($data['home_page'])) {
            if (! Route::has($data['home_page'])) {
                throw ValidationException::withMessages(['home_page' => 'route note define']);
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
            $user->preferences['mute'] = $data['notification']['mute'] ?? false;
            $user->preferences['auto_subscribe_to_channel'] = $data['notification']['auto_subscribe_to_channel'] ?? false;
            $user->preferences['auto_unsubscribe_to_channel'] = $data['notification']['auto_unsubscribe_to_channel'] ?? false;
            $user->save();

            return ['ok' => true];
        }

        return [];
    }
}
