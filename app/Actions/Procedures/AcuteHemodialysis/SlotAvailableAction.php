<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Note;

class SlotAvailableAction extends AcuteHemodialysisAction
{
    protected $LIMIT_IN_UNIT_SLOTS = 32;

    protected $LIMIT_TPE_SLOTS = 3;

    protected $LIMIT_OUT_UNIT_CASES = 6;

    /**
     * @todo complete out unit slot
     */
    public function __invoke(array $data)
    {
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

        return str_starts_with($data['dialysis_at'], 'ไตเทียม')
            ? $this->inUnitSlots($data)
            : $this->outUnitSlots(dateNote: $data['date_note'], dialysisType: $data['dialysis_type']);
    }

    protected function outUnitSlots(string $dateNote, string $dialysisType): array
    {
        $notes = $this->getNotes(dateNote: $dateNote, inUnit: false);

        $hemoCount = $notes->count()
                        ? $notes->countBy(fn ($n) => (strpos($n['type'], 'HD') !== false) || (strpos($n['type'], 'HF') !== false))
                        : 0;
        // $sleddCount = $notes->count()
        //                 ? $notes->countBy(fn ($n) => strpos($n->type, 'SLEDD') !== false)
        //                 : 0;

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
                    'type' => $note->form['dialysis_type'],
                ];
                if ($inUnit) {
                    $trans['tpe'] = str_contains(strtolower($note->form['dialysis_type']), 'tpe') ? 1 : 0;
                    $trans['slot_count'] = $this->getSlotCount($note->form['dialysis_type']);
                }

                return $trans;
            });
    }

    protected function inUnitSlots(array $data): array
    {
        $notes = $this->getNotes($data['date_note']);

        $tpeCount = $notes->sum('tpe');
        $sum = $notes->sum('slot_count');
        $requestSlot = $this->getSlotCount($data['dialysis_type']);
        $available = true;
        $reply = 'ok';

        if (($this->LIMIT_IN_UNIT_SLOTS - $sum) < $requestSlot) {
            $available = false;
            $reply = 'not enough slots';
        }

        if ($available && str_contains(strtolower($data['dialysis_type']), 'tpe') && $tpeCount === $this->LIMIT_TPE_SLOTS) {
            $available = false;
            $reply = 'TPE limit has been reached';
        }

        return [
            'slots' => $notes,
            'available' => $available,
            'reply' => $reply,
        ];
    }

    protected function getSlotCount($dialysisType): int
    {
        if ($dialysisType === 'SLEDD') {
            return 4;
        } elseif (str_starts_with($dialysisType, 'HD+TPE')) {
            return 3;
        } elseif (strpos($dialysisType, '4') !== -1 || strpos($dialysisType, '3') !== -1) {
            return 2;
        } else {
            return 1;
        }
    }
}
