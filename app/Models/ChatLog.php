<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'payload' => AsArrayObject::class,
    ];

    protected array $modes = ['', 'push', 'read', 'reply'];

    protected function mode(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->modes[$this->attributes['mode']] ?? null,
            set: fn ($value) => array_search($value, $this->modes) ?? 0,
        );
    }
}
