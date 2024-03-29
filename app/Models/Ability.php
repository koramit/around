<?php

namespace App\Models;

use App\Models\Resources\Registry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ability extends Model
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function registry(): BelongsTo
    {
        return $this->belongsTo(Registry::class);
    }
}
