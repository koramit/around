<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceActionLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'payload' => AsArrayObject::class,
    ];

    protected $actions = [
        '',
        'create',
        'update', // use before submitted
        'submit',
        'resubmit', // use after submitted
        'view',
        'print',
        'cancel',
        'expire', // NOTE ONLY
        'request_change',
        'approve_change',
        'disapprove_change',
        'cancel_request',
        'expire_request',
        'perform', // NOTE ONLY
        'dismiss', // CRF ONLY
        'archive', // CRF ONLY
    ];

    public function loggable()
    {
        return $this->morphTo();
    }

    public function actor()
    {
        return $this->belongsTo(User::class);
    }

    protected function action(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->actions[$this->attributes['action']] ?? null,
            set: fn ($value) => array_search($value, $this->actions) ?? null,
        );
    }
}
