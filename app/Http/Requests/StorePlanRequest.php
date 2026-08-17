<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StorePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:plans,slug'],
            'description' => ['nullable', 'string', 'max:1000'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'yearly_price' => ['required', 'numeric', 'min:0'],
            'student_limit' => ['nullable', 'integer', 'min:1'],
            'is_unlimited' => ['sometimes', 'boolean'],
            'trial_days' => ['required', 'integer', 'min:0', 'max:365'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'discount_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'discount_start_date' => ['nullable', 'date', 'before_or_equal:discount_end_date'],
            'discount_end_date' => ['nullable', 'date', 'after_or_equal:discount_start_date'],
            'discount_scope' => ['nullable', 'in:monthly,annual,both'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->input('slug') && $this->input('name')) {
                $this->merge(['slug' => Str::slug($this->input('name'))]);
            }
        });
    }
}
