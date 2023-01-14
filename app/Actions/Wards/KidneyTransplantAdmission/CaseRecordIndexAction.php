<?php

namespace App\Actions\Wards\KidneyTransplantAdmission;

use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\KidneyTransplantAdmissionCaseRecord;
use App\Models\User;
use App\Traits\HomePageSelectable;

class CaseRecordIndexAction extends KidneyTransplantAdmissionAction
{
    use HomePageSelectable;

    public function __invoke(array $filters, User|AvatarUser $user, $routeName = 'home')
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $cases = KidneyTransplantAdmissionCaseRecord::query()
            ->select(['id', 'patient_id', 'status', 'meta'])
            ->paginate($user->items_per_page)
            ->withQueryString()
            ->through(function ($case) use ($user) {
                return [
                    'title' => $case->title,
                    'route' => route('wards.kt-admission.edit', $case->hashed_key),
                ];
            });

        $flash = $this->getFlash('Kidney Transplant Admission - Cases', $user);
        $flash['action-menu'][] = $this->getSetHomePageActionMenu($routeName, $user->home_page);

        return [
            'flash' => $flash,
            'cases' => $cases,
            'filters' => [
                'search' => $filters['search'] ?? '',
                'scope' => $filters['scope'] ?? 'active',
            ],
            'configs' => [
                'scopes' => ['active', 'discharged', 'completed', 'all'],
                'admit_reasons' => $this->CONFIGS['admit_reasons'],
            ],
            'routes' => [
                'admissionsShow' => route('resources.api.admissions.show'),
                'store' => route('wards.kt-admission.store'),
            ],
            'can' => [
                'create' => $user->can('create_kt_admission_case'),
            ],
        ];
    }
}
{

}
