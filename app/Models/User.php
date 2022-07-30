<?php

namespace App\Models;

use App\Traits\FirstNameAware;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $items_per_page
 * @property string $home_page
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, FirstNameAware;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'profile' => AsArrayObject::class,
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(Subscription::class, 'subscription_user', 'subscriber_id', 'subscription_id');
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
            get: fn () => $this->profile['home_page'] ?? 'home',
            set: fn ($value) => $this->update(['profile->home_page' => $value])
        );
    }

    /** @alias string $items_per_page*/
    protected function itemsPerPage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->profile['items_per_page'] ?? 15,
            set: fn ($value) => $this->update(['profile->items_per_page' => $value])
        );
    }

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstName($this->full_name),
        );
    }

    protected function abilities(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->cacheAbilities("uid-$this->id-abilities", 'name'),
        );
    }

    /** @alias string $role_names*/
    protected function roleNames(): Attribute
    {
        return Attribute::make(
            get: fn () => cache()->remember("uid-$this->id-role-names", config('session.lifetime') * 60, function () {
                unset($this->roles);

                return $this->roles->pluck('name');
            }),
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
}
