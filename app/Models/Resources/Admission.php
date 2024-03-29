<?php

namespace App\Models\Resources;

use App\Traits\CKHashable;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* @property-read string $place_name */
class Admission extends Model
{
    use CKHashable;

    protected string $candidateKeyNameToHash = 'an';

    protected $casts = [
        'meta' => AsArrayObject::class,
        'encountered_at' => 'datetime',
        'dismissed_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo('App\Models\Resources\Ward', 'ward_id', 'id');
    }

    protected function an(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => app(Hashids::class)->decode($value)[0],
            set: fn ($value) => app(Hashids::class)->encode($value),
        );
    }

    protected function patientAgeAtEncounter(): Attribute
    {
        return Attribute::make(
            get: function () {
                $ageInMonths = $this->encountered_at->diffInMonths($this->patient->dob);
                if ($ageInMonths < 12) {
                    return $ageInMonths;
                }

                return $this->encountered_at->diffInYears($this->patient->dob);
            },
        );
    }

    protected function patientAgeAtEncounterUnit(): Attribute
    {
        return Attribute::make(
            get: function () {
                $ageInYears = $this->encountered_at->diffInYears($this->patient->dob);
                if ($ageInYears >= 1) {
                    return 'YO';
                }

                return 'MO';
            },
        );
    }

    protected function patientAgeAtEncounterText(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->patient_age_at_encounter.' '.$this->patient_age_at_encounter_unit,
        );
    }

    protected function encounteredAtForHumans(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->encountered_at
                ? $this->encountered_at->longRelativeToNowDiffForHumans()
                : null,
        );
    }

    protected function dismissedAtForHumans(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->dismissed_at
                ? $this->dismissed_at->longRelativeToNowDiffForHumans()
                : null,
        );
    }

    protected function lengthOfStay(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->encountered_at || ! $this->dismissed_at) {
                    return null;
                }

                $losInMinutes = $this->encountered_at->diffInMinutes($this->dismissed_at);
                // 1440 minutes in a day, more than 360 minutes count a day
                if ($losInMinutes >= 1440) {
                    $losInDays = (int) floor($losInMinutes / 1440);
                    $remains = $losInMinutes % 1440;
                    if ($remains > 360) {
                        $losInDays++;
                    }

                    return $losInDays;
                } elseif ($losInMinutes > 360) {
                    return 1;
                }

                return 0;
            },
        );
    }

    public function scopeWithPlaceName($query)
    {
        $query->addSelect([
            'place_name' => Ward::select('name')
                ->whereColumn('ward_id', 'wards.id')
                ->latest()
                ->limit(1),
        ]);
    }
}
