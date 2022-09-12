<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Models\User;
use App\Traits\HomePageSelectable;

class CaseRecordIndexAction extends AcuteHemodialysisAction
{
    use HomePageSelectable;

    public function __invoke(array $filters, User $user, string $routeName = 'home'): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api + query params
        }

        $cases = AcuteHemodialysisCaseRecord::query()
            ->select(['id', 'patient_id', 'status'])
            ->with(['patient:id,profile,hn', 'orders' => fn ($query) => $query->select(['id', 'case_record_id', 'author_id', 'status', 'meta', 'date_note'])
                    ->withAuthorName()
                    ->slotoccupiedStatuses()
                    ->orderByDesc('date_note'),
            ])->filterStatus($filters['scope'] ?? null)
            ->metaSearchTerms($filters['search'] ?? null)
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
            ->through(function ($case) use ($user) {
                $activeOrder = $case->orders
                    ->filter(fn ($o) => collect(['started', 'finished'])->doesntContain($o->status))->first();
                $lastPerformedOrder = $case->orders
                    ->filter(fn ($o) => collect(['started', 'finished'])->contains($o->status))->first();

                return [
                    'hn' => $case->patient->hn,
                    'patient_name' => $case->patient->full_name,
                    'case_status' => $case->status,
                    'date_note' => $activeOrder?->date_note?->format('M j'),
                    'dialysis_type' => $activeOrder?->meta['dialysis_type'],
                    'dialysis_at' => $activeOrder ? ($activeOrder->meta['in_unit'] ? 'in' : 'out') : null,
                    'status' => $this->styleStatusBadge($activeOrder?->status),
                    'md' => $this->getFirstName(
                        ($activeOrder?->author_name) ?? ($lastPerformedOrder?->author_name)
                    ),
                    'can' => [
                        'edit_order' => $activeOrder && $user->can('edit', $activeOrder),
                        'create_order' => $case->status === 'active'
                            && ! $activeOrder
                            && $user->can('create_acute_hemodialysis_order'),
                        'view_order' => $activeOrder && $user->can('view', $activeOrder),
                    ],
                    'routes' => [
                        'edit' => route('procedures.acute-hemodialysis.edit', $case->hashed_key),
                        'edit_order' => $activeOrder ? route('procedures.acute-hemodialysis.orders.edit', $activeOrder->hashed_key) : null,
                        'view_order' => $activeOrder ? route('procedures.acute-hemodialysis.orders.show', $activeOrder->hashed_key) : null,
                        'create_order' => route('procedures.acute-hemodialysis.orders.create-shortcut', $case->hashed_key),
                    ],
                ];
            });

        $flash = [
            'page-title' => 'Acute Hemodialysis - Cases',
            'main-menu-links' => $this->MENU,
            'navs' => $this->NAVS,
            'action-menu' => [
                $this->getSetHomePageActionMenu($routeName, $user->home_page),
            ],
        ];

        return [
            'cases' => $cases,
            'filters' => [
                'search' => $filters['search'] ?? '',
                'scope' => $filters['scope'] ?? 'active',
            ],
            'configs' => [
                'scopes' => ['active', 'incomplete', 'completed', 'valid', 'empty'],
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
