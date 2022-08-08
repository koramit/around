<?php

namespace App\Http\Controllers\Auth;

use App\APIs\LINELoginAPI;
use App\Http\Controllers\Controller;
use App\Models\SocialProvider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SocialLinkController extends Controller
{
    protected SocialProvider $provider;

    public function __construct()
    {
        $provider = SocialProvider::query()->findByUnhashKey(request()->route('provider'))->first();
        if (! $provider) {
            throw ValidationException::withMessages(['notice' => 'No LINE login provider.']);
        }

        $this->provider = $provider;
    }

    public function create()
    {
        if ($this->provider->platform === 'line') {
            return (new LINELoginAPI($this->provider))->redirect('link');
        } else {
            return abort(404);
        }
    }

    public function store(Request $request)
    {
        try {
            if ($this->provider->platform === 'line') {
                $socialUser = (new LINELoginAPI($this->provider));
                $socialUser($request->all(), 'link');
            } else {
                return abort(404);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return redirect()->route('preferences')->withErrors(['status' => $e->getMessage()]);
        }

        $collectEmail = $request->has('email_consent') && $request->input('email_consent') === 'accepted';

        $request->user()->socialProfiles()->firstOrCreate(
            [
                'profile_id' => $socialUser->getId(),
                'social_provider_id' => $this->provider->id,
            ],
            [
                'profile' => [
                    'name' => $socialUser->getName(),
                    'email' => $collectEmail ? $socialUser->getEmail() : null,
                    'avatar' => $socialUser->getAvatar(),
                    'nickname' => $socialUser->getNickname(),
                    'status' => $socialUser->getStatus(),
                ],
            ]
        );

        return redirect()->route('preferences');
    }
}
