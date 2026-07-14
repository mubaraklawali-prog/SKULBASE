<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdateTimetableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'period_id' => 'required|exists:periods,id',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->any()) {
                return;
            }

            $this->checkOwnership($validator);
            $this->checkClassConflict($validator);
            $this->checkTeacherConflict($validator);
            $this->checkPeriodActive($validator);
        });
    }

    protected function checkOwnership($validator): void
    {
        $schoolId = $this->school_id;

        $validClass = DB::table('school_classes')
            ->where('id', $this->class_id)
            ->where('school_id', $schoolId)
            ->exists();

        if (! $validClass) {
            $validator->errors()->add('class_id', 'The selected class does not belong to the chosen school.');
        }

        $validSection = DB::table('sections')
            ->where('id', $this->section_id)
            ->where('school_id', $schoolId)
            ->exists();

        if (! $validSection) {
            $validator->errors()->add('section_id', 'The selected section does not belong to the chosen school.');
        }

        $validSubject = DB::table('subjects')
            ->where('id', $this->subject_id)
            ->where('school_id', $schoolId)
            ->exists();

        if (! $validSubject) {
            $validator->errors()->add('subject_id', 'The selected subject does not belong to the chosen school.');
        }

        $validTeacher = DB::table('teachers')
            ->where('id', $this->teacher_id)
            ->where('school_id', $schoolId)
            ->exists();

        if (! $validTeacher) {
            $validator->errors()->add('teacher_id', 'The selected teacher does not belong to the chosen school.');
        }

        $validPeriod = DB::table('periods')
            ->where('id', $this->period_id)
            ->where('school_id', $schoolId)
            ->exists();

        if (! $validPeriod) {
            $validator->errors()->add('period_id', 'The selected period does not belong to the chosen school.');
        }
    }

    protected function checkClassConflict($validator): void
    {
        $timetableId = $this->route('timetable')->id;

        $exists = DB::table('timetables')
            ->where('class_id', $this->class_id)
            ->where('section_id', $this->section_id)
            ->where('day', $this->day)
            ->where('period_id', $this->period_id)
            ->where('id', '!=', $timetableId)
            ->exists();

        if ($exists) {
            $validator->errors()->add(
                'period_id',
                'This class already has a lesson scheduled for the selected day and period.'
            );
        }
    }

    protected function checkTeacherConflict($validator): void
    {
        $timetableId = $this->route('timetable')->id;

        $exists = DB::table('timetables')
            ->where('teacher_id', $this->teacher_id)
            ->where('day', $this->day)
            ->where('period_id', $this->period_id)
            ->where('id', '!=', $timetableId)
            ->exists();

        if ($exists) {
            $validator->errors()->add(
                'teacher_id',
                'This teacher is already assigned to another class during the selected day and period.'
            );
        }
    }

    protected function checkPeriodActive($validator): void
    {
        $period = DB::table('periods')
            ->where('id', $this->period_id)
            ->first();

        if ($period && ! $period->status) {
            $validator->errors()->add(
                'period_id',
                'The selected period is inactive and cannot be used.'
            );
        }
    }
}
