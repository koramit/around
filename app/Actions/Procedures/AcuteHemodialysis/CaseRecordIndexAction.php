<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Registries\AcuteHemodialysisCaseRecord as CaseRecord;
use App\Models\User;

class CaseRecordIndexAction extends AcuteHemodialysisAction
{
    /**
     * @todo Optimize search on meta
     */
    public function __invoke(array $filters, User $user): array
    {
        if (config('auth.guards.web.provider' === 'avatar')) {
            return []; // call api + query params
        }

        $cases = CaseRecord::query()
            ->with(['patient', 'orders' => fn ($q) => $q->with('author')->activeStatuses()])
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where('meta->name', 'like', $search.'%')
                    ->orWhere('meta->hn', 'like', $search.'%');
            })->orderByDesc(
                AcuteHemodialysisOrderNote::query()
                    ->select('created_at')
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
                'status' => $this->styleOrderStatus($case->orders->first()?->status),
                'md' => $case->orders->first()?->author->first_name,
                'can' => [
                    'edit_order' => $case->orders->first() ?? $user->can('edit', $case->orders->first()),
                ],
                'routes' => [
                    'edit' => route('procedures.acute-hemodialysis.edit', $case->hashed_key),
                    'edit_order' => $case->orders->first() ? route('procedures.acute-hemodialysis.orders.edit', $case->orders->first()?->hashed_key) : null,
                    'create_order' => route('procedures.acute-hemodialysis.edit', ['hashedKey' => $case->hashed_key, 'section' => 'reservation']),
                ],
            ]);

        $flash = [
            'page-title' => 'Acute Hemodialysis - Cases',
            'main-menu-links' => $this->MENU,
            'navs' => $this->NAVS,
            'action-menu' => [],
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

    protected function styleOrderStatus(mixed $status): ?string
    {
        if (! $status) {
            return null;
        }

        return view('partials.status-badge.acute-hd-order')->with(['status' => $status])->toHtml();
    }
}
