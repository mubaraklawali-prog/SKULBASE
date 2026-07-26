<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ResolvesStudent;
use App\Models\Assignment;
use Illuminate\View\View;

class StudentAssignmentController extends Controller
{
    use ResolvesStudent;

    public function index(): View
    {
        $student = $this->resolveStudent();

        $assignments = Assignment::where('class_id', $student->school_class_id)
            ->where('school_id', $student->school_id)
            ->with(['subject', 'teacher'])
            ->latest('due_date')
            ->latest('id')
            ->get();

        return view('student.assignments.index', compact(
            'student',
            'assignments',
        ));
    }

    public function show(Assignment $assignment): View
    {
        $student = $this->resolveStudent();

        if ($assignment->class_id !== $student->school_class_id) {
            abort(403, 'Unauthorized access to this assignment.');
        }

        $assignment->load(['subject', 'teacher', 'schoolClass']);

        return view('student.assignments.show', compact(
            'assignment',
            'student',
        ));
    }
}
