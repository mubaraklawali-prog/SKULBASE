<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Models\Assignment;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $schoolId = auth()->user()->school_id;
        $role = auth()->user()->role;
        $userId = auth()->user()->id;

        $query = Assignment::with(['teacher', 'schoolClass', 'subject'])
            ->where('school_id', $schoolId);

        if ($role === 'teacher') {
            $teacher = Teacher::where('school_id', $schoolId)
                ->where('email', auth()->user()->email)
                ->first();

            if ($teacher) {
                $query->where('teacher_id', $teacher->id);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assignments = $query->latest()->paginate(10)->withQueryString();

        $classes = SchoolClass::where('school_id', $schoolId)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $subjects = Subject::where('school_id', $schoolId)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view('assignments.index', compact(
            'assignments',
            'classes',
            'subjects'
        ));
    }

    public function create(): View
    {
        $schoolId = auth()->user()->role === 'teacher'
            ? auth()->user()->school_id
            : auth()->user()->school_id;

        $teachers = Teacher::where('school_id', $schoolId)
            ->where('status', true)
            ->orderBy('first_name')
            ->get();

        $classes = SchoolClass::where('school_id', $schoolId)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $subjects = Subject::where('school_id', $schoolId)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view('assignments.create', compact('teachers', 'classes', 'subjects'));
    }

    public function store(StoreAssignmentRequest $request): RedirectResponse
    {
        $schoolId = auth()->user()->school_id;

        $data = $request->validated();
        $data['school_id'] = $schoolId;
        $data['status'] = $data['status'] ?? 'draft';

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('assignments', 'public');
        }

        Assignment::create($data);

        return redirect()->route('assignments.index')
            ->with('success', 'Assignment created successfully.');
    }

    public function show(Assignment $assignment): View
    {
        $this->authorizeAssignment($assignment);

        $assignment->load(['teacher', 'schoolClass', 'subject', 'school']);

        return view('assignments.show', compact('assignment'));
    }

    public function edit(Assignment $assignment): View
    {
        $this->authorizeAssignment($assignment);

        $schoolId = auth()->user()->school_id;

        $teachers = Teacher::where('school_id', $schoolId)
            ->where('status', true)
            ->orderBy('first_name')
            ->get();

        $classes = SchoolClass::where('school_id', $schoolId)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $subjects = Subject::where('school_id', $schoolId)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view('assignments.edit', compact('assignment', 'teachers', 'classes', 'subjects'));
    }

    public function update(UpdateAssignmentRequest $request, Assignment $assignment): RedirectResponse
    {
        $this->authorizeAssignment($assignment);

        $data = $request->validated();

        if ($request->hasFile('attachment')) {
            if ($assignment->attachment) {
                Storage::disk('public')->delete($assignment->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('assignments', 'public');
        }

        $assignment->update($data);

        return redirect()->route('assignments.index')
            ->with('success', 'Assignment updated successfully.');
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        $this->authorizeAssignment($assignment);

        if ($assignment->attachment) {
            Storage::disk('public')->delete($assignment->attachment);
        }

        $assignment->delete();

        return redirect()->route('assignments.index')
            ->with('success', 'Assignment deleted successfully.');
    }

    protected function authorizeAssignment(Assignment $assignment): void
    {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            return;
        }

        abort_if($assignment->school_id !== $user->school_id, 403, 'Unauthorized access.');

        if ($user->role === 'teacher') {
            $teacher = Teacher::where('school_id', $user->school_id)
                ->where('email', $user->email)
                ->first();

            abort_if(! $teacher || $assignment->teacher_id !== $teacher->id, 403, 'You can only access your own assignments.');
        }
    }
}
