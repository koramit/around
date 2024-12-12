<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Jobs\Procedures\AcuteHemodialysis\NotifyOrderCanceledToSubscribers;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Traits\AvatarLinkable;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderDestroyAction extends AcuteHemodialysisAction
{
    use AvatarLinkable;

    public function __invoke(array $data, string $hashedKey, mixed $user): array
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        $validated = Validator::make($data, ['reason' => 'required|string|max:255'])->validate();

        /** @var AcuteHemodialysisOrderNote $order */
        $order = AcuteHemodialysisOrderNote::query()->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('destroy', $order)) {
            abort(403);
        }

        // @TODO IF status == scheduling then also expire corresponding request
        if ($order->status === 'scheduling') {
            return [
                'type' => 'warning',
                'title' => 'Cannot cancel order.',
                'message' => 'Please cancel related request first.',
            ];
        }

        $order->update(['status' => 'canceled']);

        $order->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'cancel',
            'payload' => ['reason' => $validated['reason']],
        ]);
        $this->shouldNotifyCancel($order);
        /*$this->notifyCancel($order);*/

        return [
            'type' => 'info',
            'title' => 'Order canceled successfully.',
            'message' => 'Order '.$order->meta['dialysis_type'].' on '.$order->date_note->format('M j').' canceled',
        ];
    }

    private function shouldNotifyCancel(AcuteHemodialysisOrderNote $order): void
    {
        // the day before @ 20:00 local time
        $ref = $order->date_note->addDays(-1)->format('Y-m-d').' '.$this->LAST_HOUR_UTC;
        $ref = now()->create($ref);
        if (now()->lessThan($ref)) {
            return;
        }

        NotifyOrderCanceledToSubscribers::dispatchAfterResponse($order);
    }

    protected function notifyCancel(AcuteHemodialysisOrderNote $order): void
    {
        $order->load('author');
        $message = "\nOrder ถูกยกเลิก\n";
        $message .= "คนไข้ {$order->meta['name']} {$order->meta['dialysis_type']} \nวันที่ {$order->date_note->format('M j y')}\n";
        $message .= "โดย พ.{$order->author->first_name}";

        $sticker = collect([ // cheerful set
            ['packageId' => 789, 'stickerId' => 10856],
            ['packageId' => 789, 'stickerId' => 10863],
            ['packageId' => 789, 'stickerId' => 10874],
            ['packageId' => 1070, 'stickerId' => 17840],
            ['packageId' => 1070, 'stickerId' => 17841],
            ['packageId' => 1070, 'stickerId' => 17843],
            ['packageId' => 8522, 'stickerId' => 16581276],
            ['packageId' => 11537, 'stickerId' => 52002734],
            ['packageId' => 11537, 'stickerId' => 52002738],
            ['packageId' => 11539, 'stickerId' => 52114118],
            ['packageId' => 11539, 'stickerId' => 52114131],
        ])->random();

        try {
            Http::withToken(config('line_notify_group_chat.acute_hd'))
                ->asForm()
                ->post('https://notify-api.line.me/api/notify', [
                    'message' => $message,
                    'stickerPackageId' => $sticker['packageId'],
                    'stickerId' => $sticker['stickerId'],
                ]);

            // COUNT LINE NOTIFY
            $cacheKey = now()->format('Ym').'-LINE-NOTIFY-COUNT';
            cache()->increment($cacheKey);
        } catch (Exception $e) {
            Log::error("Failed to notify cancel order\n".$e->getMessage());
        }
    }
}
