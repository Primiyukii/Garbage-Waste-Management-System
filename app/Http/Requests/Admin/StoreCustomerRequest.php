<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rules\Password;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:20'],
            'barangay_id' => ['required', 'exists:barangays,id'],
            'street' => ['required', 'string', 'max:255'],
            'house_number' => ['required', 'string', 'max:50'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'service_id' => ['nullable', 'exists:services,id'],
        ];
    }
}
