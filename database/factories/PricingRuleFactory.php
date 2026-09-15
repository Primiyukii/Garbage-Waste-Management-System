<?php

namespace Database\Factories;

use App\Models\PricingRule;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class PricingRuleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'waste_type_id' => null,
            'rule_type' => 'additional_weight',
            'rate' => 10.00,
            'unit' => 'per_kg',
            'effective_from' => now()->subMonth(),
            'effective_to' => null,
            'status' => 'active',
        ];
    }
}
