<?php

namespace App\Http\Controllers;

use App\Actions\Auth\LoginRecordAction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class MagicLinkController extends Controller
{
    public function __invoke(Request $request)
    {
        $to = cache()->pull('magic-link-token-'.$request->input('token'));

        if (! $to) {
            abort(404);
        }

        // cache intend link with token
        // send token with signed url
        // user hti magic link - validate
        // login user and redirect to cache url

        if ($request->user()) {
            return redirect($to);
        }

        $user = User::query()->findByUnhashKey($request->input('user'))->firstOrFail();

        Auth::login($user);
        (new LoginRecordAction)(
            ip: $request->ip(),
            agent: new Agent(),
            user: $user,
            provider: 'magic-link',
        );

        return redirect($to);
    }
}
