<?php

namespace App\Extensions\Auth;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AvatarUserProvider implements UserProvider
{
    private $providerUrl;
    private $providerToken;

    public function __construct(array $config)
    {
        $this->providerUrl = $config['url'];
        $this->providerToken = $config['token'];
    }

    public function retrieveById($identifier)
    {
        /*
         * Work the same as User::find($id);
         */
        $response = Http::withToken($identifier)->get($this->providerUrl.'/api/avatar');
        $user = $response->json() ?? ['found' => false];
        if (! ($response->ok() && $user['found'])) {
            throw new Exception('provider service not available');
        }
        $user['avatar_token'] = $identifier;

        return new AvatarUser($user);
    }

    public function retrieveByToken($identifier, $token)
    {
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
    }

    public function retrieveByCredentials(array $credentials)
    {
        /*
         * The retrieveByCredentials method receives the array of credentials passed to the Auth::attempt
         * method when attempting to authenticate with an application. The method should then "query"
         * the underlying persistent storage for the user matching those credentials. Typically,
         * this method will run a query with a "where" condition that searches for a user
         * record with a "username" matching the value of $credentials['username'].
         * The method should return an implementation of Authenticatable.
         * This method should not attempt to do any password
         * validation or authentication.
         */
        $response = Http::withHeaders(['token' => $this->providerToken])
                        ->post($this->providerUrl.'/api/avatar', $credentials);
        $user = $response->json() ?? ['found' => false];
        if (! ($response->ok() && $user['found'])) {
            return null;
        }

        $user['password'] = Hash::make($credentials['password']);

        return new AvatarUser($user);
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        /*
         * The validateCredentials method should compare the given $user with the $credentials
         * to authenticate the user. For example, this method will typically use the
         * Hash::check method to compare the value of $user->getAuthPassword() to
         * the value of $credentials['password']. This method should return
         * true or false indicating whether the password is valid.
         */

        if (! Hash::check($credentials['password'], $user->getAuthPassword())) {
            return false;
        }
        Auth::login($user);

        return true;
    }
}
