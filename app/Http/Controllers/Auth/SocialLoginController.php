<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginRecordAction;
use App\APIs\LINELoginAPI;
use App\Http\Controllers\Controller;
use App\Models\SocialProfile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;

class SocialLoginController extends Controller
{
    public function create($provider)
    {
        if ($provider === 'line') {
            return LINELoginAPI::redirect();
        } else {
            return abort(404);
        }
    }

    public function store(Request $request, string $provider)
    {
        try {
            if ($provider === 'line') {
                $socialUser = new LINELoginAPI($request->all());
            } else {
                return abort(404);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return redirect()->route('preferences')->withErrors(['status' => $e->getMessage()]);
        }

        if (! $user = SocialProfile::query()->where('profile_id', $socialUser->getId())->activeLineLogin()->first()?->user) {
            return redirect()->route('login')->withErrors(['notice' => 'Please link LINE in preferences menu first.']);
        }

        if (now()->greaterThan(now()->create($user->profile['password_expiration_date']))) {
            return redirect()->route('login')->withErrors(['notice' => 'Please login using Siriraj AD to reactivate LINE login.']);
        }

        Auth::login($user);
        (new LoginRecordAction)(
            ip: $request->ip(),
            agent: new Agent(),
            user: $user,
            provider: $provider,
        );

        return redirect()->intended(route($user->home_page));
    }
}
