<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:services,name'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'in:residential,commercial'],
            'frequency' => ['required', 'in:daily,weekly,twice_weekly,monthly'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'included_weight_kg' => ['required', 'numeric', 'min:0'],
        ];
    }
}
