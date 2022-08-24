<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Registries\AcuteHemodialysisCaseRecord as CaseRecord;
use App\Models\User;
use App\Traits\HomePageSelectable;

class CaseRecordIndexAction extends AcuteHemodialysisAction
{
    use HomePageSelectable;

    /**
     * @todo Optimize search on meta, add scope of status
     */
    public function __invoke(array $filters, User $user, string $routeName = 'home'): array
    {
        if (config('auth.guards.web.provider' === 'avatar')) {
            return []; // call api + query params
        }

        $ilike = config('database.ilike');

        $cases = CaseRecord::query()
            ->with(['patient', 'orders' => fn ($q) => $q->withAuthorName()->activeStatuses()])
            ->where('status', '<>', 6)
            ->when($filters['search'] ?? null, function ($query, $search) use ($ilike) {
                $query->where('meta->name', $ilike, $search.'%')
                    ->orWhere('meta->hn', $ilike, $search.'%');
            })->orderByDesc(
                AcuteHemodialysisOrderNote::query()
                    ->select('date_note')
                    ->whereColumn('notes.case_record_id', 'case_records.id')
                    ->latest('date_note')
                    ->take(1)
            )->paginate($user->items_per_page)
            ->withQueryString()
            ->through(fn ($case) => [
                'hn' => $case->patient->hn,
                'patient_name' => $case->patient->full_name,
                'date_note' => $case->orders->first()?->date_note?->format('M j'),
                'dialysis_type' => $case->orders->first()?->meta['dialysis_type'],
                'dialysis_at' => $case->orders->first() ? ($case->orders->first()->meta['in_unit'] ? 'in' : 'out') : null,
                'status' => $this->styleStatusBadge($case->orders->first()?->status),
                'md' => $this->getFirstName($case->orders->first()?->author_name),
                'can' => [
                    'edit_order' => $case->orders->first() && $user->can('edit', $case->orders->first()),
                    'create_order' => ! $case->orders->first() && $user->can('create_acute_hemodialysis_order'),
                    'view_order' => $case->orders->first() && $user->can('view', $case->orders->first()),
                ],
                'routes' => [
                    'edit' => route('procedures.acute-hemodialysis.edit', $case->hashed_key),
                    'edit_order' => $case->orders->first() ? route('procedures.acute-hemodialysis.orders.edit', $case->orders->first()?->hashed_key) : null,
                    'view_order' => $case->orders->first() ? route('procedures.acute-hemodialysis.orders.show', $case->orders->first()?->hashed_key) : null,
                    'create_order' => route('procedures.acute-hemodialysis.orders.create-shortcut', $case->hashed_key),
                ],
            ]);

        $flash = [
            'page-title' => 'Acute Hemodialysis - Cases',
            'main-menu-links' => $this->MENU,
            'navs' => $this->NAVS,
            'action-menu' => [
                $this->getSetHomePageActionMenu($routeName, $user),
            ],
        ];

        return [
            'cases' => $cases,
            'filters' => [
                'search' => $filters['search'] ?? '',
                'scope' => $filters['scope'] ?? 'all',
            ],
            'routes' => [
                'index' => route('procedures.acute-hemodialysis.index'),
                'store' => route('procedures.acute-hemodialysis.store'),
                'serviceEndpoint' => route('resources.api.patient-recently-admission.show'),
            ],
            'can' => [
                'create' => $user->can('create_acute_hemodialysis_case'),
            ],
            'flash' => $flash,
        ];
    }
}
