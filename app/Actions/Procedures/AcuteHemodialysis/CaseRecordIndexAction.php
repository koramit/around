<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\CaseRecord;
use App\Models\Note;

class CaseRecordIndexAction extends AcuteHemodialysisAction
{
    public function __invoke(array $filters)
    {
        /*
         * @todo Optimize search on meta
         */
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api + query params
        }

        $cases = CaseRecord::query()
            ->where('registry_id', $this->REGISTRY_ID)
            ->with(['patient', 'latestAcuteOrder' => fn ($q) => $q->withAuthorUsername()])
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where('meta->name', 'like', $search.'%')
                        ->orWhere('meta->hn', 'like', $search.'%');
            })->orderByDesc(
                Note::select('date_note')
                ->whereColumn('notes.case_record_id', 'case_records.id')
                ->latest('date_note')
                ->take(1)
            )->paginate(10)
            ->withQueryString()
            ->through(fn ($case) => [
                'hn' => $case->patient->hn,
                'patient_name' => $case->patient->full_name,
                'date_dialyze' =>$case->latestAcuteOrder?->date_note?->format('M j'),
                'date_reserved' =>$case->latestAcuteOrder?->created_at?->tz($this->TIMEZONE)?->format('M j'),
                'md' => $case->latestAcuteOrder?->author_username,
                'routes' => [
                    'edit' => route('procedures.acute-hemodialysis.edit', $case->hashed_key),
                ],
            ]);

        $this->setFlash([
            'page-title' => 'Acute Hemodialysis',
            'main-menu-links' => [
                ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
                ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
                ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures'), 'can' => true],
            ],
            'action-menu' => [],
        ]);

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
        ];
    }
}
