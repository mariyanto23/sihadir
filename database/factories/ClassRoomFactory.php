<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClassRoomFactory extends Factory
{
    public function definition(): array
    {
        $level = fake()->numberBetween(1, 6);

        return [
            'name' => $level.fake()->randomElement(['A', 'B', 'C']),
            'level' => $level,
        ];
    }
}
