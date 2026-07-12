<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $students = Student::query()
            ->with('school', 'schoolClass')
            ->when($request->search, function ($query, $search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('admission_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->class_id, function ($query, $classId) {
                $query->where('school_class_id', $classId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $schoolClasses = SchoolClass::orderBy('name')->get();

        return view('students.index', compact('students', 'schoolClasses'));
    }

    public function create(): View
    {
        $schools = School::orderBy('name')->get();
        $schoolClasses = SchoolClass::orderBy('name')->get();

        return view('students.create', compact('schools', 'schoolClasses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'admission_number' => 'required|string|unique:students,admission_number',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date|before:today',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'school_class_id' => 'nullable|exists:school_classes,id',
        ]);

        if (! empty($validated['school_class_id'])) {
            $class = SchoolClass::find($validated['school_class_id']);

            if ($class && $class->school_id != $validated['school_id']) {
                return back()
                    ->withErrors(['school_class_id' => 'The selected class does not belong to the chosen school.'])
                    ->withInput();
            }
        }

        $validated['status'] = 'active';

        Student::create($validated);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    public function edit(Student $student): View
    {
        $schools = School::orderBy('name')->get();
        $schoolClasses = SchoolClass::orderBy('name')->get();

        return view('students.edit', compact('student', 'schools', 'schoolClasses'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'admission_number' => 'required|string|unique:students,admission_number,' . $student->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date|before:today',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'school_class_id' => 'nullable|exists:school_classes,id',
            'status' => 'required|in:active,inactive',
        ]);

        if (! empty($validated['school_class_id'])) {
            $class = SchoolClass::find($validated['school_class_id']);

            if ($class && $class->school_id != $validated['school_id']) {
                return back()
                    ->withErrors(['school_class_id' => 'The selected class does not belong to the chosen school.'])
                    ->withInput();
            }
        }

        $student->update($validated);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
