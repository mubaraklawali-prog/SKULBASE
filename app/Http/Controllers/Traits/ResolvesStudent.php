<?php

namespace App\Http\Controllers\Traits;

use App\Models\Student;

trait ResolvesStudent
{
    protected function resolveStudent(): Student
    {
        $user = auth()->user();

        $student = Student::where('school_id', $user->school_id)
            ->where('email', $user->email)
            ->first();

        if (! $student) {
            abort(404, 'Student profile not found for this account.');
        }

        return $student;
    }
}
