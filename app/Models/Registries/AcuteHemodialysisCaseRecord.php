<?php

namespace App\Models\Registries;

use App\Models\CaseRecord;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Resources\Registry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcuteHemodialysisCaseRecord extends CaseRecord
{
    protected $table = 'case_records';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($case) {
            $case->registry_id = cache()->rememberForever(
                'registry-id-acute_hd',
                fn () => Registry::query()->where('name', 'acute_hd')->first()->id
            );
        });

        static::addGlobalScope('acuteHemodialysisRegistryScope', function (Builder $builder) {
            $builder->where(
                'registry_id',
                cache()->rememberForever(
                    'registry-id-acute_hd',
                    fn () => Registry::query()->where('name', 'acute_hd')->first()->id
                )
            );
        });
    }

    public function orders(): HasMany
    {
        return $this->hasMany(AcuteHemodialysisOrderNote::class, 'case_record_id', 'id');
    }

    public function genTitle(): string
    {
        return "Acute Hemodialysis Case : HN {$this->meta['hn']} {$this->meta['name']} : {$this->created_at->format('M j y')}";
    }

    protected function discussionRoute(): Attribute
    {
        return Attribute::make(
            get: fn () => route('procedures.acute-hemodialysis.edit', $this->hashed_key).'#discussion',
        );
    }
}
