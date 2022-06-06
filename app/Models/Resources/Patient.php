<?php

namespace App\Models\Resources;

use App\Traits\IdHashable;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\Casts\AsEncryptedArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory, IdHashable;

    protected $hashIdName = 'hn';

    protected $casts = [
        'profile' => AsEncryptedArrayObject::class,
        'dob' => 'date',
    ];

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    protected function hn(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => app(Hashids::class)->decode($value)[0],
            set: fn ($value) => app(Hashids::class)->encode($value),
        );
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => implode(' ', [
                $this->profile['title'],
                $this->profile['first_name'],
                $this->profile['last_name'],
            ]),
        );
    }

    protected function gender(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? 'male' : 'female',
        );
    }
}
