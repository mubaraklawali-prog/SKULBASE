<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'audience' => ['required', 'in:everyone,teachers,students,parents'],
            'attachment' => ['nullable', 'file', 'max:10240'],
            'status' => ['sometimes', 'in:draft,published'],
            'expires_at' => ['nullable', 'date'],
        ];
    }
}
