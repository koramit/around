<?php

namespace App\Models\Registries;

use App\Models\CaseRecord;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use Illuminate\Database\Eloquent\Builder;

class AcuteHemodialysisCaseRecord extends CaseRecord
{
    protected $table = 'case_records';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($case) {
            $case->registry_id = config('registries.acute_hd');
        });

        static::addGlobalScope('acuteHemodialysisRegistryScope', function (Builder $builder) {
            $builder->where('registry_id', config('registries.acute_hd'));
        });
    }

    public function orders()
    {
        return $this->hasMany(AcuteHemodialysisOrderNote::class, 'case_record_id', 'id');
    }
}
