<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdatePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $planId = $this->route('plan')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('plans', 'slug')->ignore($planId)],
            'description' => ['nullable', 'string', 'max:1000'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'yearly_price' => ['required', 'numeric', 'min:0'],
            'student_limit' => ['nullable', 'integer', 'min:1'],
            'is_unlimited' => ['sometimes', 'boolean'],
            'trial_days' => ['required', 'integer', 'min:0', 'max:365'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
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
