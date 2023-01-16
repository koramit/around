<?php

namespace App\Actions\Labs\KidneyTransplantHLATyping;

use App\Models\Notes\KidneyTransplantAdditionTissueTypingNote;
use App\Models\Notes\KidneyTransplantCrossmatchNote;
use App\Models\Notes\KidneyTransplantHLATypingNote;
use App\Models\Notes\KidneyTransplantHLATypingReportNote;
use App\Models\Registries\KidneyTransplantHLATypingCaseRecord as CaseRecord;
use App\Models\Resources\Patient;
use App\Models\Subscription;
use App\Models\User;
use App\Rules\HnExists;
use App\Traits\CaseRecordFinishable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReportStoreAction extends ReportAction
{
    use CaseRecordFinishable;

    public function __invoke(array $data, mixed $user)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $validated = Validator::validate($data, [
            'patient_type' => ['required', 'string', Rule::in($this->PATIENT_TYPES)],
            'hn' => ['required', 'digits:8', new HnExists()],
            'date_serum' => ['required', 'date'],
            'donor_hn' => ['nullable', 'digits:8', new HnExists()],
            'request_hla' => ['required', 'bool', Rule::requiredIf(! $data['request_cxm'] && ! $data['request_addition_tissue'])],
            'request_cxm' => ['required', 'bool', Rule::requiredIf(! $data['request_hla'] && ! $data['request_addition_tissue'])],
            'request_addition_tissue' => ['required', 'bool', Rule::requiredIf(! $data['request_hla'] && ! $data['request_cxm'])],
        ]);

        $duplicate = KidneyTransplantHLATypingReportNote::query()
            ->where('date_note', now()->create($validated['date_serum']))
            ->where('meta->hn', (int) $validated['hn'])
            ->where('meta->donor_hn', $validated['donor_hn'] ? (int) $validated['donor_hn'] : null)
            ->where('meta->request_hla', (bool) $validated['request_hla'])
            ->where('meta->request_cxm', (bool) $validated['request_cxm'])
            ->where('meta->request_addition_tissue', (bool) $validated['request_addition_tissue'])
            ->first();

        if ($duplicate) {
            return ['key' => $duplicate->hashed_key];
        }

        $patient = Patient::query()->findByHashedKey($validated['hn'])->first();
        $donor = ($validated['donor_hn'] ?? null)
            ? Patient::query()->findByHashedKey($validated['donor_hn'])->first()
            : null;

        // if no case yet then create one
        $patientCaseRecord = $this->findOrCreateCaseRecord($patient, $user);
        $donorCaseRecord = $donor
            ? $this->findOrCreateCaseRecord($donor, $user)
            : null;

        // if hla then create hla note
        $patientHLANote = null;
        $donorHLANote = null;
        if ($validated['request_hla']) {
            $patientHLANote = $this->createNote($patientCaseRecord, $patient, $user, 'hla', $validated['date_serum']);
            if ($donor) {
                $donorHLANote = $this->createNote($donorCaseRecord, $patient, $user, 'hla', $validated['date_serum']);
            }
        }

        // if cxm then create cxm note
        $patientCXMNote = null;
        $donorCXMNote = null;
        if ($validated['request_cxm']) {
            $patientCXMNote = $this->createNote($patientCaseRecord, $patient, $user, 'cxm', $validated['date_serum']);
            if ($donor) {
                $donorCXMNote = $this->createNote($donorCaseRecord, $patient, $user, 'cxm', $validated['date_serum']);
            }
        }

        // if addition tissue then create addition tissue note
        $patientAdditionTissueTypingNote = null;
        $donorAdditionTissueTypingNote = null;
        if ($validated['request_addition_tissue']) {
            $patientAdditionTissueTypingNote = $this->createNote(
                $patientCaseRecord,
                $patient,
                $user,
                'addition_tissue_typing',
                $validated['date_serum']
            );
            if ($donor) {
                $donorAdditionTissueTypingNote = $this->createNote(
                    $donorCaseRecord,
                    $patient,
                    $user,
                    'addition_tissue_typing',
                    $validated['date_serum']
                );
            }
        }

        // create report note
        $report = new KidneyTransplantHLATypingReportNote();
        $report->case_record_id = $patientCaseRecord->id;
        $report->date_note = $validated['date_serum'];
        $form['diagnosis'] = null;
        $form['clinician'] = null;
        $form['date_report'] = null;
        $form['reporter'] = null;
        $form['approver'] = null;
        $form['recipient_is'] = null;
        $form['donor_is'] = null;
        $form['scanned_report'] = null;
        $form['remark'] = null;
        $report->form = $form;
        $report->meta = [
            'hn' => $patient->hn,
            'name' => $patient->first_name,
            'donor_hn' => $donor?->hn,
            'donor_name' => $donor?->first_name,
            'version' => 1.0,
            'request_hla' => $validated['request_hla'],
            'request_addition_tissue' => $validated['request_addition_tissue'],
            'request_cxm' => $validated['request_cxm'],
            'patient_hla_note_key' => $patientHLANote?->hashed_key,
            'patient_addition_tissue_typing_note_key' => $patientAdditionTissueTypingNote?->hashed_key,
            'patient_cxm_note_key' => $patientCXMNote?->hashed_key,
            'donor_hla_note_key' => $donorHLANote?->hashed_key,
            'donor_addition_tissue_typing_note_key' => $donorAdditionTissueTypingNote?->hashed_key,
            'donor_cxm_note_key' => $donorCXMNote?->hashed_key,
        ];
        $report->author_id = $user->id;
        $report->save();
        $report->update(['meta->title' => $report->genTitle()]);

        $sub = Subscription::query()->create([
            'subscribable_type' => $report::class,
            'subscribable_id' => $report->id,
        ]);

        if ($user->auto_subscribe_to_channel) {
            $user->subscriptions()->attach($sub->id);
        }

        $report->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'create',
        ]);

        return [
            'key' => $report->hashed_key,
        ];
    }

    protected function findOrCreateCaseRecord(Patient $patient, User $user): CaseRecord
    {
        /** @var CaseRecord $caseRecord */
        if ($caseRecord = CaseRecord::query()
            ->where('status', 1) // active
            ->where('patient_id', $patient->id)
            ->first()) {
            return $caseRecord;
        }

        $caseRecord = new CaseRecord;
        $caseRecord->patient_id = $patient->id;
        $caseRecord->form = [];
        $caseRecord->meta = [
            'version' => 1.0, // $this->CRF_VERSION,
            'hn' => $patient->hn,
            'name' => $patient->first_name,
        ];
        $caseRecord->save();
        $caseRecord->update(['meta->title' => $caseRecord->genTitle()]);
        $this->finishing($caseRecord, $patient, $user, $this->REGISTRY_ID);

        return $caseRecord;
    }

    protected function createNote(CaseRecord $caseRecord, Patient $patient, User $user, string $noteType, string $dateNote): null|KidneyTransplantHLATypingNote|KidneyTransplantCrossmatchNote|KidneyTransplantAdditionTissueTypingNote
    {
        $data = [
            'case_record_id' => $caseRecord->id,
            'form' => [],
            'date_note' => $dateNote,
            'author_id' => $user->id,
            'meta' => [
                'hn' => $patient->hn,
                'name' => $patient->first_name,
                'version' => 1.0,
            ],
        ];
        if ($noteType === 'hla') {
            $data['form'] = [
                'date_hla_typing' => null,
                'abo' => null,
                'rh' => null,
                'hla_typing_class_i_a1' => null,
                'hla_typing_class_i_a2' => null,
                'hla_typing_class_i_b1' => null,
                'hla_typing_class_i_b2' => null,
                'hla_typing_class_i_c1' => null,
                'hla_typing_class_i_c2' => null,
                'hla_typing_class_i_bw4' => null,
                'hla_typing_class_i_bw6' => null,
                'hla_typing_class_ii_drb11' => null,
                'hla_typing_class_ii_drb12' => null,
                'hla_typing_class_ii_drb31' => null,
                'hla_typing_class_ii_drb32' => null,
                'hla_typing_class_ii_drb41' => null,
                'hla_typing_class_ii_drb42' => null,
                'hla_typing_class_ii_drb51' => null,
                'hla_typing_class_ii_drb52' => null,
                'hla_typing_class_ii_dqb11' => null,
                'hla_typing_class_ii_dqb12' => null,
                'hla_typing_mismatch' => null,
            ];
            $note = KidneyTransplantHLATypingNote::query()->create($data);
        } elseif ($noteType === 'cxm') {
            $data['form'] = [
                'date_cross_matching' => null,
                't_lymphocyte_cdc_neat_rt' => null,
                't_lymphocyte_cdc_neat_37_degree_celsius' => null,
                't_lymphocyte_cdc_dtt_rt' => null,
                't_lymphocyte_cdc_dtt_37_degree_celsius' => null,
                't_lymphocyte_cdc_ahg_neat_rt' => null,
                't_lymphocyte_cdc_ahg_dtt_rt' => null,
                'b_lymphocyte_cdc_neat_37_degree_celsius' => null,
                'b_lymphocyte_cdc_dtt_37_degree_celsius' => null,
                'b_lymphocyte_cdc_ahg_neat_37_degree_celsius' => null,
                'b_lymphocyte_cdc_ahg_dtt_37_degree_celsius' => null,
                't_lymphocyte_crossmatch' => null,
                'b_lymphocyte_crossmatch' => null,
            ];
            $note = KidneyTransplantCrossmatchNote::query()->create($data);
        } elseif ($noteType === 'addition_tissue_typing') {
            $data['form'] = [
                'date_addition_tissue_typing' => null,
                'tissue_typing_dqa1' => null,
                'tissue_typing_dqa2' => null,
                'tissue_typing_dpa1' => null,
                'tissue_typing_dpa2' => null,
                'tissue_typing_dpb1' => null,
                'tissue_typing_dpb2' => null,
                'tissue_typing_mica1' => null,
                'tissue_typing_mica2' => null,
            ];
            $note = KidneyTransplantAdditionTissueTypingNote::query()->create($data);
        } else {
            return null;
        }

        $sub = Subscription::query()->create([
            'subscribable_type' => $note::class,
            'subscribable_id' => $note->id,
        ]);

        if ($user->auto_subscribe_to_channel) {
            $user->subscriptions()->attach($sub->id);
        }

        $note->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'create',
        ]);

        return $note;
    }
}
