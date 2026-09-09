<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

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
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()],
            'phone' => ['nullable', 'string', 'max:20'],
            'employee_id' => ['required', 'string', 'max:50', 'unique:collectors,employee_id'],
            'date_hired' => ['required', 'date'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'areas' => ['nullable', 'array'],
            'areas.*' => ['exists:barangays,id'],
        ];
    }
}
