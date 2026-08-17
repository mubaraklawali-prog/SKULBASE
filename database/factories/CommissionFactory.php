<?php

namespace Database\Factories;

use App\Models\Affiliate;
use App\Models\Commission;
use App\Models\Referral;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Commission>
 */
class CommissionFactory extends Factory
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
            'referral_id' => Referral::factory(),
            'amount' => fake()->randomFloat(2, 500, 50000),
            'rate' => 20.00,
            'type' => 'first_payment',
            'status' => 'pending',
        ];
    }

    /**
     * Indicate that the commission is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    /**
     * Indicate that the commission has been paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    /**
     * Indicate that the commission is recurring.
     */
    public function recurring(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'recurring',
        ]);
    }
}
