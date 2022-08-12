<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\DocumentChangeRequests\AcuteHemodialysisSlotRequest;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;
use App\Traits\AcuteHemodialysis\OrderSwappable;
use App\Traits\AcuteHemodialysis\SlotCountable;
use Exception;
use Illuminate\Support\Facades\Validator;

class OrderSwapAction extends AcuteHemodialysisAction
{
    use OrderSwappable, SlotCountable;

    /**
     * @throws Exception
     */
    public function __invoke(array $data, string $hashedKey, User $user): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        /** @var AcuteHemodialysisOrderNote $order */
        $order = AcuteHemodialysisOrderNote::query()->withPlaceName('App\Models\Resources\Ward')->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('swap', $order)) {
            abort(403);
        }

        $validated = Validator::make($data, ['swap_with' => 'required|digits:4'])->validate();

        if (! $swap = AcuteHemodialysisOrderNote::query()->activeStatuses()->where('meta->swap_code', $validated['swap_with'])->first()) {
            return [
                'type' => 'danger',
                'title' => 'Cannot swap slot.',
                'message' => 'Code not match.',
            ];
        }

        if ($order->meta['in_unit'] !== $swap->meta['in_unit']) {
            $order->update(['meta->swap_code' => $this->genSwapCode()]);
            $swap->update(['meta->swap_code' => $this->genSwapCode()]);

            return [
                'type' => 'danger',
                'title' => 'Cannot swap slot.',
                'message' => 'Cannot swap HD unit slot with ward slot, swap code regenerated.',
            ];
        }

        if ($order->date_note->equalTo($swap->date_note)) {
            $order->update(['meta->swap_code' => $this->genSwapCode()]);
            $swap->update(['meta->swap_code' => $this->genSwapCode()]);

            return [
                'type' => 'danger',
                'title' => 'Cannot swap slot.',
                'message' => 'These slots are in the same date, swap code regenerated.',
            ];
        }

        $orderIsTpe = str_contains($order->meta['dialysis_type'], 'TPE');
        $swapIsTpe = str_contains($swap->meta['dialysis_type'], 'TPE');
        $tpeOrderDate = AcuteHemodialysisOrderNote::query()->where('date_note', $order->date_note)->where('meta->dialysis_type', config('database.ilike'), '%TPE%')->count();
        $tpeSwapDate = AcuteHemodialysisOrderNote::query()->where('date_note', $swap->date_note)->where('meta->dialysis_type', config('database.ilike'), '%TPE%')->count();
        if (
            ($orderIsTpe && ! $swapIsTpe && $tpeSwapDate === $this->LIMIT_TPE_SLOTS)
            || (! $orderIsTpe && $swapIsTpe && $tpeOrderDate === $this->LIMIT_TPE_SLOTS)
        ) {
            $order->update(['meta->swap_code' => $this->genSwapCode()]);
            $swap->update(['meta->swap_code' => $this->genSwapCode()]);

            return [
                'type' => 'danger',
                'title' => 'Cannot swap slot.',
                'message' => 'TPE limit conflict, swap code regenerated.',
            ];
        }

        if ($order->meta['in_unit']) {
            $orderSlot = $this->slotCount($order->meta['dialysis_type']);
            $swapSlot = $this->slotCount($swap->meta['dialysis_type']);
            if ($orderSlot !== $swapSlot) {
                $orderDateSlots = $this->getNotes($order->date_note->format('Y-m-d'), $user);
                $orderDateAvailableSlots = $this->LIMIT_IN_UNIT_SLOTS - $orderDateSlots->sum('slot_count') + $orderSlot;
                $swapDateSlots = $this->getNotes($swap->date_note->format('Y-m-d'), $user);
                $swapDateAvailableSlots = $this->LIMIT_IN_UNIT_SLOTS - $swapDateSlots->sum('slot_count') + $swapSlot;

                if ($orderSlot > $swapDateAvailableSlots || $swapSlot > $orderDateAvailableSlots) {
                    $order->update(['meta->swap_code' => $this->genSwapCode()]);
                    $swap->update(['meta->swap_code' => $this->genSwapCode()]);

                    return [
                        'type' => 'danger',
                        'title' => 'Cannot swap slot.',
                        'message' => 'Not enough slot, swap code regenerated.',
                    ];
                }
            }
        }

        if ($order->date_note->format('Y-m-d') === $this->TODAY || $swap->date_note->format('Y-m-d') === $this->TODAY) {
            /** @var AcuteHemodialysisSlotRequest $request */
            $request = $order->changeRequests()->create([
                'requester_id' => $user->id,
                'changes' => [
                    'swap' => $swap->id,
                    'date_note' => $this->TODAY,
                ],
                'authority_ability_id' => $this->APPROVE_ACUTE_HEMODIALYSIS_SLOT_REQUEST_ABILITY_ID,
            ]);
            $request->actionLogs()->create([
                'action' => 'create',
                'actor_id' => $user->id,
            ]);
            $order->actionLogs()->create([
                'action' => 'request_change',
                'actor_id' => $user->id,
                'payload' => ['request_id' => $request->id],
            ]);
            $order->update([
                'status' => 'scheduling',
                'meta->submitted' => $order->status === 'submitted',
                'meta->swap_code' => $this->genSwapCode(),
            ]);

            $swap->actionLogs()->create([
                'action' => 'request_change',
                'actor_id' => $swap->author_id,
                'payload' => ['request_id' => $request->id],
            ]);
            $swap->update([
                'status' => 'scheduling',
                'meta->submitted' => $swap->status === 'submitted',
                'meta->swap_code' => $this->genSwapCode(),
            ]);

            return [
                'type' => 'warning',
                'title' => 'Request pending for approval.',
                'message' => 'Swap '.$order->meta['name'].' on '.$order->date_note->format('M j').' WITH '.$swap->meta['name'].' on '.$swap->date_note->format('M j').' request submitted, swap code regenerated.',
            ];
        }

        $order->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'reschedule',
            'payload' => [
                'from' => $order->date_note->format('Y-m-d'),
                'to' => $swap->date_note->format('Y-m-d'),
                'swap_with' => $swap->id,
            ],
        ]);
        $swap->actionLogs()->create([
            'actor_id' => $swap->author_id,
            'action' => 'reschedule',
            'payload' => [
                'from' => $swap->date_note->format('Y-m-d'),
                'to' => $order->date_note->format('Y-m-d'),
                'swap_with' => $order->id,
            ],
        ]);
        $dateSwap = $order->date_note;
        $order->update([
            'date_note' => $swap->date_note,
            'meta->swap_code' => $this->genSwapCode(),
            'meta->title' => $order->genTitle($swap->date_note->format('Y-m-d')),
        ]);
        $swap->update([
            'date_note' => $dateSwap,
            'meta->swap_code' => $this->genSwapCode(),
            'meta->title' => $swap->genTitle($dateSwap->format('Y-m-d')),
        ]);

        return [
            'type' => 'info',
            'title' => 'Swap successful.',
            'message' => 'Swap '.$order->meta['name'].' on '.$order->date_note->format('M j').' WITH '.$swap->meta['name'].' on '.$swap->date_note->format('M j').', swap code regenerated.',
        ];
    }
}
