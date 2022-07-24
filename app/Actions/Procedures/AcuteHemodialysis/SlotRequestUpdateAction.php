<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\DocumentChangeRequests\AcuteHemodialysisSlotRequest;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class SlotRequestUpdateAction
{
    public function __invoke(string $hashedKey, array $data, User $user): array
    {
        $request = AcuteHemodialysisSlotRequest::query()->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('approve', $request)) {
            abort(403);
        }

        $validated = Validator::make($data, [
            'approve' => 'required|boolean',
            'reason' => 'required_if:approve,false|string|max:255',
        ])->validate();

        /** @var AcuteHemodialysisOrderNote $order */
        $order = $request->changeable;
        if ($validated['approve']) {
            if (! isset($request->changes['swap'])) {
                $order->update([
                    'status' => ($order->meta['submitted'] ?? false) ? 'submitted' : 'draft',
                    'date_note' => $request->changes['date_note'],
                ]);
            } else {
                $swap = AcuteHemodialysisOrderNote::query()->find($request->changes['swap']);
                $dateSwap = $order->date_note;
                $order->update([
                    'date_note' => $swap->date_note,
                    'status' => ($order->meta['submitted'] ?? false) ? 'submitted' : 'draft',
                ]);
                $swap->update([
                    'date_note' => $dateSwap,
                    'status' => ($swap->meta['submitted'] ?? false) ? 'submitted' : 'draft',
                ]);
            }

            $request->update(['status' => 'approved']);
            $request->actionLogs()->create([
                'action' => 'approve',
                'actor_id' => $user->id,
            ]);

            return [
                'type' => 'info',
                'title' => 'Request approved.',
                'message' => "HN {$order->meta['hn']} {$order->meta['name']} $request->change_request_text.",
            ];
        }

        // disapprove request
        unset($validated['approve']);
        if ($order->date_note->format('Y-m-d') === $request->changes['date_note']) {
            $order->update(['status' => 'disapproved']);
            $order->actionLogs()->create([
                'action' => 'disapprove',
                'actor_id' => $user->id,
                'payload' => ['reason' => 'the request is not approved'],
            ]);
        } else {
            if (isset($request->changes['swap'])) {
                $swap = AcuteHemodialysisOrderNote::query()->find($request->changes['swap']);
                $swap->update(['status' => ($order->meta['submitted'] ?? false) ? 'submitted' : 'draft']);
                $order->update(['status' => ($order->meta['submitted'] ?? false) ? 'submitted' : 'draft']);
            } else {
                if ($order->date_note->format('Y-m-d') === $request->changes['date_note']) {
                    $order->update(['status' => 'disapproved']); // also disapprove order
                    $order->actionLogs()->create([
                        'action' => 'disapprove',
                        'actor_id' => $user->id,
                        'payload' => $validated,
                    ]);
                } else {
                    $order->update(['status' => ($order->meta['submitted'] ?? false) ? 'submitted' : 'draft']);
                }
            }
        }

        $request->update(['status' => 'disapproved']);
        $request->actionLogs()->create([
            'action' => 'disapprove',
            'actor_id' => $user->id,
            'payload' => $validated,
        ]);

        return [
            'type' => 'info',
            'title' => 'Request disapproved.',
            'message' => "HN {$order->meta['hn']} {$order->meta['name']} $request->change_request_text.",
        ];
    }
}
