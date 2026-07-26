<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'event_type' => ['required', 'in:academic,exam,holiday,meeting,sports,other'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after_or_equal:start_time'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'in:draft,published'],
        ];

        if ($this->user() && $this->user()->role === 'super_admin') {
            $rules['school_id'] = ['required', 'exists:schools,id'];
        }

        return $rules;
    }
}
