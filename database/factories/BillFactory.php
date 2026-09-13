<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillFactory extends Factory
{
    private static int $sequence = 1;

    public function definition(): array
    {
        return [
            'bill_number' => 'BILL-2026-' . str_pad((string) self::$sequence++, 6, '0', STR_PAD_LEFT),
            'customer_id' => Customer::factory(),
            'billing_period_start' => now()->startOfMonth(),
            'billing_period_end' => now()->endOfMonth(),
            'base_fee' => 300.00,
            'waste_fee' => 0,
            'additional_weight_fee' => 0,
            'other_charges' => 0,
            'discount' => 0,
            'tax' => 0,
            'total_amount' => 300.00,
            'amount_paid' => 0,
            'due_date' => now()->addDays(15),
            'status' => 'unpaid',
        ];
    }
}
