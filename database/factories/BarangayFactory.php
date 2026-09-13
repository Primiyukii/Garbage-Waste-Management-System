<?php

namespace Database\Factories;

use App\Models\Barangay;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangayFactory extends Factory
{
    public function definition(): array
    {
        return [
            'city_id' => City::factory(),
            'name' => 'Barangay ' . $this->faker->unique()->numberBetween(1, 100),
        ];
    }
}
