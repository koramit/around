<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AuthenticatedSessionController extends Controller
{
    public function store()
    {
        // Request::validate()

        if (config('auth.guards.web.provider') === 'avatars') {
            return $this->storeAvatarUser();
        }
    }

    protected function storeAvatarUser()
    {
        if (Auth::attempt(Request::only(['login', 'password']), )) {
            return Redirect::intended(route('home'));
            // return Redirect::intended(route(Auth::user()->home_page));
        }

        return back()->withErrors([
            'login' => __('auth.failed'),
        ]);
    }
}
