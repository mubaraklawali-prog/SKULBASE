<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeriodRequest;
use App\Http\Requests\UpdatePeriodRequest;
use App\Models\Period;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeriodController extends Controller
{
    public function index(Request $request): View
    {
        $periods = Period::query()
            ->with('school')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            })
            ->when($request->school_id, function ($query, $schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->orderBy('sort_order')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $schools = School::orderBy('name')->get();

        return view('periods.index', compact('periods', 'schools'));
    }

    public function show(Period $period): View
    {
        $period->load('school');

        return view('periods.show', compact('period'));
    }

    public function create(): View
    {
        $schools = School::orderBy('name')->get();

        return view('periods.create', compact('schools'));
    }

    public function store(StorePeriodRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated['duration_minutes'] = $this->calculateDuration(
            $validated['start_time'],
            $validated['end_time']
        );

        if (! isset($validated['sort_order'])) {
            $validated['sort_order'] = Period::where('school_id', $validated['school_id'])->max('sort_order') + 1;
        }

        $validated['status'] = true;

        Period::create($validated);

        return redirect()
            ->route('periods.index')
            ->with('success', 'Period created successfully.');
    }

    public function edit(Period $period): View
    {
        $schools = School::orderBy('name')->get();

        return view('periods.edit', compact('period', 'schools'));
    }

    public function update(UpdatePeriodRequest $request, Period $period): RedirectResponse
    {
        $validated = $request->validated();

        $validated['duration_minutes'] = $this->calculateDuration(
            $validated['start_time'],
            $validated['end_time']
        );

        $period->update($validated);

        return redirect()
            ->route('periods.index')
            ->with('success', 'Period updated successfully.');
    }

    public function destroy(Period $period): RedirectResponse
    {
        $period->delete();

        return redirect()
            ->route('periods.index')
            ->with('success', 'Period deleted successfully.');
    }

    public function toggleStatus(Period $period): RedirectResponse
    {
        $period->update([
            'status' => ! $period->status,
        ]);

        $status = $period->status ? 'activated' : 'deactivated';

        return redirect()
            ->route('periods.index')
            ->with('success', "Period {$status} successfully.");
    }

    protected function calculateDuration(string $startTime, string $endTime): int
    {
        $start = new \DateTime($startTime);
        $end = new \DateTime($endTime);

        return (int) $start->diff($end)->i + ($start->diff($end)->h * 60);
    }
}
