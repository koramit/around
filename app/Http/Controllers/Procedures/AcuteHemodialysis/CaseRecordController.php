<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Http\Controllers\Controller;
use App\Managers\Resources\PatientManager;
use App\Models\CaseRecord;
use App\Models\Resources\Admission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CaseRecordController extends Controller
{
    protected $avatarMode; // = config('auth.gurads.web.provider') === 'avatar';

    protected $registryId = 1;

    public function __construct()
    {
        $this->avatarMode = config('auth.gurads.web.provider') === 'avatar';
    }

    public function edit(CaseRecord $caseRecord)
    {
        return $caseRecord;
    }

    public function index()
    {
        return Inertia::render('Procedures/AcuteHemodialysisIndex', [
            'cases' => [
                'data' => [],
                'links' => [],
            ],
            'filters' => [
                'search' => '',
                'scope' => 'all',
            ],
            'routes' => [
                'index' => route('procedures.acute-hemodialysis.index'),
                'store' => route('procedures.acute-hemodialysis.store'),
                'serviceEndpoint' => route('resources.api.patient-recently-admission.show'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        if ($this->avatarMode) {
            return []; // call api
        }

        $patient = (new PatientManager)->manage(hn: $request->hn, forceUpdate: true);

        if (! $patient['found']) {
            return []; //invalid trans('dot', ':attribute')
        }

        $patient = $patient['patient'];

        // Later
        // $request->validate([
        //     'hn' => [
        //         'required','digits:8'
        //     ],
        //     'an' => []
        // ]);

        $caseRecord = new CaseRecord();
        $caseRecord->patient_id = $patient->id;
        $caseRecord->registry_id = $this->registryId;
        $form = $this->initForm();
        if ($request->has('an')) {
            $admission = Admission::query()
                                ->findByHashedId($request->an)
                                ->first();
            if (! $admission->dismissed_at) {
                $form['an'] = $admission->an;
            }
        }
        $caseRecord->form = $form;
        $caseRecord->meta = [
            'hn' => $patient->hn,
            'name' => $patient->first_name,
        ];
        $caseRecord->creator_id = $request->user()->id;
        $caseRecord->updater_id = $request->user()->id;
        $caseRecord->save();

        return $caseRecord;
    }

    protected function initForm()
    {
        return [
            'an' => null,
            'ward_admit' => null,
            'ward_discharge' => null,
            'previous_crrt' => false,
            'date_start_crrt' => null,
            'date_end_crrt' => null,
            'renal_diagnosis' => null,
            'admission_diagnosis' => null,
            // 'renal_diagnosis_aki' => [
            //     'check' => false,
            //     'sepsis' => false,
            //     'chf' => false,
            //     'acs' => false,
            //     'other_cardiac_cause' => false,
            //     'glomerulonephritis' => false,
            //     'acute_interstitial_nephritis' => false,
            //     'contrast_induced_nephropathy' => false,
            //     'acute_tubular_necrosis' => false,
            //     'drug_induced_aki' => false,
            //     'other' => null,
            // ],
            // 'renal_diagnosis_ckd' => [
            //     'check' => false,
            //     'dn' => false,
            //     'ht' => false,
            //     'glomerular_disease' => false,
            //     'chronic_tubulointerstitial_nephritis' => false,
            //     'other' => null,
            // ],
            'comorbidities' => [
                'dm' => false,
                'ht' => false,
                'dlp' => false,
                'coronary_artery_disease' => false,
                'cerebrovascular_disease' => false,
                'copd' => false,
                'cirrhosis' => false,
                'cancer' => false,
                'other' => null,
            ],
            'indications' => [
                'volume_overload' => false,
                'metabolic_acidosis' => false,
                'hyperkalemia' => false,
                'toxin_removal' => false,
                'initiate_chronic_hd' => false,
                'maintain_chronic_hd' => false,
                'change_from_pd' => false,
                'uremia' => false,
                'delayed_graft_function' => false,
                'other' => null,
            ],
            'hbs_ag' => null,
            'date_hbs_ag' => null,
            'anti_hcv' => null,
            'date_anti_hcv' => null,
            'anti_hiv' => null,
            'date_anti_hiv' => null,
            'opd_consent_form' => null,
            'ipd_consent_form' => null,
            'same_consent_form' => false,
            'insurance' => null,
            'renal_outcome' => null,
            'cr_before_discharge' => null,
            'patient_outcome' => null,
            'cause_of_dead' => null,
        ];
    }
}
