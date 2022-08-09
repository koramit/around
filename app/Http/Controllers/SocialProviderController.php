<?php

namespace App\Http\Controllers;

use App\Models\SocialProvider;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SocialProviderController extends Controller
{
    public function index()
    {
        $providers = SocialProvider::query()
            ->get()
            ->transform(fn ($s) => [
                'id' => $s->hashed_key,
                'name' => $s->name,
                'platform' => $s->platform,
                'routes' => [
                    'show' => route('social-providers.show', $s->hashed_key),
                    'update' => route('social-providers.update', $s->hashed_key),
                    'bots_store' => route('social-providers.bots.store', $s->hashed_key),
                ],
            ]);

        return Inertia::render('ChatBot/ProviderIndex')->with([
            'providers' => $providers,
            'configs' => [
                'routes' => [
                    'base_webhooks' => route('home').'/webhooks/messaging/',
                    'providers_store' => route('social-providers.store'),
                    'terms' => route('terms'),
                ],
                'channel_detail' => 'By Nephr@SI',
            ],
        ]);
    }

    public function show($hashedKey)
    {
        $provider = SocialProvider::query()
            ->findByUnhashKey($hashedKey)
            ->select(['id', 'name', 'configs'])
            ->firstOrFail();

        return [
            'name' => $provider->name,
            'configs' => $provider->configs,
            'bots' => $provider->chatBots()->select(['id', 'name', 'social_provider_id'])->get()->transform(fn ($b) => [
                'id' => $b->hashed_key,
                'name' => $b->name,
                'routes' => [
                    'show' => route('social-providers.bots.show', $b->hashed_key),
                ],
            ]),
            'routes' => [
                'update' => route('social-providers.update', $provider->hashed_key),
                'callbacks' => implode("\n", [
                    route('social-link.create', $provider->hashed_key),
                    route('social-link.store', $provider->hashed_key),
                    route('social-login.create', $provider->hashed_key),
                    route('social-login.store', $provider->hashed_key),
                ]),
            ],
        ];
    }

    public function update($hashedKey, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|exists:social_providers',
            'provider_id' => 'required|numeric',
            'channel_id' => 'required|numeric',
            'channel_secret' => 'required|alpha_num',
            'access_token_url' => 'required|url',
            'auth_url' => 'required|url',
            'profile_url' => 'required|url',
        ]);

        $provider = SocialProvider::query()->findByUnhashKey($hashedKey)->firstOrFail();

        $provider->update([
            'configs' => [
                'provider_id' => $validated['provider_id'],
                'channel_id' => $validated['channel_id'],
                'channel_secret' => $validated['channel_secret'],
                'access_token_url' => $validated['access_token_url'],
                'auth_url' => $validated['auth_url'],
                'profile_url' => $validated['profile_url'],
            ],
        ]);

        $provider->actionLogs()->create([
            'action' => 'update',
            'actor_id' => $request->user()->id,
        ]);

        return back()->with('message', [
            'type' => 'success',
            'title' => 'Social Provider successfully updated.',
            'message' => '',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:social_providers',
            'provider_id' => 'required|numeric',
            'channel_id' => 'required|numeric',
            'channel_secret' => 'required|alpha_num',
            'access_token_url' => 'required|url',
            'auth_url' => 'required|url',
            'profile_url' => 'required|url',
        ]);

        $provider = SocialProvider::query()->create([
            'name' => $validated['name'],
            'configs' => [
                'provider_id' => $validated['provider_id'],
                'channel_id' => $validated['channel_id'],
                'channel_secret' => $validated['channel_secret'],
                'access_token_url' => $validated['access_token_url'],
                'auth_url' => $validated['auth_url'],
                'profile_url' => $validated['profile_url'],
            ],
        ]);

        $provider->actionLogs()->create([
            'action' => 'create',
            'actor_id' => $request->user()->id,
        ]);

        return back()->with('message', [
            'type' => 'success',
            'title' => 'Social Provider successfully added.',
            'message' => '',
        ]);
    }
}
