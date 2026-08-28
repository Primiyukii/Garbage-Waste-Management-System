<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'province_id' => ['required', 'exists:provinces,id'],
            'city_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cities', 'name')->where(fn ($query) => $query->where('province_id', $this->province_id)),
            ],
        ];
    }
}
