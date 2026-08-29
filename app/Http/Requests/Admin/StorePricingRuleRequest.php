<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePricingRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'waste_type_id' => ['nullable', 'exists:waste_types,id'],
            'rule_type' => ['required', 'in:additional_weight,surcharge,discount'],
            'rate' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'in:per_kg,flat,percentage'],
            'effective_from' => ['required', 'date'],
            'effective_to' => ['nullable', 'date', 'after_or_equal:effective_from'],
        ];
    }
}
