<?php

namespace App\Traits;

use App\Extensions\Auth\AvatarUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

trait AvatarLinkable
{
    protected function shouldLinkAvatar()
    {
        $user = Auth::user();
        if (! ($user instanceof AvatarUser)) {
            return false;
        }

        $routeName = Route::currentRouteName();
        $url = str_replace(config('app.url'), config('auth.avatar.url'), route($routeName, request()->route()->parameters()));
        $method = $this->getRouteMethod($routeName);
        $client = Http::withToken($user->getAuthIdentifier())->acceptJson();
        if ($method === 'GET') {
            $response = $client->get($url, request()->all());
        } elseif ($method === 'PATCH') {
            $response = $client->patch($url, request()->all());
        } else {
            abort(404);
        }

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

    protected function getRouteMethod(string $name)
    {
        $routes = collect(Route::getRoutes())->map(fn ($route) => $route);
        $index = $routes->search(fn ($route) => ($route->action['as'] ?? '') === $name);
        if ($index === false) {
            abort(404);
        }

        return $routes[$index]->methods[0];
    }
}
