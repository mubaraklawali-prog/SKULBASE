<?php

namespace App\Http\Controllers;

use App\Models\AssessmentType;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssessmentTypeController extends Controller
{
    private function schoolId(): ?int
    {
        $user = auth()->user();

        return $user->role === 'super_admin' ? null : $user->school_id;
    }

    public function index(Request $request): View
    {
        $schoolId = $this->schoolId();

        $assessmentTypes = AssessmentType::query()
            ->with('school')
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('results.assessment-types.index', compact('assessmentTypes'));
    }

    public function create(): View
    {
        $schoolId = $this->schoolId();
        $schools = School::when($schoolId, fn ($q) => $q->where('id', $schoolId))->orderBy('name')->get();

        return view('results.assessment-types.create', compact('schools'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0.01|max:100',
        ]);

        $validated['status'] = true;

        AssessmentType::create($validated);

        return redirect()
            ->route('results.assessment-types.index')
            ->with('success', 'Assessment type created successfully.');
    }

    public function edit(AssessmentType $assessmentType): View
    {
        $schoolId = $this->schoolId();
        $schools = School::when($schoolId, fn ($q) => $q->where('id', $schoolId))->orderBy('name')->get();

        return view('results.assessment-types.edit', compact('assessmentType', 'schools'));
    }

    public function update(Request $request, AssessmentType $assessmentType): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0.01|max:100',
            'status' => 'required|boolean',
        ]);

        $assessmentType->update($validated);

        return redirect()
            ->route('results.assessment-types.index')
            ->with('success', 'Assessment type updated successfully.');
    }

    public function destroy(AssessmentType $assessmentType): RedirectResponse
    {
        if ($assessmentType->studentResults()->exists()) {
            return back()->with('error', 'Cannot delete this assessment type because it is still used in student results. Remove all associated results first.');
        }

        $assessmentType->delete();

        return redirect()
            ->route('results.assessment-types.index')
            ->with('success', 'Assessment type deleted successfully.');
    }

    public function toggleStatus(AssessmentType $assessmentType): RedirectResponse
    {
        $assessmentType->update([
            'status' => ! $assessmentType->status,
        ]);

        $status = $assessmentType->status ? 'activated' : 'deactivated';

        return redirect()
            ->route('results.assessment-types.index')
            ->with('success', "Assessment type {$status} successfully.");
    }
}
