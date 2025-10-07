<?php

namespace App\Actions\Clinics\PostKT;

use App\Enums\KidneyTransplantSurvivalCaseStatus;
use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\Resources\Registry;
use App\Models\User;
use App\Traits\AvatarLinkable;

class ExportSummaryCases
{
    use AvatarLinkable;

    public function __invoke(User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $sheet = KidneyTransplantSurvivalCaseRecord::query()
            ->where('status', '!=', KidneyTransplantSurvivalCaseStatus::DELETED)
            ->get()
            ->sortBy(fn (KidneyTransplantSurvivalCaseRecord $c) => $c->meta['kt_no'])
            ->values()
            ->map(function ($c) {
                return [
                    'KTNO' => $c->meta['kt_no'],
                    'Txdate' => $c->meta['date_transplant'],
                    'Recipient_ID' => $c->meta['recipient_id'],
                    'Donor_ID' => $c->meta['donor_id'],
                    'Donortype' => $c->meta['donor_type'] === 'LD' ? 'LD' : 'CD',
                    'CD_RedCrossID' => $c->meta['donor_redcross_id'],
                    'CD_hospital' => $c->form['donor_hospital'],
                    'LD_HN' => $c->form['donor_hn'],
                    'LD_NAME' => $c->form['donor_name'],
                    'Recipient_ID_' => $c->meta['recipient_id'],
                    'RecipientHN' => $c->meta['hn'],
                    'Recipient_NAME' => trim($c->meta['name']),
                    'CASE_NO' => $c->case_no,
                    'medical_scheme' => $c->form['medical_scheme'],
                    'status' => $c->status->label(),
                    'date_last_update' => $c->form['date_last_update'],
                    'AN' => $c->meta['an'],
                ];
            });

        $registryId = cache()->rememberForever(
            'registry-id-kt_survival',
            fn () => Registry::query()->where('name', 'kt_survival')->first()->id
        );

        $registry = Registry::query()->find($registryId);
        $registry->actionLogs()->create([
            'action' => 'export',
            'actor_id' => $user->id,
            'payload' => [
                'report' => 'summary_cases',
            ],
        ]);

        return [
            'sheet' => $sheet,
            'filename' => 'kt_summary_cases_' . now()->format('Y-m-d') . '.xlsx',
        ];
    }
}
