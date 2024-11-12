<?php

namespace App\Actions\Clinics\PostKT;

use App\APIs\PortalAPI;
use App\Enums\KidneyTransplantSurvivalCaseStatus;
use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\Resources\Admission;
use App\Models\User;
use App\Rules\AnExists;
use App\Traits\CaseRecordFinishable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class CaseStoreAction extends CaseBaseAction
{
    use CaseRecordFinishable;

    protected float $CRF_VERSION = 1.0;

    public function __invoke(array $data, User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $validated = Validator::validate($data, [
            'an' => ['required', 'digits:8', new AnExists],
            'date_transplant' => ['required', 'date'],
            'case_no' => ['required', 'integer', 'min:1', 'max:200'],
        ]);

        $admission = Admission::query()->findByHashKey($validated['an'])->first();
        $year = $admission->encountered_at->tz('Asia/Bangkok')->year;
        $thaiYear = $year + 543;
        $caseNo = (int) $validated['case_no'];
        $ktNo = ($thaiYear % 100) . '-' . ($caseNo > (99) ? $caseNo : str_pad($caseNo, 2, '0', STR_PAD_LEFT));
        $ktId = '1' . $year . str_pad($caseNo, 3, '0', STR_PAD_LEFT);

        if ($case = KidneyTransplantSurvivalCaseRecord::query()
            ->where('form->kt_no', $ktNo)
            ->first()
        ) {
            return ['key' => $case->hashed_key];
        }

        $case = new KidneyTransplantSurvivalCaseRecord();

        $case->patient_id = $admission->patient_id;
        $case->form = [
            'date_transplant' => $validated['date_transplant'],
            'kt_no' => $ktNo,
            'kt_id' => (int) $ktId,
            'graft_status' => 'graft function',
            'date_latest_cr' => null,
            'latest_cr' => null,
            'patient_status' => 'alive',
            'discharge_cr' => null,
            'date_discharge_cr' => null,
            'one_week_cr' => null,
            'date_one_week_cr' => null,
            'one_month_cr' => null,
            'date_one_month_cr' => null,
            'three_month_cr' => null,
            'date_three_month_cr' => null,
            'six_month_cr' => null,
            'date_six_month_cr' => null,
        ];
        $case->status = KidneyTransplantSurvivalCaseStatus::ACTIVE;
        $case->meta = [
            'version' => $this->CRF_VERSION,
            'hn' => $admission->patient->hn,
            'an' => $admission->an,
            'name' => $admission->patient->full_name,
        ];
        $case->save();
        $case->update(['meta->title' => $case->genTitle()]);
        $this->finishing($case, $case->patient, $user, $this->REGISTRY_ID);

        $this->updateCreatinine($case);

        return ['key' => $case->hashed_key];
    }

    public function updateCreatinine(KidneyTransplantSurvivalCaseRecord $case): void
    {
        $api = new PortalAPI();
        $result = $api->getLabFromItemCodeAllResults($case->meta['hn'], '0020');
        if (! $result['found']) {
            return;
        }

        $labs = collect(array_map(fn ($item) => (object) $item, $result['reports']));
        $latestLabs = $labs->sortByDesc('datetime_specimen_received');
        if ($latestLabs->first()->datetime_specimen_received > $case->form['date_latest_cr']) {
            $case->form['date_latest_cr'] = explode(' ', $latestLabs->first()->datetime_specimen_received)[0];
            $case->form['latest_cr'] = $latestLabs->first()->value_numeric;
        }

        $dateLatestCr = Carbon::create($case->form['date_latest_cr']);

        $dateTransplant = Carbon::create($case->form['date_transplant']);

        if (!$case->form['one_week_cr'] || !$case->form['date_one_week_cr']) {
            $dateOneWeek = $dateTransplant->copy()->addWeek();
            $selectedLab = $this->searchNearestLab($dateOneWeek, 3, $labs);
            if ($selectedLab) {
                $case->form['one_week_cr'] = $selectedLab->value;
                $case->form['date_one_week_cr'] = explode(' ', $selectedLab->date_lab)[0];
            }
        }

        if (!$case->form['one_month_cr'] || !$case->form['date_one_month_cr']) {
            $dateOneMonth = $dateTransplant->copy()->addMonth();
            $selectedLab = $this->searchNearestLab($dateOneMonth, 7, $labs);
            if ($selectedLab) {
                $case->form['one_month_cr'] = $selectedLab->value;
                $case->form['date_one_month_cr'] = explode(' ', $selectedLab->date_lab)[0];
            }
        }

        if (!$case->form['three_month_cr'] || !$case->form['date_three_month_cr']) {
            $dateThreeMonth = $dateTransplant->copy()->addMonths(3);
            $selectedLab = $this->searchNearestLab($dateThreeMonth, 14, $labs);
            if ($selectedLab) {
                $case->form['three_month_cr'] = $selectedLab->value;
                $case->form['date_three_month_cr'] = explode(' ', $selectedLab->date_lab)[0];
            }
        }

        if (!$case->form['six_month_cr'] || !$case->form['date_six_month_cr']) {
            $dateSixMonth = $dateTransplant->copy()->addMonths(6);
            $selectedLab = $this->searchNearestLab($dateSixMonth, 14, $labs);
            if ($selectedLab) {
                $case->form['six_month_cr'] = $selectedLab->value;
                $case->form['date_six_month_cr'] = explode(' ', $selectedLab->date_lab)[0];
            }
        }

        if (!$case->form['discharge_cr'] || !$case->form['date_discharge_cr']) {
            $dateDischarged = null;
            if ($case->meta['an'] ?? null) {
                $dateDischarged = Admission::query()
                    ->findByHashKey($case->meta['an'])
                    ->first()
                    ->dismissed_at
                    ->tz('Asia/Bangkok');
            }
            if ($dateDischarged) {
                $selectedLab = $this->searchNearestLab($dateDischarged, 14, $labs);
                if ($selectedLab) {
                    $case->form['discharge_cr'] = $selectedLab->value;
                    $case->form['date_discharge_cr'] = explode(' ', $selectedLab->date_lab)[0];
                }
            }
        }

        $yearCount = 1;
        $nextYear = $dateTransplant->copy();
        while (true) {
            $nextYear->addYear();
            if ($nextYear->isFuture() || $nextYear->greaterThan($dateLatestCr)) {
                break;
            }

            if (!($case->form["year_{$yearCount}_cr"] ?? null) || !($case->form["date_year_{$yearCount}_cr"] ?? null)) {
                $selectedLab = $this->searchNearestLab($nextYear, 60, $labs);
                if ($selectedLab) {
                    $case->form["year_{$yearCount}_cr"] = $selectedLab->value;
                    $case->form["date_year_{$yearCount}_cr"] = explode(' ', $selectedLab->date_lab)[0];
                }
            }

            $yearCount++;
        }

        $case->save();
    }

    public function searchNearestLab(Carbon $refDate, int $dayOffset, Collection $labs): ?object
    {
        $minutesOffset = $dayOffset * 24 * 60;
        $nearestLab = $labs->map(fn ($lab) => (object)[
                'value' => $lab->value_numeric,
                'date_lab' => $lab->datetime_specimen_received,
                'time_diff' => abs($refDate->diffInMinutes(Carbon::create($lab->datetime_specimen_received))),
            ])->sortBy('time_diff')
            ->first();

        return $nearestLab->time_diff <= $minutesOffset
            ? $nearestLab
            : null;
    }
}
