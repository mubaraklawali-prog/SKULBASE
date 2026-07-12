<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(Request $request): View
    {
        $teachers = Teacher::query()
            ->with('school')
            ->when($request->search, function ($query, $search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        $schools = School::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $schoolClasses = SchoolClass::orderBy('name')->get();

        return view('teachers.create', compact('schools', 'subjects', 'schoolClasses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'qualification' => 'nullable|string|max:255',
            'employment_date' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'school_classes' => 'nullable|array',
            'school_classes.*' => 'exists:school_classes,id',
        ]);

        $validated['status'] = true;

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        unset($validated['subjects'], $validated['school_classes']);

        $teacher = Teacher::create($validated);

        if ($request->has('subjects')) {
            $teacher->subjects()->sync($request->subjects);
        }

        if ($request->has('school_classes')) {
            $teacher->schoolClasses()->sync($request->school_classes);
        }

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function show(Teacher $teacher): View
    {
        $teacher->load('school', 'subjects', 'schoolClasses');

        return view('teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher): View
    {
        $schools = School::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $schoolClasses = SchoolClass::orderBy('name')->get();
        $assignedSubjectIds = $teacher->subjects->pluck('id')->toArray();
        $assignedClassIds = $teacher->schoolClasses->pluck('id')->toArray();

        return view('teachers.edit', compact('teacher', 'schools', 'subjects', 'schoolClasses', 'assignedSubjectIds', 'assignedClassIds'));
    }

    public function update(Request $request, Teacher $teacher): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'qualification' => 'nullable|string|max:255',
            'employment_date' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|boolean',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'school_classes' => 'nullable|array',
            'school_classes.*' => 'exists:school_classes,id',
        ]);

        if ($request->hasFile('photo')) {
            if ($teacher->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }
            $validated['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        unset($validated['subjects'], $validated['school_classes']);

        $teacher->update($validated);

        $teacher->subjects()->sync($request->input('subjects', []));
        $teacher->schoolClasses()->sync($request->input('school_classes', []));

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->subjects()->detach();
        $teacher->schoolClasses()->detach();
        $teacher->delete();

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }

    public function toggleStatus(Teacher $teacher): RedirectResponse
    {
        $teacher->update([
            'status' => ! $teacher->status,
        ]);

        $status = $teacher->status ? 'activated' : 'deactivated';

        return redirect()
            ->route('teachers.index')
            ->with('success', "Teacher {$status} successfully.");
    }
}
