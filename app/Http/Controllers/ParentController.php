<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ParentController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $parents = ParentModel::query()
            ->with(['school', 'children'])
            ->when($user->role !== 'super_admin' && $user->school_id, fn ($q) => $q->where('parents.school_id', $user->school_id))
            ->when($request->search, function ($query, $search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('parents.index', compact('parents'));
    }

    public function create(): View
    {
        $user = auth()->user();

        $schools = $user->role === 'super_admin'
            ? School::orderBy('name')->get()
            : School::where('id', $user->school_id)->get();

        $students = $user->role === 'super_admin'
            ? Student::orderBy('first_name')->get()
            : Student::where('school_id', $user->school_id)->orderBy('first_name')->get();

        return view('parents.create', compact('schools', 'students'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
            'create_login_account' => 'nullable',
        ]);

        if ($user->role !== 'super_admin') {
            abort_if((int) $validated['school_id'] !== (int) $user->school_id, 403, 'Unauthorized access.');
            $validated['school_id'] = $user->school_id;
        }

        $existingParent = $this->findExistingParent(
            $validated['school_id'],
            $validated['email'] ?? null,
            $validated['phone'] ?? null
        );

        if ($existingParent) {
            if (! empty($validated['student_ids'])) {
                $existingParent->children()->syncWithoutDetaching($validated['student_ids']);
            }

            return redirect()
                ->route('parents.show', $existingParent)
                ->with('success', 'Parent already exists. New students have been linked to the existing parent account.');
        }

        $validated['status'] = true;

        $createAccount = $request->boolean('create_login_account');

        unset($validated['student_ids'], $validated['create_login_account']);

        $parent = ParentModel::create($validated);

        if (! empty($request->student_ids)) {
            $parent->children()->sync($request->student_ids);
        }

        if ($createAccount && $parent->email) {
            $password = Str::random(12);

            $parentUser = User::forceCreate([
                'name' => $parent->full_name,
                'email' => $parent->email,
                'password' => Hash::make($password),
                'force_password_change' => true,
            ]);

            $parentUser->forceFill([
                'role' => 'parent',
                'school_id' => $parent->school_id,
            ])->save();

            $parent->update(['user_id' => $parentUser->id]);

            return redirect()
                ->route('parents.credentials', $parent)
                ->with('credentials', [
                    'name' => $parent->email,
                    'password' => $password,
                ]);
        }

        return redirect()
            ->route('parents.index')
            ->with('success', 'Parent created successfully.');
    }

    public function show(ParentModel $parent): View
    {
        $parent->load('school', 'children.schoolClass');

        return view('parents.show', compact('parent'));
    }

    public function edit(ParentModel $parent): View
    {
        $user = auth()->user();

        $schools = $user->role === 'super_admin'
            ? School::orderBy('name')->get()
            : School::where('id', $user->school_id)->get();

        $students = $user->role === 'super_admin'
            ? Student::orderBy('first_name')->get()
            : Student::where('school_id', $parent->school_id)->orderBy('first_name')->get();

        $assignedStudentIds = $parent->children->pluck('id')->toArray();

        return view('parents.edit', compact('parent', 'schools', 'students', 'assignedStudentIds'));
    }

    public function update(Request $request, ParentModel $parent): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|boolean',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        if ($user->role !== 'super_admin') {
            abort_if((int) $validated['school_id'] !== (int) $user->school_id, 403, 'Unauthorized access.');
            $validated['school_id'] = $user->school_id;
        }

        unset($validated['student_ids']);

        $parent->update($validated);

        $parent->children()->sync($request->input('student_ids', []));

        return redirect()
            ->route('parents.index')
            ->with('success', 'Parent updated successfully.');
    }

    public function destroy(ParentModel $parent): RedirectResponse
    {
        if ($parent->user_id) {
            $parent->user()->delete();
        }

        $parent->children()->detach();
        $parent->delete();

        return redirect()
            ->route('parents.index')
            ->with('success', 'Parent deleted successfully.');
    }

    public function credentials(ParentModel $parent): View
    {
        $credentials = session('credentials');

        abort_unless($credentials, 404);

        return view('parents.create-credentials', [
            'parent' => $parent,
            'credentials' => $credentials,
        ]);
    }

    private function findExistingParent(int $schoolId, ?string $email, ?string $phone): ?ParentModel
    {
        if (! $email && ! $phone) {
            return null;
        }

        return ParentModel::where('school_id', $schoolId)
            ->where(function ($query) use ($email, $phone) {
                if ($email) {
                    $query->where('email', $email);
                }
                if ($phone) {
                    $query->orWhere('phone', $phone);
                }
            })
            ->first();
    }
}
