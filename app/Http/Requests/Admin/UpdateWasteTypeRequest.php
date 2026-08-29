<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWasteTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('waste_types', 'name')->ignore($this->route('waste_type'))],
            'description' => ['nullable', 'string'],
            'is_hazardous' => ['sometimes', 'boolean'],
        ];
    }
}
