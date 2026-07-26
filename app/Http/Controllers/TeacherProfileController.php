<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ResolvesTeacher;
use Illuminate\View\View;

class TeacherProfileController extends Controller
{
    use ResolvesTeacher;

    public function __invoke(): View
    {
        $teacher = $this->resolveTeacher();

        $teacher->load(['subjects', 'schoolClasses' => function ($query) {
            $query->withCount('students');
        }]);

        return view('teacher.profile', compact('teacher'));
    }
}
