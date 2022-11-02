<?php

namespace App\Actions\Labs\KidneyTransplantHLATyping;

use App\Models\Notes\KidneyTransplantCrossmatchNote;
use App\Models\Notes\KidneyTransplantHLATypingNote;
use App\Models\Notes\KidneyTransplantHLATypingReportNote;
use App\Models\Resources\Patient;
use App\Traits\AvatarLinkable;
use App\Traits\FlashDataGeneratable;

class ReportEditAction extends ReportAction
{
    use AvatarLinkable, FlashDataGeneratable;

    public function __invoke(string $hashedKey, mixed $user)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $report = KidneyTransplantHLATypingReportNote::query()->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('update', $report)) {
            abort(403);
        }

        $patientHLANote = $report->meta['patient_hla_note_key']
            ? KidneyTransplantHLATypingNote::query()->findByUnhashKey($report->meta['patient_hla_note_key'])->first()
            : null;
        $donorHLANote = $report->meta['donor_hla_note_key']
            ? KidneyTransplantHLATypingNote::query()->findByUnhashKey($report->meta['donor_hla_note_key'])->first()
            : null;
        $patientCXMNote = $report->meta['patient_cxm_note_key']
            ? KidneyTransplantCrossmatchNote::query()->findByUnhashKey($report->meta['patient_cxm_note_key'])->first()
            : null;
        $donorCXMNote = $report->meta['donor_cxm_note_key']
            ? KidneyTransplantCrossmatchNote::query()->findByUnhashKey($report->meta['donor_cxm_note_key'])->first()
            : null;

        $form = $report->form;
        $form['patient_hla_note'] = $patientHLANote?->form;
        $form['donor_hla_note'] = $donorHLANote?->form;
        $form['patient_cxm_note'] = $patientCXMNote?->form;
        $form['donor_cxm_note'] = $donorCXMNote?->form;

        $patient = $report->patient;
        $donor = $report->meta['donor_hn']
            ? Patient::query()->findByHashedKey($report->meta['donor_hn'])->first()
            : null;
        $meta = [];
        $meta['patient_hn'] = $patient->hn;
        $meta['patient_name'] = $patient->full_name;
        $meta['donor_hn'] = $donor?->hn;
        $meta['donor_name'] = $donor?->full_name;
        $meta['request_hla'] = $report->meta['request_hla'];
        $meta['request_cxm'] = $report->meta['request_cxm'];
        $meta['date_serum'] = $report->date_note->format('Y-m-d');
        $meta['patients'] = $donor
            ? ['patient', 'donor']
            : ['patient'];
        $formConfigs = [
            'routes' => [
                'update' => route('labs.kt-hla-typing.reports.update', $report->hashed_key),
                'clinicians' => route('resources.api.people'),
                'clinicians_scope_params' => '&position=8&division_id=6',
            ],
            'antigens' => [
                ['name' => 'a1', 'label' => 'a'],
                ['name' => 'a2', 'label' => 'a'],
                ['name' => 'b1', 'label' => 'b'],
                ['name' => 'b2', 'label' => 'b'],
                ['name' => 'c1', 'label' => 'c'],
                ['name' => 'c2', 'label' => 'c'],
                ['name' => 'bw4', 'label' => 'bw4'],
                ['name' => 'bw6', 'label' => 'bw6'],
            ],
            'classIIAntigens' => [
                ['name' => 'drb11', 'label' => 'drb1*', 'group' => 1],
                ['name' => 'drb12', 'label' => 'drb1*', 'group' => 1],
                ['name' => 'drb3', 'label' => 'drb3*', 'group' => 2],
                ['name' => 'drb4', 'label' => 'drb4*', 'group' => 2],
                ['name' => 'drb5', 'label' => 'drb5*', 'group' => 2],
                ['name' => 'dqb11', 'label' => 'dqb1*', 'group' => 3],
                ['name' => 'dqb12', 'label' => 'dqb1*', 'group' => 3],
            ],
            'lymphocyteCrossmatchOptions' => $this->LYMPHOCYTE_CROSSMATCH_OPTIONS,
            'recipient_is_options' => $this->RECIPIENT_IS_OPTIONS,
            'donor_is_options' => $this->DONOR_IS_OPTIONS,
            'abo_options' => $this->ABO_OPTIONS,
            'rh_options' => $this->RH_OPTIONS,
        ];

        $flash = $this->getFlash($report->meta['title'], $user);
        $flash['breadcrumbs'] = [
            ['label' => 'Home', 'route' => route('home')],
            ['label' => 'Labs', 'route' => route('labs.index')],
            ['label' => 'KT HLA Typing Report', 'route' => route('labs.kt-hla-typing.reports.index')],
        ];
        $flash['hn'] = $report->meta['hn'];

        return [
            'formData' => $form,
            'metaData' => $meta,
            'formConfigs' => $formConfigs,
            'flash' => $flash,
        ];
    }
}
