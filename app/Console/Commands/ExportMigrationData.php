<?php

namespace App\Console\Commands;

use App\Casts\KidneyTransplantAdmissionCaseRecordStatus;
use App\Enums\KidneyTransplantSurvivalCaseStatus;
use App\Models\Notes\KidneyTransplantHLATypingNote;
use App\Models\Notes\KidneyTransplantHLATypingReportNote;
use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Models\Registries\KidneyTransplantAdmissionCaseRecord;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;
use Throwable;

class ExportMigrationData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:export-migration-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * @throws Throwable
     */
    public function handle(): void
    {
        $cases = KidneyTransplantSurvivalCaseRecord::query()
            ->with('patient')
            ->where('status', '!=', KidneyTransplantSurvivalCaseStatus::DELETED)
            ->whereBetween('meta->date_transplant', ['2025-01-01', '2025-12-31'])
            ->get();
        $rows = [];

        foreach ($cases as $case) {
            $rows[] = $this->addRow($case);

            /*if (!$case->form['date_first_rrt'] && !$case->form['rrt_mode']) {
                $this->warn($case->meta['kt_no'] . ' : MISSING RRT DATE');
            }*/
        }

        (new FastExcel($rows))->export(storage_path('migrate_kt.xlsx'));
    }

    protected function addRow(KidneyTransplantSurvivalCaseRecord $case): array
    {
        $wardCase = KidneyTransplantAdmissionCaseRecord::query()
            ->where('patient_id', $case->patient_id)
            ->where('meta->reason_for_admission', 'kt')
            ->whereIn('status', (new KidneyTransplantAdmissionCaseRecordStatus)->getActiveStatusCodes())
            ->first();
        /*if (!$wardCase) {
            $this->warn($case->meta['kt_no'] . ' : MISSING WARD CASE');
        }*/

        $row = [];
        /*$row['year'] = (int) explode('-', $case->meta['date_transplant'])[0];
        $row['id'] = $case->meta['recipient_id'];
        $row['donor'] = $case->meta['donor_type'];
        $row['graft_function'] = $case->form['graft_function'] ?? $wardCase?->form['graft_function'];
        return $row;*/
        $row['hn'] = $case->meta['hn']; // NEED recode
        $row['an'] = $case->meta['an']; // NEED recode
        $row['transplant_date'] = $case->meta['date_transplant'];
        $row['run_number'] = ((int) explode('-', $case->meta['date_transplant'])[0] + 543) % 100 * 1000 + (int) explode('-', $case->meta['kt_no'])[1];
        // $row['recipients_thai_tx_society_id'] = $case->meta['recipient_id'];
        // $row['donors_thai_tx_society_id'] = $case->meta['donor_id'];
        $row['deceased_donors_redcross_id'] = $case->meta['donor_redcross_id'];
        $row['nephrologist_id'] = $this->getNephrologistCode($case->form['nephrologist'] ?? $wardCase?->form['nephrologist']); // NEED recode
        $row['surgeon_id'] = $this->getSurgeonCode($case->form['surgeon'] ?? $wardCase?->form['surgeon']); // NEED recode
        $row['deceased_donors_hospital_id'] = $case->form['donor_hospital']; // NEED recode
        $row['deceased_donors_paired_kidney_hospital_id'] = $case->form['co_recipient_hospital']; // NEED recode
        $row['deceased_donors_non_utilized_another_kidney_reason'] = $case->form['no_co_recipient_hospital_reason'] ?? null;
        $row['donors_sex'] = $case->form['donor_gender'] ? ($case->form['donor_gender'] === 'male' ? 1 : 2) : null;
        $row['transplant_type'] = $this->getTxType($case->meta['donor_type'], $case->form['combined_with_liver'], $case->form['combined_with_heart'], $case->form['combined_with_pancreas']);
        $row['deceased_donors_death_cause'] = $this->getCauseOfDeadCode($case->form['donor_cause_of_death'] ?? null);
        $row['deceased_donors_death_cause_detail'] = ($case->form['donor_cause_of_death'] ?? null) ? 'migrate data : ' . $case->form['donor_cause_of_death'] : null;
        $row['deceased_donors_is_trauma'] = (int) $case->form['donor_trauma'];
        $row['health_insurance'] = $this->getHealthInsuranceCode($case->form['medical_scheme'] ?? $wardCase?->form['insurance']);
        $row['donors_age_at_nx'] = $case->form['donor_age'];
        $row['recipients_transplant_number'] = $case->form['kt_times'];
        $row['is_abo_incompatible'] = (int) $case->form['abo_incompatible'];
        $row['living_donors_hn'] = $case->form['donor_hn']; //
        $row['donor_type'] = $this->getDonorType($case->meta['donor_type'] ?? $wardCase?->form['donor_type'], $case->form['donor_is'] ?? $wardCase?->form['donor_is']);
        $row['recipients_pcr_platelet_total_units'] = $case->form['pre_kt_prc_unit'];

        // $row['cit_hrs'] = $case->form['cold_ischemic_time_hours'] ?? $wardCase?->form['cold_ischemic_time_hours'];
        // $row['cit_mins'] = $case->form['cold_ischemic_time_minutes'] ?? $wardCase?->form['cold_ischemic_time_minutes'];
        // $row['wit_mins'] = $case->form['warm_ischemic_time_minutes'] ?? $wardCase?->form['warm_ischemic_time_minutes'];
        // $row['anastomosis_time_mins'] = $case->form['anastomosis_time_minutes'] ?? $wardCase?->form['anastomosis_time_minutes'];
        $row['donor_clamp_at'] = $wardCase?->form['datetime_clamp_at_donor'] ?? null;
        $row['perfuse_at'] = $wardCase?->form['datetime_perfusion'] ?? null;
        $row['remove_from_ice_at'] = $wardCase?->form['datetime_remove_from_ice'] ?? null;
        $row['unclamp_at'] = $wardCase?->form['datetime_unclamp_all'] ?? null;

        /*$opPIT['cit_hrs'] = $case->form['cold_ischemic_time_hours'] ?? $wardCase?->form['cold_ischemic_time_hours'];
        $opPIT['cit_mins'] = $case->form['cold_ischemic_time_minutes'] ?? $wardCase?->form['cold_ischemic_time_minutes'];
        $opPIT['wit_mins'] = $case->form['warm_ischemic_time_minutes'] ?? $wardCase?->form['warm_ischemic_time_minutes'];
        $opPIT['anastomosis_time_mins'] = $case->form['anastomosis_time_minutes'] ?? $wardCase?->form['anastomosis_time_minutes'];
        $opPIT['donor_clamp_at'] = $wardCase?->form['datetime_clamp_at_donor'] ?? null;
        $opPIT['perfuse_at'] = $wardCase?->form['datetime_perfusion'] ?? null;
        $opPIT['remove_from_ice_at'] = $wardCase?->form['datetime_remove_from_ice'] ?? null;
        $opPIT['unclamp_at'] = $wardCase?->form['datetime_unclamp_all'] ?? null;

        $opPIT = $this->getOpPointInTime($opPIT);

        $row['donor_clamp_at'] = $opPIT['donor_clamp_at'];
        $row['perfuse_at'] = $opPIT['perfuse_at'];
        $row['remove_from_ice_at'] = $opPIT['remove_from_ice_at'];
        $row['unclamp_at'] = $opPIT['unclamp_at'];*/

        $row['or_start_at'] = $wardCase?->form['datetime_operation_start'] ?? null;
        $row['or_end_at'] = $wardCase?->form['datetime_operation_finish'] ?? null;
        $row['recipients_esrd_cause'] = $this->getEsrdCauseCode($case->form['cause_of_esrd'] ?? $wardCase?->form['cause_of_esrd']);
        $row['recipients_esrd_biopsy_proven'] = $case->form['native_biopsy_report'] ? ($case->form['native_biopsy_report'] === 'Yes' ? 1 : 0) : null;
        $row['recipients_rrt_modality'] = $this->getRRTCode($case->form['preemptive'], $case->form['rrt_mode']);
        $row['recipients_first_rrt_start_date'] = $case->form['date_first_rrt'];
        $row['donors_baseline_cr_mgdl'] = $case->form['baseline_cr'];
        $row['donors_pre_op_cr_mgdl'] = $case->form['pre_kt_cr'] ?? $wardCase?->form['donor_creatinine_before_harvest'];
        $row['donors_cmv_igg_result'] = $this->getSerologyCode($case->form['donor_cmv_igg']);
        $row['recipients_cmv_igg_result'] = $this->getSerologyCode($case->form['recipient_cmv_igg']);
        $row['hla_a_mismatch_count'] = $case->form['mismatch_a'] ?? $wardCase?->form['hla_mismatch_a'];
        $row['hla_b_mismatch_count'] = $case->form['mismatch_b'] ?? $wardCase?->form['hla_mismatch_b'];
        $row['hla_dr_mismatch_count'] = $case->form['mismatch_dr'] ?? $wardCase?->form['hla_mismatch_dr'];
        $row['hla_dq_mismatch_count'] = $case->form['mismatch_dq'] ?? $wardCase?->form['hla_mismatch_dq'];
        $row['total_hospital_charges_thb'] = $wardCase?->form['cost'] ?? null;
        $row['recipients_abo_group'] = $this->getABOCode($wardCase?->form['blood_group_abo']);
        $row['recipients_rh_group'] = $this->getRhCode($wardCase?->form['blood_group_rh']);
        $row['donors_abo_group'] = $this->getABOCode($wardCase?->form['donor_blood_group_abo'] ?? null);
        $row['donors_rh_group'] = $this->getRhCode($wardCase?->form['donor_blood_group_rh'] ?? null);
        $row['pre_kt_has_induction'] = (int) (
            $wardCase?->form['immunosuppressive_drugs_induction']['simulect']
            || $wardCase?->form['immunosuppressive_drugs_induction']['ATG']
            || $wardCase?->form['immunosuppressive_drugs_induction']['rituximab']
        );
        $row['pre_kt_has_il2ra'] = (int) $wardCase?->form['immunosuppressive_drugs_induction']['simulect'];
        $row['pre_kt_has_atg'] = (int) $wardCase?->form['immunosuppressive_drugs_induction']['ATG'];
        $row['pre_kt_has_rituximab'] = (int) $wardCase?->form['immunosuppressive_drugs_induction']['rituximab'];
        $row['graft_function_status'] = $this->getGFCode($case->form['graft_function'] ?? $wardCase?->form['graft_function']);

        $row['recipients_gravida'] = $case->form['gestation_g'];
        $row['recipients_para'] = $case->form['gestation_p'];
        $row['recipients_abortion'] = $case->form['gestation_a'];

        $row['recipients_no_comorbidity'] = $wardCase
            ? (int) $wardCase->form['comorbidities']['none']
            : 0;
        $row['recipients_ami_status'] = $this->getComorbidityStatus($wardCase, 'acute_mi');
        $row['recipients_ami_date'] = $wardCase?->form['comorbidities']['date_acute_mi'] ?? null;
        $row['recipients_unstable_angina_status'] = $this->getComorbidityStatus($wardCase, 'unstable_angina');
        $row['recipients_unstable_angina_date'] = $wardCase?->form['comorbidities']['date_unstable_angina'] ?? null;
        $row['recipients_cag_status'] = $this->getComorbidityStatus($wardCase, 'CAG');
        $row['recipients_cag_date'] = $wardCase?->form['comorbidities']['date_CAG'] ?? null;
        $row['recipients_ptca_status'] = $this->getComorbidityStatus($wardCase, 'PTCA');
        $row['recipients_ptca_date'] = $wardCase?->form['comorbidities']['date_PTCA'] ?? null;
        $row['recipients_cabg_status'] = $this->getComorbidityStatus($wardCase, 'CABG');
        $row['recipients_cabg_date'] = $wardCase?->form['comorbidities']['date_CABG'] ?? null;
        $row['recipients_cad_status'] = $this->getComorbidityStatus($wardCase, 'CAD');
        $row['recipients_cad_date'] = $wardCase?->form['comorbidities']['date_CAD'] ?? null;
        $row['recipients_cva_status'] = $this->getComorbidityStatus($wardCase, 'CVA') ?? $this->getComorbidityStatus($wardCase, 'stroke');
        $row['recipients_cva_date'] = $wardCase?->form['comorbidities']['date_CVA'] ?? $wardCase?->form['comorbidities']['date_stroke'] ?? null;
        $row['recipients_pvd_status'] = $this->getComorbidityStatus($wardCase, 'PVD') ?? $this->getComorbidityStatus($wardCase, 'amputation');
        $row['recipients_pvd_date'] = $wardCase?->form['comorbidities']['date_PVD'] ?? $wardCase?->form['comorbidities']['date_amputation'] ?? null;
        /*$row['recipients_amputation_status'] = $this->getComorbidityStatus($wardCase, 'amputation');
        $row['recipients_amputation_date'] = $wardCase?->form['comorbidities']['date_amputation'] ?? null;*/
        $row['recipients_chf_status'] = $this->getComorbidityStatus($wardCase, 'CHF');
        $row['recipients_chf_date'] = $wardCase?->form['comorbidities']['date_CHF'] ?? null;
        $row['recipients_ht_status'] = $this->getComorbidityStatus($wardCase, 'HT');
        $row['recipients_ht_date'] = $wardCase?->form['comorbidities']['date_HT'] ?? null;
        $row['recipients_antihypertensive_status'] = $this->getComorbidityStatus($wardCase, 'on_HT_medication');
        $row['recipients_antihypertensive_date'] = $wardCase?->form['comorbidities']['date_start_HT_medication'] ?? null;
        $row['recipients_antihypertensive_meds'] = $wardCase?->form['comorbidities']['HT_medication'] ?? null;
        $row['recipients_dm_status'] = $this->getComorbidityStatus($wardCase, 'DM');
        $row['recipients_dm_date'] = $wardCase?->form['comorbidities']['date_DM'] ?? null;
        // $row['recipients_dm_treatment_type'] = $wardCase?->form['comorbidities'] ?? null;
        $row['recipients_dm_meds'] = $wardCase?->form['comorbidities']['DM_medication'] ?? null;
        $row['recipients_copd_status'] = $this->getComorbidityStatus($wardCase, 'COPD');
        $row['recipients_copd_date'] = $wardCase?->form['comorbidities']['date_COPD'] ?? null;
        $row['recipients_asthma_status'] = $this->getComorbidityStatus($wardCase, 'asthma');
        $row['recipients_asthma_date'] = $wardCase?->form['comorbidities']['date_asthma'] ?? null;
        $row['recipients_pulmonary_tb_status'] = $this->getComorbidityStatus($wardCase, 'TB');
        $row['recipients_pulmonary_tb_date'] = $wardCase?->form['comorbidities']['date_TB'] ?? null;
        $row['recipients_cancer_status'] = $this->getComorbidityStatus($wardCase, 'cancer');
        $row['recipients_cancer_date'] = $wardCase?->form['comorbidities']['date_cancer'] ?? null;
        $row['recipients_cirrhosis_status'] = $this->getComorbidityStatus($wardCase, 'cirrhosis');
        $row['recipients_cirrhosis_date'] = $wardCase?->form['comorbidities']['date_cirrhosis'] ?? null;
        $row['recipients_dlp_status'] = $this->getComorbidityStatus($wardCase, 'DLP');
        $row['recipients_dlp_date'] = $wardCase?->form['comorbidities']['date_DLP'] ?? null;
        $row['recipients_prca_status'] = $this->getComorbidityStatus($wardCase, 'PRCA');
        $row['recipients_prca_date'] = $wardCase?->form['comorbidities']['date_PRCA'] ?? null;
        $row['recipients_hyperuricemia_status'] = $this->getComorbidityStatus($wardCase, 'uric_greater_than_six') ?? $this->getComorbidityStatus($wardCase, 'on_allopurinol');
        $row['recipients_hyperuricemia_date'] = $wardCase?->form['comorbidities']['date_uric_greater_than_six'] ?? $wardCase?->form['comorbidities']['date_start_allopurinol'] ?? null;
        $row['recipients_gout_status'] = $this->getComorbidityStatus($wardCase, 'gout');
        $row['recipients_gout_date'] = $wardCase?->form['comorbidities']['date_gout'] ?? null;
        $row['recipients_hyperparathyroidism_status'] = $this->getComorbidityStatus($wardCase, 'hyperparathyroidism') ?? $this->getComorbidityStatus($wardCase, 'PTH_grater_than_one_hundred');
        $row['recipients_hyperparathyroidism_date'] = $wardCase?->form['comorbidities']['date_hyperparathyroidism'] ?? $wardCase?->form['comorbidities']['date_PTH_grater_than_one_hundred'] ?? null;
        $row['recipients_tobacco_use'] = $this->getComorbidityStatus($wardCase, 'smoking');
        $row['recipients_tobacco_use_date'] = $wardCase?->form['comorbidities']['date_start_smoking'] ?? null;

        $row['recipients_last_pra_class_i_pct'] = $case->form['last_pra_class_i_percent'];
        $row['recipients_last_pra_class_i_date'] = $case->form['date_last_pra_class_i'];
        $row['recipients_last_pra_class_ii_pct'] = $case->form['last_pra_class_ii_percent'];
        $row['recipients_last_pra_class_ii_date'] = $case->form['date_last_pra_class_ii'];
        $row['recipients_peak_pra_class_i_pct'] = $case->form['peak_pra_class_i_percent'];
        $row['recipients_peak_pra_class_i_date'] = $case->form['date_peak_pra_class_i'];
        $row['recipients_peak_pra_class_ii_pct'] = $case->form['peak_pra_class_ii_percent'];
        $row['recipients_peak_pra_class_ii_date'] = $case->form['date_peak_pra_class_ii'];

        // $row['post_op_dialysis_sessions'] = 0;
        // $row['post_op_dialysis_start_date'] = null;

        $this->getHLATyping($row, $case->meta['hn'], $case->form['donor_hn']);
        $this->getAcuteHD($row);

        $row['check'] = 'OK';

        return $row;
    }

    protected function getAcuteHD(array &$row): void
    {
        $row['post_op_dialysis_sessions'] = 0;
        $row['post_op_dialysis_start_date'] = null;
        if (! $acuteCase = AcuteHemodialysisCaseRecord::query()->where('meta->an', $row['an'])->first() ) {
            return;
        }

        if (! $acuteCase->firstPerformedOrder) {
            return;
        }

        $row['post_op_dialysis_sessions'] = $acuteCase->orders()->whereIn('status', [5, 6])->count();
        $row['post_op_dialysis_start_date'] = $acuteCase->firstPerformedOrder->date_note->format('Y-m-d ').$acuteCase->firstPerformedOrder->meta['started_at'];
    }

    protected function getHLATyping(array &$row, string|int $hn, string|int|null $donorHn): void
    {
        $row['recipients_hla_a1'] = null;
        $row['recipients_hla_a2'] = null;
        $row['recipients_hla_b1'] = null;
        $row['recipients_hla_b2'] = null;
        $row['recipients_hla_bw1'] = null;
        $row['recipients_hla_bw2'] = null;
        $row['recipients_hla_dr1'] = null;
        $row['recipients_hla_dr2'] = null;
        $row['donors_hla_a1'] = null;
        $row['donors_hla_a2'] = null;
        $row['donors_hla_b1'] = null;
        $row['donors_hla_b2'] = null;
        $row['donors_hla_bw1'] = null;
        $row['donors_hla_bw2'] = null;
        $row['donors_hla_dr1'] = null;
        $row['donors_hla_dr2'] = null;

        /*$this->info("HLA : $hn $donorHn");*/

        if ($donorHn) {
            $lastReportDate = KidneyTransplantHLATypingReportNote::query()
                ->whereNotNull('meta->donor_hla_note_key')
                ->where("meta->hn", $hn)
                ->whereIn("status", [1, 2, 4])
                ->where("date_note", "<", "2026-01-01 00:00:00")
                ->max("date_note");

            if (!$lastReportDate) {
                return;
            }

            $report = KidneyTransplantHLATypingReportNote::query()
                ->whereNotNull('meta->donor_hla_note_key')
                ->where("meta->hn", $hn)
                ->whereIn("status", [1, 2, 4])
                ->where("date_note", $lastReportDate)
                ->first();

            if (!$report) {
                return;
            }

            $recipientReport = KidneyTransplantHLATypingNote::findByUnhashKey($report->meta['patient_hla_note_key'])->first();
            $donorReport =  KidneyTransplantHLATypingNote::findByUnhashKey($report->meta['donor_hla_note_key'])->first();

            if (!$row['recipients_abo_group']) {
                $row['recipients_abo_group']  = $this->getABOCode($recipientReport->form['abo']);
            }

            if (!$row['recipients_rh_group']) {
                $row['recipients_rh_group']  = $this->getRhCode($recipientReport->form['rh']);
            }

            $row['recipients_hla_a1'] = str_replace('ฺ', '', $recipientReport->form['hla_typing_class_i_a1']);
            $row['recipients_hla_a2'] = str_replace('ฺ', '', $recipientReport->form['hla_typing_class_i_a2']);
            $row['recipients_hla_b1'] = str_replace('ฺ', '', $recipientReport->form['hla_typing_class_i_b1']);
            $row['recipients_hla_b2'] = str_replace('ฺ', '', $recipientReport->form['hla_typing_class_i_b2']);
            $row['recipients_hla_bw1'] = str_replace('ฺ', '', $recipientReport->form['hla_typing_class_i_bw4']);
            $row['recipients_hla_bw2'] = str_replace('ฺ', '', $recipientReport->form['hla_typing_class_i_bw6']);
            $row['recipients_hla_dr1'] = str_replace('ฺ', '', $recipientReport->form['hla_typing_class_ii_drb11']);
            $row['recipients_hla_dr2'] = str_replace('ฺ', '', $recipientReport->form['hla_typing_class_ii_drb12']);

            if (!$row['donors_abo_group']) {
                $row['donors_abo_group']  = $this->getABOCode($donorReport->form['abo']);
            }

            if (!$row['donors_rh_group']) {
                $row['donors_rh_group']  = $this->getRhCode($donorReport->form['rh']);
            }

            $row['donors_hla_a1'] = str_replace('ฺ', '', $donorReport->form['hla_typing_class_i_a1']);
            $row['donors_hla_a2'] = str_replace('ฺ', '', $donorReport->form['hla_typing_class_i_a2']);
            $row['donors_hla_b1'] = str_replace('ฺ', '', $donorReport->form['hla_typing_class_i_b1']);
            $row['donors_hla_b2'] = str_replace('ฺ', '', $donorReport->form['hla_typing_class_i_b2']);
            $row['donors_hla_bw1'] = str_replace('ฺ', '', $donorReport->form['hla_typing_class_i_bw4']);
            $row['donors_hla_bw2'] = str_replace('ฺ', '', $donorReport->form['hla_typing_class_i_bw6']);
            $row['donors_hla_dr1'] = str_replace('ฺ', '', $donorReport->form['hla_typing_class_ii_drb11']);
            $row['donors_hla_dr2'] = str_replace('ฺ', '', $donorReport->form['hla_typing_class_ii_drb12']);

            return;
        }

        $lastReportDate = KidneyTransplantHLATypingNote::query()
            ->where("meta->hn", $hn)
            ->whereIn("status", [1, 2, 4])
            ->where("date_note", "<", "2026-01-01 00:00:00")
            ->max("date_note");

        if (!$lastReportDate) {
            return;
        }

        $report = KidneyTransplantHLATypingNote::query()
            ->where("meta->hn", $hn)
            ->whereIn("status", [1, 2, 4])
            ->where("date_note", $lastReportDate)
            ->first();

        if (!$report) {
            return;
        }


        if (!$row['recipients_abo_group']) {
            $row['recipients_abo_group']  = $this->getABOCode($report->form['abo']);
        }

        if (!$row['recipients_rh_group']) {
            $row['recipients_rh_group']  = $this->getRhCode($report->form['rh']);
        }

        $row['recipients_hla_a1'] = str_replace('ฺ', '',$report->form['hla_typing_class_i_a1']);
        $row['recipients_hla_a2'] = str_replace('ฺ', '',$report->form['hla_typing_class_i_a2']);
        $row['recipients_hla_b1'] = str_replace('ฺ', '',$report->form['hla_typing_class_i_b1']);
        $row['recipients_hla_b2'] = str_replace('ฺ', '',$report->form['hla_typing_class_i_b2']);
        $row['recipients_hla_bw1'] = str_replace('ฺ', '',$report->form['hla_typing_class_i_bw4']);
        $row['recipients_hla_bw2'] = str_replace('ฺ', '',$report->form['hla_typing_class_i_bw6']);
        $row['recipients_hla_dr1'] = str_replace('ฺ', '',$report->form['hla_typing_class_ii_drb11']);
        $row['recipients_hla_dr2'] = str_replace('ฺ', '',$report->form['hla_typing_class_ii_drb12']);
    }

    protected function getGFCode(?string $gf): ?int
    {
        if (is_null($gf)) {
            return null;
        }

        return match (strtolower($gf)) {
            'immediate graft function' => 1,
            'slow graft function' => 2,
            'delayed graft function' => 3,
            'primary non-function' => 4,
            default => null
        };
    }

    protected function getABOCode(?string $abo): ?int
    {
        if (is_null($abo)) {
            return null;
        }

        return match (strtolower($abo)) {
            'a' => 1,
            'b' => 2,
            'ab' => 3,
            'o' => 4,
            default => null
        };
    }

    protected function getRhCode(?string $rh): ?int
    {
        if (is_null($rh)) {
            return null;
        }

        return match (strtolower($rh)) {
            'positive' => 1,
            'negative' => 2,
            default => null
        };
    }

    protected function getSerologyCode(?string $serology): ?int
    {
        if (is_null($serology)) {
            return null;
        }

        return match (strtolower($serology)) {
            'negative' => 0,
            'positive' => 1,
            default => null
        };
    }

    protected function getRRTCode(bool $preemptive, ?string $mode): ?int
    {
        if ($preemptive) {
            return 3;
        }

        return match (strtolower($mode)) {
            'HD' => 1,
            'PD' => 2,
            default => null
        };
    }

    protected function getComorbidityStatus(?KidneyTransplantAdmissionCaseRecord $ward, string $key): ?int
    {
        if (!$ward) {
            return null;
        }

        $complete = in_array($ward->status, ['completed', 'edited']);

        if ($complete && $ward->form['comorbidities']['none']) {
            return 0;
        }

        if ($key === 'smoking') {
            if ($complete) {
                if (! $ward->form['comorbidities']['smoking']) {
                    return 0;
                } elseif ($ward->form['comorbidities']['smoking_type'] === 'smoker') {
                    return 1;
                } elseif ($ward->form['comorbidities']['smoking_type'] === 'ex-smoker') {
                    return 2;
                }
            }
            return null;
        }

        return $complete
            ? (int) $ward->form['comorbidities'][$key]
            : ($ward->form['comorbidities'][$key] ? 1 : null);
    }

    /**
     * แปลงข้อมูลเก่า (Migration) เป็น DonorType Enum Code
     */
    protected function getDonorType(?string $txType, ?string $donorIs): ?int
    {
        // 1. ตรวจสอบ null ทั้งคู่
        if (is_null($txType) && is_null($donorIs)) {
            return null;
        }

        $txType = $txType ? trim(strtoupper($txType)) : null;
        $donorIs = $donorIs ? trim($donorIs) : null;

        // 2. จัดการกลุ่ม Deceased Donor (CD)
        // ถ้าระบุว่าเป็น CD, CD Single, CD Dual ให้เป็น DECEASED ทันที
        if ($txType && (str_contains($txType, 'CD') || str_contains($txType, 'CADAVERIC'))) {
            return 10; // DonorType::DECEASED
        }

        // 3. ถ้าไม่มี $donorIs ให้จบที่การเช็ค $txType (กรณี LD ทั่วไปที่ไม่ระบุความสัมพันธ์)
        if (is_null($donorIs)) {
            return ($txType === 'LD') ? 40 : null; // ถ้าเป็น LD แต่ไม่รู้ใคร ให้เป็น UNRELATED ไว้ก่อน
        }

        // 4. จัดการข้อมูลภาษาไทยและภาษาอังกฤษใน $donorIs
        return match (true) {
            // --- Living: Genetic Related ---
            in_array($donorIs, ['บิดา', 'มารดา', 'father', 'mother']) => 22,
            in_array($donorIs, ['บุตร', 'ลูก', 'child', 'son', 'daughter']) => 23,
            in_array($donorIs, ['พี่', 'น้อง', 'พี่ชาย', 'น้องชาย', 'พี่สาว', 'น้องสาว', 'sibling']) => 24,
            in_array($donorIs, ['น้า', 'อา', 'ป้า', 'ลุง', 'aunt', 'uncle']) => 25,
            in_array($donorIs, ['หลาน', 'nephew', 'niece']) => 26,
            in_array($donorIs, ['ลูกผู้น้อง', 'ญาติ', 'cousin']) => 27,
            in_array($donorIs, ['ฝาแฝด', 'twin']) => 20, // Default เป็น Identical (ส่วนใหญ่ในไทยเป็นกลุ่มนี้)

            // --- Living: Non-Genetic / Legal Related ---
            in_array($donorIs, ['ภรรยา', 'สามี', 'คู่สมรส', 'wife', 'husband', 'spouse']) => 30,

            // --- กรณีอื่นๆ ---
            in_array($donorIs, ['คนนอก', 'unrelated']) => 40,

            default => null
        };
    }

    /**
     * แปลงข้อมูลการปลูกถ่ายเป็น TransplantType Enum Code
     */
    protected function getTxType(?string $txType, bool $liver, bool $heart, bool $pancreas): ?int
    {
        // 1. ตรวจสอบกลุ่ม Multi-organ ก่อน (Priority สูงสุด)
        if ($heart && $liver) {
            return 6; // KIDNEY_HEART_LIVER
        }

        if ($pancreas) {
            return 5; // KIDNEY_PANCREAS
        }

        if ($heart) {
            return 4; // KIDNEY_HEART
        }

        if ($liver) {
            return 3; // KIDNEY_LIVER
        }

        // 2. ถ้าไม่มีอวัยวะอื่นร่วม ให้ดูที่จำนวนไตจาก $txType
        if (is_null($txType)) {
            return 1; // Default เป็น SINGLE_KIDNEY ตามมาตรฐานส่วนใหญ่
        }

        $txType = strtoupper($txType);

        // เช็คกรณีไต 2 ข้าง (Dual/Double)
        if (str_contains($txType, 'DUAL') || str_contains($txType, 'DOUBLE')) {
            return 2; // DOUBLE_KIDNEYS
        }

        // กรณีอื่นๆ หรือ Single kidney
        return 1; // SINGLE_KIDNEY
    }

    /**
     * แปลงชื่อแพทย์จากข้อมูลเก่าเป็น Nephrologist Enum Code
     */
    function getNephrologistCode(?string $name): ?int
    {
        if (is_null($name)) {
            return null;
        }

        $name = trim($name);

        return match (true) {
            str_contains($name, 'เกรียงศักดิ์') => 1,
            str_contains($name, 'ชัยรัตน์') => 2,
            str_contains($name, 'ทวี ชาญชัยรุจิรา') => 3,
            str_contains($name, 'สุชาย') => 4,
            str_contains($name, 'อรรถพงศ์') => 5,
            str_contains($name, 'นลินี') => 6,
            str_contains($name, 'รัตนา ชวนะสุนทรพจน์') => 7,
            str_contains($name, 'สุกิจ') => 8,
            str_contains($name, 'นัฐสิทธิ์') => 9,
            str_contains($name, 'ทัศน์พรรณ') => 10,
            str_contains($name, 'ปีณิดา') => 11,
            str_contains($name, 'กรชนก') => 12,
            str_contains($name, 'อารีรัตน์') => 13,
            str_contains($name, 'อนิรุธ') => 101,
            str_contains($name, 'นันทวัน') => 102,
            str_contains($name, 'ธนพร') => 103,
            str_contains($name, 'ไกรสูรย์') => 104,
            str_contains($name, 'ญาณ์นรินทร์') => 105,

            default => null,
        };
    }

    /**
     * แปลงชื่อศัลยแพทย์จากข้อมูลเก่าเป็น Surgeon Enum Code
     */
    function getSurgeonCode(?string $name): ?int
    {
        if (is_null($name)) {
            return null;
        }

        $name = trim($name);

        return match (true) {
            // --- Urologists ---
            str_contains($name, 'ศิรส') => 1,
            str_contains($name, 'ฐิติภัท') => 2,
            str_contains($name, 'วรัชญ์') => 3,
            str_contains($name, 'กานติมา') => 4,
            str_contains($name, 'ธวัชชัย') => 5,
            str_contains($name, 'เอกรินทร์') => 6,
            str_contains($name, 'พงศธร') => 7, // ดักจับทั้ง "นพ. พงศธร" และ "อ.พงศธร"

            // --- Gen C ---
            str_contains($name, 'ประเวชย์') => 101,
            str_contains($name, 'เวธิต') => 102,
            str_contains($name, 'ประวัฒน์') => 103,
            str_contains($name, 'ชุติวิชัย') => 104,
            str_contains($name, 'พลสิทธิ์') => 105,
            str_contains($name, 'สมชัย') => 106,
            str_contains($name, 'ยงยุทธ') => 107,
            str_contains($name, 'ชาญวิทย์') => 108,

            default => null,
        };
    }

    /**
     * แปลงข้อมูลการวินิจฉัย (Diagnosis) เป็น CauseOfDead Enum Code
     */
    function getCauseOfDeadCode(?string $diag): ?int
    {
        if (is_null($diag)) {
            return null;
        }

        if (strtolower(trim($diag)) === 'unknown') {
            return 99; // CauseOfDead::UNKNOWN
        }

        $diag = strtolower(trim($diag));

        return match (true) {
            // 1. กลุ่ม TRAFFIC_ACCIDENT
            str_contains($diag, 'traffic accident') => 1,

            // 2. กลุ่ม FALL_FROM_HEIGHT
            str_contains($diag, 'falls from height') => 3,

            // 3. กลุ่ม ASPHYXIA
            str_contains($diag, 'asphyxia'),
            str_contains($diag, 'จมน้ำ'),
            str_contains($diag, 'hanging') => 5,

            // 4. กลุ่ม PRIMARY_BRAIN_TUMOR
            str_contains($diag, 'tumor'),
            str_contains($diag, 'neurocytoma') => 7,

            // 5. กลุ่ม TRAUMA (พิจารณาจากคำศัพท์ Head Injury หรือ Fracture)
            // ข้อมูลเก่า: Severe HI, SDH/EDH (กรณีมี Fx หรือ Contusion), Traumatic SAH
            str_contains($diag, 'severe hi'),
            str_contains($diag, 'severe head injury'),
            str_contains($diag, 'traumatic'),
            str_contains($diag, 'blunt'),
            str_contains($diag, 'fx'),
            str_contains($diag, 'fracture'),
            str_contains($diag, 'skull'),
            str_contains($diag, 'contusion') => 1, // ส่วนใหญ่มักเป็น Traffic Accident หรือ Blunt Trauma

            // 6. กลุ่ม CVA_STROKE (พิจารณาจากตำแหน่งเลือดออกที่ไม่ได้เกิดจากอุบัติเหตุ)
            // ข้อมูลเก่า: ICH, SDH (Non-traumatic), SAH, IVH, Thalamic, Pontine, BGH
            str_contains($diag, 'cva'),
            str_contains($diag, 'stroke'),
            str_contains($diag, 'hemorrhage'),
            str_contains($diag, 'ich'),
            str_contains($diag, 'sah'),
            str_contains($diag, 'ivh'),
            str_contains($diag, 'sdh'),
            str_contains($diag, 'iph'),
            str_contains($diag, 'ischemia'),
            str_contains($diag, 'rupture'),
            str_contains($diag, 'aneurysm'),
            str_contains($diag, 'thalamic'),
            str_contains($diag, 'pontine'),
            str_contains($diag, 'bgh'),
            str_contains($diag, 'cerebellar') => 2,

            // 7. กลุ่ม MISCELLANEOUS (อื่นๆ)
            // ข้อมูลเก่า: Cirrhosis, Pre-eclampsia, Anaphylaxis, Warfarin overdose, Thunderstorm
            str_contains($diag, 'cirrhosis'),
            str_contains($diag, 'eclampsia'),
            str_contains($diag, 'anaphylaxis'),
            str_contains($diag, 'overdose'),
            str_contains($diag, 'thunder') => 8,

            default => 99,
        };
    }

    /**
     * แปลงข้อมูลสิทธิการรักษาจากข้อมูลเก่าเป็น HealthInsurance Enum Code
     */
    function getHealthInsuranceCode(?string $insurance): ?int
    {
        if (is_null($insurance)) {
            return null;
        }

        $insurance = trim($insurance);

        return match (true) {
            // 1. เบิกจ่ายตรง (CSMBS)
            str_contains($insurance, 'เบิกจ่ายตรง'),
            str_contains($insurance, 'กรมบัญชีกลาง'),
            str_contains($insurance, 'ข้าราชการ') => 1,

            // 2. ประกันสังคม (Social Security)
            str_contains($insurance, 'ประกันสังคม') => 2,

            // 3. สปสช. / บัตรทอง (UCS)
            str_contains($insurance, 'สปสช'),
            str_contains($insurance, '30 บาท'),
            str_contains($insurance, 'บัตรทอง'),
            str_contains($insurance, 'หลักประกันสุขภาพ') => 3,

            // 4. รัฐวิสาหกิจ (State Enterprise)
            str_contains($insurance, 'รัฐวิสาหกิจ') => 4,

            // 5. จ่ายเอง (Self Pay)
            str_contains($insurance, 'จ่ายเอง'),
            str_contains($insurance, 'เงินสด') => 5,

            // 6. กทม. (BMA)
            str_contains($insurance, 'กทม'),
            str_contains($insurance, 'กรุงเทพมหานคร') => 6,

            // 7. อปท. (LGO)
            str_contains($insurance, 'อปท'),
            str_contains($insurance, 'เทศบาล'),
            str_contains($insurance, 'อบจ') => 7,

            // 8. อบต. (SAO)
            str_contains($insurance, 'อบต') => 8,

            default => null,
        };

    }

    /**
     * แปลงข้อมูลสาเหตุ ESRD จากข้อมูลเก่าเป็น ESRDCause Enum Code
     */
    protected function getEsrdCauseCode(?string $cause): int
    {
        if (is_null($cause) || in_array(strtolower(trim($cause)), ['unknown', ''])) {
            return 99; // ESRDCause::UNKNOWN
        }

        $cause = trim($cause);

        return match (true) {
            // --- DM Group ---
            str_contains($cause, 'DM type1') => 9,
            str_contains($cause, 'DM type2') => 10,
            str_contains($cause, 'DM') => 8,

            // --- GN Group ---
            str_contains($cause, 'IgAN') => 15,
            str_contains($cause, 'IgMN') => 16,
            str_contains($cause, 'FSGS') => 11,
            str_contains($cause, 'LN') || str_contains($cause, 'Lupus') => 17,
            str_contains($cause, 'Membranous GN') => 18,
            str_contains($cause, 'CresenticGN') => 6,
            str_contains($cause, 'Pauci immune') => 22,
            str_contains($cause, 'RPGN') => 27,
            str_contains($cause, 'CGN') => 4,

            // --- Standard Mapping ---
            $cause === 'Alport' => 1,
            $cause === 'Analgesic nephropathy' => 2,
            $cause === 'Anti-GBM' => 3,
            $cause === 'Chronic pyelonephritis' => 5,
            $cause === 'CTIN' => 7,
            $cause === 'Gout' => 12,
            $cause === 'Graft failure' => 13,
            $cause === 'HT' => 14,
            $cause === 'Nephrocalcinosis' => 19,
            $cause === 'Neurogenic Bladder' => 20,
            $cause === 'Obstructive Uropathy' => 21,
            $cause === 'PKD' => 23,
            $cause === 'RAS' => 24,
            $cause === 'Reflux nephropathy' => 25,
            $cause === 'Renal dysplasia' => 26,
            $cause === 'Single Kidney' => 28,
            $cause === 'Stone' => 29,

            default => 99,
        };
    }

    function getOpPointInTime(array $form): array
    {
        $reply = [
            'donor_clamp_at' => null,
            'perfuse_at' => null,
            'remove_from_ice_at' => null,
            'unclamp_at' => null,
            'status' => 'ok'
        ];

        if (empty($form)) return $reply;

        // เตรียม Carbon Instance สำหรับข้อมูลที่มี
        $unclamp = !empty($form['datetime_unclamp_all']) ? Carbon::parse($form['datetime_unclamp_all']) : null;
        $removeFromIce = !empty($form['datetime_remove_from_ice']) ? Carbon::parse($form['datetime_remove_from_ice']) : null;
        $perfuse = !empty($form['datetime_perfusion']) ? Carbon::parse($form['datetime_perfusion']) : null;
        $clamp = !empty($form['datetime_clamp_at_donor']) ? Carbon::parse($form['datetime_clamp_at_donor']) : null;

        $firstWitMin = (int)($form['warm_ischemic_time_minutes'] ?? 0);

        // --- LOGIC 1: จัดการ First Warm Ischemia (Donor Side) ---
        if ($firstWitMin > 30) {
            $reply['status'] = 'ignored: warm_ischemic_time_minutes > 30';
            // ไม่นำ $firstWitMin ไปคำนวณต่อตามโจทย์
        } else {
            // หา perfuse_at จาก clamp + wit
            if (!$perfuse && $clamp && $firstWitMin > 0) {
                $perfuse = $clamp->copy()->addMinutes($firstWitMin);
            }
            // หา donor_clamp_at จาก perfuse - wit
            if (!$clamp && $perfuse && $firstWitMin > 0) {
                $clamp = $perfuse->copy()->subMinutes($firstWitMin);
            }
        }

        // --- LOGIC 2: จัดการ Cold Ischemia (In-transit / Ice Box) ---
        $citHours = (int)($form['cold_ischemic_time_hours'] ?? 0);
        $citMins = (int)($form['cold_ischemic_time_minutes'] ?? 0);
        $totalCitMins = ($citHours * 60) + $citMins;

        if ($totalCitMins > 0) {
            // หา remove_from_ice ย้อนหลังจาก perfuse
            if (!$removeFromIce && $perfuse) {
                $removeFromIce = $perfuse->copy()->addMinutes($totalCitMins);
            }
            // หา perfuse ย้อนหลังจาก remove_from_ice
            if (!$perfuse && $removeFromIce) {
                $perfuse = $removeFromIce->copy()->subMinutes($totalCitMins);
            }
        }

        // --- LOGIC 3: จัดการ Anastomosis Time (Recipient Side) ---
        // ถ้าแพทย์ระบุเวลาปักเข็ม (unclamp) แต่ไม่ระบุเวลาเอาออกจากน้ำแข็ง
        $anaTimeMin = (int)($form['anastomosis_time_minutes'] ?? 0);
        if (!$unclamp && $removeFromIce && $anaTimeMin > 0) {
            $unclamp = $removeFromIce->copy()->addMinutes($anaTimeMin);
        }
        if (!$removeFromIce && $unclamp && $anaTimeMin > 0) {
            $removeFromIce = $unclamp->copy()->subMinutes($anaTimeMin);
        }

        // --- LOGIC 4: Final Fallback (กรณีข้ามจุด Perfuse) ---
        // ถ้าไม่มี perfuse_at แต่มี clamp และ CIT ให้ประมาณการ perfuse_at เป็น clamp_at (WIT = 0)
        if (!$perfuse && $clamp && $totalCitMins > 0 && !$removeFromIce) {
            $perfuse = $clamp->copy();
            $removeFromIce = $perfuse->copy()->addMinutes($totalCitMins);
        }

        $reply['unclamp_at'] = $unclamp?->toDateTimeString();
        $reply['remove_from_ice_at'] = $removeFromIce?->toDateTimeString();
        $reply['perfuse_at'] = $perfuse?->toDateTimeString();
        $reply['donor_clamp_at'] = $clamp?->toDateTimeString();

        return $reply;
    }

}
