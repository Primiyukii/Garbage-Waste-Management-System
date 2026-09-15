<?php

namespace Database\Factories;

use App\Models\Barangay;
use App\Models\CollectionSchedule;
use App\Models\Customer;
use App\Models\Service;
use App\Models\User;
use App\Models\WasteType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollectionScheduleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'collector_id' => null,
            'service_id' => Service::factory(),
            'waste_type_id' => WasteType::factory(),
            'barangay_id' => Barangay::factory(),
            'scheduled_date' => now(),
            'frequency' => 'weekly',
            'status' => 'scheduled',
            'created_by' => User::factory()->admin(),
        ];
    }
}
