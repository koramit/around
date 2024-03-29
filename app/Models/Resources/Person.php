<?php

namespace App\Models\Resources;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read string $first_name
 * */
class Person extends Model
{
    use HasFactory;

    protected array $positions = [
        '',
        'physician',
        'resident 1',
        'resident 2',
        'resident 3',
        'resident 4',
        'fellow 1',
        'fellow 2',
        'staff',
    ];

    protected array $positionTitle = [
        '', // '',
        'พ.', // 'physician',
        'พ.', // 'resident 1',
        'พ.', // 'resident 2',
        'พ.', // 'resident 3',
        'พ.', // 'resident 4',
        'พ.', // 'fellow 1',
        'พ.', // 'fellow 2',
        'อ.', // 'staff',
    ];

    public function scopeFilter($query, $filters)
    {
        $iLike = config('database.iLike');

        $query->when($filters['position'] ?? null, function ($query, $position) {
            $query->where('position', $position);
        })->when($filters['division_id'] ?? null, function ($query, $division_id) {
            gettype($division_id) === 'array'
            ? $query->whereIn('division_id', $division_id)
            : $query->where('division_id', $division_id);
        })
            ->where('name', $iLike, '%'.$filters['search'].'%')
            ->where('active', true);
    }

    protected function position(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->positions[$value] ?? null,
            set: fn ($value) => array_search($value, $this->positions) ?? null,
        );
    }

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->positionTitle[$this->attributes['position']].explode(' ', $this->attributes['name'])[1] ?? 'nameless',
        );
    }
}
