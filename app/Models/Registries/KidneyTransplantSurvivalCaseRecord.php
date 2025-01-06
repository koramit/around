<?php

namespace App\Models\Registries;

use App\Enums\KidneyTransplantSurvivalCaseStatus;
use App\Models\CaseRecord;
use App\Models\Resources\Registry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    protected function caseNo(): Attribute
    {
        return Attribute::make(
            get: function () {
                $form = $this->form;
                if (!$form['combined_with_liver'] && !$form['combined_with_heart'] && !$form['combined_with_pancreas']) {
                    return $this->meta['kt_no'];
                }

                $liver = $form['combined_with_liver'];
                $heart = $form['combined_with_heart'];
                $pancreas = $form['combined_with_pancreas'];

                if ($liver && !$heart && !$pancreas) {
                    return 'LK'.$this->meta['kt_no'];
                }

                if (!$liver && $heart && !$pancreas) {
                    return 'HK'.$this->meta['kt_no'];
                }

                if (!$liver && !$heart && $pancreas) {
                    return 'SPK'.$this->meta['kt_no'];
                }

                if ($liver && $heart && !$pancreas) {
                    return 'HLK'.$this->meta['kt_no'];
                }

                return '??'.$this->meta['kt_no'];
            }
        );
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
