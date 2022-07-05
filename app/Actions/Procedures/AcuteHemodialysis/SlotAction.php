<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use Illuminate\Support\Facades\Validator;

class SlotAction extends AcuteHemodialysisAction
{
    protected $LIMIT_IN_UNIT_SLOTS = 32;

    protected $LIMIT_OUT_UNIT_CASES = 6;

    /**
     * @todo complete out unit slot
     */
    public function __invoke(array $data)
    {
        // validate
        $validated = Validator::make($data, ['date_note' => 'required|date'])->validate();

        $hdUnit = $this->getNotes(dateNote: $validated['date_note'], inUnit: true);
        $availableSlots = $this->LIMIT_IN_UNIT_SLOTS - $hdUnit->sum('slot_count');
        for ($i = 1; $i <= $availableSlots; $i++) {
            $hdUnit->push([
                'slot_count' => 1,
                'available' => true,
            ]);
        }

        $ward = $this->getNotes(dateNote: $validated['date_note'], inUnit: false);
        $availableCount = ($this->LIMIT_OUT_UNIT_CASES - $ward->count());
        for ($i = 1; $i <= $availableCount; $i++) {
            $ward[] = ['type' => null];
        }

        return [
            'hd_unit' => $hdUnit,
            'ward' => $ward,
        ];
    }

    protected function getNotes(string $dateNote, bool $inUnit = true)
    {
        return AcuteHemodialysisOrderNote::with(['patient', 'author', 'attendingStaff', 'caseRecord'])
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
                    'attending' => $note->attendingStaff->first_name,
                ];
                if ($inUnit) {
                    $trans['slot_count'] = $this->slotCount($note->meta['dialysis_type']);
                    $trans['available'] = false;
                }

                return $trans;
            });
    }

    protected function slotCount(string $dialysisType): int
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
