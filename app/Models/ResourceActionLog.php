<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ResourceActionLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'payload' => AsArrayObject::class,
        'performed_at' => 'datetime',
    ];

    public $timestamps = false;

    protected array $actions = [
        '',
        'create',
        'update', // use before submitted
        'submit',
        'resubmit', // use after submitted
        'reschedule', // NOTE ONLY
        'view',
        'print',
        'cancel', // NOTE & REQUEST
        'expire', // NOTE & REQUEST
        'request_change',
        'approve', // NOTE & REQUEST
        'disapprove', // NOTE & REQUEST
        'start', // NOTE ONLY
        'finish', // NOTE ONLY
        'change', // changing by authority, no request required
        'dismiss', // CRF ONLY
        'archive', // CRF ONLY
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($log) {
            $log->performed_at = now();
        });
    }

    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWithActorUsername($query)
    {
        $query->addSelect([
            'actor_username' => User::select('name')
                ->whereColumn('id', 'resource_action_logs.actor_id')
                ->limit(1)
                ->latest(),
        ]);
    }

    protected function action(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->actions[$this->attributes['action']] ?? null,
            set: fn ($value) => array_search($value, $this->actions) ?? null,
        );
    }
}
