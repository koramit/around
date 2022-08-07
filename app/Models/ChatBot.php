<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsEncryptedArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ChatBot extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = ['configs' => AsEncryptedArrayObject::class];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function scopeMinUserCount($query, $socialProviderId)
    {
        return $query->where('social_provider_id', $socialProviderId)
            ->orderByDesc('user_count')
            ->limit(1);
    }
}
