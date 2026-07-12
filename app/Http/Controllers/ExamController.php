<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function index(Request $request): View
    {
        $exams = Exam::query()
            ->with('school')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('term', 'like', "%{$search}%")
                    ->orWhere('session', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('results.exams.index', compact('exams'));
    }

    public function create(): View
    {
        $schools = School::orderBy('name')->get();

        return view('results.exams.create', compact('schools'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'term' => 'nullable|string|max:100',
            'session' => 'nullable|string|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['status'] = true;

        Exam::create($validated);

        return redirect()
            ->route('results.exams.index')
            ->with('success', 'Exam created successfully.');
    }

    public function edit(Exam $exam): View
    {
        $schools = School::orderBy('name')->get();

        return view('results.exams.edit', compact('exam', 'schools'));
    }

    public function update(Request $request, Exam $exam): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'term' => 'nullable|string|max:100',
            'session' => 'nullable|string|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|boolean',
        ]);

        $exam->update($validated);

        return redirect()
            ->route('results.exams.index')
            ->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam): RedirectResponse
    {
        $exam->delete();

        return redirect()
            ->route('results.exams.index')
            ->with('success', 'Exam deleted successfully.');
    }

    public function toggleStatus(Exam $exam): RedirectResponse
    {
        $exam->update([
            'status' => ! $exam->status,
        ]);

        $status = $exam->status ? 'activated' : 'deactivated';

        return redirect()
            ->route('results.exams.index')
            ->with('success', "Exam {$status} successfully.");
    }
}
