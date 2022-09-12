<?php

namespace App\Traits;

use App\Extensions\Auth\AvatarUser;
use Illuminate\Support\Facades\Http;

trait AvatarLinkable
{
    protected function shouldLinkAvatar(mixed $user, string $routeName)
    {
        if (! ($user instanceof AvatarUser)) {
            return false;
        }

        $url = str_replace(config('app.url'), config('auth.avatar.url'), route($routeName));

        $response = Http::withToken($user->getAuthIdentifier())
            ->acceptJson()
            ->get($url);
        $data = $response->json();
        $this->replaceDomain($data);

        return $data;
    }

    protected function replaceDomain(array &$data): void
    {
        $keys = array_keys($data);
        foreach ($keys as $key) {
            if (gettype($data[$key]) === 'array') {
                $this->replaceDomain($data[$key]);
            } elseif (gettype($data[$key]) === 'string') {
                $source = str_replace('/avatar', '', config('auth.avatar.url'));
                if (str_starts_with($data[$key], $source)) {
                    $data[$key] = str_replace($source, config('app.url'), $data[$key]);
                }
            }
        }
    }
}
