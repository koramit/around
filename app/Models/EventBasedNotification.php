<?php

namespace App\Models;

use App\Models\Resources\Registry;
use App\Traits\PKHashable;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class EventBasedNotification extends Model
{
    use HasFactory, PKHashable;

    protected $guarded = [];

    protected $casts = [
        'locale' => AsArrayObject::class,
    ];

    public function registry(): BelongsTo
    {
        return $this->belongsTo(Registry::class);
    }

    public function subscription(): MorphOne
    {
        return $this->morphOne(Subscription::class, 'subscribable');
    }

    public function scopeWithRegistryName($query)
    {
        $query->addSelect([
            'registry_name' => Registry::select('name')
                ->whereColumn('id', 'event_based_notifications.registry_id')
                ->limit(1)
                ->latest(),
        ]);
    }
}
