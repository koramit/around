<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Casts\AcuteHemodialysisOrderStatus;
use App\Models\CaseRecord;
use App\Models\DocumentChangeRequest;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;

class CaseRecordIndexAction extends AcuteHemodialysisAction
{
    public function __invoke(array $filters, User $user): array
    {
        /*
         * @todo Optimize search on meta
         */
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api + query params
        }

        $cases = CaseRecord::query()
            ->with([
                'patient',
                'notes' => fn ($q) => $q->withAuthorUsername()
                    ->where('note_type_id', $this->ACUTE_HD_ORDER_NOTE_TYPE_ID)
                    ->whereIn('status', (new AcuteHemodialysisOrderStatus)->getActiveStatusCodes()),
            ])->where('registry_id', $this->REGISTRY_ID)
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where('meta->name', 'like', $search.'%')
                        ->orWhere('meta->hn', 'like', $search.'%');
            })->orderByDesc(
                AcuteHemodialysisOrderNote::select('created_at')
                    ->whereColumn('notes.case_record_id', 'case_records.id')
                    ->latest('date_note')
                    ->take(1)
            )->paginate($user->items_per_page)
            ->withQueryString()
            ->through(fn ($case) => [
                'hn' => $case->patient->hn,
                'patient_name' => $case->patient->full_name,
                'date_note' => $case->notes->first()?->date_note?->format('M j'),
                'dialysis_type' =>$case->notes->first()?->meta['dialysis_type'],
                'md' => $case->notes->first()?->author_username,
                'routes' => [
                    'edit' => route('procedures.acute-hemodialysis.edit', $case->hashed_key),
                ],
            ]);

        // ['hn', 'patient_name', 'request', 'requester']
        $requests = DocumentChangeRequest::query()
            ->with(['changeable', 'requester'])
            ->where(fn ($q) => $q->where('requester_id', $user->id))
            ->where('changeable_type', 'App\Models\Notes\AcuteHemodialysisOrderNote')
            ->get()
            ->transform(function ($request) {
                return [
                    'hn' => $request->changeable->meta['hn'],
                    'patient_name' => $request->changeable->meta['name'],
                    'request' => $request->changeable->getChangRequestText($request->changes),
                    'requester' => $request->requester->first_name,
                ];
            });

        $flash = [
            'page-title' => 'Acute Hemodialysis',
            'main-menu-links' => [
                ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
                ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
                ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures'), 'can' => true],
            ],
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
                'slot' => route('procedures.acute-hemodialysis.slot'),
            ],
            'requests' => $requests,
            'flash' => $flash,
        ];
    }
}
