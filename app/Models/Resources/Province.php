<?php

namespace App\Models\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    public function hospitals(): HasMany
    {
        return $this->hasMany(Hospital::class);
    }
}
