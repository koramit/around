<?php

namespace App\Http\Controllers\Auth;

use App\APIs\LINELoginAPI;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SocialLinkController extends Controller
{
    public function create($provider)
    {
        if ($provider === 'line') {
            return LINELoginAPI::redirect('link');
        } else {
            return abort(404);
        }
    }

    public function store(Request $request, string $provider)
    {
        try {
            if ($provider === 'line') {
                $socialUser = new LINELoginAPI($request->all(), 'link');
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
                'social_provider_id' => $socialUser->getProvider()->id,
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
