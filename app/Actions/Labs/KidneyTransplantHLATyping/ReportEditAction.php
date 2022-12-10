<?php

namespace App\Actions\Labs\KidneyTransplantHLATyping;

use App\Extensions\Auth\AvatarUser;
use App\Models\Notes\KidneyTransplantAdditionTissueTypingNote;
use App\Models\Notes\KidneyTransplantCrossmatchNote;
use App\Models\Notes\KidneyTransplantHLATypingNote;
use App\Models\Resources\Patient;
use App\Models\User;
use App\Traits\FlashDataGeneratable;

class ReportEditAction extends ReportAction
{
    use FlashDataGeneratable;

    public function __invoke(string $hashedKey, User|AvatarUser $user)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $report = $this->getReport($hashedKey);

        if ($user->cannot('update', $report) && $user->cannot('addendum', $report)) {
            abort(403, 'You are not allowed to edit this report.');
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
        $patientAdditionTissueTypingNote = $report->meta['patient_addition_tissue_typing_note_key']
            ? KidneyTransplantAdditionTissueTypingNote::query()->findByUnhashKey($report->meta['patient_addition_tissue_typing_note_key'])->first()
            : null;
        $donorAdditionTissueTypingNote = $report->meta['donor_addition_tissue_typing_note_key']
            ? KidneyTransplantAdditionTissueTypingNote::query()->findByUnhashKey($report->meta['donor_addition_tissue_typing_note_key'])->first()
            : null;

        $form = $report->form;
        $form['patient_hla_note'] = $patientHLANote?->form;
        $form['donor_hla_note'] = $donorHLANote?->form;
        $form['patient_cxm_note'] = $patientCXMNote?->form;
        $form['donor_cxm_note'] = $donorCXMNote?->form;
        $form['patient_addition_tissue_typing_note'] = $patientAdditionTissueTypingNote?->form;
        $form['donor_addition_tissue_typing_note'] = $donorAdditionTissueTypingNote?->form;

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
        $meta['request_addition_tissue'] = $report->meta['request_addition_tissue'];
        $meta['date_serum'] = $report->date_note->format('Y-m-d');
        $meta['patients'] = $donor
            ? ['patient', 'donor']
            : ['patient'];
        $formConfigs = [
            'routes' => [
                'update' => route('labs.kt-hla-typing.reports.update', $report->hashed_key),
                'clinicians' => route('resources.api.people'),
                'clinicians_scope_params' => '&position=8&division_id=6',
                'upload' => [
                    'store' => route('uploads.store'),
                    'show' => route('uploads.show'),
                ],
                'destroy' => route('labs.kt-hla-typing.reports.destroy', $report->hashed_key),
                'publish' => route('labs.kt-hla-typing.reports.publish', $report->hashed_key),
                'cancel' => route('labs.kt-hla-typing.reports.cancel', $report->hashed_key),
                'addendum' => route('labs.kt-hla-typing.reports.addendum', $report->hashed_key),
            ],
            'can' => [
                'update' => $user->can('update', $report),
                'destroy' => $user->can('destroy', $report),
                'addendum' => $user->can('addendum', $report),
                'cancel' => $user->can('cancel', $report),
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
            'additionAntigens' => [
                ['name' => 'dqa1', 'label' => 'dqa1*'],
                ['name' => 'dqa2', 'label' => 'dqa1*'],
                ['name' => 'dpa1', 'label' => 'dpa1*'],
                ['name' => 'dpa2', 'label' => 'dpa1*'],
                ['name' => 'dpb1', 'label' => 'dpb1*'],
                ['name' => 'dpb2', 'label' => 'dpb1*'],
                ['name' => 'mica1', 'label' => 'mica'],
                ['name' => 'mica2', 'label' => 'mica'],
            ],
            'lymphocyteCrossmatchOptions' => $this->LYMPHOCYTE_CROSSMATCH_OPTIONS,
            'recipient_is_options' => $report->patient->gender === 'female'
                ? $this->FEMALE_RECIPIENT_IS_OPTIONS
                : $this->MALE_RECIPIENT_IS_OPTIONS,
            'donor_is_options' => $this->DONOR_IS_OPTIONS,
            'abo_options' => $this->ABO_OPTIONS,
            'rh_options' => $this->RH_OPTIONS,
            'upload_pathname' => 'l/k/h',
        ];

        $flash = $this->getFlash($report->title, $user);
        $flash['action-menu'] = $this->getActionMenu(
            $report,
            $user,
            $report->status === 'draft'
            ? ['publish', 'destroy']
            : ['addendum', 'cancel']
        );

        $flash['breadcrumbs'] = [
            ['label' => 'Home', 'route' => route('home')],
            ['label' => 'Labs', 'route' => route('labs.index')],
            ['label' => 'KT HLA Typing Report', 'route' => route('labs.kt-hla-typing.reports.index')],
        ];
        $flash['hn'] = $report->meta['hn'];

        if (! $formConfigs['can']['update']) {
            $flash['message'] = [
                'type' => 'warning',
                'title' => 'Autosave disabled.',
                'message' => 'Please Addendum to save changes.',
            ];
        }

        $formConfigs['actions'] = $flash['action-menu'];

        return [
            'formData' => $form,
            'metaData' => $meta,
            'formConfigs' => $formConfigs,
            'flash' => $flash,
        ];
    }
}
