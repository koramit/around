<?php

namespace App\Actions\Clinics\PostKT;

use App\Enums\KidneyTransplantSurvivalCaseStatus;
use App\Extensions\Auth\AvatarUser;
use App\Managers\Resources\AdmissionManager;
use App\Managers\Resources\PatientManager;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\Resources\Registry;
use App\Models\User;
use App\Traits\AvatarLinkable;

class ExportSummaryCasesForMayHotHot
{
    use AvatarLinkable;

    public function __invoke(User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $sheet = KidneyTransplantSurvivalCaseRecord::query()
            ->where('status', KidneyTransplantSurvivalCaseStatus::ACTIVE)
            ->get()
            ->sortBy(fn (KidneyTransplantSurvivalCaseRecord $c) => $c->meta['kt_no'])
            ->values()
            ->map(function ($c) {
                $admissionManager = new AdmissionManager();
                $admission = $c->meta['an'] ? $admissionManager->manage($c->meta['an']) : null;
                $admission = $admission['admission'] ?? null;
                $patientManager = new PatientManager();
                $patient = $patientManager->manage($c->meta['hn']);
                $patient = $patient['patient'] ?? null;
                return [
                    'KTNO' => $c->meta['kt_no'],
                    'HN' => $c->meta['hn'],
                    'title' => $patient?->profile['title'] ?? null,
                    'first_name' => $patient?->profile['first_name'] ?? null,
                    'last_name' => $patient?->profile['last_name'] ?? null,
                    'admitted_at' => $admission?->encountered_at?->tz('Asia/Bangkok')?->format('Y-m-d') ?? null,
                    'Txdate' => $c->meta['date_transplant'],
                    'discharged_at' => $admission?->dismissed_at?->tz('Asia/Bangkok')?->format('Y-m-d') ?? null,
                    'Donortype' => $c->meta['donor_type'] === 'LD' ? 'LD' : 'CD',
                    'medical_scheme_by_form' => $c->form['medical_scheme'],
                    'medical_scheme_by_link' => $patient?->profile['insurance_name'] ?? null,
                    'AN' => $c->meta['an'],
                    'LOS' => $admission?->length_of_stay ?? null,
                ];
            });

        return [
            'sheet' => $sheet,
            'filename' => 'kt_summary_cases_for_may_hot_hot' . now()->format('Y-m-d') . '.xlsx',
        ];
    }
}
