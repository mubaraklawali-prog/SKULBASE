<?php

namespace App\Http\Requests;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $schoolId = $this->user()->school_id;

        return [
            'teacher_id' => ['required', 'exists:teachers,id'],
            'class_id' => ['required', 'exists:school_classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'instructions' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'max:10240'],
            'total_marks' => ['nullable', 'integer', 'min:1'],
            'status' => ['sometimes', 'in:draft,published'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $schoolId = $this->user()->school_id;

            if (! $this->teacher_id) {
                return;
            }

            $validTeacher = Teacher::where('id', $this->teacher_id)
                ->where('school_id', $schoolId)
                ->exists();

            if (! $validTeacher) {
                $validator->errors()->add('teacher_id', 'The selected teacher does not belong to this school.');
            }

            $validClass = SchoolClass::where('id', $this->class_id)
                ->where('school_id', $schoolId)
                ->exists();

            if (! $validClass) {
                $validator->errors()->add('class_id', 'The selected class does not belong to this school.');
            }

            $validSubject = Subject::where('id', $this->subject_id)
                ->where('school_id', $schoolId)
                ->exists();

            if (! $validSubject) {
                $validator->errors()->add('subject_id', 'The selected subject does not belong to this school.');
            }
        });
    }
}
