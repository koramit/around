<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\User;
use App\Rules\NameExistsInWards;
use App\Traits\AcuteHemodialysis\OrderShareValidatable;
use App\Traits\AcuteHemodialysis\SlotCountable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SlotAvailableAction extends AcuteHemodialysisAction
{
    use OrderShareValidatable, SlotCountable;

    protected User $user;

    /**
     * @todo complete out unit slot
     */
    public function __invoke(array $data, User $user): array
    {
        // validate
        $validated = Validator::make($data, [
            'date_note' => 'required|date',
            'dialysis_at' => ['required', 'string', 'max:255', new NameExistsInWards],
            'dialysis_type' => ['required', 'string', Rule::in($this->getAllDialysisType())],
        ])->validate();

        $this->user = $user;
        /*
         * - ไตเทียม ตาม slot 8*4 ไม่ทำ sledd
         * - tpe ทำที่ไตเทียมเท่านั้น ** เปลี่ยนเป็นทำที่วอร์ดด้วย 2022/06/29 **
         * - tpe ทำวันละ 3 เท่านั้น
         * - sledd ทำนอกไตเทียมเท่านั้น
         * - นอกไตเทียมทำ hd4 hd+hf ได้วันละ 2 เคส
         * - นอกไตเทียมทำ sledd ได้วันละ = (6 - (hd4 + (hf+hf))) เคส
         * - นอกเวลาให้ f submit แล้วรอ chief nurse accept
         * - นอกเวลาในแต่ละวันจะทำนอกหรือในไตเทียมเท่านั้น
         */

        return str_starts_with($validated['dialysis_at'], 'ไตเทียม')
            ? $this->inUnitSlots(dateNote: $validated['date_note'], dialysisType: $validated['dialysis_type'])
            : $this->outUnitSlots(dateNote: $validated['date_note'], dialysisType: $validated['dialysis_type']);
    }

    protected function outUnitSlots(string $dateNote, string $dialysisType): array
    {
        $notes = $this->getNotes(dateNote: $dateNote, user: $this->user, inUnit: false);

        $hemoCount = $notes->count()
                        ? $notes->filter(fn ($n) => (str_contains($n['type'], 'HD')) || (str_contains($n['type'], 'HF')))->count()
                        : 0;

        $available = true;
        $reply = 'ok';

        if ($notes->count() === $this->LIMIT_OUT_UNIT_CASES) {
            $available = false;
            $reply = 'All cases limit reached for the date';
        } elseif ($dialysisType !== 'SLEDD' && $hemoCount === 2) {
            $available = false;
            $reply = 'HD/HF cases limit reached for the date';
        } elseif (str_contains($dialysisType, 'TPE') && $this->tpeCaseCount($dateNote) === $this->LIMIT_TPE_SLOTS) {
            $available = false;
            $reply = 'TPE limit has been reached';
        }

        if ($available) {
            $availableCount = ($this->LIMIT_OUT_UNIT_CASES - $notes->count());
            for ($i = 1; $i <= $availableCount; $i++) {
                $notes[] = ['type' => null];
            }
        }

        return [
            'slots' => $notes,
            'available' => $available,
            'reply' => $reply,
            'hemoCount' => $hemoCount,
        ];
    }

    protected function inUnitSlots(string $dateNote, string $dialysisType): array
    {
        $orders = $this->getNotes(dateNote: $dateNote, user: $this->user);
        $chronic = $orders->filter(fn ($o) => $o['dialysis_at_chronic_unit'])->values();
        if ($chronic->count() !== 0) {
            $orders = $orders->filter(fn ($o) => ! $o['dialysis_at_chronic_unit'])->values();
        }

        $requestSlot = $this->slotCount($dialysisType);
        $available = true;
        $reply = 'ok';

        if (($this->LIMIT_IN_UNIT_SLOTS - $orders->sum('slot_count')) < $requestSlot) {
            $available = false;
            $reply = 'not enough slots';
        } elseif (str_contains(strtolower($dialysisType), 'tpe') && $this->tpeCaseCount($dateNote) === $this->LIMIT_TPE_SLOTS) {
            $available = false;
            $reply = 'TPE limit has been reached';
        }

        $sorted = $this->orderInUnitSlot($orders);

        return [
            'slots' => $sorted->reverse()->values(),
            'chronic' => $chronic,
            'available' => $available,
            'reply' => $reply,
        ];
    }
}
