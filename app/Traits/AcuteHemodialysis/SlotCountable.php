<?php

namespace App\Traits\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use Illuminate\Support\Collection;

trait SlotCountable
{
    protected $LIMIT_IN_UNIT_SLOTS = 32;

    protected $LIMIT_TPE_SLOTS = 3;

    protected $LIMIT_OUT_UNIT_CASES = 6;

    protected function getNotes(string $dateNote, bool $inUnit = true)
    {
        return AcuteHemodialysisOrderNote::query()
            ->with(['patient', 'author', 'attendingStaff', 'caseRecord'])
            ->where('date_note', $dateNote)
            ->where('meta->in_unit', $inUnit)
            ->activeStatuses()
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

    protected function orderInUnitSlot(Collection $slots): Collection
    {
        $availableSlots = $this->LIMIT_IN_UNIT_SLOTS - $slots->sum('slot_count');
        for ($i = 1; $i <= $availableSlots; $i++) {
            $slots->push([
                'slot_count' => 1,
                'available' => true,
            ]);
        }

        $groupBySlotCount = [[]];
        $sumGroup = [0];
        for ($i = 1; $i <= 3; $i++) {
            $groupBySlotCount[] = $slots->filter(fn ($n) => $n['slot_count'] == $i)->values();
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

        return $ordered;
    }
}
