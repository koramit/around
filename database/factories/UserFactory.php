<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = rand(1, 100) > 75 ? 'male' : 'female';
        $firstName = fake()->firstName($gender);
        $lastname = fake()->lastName();

        return [
            'name' => strtolower($firstName).'.'.strtolower(substr($lastname, 0, 3)),
            'division_id' => 5,
            'full_name' => implode(' ', [fake()->title($gender), $firstName, $lastname]),
            'profile' => [
                'tel_no' => fake()->e164PhoneNumber(),
                'org_id' => fake()->numberBetween(10020001, 10049999),
                'division' => 'nephrology',
                'position' => 'staff',
                'pln' => null,
                'remark' => 'fake user',
            ],
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
        ];
    }
}
