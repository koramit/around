<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\DocumentChangeRequests\AcuteHemodialysisSlotRequest;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class SlotRequestDestroyAction
{
    public function __invoke(string $hashedKey, array $data, User $user): array
    {
        $request = AcuteHemodialysisSlotRequest::query()->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('cancel', $request)) {
            abort(403);
        }

        $validated = Validator::make($data, ['reason' => 'required|string|max:255'])->validate();

        $request->update(['status' => 'canceled']);
        $request->actionLogs()->create([
            'action' => 'cancel',
            'actor_id' => $user->id,
            'payload' => $validated,
        ]);

        /** @var AcuteHemodialysisOrderNote $order */
        $order = $request->changeable;
        if ($order->date_note->format('Y-m-d') === $request->changes['date_note']) {
            $order->update(['status' => 'canceled']);
            $order->actionLogs()->create([
                'action' => 'cancel',
                'actor_id' => $user->id,
                'payload' => ['reason' => 'the request was canceled'],
            ]);

            return [
                'type' => 'info',
                'title' => 'Request and order are canceled.',
                'message' => "HN {$order->meta['hn']} {$order->meta['name']} $request->change_request_text.",
            ];
        }

        $order->update(['status' => ($order->meta['submitted'] ?? false) ? 'submitted' : 'draft']);
        if (isset($request->changes['swap'])) {
            $swap = AcuteHemodialysisOrderNote::query()->find($request->changes['swap']);
            $swap->update(['status' => ($order->meta['submitted'] ?? false) ? 'submitted' : 'draft']);
        }

        return [
            'type' => 'info',
            'title' => 'The request was canceled.',
            'message' => "HN {$order->meta['hn']} {$order->meta['name']} $request->change_request_text.",
        ];
    }
}
