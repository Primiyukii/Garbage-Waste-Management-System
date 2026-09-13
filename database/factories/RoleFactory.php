<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->unique()->word()),
            'slug' => $this->faker->unique()->slug(1),
        ];
    }

    public function admin(): static
    {
        return $this->state(['name' => 'Administrator', 'slug' => 'admin']);
    }

    public function customer(): static
    {
        return $this->state(['name' => 'Customer', 'slug' => 'customer']);
    }

    public function collector(): static
    {
        return $this->state(['name' => 'Garbage Collector', 'slug' => 'collector']);
    }
}
