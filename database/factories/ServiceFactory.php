<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Weekly Residential',
            'slug' => $this->faker->unique()->slug(2),
            'category' => 'residential',
            'frequency' => 'weekly',
            'base_price' => 300.00,
            'included_weight_kg' => 20.00,
            'status' => 'active',
        ];
    }
}
