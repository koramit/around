<?php

namespace App\Models\Registries;

use App\Models\CaseRecord;
use App\Models\Notes\AcuteHemodialysisOrderNote;
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
            $case->registry_id = config('registries.acute_hd');
        });

        static::addGlobalScope('acuteHemodialysisRegistryScope', function (Builder $builder) {
            $builder->where('registry_id', config('registries.acute_hd'));
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
}
