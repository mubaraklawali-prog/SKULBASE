<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentController extends Controller
{
    private function schoolId(): ?int
    {
        $user = auth()->user();

        return $user->role === 'super_admin' ? null : $user->school_id;
    }

    public function index(Request $request): View
    {
        $schoolId = $this->schoolId();

        $students = Student::query()
            ->with('school', 'schoolClass')
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
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

        $schoolClasses = SchoolClass::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();

        return view('students.index', compact('students', 'schoolClasses'));
    }

    public function create(): View
    {
        $user = auth()->user();

        $schools = $user->role === 'super_admin'
            ? School::orderBy('name')->get()
            : School::where('id', $user->school_id)->get();

        $schoolClasses = $user->role === 'super_admin'
            ? SchoolClass::orderBy('name')->get()
            : SchoolClass::where('school_id', $user->school_id)->orderBy('name')->get();

        $parents = $user->role === 'super_admin'
            ? ParentModel::orderBy('first_name')->get()
            : ParentModel::where('school_id', $user->school_id)->orderBy('first_name')->get();

        return view('students.create', compact('schools', 'schoolClasses', 'parents'));
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
            'existing_parent_id' => 'nullable|exists:parents,id',
            'new_parent_first_name' => 'nullable|string|max:255',
            'new_parent_last_name' => 'nullable|string|max:255',
            'new_parent_email' => 'nullable|email|max:255',
            'new_parent_phone' => 'nullable|string|max:20',
            'new_parent_address' => 'nullable|string|max:500',
            'create_parent_account' => 'nullable',
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

        $schoolId = $validated['school_id'];

        unset(
            $validated['existing_parent_id'],
            $validated['new_parent_first_name'],
            $validated['new_parent_last_name'],
            $validated['new_parent_email'],
            $validated['new_parent_phone'],
            $validated['new_parent_address'],
            $validated['create_parent_account']
        );

        $student = Student::create($validated);

        $parentId = null;

        if (! empty($request->existing_parent_id)) {
            $existingParent = ParentModel::where('id', $request->existing_parent_id)
                ->where('school_id', $schoolId)
                ->first();

            if ($existingParent) {
                $parentId = $existingParent->id;
            }
        } elseif (! empty($request->new_parent_first_name) && ! empty($request->new_parent_last_name)) {
            $parentEmail = $request->new_parent_email;
            $parentPhone = $request->new_parent_phone;

            $duplicateParent = null;
            if ($parentEmail || $parentPhone) {
                $duplicateParent = ParentModel::where('school_id', $schoolId)
                    ->where(function ($query) use ($parentEmail, $parentPhone) {
                        if ($parentEmail) {
                            $query->where('email', $parentEmail);
                        }
                        if ($parentPhone) {
                            $query->orWhere('phone', $parentPhone);
                        }
                    })
                    ->first();
            }

            if ($duplicateParent) {
                $parentId = $duplicateParent->id;
            } else {
                $parent = ParentModel::create([
                    'school_id' => $schoolId,
                    'first_name' => $request->new_parent_first_name,
                    'last_name' => $request->new_parent_last_name,
                    'email' => $parentEmail,
                    'phone' => $parentPhone,
                    'address' => $request->new_parent_address,
                    'status' => true,
                ]);

                $parentId = $parent->id;

                if ($request->boolean('create_parent_account') && $parent->email) {
                    $password = Str::random(12);

                    $parentUser = User::forceCreate([
                        'name' => $parent->full_name,
                        'email' => $parent->email,
                        'password' => Hash::make($password),
                        'force_password_change' => true,
                    ]);

                    $parentUser->forceFill([
                        'role' => 'parent',
                        'school_id' => $schoolId,
                    ])->save();

                    $parent->update(['user_id' => $parentUser->id]);

                    $student->parents()->attach($parentId);

                    return redirect()
                        ->route('parents.credentials', $parent)
                        ->with('credentials', [
                            'name' => $parent->email,
                            'password' => $password,
                        ]);
                }
            }
        }

        if ($parentId) {
            $student->parents()->attach($parentId);
        }

        return redirect()
            ->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    public function edit(Student $student): View
    {
        $schoolId = $this->schoolId();
        $schools = School::when($schoolId, fn ($q) => $q->where('id', $schoolId))->orderBy('name')->get();
        $schoolClasses = SchoolClass::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();

        return view('students.edit', compact('student', 'schools', 'schoolClasses'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'admission_number' => 'required|string|unique:students,admission_number,'.$student->id,
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
        $hasData = $student->attendances()->exists()
            || $student->feePayments()->exists()
            || $student->studentResults()->exists();

        if ($hasData) {
            return back()->with('error', 'Cannot delete this student because they still have associated records (attendance, payments, or results). Please remove all dependent records first.');
        }

        $student->parents()->detach();
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
