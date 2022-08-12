<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventBasedNotification extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'locale' => AsArrayObject::class,
    ];
}
