<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
    }
}
