<?php

namespace App\Console\Commands;

use App\Models\Registries\KidneyTransplantAdmissionCaseRecord;
use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;

class ExportWardKTSoc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:ward-kt-soc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sheet = KidneyTransplantAdmissionCaseRecord::query()
            ->with('patient')
            ->where('meta->reason_for_admission', 'kt')
            ->get()
            ->transform(function (KidneyTransplantAdmissionCaseRecord $case) {
               return $this->transform($case);
            });

        (new FastExcel($sheet))->export(storage_path('app/kt_for_nuch.xlsx'));
    }

    protected function transform(KidneyTransplantAdmissionCaseRecord $case): array
    {
        return [
            'hn' => $case->meta['hn'],
            'name' => $case->patient->full_name,
            'nephrologist' => $case->form['nephrologist'],
            'surgeon' => $case->form['surgeon'],
            'date_off_drain' => $case->form['date_off_drain'],
            'date_off_foley' => $case->form['date_off_foley'],
            'insurance' => $case->form['insurance'],
            'cost' => $case->form['cost'],
            'tel_no' => $case->form['tel_no'],
            'alternative_contact' => $case->form['alternative_contact'],
            'patient_transferred_to' => $case->form['patient_transferred_to'],
            'cause_of_esrd' => $case->form['cause_of_esrd'],
            'donor_type' => $case->form['donor_type'],
            'recipient_is' => $case->form['recipient_is'],
            'donor_is' => $case->form['donor_is'],
            'blood_group_abo' => $case->form['blood_group_abo'],
            'blood_group_rh' => $case->form['blood_group_rh'],
            'hla_mismatch_a' => $case->form['hla_mismatch_a'],
            'hla_mismatch_b' => $case->form['hla_mismatch_b'],
            'hla_mismatch_dr' => $case->form['hla_mismatch_dr'],
            'hla_mismatch_dq' => $case->form['hla_mismatch_dq'],
            'pra_class_i_percent' => $case->form['pra_class_i_percent'],
            'pra_class_ii_percent' => $case->form['pra_class_ii_percent'],
            'crossmatch_cdc' => $case->form['crossmatch_cdc'],
            'crossmatch_cdc_positive_specification' => $case->form['crossmatch_cdc_positive_specification'],
            'crossmatch_cdc_ahg' => $case->form['crossmatch_cdc_ahg'],
            'crossmatch_cdc_ahg_positive_specification' => $case->form['crossmatch_cdc_ahg_positive_specification'],
            'crossmatch_flow_cxm' => $case->form['crossmatch_flow_cxm'],
            'crossmatch_flow_cxm_positive_specification' => $case->form['crossmatch_flow_cxm_positive_specification'],
            'donor_cd_hospital' => $case->form['donor_cd_hospital'],
            'donor_creatinine_before_harvest' => $case->form['donor_creatinine_before_harvest'],
            'immunosuppressive_drugs_induction.none' => $case->form['immunosuppressive_drugs_induction']['none'] ? 'Yes' : 'No',
            'immunosuppressive_drugs_induction.simulect' => $case->form['immunosuppressive_drugs_induction']['simulect'] ? 'Yes' : 'No',
            'immunosuppressive_drugs_induction.ATG' => $case->form['immunosuppressive_drugs_induction']['ATG'] ? 'Yes' : 'No',
            'immunosuppressive_drugs_induction.rituximab' => $case->form['immunosuppressive_drugs_induction']['rituximab'] ? 'Yes' : 'No',
            'immunosuppressive_drugs_induction.induction_other' => $case->form['immunosuppressive_drugs_induction']['induction_other'],
            'datetime_harvest_start' => $case->form['datetime_harvest_start'],
            'datetime_harvest_finish' => $case->form['datetime_harvest_finish'],
            'datetime_operation_start' => $case->form['datetime_operation_start'],
            'datetime_operation_finish' => $case->form['datetime_operation_finish'],
            'cold_ischemic_time_hours' => $case->form['cold_ischemic_time_hours'],
            'cold_ischemic_time_minutes' => $case->form['cold_ischemic_time_minutes'],
            'warm_ischemic_time_minutes' => $case->form['warm_ischemic_time_minutes'],
            'anastomosis_time_minutes' => $case->form['anastomosis_time_minutes'],
            'datetime_clamp_at_donor' => $case->form['datetime_clamp_at_donor'],
            'datetime_perfusion' => $case->form['datetime_perfusion'],
            'datetime_remove_from_ice' => $case->form['datetime_remove_from_ice'],
            'datetime_unclamp_all' => $case->form['datetime_unclamp_all'],
            'graft_function' => $case->form['graft_function'],
            'creatinine_at_discharge' => $case->form['creatinine_at_discharge'],
            'delayed_graft_function_dialysis_mode' => $case->form['delayed_graft_function_dialysis_mode'],
            'date_delayed_graft_function_dialysis_start' => $case->form['date_delayed_graft_function_dialysis_start'],
            'delayed_graft_function_dialysis_indication_hyper_k' => $case->form['delayed_graft_function_dialysis_indication_hyper_k'] ? 'Yes' : 'No',
            'delayed_graft_function_dialysis_indication_volume_overload' => $case->form['delayed_graft_function_dialysis_indication_volume_overload'] ? 'Yes' : 'No',
            'delayed_graft_function_dialysis_indication_uremia' => $case->form['delayed_graft_function_dialysis_indication_uremia'] ? 'Yes' : 'No',
            'delayed_graft_function_dialysis_indication_other' => $case->form['delayed_graft_function_dialysis_indication_other'],
            'graft_function_graft_nephrectomy' => $case->form['graft_function_graft_nephrectomy'] ? 'Yes' : 'No',
            'remarks' => null,
        ];
    }
}
