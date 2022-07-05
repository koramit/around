<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentChangeRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'changes' => AsArrayObject::class,
        'approved_at' => 'datetime',
        'disapproved_at' => 'datetime',
    ];

    protected $statuses = ['', 'pending', 'approved', 'disapproved', 'canceled', 'expired'];

    public function changeable()
    {
        return $this->morphTo();
    }

    public function requester()
    {
        return $this->belongsTo(User::class);
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->statuses[$this->attributes['status']] ?? null,
            set: fn ($value) => array_search($value, $this->statuses) ?? null,
        );
    }
}
