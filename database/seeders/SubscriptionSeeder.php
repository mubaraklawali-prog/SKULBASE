<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\School;
use App\Models\Subscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $premium = Plan::updateOrCreate(
            ['slug' => 'premium'],
            [
                'name' => 'Premium',
                'description' => 'For large institutions requiring unlimited students and priority support.',
                'monthly_price' => 20000,
                'yearly_price' => 200000,
                'student_limit' => null,
                'is_unlimited' => true,
                'trial_days' => 30,
                'is_active' => true,
                'sort_order' => 3,
            ]
        );

        $schoolIds = School::whereDoesntHave('subscriptions')->pluck('id');

        if ($schoolIds->isEmpty()) {
            return;
        }

        $now = Carbon::now();

        foreach ($schoolIds as $schoolId) {
            Subscription::create([
                'school_id' => $schoolId,
                'plan_id' => $premium->id,
                'billing_cycle' => 'monthly',
                'status' => 'trial',
                'starts_at' => $now,
                'expires_at' => null,
                'trial_starts_at' => $now,
                'trial_ends_at' => $now->copy()->addDays($premium->trial_days),
                'grace_ends_at' => null,
                'cancelled_at' => null,
                'is_trial' => true,
                'amount_paid' => 0,
                'payment_reference' => null,
                'notes' => 'Trial subscription created via SubscriptionSeeder',
            ]);
        }
    }
}
