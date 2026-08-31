<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
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
            'scheduled_date' => ['required', 'date'],
            'scheduled_time' => ['nullable', 'date_format:H:i'],
            'frequency' => ['required', 'in:daily,weekly,twice_weekly,monthly'],
            'status' => ['required', 'in:scheduled,assigned,in_progress,completed,missed,cancelled'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
