<?php

namespace App\Extensions\Auth;

use Illuminate\Foundation\Auth\User as Authenticatable;

class AvatarUser extends Authenticatable
{
    private $avatar_token;

    private $password;

    public $profile;

    public $login;

    public $name;

    public $home_page;

    public $abilities;

    public $preferences;

    public function __construct($user)
    {
        $this->avatar_token = $user['avatar_token'];
        $this->login = $user['login'];
        $this->name = $user['name'];
        $this->password = $user['password'] ?? 'secret';
        $this->profile = $user['profile'] ?? [];
        $this->home_page = $user['home_page'] ?? 'home';
        $this->abilities = collect($user['abilities'] ?? []);
        $this->preferences = $user['preferences'];
    }

    public function getAuthIdentifierName()
    {
        return 'avatar_token';
    }

    public function getAuthIdentifier()
    {
        return $this->avatar_token;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
    }

    public function setRememberToken($value)
    {
    }

    public function getRememberTokenName()
    {
    }

    public function toArray()
    {
        return [
            'profile' => $this->profile,
            'login' => $this->login,
            'name' => $this->name,
            'home_page' => $this->home_page,
            'abilities' => $this->abilities,
            'preferences' => $this->preferences,
        ];
    }
}
