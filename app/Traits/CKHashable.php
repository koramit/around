<?php

namespace App\Traits;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait CKHashable
{
    public function scopeFindByHashedId($query, string $plain)
    {
        return $query->where($this->hashIdName, app(Hashids::class)->encode($plain));
    }

    protected function hashedId(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes[$this->hashIdName],
        );
    }
}
