<?php

namespace Database\Factories;

use App\Models\Role;
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
        return $this->state(fn () => Role::firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Administrator']
        )->only(['name', 'slug']));
    }

    public function customer(): static
    {
        return $this->state(fn () => Role::firstOrCreate(
            ['slug' => 'customer'],
            ['name' => 'Customer']
        )->only(['name', 'slug']));
    }

    public function collector(): static
    {
        return $this->state(fn () => Role::firstOrCreate(
            ['slug' => 'collector'],
            ['name' => 'Garbage Collector']
        )->only(['name', 'slug']));
    }
}
