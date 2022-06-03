<?php

namespace App\Models\Resources;

use App\Traits\IdHashable;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory, IdHashable;

    protected $guarded = [];

    protected $hashIdName = 'an';

    protected $casts = [
        'meta' => AsArrayObject::class,
        'encountered_at' => 'datetime',
        'dismissed_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function place()
    {
        return $this->belongsTo('App\Models\Resources\Ward', 'ward_id', 'id');
    }

    protected function an(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => app(Hashids::class)->decode($value)[0],
            set: fn ($value) => app(Hashids::class)->encode($value),
        );
    }
}
