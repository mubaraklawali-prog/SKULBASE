<?php

namespace Database\Factories;

use App\Models\Affiliate;
use App\Models\Payout;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payout>
 */
class PayoutFactory extends Factory
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
            'amount' => fake()->randomFloat(2, 10000, 100000),
            'method' => 'bank_transfer',
            'status' => 'pending',
            'requested_at' => now(),
        ];
    }

    /**
     * Indicate that the payout has been paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }
}
