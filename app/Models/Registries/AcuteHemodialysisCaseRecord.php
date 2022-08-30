<?php

namespace App\Models\Registries;

use App\Casts\AcuteHemodialysisCaseRecordStatus;
use App\Models\CaseRecord;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Resources\Registry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/** @property string $discussion_route */
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

    /**
     * Override.
     */
    public function getCasts(): array
    {
        return array_merge(parent::getCasts(), ['status' => AcuteHemodialysisCaseRecordStatus::class]);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(AcuteHemodialysisOrderNote::class, 'case_record_id', 'id');
    }

    public function lastOrder(): HasOne
    {
        return $this->hasOne(AcuteHemodialysisOrderNote::class, 'case_record_id', 'id')->ofMany([
            'date_note' => 'max',
        ], function ($query) {
            $query->whereNotIn('status', [4, 7, 8]); // canceled, expired, disapproved
        });
    }

    public function genTitle(): string
    {
        return "Acute Hemodialysis Case : HN {$this->meta['hn']} {$this->meta['name']} : {$this->created_at->format('M j y')}";
    }

    /** @alias $discussion_route */
    protected function discussionRoute(): Attribute
    {
        return Attribute::make(
            get: fn () => route('procedures.acute-hemodialysis.edit', $this->hashed_key).'#discussion',
        );
    }

    public function scopeFilterStatus($query, $status)
    {
        // active, incomplete, empty, valid
        $statusCaster = new AcuteHemodialysisCaseRecordStatus();
        $statusCodes = match ($status ?? '') {
            'incomplete' => [
                $statusCaster->getCode('dismissed'),
                $statusCaster->getCode('discharged'),
            ],
            'empty' => [
                $statusCaster->getCode('canceled'),
                $statusCaster->getCode('expired'),
            ],
            'valid' => [
                $statusCaster->getCode('active'),
                $statusCaster->getCode('dismissed'),
                $statusCaster->getCode('discharged'),
                $statusCaster->getCode('completed'),
                $statusCaster->getCode('archived'),
            ],
            default => [$statusCaster->getCode('active')],
        };
        $query->whereIn('status', $statusCodes);
    }
}
