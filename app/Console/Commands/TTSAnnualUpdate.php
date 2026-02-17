<?php

namespace App\Console\Commands;

use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class TTSAnnualUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tts-annual-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $sheet = (new FastExcel())->import(storage_path('app/excel/siriraj_tts_2025.xlsx'));

        $updatedSheet = [];
        foreach ($sheet as $row) {
            if (
                ((int) $row['Patient_Status_Description'] !== 0 || (int) $row['Graft_Status_Description'] !== 1)
                && (!empty($row['Patient_Status_Description']))
            ) {
                $updatedSheet[] = $row;
                continue;
            }

            $case = KidneyTransplantSurvivalCaseRecord::query()
                ->where('meta->recipient_id', (int) $row['Transplant_Donor_National_Recipient_ID'])
                ->first();

            $this->info('ID ' . $case->meta['recipient_id'] . ' -> ' . 'GS ' . $row['Graft_Status_Description'] . ' - PS ' . $row['Patient_Status_Description']);

            $txDate = Carbon::create($case->meta['date_transplant']);
            // $row['DateDiffGraftStatus'] tx_date with gl_date or last_cr_date
            $row['DateDiffGraftStatus'] = $case->form['date_graft_loss']
                ? (int) $txDate->diffInDays(Carbon::create($case->form['date_graft_loss']))
                : (int) $txDate->diffInDays(Carbon::create($case->form['date_update_graft_status']));
            $this->warn('Update DateDiffGraftStatus: ' . $row['DateDiffGraftStatus']);

            // $row['DateDiffPatientStatus'] tx_date with pl_date or last_cr_date
            $row['DateDiffPatientStatus'] = $case->form['date_dead']
                ? (int) $txDate->diffInDays(Carbon::create($case->form['date_dead']))
                : (int) $txDate->diffInDays(Carbon::create($case->form['date_update_patient_status']));
            $this->warn('Update DateDiffPatientStatus: ' . $row['DateDiffPatientStatus']);

            if (!$row['Transplant_Cr12Month_Date'] && ($case->form['date_year_1_cr'] ?? null)) {
                $this->warn('Update Transplant_Cr12Month_MgDl: ' . $case->form['year_1_cr']);
                $row['Transplant_Cr12Month_Date'] = $case->form['date_year_1_cr'];
                $row['Transplant_Cr12Month_MgDl'] = $case->form['year_1_cr'];
            }

            $this->warn('Update Transplant_CrLast_MgDl: ' . $case->form['latest_cr']);
            $row['Transplant_CrLast_Date'] = $case->form['date_latest_cr'];
            $row['Transplant_CrLast_MgDl'] = $case->form['latest_cr'];

            $this->warn('Update Graft_Status_Description: ' . $case->form['graft_status']);
            $row['Graft_Status_Description'] = $this->getTTSGraftStatus($case->form['graft_status']);
            $row['Transplant_Graft_Status_Date'] = $case->form['date_update_graft_status'];
            if ($row['Graft_Status_Description'] === 0) {
                $ttsGLCode = $this->getTTSGLCode($case->form['graft_loss_codes'][0]['code']);
                $this->error('Update Graft_Loss_Description: ' . $ttsGLCode);
                $row['Graft_Loss_Description'] = $ttsGLCode;
                $row['Transplant_Graft_Loss_Date'] = $case->form['date_graft_loss'];
            }

            $this->warn('Update Patient_Status_Description: ' . $case->form['patient_status']);
            $row['Patient_Status_Description'] = $this->getTTSPatientStatus($case->form['patient_status']);
            $row['Transplant_Patient_Status_Date'] = $case->form['date_update_patient_status'];
            if ($row['Patient_Status_Description'] === 3) {
                $dcCode = $this->getTTSDCCode($case->form['dead_report_codes'][0]['code']);
                $this->error('Update Patient_Loss_Description: ' . $dcCode);
                $row['Patient_Loss_Description'] = $dcCode;
                $row['Transplant_Patient_Loss_Date'] = $case->form['date_dead'];
            }

            $row['Transplant_Last_Modify_Date'] = $case->form['date_last_update'];

            if (!$row['Transplant_Creatinine_M1'] && ($case->form['one_month_cr'] ?? null)) {
                $this->warn('Update Transplant_Creatinine_M1: ' . $case->form['one_month_cr']);
                $row['Transplant_Creatinine_M1_Date'] = $case->form['date_one_month_cr'];
                $row['Transplant_Creatinine_M1'] = $case->form['one_month_cr'];
            }

            if (!$row['Transplant_Creatinine_M6'] && ($case->form['six_month_cr'] ?? null)) {
                $this->warn('Update Transplant_Creatinine_M6: ' . $case->form['six_month_cr']);
                $row['Transplant_Creatinine_M6_Date'] = $case->form['date_six_month_cr'];
                $row['Transplant_Creatinine_M6'] = $case->form['six_month_cr'];
            }

            if (!$row['Transplant_Creatinine_M12'] && ($case->form['year_12_cr'] ?? null)) {
                $this->warn('Update Transplant_Creatinine_M12: ' . $case->form['year_12_cr']);
                $row['Transplant_Creatinine_M12_Date'] = $case->form['date_year_12_cr'];
                $row['Transplant_Creatinine_M12'] = $case->form['year_12_cr'];
            }

            $row['check-ps'] = 'OK';

            $updatedSheet[] = $row;
        }

        (new FastExcel($updatedSheet))->export(storage_path('app/excel/tts_annual_update.xlsx'));
    }

    protected function getTTSGraftStatus(?string $graftStatus): int
    {
        return match (strtolower($graftStatus)) {
            'graft function' => 1,
            'graft loss' => 0,
            'loss follow up' => 10200,
            default => 999
        };
    }

    protected function getTTSPatientStatus(?string $patientStatus): int
    {
        return match (strtolower($patientStatus)) {
            'alive' => 0,
            'dead' => 3,
            'loss follow up' => 1,
            default => 999
        };
    }

    public function getTTSGLCode(int $glCode): int
    {
        return match ($glCode) {
            // --- Rejection & CAN ---
            106                     => 11,   // Hyperacute rejection
            101, 102, 103, 104, 109 => 21,   // Acute (Bx & suspect)
            105                     => 26,   // Chronic/Subacute rejection
            200, 300                => 28,   // CAN (Includes IFTA & CNI tox)
            701                     => 21,   // TMA with rejection

            // --- Death & Compliance ---
            999                     => 29,   // Dead with functioning
            906, 907                => 31,   // Non-compliance/Loss follow up

            // --- Loss due to Other factors (Infection/Malignancy) ---
            32                      => 32,   // Rejection due to malignancy
            601, 602, 801           => 33,   // Loss due to infection (BKVAN, Sepsis)
            36                      => 36,   // Complications of drug therapy

            // --- Glomerular Diseases (GN) ---
            401                     => 41,   // MPGN type 1
            402                     => 42,   // MPGN type 2
            403                     => 43,   // FSGS
            404                     => 44,   // Membranous
            405                     => 45,   // IgA
            406                     => 46,   // Goodpasture's
            407                     => 47,   // ANCA
            408, 409                => 49,   // Other/Suspected GN

            // --- Vascular & Surgical ---
            501                     => 51,   // TRAS
            502                     => 52,   // Artery thrombosis
            503                     => 55,   // Vein thrombosis
            504, 505                => 58,   // Ureteric/Urine leak
            702                     => 65,   // TMA no rejection (Vascular/Cortical necrosis)
            901                     => 92,   // Primary non-functioning

            // --- Malignancy ---
            902                     => 81,   // Donor malignancy
            905                     => 82,   // Malignancy invade graft

            // --- Default / Others ---
            // Unknown/Missing
            802, 904                => 1000, // Others/Miscellaneous
            default                 => 999,  // ป้องกัน Error หากไม่พบรหัส
        };
    }

    public function getTTSDCCode(int $dcCode): int
    {
        return match ($dcCode) {
            // --- 1xx: Infection Group ---
            102           => 36,   // TB -> Tuberculosis (lung)
            105           => 40,   // Pneumonia -> Pulmonary infection (bacterial) *ส่วนใหญ่เป็นแบคทีเรีย
            101, 108      => 35,   // CMV, Sepsis -> Septicaemia
            103, 104, 107 => 34,   // PCP, Strongy, Other -> Infection elsewhere

            // --- 2xx: Cardiac & Vascular Group ---
            201, 207      => 11,   // CAD, Sudden death -> Myocardial ischemia/infarction
            202           => 14,   // Heart failure -> Other causes of cardiac failure
            203           => 15,   // Arrhythmia -> Cardiac arrest, cause unknown
            204           => 22,   // CVA -> Cerebro-vascular accident
            206           => 21,   // Pulmonary embolism -> Pulmonary embolus
            205, 208      => 14,   // PAD, Other cardiac -> Other cardiac failure

            // --- 3xx: Malignancy Group ---
            307, 308, 309 => 66,   // Lymphoma, Leukemia, Hemato -> Lymphoma (PTLD)
            301, 303, 304,
            305, 306, 310,
            311           => 73,   // Solid cancers/Unknown origin -> Non-lymphoid malignant disease
            302           => 49,   // HCC -> Recurrent hepatoma (HCC)

            // --- 4xx & 5xx: Renal & Liver Group ---
            400           => 47,   // Renal failure no RRT -> Renal failure (CRF)
            501           => 42,   // Liver failure (Viral) -> Liver-other viral hepatitis
            502           => 43,   // Liver failure (Drugs) -> Liver-drug toxicity
            503, 504      => 46,   // Liver failure (Alcohol/Other) -> Liver failure-cause unknown

            // --- 6xx - 9xx: Social & Miscellaneous ---
            600           => 82,   // Accident -> Accident unrelated to treatment
            700           => 52,   // Suicide -> Suicide
            800           => 95,   // Other cause -> Other identified cause of death
            // Unknown -> Missing

            default       => 999,
        };
    }

}
