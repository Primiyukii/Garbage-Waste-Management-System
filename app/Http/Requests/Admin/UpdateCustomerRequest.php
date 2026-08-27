<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customer = $this->route('customer');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($customer->user_id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'barangay_id' => ['required', 'exists:barangays,id'],
            'street' => ['required', 'string', 'max:255'],
            'house_number' => ['required', 'string', 'max:50'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'service_id' => ['nullable', 'exists:services,id'],
        ];
    }
}
