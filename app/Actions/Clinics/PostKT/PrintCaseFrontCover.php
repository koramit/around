<?php

namespace App\Actions\Clinics\PostKT;

use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\User;
use App\Traits\AvatarLinkable;
use App\Traits\FirstNameAware;
use Illuminate\Support\Carbon;

class PrintCaseFrontCover
{
    use AvatarLinkable, FirstNameAware;

    public function __invoke(string $hashedKey, User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $case = KidneyTransplantSurvivalCaseRecord::query()
            ->findByUnhashKey($hashedKey)
            ->firstOrFail();

        // $data['donor_type'];

        // donor type
        // patient name
        // cause_of_esrd
        // has_native_bx_report
        // nephro
        // surgeon
        // PRC_unit
        // baseline_cr
        // pre kt cr
        // CD_is => male 28Yo non-Trauma
        // CD cause of death
        // clamp time => xxxx-xx-xx xx:xx
        // graft function =>

        $dateTx = Carbon::create($case->meta['date_transplant']);
    }
}
