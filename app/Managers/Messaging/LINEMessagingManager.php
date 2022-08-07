<?php

namespace App\Managers\Messaging;

use App\Models\ChatBot;
use App\Models\ChatLog;
use App\Models\SocialProfile;
use App\Models\User;
use App\Services\Messages\LINEMessage;
use App\Traits\Placeholderable;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LINEMessagingManager
{
    use Placeholderable;

    private string $baseEndpoint = 'https://api.line.me/v2/bot/';

    private Chatbot $bot;

    private PendingRequest $client;

    public function __construct(ChatBot $bot)
    {
        $this->bot = $bot;

        $this->client = Http::timeout(2)->retry(3, 100)->withToken($this->bot->configs['token']);
    }

    public function manage(array $payload): void
    {
        $profile = SocialProfile::query()
            ->where('profile_id', $payload['events'][0]['source']['userId'])
            ->where('social_provider_id', cache('line-login-provider')?->id)
            ->where('active', true)
            ->first();

        $user = $profile ? $profile->user : User::query()->first();

        ChatLog::query()->create([
            'payload' => $payload,
            'user_id' => $user->id,
            'chat_bot_id' => $this->bot->id,
            'mode' => 'read',
        ]);

        foreach ($payload['events'] as $event) {
            if ($event['type'] == 'follow') {
                $this->follow($event, $user, $profile);
            } elseif ($event['type'] == 'unfollow') {
                $this->unfollow($user);
            } elseif ($event['type'] == 'message') {
                $this->message($event, $user);
            } elseif ($event['type'] == 'unsend') {
                $this->unsend($user);
            } else {
                Log::notice('unhandled LINE event : '.$event['type']);
            }
        }
    }

    private function follow(array $event, User $user, ?SocialProfile $profile): void
    {
        // unauthorized user
        if ($user->id === 1) {
            $payload = $this->replyMessage($event['replyToken'], (new LINEMessage())->text('ไม่สามารถให้บริการได้ กรุณาทำการ Link LINE ในเมนู preferences ก่อน')->getMessages());
            $this->log($user->id, $this->bot->id, $payload, 'reply');

            return;
        }

        // unauthorized bot service provider
        if ($user->profile['line_bot_provider_id'] !== $this->bot->social_provider_id) {
            $payload = $this->replyMessage($event['replyToken'], (new LINEMessage())->text('ไม่สามารถให้บริการได้ กรุณาทำการ Add LINE ที่แสดงในเมนู preferences ของท่านเท่านั้น')->getMessages());
            $this->log($user->id, $this->bot->id, $payload, 'reply');

            return;
        }

        // friended, just scan qrcode or click link add friend again
        if ($user->chatBots()->where('id', $this->bot->id)->wherePivot('active', true)->count()) {
            $payload = $this->replyMessage($event['replyToken'], (new LINEMessage())->text('กด add บ่อยนะ คิดอะไรหรือเปล่า 😄')->getMessages());
            $this->log($user->id, $this->bot->id, $payload, 'reply');

            return;
        }

        // unfriended then ask for makeup - re-follow
        if ($user->chatBots()->where('id', $this->bot->id)->wherePivot('active', false)->count()) {
            $payload = $this->replyMessage($event['replyToken'], (new LINEMessage())->text("🙄กลับมาทำไม ♩\n\nฉันลืมเธอไปหมดแล้ว ♪\n\nความหวังที่เคยเพริดแพรว ♫\n\nฉันลืมหมดแล้วไม่อยู่ในใจ ♬\n\n😒")->getMessages());
            $this->log($user->id, $this->bot->id, $payload, 'reply');
            $user->chatBots()->updateExistingPivot($this->bot->id, ['active' => true]);

            return;
        }

        // finally - follow
        if ($user->chatBots()->where('id', $this->bot->id)->count() === 0) {
            $text = $this->replaceholders(
                "สวัสดี :LINE_USER_NAME: 😃\n\n ตั้งค่าการแจ้งเตือนสำเร็จ 🥳\n\nยินดีต้อนรับ!! 🎉",
                ['LINE_USER_NAME' => $profile->profile['nickname'] ?? $profile->profile['name']]
            );
            $text .= "\n\n🤙🏻 LINE นี้สำหรับแจ้งเตือนและฝากคำแนะนำการให้บริการเท่านั้น โปรดอย่าพิมพ์ข้อมูลส่วนบุคคลหรือข้อมูลสุขภาพทั้งของท่านและของผู้ป่วยส่งเข้ามา\n\nหากต้องการแจ้งปัญหาการใช้งานโปรดแจ้งทางเมนู Support 👌";
            $payload = $this->replyMessage($event['replyToken'], (new LINEMessage())->text($text)->sticker(6359, collect([11069855, 11069867, 11069868, 11069870])->random())->getMessages());
            $this->log($user->id, $this->bot->id, $payload, 'reply');
            $user->chatBots()->attach($this->bot->id, ['active' => true]);
        }
    }

    private function unfollow(User $user): void
    {
        // unfollow by unauthorized user
        if ($user->id === 1) {
            return;
        }

        $user->chatBots()->updateExistingPivot($this->bot->id, ['active' => false]);
    }

    private function message(array $event, User $user): void
    {
        /* @TODO implement message event */
        $text = Inspiring::quote();
        $text = str_replace('<options=bold>', '', $text);
        $text = str_replace('<fg=gray>', '', $text);
        $text = str_replace('</>', '', $text);

        $this->replyMessage($event['replyToken'], (new LINEMessage())->text($text)->getMessages());
    }

    private function unsend(User $user): void
    {
        /* @TODO implement unsend event */
    }

    private function replyMessage(string $replyToken, array $messages): array
    {
        $payload = [
            'replyToken' => $replyToken,
            'messages' => $messages,
        ];
        $this->client->post($this->baseEndpoint.'message/reply', $payload);

        return $payload;
    }

    private function log(int $userId, int $botId, array $payload, string $mode): void
    {
        ChatLog::query()->create([
            'user_id' => $userId,
            'chat_bot_id' => $botId,
            'payload' => $payload,
            'mode' => $mode,
        ]);
    }
}
