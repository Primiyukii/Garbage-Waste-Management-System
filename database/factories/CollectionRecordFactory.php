<?php

namespace Database\Factories;

use App\Models\CollectionRecord;
use App\Models\CollectionSchedule;
use App\Models\Collector;
use App\Models\Customer;
use App\Models\WasteType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollectionRecordFactory extends Factory
{
    public function definition(): array
    {
        return [
            'collection_schedule_id' => CollectionSchedule::factory(),
            'customer_id' => Customer::factory(),
            'collector_id' => Collector::factory(),
            'waste_type_id' => WasteType::factory(),
            'weight_kg' => $this->faker->randomFloat(2, 5, 30),
            'collection_date' => now(),
            'status' => 'completed',
            'recorded_at' => now(),
        ];
    }
}
