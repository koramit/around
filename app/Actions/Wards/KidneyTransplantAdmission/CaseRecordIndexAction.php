<?php

namespace App\Actions\Wards\KidneyTransplantAdmission;

use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\KidneyTransplantAdmissionCaseRecord;
use App\Models\Resources\Admission;
use App\Models\User;
use App\Traits\FirstNameAware;
use App\Traits\HomePageSelectable;
use Hashids\Hashids;

class CaseRecordIndexAction extends KidneyTransplantAdmissionAction
{
    use HomePageSelectable, FirstNameAware;

    public function __invoke(array $filters, User|AvatarUser $user, $routeName = 'home')
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $ans = [];
        $cases = KidneyTransplantAdmissionCaseRecord::query()
            ->select(['id', 'patient_id', 'status', 'meta'])
            ->with(['patient', 'actionLogs' => fn ($q) => $q->where('action', 1)])
            ->filterStatus($filters['scope'] ?? null)
            ->metaSearchTerms($filters['search'] ?? null)
            ->orderBy('meta->an', 'desc')
            ->paginate($user->items_per_page)
            ->withQueryString()
            ->through(function ($case) use ($user, &$ans) {
                $ans[] = app(Hashids::class)->encode($case->meta['an']);

                return [
                    'an' => $case->meta['an'],
                    'patient' => explode(':', $case->title)[0],
                    'reason' => strtoupper($case->meta['reason_for_admission']),
                    'status' => $case->status,
                    'author' => $this->getFirstName($case->creator->full_name),
                    'actions' => $this->getActionMenu($case, $user, ['edit', 'destroy', 'cancel']),
                ];
            });

        $admissions = Admission::query()
            ->select(['an', 'encountered_at'])
            ->whereIn('an', $ans)
            ->pluck('encountered_at', 'an');

        $cases = $cases->through(function ($case) use ($admissions) {
            $case['admitted_at'] = $admissions[app(Hashids::class)->encode($case['an'])]->format('d M y');

            return $case;
        });

        $flash = $this->getFlash('Kidney Transplant Admission - Cases', $user);
        $flash['action-menu'][] = $this->getSetHomePageActionMenu($routeName, $user->home_page);

        return [
            'flash' => $flash,
            'caseRecords' => $cases,
            'filters' => [
                'search' => $filters['search'] ?? '',
                'scope' => $filters['scope'] ?? 'all',
            ],
            'configs' => [
                'scopes' => ['all', 'draft', 'completed', 'edited', 'canceled', 'deleted'],
                'admit_reasons' => $this->CONFIGS['admit_reasons'],
                'admissions' => $admissions,
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
