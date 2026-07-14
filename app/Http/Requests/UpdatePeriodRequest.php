<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdatePeriodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:academic,break,lunch,assembly,other',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->any()) {
                return;
            }

            $this->checkOverlap($validator);
        });
    }

    protected function checkOverlap($validator): void
    {
        $schoolId = $this->school_id;
        $startTime = $this->start_time;
        $endTime = $this->end_time;
        $periodId = $this->route('period')->id;

        $overlaps = DB::table('periods')
            ->where('school_id', $schoolId)
            ->where('id', '!=', $periodId)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            })
            ->exists();

        if ($overlaps) {
            $validator->errors()->add(
                'start_time',
                'This time slot overlaps with an existing period for the selected school.'
            );
        }
    }
}
