<?php

namespace App\Actions\Clinics\PostKT;

use App\Models\Registries\KidneyTransplantSurvivalCaseRecord as CaseRecord;
use App\Models\Resources\Registry;
use App\Traits\AvatarLinkable;
use App\Traits\FlashDataGeneratable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CaseBaseAction
{
    use AvatarLinkable, FlashDataGeneratable;

    protected int $REGISTRY_ID;

    protected array $BREADCRUMBS;

    public function __construct()
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return;
        }

        $this->REGISTRY_ID = cache()->rememberForever(
            'registry-id-kt_survival',
            fn () => Registry::query()->where('name', 'kt_survival')->first()->id
        );

        $this->BREADCRUMBS = [
            ['label' => 'Home', 'route' => route('home')],
            ['label' => 'Clinics', 'route' => route('clinics.index')],
            ['label' => 'Post KT', 'route' => route('clinics.post-kt.index')],
        ];
    }

    protected function getCaseRecord(string $hashedKey): Model|Builder|CaseRecord
    {
        return CaseRecord::query()
            ->findByUnhashKey($hashedKey)
            ->firstOrFail();
    }
}
