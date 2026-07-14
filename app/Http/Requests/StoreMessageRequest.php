<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'max:10240'],
        ];

        if ($this->input('recipient_type') === 'direct') {
            $rules['recipient_id'] = ['required', 'exists:users,id'];
        } else {
            $rules['recipient_role'] = ['required', 'in:teachers,students,parents'];
        }

        return $rules;
    }
}
