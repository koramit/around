<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginRecordAction;
use App\APIs\LINELoginAPI;
use App\Http\Controllers\Controller;
use App\Models\SocialProfile;
use App\Models\SocialProvider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Agent;

class SocialLoginController extends Controller
{
    protected SocialProvider $provider;

    public function create()
    {
        $this->setProvider();

        if ($this->provider->platform === 'line') {
            return (new LINELoginAPI($this->provider))->redirect();
        } else {
            abort(404);
        }
    }

    public function store(Request $request)
    {
        $this->setProvider();

        try {
            if ($this->provider->platform === 'line') {
                $socialUser = (new LINELoginAPI($this->provider));
                $socialUser($request->all());
            } else {
                abort(404);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return redirect()->route('login')->withErrors(['status' => $e->getMessage()]);
        }

        if (! $user = SocialProfile::query()->where('profile_id', $socialUser->getId())->activeLoginByProviderId($this->provider->id)->first()?->user) {
            return redirect()->route('login')->withErrors(['notice' => 'Please link LINE in preferences menu first.']);
        }

        if (!isset($user->profile['password_expiration_date']) || now()->greaterThan(now()->create($user->profile['password_expiration_date'])) ) {
            return redirect()->route('login')->withErrors(['notice' => 'Please login using Siriraj AD to reactivate LINE login.']);
        }

        Auth::login($user);
        (new LoginRecordAction)(
            ip: $request->ip(),
            agent: new Agent(),
            user: $user,
            provider: $this->provider->platform,
        );

        return redirect()->intended(route($user->home_page));
    }

    protected function setProvider()
    {
        $provider = SocialProvider::query()->findByUnhashKey(request()->route('provider'))->first();
        if (! $provider) {
            throw ValidationException::withMessages(['notice' => 'No LINE login provider.']);
        }

        $this->provider = $provider;
    }
}
