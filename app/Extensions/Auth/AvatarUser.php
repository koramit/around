<?php

namespace App\Extensions\Auth;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;

class AvatarUser extends Authenticatable
{
    private string $avatar_token;

    private string $password;

    public array $profile;

    public string $login;

    public string $name;

    public string $home_page;

    public Collection $abilities;

    public array $preferences;

    public function __construct(array $user = [])
    {
        parent::__construct();
        $this->avatar_token = $user['avatar_token'] ?? '';
        $this->login = $user['login'] ?? '';
        $this->name = $user['name'] ?? '';
        $this->password = $user['password'] ?? 'secret';
        $this->profile = $user['profile'] ?? [];
        $this->home_page = $user['home_page'] ?? 'home';
        $this->abilities = collect($user['abilities'] ?? []);
        $this->preferences = $user['preferences'] ?? [];
    }

    public function getAuthIdentifierName(): string
    {
        return 'avatar_token';
    }

    public function getAuthIdentifier(): mixed
    {
        return $this->avatar_token;
    }

    public function getAuthPassword(): string
    {
        return $this->password;
    }

    public function getRememberToken(): string
    {
        return '';
    }

    public function setRememberToken($value): void
    {
    }

    public function getRememberTokenName(): string
    {
        return '';
    }

    public function toArray(): array
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
