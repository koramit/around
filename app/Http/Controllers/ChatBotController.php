<?php

namespace App\Http\Controllers;

use App\Models\ChatBot;
use App\Models\SocialProvider;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    public function show(string $hashedKey)
    {
        $bot = ChatBot::query()->findByUnhashKey($hashedKey)->firstOrFail();

        return [
            'id' => $bot->hashed_key,
            'name' => $bot->name,
            'channel_id' => $bot->configs['channel_id'],
            'secret' => $bot->callback_token,
            'basic_id' => $bot->configs['basic_id'],
            'token' => $bot->configs['token'],
            'add_friend_base_url' => $bot->configs['add_friend_base_url'],
            'routes' => [
                'update' => route('social-providers.bots.update', $bot->hashed_key),
            ],
        ];
    }

    public function store(string $hashedKey, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'channel_id' => 'required|numeric',
            'secret' => 'required|alpha_num|unique:chat_bots,callback_token',
            'basic_id' => 'required|string',
            'token' => 'required|string',
            'add_friend_base_url' => 'required|url',
        ]);

        $provider = SocialProvider::query()->findByUnhashKey($hashedKey)->firstOrFail();

        /** @var ChatBot $bot */
        $bot = $provider->chatBots()->create([
            'name' => $validated['name'],
            'callback_token' => $validated['secret'],
            'configs' => [
                'channel_id' => $validated['channel_id'],
                'basic_id' => $validated['basic_id'],
                'token' => $validated['token'],
                'add_friend_base_url' => $validated['add_friend_base_url'],
            ],
        ]);

        $bot->actionLogs()->create([
            'action' => 'create',
            'actor_id' => $request->user()->id,
        ]);

        return back()->with('message', [
            'type' => 'success',
            'title' => 'Bot successfully added.',
            'message' => '',
        ]);
    }

    public function update(string $hashedKey, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'channel_id' => 'required|numeric',
            'secret' => 'required|alpha_num|exists:chat_bots,callback_token',
            'basic_id' => 'required|string',
            'token' => 'required|string',
            'add_friend_base_url' => 'required|url',
        ]);

        $bot = ChatBot::query()->findByUnhashKey($hashedKey)->firstOrFail();

        $bot->update([
            'name' => $validated['name'],
            'callback_token' => $validated['secret'],
            'configs' => [
                'channel_id' => $validated['channel_id'],
                'token' => $validated['token'],
                'basic_id' => $validated['basic_id'],
                'add_friend_base_url' => $validated['add_friend_base_url'],
            ],
        ]);

        $bot->actionLogs()->create([
            'action' => 'update',
            'actor_id' => $request->user()->id,
        ]);

        return back()->with('message', [
            'type' => 'success',
            'title' => 'Bot successfully updated.',
            'message' => '',
        ]);
    }
}
