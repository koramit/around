<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Note;
use App\Rules\NameExistsInWards;
use App\Traits\AcuteHemodialysis\OrderShareValidatable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SlotAvailableAction extends AcuteHemodialysisAction
{
    use OrderShareValidatable;

    protected $LIMIT_IN_UNIT_SLOTS = 32;

    protected $LIMIT_TPE_SLOTS = 3;

    protected $LIMIT_OUT_UNIT_CASES = 6;

    /**
     * @todo complete out unit slot
     */
    public function __invoke(array $data)
    {
        // validate
        $validated = Validator::make($data, [
            'date_note' => 'required|date',
            'dialysis_at' => ['required', 'string', 'max:255', new NameExistsInWards],
            'dialysis_type' => ['required', 'string', Rule::in($this->getAllDialysisType())],
        ])->validate();
        /*
         * - ไตเทียม ตาม slot 8*4 ไม่ทำ sledd
         * - tpe ทำที่ไตเทียมเท่านั้น
         * - tpe ทำวันละ 3 เท่านั้น
         * - sledd ทำนอกไตเทียมเท่านั้น
         * - นอกไตเทียมทำ hd4 hd+hf ได้วันละ 2 เคส
         * - นอกไตเทียมทำ sleddได้วันละ = (6 - (hd4 + (hf+hf))) เคส
         * - นอกเวลาให้ f submit แล้วรอ chief nurse accept
         * - นอกเวลาในแต่ละวันจะทำนอกหรือในไตเทียมเท่านั้น
         */

        return str_starts_with($validated['dialysis_at'], 'ไตเทียม')
            ? $this->inUnitSlots(dateNote: $validated['date_note'], dialysisType: $validated['dialysis_type'])
            : $this->outUnitSlots(dateNote: $validated['date_note'], dialysisType: $validated['dialysis_type']);
    }

    protected function outUnitSlots(string $dateNote, string $dialysisType): array
    {
        $notes = $this->getNotes(dateNote: $dateNote, inUnit: false);

        $hemoCount = $notes->count()
                        ? $notes->filter(fn ($n) => (strpos($n['type'], 'HD') !== false) || (strpos($n['type'], 'HF') !== false))->count()
                        : 0;

        $available = true;
        $reply = 'ok';

        if ($notes->count() === $this->LIMIT_OUT_UNIT_CASES) {
            $available = false;
            $reply = 'All cases limit reached for the date';
        } elseif ($dialysisType !== 'SLEDD' && $hemoCount === 2) {
            $available = false;
            $reply = 'HD/HF cases limit reached for the date';
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

    protected function getNotes(string $dateNote, bool $inUnit = true)
    {
        return Note::with(['patient', 'author', 'caseRecord'])
            ->where('note_type_id', $this->ACUTE_HD_ORDER_NOTE_TYPE_ID)
            ->where('date_note', $dateNote)
            ->whereNull('canceled_at')
            ->where('meta->in_unit', $inUnit)
            ->get()
            ->transform(function ($note) use ($inUnit) {
                $trans = [
                    'case_record_route' => route('procedures.acute-hemodialysis.edit', $note->caseRecord->hashed_key),
                    'patient_name' => $note->patient->profile['first_name'],
                    'author' => $note->author->name,
                    'type' => explode(' ', $note->meta['dialysis_type'])[0],
                ];
                if ($inUnit) {
                    $trans['tpe'] = str_contains(strtolower($note->meta['dialysis_type']), 'tpe') ? 1 : 0;
                    $trans['slot_count'] = $this->getSlotCount($note->meta['dialysis_type']);
                    $trans['available'] = false;
                }

                return $trans;
            });
    }

    protected function inUnitSlots(string $dateNote, string $dialysisType): array
    {
        $notes = $this->getNotes($dateNote);

        $tpeCount = $notes->sum('tpe');
        $sum = $notes->sum('slot_count');
        $requestSlot = $this->getSlotCount($dialysisType);
        $available = true;
        $reply = 'ok';

        if (($this->LIMIT_IN_UNIT_SLOTS - $sum) < $requestSlot) {
            $available = false;
            $reply = 'not enough slots';
        }

        if ($available && str_contains(strtolower($dialysisType), 'tpe') && $tpeCount === $this->LIMIT_TPE_SLOTS) {
            $available = false;
            $reply = 'TPE limit has been reached';
        }

        if (! $available) {
            return [
                'slots' => $notes,
                'available' => $available,
                'reply' => $reply,
            ];
        }

        $availableSlots = $this->LIMIT_IN_UNIT_SLOTS - $sum;
        for ($i = 1; $i <= $availableSlots; $i++) {
            $notes->push([
                'slot_count' => 1,
                'available' => true,
            ]);
        }

        $groupBySlotCount = [[]];
        $sumGroup = [0];
        for ($i = 1; $i <= 3; $i++) {
            $groupBySlotCount[] = $notes->filter(fn ($n) => $n['slot_count'] == $i)->values();
        }

        /*
         * prefer order
         * 3 slots NEED 1 slots => must follow with 1 slot
         * 2 slots
         * 1 slot treated as free slot
         */
        $ordered = collect([]);

        foreach ($groupBySlotCount[3] as $n) {
            $ordered->push($n);
            if ($groupBySlotCount[1]->count()) {
                $ordered->push($groupBySlotCount[1]->shift());
            }
        }

        foreach ($groupBySlotCount[2] as $n) {
            $ordered->push($n);
        }

        foreach ($groupBySlotCount[1] as $n) {
            $ordered->push($n);
        }

        return [
            'slots' => $ordered->reverse()->values(),
            'available' => $available,
            'reply' => $reply,
            'notes' => $notes,
        ];
    }

    protected function getSlotCount(string $dialysisType): int
    {
        if ($dialysisType === 'SLEDD') {
            return 4;
        } elseif (str_starts_with($dialysisType, 'HD+TPE')) {
            return 3;
        } elseif (strpos($dialysisType, '4') !== false || strpos($dialysisType, '3') !== false) {
            return 2;
        } else {
            return 1;
        }
    }
}
