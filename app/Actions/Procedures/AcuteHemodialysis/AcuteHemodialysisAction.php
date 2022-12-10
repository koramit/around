<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Ability;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Resources\NoteType;
use App\Models\Resources\Registry;
use App\Models\User;
use App\Traits\AvatarLinkable;
use App\Traits\FirstNameAware;
use App\Traits\FlashDataGeneratable;
use App\Traits\Subscribable;
use Hashids\Hashids;

class AcuteHemodialysisAction
{
    use FirstNameAware, FlashDataGeneratable, AvatarLinkable, Subscribable;

    protected int $REGISTRY_ID;

    protected int $ACUTE_HD_ORDER_NOTE_TYPE_ID;

    protected int $TIMEZONE = 7;

    protected int $LIMIT_ADVANCE_DAYS = 3;

    protected array $PATIENT_TYPES = ['Acute', 'Chronic'];

    protected int $IN_UNIT_WARD_ID = 72;

    protected array $NAVS;

    protected array $BREADCRUMBS;

    protected string $TODAY;

    protected int $APPROVE_ACUTE_HEMODIALYSIS_SLOT_REQUEST_ABILITY_ID;

    protected string $STAFF_SCOPE_PARAMS = '&position=8&division_id=6';

    protected string $UNIT_DAY_OFF = 'Sunday';

    protected string $LAST_HOUR_UTC = '13:00:00';

    public function __construct()
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return;
        }

        $this->REGISTRY_ID = cache()->rememberForever(
            'registry-id-acute_hd',
            fn () => Registry::query()->where('name', 'acute_hd')->first()->id
        );
        $this->ACUTE_HD_ORDER_NOTE_TYPE_ID = cache()->rememberForever(
            'note-type-id-acute_hd_order',
            fn () => NoteType::query()->where('name', 'acute_hd_order')->first()->id
        );
        $this->APPROVE_ACUTE_HEMODIALYSIS_SLOT_REQUEST_ABILITY_ID = cache()->rememberForever(
            'ability-id-approve_acute_hemodialysis_slot_request',
            fn () => Ability::query()->where('name', 'approve_acute_hemodialysis_slot_request')->first()->id
        );

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

    protected function getSerology(array $form): array
    {
        return [
            ['label' => 'HBS AG', 'result' => $form['hbs_ag'], 'date_lab' => $form['date_hbs_ag']],
            ['label' => 'ANTI HCV', 'result' => $form['anti_hcv'], 'date_lab' => $form['date_anti_hcv']],
            ['label' => 'ANTI HIV', 'result' => $form['anti_hiv'], 'date_lab' => $form['date_anti_hiv']],
        ];
    }

    protected function initOrderFlash(AcuteHemodialysisOrderNote $order, User $user)
    {
        $flash = $this->getFlash($order->title, $user);
        $flash['hn'] = $order->patient->hn;
        $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Discussion', 'type' => '#', 'route' => '#discussion', 'can' => true]);
        $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Prescription', 'type' => '#', 'route' => '#prescription', 'can' => true]);
        $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Predialysis', 'type' => '#', 'route' => '#predialysis-evaluation', 'can' => true]);
        $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Special requests', 'type' => '#', 'route' => '#special-requests', 'can' => true]);
        $flash['breadcrumbs'] = $this->getBreadcrumbs([
            ['label' => 'Case Record', 'route' => route('procedures.acute-hemodialysis.edit', app(Hashids::class)->encode($order->case_record_id))],
        ]);
        $flash['action-menu'] = [$this->getSubscriptionActionMenu($order, $user)];

        return $flash;
    }
}
