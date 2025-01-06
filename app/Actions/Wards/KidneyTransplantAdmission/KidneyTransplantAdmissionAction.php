<?php

namespace App\Actions\Wards\KidneyTransplantAdmission;

use App\Models\Registries\KidneyTransplantAdmissionCaseRecord as CaseRecord;
use App\Models\Resources\Registry;
use App\Models\User;
use App\Traits\AvatarLinkable;
use App\Traits\FlashDataGeneratable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class KidneyTransplantAdmissionAction
{
    use AvatarLinkable, FlashDataGeneratable;

    protected int $REGISTRY_ID;

    protected array $BREADCRUMBS;

    protected array $CONFIGS = [
        'admit_reasons' => [
            ['label' => 'Kidney Transplant', 'value' => 'kt'],
            ['label' => 'Complication', 'value' => 'complication'],
        ],
        'donor_types' => ['CD', 'CD Single kidney', 'CD Dual kidneys', 'LD'],
        'abo_options' => ['A', 'B', 'AB', 'O'],
        'rh_options' => ['positive', 'negative'],
        'hla_mismatch_options' => [0, 1, 2],
        'crossmatch_options' => ['positive', 'negative'],
        'male_recipient_is_options' => ['น้อง', 'ลูกผู้น้อง', 'พี่', 'ลูกผู้พี่', 'บุตร', 'สามี', 'บิดา', 'หลาน', 'น้า', 'อา', 'ลุง'],
        'female_recipient_is_options' => ['น้อง', 'ลูกผู้น้อง', 'พี่', 'ลูกผู้พี่', 'บุตร', 'ภรรยา', 'มารดา',  'หลาน', 'ป้า', 'น้า', 'อา'],
        'donor_is_options' => [
            'น้อง' => ['พี่', 'ฝาแฝด'],
            'ลูกผู้น้อง' => ['ลูกผู้พี่'],
            'พี่' => ['น้อง', 'ฝาแฝด'],
            'ลูกผู้พี่' => ['ลูกผู้น้อง'],
            'บุตร' => ['บิดา', 'มารดา'],
            'ภรรยา' => ['สามี'],
            'สามี' => ['ภรรยา'],
            'มารดา' => ['บุตร'],
            'บิดา' => ['บุตร'],
            'หลาน' => ['ป้า', 'น้า', 'อา', 'ลุง'],
            'ป้า' => ['หลาน'],
            'น้า' => ['หลาน'],
            'อา' => ['หลาน'],
            'ลุง' => ['หลาน'],
        ],
        'smoking_options' => ['smoker', 'ex-smoker', 'never'],
        'smoking_types' => ['smoker', 'ex-smoker'],
        'graft_function_options' => ['immediate graft function', 'slow graft function', 'delayed graft function', 'primary non-function'],
        'dialysis_mode_options' => ['HD', 'PD'],
        'attachment_upload_pathname' => 'w/k/a',
    ];

    public function __construct()
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return;
        }

        $this->REGISTRY_ID = cache()->rememberForever(
            'registry-id-kt_admission',
            fn () => Registry::query()->where('name', 'kt_admission')->first()->id
        );

        $this->BREADCRUMBS = [
            ['label' => 'Home', 'route' => route('home')],
            ['label' => 'Wards', 'route' => route('wards.index')],
            ['label' => 'KT Admission', 'route' => route('wards.kt-admission.index')],
        ];
    }

    protected function getCaseRecord(string $hashedKey): Model|Builder|CaseRecord
    {
        return CaseRecord::query()
            ->findByUnhashKey($hashedKey)
            ->firstOrFail();
    }

    protected function getActionMenu(CaseRecord $caseRecord, User $user, array $actions = []): array
    {
        return collect([
            [
                'label' => 'Edit',
                'as' => 'link',
                'icon' => 'edit',
                'theme' => 'accent',
                'route' => route('wards.kt-admission.edit', $caseRecord->hashed_key),
                'can' => ($user->can('edit', $caseRecord) || $user->can('addendum', $caseRecord))
                    && (! count($actions) || in_array('edit', $actions)),
            ],
            [
                'label' => 'Complete',
                'as' => 'button',
                'icon' => 'box-archive',
                'name' => 'complete-case',
                'route' => route('wards.kt-admission.complete', $caseRecord->hashed_key),
                'can' => $user->can('update', $caseRecord)
                    && (! count($actions) || in_array('complete', $actions)),
            ],
            [
                'label' => 'Delete',
                'as' => 'button',
                'icon' => 'trash',
                'theme' => 'danger',
                'name' => 'destroy-case',
                'route' => route('wards.kt-admission.destroy', $caseRecord->hashed_key),
                'config' => [
                    'heading' => 'Delete Case',
                    'confirmText' => $caseRecord->title,
                    'requireReason' => false,
                ],
                'can' => $user->can('update', $caseRecord)
                    && (! count($actions) || in_array('destroy', $actions)),
            ],
            [
                'label' => 'Addendum',
                'as' => 'button',
                'icon' => 'edit',
                'name' => 'addendum-case',
                'route' => route('wards.kt-admission.addendum', $caseRecord->hashed_key),
                'can' => $user->can('addendum', $caseRecord)
                    && (! count($actions) || in_array('addendum', $actions)),
            ],
            [
                'label' => 'Cancel',
                'as' => 'button',
                'icon' => 'trash-x-mark',
                'theme' => 'warning',
                'route' => route('wards.kt-admission.cancel', $caseRecord->hashed_key),
                'name' => 'cancel-case',
                'config' => [
                    'heading' => 'Cancel Case',
                    'confirmText' => $caseRecord->title,
                    'requireReason' => true,
                ],
                'can' => $user->can('cancel', $caseRecord)
                    && (! count($actions) || in_array('cancel', $actions)),
            ],
            [
                'label' => 'Off case',
                'as' => 'button',
                'icon' => 'trash-x-mark',
                'theme' => 'warning',
                'route' => route('wards.kt-admission.off', $caseRecord->hashed_key),
                'name' => 'off-case',
                'config' => [
                    'heading' => 'Off Case',
                    'confirmText' => $caseRecord->title,
                    'requireReason' => true,
                ],
                'can' => $user->can('off', $caseRecord)
                    && (! count($actions) || in_array('off', $actions)),
            ],
        ])->filter(fn ($action) => $action['can'])->values()->all();
    }
}
