<?php

namespace App\Models\Resources;

use App\Models\CaseRecord;
use App\Models\Note;
use App\Traits\CKHashable;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\Casts\AsEncryptedArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * App\Models\Resources
 *
 * @property-read string $first_name
 * @property-read string $full_name
 * */
class Patient extends Model
{
    use HasFactory, CKHashable;

    protected string $hashIdName = 'hn';

    protected $casts = [
        'profile' => AsEncryptedArrayObject::class,
        'dob' => 'date',
    ];

    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class);
    }

    public function registries(): BelongsToMany
    {
        return $this->belongsToMany(Registry::class);
    }

    public function notes(): HasManyThrough
    {
        return $this->hasManyThrough(Note::class, CaseRecord::class);
    }

    protected function hn(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => app(Hashids::class)->decode($value)[0],
            set: fn ($value) => app(Hashids::class)->encode($value),
        )->shouldCache();
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => implode(' ', [
                $this->profile['title'],
                $this->profile['first_name'],
                $this->profile['last_name'],
            ]),
        )->shouldCache();
    }

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->profile['first_name'] ?? null,
        )->shouldCache();
    }

    protected function gender(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? 'male' : 'female',
        );
    }
}
