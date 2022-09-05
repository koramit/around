<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginRecordAction;
use App\Actions\Auth\LogoutRecordAction;
use App\Contracts\AuthenticationAPI;
use App\Http\Controllers\Controller;
use App\Models\SocialProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Jenssegers\Agent\Agent;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return Response
     */
    public function create()
    {
        session()->flash('page-title', __('Please Login'));
        $lineProvider = config('auth.guards.web.provider') === 'avatar'
            ? null
            : SocialProvider::query()->where('platform', 1)->first();

        return Inertia::render('Auth/LoginForm', [
            'links' => [
                'login_store' => route('login.store'),
                'line_login' => $lineProvider ? route('social-login.create', $lineProvider->hashed_key) : null,
            ],
        ]);
    }

    public function store(Request $request, AuthenticationAPI $api)
    {
        $validated = $request->validate([
            'login' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        if (config('auth.guards.web.provider') === 'avatar') {
            return $this->storeAvatarUser($validated);
        }

        $data = $api->authenticate($validated['login'], $validated['password']);

        if (! $data['found']) {
            return back()->withErrors([
                'login' => $data['message'],
            ]);
        }

        if ($user = User::whereLogin($validated['login'])->first()) {
            Auth::login($user);
            (new LoginRecordAction)(
                ip: $request->ip(),
                agent: new Agent(),
                user: $user,
                daysBeforePasswordExpired: $data['password_expires_in_days'] ?? 0
            );

            return redirect()->intended(route($user->home_page));
        }

        session()->put('profile', $data);

        return redirect()->route('register');
    }

    public function update()
    {
        return ['active' => true];
    }

    public function destroy(Request $request)
    {
        (new LogoutRecordAction)($request->user());

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    protected function storeAvatarUser(array $data)
    {
        if (Auth::attempt($data)) {
            return redirect()->intended(route(Auth::user()->home_page));
        }

        return back()->withErrors([
            'login' => __('auth.failed'),
        ]);
    }
}
