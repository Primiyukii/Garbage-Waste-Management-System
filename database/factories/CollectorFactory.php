<?php

namespace Database\Factories;

use App\Models\Collector;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollectorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->collector(),
            'employee_id' => 'EMP-' . str_pad((string) $this->faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'date_hired' => now(),
        ];
    }
}
