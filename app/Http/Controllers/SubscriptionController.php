<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\School;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Subscription::with('school', 'plan')
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->plan_id, function ($query, $planId) {
                $query->where('plan_id', $planId);
            })
            ->when($request->school_id, function ($query, $schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->when($request->billing_cycle, function ($query, $cycle) {
                $query->where('billing_cycle', $cycle);
            })
            ->when($request->is_trial, function ($query, $isTrial) {
                $query->where('is_trial', $isTrial === '1');
            })
            ->when($request->search, function ($query, $search) {
                $query->whereHas('school', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $plans = Plan::orderBy('name')->get();
        $schools = School::orderBy('name')->get();

        return view('subscriptions.index', compact('query', 'plans', 'schools'));
    }

    public function show(Subscription $subscription): View
    {
        $subscription->load('school', 'plan');

        return view('subscriptions.show', compact('subscription'));
    }

    public function destroy(Subscription $subscription): RedirectResponse
    {
        $subscription->delete();

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'Subscription deleted successfully.');
    }
}
