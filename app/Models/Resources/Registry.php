<?php

namespace App\Models\Resources;

use App\Models\ResourceActionLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Registry extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(Patient::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function actionLogs(): MorphMany
    {
        return $this->morphMany(ResourceActionLog::class, 'loggable');
    }
}
