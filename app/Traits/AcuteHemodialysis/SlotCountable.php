<?php

namespace App\Traits\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;
use Illuminate\Support\Collection;

trait SlotCountable
{
    protected int $LIMIT_IN_UNIT_SLOTS = 32;

    protected int $LIMIT_TPE_SLOTS = 3;

    protected int $LIMIT_OUT_UNIT_CASES = 6;

    protected function getNotes(string $dateNote, User $user, bool $inUnit = true): \Illuminate\Database\Eloquent\Collection|array
    {
        return AcuteHemodialysisOrderNote::query()
            ->select(['id', 'date_note', 'status', 'meta', 'author_id', 'attending_staff_id', 'case_record_id'])
            ->with(['author:id,profile', 'attendingStaff:id,name,position', 'caseRecord:id,meta'])
            ->where('date_note', $dateNote)
            ->where('meta->in_unit', $inUnit)
            ->slotOccupiedStatuses()
            ->get()
            ->transform(function ($note) use ($user, $inUnit) {
                $trans = [
                    'case_record_route' => route('procedures.acute-hemodialysis.edit', $note->caseRecord->hashed_key),
                    'patient_name' => $note->caseRecord->meta['name'],
                    'author' => 'พ.'.$note->author->first_name,
                    'type' => explode(' ', $note->meta['dialysis_type'])[0],
                    'status' => $note->status,
                    'attending' => $note->attendingStaff->first_name,
                    'covid_case' => $note->meta['covid_case'] ?? false,
                    'order_route' => $user->can('edit', $note)
                        ? route('procedures.acute-hemodialysis.orders.edit', $note->hashed_key)
                        : route('procedures.acute-hemodialysis.orders.show', $note->hashed_key),
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
        } elseif (str_contains($dialysisType, '4') || str_contains($dialysisType, '3')) {
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

        $groupBySlotCount = collect([[]]);
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

    protected function tpeCaseCount(string $dateNote): int
    {
        return AcuteHemodialysisOrderNote::query()->where('date_note', $dateNote)->where('meta->dialysis_type', config('database.ilike'), '%TPE%')->count();
    }
}
