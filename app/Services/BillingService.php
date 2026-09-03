<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\PricingRule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Notifications\BillGeneratedNotification;

class BillingService
{
    public function generateForCustomer(Customer $customer, Carbon $periodStart, Carbon $periodEnd): Bill
    {
        $customer->loadMissing('service');
        $service = $customer->service;

        if (! $service) {
            throw new \RuntimeException("{$customer->user->name} has no active service assigned.");
        }

        $duplicate = $customer->bills()
            ->whereDate('billing_period_start', $periodStart)
            ->whereDate('billing_period_end', $periodEnd)
            ->exists();

        if ($duplicate) {
            throw new \RuntimeException('A bill already exists for this exact billing period.');
        }

        $records = $customer->collectionRecords()
            ->where('status', 'completed')
            ->whereBetween('collection_date', [$periodStart, $periodEnd])
            ->with('wasteType')
            ->orderBy('collection_date')
            ->get();

        return DB::transaction(function () use ($customer, $service, $records, $periodStart, $periodEnd) {
            $items = [[
                'description' => "{$service->name} — Base Service Fee",
                'quantity' => 1,
                'unit_price' => $service->base_price,
                'amount' => $service->base_price,
            ]];

            $includedRemaining = (float) $service->included_weight_kg;
            $additionalWeightFee = 0;

            foreach ($records as $record) {
                $weight = (float) $record->weight_kg;
                $excess = max(0, $weight - $includedRemaining);
                $includedRemaining = max(0, $includedRemaining - $weight);

                if ($excess <= 0) {
                    continue;
                }

                $rule = $this->findRule($service->id, $record->waste_type_id, 'additional_weight', $record->collection_date);

                if (! $rule) {
                    continue;
                }

                $fee = $this->calculateRuleAmount($rule, $excess);
                $additionalWeightFee += $fee;

                $items[] = [
                    'description' => "Additional Weight — {$record->wasteType->name} ({$excess}kg on " . $record->collection_date->format('M d') . ')',
                    'quantity' => $excess,
                    'unit_price' => $rule->unit === 'per_kg' ? $rule->rate : 0,
                    'amount' => $fee,
                ];
            }

            $totalAmount = $service->base_price + $additionalWeightFee;

            $bill = Bill::create([
                'bill_number' => $this->generateBillNumber(),
                'customer_id' => $customer->id,
                'billing_period_start' => $periodStart,
                'billing_period_end' => $periodEnd,
                'base_fee' => $service->base_price,
                'waste_fee' => 0,
                'additional_weight_fee' => $additionalWeightFee,
                'other_charges' => 0,
                'discount' => 0,
                'tax' => 0,
                'total_amount' => $totalAmount,
                'amount_paid' => 0,
                'due_date' => $periodEnd->copy()->addDays(15),
                'status' => 'unpaid',
            ]);

            foreach ($items as $item) {
                $bill->items()->create($item);
            }

            return $bill;
        });
    }

    private function findRule(int $serviceId, ?int $wasteTypeId, string $ruleType, Carbon $date): ?PricingRule
    {
        return PricingRule::where('service_id', $serviceId)
            ->where('rule_type', $ruleType)
            ->where('status', 'active')
            ->where('effective_from', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('effective_to')->orWhere('effective_to', '>=', $date);
            })
            ->where(function ($q) use ($wasteTypeId) {
                $q->where('waste_type_id', $wasteTypeId)->orWhereNull('waste_type_id');
            })
            ->orderByRaw('waste_type_id IS NULL')
            ->first();
    }

    private function calculateRuleAmount(PricingRule $rule, float $baseValue): float
    {
        return match ($rule->unit) {
            'per_kg' => round($rule->rate * $baseValue, 2),
            'flat' => (float) $rule->rate,
            'percentage' => round(($rule->rate / 100) * $baseValue, 2),
            default => 0,
        };
    }

    private function generateBillNumber(): string
    {
        $year = now()->year;
        $count = Bill::whereYear('created_at', $year)->withTrashed()->count() + 1;

        return "BILL-{$year}-" . str_pad((string) $count, 6, '0', STR_PAD_LEFT);
    }
}
