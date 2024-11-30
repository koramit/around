<?php

namespace App\Actions\Clinics\PostKT;

use App\Enums\KidneyTransplantSurvivalCaseStatus;
use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\User;
use App\Traits\AvatarLinkable;
use App\Traits\FlashDataGeneratable;
use App\Traits\HomePageSelectable;
use Carbon\Carbon;

class CaseIndexAction
{
    use AvatarLinkable, FlashDataGeneratable, HomePageSelectable;

    public function __invoke(array $filters, User|AvatarUser $user, string $routeName = 'home')
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        $cases = KidneyTransplantSurvivalCaseRecord::query()
            ->with(['patient'])
            ->orderBy('meta->kt_no')
            ->where('status', '!=', KidneyTransplantSurvivalCaseStatus::DELETED)
            ->paginate($user->items_per_page)
            ->withQueryString()
            ->through(function (KidneyTransplantSurvivalCaseRecord $case) {
                $dateTx = Carbon::create($case->meta['date_transplant']);
                $yearTh = Carbon::now()->year - $dateTx->year;

                return [
                    'kt_no' => $case->meta['kt_no'],
                    'date_transplant' => $dateTx->format('M d, Y'),
                    'patient' => 'HN '.$case->meta['hn'].' '.$case->meta['name'],
                    'status' => $case->status->label(),
                    'annual_cr' => $case->form["year_{$yearTh}_cr"] ?? null,
                    'last_update' => Carbon::create($case->form['date_last_update'])->format('M d, Y'),
                    'actions' => [
                        [
                            'label' => 'Edit',
                            'as' => 'link',
                            'icon' => 'edit',
                            'theme' => 'accent',
                            'route' => route('clinics.post-kt.edit', $case->hashed_key),
                            'can' => true, // @TODO set policy
                        ],
                    ],
                ];
            });

        $flash = $this->getFlash('Kidney Transplant Survival - Cases', $user);
        $flash['action-menu'][] = $this->getSetHomePageActionMenu($routeName, $user->home_page);
        $configs = [
            'can' => ['create' => true],
            'routes' => [
                'store' => route('clinics.post-kt.store'),
                'admissionsShow' => route('resources.api.admissions.show'),
            ],
        ];

        return [
            'flash' => $flash,
            'caseRecords' => $cases,
            'configs' => $configs,
        ];
    }
}
