<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCollectorRequest extends FormRequest
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
            'employee_id' => ['required', 'string', 'max:50', 'unique:collectors,employee_id'],
            'date_hired' => ['required', 'date'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'areas' => ['nullable', 'array'],
            'areas.*' => ['exists:barangays,id'],
        ];
    }
}
