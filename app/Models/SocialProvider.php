<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsEncryptedArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialProvider extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = ['configs' => AsEncryptedArrayObject::class];

    public function users(): HasMany
    {
        return $this->hasMany(SocialProfile::class, 'social_provider_id', 'id');
    }
}
