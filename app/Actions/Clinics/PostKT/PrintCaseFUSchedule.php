<?php

namespace App\Actions\Clinics\PostKT;

use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\User;
use App\Traits\AvatarLinkable;
use Illuminate\Support\Carbon;

class PrintCaseFUSchedule
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

        $data = [
            'fus' => [
                $this->getDateLabel($dateTx->copy()->addWeek()),
                $this->getDateLabel($dateTx->copy()->addWeeks(2)),
                $this->getDateLabel($dateTx->copy()->addWeeks(4)),
                $this->getDateLabel($dateTx->copy()->addWeeks(8)),
                $this->getDateLabel($dateTx->copy()->addWeeks(12)),
                $this->getDateLabel($dateTx->copy()->addWeeks(16)),
                $this->getDateLabel($dateTx->copy()->addWeeks(20)),
                $this->getDateLabel($dateTx->copy()->addWeeks(24)),
                $this->getDateLabel($dateTx->copy()->addWeeks(36)),
                $this->getDateLabel($dateTx->copy()->addWeeks(48)),
                $this->getDateLabel($dateTx->copy()->addWeeks(96)),
                $this->getDateLabel($dateTx->copy()->addWeeks(144)),
                $this->getDateLabel($dateTx->copy()->addWeeks(192)),
                $this->getDateLabel($dateTx->copy()->addWeeks(240)),
            ]
        ];

        $data['recipient_label'] = 'HN ' . $case->meta['hn'] . ' ' . $case->meta['name'];
        $data['kt_id'] = $case->meta['recipient_id'];
        $dateTx = Carbon::create($case->meta['date_transplant']);
        $data['date_transplant'] = $dateTx->format('d') . ' ' . $dateTx->format('m') . ' ' . ($dateTx->format('Y') + 543) % 100;
        $data['medical_scheme'] = $case->form['medical_scheme'];

        $flash['page-title'] = 'Print FU schedule ' . $case->title;
        $flash['main-menu-links'] = [];
        $flash['action-menu'] = [];

        $case->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'print_case_fu_schedule',
        ]);

        return [
            'flash' => $flash,
            'data' => $data,
        ];
    }

    protected function getDateLabel(Carbon $date): string
    {
        return implode(' ', [
            $date->format('d'),
            $date->locale('th')->translatedFormat('M'),
            ($date->year + 543) % 100
        ]);
    }
}
