<?php

namespace App\Http\Controllers;

use App\Models\GradingSystem;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradingSystemController extends Controller
{
    private function schoolId(): ?int
    {
        $user = auth()->user();

        return $user->role === 'super_admin' ? null : $user->school_id;
    }

    public function index(Request $request): View
    {
        $schoolId = $this->schoolId();

        $gradingSystems = GradingSystem::query()
            ->with('school')
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->when($request->search, function ($query, $search) {
                $query->where('grade', 'like', "%{$search}%")
                    ->orWhere('remark', 'like', "%{$search}%");
            })
            ->orderBy('min_score', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('results.grading-systems.index', compact('gradingSystems'));
    }

    public function create(): View
    {
        $schoolId = $this->schoolId();
        $schools = School::when($schoolId, fn ($q) => $q->where('id', $schoolId))->orderBy('name')->get();

        return view('results.grading-systems.create', compact('schools'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'min_score' => 'required|numeric|min:0|max:100',
            'max_score' => 'required|numeric|min:0|max:100|gte:min_score',
            'grade' => 'required|string|max:10',
            'remark' => 'required|string|max:255',
            'grade_point' => 'nullable|numeric|min:0|max:5',
        ]);

        $hasOverlap = GradingSystem::where('school_id', $validated['school_id'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('min_score', [$validated['min_score'], $validated['max_score']])
                    ->orWhereBetween('max_score', [$validated['min_score'], $validated['max_score']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('min_score', '<=', $validated['min_score'])
                            ->where('max_score', '>=', $validated['max_score']);
                    });
            })
            ->exists();

        if ($hasOverlap) {
            return back()
                ->withErrors(['min_score' => 'The score range overlaps with an existing grading rule.'])
                ->withInput();
        }

        GradingSystem::create($validated);

        return redirect()
            ->route('results.grading-systems.index')
            ->with('success', 'Grading rule created successfully.');
    }

    public function edit(GradingSystem $gradingSystem): View
    {
        $schoolId = $this->schoolId();
        $schools = School::when($schoolId, fn ($q) => $q->where('id', $schoolId))->orderBy('name')->get();

        return view('results.grading-systems.edit', compact('gradingSystem', 'schools'));
    }

    public function update(Request $request, GradingSystem $gradingSystem): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'min_score' => 'required|numeric|min:0|max:100',
            'max_score' => 'required|numeric|min:0|max:100|gte:min_score',
            'grade' => 'required|string|max:10',
            'remark' => 'required|string|max:255',
            'grade_point' => 'nullable|numeric|min:0|max:5',
        ]);

        $hasOverlap = GradingSystem::where('school_id', $validated['school_id'])
            ->where('id', '!=', $gradingSystem->id)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('min_score', [$validated['min_score'], $validated['max_score']])
                    ->orWhereBetween('max_score', [$validated['min_score'], $validated['max_score']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('min_score', '<=', $validated['min_score'])
                            ->where('max_score', '>=', $validated['max_score']);
                    });
            })
            ->exists();

        if ($hasOverlap) {
            return back()
                ->withErrors(['min_score' => 'The score range overlaps with an existing grading rule.'])
                ->withInput();
        }

        $gradingSystem->update($validated);

        return redirect()
            ->route('results.grading-systems.index')
            ->with('success', 'Grading rule updated successfully.');
    }

    public function destroy(GradingSystem $gradingSystem): RedirectResponse
    {
        $gradingSystem->delete();

        return redirect()
            ->route('results.grading-systems.index')
            ->with('success', 'Grading rule deleted successfully.');
    }
}
