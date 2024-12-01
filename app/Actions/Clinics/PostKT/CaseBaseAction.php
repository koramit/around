<?php

namespace App\Actions\Clinics\PostKT;

use App\APIs\PortalAPI;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord as CaseRecord;
use App\Models\Resources\Admission;
use App\Models\Resources\Registry;
use App\Traits\AvatarLinkable;
use App\Traits\FlashDataGeneratable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CaseBaseAction
{
    use AvatarLinkable, FlashDataGeneratable;

    protected int $REGISTRY_ID;

    protected array $BREADCRUMBS;

    public function __construct()
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return;
        }

        $this->REGISTRY_ID = cache()->rememberForever(
            'registry-id-kt_survival',
            fn () => Registry::query()->where('name', 'kt_survival')->first()->id
        );

        $this->BREADCRUMBS = [
            ['label' => 'Home', 'route' => route('home')],
            ['label' => 'Clinics', 'route' => route('clinics.index')],
            ['label' => 'Post KT', 'route' => route('clinics.post-kt.index')],
        ];
    }

    protected function getCaseRecord(string $hashedKey): Model|Builder|CaseRecord
    {
        return CaseRecord::query()
            ->findByUnhashKey($hashedKey)
            ->firstOrFail();
    }

    protected function updateCreatinine(KidneyTransplantSurvivalCaseRecord $case): void
    {
        $api = new PortalAPI;
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

        $dateTransplant = Carbon::create($case->meta['date_transplant']);

        if (! $case->form['one_week_cr'] || ! $case->form['date_one_week_cr']) {
            $dateOneWeek = $dateTransplant->copy()->addWeek();
            $selectedLab = $this->searchNearestLab($dateOneWeek, 2, $labs);
            if ($selectedLab) {
                $case->form['one_week_cr'] = $selectedLab->value;
                $case->form['date_one_week_cr'] = explode(' ', $selectedLab->date_lab)[0];
            }
        }

        if (! $case->form['one_month_cr'] || ! $case->form['date_one_month_cr']) {
            $dateOneMonth = $dateTransplant->copy()->addMonth();
            $selectedLab = $this->searchNearestLab($dateOneMonth, 7, $labs);
            if ($selectedLab) {
                $case->form['one_month_cr'] = $selectedLab->value;
                $case->form['date_one_month_cr'] = explode(' ', $selectedLab->date_lab)[0];
            }
        }

        if (! $case->form['three_month_cr'] || ! $case->form['date_three_month_cr']) {
            $dateThreeMonth = $dateTransplant->copy()->addMonths(3);
            $selectedLab = $this->searchNearestLab($dateThreeMonth, 14, $labs);
            if ($selectedLab) {
                $case->form['three_month_cr'] = $selectedLab->value;
                $case->form['date_three_month_cr'] = explode(' ', $selectedLab->date_lab)[0];
            }
        }

        if (! $case->form['six_month_cr'] || ! $case->form['date_six_month_cr']) {
            $dateSixMonth = $dateTransplant->copy()->addMonths(6);
            $selectedLab = $this->searchNearestLab($dateSixMonth, 14, $labs);
            if ($selectedLab) {
                $case->form['six_month_cr'] = $selectedLab->value;
                $case->form['date_six_month_cr'] = explode(' ', $selectedLab->date_lab)[0];
            }
        }

        if (! $case->form['discharge_cr'] || ! $case->form['date_discharge_cr']) {
            $dateDischarged = null;
            if ($case->meta['an'] ?? null) {
                $dateDischarged = Admission::query()
                    ->findByHashKey($case->meta['an'])
                    ->first()
                    ->dismissed_at
                    ->tz('Asia/Bangkok');
            }
            if ($dateDischarged) {
                $selectedLab = $this->searchNearestLab($dateDischarged, 2, $labs);
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
            $coupleMonthsBeforeNextYear = $nextYear->copy()->subDays(60);
            if ($coupleMonthsBeforeNextYear->isFuture() || $coupleMonthsBeforeNextYear->greaterThan($dateLatestCr)) {
                break;
            }

            if (
                ! ($case->form["year_{$yearCount}_cr"] ?? null)
                || ! ($case->form["date_year_{$yearCount}_cr"] ?? null)
            ) {
                $selectedLab = $this->searchNearestLab($nextYear, 60, $labs);
                $case->form["year_{$yearCount}_cr"] = $selectedLab->value ?? null;
                $case->form["date_year_{$yearCount}_cr"] = $selectedLab
                    ? explode(' ', $selectedLab->date_lab)[0]
                    : null;
            }

            $yearCount++;
        }

        $case->save();
    }

    protected function searchNearestLab(Carbon $refDate, int $dayOffset, Collection $labs): ?object
    {
        $minutesOffset = $dayOffset * 24 * 60;
        $nearestLab = $labs->map(fn ($lab) => (object) [
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
