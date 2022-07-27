<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Traits\FirstNameAware;

class AcuteHemodialysisAction
{
    use FirstNameAware;

    protected int $REGISTRY_ID;

    protected int $ACUTE_HD_ORDER_NOTE_TYPE_ID;

    protected int $TIMEZONE = 7;

    protected int $LIMIT_ADVANCE_DAYS = 3;

    protected array $PATIENT_TYPES = ['Acute', 'Chronic'];

    protected int $IN_UNIT_WARD_ID = 72;

    protected array $MENU;

    protected array $NAVS;

    protected array $BREADCRUMBS;

    protected string $TODAY;

    protected int $APPROVE_ACUTE_HEMODIALYSIS_TODAY_SLOT_REQUEST_ABILITY_ID = 10;

    protected string $STAFF_SCOPE_PARAMS = '&position=8&division_id=5';

    protected string $UNIT_DAY_OFF = 'Sunday';

    public function __construct()
    {
        $this->REGISTRY_ID = config('registries.acute_hd');
        $this->ACUTE_HD_ORDER_NOTE_TYPE_ID = config('notes.acute_hd_order');
        $this->MENU = [
            ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
            ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
            ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures.index'), 'can' => true],
            ['icon' => 'comment-alt', 'label' => 'Feedback', 'route' => route('feedback'), 'can' => true],
        ];
        $this->NAVS = [
            ['label' => 'Cases', 'route' => route('procedures.acute-hemodialysis.index')],
            ['label' => 'Schedule', 'route' => route('procedures.acute-hemodialysis.schedule')],
            ['label' => 'Requests', 'route' => route('procedures.acute-hemodialysis.slot-requests')],
        ];
        $this->BREADCRUMBS = [
            ['label' => 'Home', 'route' => route('home')],
            ['label' => 'Procedures', 'route' => route('procedures.index')],
            ['label' => 'Acute HD', 'route' => route('procedures.acute-hemodialysis.last-index-section')],
        ];
        $this->TODAY = now()->tz($this->TIMEZONE)->format('Y-m-d');
    }

    protected function styleStatusBadge(mixed $status, string $resource = 'order'): ?string
    {
        if (! $status) {
            return null;
        }

        return view("partials.status-badge.acute-hd-$resource")->with(['status' => $status])->toHtml();
    }

    protected function reserveAvailableDates(): array
    {
        $availableDates = [];
        $start = now()->create($this->TODAY);
        $count = 0;
        do {
            if (! $start->is($this->UNIT_DAY_OFF)) {
                $availableDates[] = $start->format('Y-m-d');
            }
            $start->addDay();
            $count++;
        } while ($count <= $this->LIMIT_ADVANCE_DAYS);

        return $availableDates;
    }

    protected function getBreadcrumbs(array $links): array
    {
        return array_merge($this->BREADCRUMBS, $links);
    }

    public function getToday(): string
    {
        return $this->TODAY;
    }

    public function getUnitDayOff(): string
    {
        return $this->UNIT_DAY_OFF;
    }
}
