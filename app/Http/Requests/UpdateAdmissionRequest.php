<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female'],
            'date_of_birth' => ['required', 'date'],
            'parent_name' => ['required', 'string', 'max:255'],
            'parent_phone' => ['required', 'string', 'max:20'],
            'parent_email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'class_id' => ['required', 'exists:school_classes,id'],
            'previous_school' => ['nullable', 'string', 'max:255'],
            'passport' => ['nullable', 'file', 'image', 'max:2048'],
            'status' => ['sometimes', 'in:pending,approved,rejected'],
        ];
    }
}
