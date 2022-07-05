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

    protected $BREADCRUMBS;

    protected $TODAY;

    protected $APPROVE_ACUTE_HEMODIALYSIS_RESCHEDULE_TO_TODAY_ABILITY_ID = 10;

    public function __construct()
    {
        $this->ACUTE_HD_ORDER_NOTE_TYPE_ID = config('notes.acute_hd_order');
        $this->BREADCRUMBS = [
            ['label' => 'Home', 'route' => route('home')],
            ['label' => 'Procedures', 'route' => route('procedures')],
        ];
        $this->TODAY = now()->tz($this->TIMEZONE)->format('Y-m-d');
    }

    protected function reserveAvailableDates(): array
    {
        $availableDates = [];
        $start = now()->create($this->TODAY);
        $count = 0;
        do {
            if (! $start->isSunday()) {
                $availableDates[] = $start->format('Y-m-d');
            }
            $start->addDay();
            $count++;
        } while ($count <= $this->LIMIT_ADVANCE_DAYS);

        return $availableDates;
    }

    protected function getBreadcumbs(array $links): array
    {
        return array_merge($this->BREADCRUMBS, $links);
    }
}
