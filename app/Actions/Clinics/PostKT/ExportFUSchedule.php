<?php

namespace App\Actions\Clinics\PostKT;

use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\User;
use App\Traits\AvatarLinkable;
use Illuminate\Support\Carbon;

class ExportFUSchedule
{
    use AvatarLinkable;

    public function __invoke(string $hashedKey, User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $case = KidneyTransplantSurvivalCaseRecord::query()
            ->findByUnhashKey($hashedKey)
            ->firstOrFail();

        $dateTx = Carbon::create($case->meta['date_transplant']);
        $sheet = [
            [
                'KT-NO' => $case->meta['kt_no'],
                'date_kt' => $dateTx->format('d-M-Y'),
                'Wk1' => $dateTx->copy()->addWeek()->format('d-M-Y'),
                'Wk2' => $dateTx->copy()->addWeeks(2)->format('d-M-Y'),
                'Wk4' => $dateTx->copy()->addWeeks(4)->format('d-M-Y'),
                'Wk8' => $dateTx->copy()->addWeeks(8)->format('d-M-Y'),
                'Wk12' => $dateTx->copy()->addWeeks(12)->format('d-M-Y'),
                'Wk16' => $dateTx->copy()->addWeeks(16)->format('d-M-Y'),
                'Wk20' => $dateTx->copy()->addWeeks(20)->format('d-M-Y'),
                'Wk24' => $dateTx->copy()->addWeeks(24)->format('d-M-Y'),
                'Wk36' => $dateTx->copy()->addWeeks(36)->format('d-M-Y'),
                'Wk48' => $dateTx->copy()->addWeeks(48)->format('d-M-Y'),
                'Wk96' => $dateTx->copy()->addWeeks(96)->format('d-M-Y'),
                'Wk144' => $dateTx->copy()->addWeeks(144)->format('d-M-Y'),
                'Wk192' => $dateTx->copy()->addWeeks(192)->format('d-M-Y'),
                'Wk240' => $dateTx->copy()->addWeeks(240)->format('d-M-Y'),
            ]
        ];

        return [
            'filename' => $case->meta['kt_no'].'_follow_up_schedule.xlsx',
            'sheet' => $sheet,
        ];
    }
}
