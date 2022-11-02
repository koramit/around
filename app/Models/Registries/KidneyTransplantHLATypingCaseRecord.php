<?php

namespace App\Models\Registries;

use App\Models\CaseRecord;
use App\Models\Resources\Registry;
use Illuminate\Database\Eloquent\Builder;

class KidneyTransplantHLATypingCaseRecord extends CaseRecord
{
    protected $table = 'case_records';

    protected static function booted(): void
    {
        static::creating(function ($case) {
            $case->registry_id = cache()->rememberForever(
                'registry-id-kt_hla_typing',
                fn () => Registry::query()->where('name', 'kt_hla_typing')->first()->id
            );
        });

        static::addGlobalScope('kidneyTransplantHLATypingRegistryScope', function (Builder $builder) {
            $builder->where(
                'registry_id',
                cache()->rememberForever(
                    'registry-id-kt_hla_typing',
                    fn () => Registry::query()->where('name', 'kt_hla_typing')->first()->id
                )
            );
        });
    }
}
