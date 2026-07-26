<?php

namespace App\Http\Controllers;

use App\Services\SubscriptionService;
use Illuminate\View\View;

class SchoolSubscriptionController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptionService
    ) {}

    public function index(): View
    {
        $school = auth()->user()->school;

        if (! $school) {
            abort(403, 'No school is associated with your account.');
        }

        $subscription = $this->subscriptionService->getActiveSubscription($school);

        $history = $school->subscriptions()
            ->with('plan')
            ->latest()
            ->get();

        return view('school.subscription.index', compact('school', 'subscription', 'history'));
    }
}
