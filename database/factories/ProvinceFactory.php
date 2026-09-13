<?php

namespace Database\Factories;

use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProvinceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->state(),
        ];
    }
}
