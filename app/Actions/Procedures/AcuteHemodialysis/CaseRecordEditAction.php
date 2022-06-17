<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Managers\Resources\AdmissionManager;
use App\Models\CaseRecord;
use App\Models\Note;
use App\Models\Resources\Ward;

class CaseRecordEditAction extends CaseRecordAction
{
    protected $LIMIT_ADVANCE_DAYS = 3;

    protected $FORM_CONFIGS = [
        'in_unit_dialysis_types' => [
            'HD 2 hrs.',
            'HD 3 hrs.',
            'HD 4 hrs.',
            'HD+HF 4 hrs.',
            'HD+TPE 6 hrs.',
            'HF 2 hrs.',
            'TPE 2 hrs.',
        ],
        'out_unit_dialysis_types' => [
            'HD 2 hrs.',
            'HD 3 hrs.',
            'HD 4 hrs.',
            'HD+HF 4 hrs.',
            'HF 2 hrs.',
            'SLEDD',
        ],
        'renal_diagnosis' => ['AKI', 'AKI ontop CKD', 'ESRD', 'Post KT'],
        'comorbidities' => [
            ['name' => 'DM', 'label' => 'DM'],
            ['name' => 'HT', 'label' => 'HT'],
            ['name' => 'DLP', 'label' => 'DLP'],
            ['name' => 'coronary_artery_disease', 'label' => 'Coronary artery disease'],
            ['name' => 'cerebrovascular_disease', 'label' => 'Cerebrovascular disease'],
            ['name' => 'COPD', 'label' => 'COPD'],
            ['name' => 'cirrhosis', 'label' => 'Cirrhosis'],
            ['name' => 'cancer', 'label' => 'Cancer'],
        ],
        'indications' => [
            ['name' => 'volume_overload', 'label' => 'Volume overload'],
            ['name' => 'metabolic_acidosis', 'label' => 'Metabolic acidosis'],
            ['name' => 'hyperkalemia', 'label' => 'Hyperkalemia'],
            ['name' => 'toxin_removal', 'label' => 'Toxin removal'],
            ['name' => 'initiate_chronic_hd', 'label' => 'Initiate Chronic HD'],
            ['name' => 'maintain_chronic_hd', 'label' => 'Maintain Chronic HD'],
            ['name' => 'change_from_pd', 'label' => 'Change from PD'],
            ['name' => 'uremia', 'label' => 'Uremia'],
            ['name' => 'delayed_graft_function', 'label' => 'Delayed graft function'],
        ],
        'insurances' => ['เบิกจ่ายตรง', 'ประกันสังคม', '30 บาท'],
    ];

    public function __invoke(string $hashed)
    {
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api
        }

        $caseRecord = CaseRecord::query()->findByUnhashKey($hashed)->firstOrFail();

        // form
        $form = $caseRecord->form;
        $form['admission'] = ($form['an'] ?? false)
                        ? (new AdmissionManager)->manage($caseRecord->form['an'])['admission']
                        : [];
        $form['record']['id'] = $caseRecord->id;
        $form['record']['patient_id'] = $caseRecord->patient_id;
        $form['record']['hn'] = $caseRecord->patient->hn;

        // form configs
        $availableDates = [];
        $start = now()->tz($this->TIMEZONE)->addDay();
        $count = 0;
        do {
            if (! $start->isSunday()) {
                $availableDates[] = $start->format('Y-m-d');
            }
            $start->addDay();
            $count++;
        } while ($count <= $this->LIMIT_ADVANCE_DAYS);
        $configs = $this->FORM_CONFIGS + [
            'reserve_available_dates' => $availableDates,
            'reserve_disable_dates' => [], // 'August 13, 2021',
        ];

        // HD orders
        $orders = Note::with(['patient'])
                    ->WithAuthorUsername()
                    ->withPlaceName(Ward::class)
                    ->where('case_record_id', $caseRecord->id)
                    ->where('note_type_id', $this->ACUTE_HD_ORDER_NOTE_TYPE_ID)
                    ->orderByDesc('date_note')
                    ->get()
                    ->transform(function ($note) {
                        return [
                            'ward_name' => $note->place_name,
                            'dialysis_type' => $note->form['dialysis_type'],
                            'date_dialyze' => $note->date_note->format('d M'),
                            'md' => $note->author_username,
                        ];
                    });

        // menu available
        $menu = [
            ['icon' => 'arrow-circle-left', 'label' => 'Back', 'route' => 'procedures.acute-hemodialysis.index', 'can' => true],
            ['icon' => 'slack-hash', 'label' => 'Case Record', 'route' => '#case-record', 'can' => true],
            ['icon' => 'slack-hash', 'label' => 'Orders', 'route' => '#orders', 'can' => true],
            ['icon' => 'slack-hash', 'label' => 'Reservation', 'route' => '#reservation', 'can' => true],
            ['icon' => 'patient', 'label' => 'Patients', 'route' => 'patients', 'can' => true],
            ['icon' => 'clinic', 'label' => 'Clinics', 'route' => 'clinics', 'can' => true],
            ['icon' => 'procedure', 'label' => 'Procedures', 'route' => 'procedures', 'can' => true],
        ];

        return [
            'caseRecordForm' => $form,
            'formConfigs' => $configs,
            'orders' => $orders,
            'flash' => [
                'page-title' => 'Acute HD '.$caseRecord->patient->full_name,
                'main-menu-links' => $menu,
                'action-menu' => [],
            ],
        ];
    }
}
