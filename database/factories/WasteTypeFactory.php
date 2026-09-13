<?php

namespace Database\Factories;

use App\Models\WasteType;
use Illuminate\Database\Eloquent\Factories\Factory;

class WasteTypeFactory extends Factory
{
    protected $model = WasteType::class;

    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->unique()->word()) . ' Waste',
            'slug' => $this->faker->unique()->slug(2),
            'description' => $this->faker->sentence(),
            'is_hazardous' => false,
            'status' => 'active',
        ];
    }
}
