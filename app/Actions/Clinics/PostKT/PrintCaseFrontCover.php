<?php

namespace App\Actions\Clinics\PostKT;

use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\User;
use App\Traits\AvatarLinkable;
use App\Traits\FirstNameAware;
use Illuminate\Support\Number;

class PrintCaseFrontCover
{
    use AvatarLinkable, FirstNameAware;

    public function __invoke(string $hashedKey, User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $case = KidneyTransplantSurvivalCaseRecord::query()
            ->findByUnhashKey($hashedKey)
            ->firstOrFail();

        $patient = $case->patient;

        $data['donor_type'] = str_starts_with($case->meta['donor_type'], 'LD') ? 'LD' : 'CD';
        $data['patient_name'] = $case->meta['name'];
        $data['cause_of_esrd'] = $case->form['cause_of_esrd'];
        $data['native_biopsy_report'] = $case->form['native_biopsy_report'];
        $data['nephrologist'] = $case->form['nephrologist']
            ? 'อ.'.$this->getFirstName($case->form['nephrologist'])
            : null;
        $data['surgeon'] = $case->form['surgeon']
            ? 'อ.'.$this->getFirstName($case->form['surgeon'])
            : null;
        $data['pre_kt_prc_unit'] = $case->form['pre_kt_prc_unit'];
        $data['gestation_g'] = $patient->gender === 'male' ? '-' : $case->form['gestation_g'];
        $data['gestation_p'] = $patient->gender === 'male' ? '-' : $case->form['gestation_p'];
        $data['gestation_a'] = $patient->gender === 'male' ? '-' : $case->form['gestation_a'];
        $data['baseline_cr'] = $case->form['baseline_cr'];
        $data['pre_kt_cr'] = $case->form['pre_kt_cr'];
        $data['date_first_rrt'] = $case->form['date_first_rrt'];
        $data['rrt_mode'] = $case->form['rrt_mode'];
        $data['cold_ischemic_time_hours'] = $case->form['cold_ischemic_time_hours'];
        $data['cold_ischemic_time_minutes'] = $case->form['cold_ischemic_time_minutes'];

        if ($data['donor_type'] === 'CD') {
            $data['donor_cause_of_death'] = $case->form['donor_cause_of_death'];
            $data['co_recipient_hospital'] = $case->form['co_recipient_hospital'];
            $data['clamp_time'] = $case->form['time_clamp_at_donor'];
            $data['donor_is'] = $case->form['donor_gender'];
            if ($case->form['donor_age']) {
                $data['donor_is'] = $data['donor_is'] . ' ' . $case->form['donor_age'] . 'Yo';
            }

            $data['donor_is'] = $case->form['donor_trauma']
                ? $data['donor_is'] . ' trauma'
                : $data['donor_is'] . ' non-trauma';
            $data['donor_is'] = trim($data['donor_is']);
            $data['transplant_specification'] = str_contains(strtolower($case->meta['donor_type']), 'dual kidneys')
                ? 'Dual kidneys'
                : 'Single kidney';
        } else {
            $data['anastomosis_time_minutes'] = $case->form['anastomosis_time_minutes'];
            $data['warm_ischemic_time_minutes'] = $case->form['warm_ischemic_time_minutes'];
            $data['donor_is'] = $case->form['donor_is'] . ' ' . $case->form['donor_gender'] . ' ' . $case->form['donor_age'] . 'Yo';
            $aboIncompatible = $case->form['abo_incompatible']
                ? 'ABO incompatible'
                : '';

            $preemptive = '';
            if ($case->form['preemptive']) {
                $data['date_first_rrt'] = '-';
                $data['rrt_mode'] = 'no RRT';
                $preemptive = 'Preemptive';
            }

            $data['transplant_specification'] = ($aboIncompatible && $preemptive)
                ? "$aboIncompatible/$preemptive"
                : trim("$aboIncompatible $preemptive");
        }

        $combined = [];
        foreach (['liver', 'heart', 'pancreas'] as $organ) {
            if ($case->form["combined_with_$organ"]) {
                $combined[] = $organ;
            }
        }
        if (count($combined)) {
            $data['transplant_specification'] .= ' ';
            $data['transplant_specification'] .= implode(' ', ['combine', ...$combined]);
        }

        $data['donor_is'] = trim($data['donor_is']);
        $data['graft_function'] = $case->form['graft_function'];
        $data['kt_times'] = $case->form['kt_times'] ? Number::ordinal($case->form['kt_times']) : null;
        $data['date_transplant'] = $case->meta['date_transplant'];
        $data['hn'] = $patient->hn;
        $data['dob'] = $patient->dob->format('Y-m-d');

        $data['last_pra_class_i_percent'] = $case->form['last_pra_class_i_percent']
            ? $case->form['last_pra_class_i_percent'] . '%'
            : null;
        $data['last_pra_class_ii_percent'] = $case->form['last_pra_class_ii_percent']
            ? $case->form['last_pra_class_ii_percent'] . '%'
            : null;
        $data['date_last_pra'] = $case->form['date_last_pra_class_i'] === $case->form['date_last_pra_class_ii']
            ? $case->form['date_last_pra_class_i']
            : trim($case->form['date_last_pra_class_i'].'/'.$case->form['date_last_pra_class_ii'], '/');
        $data['peak_pra_class_i_percent'] = $case->form['peak_pra_class_i_percent']
            ? $case->form['peak_pra_class_i_percent'] . '%'
            : null;
        $data['peak_pra_class_ii_percent'] = $case->form['peak_pra_class_ii_percent']
            ? $case->form['peak_pra_class_ii_percent'] . '%'
            : null;
        $data['date_peak_pra'] = $case->form['date_peak_pra_class_i'] === $case->form['date_peak_pra_class_ii']
            ? $case->form['date_peak_pra_class_i']
            : trim($case->form['date_peak_pra_class_i'].'/'.$case->form['date_peak_pra_class_ii'], '/');
        $data['mismatch_a'] = $case->form['mismatch_a'];
        $data['mismatch_b'] = $case->form['mismatch_b'];
        $data['mismatch_dr'] = $case->form['mismatch_dr'];
        $data['mismatch_cw'] = $case->form['mismatch_cw'];
        $data['mismatch_drb'] = $case->form['mismatch_drb'];
        $data['mismatch_dqb1'] = $case->form['mismatch_dqb1'];
        $data['mismatch_dpb1'] = $case->form['mismatch_dpb1'];
        $data['mismatch_mica'] = $case->form['mismatch_mica'];
        $data['mismatch_dqa1'] = $case->form['mismatch_dqa1'];
        $data['mismatch_dpa1'] = $case->form['mismatch_dpa1'];
        $data['donor_cmv_igg'] = $case->form['donor_cmv_igg'];
        $data['recipient_cmv_igg'] = $case->form['recipient_cmv_igg'];

        $cxm = [];
        $countNegative = 0;
        if ($case->form['crossmatch_cdc'] === 'positive') {
            $cxm[] = 'CDC ' . $case->form['crossmatch_cdc_positive_specification'];
        } elseif ($case->form['crossmatch_cdc'] === 'negative') {
            $countNegative++;
        }
        if ($case->form['crossmatch_cdc_ahg'] === 'positive') {
            $cxm[] = 'CDC AHG ' . $case->form['crossmatch_cdc_ahg_positive_specification'];
        } elseif ($case->form['crossmatch_cdc_ahg'] === 'negative') {
            $countNegative++;
        }
        if ($case->form['crossmatch_flow_cxm'] === 'positive') {
            $cxm[] = 'FLOW CXM ' . $case->form['crossmatch_flow_cxm_positive_specification'];
        } elseif ($case->form['crossmatch_flow_cxm'] === 'negative') {
            $countNegative++;
        }
        if (count($cxm)) {
            $data['cxm'] = implode(', ', $cxm)    ;
        } elseif ($countNegative === 3) {
            $data['cxm'] = 'none';
        } else {
            $data['cxm'] = null;
        }

        $data['managements'] = [...$case->form['managements']];

        $flash['page-title'] = 'Print front cover ' . $case->title;
        $flash['main-menu-links'] = [];
        $flash['action-menu'] = [];

        $case->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'print_case_front_cover',
        ]);

        return [
            'flash' => $flash,
            'data' => $data,
        ];
    }
}
