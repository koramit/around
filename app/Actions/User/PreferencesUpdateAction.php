<?php

namespace App\Actions\User;

use App\Models\User;
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

        return [];
    }
}
