<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'collector_id' => ['nullable', 'exists:collectors,id'],
            'service_id' => ['required', 'exists:services,id'],
            'waste_type_id' => ['required', 'exists:waste_types,id'],
            'scheduled_date' => ['required', 'date', 'after_or_equal:today'],
            'scheduled_time' => ['nullable', 'date_format:H:i'],
            'frequency' => ['required', 'in:daily,weekly,twice_weekly,monthly'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
