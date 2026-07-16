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
        $schoolClasses = SchoolClass::query()
            ->with('school')
            ->withCount('students')
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
        $schools = School::orderBy('name')->get();

        return view('classes.create', compact('schools'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['status'] = true;

        SchoolClass::create($validated);

        return redirect()
            ->route('classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function show(SchoolClass $schoolClass): View
    {
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
        $schools = School::orderBy('name')->get();

        return view('classes.edit', compact('schoolClass', 'schools'));
    }

    public function update(Request $request, SchoolClass $schoolClass): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|boolean',
        ]);

        $schoolClass->update($validated);

        return redirect()
            ->route('classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $schoolClass): RedirectResponse
    {
        $schoolClass->delete();

        return redirect()
            ->route('classes.index')
            ->with('success', 'Class deleted successfully.');
    }

    public function toggleStatus(SchoolClass $schoolClass): RedirectResponse
    {
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
}
