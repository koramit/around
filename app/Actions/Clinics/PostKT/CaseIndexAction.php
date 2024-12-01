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
            ->when(! ($filters['scope'] ?? null),
                fn ($query)
                    => $query->where('status', KidneyTransplantSurvivalCaseStatus::ACTIVE)
            )->when(($filters['scope'] ?? null) && $filters['scope'] !== 'all',
                fn ($query)
                    => $query->where('status', KidneyTransplantSurvivalCaseStatus::fromLabel($filters['scope']))
            )->when($filters['mo'] ?? null,
                fn ($query) => $query->where('meta->month', match ($filters['mo']) {
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
                })
            )->paginate($user->items_per_page)
            ->withQueryString()
            ->through(function (KidneyTransplantSurvivalCaseRecord $case) {
                $dateTx = Carbon::create($case->meta['date_transplant']);
                $yearTh = Carbon::now()->year - $dateTx->year;

                return [
                    'kt_no' => $case->meta['kt_no'],
                    'date_transplant' => $dateTx->format('M d, Y'),
                    'patient' => 'HN '.$case->meta['hn'].' '.$case->meta['name'],
                    'status' => $case->status->label(),
                    'annual_cr' => (string) ($case->form["year_{$yearTh}_cr"] ?? null),
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
            'scopes' => ['all', 'active', 'graft loss', 'dead', 'loss f/u'],
            'month_options' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'can' => ['create' => true],
            'routes' => [
                'store' => route('clinics.post-kt.store'),
                'admissionsShow' => route('resources.api.admissions.show'),
            ],
            'filters' => [
                'search' => $filters['search'] ?? '',
                'scope' => $filters['scope'] ?? 'active',
                'mo' => $filters['mo'] ?? '',
            ],
        ];

        return [
            'flash' => $flash,
            'caseRecords' => $cases,
            'configs' => $configs,
        ];
    }
}
