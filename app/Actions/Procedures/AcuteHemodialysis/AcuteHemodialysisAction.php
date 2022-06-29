<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

class AcuteHemodialysisAction
{
    protected $REGISTRY_ID = 1;

    protected $ACUTE_HD_ORDER_NOTE_TYPE_ID;

    protected $TIMEZONE = 7;

    protected $LIMIT_ADVANCE_DAYS = 3;

    protected $PATIENT_TYPES = ['Acute', 'Chronic'];

    protected $IN_UNIT_WARD_ID = 72;

    public function __construct()
    {
        $this->ACUTE_HD_ORDER_NOTE_TYPE_ID = config('notes.acute_hd_order');
    }

    protected function reserveAvailableDates(): array
    {
        $availableDates = [];
        $start = now()->tz($this->TIMEZONE)->addDay();
        $count = 0;
        do {
            if (! $start->isSunday()) {
                $availableDates[] = $start->format('Y-m-d');
            }
            $start->addDay();
            $count++;
        } while ($count < $this->LIMIT_ADVANCE_DAYS);

        return $availableDates;
    }
}
