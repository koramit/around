<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\InitUserRoleAction;
use App\Actions\Auth\LoginRecordAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Jenssegers\Agent\Agent;

class RegisteredUserController extends Controller
{
    public function create()
    {
        if (! $profile = Session::get('profile')) {
            return Redirect::route('login');
        }
        if (! isset($profile['is_md'])) {
            $profile['is_md'] = str_contains($profile['name'], 'พญ.') || str_contains($profile['name'], 'นพ.');
            Session::put('profile', $profile);
        }

        Session::flash('page-title', __('Register'));

        return Inertia::render('Auth/RegisterForm', [
            'profile' => $profile,
            'routes' => [
                'terms' => route('terms'),
                'registerStore' => route('register.store'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'login' => 'required|string|unique:users',
            'name' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $value = strtolower($value);
                    if (User::query()->whereRaw("lower(name) = '$value'")->first()) {
                        $fail('This '.$attribute.' is already taken.');
                    }
                },
            ],
            'full_name' => 'required|string',
            'pln' => 'exclude_if:is_md,false|required|digits_between:4,6',
            'tel_no' => 'required|digits_between:9,10',
            'agreement_accepted' => 'required',
            'org_id' => 'required|digits:8',
            'division' => 'nullable|string',
            'position' => 'nullable|string',
            'remark' => 'nullable|string',
        ]);

        $profile = [
            'tel_no' => $data['tel_no'],
            'org_id' => $data['org_id'],
            'division' => $data['division'],
            'position' => $data['position'],
            'pln' => $data['pln'] ?? null,
            'remark' => $data['remark'],
        ];

        $user = new User();

        $user->login = $data['login'];
        $user->name = $data['name'];
        $user->full_name = $data['full_name'];
        $user->password = Hash::make(Str::random(64));
        $user->profile = $profile;
        $user->save();

        (new InitUserRoleAction)($user);

        Auth::login($user);
        (new LoginRecordAction)($request->ip(), new Agent(), $user);
        Session::forget('profile');

        return Redirect::route('home');
    }
}
