<?php

namespace App\Traits;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait PKHashable
{
    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     * 🥲 cannot use route model binding when avatar mode is needed
     */
    // public function resolveRouteBinding($value, $field = null)
    // {
    //     return $this->where('id', app(Hashids::class)->decode($value)[0])->firstOrFail();
    // }
    public function scopeFindByUnhashKey($query, string $hashed)
    {
        return $query->where('id', app(Hashids::class)->decode($hashed)[0]);
    }

    protected function hashedKey(): Attribute
    {
        return Attribute::make(
            get: fn () => app(Hashids::class)->encode($this->attributes['id']),
        );
    }
}
