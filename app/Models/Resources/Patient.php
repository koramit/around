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

    protected $casts = ['profile' => AsEncryptedArrayObject::class];

    protected function hn(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => app(Hashids::class)->decode($value)[0],
            set: fn ($value) => app(Hashids::class)->encode($value),
        );
    }
}
