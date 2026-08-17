<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\School;
use App\Services\AffiliateService;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PendingSchoolController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptionService,
        private AffiliateService $affiliateService
    ) {}

    public function index(Request $request): View
    {
        $schools = School::query()
            ->pending()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest('registered_at')
            ->paginate(10)
            ->withQueryString();

        return view('super-admin.pending-schools.index', compact('schools'));
    }

    public function show(School $school): View
    {
        $admin = $school->users()->where('role', 'school_admin')->first();
        $plan = $school->selectedPlan ?? Plan::active()->first();

        return view('super-admin.pending-schools.show', compact('school', 'admin', 'plan'));
    }

    public function approve(School $school): RedirectResponse
    {
        if ($school->registration_status !== 'pending') {
            return back()->with('error', 'This school is not pending approval.');
        }

        DB::transaction(function () use ($school) {
            $school->update([
                'is_active' => true,
                'registration_status' => 'approved',
                'approved_at' => now(),
            ]);

            $this->subscriptionService->createTrial($school);

            $this->affiliateService->handleSchoolApproval($school);
        });

        return redirect()
            ->route('pending-schools.index')
            ->with('success', "School \"{$school->name}\" has been approved successfully. A 30-day trial subscription has been activated.");
    }

    public function reject(Request $request, School $school): RedirectResponse
    {
        if ($school->registration_status !== 'pending') {
            return back()->with('error', 'This school is not pending approval.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $school->update([
            'registration_status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        $admin = $school->users()->where('role', 'school_admin')->first();
        if ($admin) {
            $admin->update([
                'school_id' => null,
            ]);
        }

        return redirect()
            ->route('pending-schools.index')
            ->with('success', "School \"{$school->name}\" has been rejected.");
    }
}
