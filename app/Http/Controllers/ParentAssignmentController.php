<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ResolvesParentChildren;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParentAssignmentController extends Controller
{
    use ResolvesParentChildren;

    public function index(Request $request): View
    {
        $children = $this->resolveParentChildren();
        $selectedStudentId = $request->student_id;
        $selectedStudent = $this->resolveSelectedChild($children, $selectedStudentId);

        $assignments = collect();

        if ($selectedStudent) {
            $assignments = Assignment::where('class_id', $selectedStudent->school_class_id)
                ->where('school_id', $selectedStudent->school_id)
                ->with(['subject', 'teacher'])
                ->latest('due_date')
                ->latest('id')
                ->get();
        }

        return view('parent.assignments.index', compact(
            'children',
            'selectedStudentId',
            'selectedStudent',
            'assignments',
        ));
    }

    public function show(Assignment $assignment): View
    {
        $children = $this->resolveParentChildren();
        $childClassIds = $children->pluck('school_class_id')->toArray();

        if (! in_array($assignment->class_id, $childClassIds)) {
            abort(403, 'Unauthorized access to this assignment.');
        }

        $assignment->load(['subject', 'teacher', 'schoolClass']);

        return view('parent.assignments.show', compact(
            'assignment',
            'children',
        ));
    }
}
