<?php

namespace App\Models;

use App\Traits\PKHashable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property-read string $hashed_key
 * @property-read string $body_html
 * @property-read string $commentator_name
 */
class Comment extends Model
{
    use HasFactory, PKHashable;

    protected $guarded = [];

    public function commentator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo('commentable');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id'); // ->with('replies');
    }

    public function scopeWithCommentatorUsername($query)
    {
        $query->addSelect([
            'commentator_username' => User::select('name')
                ->whereColumn('id', 'comments.commentator_id')
                ->limit(1)
                ->latest(),
        ]);
    }

    public function scopeWithCommentatorName($query)
    {
        $query->addSelect([
            'commentator_name' => User::select('full_name')
                ->whereColumn('id', 'comments.commentator_id')
                ->limit(1)
                ->latest(),
        ]);
    }

    /** @alias $body_html */
    protected function bodyHtml(): Attribute
    {
        return Attribute::make(
            get: fn () => collect(explode("\n", $this->body))
                            ->transform(fn ($line) => "<p>$line</p>")
                            ->join(''),
        );
    }
}
