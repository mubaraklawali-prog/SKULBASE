<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\School;
use App\Models\Subscription;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    private const GRACE_PERIOD_DAYS = 7;

    public function createTrial(School $school): Subscription
    {
        $plan = Plan::active()->where('is_unlimited', true)->first()
            ?? Plan::active()->orderByDesc('sort_order')->first();

        if (! $plan) {
            $plan = Plan::firstOrCreate(
                ['slug' => 'default'],
                [
                    'name' => 'Default Plan',
                    'description' => 'Default plan for new schools',
                    'monthly_price' => 0,
                    'yearly_price' => 0,
                    'student_limit' => null,
                    'is_unlimited' => true,
                    'trial_days' => 30,
                    'is_active' => true,
                    'sort_order' => 0,
                ]
            );
        }

        $now = Carbon::now();

        return DB::transaction(function () use ($school, $plan, $now) {
            $this->deactivateExisting($school);

            return Subscription::create([
                'school_id' => $school->id,
                'plan_id' => $plan->id,
                'billing_cycle' => 'monthly',
                'status' => 'trial',
                'starts_at' => $now,
                'expires_at' => null,
                'trial_starts_at' => $now,
                'trial_ends_at' => $now->copy()->addDays($plan->trial_days),
                'grace_ends_at' => null,
                'cancelled_at' => null,
                'is_trial' => true,
                'amount_paid' => 0,
                'payment_reference' => null,
                'notes' => 'Auto-created trial subscription',
            ]);
        });
    }

    public function activate(Subscription $subscription, string $billingCycle = 'monthly'): Subscription
    {
        $plan = $subscription->plan;
        $now = Carbon::now();
        $expiresAt = $billingCycle === 'yearly'
            ? $now->copy()->addYear()
            : $now->copy()->addMonth();
        $amount = $billingCycle === 'yearly'
            ? $plan->yearly_price
            : $plan->monthly_price;

        return DB::transaction(function () use ($subscription, $billingCycle, $now, $expiresAt, $amount) {
            $this->deactivateExisting($subscription->school);

            $subscription->update([
                'status' => 'active',
                'billing_cycle' => $billingCycle,
                'is_trial' => false,
                'starts_at' => $now,
                'expires_at' => $expiresAt,
                'trial_starts_at' => $subscription->trial_starts_at,
                'trial_ends_at' => null,
                'grace_ends_at' => null,
                'cancelled_at' => null,
                'amount_paid' => $amount,
            ]);

            return $subscription->fresh();
        });
    }

    public function renew(Subscription $subscription): Subscription
    {
        $plan = $subscription->plan;
        $now = Carbon::now();
        $expiresAt = $subscription->billing_cycle === 'yearly'
            ? $now->copy()->addYear()
            : $now->copy()->addMonth();
        $amount = $subscription->billing_cycle === 'yearly'
            ? $plan->yearly_price
            : $plan->monthly_price;

        return DB::transaction(function () use ($subscription, $now, $expiresAt, $amount) {
            $subscription->update([
                'status' => 'active',
                'is_trial' => false,
                'starts_at' => $now,
                'expires_at' => $expiresAt,
                'grace_ends_at' => null,
                'cancelled_at' => null,
                'amount_paid' => $amount,
            ]);

            return $subscription->fresh();
        });
    }

    public function expire(Subscription $subscription): Subscription
    {
        $subscription->update([
            'status' => 'expired',
            'cancelled_at' => null,
        ]);

        return $subscription->fresh();
    }

    public function startGrace(Subscription $subscription): Subscription
    {
        $subscription->update([
            'status' => 'grace',
            'grace_ends_at' => Carbon::now()->copy()->addDays(self::GRACE_PERIOD_DAYS),
        ]);

        return $subscription->fresh();
    }

    public function cancel(Subscription $subscription): Subscription
    {
        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => Carbon::now(),
        ]);

        return $subscription->fresh();
    }

    public function getActiveSubscription(School $school): ?Subscription
    {
        return $school->subscriptions()
            ->whereIn('status', ['trial', 'active', 'grace'])
            ->latest()
            ->first();
    }

    public function checkAndTransitionSubscriptions(): array
    {
        $transitions = ['trial_to_grace' => 0, 'grace_to_expired' => 0];

        Subscription::trial()
            ->where('trial_ends_at', '<=', Carbon::now())
            ->each(function (Subscription $subscription) use (&$transitions) {
                $this->startGrace($subscription);
                $transitions['trial_to_grace']++;
            });

        Subscription::grace()
            ->where('grace_ends_at', '<=', Carbon::now())
            ->each(function (Subscription $subscription) use (&$transitions) {
                $this->expire($subscription);
                $transitions['grace_to_expired']++;
            });

        return $transitions;
    }

    private function deactivateExisting(School $school): void
    {
        $school->subscriptions()
            ->whereIn('status', ['trial', 'active', 'grace'])
            ->update(['status' => 'expired']);
    }
}
