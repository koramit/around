<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvatarController extends Controller
{
    public function store()
    {
        if (Auth::attempt(request()->only(['email', 'password']))) {
            $user = Auth::user();
            return $user->toArray() + [
                'found' => true,
                'avatar_token' => $user->avatar_token,
            ];
        } else {
            return [
                'found' => false,
            ];
        }
    }

    public function show()
    {
        return request()->user()->toArray() + [
            'found' => true
        ];
    }
}
