<?php

namespace Database\Factories;

use App\Models\ClassRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nis' => fake()->unique()->numerify('########'),
            'name' => fake()->name(),
            'class_id' => ClassRoom::factory(),
            'birth_date' => fake()->dateTimeBetween('-12 years', '-6 years'),
            'gender' => fake()->randomElement(['L', 'P']),
        ];
    }
}
