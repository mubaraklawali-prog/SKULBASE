<?php

namespace App\Http\Controllers\Traits;

use App\Models\Teacher;

trait ResolvesTeacher
{
    protected function resolveTeacher(): Teacher
    {
        $user = auth()->user();

        $teacher = Teacher::where('school_id', $user->school_id)
            ->where('email', $user->email)
            ->first();

        if (! $teacher) {
            abort(404, 'Teacher profile not found for this account.');
        }

        return $teacher;
    }
}
