<?php

namespace Tests\Feature\Billing;

use App\Models\CollectionRecord;
use App\Models\Customer;
use App\Models\PricingRule;
use App\Models\Service;
use App\Models\WasteType;
use App\Services\BillingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_bill_calculates_base_fee_with_no_excess_weight(): void
    {
        $service = Service::factory()->create([
            'base_price' => 300.00,
            'included_weight_kg' => 20.00,
        ]);
        $customer = Customer::factory()->create(['service_id' => $service->id]);

        $bill = app(BillingService::class)->generateForCustomer(
            $customer,
            now()->startOfMonth(),
            now()->endOfMonth()
        );

        $this->assertEquals(300.00, $bill->total_amount);
        $this->assertEquals('unpaid', $bill->status);
    }

    public function test_bill_pools_included_weight_across_multiple_collections_and_applies_specific_rule(): void
    {
        $service = Service::factory()->create([
            'base_price' => 300.00,
            'included_weight_kg' => 20.00,
        ]);
        $customer = Customer::factory()->create(['service_id' => $service->id]);
        $wasteType = WasteType::factory()->create(['name' => 'General Waste']);

        // Specific rule for this waste type — should win over any generic rule
        PricingRule::factory()->create([
            'service_id' => $service->id,
            'waste_type_id' => $wasteType->id,
            'rule_type' => 'additional_weight',
            'unit' => 'per_kg',
            'rate' => 10.00,
            'effective_from' => now()->subMonth(),
        ]);

        // A generic "any waste type" rule with a different rate, to prove precedence
        PricingRule::factory()->create([
            'service_id' => $service->id,
            'waste_type_id' => null,
            'rule_type' => 'additional_weight',
            'unit' => 'per_kg',
            'rate' => 99.00,
            'effective_from' => now()->subMonth(),
        ]);

        CollectionRecord::factory()->create([
            'customer_id' => $customer->id,
            'waste_type_id' => $wasteType->id,
            'weight_kg' => 25.5,
            'collection_date' => now()->startOfMonth()->addDays(5),
            'status' => 'completed',
        ]);

        CollectionRecord::factory()->create([
            'customer_id' => $customer->id,
            'waste_type_id' => $wasteType->id,
            'weight_kg' => 20.0,
            'collection_date' => now()->startOfMonth()->addDays(20),
            'status' => 'completed',
        ]);

        $bill = app(BillingService::class)->generateForCustomer(
            $customer,
            now()->startOfMonth(),
            now()->endOfMonth()
        );

        $this->assertEquals(555.00, $bill->total_amount);
        $this->assertEquals(300.00, $bill->base_fee);
        $this->assertEquals(255.00, $bill->additional_weight_fee);
    }

    public function test_bill_cannot_be_generated_twice_for_the_same_period(): void
    {
        $service = Service::factory()->create();
        $customer = Customer::factory()->create(['service_id' => $service->id]);

        $billingService = app(BillingService::class);
        $billingService->generateForCustomer($customer, now()->startOfMonth(), now()->endOfMonth());

        $this->expectException(\RuntimeException::class);
        $billingService->generateForCustomer($customer, now()->startOfMonth(), now()->endOfMonth());
    }

    public function test_bill_generation_fails_without_an_assigned_service(): void
    {
        $customer = Customer::factory()->create(['service_id' => null]);

        $this->expectException(\RuntimeException::class);
        app(BillingService::class)->generateForCustomer($customer, now()->startOfMonth(), now()->endOfMonth());
    }
}
