<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $subjects = Subject::query()
            ->with('school')
            ->when($user->role !== 'super_admin' && $user->school_id, fn ($q) => $q->where('subjects.school_id', $user->school_id))
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('subjects.index', compact('subjects'));
    }

    public function show(Subject $subject): View
    {
        $subject->load('school', 'schoolClasses');

        return view('subjects.show', compact('subject'));
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

        return view('subjects.create', compact('schools', 'schoolClasses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'school_classes' => 'nullable|array',
            'school_classes.*' => 'exists:school_classes,id',
        ]);

        if ($user->role !== 'super_admin') {
            abort_if((int) $validated['school_id'] !== (int) $user->school_id, 403, 'Unauthorized access.');
            $validated['school_id'] = $user->school_id;
        }

        $validated['status'] = true;

        unset($validated['school_classes']);

        $subject = Subject::create($validated);

        if ($request->has('school_classes')) {
            $subject->schoolClasses()->sync($request->school_classes);
        }

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function edit(Subject $subject): View
    {
        $user = auth()->user();

        $schools = $user->role === 'super_admin'
            ? School::orderBy('name')->get()
            : School::where('id', $user->school_id)->get();

        $schoolClasses = $user->role === 'super_admin'
            ? SchoolClass::orderBy('name')->get()
            : SchoolClass::where('school_id', $user->school_id)->orderBy('name')->get();

        $assignedClassIds = $subject->schoolClasses->pluck('id')->toArray();

        return view('subjects.edit', compact('subject', 'schools', 'schoolClasses', 'assignedClassIds'));
    }

    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|boolean',
            'school_classes' => 'nullable|array',
            'school_classes.*' => 'exists:school_classes,id',
        ]);

        if ($user->role !== 'super_admin') {
            abort_if((int) $validated['school_id'] !== (int) $user->school_id, 403, 'Unauthorized access.');
            $validated['school_id'] = $user->school_id;
        }

        unset($validated['school_classes']);

        $subject->update($validated);

        $subject->schoolClasses()->sync($request->input('school_classes', []));

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        if ($subject->assignments()->exists()) {
            return back()->with('error', 'Cannot delete this subject because it has assigned tasks. Remove all assignments first.');
        }

        if ($subject->studentResults()->exists()) {
            return back()->with('error', 'Cannot delete this subject because it has student results. Remove all results first.');
        }

        $subject->schoolClasses()->detach();
        $subject->teachers()->detach();
        $subject->delete();

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }

    public function toggleStatus(Subject $subject): RedirectResponse
    {
        $subject->update([
            'status' => ! $subject->status,
        ]);

        $status = $subject->status ? 'activated' : 'deactivated';

        return redirect()
            ->route('subjects.index')
            ->with('success', "Subject {$status} successfully.");
    }
}
