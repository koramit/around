<?php

namespace App\Models\Registries;

use App\Models\Resources\Registry;
use Illuminate\Database\Eloquent\Builder;

class KidneyTransplantAdmissionCaseRecord extends \App\Models\CaseRecord
{
    protected $table = 'case_records';

    protected static function booted(): void
    {
        static::creating(function ($case) {
            $case->registry_id = cache()->rememberForever(
                'registry-id-kt_admission',
                fn () => Registry::query()->where('name', 'kt_admission')->first()->id
            );
        });

        static::addGlobalScope('kidneyTransplantAdmissionRegistryScope', function (Builder $builder) {
            $builder->where(
                'registry_id',
                cache()->rememberForever(
                    'registry-id-kt_admission',
                    fn () => Registry::query()->where('name', 'kt_admission')->first()->id
                )
            );
        });
    }
}
