<?php

namespace App\Actions\Wards\KidneyTransplantAdmission;

use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\KidneyTransplantAdmissionCaseRecord;
use App\Models\Resources\Admission;
use App\Models\Resources\Registry;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Exception\WriterNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;

class CaseRecordExportAction extends KidneyTransplantAdmissionAction
{
    protected Collection $labs;

    /**
     * @throws IOException
     * @throws WriterNotOpenedException
     * @throws UnsupportedTypeException
     * @throws InvalidArgumentException
     */
    public function __invoke(User|AvatarUser $user)
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        $ldSheet = [];
        $cdSheet = [];
        KidneyTransplantAdmissionCaseRecord::query()
            ->with(['patient'])
            ->whereNotIn('status', [3, 5])
            ->each(function ($case) use (&$ldSheet, &$cdSheet) {
                if (($case->form['donor_type'] ?? null) === 'LD') {
                    $ldSheet[] = $this->getLD($case);
                } elseif (str_starts_with(($case->form['donor_type'] ?? ''), 'CD')) {
                    $cdSheet[] = $this->getCD($case);
                }
            });

        $sheets = new SheetCollection([
            'CD KT' => $cdSheet,
            'LRD KT' => $ldSheet,
        ]);

        $today = Carbon::now()->format('Ymd');

        $registry = Registry::query()->find($this->REGISTRY_ID);
        $registry->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'export',
            'payload' => [
                'report' => 'case_records',
            ],
        ]);

        return (new FastExcel($sheets))->download("kt_admission_case_records_{$today}.xlsx");
    }

    protected function getLD(KidneyTransplantAdmissionCaseRecord &$case): array
    {
        $form = $case->form;
        $row = [];
        $admission = Admission::query()->findByHashKey($case->meta['an'])->first();
        $row['KT No.'] = null;
        $row['case_status'] = $case->status;
        $row['patient_gender'] = $case->patient->gender;
        $row['patient_age'] = $admission->patient_age_at_encounter;
        $row['patient_abo'] = $form['blood_group_abo'];
        $row['patient_rh'] = $form['blood_group_rh'];
        $row['dx'] = $form['cause_of_esrd'];

        $row['relation'] = $form['donor_is'];
        $row['donor_gender'] = null;
        $row['donor_age'] = null;
        $row['donor_abo'] = $form['donor_blood_group_abo'] ?? null;
        $row['donor_rh'] = $form['donor_blood_group_rh'] ?? null;

        $row['date_op'] = Carbon::create($form['datetime_operation_start'])->format('Y-m-d');
        $row['HLA_MM_A'] = $form['hla_mismatch_a'];
        $row['HLA_MM_B'] = $form['hla_mismatch_b'];
        $row['HLA_MM_DR'] = $form['hla_mismatch_dr'];
        $row['HLA_MM_DQ'] = $form['hla_mismatch_dq'];
        $row['CXM_CDC'] = $form['crossmatch_cdc'];
        $row['CXM_CDC_specific'] = $form['crossmatch_cdc_positive_specification'];
        $row['CXM_CDC_AHG'] = $form['crossmatch_cdc_ahg'];
        $row['CXM_CDC_AHG_specific'] = $form['crossmatch_cdc_ahg_positive_specification'];
        $row['CXM_Flow'] = $form['crossmatch_flow_cxm'];
        $row['CXM_Flow_specific'] = $form['crossmatch_flow_cxm_positive_specification'];

        $row['induction'] = null;
        $row['graft_function'] = $form['graft_function'];
        $row['complication'] = $this->getComplicationText($form['complications']);
        $row['cr_at_d/c'] = $form['creatinine_at_discharge'];
        $row['LOS'] = $admission->length_of_stay;
        $row['cost'] = $form['cost'];
        $row['medical_scheme'] = $form['insurance'];
        $row['dc_at'] = $admission->dismissed_at?->format('Y-m-d');
        $row['hn'] = $case->patient->hn;
        $row['name'] = $case->patient->full_name;

        return $row;
    }

    protected function getComplicationText(array $complications): string
    {
        if ($complications['none']) {
            return 'None';
        }

        unset($complications['none'], $complications['attachments']);

        $complicationSet = collect([]);
        foreach ($complications as $key => $value) {
            if (! $value) {
                continue;
            }
            $complicationSet->push(str_replace('_', ' ', $key));
        }

        return $complicationSet->join(', ');
    }

    protected function getCD(KidneyTransplantAdmissionCaseRecord &$case): array
    {
        $form = $case->form;
        $row = [];
        $admission = Admission::query()->findByHashKey($case->meta['an'])->first();
        $row['KT No.'] = null;
        $row['case_status'] = $case->status;
        $row['patient_gender'] = $case->patient->gender;
        $row['patient_age'] = $admission->patient_age_at_encounter;
        $row['patient_abo'] = $form['blood_group_abo'];
        $row['patient_rh'] = $form['blood_group_rh'];
        $row['dx'] = $form['cause_of_esrd'];

        $row['hospital'] = $form['donor_cd_hospital'];
        $row['donor_gender'] = null;
        $row['donor_age'] = null;
        $row['donor_abo'] = $form['donor_blood_group_abo'] ?? null;
        $row['donor_rh'] = $form['donor_blood_group_rh'] ?? null;
        $row['donor_cr_at_harvest'] = null;

        $row['date_op'] = Carbon::create($form['datetime_operation_start'])->format('Y-m-d');
        $row['CIT'] = ($form['cold_ischemic_time_hours'] ?? '0').':'.($form['cold_ischemic_time_minutes'] ?? '0');
        $row['HLA_MM_A'] = $form['hla_mismatch_a'];
        $row['HLA_MM_B'] = $form['hla_mismatch_b'];
        $row['HLA_MM_DR'] = $form['hla_mismatch_dr'];
        $row['HLA_MM_DQ'] = $form['hla_mismatch_dq'];
        $row['CXM_CDC'] = $form['crossmatch_cdc'];
        $row['CXM_CDC_specific'] = $form['crossmatch_cdc_positive_specification'];
        $row['CXM_CDC_AHG'] = $form['crossmatch_cdc_ahg'];
        $row['CXM_CDC_AHG_specific'] = $form['crossmatch_cdc_ahg_positive_specification'];
        $row['CXM_Flow'] = $form['crossmatch_flow_cxm'];
        $row['CXM_Flow_specific'] = $form['crossmatch_flow_cxm_positive_specification'];
        $row['PRA_class_I'] = $form['pra_class_i_percent'];
        $row['PRA_class_II'] = $form['pra_class_ii_percent'];

        $row['induction'] = null;
        $row['graft_function'] = $form['graft_function'];
        $row['complication'] = $this->getComplicationText($form['complications']);
        $row['cr_at_d/c'] = $form['creatinine_at_discharge'];
        $row['LOS'] = $admission->length_of_stay;
        $row['cost'] = $form['cost'];
        $row['medical_scheme'] = $form['insurance'];
        $row['dc_at'] = $admission->dismissed_at?->format('Y-m-d');
        $row['hn'] = $case->patient->hn;
        $row['name'] = $case->patient->full_name;

        return $row;
    }

    /*protected function getCr(Admission $admission): ?float
    {
        if (!$admission->dismissed_at) {
            return null;
        }

        return $this->labs->filter(function ($lab) use($admission) {
            return $lab['hn'] == $admission->patient->hn
                && $lab['date_lab'] >= $admission->encountered_at
                && $lab['date_lab'] <= $admission->dismissed_at;
        })->sortByDesc('date_lab')
            ->first()['cr'] ?? null;
    }*/
}
