<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free Trial',
                'slug' => 'free-trial',
                'description' => 'Try Skulbase free for 30 days. No payment required.',
                'monthly_price' => 0,
                'yearly_price' => 0,
                'student_limit' => null,
                'is_unlimited' => true,
                'trial_days' => 30,
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Perfect for small schools getting started with digital management.',
                'monthly_price' => 5000,
                'yearly_price' => 50000,
                'student_limit' => 300,
                'is_unlimited' => false,
                'trial_days' => 30,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Standard',
                'slug' => 'standard',
                'description' => 'Ideal for growing schools that need more capacity and features.',
                'monthly_price' => 10000,
                'yearly_price' => 100000,
                'student_limit' => 1000,
                'is_unlimited' => false,
                'trial_days' => 30,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'description' => 'For large institutions requiring unlimited students and priority support.',
                'monthly_price' => 20000,
                'yearly_price' => 200000,
                'student_limit' => null,
                'is_unlimited' => true,
                'trial_days' => 30,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
