<?php

namespace App\Actions\Clinics\PostKT;

use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\User;
use App\Traits\AvatarLinkable;
use App\Traits\FirstNameAware;
use Illuminate\Support\Carbon;

class PrintCaseFolderLabel
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

        $dateTx = Carbon::create($case->meta['date_transplant']);

        $dateTxThai = $dateTx->locale('th')->translatedFormat('j F ') . $dateTx->year + 543;

        $flash['page-title'] = 'Print folder label ' . $case->title;
        $flash['main-menu-links'] = [];
        $flash['action-menu'] = [];

        $case->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'print_case_folder_label',
        ]);

        return [
            'flash' => $flash,
            'data' => [
                'hn' => $case->meta['hn'],
                'patient_name' => $case->meta['name'],
                'staff' => $case->form['nephrologist']
                    ? 'อ.'.$this->getFirstName($case->form['nephrologist'])
                    : null,
                'kt_no' => $case->meta['kt_no'],
                'date_kt' => $dateTxThai
            ]
        ];
    }
}
