<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Modes\User
 *
 * @property-read string $first_name
 * @property-read string $home_page
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'profile' => AsArrayObject::class,
    ];

    /**
     * A user may be assigned many roles.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    protected function avatarToken(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->tokens()->where('name', 'avatar')->delete();

                return $this->createToken('avatar')->plainTextToken;
            },
        );
    }

    protected function homePage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->profile['home_page'] ?? 'home',
        );
    }

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: function () {
                $names = explode(' ', $this->profile['full_name']);

                return (count($names) > 2) ? $names[1] : $names[0];
            },
        );
    }

    protected function abilities(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->cacheAbilities("uid-$this->id-abilities", 'name'),
        );
    }

    protected function roleNames(): Attribute
    {
        return Attribute::make(
            get: fn () => cache()->remember("uid-$this->id-role-names", config('session.lifetime') * 60, function () {
                unset($this->roles);

                return $this->roles->pluck('name');
            }),
        );
    }

    /**
     * Assign a new role to the user.
     *
     * @param  mixed  $role
     */
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
