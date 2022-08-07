<?php

namespace App\Models;

use App\Traits\PKHashable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property-read string $hashed_key
 */
class Subscription extends Model
{
    use HasFactory, PKHashable;

    protected $guarded = [];

    public function subscribable(): MorphTo
    {
        return $this->morphTo('subscribable');
    }

    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subscription_user', 'subscription_id', 'subscriber_id')->withTimestamps();
    }
}
