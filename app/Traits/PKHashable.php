<?php

namespace App\Traits;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait PKHashable
{
    /**
     * Retrieve the model for a bound value.
     *
     * 🥲 cannot use route model binding when avatar mode is needed
     */
    public function scopeFindByUnhashKey($query, string $hashed)
    {
        return $query->where('id', app(Hashids::class)->decode($hashed)[0] ?? 0);
    }

    protected function hashedKey(): Attribute
    {
        return Attribute::make(
            get: fn () => app(Hashids::class)->encode($this->attributes['id']),
        )->shouldCache();
    }
}
