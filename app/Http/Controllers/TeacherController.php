<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $teachers = Teacher::query()
            ->with('school')
            ->when($user->role !== 'super_admin' && $user->school_id, fn ($q) => $q->where('teachers.school_id', $user->school_id))
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
        $user = auth()->user();

        $schools = $user->role === 'super_admin'
            ? School::orderBy('name')->get()
            : School::where('id', $user->school_id)->get();

        $subjects = $user->role === 'super_admin'
            ? Subject::orderBy('name')->get()
            : Subject::where('school_id', $user->school_id)->orderBy('name')->get();

        $schoolClasses = $user->role === 'super_admin'
            ? SchoolClass::orderBy('name')->get()
            : SchoolClass::where('school_id', $user->school_id)->orderBy('name')->get();

        return view('teachers.create', compact('schools', 'subjects', 'schoolClasses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

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
            'can_mark_attendance' => 'nullable',
            'create_login_account' => 'nullable',
        ]);

        if ($user->role !== 'super_admin') {
            abort_if((int) $validated['school_id'] !== (int) $user->school_id, 403, 'Unauthorized access.');
            $validated['school_id'] = $user->school_id;
        }

        $validated['status'] = true;
        $validated['can_mark_attendance'] = $request->boolean('can_mark_attendance');

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        $createAccount = $request->boolean('create_login_account');

        unset($validated['subjects'], $validated['school_classes'], $validated['create_login_account']);

        $teacher = Teacher::create($validated);

        if ($request->has('subjects')) {
            $teacher->subjects()->sync($request->subjects);
        }

        if ($request->has('school_classes')) {
            $teacher->schoolClasses()->sync($request->school_classes);
        }

        if ($createAccount && $teacher->email) {
            $password = Str::random(12);

            $teacherUser = User::forceCreate([
                'name' => $teacher->full_name,
                'email' => $teacher->email,
                'password' => Hash::make($password),
                'force_password_change' => true,
            ]);

            $teacherUser->forceFill([
                'role' => 'teacher',
                'school_id' => $teacher->school_id,
            ])->save();

            $teacher->update(['user_id' => $teacherUser->id]);

            return redirect()
                ->route('teachers.credentials', $teacher)
                ->with('credentials', [
                    'name' => $teacher->email,
                    'password' => $password,
                ]);
        }

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function credentials(Teacher $teacher): View
    {
        $credentials = session('credentials');

        abort_unless($credentials, 404);

        return view('teachers.create-credentials', [
            'teacher' => $teacher,
            'credentials' => $credentials,
        ]);
    }

    public function show(Teacher $teacher): View
    {
        $teacher->load('school', 'subjects', 'schoolClasses');

        return view('teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher): View
    {
        $user = auth()->user();

        $schools = $user->role === 'super_admin'
            ? School::orderBy('name')->get()
            : School::where('id', $user->school_id)->get();

        $subjects = $user->role === 'super_admin'
            ? Subject::orderBy('name')->get()
            : Subject::where('school_id', $user->school_id)->orderBy('name')->get();

        $schoolClasses = $user->role === 'super_admin'
            ? SchoolClass::orderBy('name')->get()
            : SchoolClass::where('school_id', $user->school_id)->orderBy('name')->get();

        $assignedSubjectIds = $teacher->subjects->pluck('id')->toArray();
        $assignedClassIds = $teacher->schoolClasses->pluck('id')->toArray();

        return view('teachers.edit', compact('teacher', 'schools', 'subjects', 'schoolClasses', 'assignedSubjectIds', 'assignedClassIds'));
    }

    public function update(Request $request, Teacher $teacher): RedirectResponse
    {
        $user = auth()->user();

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
            'can_mark_attendance' => 'nullable',
        ]);

        if ($user->role !== 'super_admin') {
            abort_if((int) $validated['school_id'] !== (int) $user->school_id, 403, 'Unauthorized access.');
            $validated['school_id'] = $user->school_id;
        }

        $validated['can_mark_attendance'] = $request->boolean('can_mark_attendance');

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
        if ($teacher->assignments()->exists()) {
            return back()->with('error', 'Cannot delete this teacher because they have assigned tasks. Remove all assignments first.');
        }

        if ($teacher->studentResults()->exists()) {
            return back()->with('error', 'Cannot delete this teacher because they have recorded student results. Remove all results first.');
        }

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
