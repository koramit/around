<?php

namespace App\Traits;

use App\Models\ChatBot;
use App\Models\ChatLog;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait LINECallable
{
    /** Reply tokens can only be used once. */
    /** Reply tokens must be used within one minute after receiving the webhook */
    private function replyMessage(ChatBot $bot, string $replyToken, array $messages): ?array
    {
        $payload = [
            'replyToken' => $replyToken,
            'messages' => $messages,
        ];

        return $this->makePost($bot, 'message/reply', $payload)
            ? $payload
            : null;
    }

    private function pushMessage(ChatBot $bot, string $to, array $messages): ?array
    {
        if ($bot->configs['limit_reached']) {
            $bot->configs['missed_count'] = $bot->configs['missed_count'] + 1;
            $bot->save();

            return null;
        }

        return [ // bot notification service closed since 2025-04-01
            'to' => $to,
            'messages' => $messages,
        ];

        /*return $this->makePost($bot, 'message/push', $payload)
            ? $payload
            : null;*/
    }

    private function makePost(ChatBot $bot, string $url, array $payload): bool
    {
        if (config('app.env') !== 'production') {
            return false;
        }
        try {
            Http::timeout(2)
                ->retry(3, 100)
                ->withToken($bot->configs['token'])
                ->post('https://api.line.me/v2/bot/'.$url, $payload);
        } catch (Exception $e) {
            $message = $e->getMessage();
            if (str_contains($message, '429')) {
                $bot->configs['limit_reached'] = true;
                $bot->save();

                return false;
            }
            Log::error('LINE API ERROR : '.$message);

            return false;
        }

        return true;
    }

    private function log(?int $userId, int $botId, ?array $payload, string $mode): void
    {
        if ($payload === null) {
            return;
        }

        ChatLog::query()->create([
            'user_id' => $userId,
            'chat_bot_id' => $botId,
            'payload' => $payload,
            'mode' => $mode,
        ]);
    }
}
