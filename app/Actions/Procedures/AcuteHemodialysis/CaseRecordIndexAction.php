<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Extensions\Auth\AvatarUser;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Models\User;
use App\Traits\HomePageSelectable;

class CaseRecordIndexAction extends AcuteHemodialysisAction
{
    use HomePageSelectable;

    public function __invoke(array $filters, User|AvatarUser $user, string $routeName = 'home'): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $mdModels = cache()->remember('acute-hd-md-options', now()->addHour(), function () {
            $mdIds = AcuteHemodialysisOrderNote::query()
                ->select('author_id')
                ->distinct('author_id')
                ->pluck('author_id');

            $mdOptionsTemp = User::query()
                ->whereIn('id', $mdIds)
                ->select('id', 'full_name')
                ->get();

            $mdOptions = [];
            $configMDOptions = [];
            foreach ($mdOptionsTemp as $md) {
                $mdOptions[] = [
                    'id' => $md->id,
                    'name' => $md->first_name,
                ];
                $configMDOptions[] = $md->first_name;
            }
            $collator = new \Collator('th_TH');
            $collator->sort($configMDOptions);

            return [
                'options' => $mdOptions,
                'configOptions' => $configMDOptions,
            ];
        });

        $lastMDFilteredCaseIds = null;
        if ($filters['md'] ?? null) {
            $authorId = collect($mdModels['options'])->filter(fn ($md) => $md['name'] === $filters['md'])->first()['id'] ?? null;
            if (! $authorId) {
                abort(404);
            }

            $statusScopedCaseIds = AcuteHemodialysisCaseRecord::query()
                ->select('id')
                ->filterStatus($filters['scope'] ?? null)
                ->pluck('id');

            $lastMDFilteredCaseIds = AcuteHemodialysisOrderNote::query()
                ->selectRaw('case_record_id, author_id, MAX(date_note) as latest_date')
                ->whereIn('case_record_id', $statusScopedCaseIds)
                ->slotOccupiedStatuses()
                ->groupBy('case_record_id')
                ->get()
                ->filter(fn ($n) => $n->author_id === $authorId)
                ->values()
                ->pluck('case_record_id');
        }

        $cases = AcuteHemodialysisCaseRecord::query()
            ->select(['id', 'patient_id', 'status'])
            ->with(['patient:id,profile,hn', 'orders' => fn ($query) => $query->select(['id', 'case_record_id', 'author_id', 'status', 'meta', 'date_note'])
                ->withAuthorName()
                ->slotOccupiedStatuses()
                ->orderByDesc('date_note'),
            ])->filterStatus($filters['scope'] ?? null)
            ->metaSearchTerms($filters['search'] ?? null)
            ->when($filters['md'] ?? null, fn ($query) => $query->whereIn('id', $lastMDFilteredCaseIds))
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

        $flash = $this->getFlash('Acute Hemodialysis - Cases', $user);
        $flash['navs'] = $this->NAVS;
        $flash['action-menu'][] = $this->getSetHomePageActionMenu($routeName, $user->home_page);
        $flash['action-menu'][] = [
            'label' => 'Export',
            'as' => 'a',
            'icon' => 'file-excel',
            'theme' => 'accent',
            'route' => route('procedures.acute-hemodialysis.export', $filters),
            'can' => $user->can('force_complete_case'),
        ];

        return [
            'cases' => $cases,
            'filters' => [
                'search' => $filters['search'] ?? '',
                'scope' => $filters['scope'] ?? 'active',
                'md' => $filters['md'] ?? '',
            ],
            'configs' => [
                'scopes' => ['active', 'incomplete', 'completed', 'valid', 'empty'],
                'mdOptions' => $mdModels['configOptions'],
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
