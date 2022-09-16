<?php

namespace App\Traits;

use App\Extensions\Auth\AvatarUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

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
        if (request()->hasFile('file')) {
            $file = request()->file('file');
            if (! $file->isValid()) {
                throw ValidationException::withMessages(['file' => 'file not invalid']);
            }
            $client->attach('file', $file->getContent(), $file->getClientOriginalName());
            $requestData = request()->except('file');
        } else {
            $requestData = request()->all();
        }
        if ($method === 'GET') {
            $response = $client->get($url, $requestData);
        } elseif ($method === 'POST') {
            $response = $client->post($url, $requestData);
        } elseif ($method === 'PATCH') {
            $response = $client->patch($url, $requestData);
        } elseif ($method === 'DELETE') {
            $response = $client->delete($url, $requestData);
        } else {
            abort(404);
        }

        if (collect([422])->contains($response->status())) {
            throw ValidationException::withMessages($response->json()['errors']);
        }

        $data = $response->json();
        if (! $data) {
            return $response;
        }
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
                // remove avatar prefix generate by route in avatar group
                $data[$key] = str_replace('/avatar/', '/', $data[$key]);
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
