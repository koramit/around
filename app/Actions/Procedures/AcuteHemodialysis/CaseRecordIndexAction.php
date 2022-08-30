<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Models\User;
use App\Traits\HomePageSelectable;

class CaseRecordIndexAction extends AcuteHemodialysisAction
{
    use HomePageSelectable;

    /* @TODO Optimize search on meta, add scope of status */
    public function __invoke(array $filters, User $user, string $routeName = 'home'): array
    {
        if (config('auth.guards.web.provider' === 'avatar')) {
            return []; // call api + query params
        }

        $cases = AcuteHemodialysisCaseRecord::query()
            ->select(['id', 'patient_id', 'status'])
            ->with(['patient:id,profile,hn', 'orders' => fn ($q) => $q->select(['id', 'case_record_id', 'author_id', 'status', 'meta', 'date_note'])
                    ->withAuthorName()
                    ->activeStatuses(),
            ])->metaSearchTerms($filters['search'] ?? null)
            ->filterStatus($filters['scope'] ?? null)
            ->orderByDesc(
                AcuteHemodialysisOrderNote::query()
                    ->select('date_note')
                    ->activeStatuses()
                    ->whereColumn('notes.case_record_id', 'case_records.id')
                    ->latest('date_note')
                    ->take(1)
            )->orderBy('status')
            ->paginate($user->items_per_page)
            ->withQueryString()
            ->through(fn ($case) => [
                'hn' => $case->patient->hn,
                'patient_name' => $case->patient->full_name,
                'case_status' => $case->status,
                'date_note' => $case->orders->first()?->date_note?->format('M j'),
                'dialysis_type' => $case->orders->first()?->meta['dialysis_type'],
                'dialysis_at' => $case->orders->first() ? ($case->orders->first()->meta['in_unit'] ? 'in' : 'out') : null,
                'status' => $this->styleStatusBadge($case->orders->first()?->status),
                'md' => $this->getFirstName($case->orders->first()?->author_name),
                'can' => [
                    'edit_order' => $case->orders->first() && $user->can('edit', $case->orders->first()),
                    'create_order' => $case->status === 'active'
                        && ! $case->orders->first()
                        && $user->can('create_acute_hemodialysis_order'),
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
            'configs' => [
                'scopes' => ['active', 'incomplete', 'valid', 'empty'],
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
