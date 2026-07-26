<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(Request $request): View
    {
        $plans = Plan::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->ordered()
            ->paginate(10)
            ->withQueryString();

        return view('plans.index', compact('plans'));
    }

    public function create(): View
    {
        return view('plans.create');
    }

    public function store(StorePlanRequest $request): RedirectResponse
    {
        $plan = Plan::create($request->validated());

        return redirect()
            ->route('plans.show', $plan)
            ->with('success', 'Plan created successfully.');
    }

    public function show(Plan $plan): View
    {
        return view('plans.show', compact('plan'));
    }

    public function edit(Plan $plan): View
    {
        return view('plans.edit', compact('plan'));
    }

    public function update(UpdatePlanRequest $request, Plan $plan): RedirectResponse
    {
        $plan->update($request->validated());

        return redirect()
            ->route('plans.show', $plan)
            ->with('success', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        if ($plan->hasSubscriptions()) {
            return back()->with('error', 'Cannot delete this plan because it has active subscriptions. Remove all subscriptions first.');
        }

        $plan->delete();

        return redirect()
            ->route('plans.index')
            ->with('success', 'Plan deleted successfully.');
    }

    public function toggleStatus(Plan $plan): RedirectResponse
    {
        $plan->update([
            'is_active' => ! $plan->is_active,
        ]);

        $status = $plan->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->route('plans.index')
            ->with('success', "Plan {$status} successfully.");
    }
}
