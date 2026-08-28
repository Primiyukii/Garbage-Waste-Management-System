<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCollectorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $collector = $this->route('collector');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($collector->user_id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'employee_id' => ['required', 'string', 'max:50', Rule::unique('collectors', 'employee_id')->ignore($collector->id)],
            'date_hired' => ['required', 'date'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'areas' => ['nullable', 'array'],
            'areas.*' => ['exists:barangays,id'],
        ];
    }
}
