<?php

namespace App\Models;

use App\Traits\PKHashable;
use Illuminate\Database\Eloquent\Casts\AsEncryptedArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property-read string $hashed_key
 */
class ChatBot extends Model
{
    use HasFactory, PKHashable;

    protected $guarded = [];

    protected $casts = ['configs' => AsEncryptedArrayObject::class];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(SocialProvider::class, 'social_provider_id', 'id');
    }

    public function scopeMinUserCountByProviderId($query, $socialProviderId)
    {
        return $query->where('social_provider_id', $socialProviderId)
            ->orderByDesc('user_count')
            ->limit(1);
    }
}
