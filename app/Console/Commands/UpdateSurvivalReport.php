<?php

namespace App\Console\Commands;

use App\Enums\KidneyTransplantSurvivalCaseStatus;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Reader\Exception\ReaderNotOpenedException;
use OpenSpout\Writer\Exception\WriterNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;

class UpdateSurvivalReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:survival-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @throws IOException
     * @throws WriterNotOpenedException
     * @throws UnsupportedTypeException
     * @throws InvalidArgumentException
     * @throws ReaderNotOpenedException
     */
    public function handle(): void
    {
        $sheet = (new FastExcel())->import(storage_path('app/siriraj_012024.xlsx'));
        $updatedSheet = [];

        foreach ($sheet as $row) {
            // $this->line('Update case : ' . $row['Transplant_National_Recipient_ID']);
            $updatedSheet[] = $this->UpdateSurvival($row);
        }

        (new FastExcel($updatedSheet))->export(storage_path('app/siriraj_012024_updated.xlsx'));
    }

    protected function UpdateSurvival(array $row): array
    {
        if (! $case = KidneyTransplantSurvivalCaseRecord::query()
            ->where('meta->recipient_id', (int) $row['Transplant_National_Recipient_ID'])
            ->first()) {
            $this->warn('case not found : ' . $row['Transplant_National_Recipient_ID']);

            return $row;
        }

        $form = $case->form;
        $dateTx = Carbon::create($case->meta['date_transplant']);

        // Transplant_CrDay_Date : DAY 5 ?? one week Cr
        // Transplant_CrDay_MgDl : FORMAT Y-m-d H:i:s
        if (! $row['Transplant_CrDay_MgDl'] && $form['one_week_cr']) {
            $row['Transplant_CrDay_MgDl'] = (float) $form['one_week_cr'];
            $row['Transplant_CrDay_Date'] = $dateTx->copy()->addDays(5)->format('Y-m-d H:i:s');
        }

        // Transplant_Cr12Month_Date : FORMAT Y-m-d H:i:s
        // Transplant_Cr12Month_MgDl : AS IS
        if (! $row['Transplant_Cr12Month_MgDl'] && ($form['year_1_cr'] ?? null)) {
            $row['Transplant_Cr12Month_MgDl'] = (float) $form['year_1_cr'];
            $row['Transplant_Cr12Month_Date'] = Carbon::create($form['date_year_1_cr'])->format('Y-m-d H:i:s');
        }

        // Transplant_CrLast_Date : FORMAT Y-m-d H:i:s
        // Transplant_CrLast_MgDl : AS IS
        if (! $row['Transplant_CrLast_MgDl'] && $form['latest_cr']) {
            $row['Transplant_CrLast_MgDl'] = (float) $form['latest_cr'];
            $row['Transplant_CrLast_Date'] = Carbon::create($form['date_latest_cr'])->format('Y-m-d H:i:s');
        }

        // Graft_Status_Description => Transplant_Graft_Status : RECODE
        // Transplant_Graft_Status_Date : FORMAT Y-m-d H:i:s
        // -- DateDiffGraftStatus -> if Transplant_Graft_Status_Date != null && GRAFT_LOSS then  (Transplant_Graft_Loss_Date ?? Transplant_Graft_Status_Date) - date_transplant
        $data = $this->codingGraftStatus($case);
        $row['Graft_Status_Description'] = $data['Graft_Status_Description'];
        $row['Transplant_Graft_Status_Date'] = $data['Transplant_Graft_Status_Date'];
        $row['DateDiffGraftStatus'] = $data['DateDiffGraftStatus'];

        // Graft_Loss_Description => Transplant_Graft_Loss_Cause : RECODE
        // Transplant_Graft_Loss_Date : FORMAT Y-m-d H:i:s
        $row['Graft_Loss_Description'] = $data['Graft_Loss_Description'];
        $row['Transplant_Graft_Loss_Date'] = $data['Transplant_Graft_Loss_Date'];

        // Patient_Status_Description => Transplant_Patient_Status : RECODE
        // Transplant_Patient_Status_Date : FORMAT Y-m-d H:i:s
        // -- DateDiffPatientStatus -> if Transplant_Patient_Status_Date != null && PATIENT_IS_DEAD then (Transplant_Patient_Loss_Date ?? Transplant_Patient_Status_Date) - date_transplant
        $data = $this->codingPatientStatus($case);
        $row['Patient_Status_Description'] = $data['Patient_Status_Description'];
        $row['Transplant_Patient_Status_Date'] = $data['Transplant_Patient_Status_Date'];
        $row['DateDiffPatientStatus'] = $data['DateDiffPatientStatus'];

        // Patient_Loss_Description => Transplant_Patient_Loss_Cause : RECODE
        // Transplant_Patient_Loss_Date : FORMAT Y-m-d H:i:s
        $row['Patient_Loss_Description'] = $data['Patient_Loss_Description'];
        $row['Transplant_Patient_Loss_Date'] = $data['Transplant_Patient_Loss_Date'];

        $row['KT NO'] = $case->meta['kt_no'];

        return array_merge(['KT NO' => $case->meta['kt_no']], $row);
    }

    protected function codingGraftStatus(KidneyTransplantSurvivalCaseRecord $case): array
    {
        $map = [
            '101' => 21,
            '102' => 21,
            '103' => 21,
            '104' => 21,
            '105' => 28,
            '106' => 11,
            '109' => 9999,
            '200' => 9999,
            '300' => 36,
            '401' => 41,
            '402' => 41,
            '403' => 43,
            '404' => 44,
            '405' => 45,
            '406' => 46,
            '407' => 47,
            '408' => 49,
            '409' => 9999,
            '501' => 51,
            '502' => 52,
            '503' => 55,
            '504' => 58,
            '505' => 58,
            '601' => 33,
            '602' => 33,
            '701' => 9999,
            '702' => 9999,
            '801' => 9999,
            '802' => 9999,
            '901' => 65,
            '902' => 9999,
            '903' => 999,
            '904' => 9999,
            '905' => 82,
            '906' => 31,
            '907' => 10200,
            '999' => 10010,
            '0' => 999,
        ];
        $dateTx = Carbon::create($case->meta['date_transplant']);
        $dateUpdateGraftStatus = Carbon::create($case->form['date_update_graft_status']);
        $data['Transplant_Graft_Status_Date'] = $dateUpdateGraftStatus->format('Y-m-d H:i:s');
        $data['DateDiffGraftStatus'] = null;
        $data['Graft_Loss_Description'] = null;
        $data['Transplant_Graft_Loss_Date'] = null;

        if ($case->status === KidneyTransplantSurvivalCaseStatus::ACTIVE) {
            $data['Graft_Status_Description'] = ! $case->form['refer']
                ? 1
                : 10100;

            return $data;
        } elseif ($case->status === KidneyTransplantSurvivalCaseStatus::LOSS_FOLLOW_UP) {
            $data['Graft_Status_Description'] = 10200;

            return $data;
        } elseif (in_array($case->status, [KidneyTransplantSurvivalCaseStatus::DEAD, KidneyTransplantSurvivalCaseStatus::GRAFT_LOSS])) {
            $data['Graft_Status_Description'] = 0;
            $key = (string) ($case->form['graft_loss_codes'][0]['code'] ?? 0);
            if ($key === '0') {
                $this->warn($case->meta['kt_no'] . ' : GLCODE');
            }
            $data['Graft_Loss_Description'] = $map[$key];
            $dateGraftLoss = Carbon::create($case->form['date_graft_loss']);
            $data['Transplant_Graft_Loss_Date'] = $dateGraftLoss->format('Y-m-d H:i:s');
            $data['DateDiffGraftStatus'] = (int) $dateGraftLoss->diffInDays($dateTx);

            return $data;
        }

        $data['Graft_Status_Description'] = 999;

        return $data;
    }

    protected function codingPatientStatus(KidneyTransplantSurvivalCaseRecord $case): array
    {
        $map = [
            '101' => 95,
            '102' => 36,
            '103' => 95,
            '104' => 95,
            '105' => 31,
            '106' => 95,
            '107' => 34,
            '108' => 35,
            '201' => 11,
            '202' => 14,
            '203' => 95,
            '204' => 22,
            '205' => 95,
            '206' => 21,
            '207' => 15,
            '208' => 13,
            '301' => 95,
            '302' => 95,
            '303' => 95,
            '304' => 95,
            '305' => 95,
            '306' => 95,
            '307' => 66,
            '308' => 95,
            '309' => 73,
            '310' => 95,
            '311' => 95,
            '400' => 61,
            '501' => 41,
            '502' => 43,
            '503' => 44,
            '504' => 46,
            '600' => 81,
            '700' => 52,
            '800' => 95,
            '900' => 999,
            '0' => 999,
        ];
        $dateTx = Carbon::create($case->meta['date_transplant']);
        $dateUpdatePatientStatus = Carbon::create($case->form['date_update_patient_status']);
        $data['Transplant_Patient_Status_Date'] = $dateUpdatePatientStatus->format('Y-m-d H:i:s');
        $data['DateDiffPatientStatus'] = null;
        $data['Patient_Loss_Description'] = null;
        $data['Transplant_Patient_Loss_Date'] = null;

        if ($case->status === KidneyTransplantSurvivalCaseStatus::ACTIVE) {
            $data['Patient_Status_Description'] = ! $case->form['refer']
                ? 0
                : 2;

            return $data;
        } elseif ($case->status === KidneyTransplantSurvivalCaseStatus::LOSS_FOLLOW_UP) {
            $data['Patient_Status_Description'] = 1;

            return $data;
        } elseif ($case->status === KidneyTransplantSurvivalCaseStatus::DEAD) {
            $data['Patient_Status_Description'] = 3;
            $key = (string) ($case->form['dead_report_codes'][0]['code'] ?? 0);
            if ($key === '0') {
                $this->warn($case->meta['kt_no'] . ' : CDCODE');
            }
            $data['Patient_Loss_Description'] = $map[$key];
            $dateDate = Carbon::create($case->form['date_dead']);
            $data['Transplant_Patient_Loss_Date'] = $dateDate->format('Y-m-d H:i:s');
            $data['DateDiffPatientStatus'] = (int) $dateDate->diffInDays($dateTx);

            return $data;
        }

        $data['Patient_Status_Description'] = 999;

        return $data;
    }
}
