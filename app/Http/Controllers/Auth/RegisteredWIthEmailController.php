<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginRecordAction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Jenssegers\Agent\Agent;

class RegisteredWIthEmailController
{
    public function create()
    {
        Session::flash('page-title', 'Register with Email');

        return Inertia::render('Auth/RegisterWithEmail');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,login',
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'full_name' => ['required', 'string', 'regex:/^[\x{0E01}-\x{0E5B}. ]+$/u', 'max:255'],
            'tel_no' => ['required', 'digits_between:9,10'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),
            ],
        ]);

        $profile = [
            'tel_no' => $validated['tel_no'],
            'org_id' => null,
            'division' => null,
            'position' => null,
            'pln' => null,
            'remark' => null,
        ];

        $user = new User;
        $user->login = $validated['email'];
        $user->name = $validated['name'];
        $user->full_name = $validated['full_name'];
        $user->profile = $profile;
        $user->password = Hash::make($validated['password']);
        $user->save();

        Auth::login($user);
        (new LoginRecordAction)($request->ip(), new Agent, $user);

        return Redirect::route('home');
    }
}
