<?php

namespace App\Models\Registries;

use App\Enums\KidneyTransplantSurvivalCaseStatus;
use App\Models\CaseRecord;
use App\Models\Resources\Registry;
use Illuminate\Database\Eloquent\Builder;

class KidneyTransplantSurvivalCaseRecord extends CaseRecord
{
    protected $table = 'case_records';

    protected static function booted(): void
    {
        static::creating(function ($case) {
            $case->registry_id = cache()->rememberForever(
                'registry-id-kt_survival',
                fn () => Registry::query()->where('name', 'kt_survival')->first()->id
            );
        });

        static::addGlobalScope('kidneyTransplantSurvivalRegistryScope', function (Builder $builder) {
            $builder->where(
                'registry_id',
                cache()->rememberForever(
                    'registry-id-kt_survival',
                    fn () => Registry::query()->where('name', 'kt_survival')->first()->id
                )
            );
        });
    }

    /**
     * Override.
     */
    public function getCasts(): array
    {
        return array_merge(parent::getCasts(), ['status' => KidneyTransplantSurvivalCaseStatus::class]);
    }

    public function genTitle(): string
    {
        return "HN {$this->meta['hn']} {$this->patient->full_name} : KT-NO {$this->meta['kt_no']} : Date-Tx {$this->meta['date_transplant']}";
    }

    public function scopeMetaSearchTerms($query, $search): void
    {
        $iLike = config('database.iLike');
        $query->when($search ?? null, function ($query, $search) use ($iLike) {
            $query->where('meta->name', $iLike, $search.'%')
                ->orWhere('meta->hn', $iLike, $search.'%')
                ->orWhere('meta->an', $iLike, $search.'%');
        });
    }
}
