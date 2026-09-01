<?php

namespace App\Http\Requests\Admin;

use App\Models\Bill;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bill = Bill::find($this->input('bill_id'));
        $remaining = $bill ? $bill->total_amount - $bill->amount_paid : 0;

        return [
            'bill_id' => ['required', 'exists:bills,id'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $remaining],
            'method' => ['required', 'in:gcash,maya,bank_transfer,cash,gateway'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
