<?php

namespace Database\Factories;

use App\Models\Affiliate;
use App\Models\Referral;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Referral>
 */
class ReferralFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'affiliate_id' => Affiliate::factory(),
            'referred_email' => fake()->unique()->safeEmail(),
            'status' => 'registered',
        ];
    }

    /**
     * Indicate that the referred school has been approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    /**
     * Indicate that the referred school has converted to a paid subscription.
     */
    public function converted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'converted',
            'first_paid_at' => now(),
            'converted_at' => now(),
            'commission_eligible_until' => now()->addMonths(12),
        ]);
    }
}
