<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\AuthenticationAPI;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AvatarController extends Controller
{
    public function store(Request $request, AuthenticationAPI $api)
    {
        $validated = $request->validate([
            'login' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        $data = $api->authenticate($validated['login'], $validated['password']);

        if (! $data['found']) {
            return [
                'found' => false,
            ];
        }

        $user = User::query()->where('login', $validated['login'])->firstOrFail();

        return $this->userData($user);
    }

    public function show()
    {
        $user = request()->user();

        return $this->userData($user, true);
    }

    protected function userData(User $user, $withOutToken = false)
    {
        return [
            'found' => true,
            'avatar_token' => $withOutToken ? null : $user->avatar_token,
            'login' => $user->login,
            'name' => $user->name,
            'password' => $user->password,
            'profile' => $user->profile,
            'home_page' => $user->home_page,
            'abilities' => $user->abilities,
            'preferences' => $user->preferences,
        ];
    }
}
