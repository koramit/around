<?php

namespace App\Models\Resources;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendingStaff extends Model
{
    use HasFactory;

    protected $table = 'attending_staffs';

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn () => 'อ.'.explode(' ', $this->attributes['name'])[1] ?? 'nameless',
        );
    }
}
