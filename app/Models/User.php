<?php

namespace App\Models;

use App\Models\Resources\Registry;
use App\Traits\FirstNameAware;
use App\Traits\PKHashable;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $items_per_page
 * @property string $home_page
 * @property Collection $role_names
 * @property Collection $role_labels
 * @property string $hashed_key
 * @property Collection $abilities
 * @property Collection $abilities_id
 * @property bool $auto_subscribe_to_channel
 * @property bool $mute_notification
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, FirstNameAware, PKHashable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'profile' => AsArrayObject::class,
        'preferences' => AsArrayObject::class,
    ];

    public function registries(): BelongsToMany
    {
        return $this->belongsToMany(Registry::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(Subscription::class, 'subscription_user', 'subscriber_id', 'subscription_id')->withTimestamps();
    }

    public function actionLogs(): MorphMany
    {
        return $this->morphMany(ResourceActionLog::class, 'loggable');
    }

    public function socialProfiles(): HasMany
    {
        return $this->hasMany(SocialProfile::class);
    }

    public function chatBots(): BelongsToMany
    {
        return $this->belongsToMany(ChatBot::class)->withTimestamps();
    }

    public function chatLogs(): HasMany
    {
        return $this->hasMany(ChatLog::class);
    }

    public function activeLINEProfile(): HasOne
    {
        /** @TODO make provider id dynamic */
        return $this->hasOne(SocialProfile::class)->ofMany([
            'updated_at' => 'max',
        ], function ($query) {
            $query->where('social_provider_id', 1)
                ->where('active', true);
        });
    }

    public function scopeWithActiveChatBots($query)
    {
        $query->with(['chatBots' => function ($q) {
            $q->wherePivot('active', true);
        }]);
    }

    /** @alias string $avatar_token*/
    protected function avatarToken(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->tokens()->where('name', 'avatar')->delete();

                return $this->createToken('avatar')->plainTextToken;
            },
        );
    }

    /** @alias string $home_page*/
    protected function homePage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->preferences['home_page'] ?? 'home',
            set: fn ($value) => $this->update(['preferences->home_page' => $value])
        );
    }

    /** @alias string $items_per_page*/
    protected function itemsPerPage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->preferences['items_per_page'] ?? 15,
            set: fn ($value) => $this->update(['preferences->items_per_page' => $value])
        );
    }

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstName($this->full_name),
        );
    }

    /** @alias $abilities */
    protected function abilities(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->cacheAbilities("uid-$this->id-abilities", 'name'),
        );
    }

    /** @alias $abilities_id */
    protected function abilitiesId(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->cacheAbilities("uid-$this->id-abilities-id", 'id'),
        );
    }

    /** @alias $role_names*/
    protected function roleNames(): Attribute
    {
        return Attribute::make(
            get: fn () => cache()->remember("uid-$this->id-role-names", config('session.lifetime') * 60, function () {
                return $this->roles()->pluck('name');
            }),
        );
    }

    /** @alias string $role_labels*/
    protected function roleLabels(): Attribute
    {
        return Attribute::make(
            get: fn () => cache()->remember("uid-$this->id-role-labels", config('session.lifetime') * 60, function () {
                return $this->roles()->whereNotNull('label')->pluck('label');
            }),
        );
    }

    /** @alias $auto_subscribe_to_channel */
    protected function autoSubscribeToChannel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->preferences['auto_subscribe_to_channel'] ?? false,
        );
    }

    /** @alias $mute_notification */
    protected function muteNotification(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->preferences['mute'] ?? false,
        );
    }

    public function assignRole(mixed $role): void
    {
        if (is_string($role)) {
            $role = Role::query()->where('name', $role)->firstOrCreate(['name' => $role]);
        }

        $this->roles()->syncWithoutDetaching($role);

        unset($this->roles); // reload for new role
        cache()->put("uid-$this->id-abilities", $this->roles->map->abilities->flatten()->pluck('name')->unique(), config('session.lifetime') * 60);
        cache()->put("uid-$this->id-role-names", $this->roles->pluck('name'), config('session.lifetime') * 60);
        cache()->put("uid-$this->id-role-labels", $this->roles->pluck('label'), config('session.lifetime') * 60);
    }

    public function hasAbility(string|int $ability): bool
    {
        $abilities = (gettype($ability) === 'integer')
            ? $this->cacheAbilities("uid-$this->id-abilities-id", 'id')
            : $this->abilities;

        return $abilities->contains($ability);
    }

    public function hasRole(string $name): bool
    {
        return $this->role_names->contains($name);
    }

    protected function cacheAbilities(string $key, string $field)
    {
        return cache()->remember($key, config('session.lifetime') * 60, function () use ($field) {
            unset($this->roles); // reload for new role

            // if unique() is not activated then the output is an array
            // but the output is an associated array so, provide
            // flatten() to guarantee output always an array
            return $this->roles->map->abilities->flatten()->pluck($field)->unique()->flatten();
        });
    }

    public function flushPrivileges()
    {
        cache()->forget("uid-$this->id-abilities");
        cache()->forget("uid-$this->id-role-names");
        cache()->forget("uid-$this->id-role-labels");
        cache()->forget("uid-$this->id-abilities-id");
    }

    public function subscribe(int $subscriptionId)
    {
        $this->subscriptions()->attach($subscriptionId);
    }

    public function unsubscribe(int $subscriptionId)
    {
        $this->subscriptions()->detach($subscriptionId);
    }

    public function activeLINEBot(SocialProfile $profile): ?ChatBot
    {
        return $this->chatBots()->where('social_provider_id', $profile->social_provider_id)
            ->wherePivot('active', true)
            ->first();
    }
}
