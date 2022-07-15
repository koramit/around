<?php

namespace App\APIs;

use App\Contracts\AuthenticationAPI;
use App\Models\User;

class FakeAuthenticationAPI implements AuthenticationAPI
{
    public function authenticate(string $login, string $password): array
    {
        // TODO: Implement authenticate() method.
        if (config('app.env') === 'production' || ! $fakeUser = User::query()->where('login', $login)->first()) {
            return [
                'ok' => true,
                'found' => false,
            ];
        }

        return [
            'ok' => true,
            'found' => true,
            'username' => $fakeUser->login,
            'name' => $fakeUser->profile['full_name'],
            'name_en' => $fakeUser->profile['full_name'],
            'email' => null,
            'org_id' => $fakeUser->profile['org_id'],
            'tel_no' => $fakeUser->profile['tel_no'],
            'document_id' => null,
            'org_division_name' => $fakeUser->profile['division'],
            'org_position_title' => $fakeUser->profile['position'],
            'remark' => $fakeUser->profile['remark'],
            'password_expires_in_days' => 90,
        ];
    }

    public function getUserById(string $id): array
    {
        // TODO: Implement getUserById() method.
        return [];
    }
}
