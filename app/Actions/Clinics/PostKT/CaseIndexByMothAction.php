<?php

namespace App\Actions\Clinics\PostKT;

use App\Enums\KidneyTransplantSurvivalCaseStatus;
use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\User;
use App\Traits\AvatarLinkable;

class CaseIndexByMothAction
{
    use AvatarLinkable;

    public function __invoke(string $month, AvatarUser|User $user)
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        return KidneyTransplantSurvivalCaseRecord::query()
            ->where('status', KidneyTransplantSurvivalCaseStatus::ACTIVE)
            ->where('meta->month', match ($month) {
                'Jan' => 1,
                'Feb' => 2,
                'Mar' => 3,
                'Apr' => 4,
                'May' => 5,
                'Jun' => 6,
                'Jul' => 7,
                'Aug' => 8,
                'Sep' => 9,
                'Oct' => 10,
                'Nov' => 11,
                'Dec' => 12,
            })->get()
            ->transform(fn (KidneyTransplantSurvivalCaseRecord $case) => [
                'case_no' => $case->meta['kt_no'],
                'patient' => 'HN '.$case->meta['hn'].' '.$case->meta['name'],
                'url' => route('clinics.post-kt.annual-update', $case->hashed_key),
            ]);
    }
}
