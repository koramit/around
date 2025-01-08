<?php

namespace App\Console\Commands;

use App\Actions\Clinics\PostKT\CaseStoreAction;
use App\APIs\PortalAPI;
use App\Enums\KidneyTransplantSurvivalCaseStatus;
use App\Managers\Resources\AdmissionManager;
use App\Managers\Resources\PatientManager;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\Resources\Patient;
use App\Models\Resources\Registry;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Reader\Exception\ReaderNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;

class MigrateKTSurvivalCase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate-data:kt-survival-case {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected array $caewGLCodes = [
        '0' => 'Unknown',
        '1' => 'Acute rejection',
        '2' => 'Infection- reduce ISD-rejection',
        '3' => 'Malignancy- reduce ISD-rejection',
        '4' => 'CAN',
        '5' => 'Dead with functioning graft',
        '6' => 'Loss follow up',
        '7' => 'GN',
        '8' => 'Malignancy invade graft',
        '9' => 'Renal artery stenosis',
        '10' => 'Renal artery thrombosis',
        '11' => 'Renal vein thrombosis',
        '12' => 'Urine leak',
        '13' => 'Other ดูใน',
    ];

    protected array $glCodesMap = [
        '0' => '903',
        '1' => '101',
        '2' => '601',
        '4' => '105',
        '5' => '999',
        '6' => '907',
        '7' => '401',
        '8' => '905',
        '9' => '502',
        '10' => '502',
        '11' => '503',
        '12' => '505',
        '13' => '904',
    ];

    protected array $socGLCodes = [
        '11' => 'Hyperacute rejection',
        '21' => 'Acute rejection ไตเคยทำงานได้มาก่อนแล้วมี severe rejection',
        '28' => 'Chronic rejection ไตเคยทำงานได้มาก่อนหลายเดือน  CAN',
        '31' => 'Non-compliance',
        '32' => 'Malignancy - reduce ISD - rejection',
        '33' => 'Infection - reduce ISD - rejection',
        '36' => 'ISD side effect/complication - reduce ISD - rejection',
        '41' => 'MPGN1',
        '42' => 'MPGN2',
        '43' => 'FSGS',
        '44' => 'Membranous GN',
        '45' => 'IgA nephropathy',
        '46' => 'Goodpasture',
        '47' => 'RPGN',
        '49' => 'Other GN(IgMN)',
        '51' => 'Transplant renal artery stenosis',
        '52' => 'Transplant renal artery thrombosis',
        '55' => 'Transplant renal vein thrombosis / stenosis',
        '58' => 'Ureteric obstruction/leak, bladder problem',
        '61' => 'Thromboemboli',
        '62' => 'Cholesterol emboli',
        '65' => 'Cortical necrosis (exclude rejection)',
        '81' => 'Donor malignancy',
        '82' => 'Malignancy invade graft',
        '91' => 'non viable kidney, cortical necrosis',
        '10010' => 'Recipient died, graft function',
        '10200' => 'loss f/u > 6 mo',
        '9999' => 'อื่นๆ',
        '999' => 'No data ไม่ทราบ ไม่มีข้อมูล',
    ];

    protected array $caewDeadCodes = [
        '0' => 'Unknown',
        '1' => 'Infection',
        '2' => 'Cardiovascular',
        '3' => 'Malignancy',
        '4' => 'Uremia',
        '5' => 'Accident',
        '6' => 'Liver disease',
        '7' => 'Loss follow up',
        '8' => 'Other ดูใน Note',
    ];

    protected array $deadCodesMap = [
        '0' => '900',
        '1' => '101',
        '2' => '201',
        '3' => '301',
        '4' => '400',
        '5' => '600',
        '6' => '501',
        '8' => '700',
    ];

    protected array $socDeadCodes = [
        '11' => 'MI',
        '12' => 'Hyperkalemia',
        '13' => 'Pericarditis',
        '14' => 'Other causes of cardiac failure',
        '15' => 'Cardiac arrest, unknown cause',
        '16' => 'Hypertensive cardiac failure',
        '17' => 'Hypokalemia',
        '20' => 'Tracheal dehiscence',
        '21' => 'Pulmonaemboli',
        '22' => 'CVA,Stroke,cerebral hemorrhage',
        '23' => 'GI bleed',
        '24' => 'Bleeding from graft',
        '25' => 'Bleeding from dialysis access',
        '26' => 'Bleeding from  rupture vascular aneurysm',
        '27' => 'Bleeding from surgery',
        '28' => 'Bleeding from other cause',
        '29' => 'Mesenteric infarction',
        '31' => 'Pneumonia : bacterial',
        '32' => 'Pneumonia : viral',
        '33' => 'Pneumoniafungal',
        '34' => 'Infection อื่นๆ นอกจาก pneumonia, viral hepatitis',
        '35' => 'Sepsis',
        '36' => 'Pulmonary TB',
        '37' => 'Non pulmonary TB',
        '38' => 'Viral infection',
        '39' => 'Peritonitis',
        '41' => 'Liver failure due to HBV',
        '42' => 'Liver failure due to non HBV viral hepatitis (HAV, HCV)',
        '43' => 'Liver failure due to drug (acetaminophen-tylenol, para, etc)',
        '44' => 'Cirrhosis, not from viral hepatitis (alcohol)',
        '45' => 'Cystic liver disease',
        '46' => 'Liver failure not from 41-45',
        '52' => 'Suicide',
        '61' => 'Uremia',
        '62' => 'Pancreatitis',
        '63' => 'BM failure',
        '64' => 'Cachexia',
        '66' => 'Lymphoid malignant disease possibly Malignancy (suspect lymphoma: no Bx)',
        '67' => 'Lymphoid malignant disease (lymphoma: Bx)',
        '71' => 'PU perforate',
        '72' => 'Colon perforate',
        '73' => 'Suspect Malignancy not lymphoma : (suspect other cancer: no Biopsy)',
        '74' => 'Non-lymphoid malignant disease : (Biopsy : other cancer)',
        '81' => 'Accident (iatrogenic)',
        '82' => 'Accident (non iatrogenic)',
        '95' => 'Other identified causes of death',
        '999' => 'Missing ไม่ทราบ ไม่มีข้อมูล',
    ];

    protected ?Collection $DOBS = null;
    protected ?Collection $DONORS = null;
    protected ?Collection $CD_HOSPITAL = null;
    protected int $REGISTRY_ID;

    /**
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws ReaderNotOpenedException
     */
    public function handle(): void
    {
        $this->REGISTRY_ID = cache()->rememberForever(
            'registry-id-kt_survival',
            fn () => Registry::query()->where('name', 'kt_survival')->first()->id
        );
        $this->DONORS = (new FastExcel)->import(storage_path("app/seeders/donor_{$this->argument('file')}.xlsx"));
        $this->CD_HOSPITAL = (new FastExcel)->import(storage_path("app/seeders/cd_hospital.xlsx"));
        $cases = (new FastExcel)->import(storage_path("app/seeders/export_survival_{$this->argument('file')}.xlsx"));
        $cases = $cases->sortBy(['KTID']);

        /** @var User $user */
        $user = User::query()->first();

        foreach ($cases as $case) {
            $case = $this->tryCreate($case, $user);
            if ($case['ok']) {
                $this->info("Case: {$case['case_no']} --> OK. ".$case['message'] ?? '');
            } else {
                $this->error("Case: {$case['case_no']} --> {$case['message']}.");
            }
        }
    }

    /**
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws ReaderNotOpenedException
     */
    protected function tryCreate(array $data, User $user): array
    {
        // check exists
        $caseId = (int) $data['KTID'];
        $runNo = $caseId % 1000;
        $dateTx = Carbon::create($data['Tx_date']);
        $thaiYear = ($dateTx->year + 543) % 100;
        $caseNo = implode('-', [
            $thaiYear,
            $runNo > 99
            ? $runNo
            : str_pad($runNo, 2, '0', STR_PAD_LEFT),
        ]);
        $existsCase = KidneyTransplantSurvivalCaseRecord::query()
            ->where('status', '!=', KidneyTransplantSurvivalCaseStatus::DELETED)
            ->where('meta->kt_no', $caseNo)
            ->first();

        if ($existsCase) {
            return [
                'ok' => false,
                'message' => 'Case already exists',
                'case_no' => $data['KT NO'],
            ];
        }

        $api = new PortalAPI;

        $admissionData = $api->getPatientAdmissions($data['recipientHN']);
        $patientNote = null;
        if (! $admissionData['found']) {
            $admissionData['admissions'] = [];
            $patientNote = 'NO ADMISSION RECORD';
        }

        $dataKeys = array_keys($data);
        foreach ($dataKeys as $key) {
            if (gettype($data[$key]) === 'string') {
                $data[$key] = trim($data[$key]);
            }
        }

        $admissionTxFiltered = collect($admissionData['admissions'])
            ->filter(static fn ($admission) => $dateTx->greaterThanOrEqualTo(Carbon::create(explode(' ', $admission['admitted_at'])[0]))
                && $dateTx->lessThan(Carbon::create(explode(' ', $admission['discharged_at'])[0]))
            )->first();

        if ($admissionTxFiltered) {
            $admission = (new AdmissionManager)->manage($admissionTxFiltered['an'])['admission'];
            $case = (new CaseStoreAction)->createWithAdmission($admission, $dateTx, $caseId, $caseNo, $user);
        } else {
            $patientData = (new PatientManager)->manage($data['recipientHN']);
            if ($patientData['found']) {
                $patient = $patientData['patient'];
                $patientNote = 'NO ADMISSION RECORD';
            } else {
                $patient = $this->createPatient($data);
                $patientNote = 'NO PATIENT RECORD';
            }
            $case = (new CaseStoreAction)->createWithPatient($patient, $dateTx->format('Y-m-d'), $caseId, $caseNo, $user, ! $patientData['found']);
        }

        $form = $case->form;
        $form['date_last_update'] = Carbon::create($data['Date update'])->format('Y-m-d');

        $remark['name'] = '';
        foreach (['recipient_name', 'recipient_surname', 'oldname', 'oldLastname', 'LowestCrin1yr', 'DateWithi1stYear', 'LowestCr', 'LowestDate', 'graftStatusTxsoc', 'PtStatusTxsoc'] as $field) {
            $value = $data[$field];
            if (! $value) {
                continue;
            }
            if (gettype($value) === 'object') {
                $remark['name'] .= "$field: ".$value->format('Y-m-d')."\n";
            } else {
                $remark['name'] .= "$field: $value\n";
            }
        }

        if ($patientNote) {
            $remark['name'] .= "$patientNote\n";
        }
        $form['remark'] = $remark['name'];

        if ($data['status'] === 'D') {
            $form['patient_status'] = 'dead';
            $form['graft_status'] = 'graft loss';
        } elseif ($data['status'] === 'A') {
            $form['patient_status'] = 'alive';
            $form['graft_status'] = ((int) $data['graftstatus']) === 1
                ? 'graft function'
                : 'graft loss';
        }

        $form['date_update_graft_status'] = $data['GL_update']
            ? Carbon::create($data['GL_update'])->format('Y-m-d')
            : null;
        $form['date_graft_loss'] = $data['GL Date']
            ? Carbon::create($data['GL Date'])->format('Y-m-d')
            : null;

        foreach (['codeGL1', 'codeGL2'] as $field) {
            if ($data[$field] && ((int)$data[$field]) >= 100) {
                $form['graft_loss_codes'][] = ['code' => (string) $data[$field], 'specification' => null];
            }
        }

        $remark['graft loss'] = '';
        foreach (['codeGL1', 'codeGL2', 'Cause of Graft loss', 'Cause of GL', 'GN', 'Note', 'Contact No', 'graftLossTxsoc'] as $field) {
            $value = $data[$field];
            if (! $value) {
                continue;
            }
            if (in_array($field, ['codeGL1', 'codeGL2'])) {
                if (array_key_exists((string) $value, $this->caewGLCodes)) {
                    $lookupValue = $value.'|'.$this->caewGLCodes[(string) $value];
                    if (array_key_exists((string) $value, $this->glCodesMap)) {
                        $form['graft_loss_codes'][] = ['code' => $this->glCodesMap[(string) $value], 'specification' => null];
                    }
                } else {
                    $lookupValue = $value;
                }

                $remark['graft loss'] .= "$field: $lookupValue\n";
            } elseif ($field === 'graftLossTxsoc') {
                $lookupValue = array_key_exists((string) $value, $this->socGLCodes)
                    ? $value.'|'.$this->socGLCodes[(string) $value]
                    : $value;
                $remark['graft loss'] .= "$field: $lookupValue\n";
            } else {
                $remark['graft loss'] .= "$field: $value\n";
            }
        }
        $form['graft_loss_status_note'] = $remark['graft loss'];

        $form['date_update_patient_status'] = $data['deadUpdate']
            ? Carbon::create($data['deadUpdate'])->format('Y-m-d')
            : null;
        $form['date_dead'] = $data['Dead date']
            ? Carbon::create($data['Dead date'])->format('Y-m-d')
            : null;

        foreach (['codeDead1', 'codeDead2'] as $field) {
            if ($data[$field] && ((int) $data[$field]) > 100) {
                $form['dead_report_codes'][] = ['code' => (string) $data[$field], 'specification' => null];
            }
        }

        $remark['dead cause'] = '';
        foreach (['codeDead1', 'codeDead2', 'Cause of dead', 'Cause of death', 'DeadCauseTxsoc', 'DeadNote'] as $field) {
            $value = $data[$field];
            if (! $value) {
                continue;
            }
            if (in_array($field, ['codeDead1', 'codeDead2'])) {
                if (array_key_exists((string) $value, $this->caewDeadCodes)) {
                    $lookupValue = $value.'|'.$this->caewDeadCodes[(string) $value];
                    if (array_key_exists((string) $value, $this->deadCodesMap)) {
                        $form['dead_report_codes'][] = ['code' => $this->deadCodesMap[(string) $value], 'specification' => null];
                    }
                } else {
                    $lookupValue = $value;
                }

                $remark['dead cause'] .= "$field: $lookupValue\n";
            } elseif ($field === 'DeadCauseTxsoc') {
                $lookupValue = array_key_exists((string) $value, $this->socDeadCodes)
                    ? $value.'|'.$this->socDeadCodes[(string) $value]
                    : $value;
                $remark['dead cause'] .= "$field: $lookupValue\n";
            } else {
                $remark['dead cause'] .= "$field: $value\n";
            }
        }
        $admissionDead = collect($admissionData['admissions'])
            ->filter(static fn ($admission) => str_contains($admission['discharge_type'], 'DEATH'))
            ->first();
        if ($admissionDead) {
            $remark['dead cause'] .= "-----\nDead at ศิริราช\n";
            $remark['dead cause'] .= "AN: {$admissionDead['an']}\n";
            $remark['dead cause'] .= "Admit: {$admissionDead['admitted_at']}\n";
            $remark['dead cause'] .= "D/C: {$admissionDead['discharged_at']}\n";
            $remark['dead cause'] .= "Ward: {$admissionDead['ward_name']}\n";
            $remark['dead cause'] .= "Division: {$admissionDead['division']}\n";
            $remark['dead cause'] .= "Type: {$admissionDead['discharge_type']}\n";
            $remark['dead cause'] .= "Status: {$admissionDead['discharge_status']}\n";
        }
        $form['patient_status_note'] = $remark['dead cause'];

        $cr_map = [
            'discharge_cr' => 'D/CCr',
            'date_discharge_cr' => 'D/CCrDate',
            'one_week_cr' => '1 wk',
            'date_one_week_cr' => '1 wk date',
            'one_month_cr' => '1mo',
            'date_one_month_cr' => '1mo  date',
            'three_month_cr' => '3mo',
            'date_three_month_cr' => '3mo date',
            'six_month_cr' => '6Mo',
            'date_six_month_cr' => '6MoDate',
        ];

        foreach ($cr_map as $des => $org) {
            if (! $form[$des] && $data[$org]) {
                if (gettype($data[$org]) === 'object') {
                    $form[$des] = Carbon::create($data[$org])->format('Y-m-d');
                } else {
                    $form[$des] = $data[$org];
                }
            }
        }

        $yearCount = 1;
        while (true) {
            if (! array_key_exists("year_{$yearCount}_cr", $form->toArray())) {
                break;
            }
            if (! $form["year_{$yearCount}_cr"] && ($data["{$yearCount}yr"] ?? null)) {
                $form["year_{$yearCount}_cr"] = $data["{$yearCount}yr"];
                $form["date_year_{$yearCount}_cr"] = $data["{$yearCount}yrDate"]
                    ? Carbon::create($data["{$yearCount}yrDate"])->format('Y-m-d')
                    : null;
            }
            $yearCount++;
        }

        if (! $form['latest_cr'] && $data['last cr']) {
            $form['latest_cr'] = $data['last cr'];
            $form['date_latest_cr'] = $data['last crDate']
                ? Carbon::create($data['last crDate'])->format('Y-m-d')
                : null;
        }

        $dateGraftLoss = $form['date_graft_loss']
            ? Carbon::create($form['date_graft_loss'])
            : null;

        if ($dateGraftLoss) {
            $graftFunctionYears = $dateGraftLoss->year - $dateTx->year;
            $txPassedYears = Carbon::now()->year - $dateTx->year;
            for ($y = ($graftFunctionYears + 1); $y <= $txPassedYears; $y++) {
                if (array_key_exists("year_{$y}_cr", $form->toArray())) {
                    unset($form["year_{$y}_cr"], $form["date_year_{$y}_cr"]);
                }
            }
        }

        $case->form = $form;
        $case->status = KidneyTransplantSurvivalCaseStatus::fromGraftPatientStatus($form['graft_status'], $form['patient_status']);
        $case->save();

        $reply = [
            'ok' => true,
            'case_no' => $data['KT NO'],
            'message' => $patientNote,
        ];

        if (!$donor = $this->DONORS->first(fn ($d) => (int) $d['r_id'] === $caseId)) {
            $this->warn($caseId. ' --> NO DONOR FOUND');
            return $reply;
        };

        $case->meta['donor_id'] = $donor['d_id'];
        $case->meta['donor_type'] = $donor['d_type'] === 'CD'
            ? 'CD single kidney'
            : 'LD';
        if ($donor['d_type'] === 'CD') {
            $case->meta['donor_redcross_id'] = $donor['d_no'];
            if ($donor['d_title']) {
                if ($hospital = $this->CD_HOSPITAL->first(fn ($h) => $h['source'] === $donor['d_title'])) {
                    $case->form['donor_hospital'] = $hospital['map'];
                }
            }
        } else {
            $case->form['donor_hn_recheck'] = implode(' ', [$donor['d_title'], $donor['d_fname'], $donor['d_lname']]);
            if ($donor['d_no']) {
                $livingDonor = (new PatientManager())->manage($donor['d_no']);
                if ($livingDonor['found']) {
                    $livingDonor = $livingDonor['patient'];
                    $case->form['donor_hn'] = $livingDonor->hn;
                    $case->form['donor_name'] = $livingDonor->full_name;
                    $case->form['donor_gender'] = $livingDonor->gender;
                    $case->form['donor_age'] = abs((int) $dateTx->diffInYears($livingDonor->dob));
                } else {
                    $this->warn($caseId. ' --> DONOR HN NOT FOUND');
                }
            }
        }

        foreach (['ms1', 'ms2', 'ms3'] as $ms) {
            if ($donor[$ms]) {
                $case->form['medical_scheme'] = $donor[$ms];
                break;
            }
        }

        if (str_contains($donor['case_no'], 'HLK')) {
            $case->form['combined_with_liver'] = true;
            $case->form['combined_with_heart'] = true;
        } elseif (str_contains($donor['case_no'], 'LK')) {
            $case->form['combined_with_liver'] = true;
        } elseif (str_contains($donor['case_no'], 'HK')) {
            $case->form['combined_with_heart'] = true;
        } elseif (str_contains($donor['case_no'], 'SPK')) {
            $case->form['combined_with_pancreas'] = true;
        }

        $case->save();

        return $reply;
    }

    /**
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws ReaderNotOpenedException
     */
    protected function createPatient(array $data): Patient
    {
        if (! $this->DOBS) {
            $this->DOBS = (new FastExcel)->import(storage_path('app/seeders/r_dob.xlsx'));
        }

        $dob = $this->DOBS->filter(static fn ($row) => (int) $row['ID'] === (int) $data['KTID'])->first();

        $patient = new Patient;
        $patient->hn = $data['recipientHN'];
        $patient->alive = $data['status'] === 'A';
        $patient->gender = strtolower($dob['Rsex']) === 'male';
        if ($dob['Rbirthdate']) {
            $patient->dob = Carbon::create($dob['Rbirthdate']);
        } elseif ($dob['ageatTx']) {
            $patient->dob = Carbon::create(Carbon::create($data['Tx_date'])->year - (int) $dob['ageatTx'], 7);
        } else {
            $patient->dob = Carbon::create(1900);
        }

        $patient->profile = [
            'patient_name' => $data['recipient_name'].' '.$data['recipient_surname'],
            'title' => null,
            'first_name' => $data['recipient_name'],
            'middle_name' => null,
            'last_name' => $data['recipient_surname'],
            'document_id' => null,
            'race' => 'ไทย',
            'nation' => 'ไทย',
            'tel_no' => null,
            'spouse' => null,
            'address' => null,
            'subdistrict' => null,
            'district' => null,
            'postcode' => null,
            'province' => null,
            'insurance_name' => null,
            'marital_status' => null,
            'alternative_contact' => null,
        ];

        $patient->save();

        return $patient;
    }
}
