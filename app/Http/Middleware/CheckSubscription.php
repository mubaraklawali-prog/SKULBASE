<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    private const SUBSCRIPTION_ROUTES = [
        'school.subscription.index',
        'school.subscription.checkout',
        'school.subscription.pay',
        'school.subscription.checkout.callback',
    ];

    public function __construct(
        private SubscriptionService $subscriptionService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user || $user->role === 'super_admin') {
            return $next($request);
        }

        $school = $user->school;

        if (! $school) {
            return $next($request);
        }

        if ($this->isSubscriptionRoute($request)) {
            return $next($request);
        }

        $subscription = $this->subscriptionService->getActiveSubscription($school);

        if (! $subscription) {
            return redirect()
                ->route('school.subscription.index')
                ->with('error', 'No active subscription found. Please contact support.');
        }

        if ($subscription->isExpiredToday() && $subscription->status !== 'expired') {
            $this->subscriptionService->expire($subscription);
            $subscription->refresh();
        }

        if (! $subscription->canAccessSystem()) {
            return redirect()
                ->route('school.subscription.index')
                ->with('error', 'Your subscription has expired. Please renew to continue using Skulbase.');
        }

        if ($subscription->isGrace()) {
            $daysRemaining = $subscription->daysRemaining();

            return redirect()
                ->route('school.subscription.index')
                ->with('warning', "Your trial has expired. You have {$daysRemaining} days remaining in your grace period. Renew your subscription to continue using Skulbase.");
        }

        if ($subscription->isTrial()) {
            $request->attributes->set('subscription_warning', [
                'type' => 'trial',
                'message' => "You are on a free trial. {$subscription->daysRemaining()} days remaining.",
                'days' => $subscription->daysRemaining(),
            ]);
        }

        return $next($request);
    }

    private function isSubscriptionRoute(Request $request): bool
    {
        foreach (self::SUBSCRIPTION_ROUTES as $route) {
            if ($request->routeIs($route)) {
                return true;
            }
        }

        return false;
    }
}
