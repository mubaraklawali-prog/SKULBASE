<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolClassController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $schoolId = $user->school_id;

        $schoolClasses = SchoolClass::query()
            ->with('school')
            ->withCount('students')
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('section', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('classes.index', compact('schoolClasses'));
    }

    public function create(): View
    {
        $user = auth()->user();

        $schools = $user->role === 'super_admin'
            ? School::orderBy('name')->get()
            : School::where('id', $user->school_id)->get();

        return view('classes.create', compact('schools'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($user->role !== 'super_admin') {
            abort_if((int) $validated['school_id'] !== (int) $user->school_id, 403, 'Unauthorized access.');
            $validated['school_id'] = $user->school_id;
        }

        $validated['status'] = true;

        SchoolClass::create($validated);

        return redirect()
            ->route('classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function show(SchoolClass $schoolClass): View
    {
        $this->authorizeSchool($schoolClass);

        $schoolClass->load([
            'school',
            'students.school',
        ])->loadCount('students');

        $students = $schoolClass->students()
            ->latest()
            ->paginate(10);

        return view('classes.show', compact(
            'schoolClass',
            'students'
        ));
    }

    public function edit(SchoolClass $schoolClass): View
    {
        $this->authorizeSchool($schoolClass);

        $user = auth()->user();

        $schools = $user->role === 'super_admin'
            ? School::orderBy('name')->get()
            : School::where('id', $user->school_id)->get();

        return view('classes.edit', compact('schoolClass', 'schools'));
    }

    public function update(Request $request, SchoolClass $schoolClass): RedirectResponse
    {
        $this->authorizeSchool($schoolClass);

        $user = auth()->user();

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|boolean',
        ]);

        if ($user->role !== 'super_admin') {
            abort_if((int) $validated['school_id'] !== (int) $user->school_id, 403, 'Unauthorized access.');
            $validated['school_id'] = $user->school_id;
        }

        $schoolClass->update($validated);

        return redirect()
            ->route('classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $schoolClass): RedirectResponse
    {
        $this->authorizeSchool($schoolClass);

        $hasData = $schoolClass->students()->exists()
            || $schoolClass->feeStructures()->exists()
            || $schoolClass->timetables()->exists()
            || $schoolClass->assignments()->exists()
            || $schoolClass->studentResults()->exists();

        if ($hasData) {
            return back()->with('error', 'Cannot delete this class because it still has associated records (students, fee structures, timetables, assignments, or results). Please remove all dependent records first.');
        }

        $schoolClass->subjects()->detach();
        $schoolClass->teachers()->detach();
        $schoolClass->delete();

        return redirect()
            ->route('classes.index')
            ->with('success', 'Class deleted successfully.');
    }

    public function toggleStatus(SchoolClass $schoolClass): RedirectResponse
    {
        $this->authorizeSchool($schoolClass);

        $schoolClass->update([
            'status' => ! $schoolClass->status,
        ]);

        $status = $schoolClass->status ? 'activated' : 'deactivated';

        return redirect()
            ->route('classes.index')
            ->with('success', "Class {$status} successfully.");
    }

    public function classesBySchool(int $schoolId): JsonResponse
    {
        $classes = SchoolClass::where('school_id', $schoolId)
            ->where('status', true)
            ->orderBy('name')
            ->select('id', 'name')
            ->get();

        return response()->json($classes);
    }

    protected function authorizeSchool(SchoolClass $schoolClass): void
    {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            return;
        }

        abort_if($schoolClass->school_id !== $user->school_id, 403, 'Unauthorized access.');
    }
}
