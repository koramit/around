<?php

namespace App\Models\Registries;

use App\Casts\KidneyTransplantAdmissionCaseRecordStatus;
use App\Models\CaseRecord;
use App\Models\Resources\Registry;
use Illuminate\Database\Eloquent\Builder;

/** @property-read string $hashed_key */
class KidneyTransplantAdmissionCaseRecord extends CaseRecord
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

    /**
     * Override.
     */
    public function getCasts(): array
    {
        return array_merge(parent::getCasts(), ['status' => KidneyTransplantAdmissionCaseRecordStatus::class]);
    }

    public function genTitle(): string
    {
        $caseType = strtoupper($this->meta['reason_for_admission']);

        return "HN {$this->meta['hn']} {$this->patient->full_name} : $caseType ADMISSION @ AN {$this->meta['an']}";
    }

    public function scopeFilterStatus($query, $status)
    {
        $caster = new KidneyTransplantAdmissionCaseRecordStatus();
        $statuses = $status && $status !== 'all'
            ? [$caster->getCode($status)]
            : [
                $caster->getCode('draft'),
                $caster->getCode('completed'),
                $caster->getCode('edited'),
                $caster->getCode('canceled'),
                $caster->getCode('offed'),
            ];

        $query->whereIn('status', $statuses);
    }

    public function scopeMetaSearchTerms($query, $search)
    {
        $iLike = config('database.iLike');
        $query->when($search ?? null, function ($query, $search) use ($iLike) {
            $query->where('meta->name', $iLike, $search.'%')
                ->orWhere('meta->hn', $iLike, $search.'%')
                ->orWhere('meta->an', $iLike, $search.'%');
        });
    }
}
