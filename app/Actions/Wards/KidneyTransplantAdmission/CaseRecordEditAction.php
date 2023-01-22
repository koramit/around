<?php

namespace App\Actions\Wards\KidneyTransplantAdmission;

use App\Extensions\Auth\AvatarUser;
use App\Models\Resources\Admission;
use App\Models\User;

class CaseRecordEditAction extends KidneyTransplantAdmissionAction
{
    public function __invoke(string $hashedKey, User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $caseRecord = $this->getCaseRecord($hashedKey);

        // @TODO - Check if user can edit this case record

        $form = $caseRecord->form;
        $form['reason_for_admission'] = $caseRecord->meta['reason_for_admission'];
        $admission = Admission::query()->findByHashKey($caseRecord->meta['an'])->first();
        $form['admitted_at'] = $admission->encountered_at->format('d M y');
        $form['discharged_at'] = $admission->dismissed_at?->format('d M y');
        $form['discharge_status'] = $admission->meta['discharge_status'];
        $form['discharge_type'] = $admission->meta['discharge_type'];
        $form['ward_discharged'] = $admission->dismissed_at ? $admission->place->name : null;
        $form['los'] = $admission->length_of_stay;
        $form['graft_biopsy'] = [];
        $configs = [
            'routes' => [
                'people' => route('resources.api.people'),
                'nephrologists_scope' => '&position=8&division_id[]=6&division_id[]=9',
                'surgeons_scope' => '&position=8&division_id[]=10&division_id[]=11',
                'wards' => route('resources.api.wards'),
                'upload' => [
                    'store' => route('uploads.store'),
                    'show' => route('uploads.show'),
                    'destroy' => route('uploads.destroy'),
                ],
            ],
            'attachment_upload_pathname' => 'w/k/a',
            'insurances' => ['เบิกจ่ายตรง', 'ประกันสังคม', '30 บาท', 'รัฐวิสาหกิจ'],
            'common_transfer_wards' => ['หอผู้ป่วยโรคไต สง่า นิลวรางกูร', 'เฉลิมพระเกียรติ ชั้น 7 เหนือ'],
            'donor_types' => ['CD', 'LD'],
            'cause_of_esrd_options' => [
                'Alport', 'Analgesic nephropathy', 'Anti-GBM', 'CGN', 'Chronic pyelonephritis', 'CresenticGN', 'CTIN', 'DM', 'DM type1', 'DM type2', 'FSGS', 'Gout', 'Graft failure', 'HT', 'IgAN', 'IgMN', 'LN', 'Membranous GN', 'Nephrocalcinosis', 'Neurogenic Bladder', 'Obstructive Uropathy', 'Panci immune Glomerulonephritis', 'PKD', 'RAS', 'Reflux nephropathy', 'renal dysplasia', 'RPGN', 'Single Kidney', 'Stone', 'Unknown',
            ],
            'abo_options' => ['A', 'B', 'AB', 'O'],
            'rh_options' => ['positive', 'negative'],
            'hla_mismatch_antigens' => ['A', 'B', 'DR', 'DQ'],
            'hla_mismatch_options' => [0,1,2],
            'recipient_is_options' => $caseRecord->patient->gender === 'male'
                ? ['น้อง', 'พี่', 'บุตร', 'สามี', 'บิดา', 'หลาน', 'น้า', 'อา', 'ลุง']
                : ['น้อง', 'พี่', 'บุตร', 'ภรรยา', 'มารดา',  'หลาน', 'ป้า', 'น้า', 'อา'],
            'donor_is_options' => [
                'น้อง' => ['พี่'],
                'พี่' => ['น้อง'],
                'บุตร' => ['บิดา', 'มารดา'],
                'ภรรยา' => ['สามี'],
                'สามี' => ['ภรรยา'],
                'มารดา' => ['บุตร'],
                'บิดา' => ['บุตร'],
                'หลาน' => ['ป้า', 'น้า', 'อา', 'ลุง'],
                'ป้า' => ['หลาน'],
                'น้า' => ['หลาน'],
                'อา' => ['หลาน'],
                'ลุง' => ['หลาน'],
            ],
            'comorbid_a' => [
                ['name' => 'acute_mi', 'label' => 'Acute MI'],
                ['name' => 'unstable_angina', 'label' => 'Unstable Angina'],
                ['name' => 'CAG', 'label' => 'CAG'],
                ['name' => 'PTCA', 'label' => 'PTCA'],
                ['name' => 'CABG', 'label' => 'CABG'],
                ['name' => 'CAD', 'label' => 'CAD'],
                ['name' => 'CVA', 'label' => 'CVA'],
                ['name' => 'stroke', 'label' => 'Stroke'],
                ['name' => 'PVD', 'label' => 'PVD'],
                ['name' => 'amputation', 'label' => 'Amputation'],
                ['name' => 'CHF', 'label' => 'CHF'],
                ['name' => 'heart_failure', 'label' => 'Heart failure'],
            ],
            'comorbid_b' => [
                ['name' => 'COPD', 'label' => 'COPD'],
                ['name' => 'asthma', 'label' => 'Asthma'],
                ['name' => 'TB', 'label' => 'TB'],
                ['name' => 'cancer', 'label' => 'Cancer'],
                ['name' => 'cirrhosis', 'label' => 'Cirrhosis'],
                ['name' => 'DLP', 'label' => 'DLP'],
                ['name' => 'PRCA', 'label' => 'PRCA'],
                ['name' => 'uric_greater_than_six', 'label' => 'Uric > 6'],
                ['name' => 'on_allopurinol', 'label' => 'On Allopurinol'],
                ['name' => 'gout', 'label' => 'Gout'],
                ['name' => 'hyperparathyroidism', 'label' => 'Hyperparathyroidism'],
                ['name' => 'PTH_grater_than_one_hundred', 'label' => 'PTH > 100'],
            ],
            'smoking_options' => ['smoker', 'ex-smoker', 'non-smoker'],
            'operative_data' => [
                ['name' => 'datetime_clamp_at_donor', 'label' => 'clamp time at donor'],
                ['name' => 'datetime_perfusion', 'label' => 'perfusion time'],
                ['name' => 'datetime_remove_from_ice', 'label' => 'remove from ice time'],
                ['name' => 'datetime_unclamp_all', 'label' => 'unclamp all time'],
            ],
            'crossmatches' => [
                ['name' => 'crossmatch_cdc', 'label' => 'CDC'],
                ['name' => 'crossmatch_cdc_ahg', 'label' => 'CDC-AHG'],
                ['name' => 'crossmatch_flow_cxm', 'label' => 'Flow-CXM'],
            ],
            'crossmatch_options' => ['positive', 'negative'],
            'graft_function_options' => ['immediate graft function ', 'slow graft function', 'delayed graft function', 'primary non-function'],
            'dialysis_mode_options' => ['HD', 'PD'],
            'dialysis_indication_fields' => [
                ['name' => 'delayed_graft_function_dialysis_indication_hyper_k', 'label' => 'Hyper K'],
                ['name' => 'delayed_graft_function_dialysis_indication_volume_overload', 'label' => 'Volume Overload'],
                ['name' => 'delayed_graft_function_dialysis_indication_uremia', 'label' => 'Uremia'],
            ],
            'graft_biopsy' => [
                'ATN' => false,
                'ATI' => false,
                'rejection' => false,
                'TMA' => false,
                'other_result' => null,
                'date_biopsy' => null,
                'attachment' => null,
            ],
            'biopsy_result_fields' => [
                ['name' => 'ATN', 'label' => 'ATN'],
                ['name' => 'ATI', 'label' => 'ATI'],
                ['name' => 'rejection', 'label' => 'Rejection'],
                ['name' => 'TMA', 'label' => 'TMA'],
            ],
            'complication_infection_fields' => [
                ['name' => 'UTI', 'label' => 'UTI'],
                ['name' => 'septicemia', 'label' => 'Septicemia'],
                ['name' => 'pneumonia', 'label' => 'Pneumonia'],
                ['name' => 'CMV', 'label' => 'CMV'],
                ['name' => 'herpez', 'label' => 'Herpez'],
                ['name' => 'BK', 'label' => 'BK'],
                ['name' => 'wound_infection', 'label' => 'Wound infection'],
            ],
            'complication_vascular_fields' => [
                ['name' => 'stenosis_a', 'label' => 'Stenosis A'],
                ['name' => 'stenosis_v', 'label' => 'Stenosis V'],
                ['name' => 'thrombosis_a', 'label' => 'Thrombosis A'],
                ['name' => 'thrombosis_v', 'label' => 'Thrombosis V'],
            ],
            'complication_investigation_fields' => [
                ['name' => 'ultrasound', 'label' => 'Ultrasound'],
                ['name' => 'doppler', 'label' => 'Doppler'],
                ['name' => 'ct_abdomen', 'label' => 'CT abdomen'],
                ['name' => 'cta', 'label' => 'CTA'],
                ['name' => 'ctv', 'label' => 'CTV'],
                ['name' => 'renogram', 'label' => 'renogram'],
            ],
            'complication_urological_fields' => [
                ['name' => 'ureter_stricture', 'label' => 'Ureter stricture'],
                ['name' => 'leakage', 'label' => 'Leakage'],
                ['name' => 'urinoma', 'label' => 'Urinoma'],
                ['name' => 'lymphocele', 'label' => 'Lymphocele'],
            ],
        ];
        $flash = $this->getFlash($caseRecord->title, $user);
        $flash['hn'] = $caseRecord->patient->hn;


        return [
            'formData' => $form,
            'formConfigs' => $configs,
            'flash' => $flash,
        ];
    }
}
