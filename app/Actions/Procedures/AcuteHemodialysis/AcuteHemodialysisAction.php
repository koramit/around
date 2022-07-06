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

    protected $MENU;

    protected $NAVS;

    protected $BREADCRUMBS;

    protected $TODAY;

    protected $APPROVE_ACUTE_HEMODIALYSIS_TODAY_SLOT_REQUEST_ABILITY_ID = 10;

    protected $STAFF_DIVISION_ID = 4;

    public function __construct()
    {
        $this->ACUTE_HD_ORDER_NOTE_TYPE_ID = config('notes.acute_hd_order');
        $this->MENU = [
            ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
            ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
            ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures'), 'can' => true],
            ['icon' => 'comment-alt', 'label' => 'Feedback', 'route' => route('feedback'), 'can' => true],
        ];
        $this->NAVS = [
            ['label' => 'Cases', 'route' => route('procedures.acute-hemodialysis.index')],
            ['label' => 'Schedule', 'route' => route('procedures.acute-hemodialysis.schedule')],
            ['label' => 'Requests', 'route' => route('procedures.acute-hemodialysis.slot-requests')],
        ];
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
