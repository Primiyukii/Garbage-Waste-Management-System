<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\Receipt;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function record(Bill $bill, array $data): Payment
    {
        return DB::transaction(function () use ($bill, $data) {
            $payment = Payment::create([
                'payment_reference' => $this->generateReference(),
                'bill_id' => $bill->id,
                'customer_id' => $bill->customer_id,
                'amount' => $data['amount'],
                'method' => $data['method'],
                'transaction_id' => $data['transaction_id'] ?? null,
                'status' => 'successful',
                'paid_at' => now(),
                'notes' => $data['notes'] ?? null,
            ]);

            $this->applyToBill($bill, $payment);
            $this->generateReceipt($payment);

            return $payment;
        });
    }

    private function applyToBill(Bill $bill, Payment $payment): void
    {
        $newAmountPaid = $bill->amount_paid + $payment->amount;

        $status = match (true) {
            $newAmountPaid >= $bill->total_amount => 'paid',
            $newAmountPaid > 0 => 'partially_paid',
            default => $bill->status,
        };

        $bill->update([
            'amount_paid' => $newAmountPaid,
            'status' => $status,
        ]);
    }

    private function generateReceipt(Payment $payment): Receipt
    {
        return Receipt::create([
            'payment_id' => $payment->id,
            'receipt_number' => $this->generateReceiptNumber(),
            'generated_at' => now(),
        ]);
    }

    private function generateReference(): string
    {
        $year = now()->year;
        $count = Payment::whereYear('created_at', $year)->count() + 1;

        return "PAY-{$year}-" . str_pad((string) $count, 6, '0', STR_PAD_LEFT);
    }

    private function generateReceiptNumber(): string
    {
        $year = now()->year;
        $count = Receipt::whereYear('created_at', $year)->count() + 1;

        return "RCPT-{$year}-" . str_pad((string) $count, 6, '0', STR_PAD_LEFT);
    }
}
