<?php

namespace App\Traits\AcuteHemodialysis;

trait OrderShareValidatable
{
    protected array $IN_UNIT = [
        'HD 2 hrs.',
        'HD 3 hrs.',
        'HD 4 hrs.',
        'HD+HF 4 hrs.',
        'HD+TPE 6 hrs.',
        'HF 2 hrs.',
        'TPE 2 hrs.',
    ];

    protected array $OUT_UNIT = [
        'HD 2 hrs.',
        'HD 3 hrs.',
        'HD 4 hrs.',
        'HD+HF 4 hrs.',
        'HD+TPE 6 hrs.',
        'HF 2 hrs.',
        'TPE 2 hrs.',
        'SLEDD',
    ];

    protected function getAllDialysisType(): array
    {
        return collect($this->IN_UNIT)->merge($this->OUT_UNIT)->unique()->values()->all();
    }

    public function isDialysisReservable($caseRecord): bool
    {
        return $caseRecord->orders()->activeStatuses()->count() === 0;
    }

    protected function getPossibleDates(): array
    {
        $today = now()->create($this->TODAY);
        $dates = [$today->clone()];
        for ($i = 1; $i <= 3; $i++) {
            $nextDay = $today->addDay();
            if ($nextDay->is($this->UNIT_DAY_OFF)) {
                continue;
            }
            $dates[] = $nextDay->clone();
        }

        return $dates;
    }

    protected function zombieHours(string $dateDialysis): bool
    {
        $dateDialysis = now()->create($dateDialysis);
        if (!$dateDialysis->is('Saturday')) {
            return false;
        }

        $ZOMBIES_HOURS_BEGIN = now()->create($dateDialysis->clone()->addDays(-2)->format('Y-m-d'). ' 13:00:00');
        $now = now();

        if (
            $now->greaterThanOrEqualTo($ZOMBIES_HOURS_BEGIN)
            && $now->lessThan($dateDialysis)
        ) {
            return true;
        }

        return false;
    }
}
