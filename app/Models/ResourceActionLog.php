<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ResourceActionLog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'payload' => AsArrayObject::class,
        'performed_at' => 'datetime',
    ];

    public $timestamps = false;

    protected array $actions = [
        '',
/**  1 */'create',
/**  2 */'update', // use before submitted
/**  3 */'submit',
/**  4 */'resubmit', // use after submitted
/**  5 */'reschedule', // NOTE ONLY
/**  6 */'view',
/**  7 */'print',
/**  8 */'cancel', // NOTE & REQUEST
/**  9 */'expire', // NOTE & REQUEST
/** 10 */'request_change',
/** 11 */'approve', // NOTE & REQUEST
/** 12 */'disapprove', // NOTE & REQUEST
/** 13 */'start', // NOTE ONLY
/** 14 */'finish', // NOTE ONLY
/** 15 */'change', // changing by authority, no request required
/** 16 */'dismiss', // CRF ONLY
/** 17 */'archive', // CRF ONLY
/** 18 */'grant', // role
/** 19 */'revoke', // role
/** 20 */'discharge', // case
/** 21 */'export', // case
/** 22 */'publish', // note
/** 23 */'delete', // case, note

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
