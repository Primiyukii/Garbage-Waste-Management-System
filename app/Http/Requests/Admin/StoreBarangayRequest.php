<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBarangayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'city_id' => ['required', 'exists:cities,id'],
            'barangay_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('barangays', 'name')->where(fn ($query) => $query->where('city_id', $this->city_id)),
            ],
        ];
    }
}
