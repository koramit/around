<?php

namespace App\Traits;

use App\Models\ChatBot;
use App\Models\ChatLog;
use Illuminate\Support\Facades\Http;

trait LINECallable
{
    /** Reply tokens can only be used once. */
    /** Reply tokens must be used within one minute after receiving the webhook */
    private function replyMessage(ChatBot $bot, string $replyToken, array $messages): array
    {
        $payload = [
            'replyToken' => $replyToken,
            'messages' => $messages,
        ];
        $this->makePost($bot->configs['token'], 'message/reply', $payload);

        return $payload;
    }

    private function pushMessage(ChatBot $bot, string $to, array $messages): array
    {
        $payload = [
            'to' => $to,
            'messages' => $messages,
        ];
        $this->makePost($bot->configs['token'], 'message/push', $payload);

        return $payload;
    }

    private function makePost(string $token, string $url, array $payload): void
    {
        Http::timeout(2)
            ->retry(3, 100)
            ->withToken($token)
            ->post('https://api.line.me/v2/bot/'.$url, $payload);
    }

    private function log(?int $userId, int $botId, array $payload, string $mode): void
    {
        ChatLog::query()->create([
            'user_id' => $userId,
            'chat_bot_id' => $botId,
            'payload' => $payload,
            'mode' => $mode,
        ]);
    }
}
