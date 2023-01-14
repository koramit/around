<?php

namespace App\Actions\Wards\KidneyTransplantAdmission;

use App\Models\Registries\KidneyTransplantAdmissionCaseRecord as CaseRecord;
use App\Models\Resources\Registry;
use App\Traits\AvatarLinkable;
use App\Traits\FlashDataGeneratable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class KidneyTransplantAdmissionAction
{
    use AvatarLinkable, FlashDataGeneratable;

    protected int $REGISTRY_ID;

    protected array $CONFIGS = [
        'admit_reasons' => [
            ['label' => 'Kidney Transplant', 'value' => 'kt'],
            ['label' => 'Complication', 'value' => 'complication'],
        ]
    ];

    public function __construct()
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return;
        }

        $this->REGISTRY_ID = cache()->rememberForever(
            'registry-id-kt_admission',
            fn () => Registry::query()->where('name', 'acute_hd')->first()->id
        );
    }

    protected function getCaseRecord(string $hashedKey): Model|Builder|CaseRecord
    {
        return CaseRecord::query()
            ->findByUnhashKey($hashedKey)
            ->firstOrFail();
    }
}
