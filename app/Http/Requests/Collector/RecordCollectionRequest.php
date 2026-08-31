<?php

namespace App\Http\Requests\Collector;

use Illuminate\Foundation\Http\FormRequest;

class RecordCollectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'waste_type_id' => ['required', 'exists:waste_types,id'],
            'weight_kg' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
