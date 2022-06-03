<?php

namespace App\Traits;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

trait IdHashable
{
    public function scopeFindByHashId($query, string $plain)
    {
        return $query->where($this->hashIdName, app(Hashids::class)->encode($plain));
    }

    protected function hashId(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes[$this->hashIdName],
        );
    }

    /*
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    // public function resolveRouteBinding($value, $field = null)
    // {
    //     // mysql cs search
    //     return $this->where(DB::raw("BINARY '".$this->hashIdName."'"), $value)->firstOrFail();
    // }
}
