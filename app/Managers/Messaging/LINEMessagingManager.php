<?php

namespace App\Managers\Messaging;

use App\Models\ChatBot;
use App\Models\ChatLog;
use App\Models\SocialProfile;
use App\Models\User;
use App\Notifications\Messages\LINEMessage;
use App\Traits\LINECallable;
use App\Traits\Placeholderable;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Log;

class LINEMessagingManager
{
    use LINECallable, Placeholderable;

    private Chatbot $bot;

    public function __construct(ChatBot $bot)
    {
        $this->bot = $bot;
    }

    public function manage(array $payload): void
    {
        $profile = SocialProfile::query()
            ->where('profile_id', $payload['events'][0]['source']['userId'])
            ->where('social_provider_id', $this->bot->social_provider_id)
            ->where('active', true)
            ->first();

        $user = $profile?->user;

        ChatLog::query()->create([
            'payload' => $payload,
            'user_id' => $user?->id,
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

    private function follow(array $event, ?User $user, ?SocialProfile $profile): void
    {
        // unauthorized user
        if (! $user) {
            $payload = $this->replyMessage($this->bot, $event['replyToken'], (new LINEMessage())->text('ไม่สามารถให้บริการได้ กรุณาทำการ Link LINE ในเมนู preferences ก่อน')->getMessages());
            $this->log(null, $this->bot->id, $payload, 'reply');

            return;
        }

        // unauthorized bot service provider
        if ($user->profile['line_bot_id'] !== $this->bot->hashed_key) {
            $payload = $this->replyMessage($this->bot, $event['replyToken'], (new LINEMessage())->text('ไม่สามารถให้บริการได้ กรุณาทำการ Add LINE ที่แสดงในเมนู preferences ของท่านเท่านั้น')->getMessages());
            $this->log($user->id, $this->bot->id, $payload, 'reply');

            return;
        }

        // friended, just scan qrcode or click link add friend again
        if ($user->chatBots()->where('id', $this->bot->id)->wherePivot('active', true)->count()) {
            $payload = $this->replyMessage($this->bot, $event['replyToken'], (new LINEMessage())->text('add บ่อยนะ คิดอะไรหรือเปล่า 😄')->getMessages());
            $this->log($user->id, $this->bot->id, $payload, 'reply');

            return;
        }

        // unfriended then ask for makeup - re-follow
        if ($user->chatBots()->where('id', $this->bot->id)->wherePivot('active', false)->count()) {
            $payload = $this->replyMessage($this->bot, $event['replyToken'], (new LINEMessage())->text("🙄กลับมาทำไม ♩\n\nฉันลืมเธอไปหมดแล้ว ♪\n\nความหวังที่เคยเพริดแพรว ♫\n\nฉันลืมหมดแล้วไม่อยู่ในใจ ♬\n\n😒")->getMessages());
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
            $text .= "\n\n🤙🏻 LINE นี้สำหรับแจ้งเตือนและฝากคำแนะนำการให้บริการเท่านั้น โปรดอย่าพิมพ์ข้อมูลส่วนบุคคลหรือข้อมูลสุขภาพทั้งของท่านและของผู้ป่วยส่งเข้ามา\n\nหากต้องการแจ้งปัญหาการใช้งานโปรดแจ้งทางเมนู Consult IT 👌";
            $payload = $this->replyMessage($this->bot, $event['replyToken'], (new LINEMessage())->text($text)->sticker(6359, collect([11069855, 11069867, 11069868, 11069870])->random())->getMessages());
            $this->log($user->id, $this->bot->id, $payload, 'reply');
            $user->chatBots()->attach($this->bot->id, ['active' => true]);
        }
    }

    private function unfollow(?User $user): void
    {
        // unfollow by unauthorized user
        if (! $user) {
            return;
        }

        $user->chatBots()->updateExistingPivot($this->bot->id, ['active' => false]);
    }

    private function message(array $event, ?User $user): void
    {
        /* @TODO implement message event */
        $text = Inspiring::quote();
        $text = str_replace('<options=bold>', '', $text);
        $text = str_replace('<fg=gray>', '', $text);
        $text = str_replace('</>', '', $text);

        $payload = $this->replyMessage($this->bot, $event['replyToken'], (new LINEMessage())->text($text)->getMessages());
        $this->log($user?->id, $this->bot->id, $payload, 'reply');
    }

    private function unsend(?User $user): void
    {
        /* @TODO implement unsend event */
    }
}
