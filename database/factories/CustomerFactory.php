<?php

namespace Database\Factories;

use App\Models\Barangay;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->customer(),
            'customer_code' => 'CUST-' . str_pad((string) $this->faker->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),
            'barangay_id' => Barangay::factory(),
            'street' => $this->faker->streetName(),
            'house_number' => (string) $this->faker->buildingNumber(),
            'postal_code' => $this->faker->postcode(),
            'registered_at' => now(),
        ];
    }
}
