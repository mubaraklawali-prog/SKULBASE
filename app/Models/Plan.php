<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'monthly_price',
        'yearly_price',
        'student_limit',
        'is_unlimited',
        'trial_days',
        'is_active',
        'sort_order',
        'discount_percentage',
        'discount_start_date',
        'discount_end_date',
        'discount_scope',
    ];

    protected $casts = [
        'monthly_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
        'student_limit' => 'integer',
        'is_unlimited' => 'boolean',
        'trial_days' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'discount_percentage' => 'decimal:2',
        'discount_start_date' => 'date',
        'discount_end_date' => 'date',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function isUnlimited(): bool
    {
        return $this->is_unlimited;
    }

    public function formattedMonthlyPrice(): string
    {
        return '₦'.number_format((float) $this->monthly_price, 0);
    }

    public function formattedYearlyPrice(): string
    {
        return '₦'.number_format((float) $this->yearly_price, 0);
    }

    public function getStudentLimitDisplayAttribute(): string
    {
        if ($this->is_unlimited) {
            return 'Unlimited';
        }

        return number_format($this->student_limit ?? 0);
    }

    public function hasSubscriptions(): bool
    {
        return $this->subscriptions()->exists();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Check if the discount is currently active (within date range and percentage > 0).
     */
    public function isDiscountActive(): bool
    {
        if ($this->discount_percentage <= 0) {
            return false;
        }

        $now = Carbon::now();

        if ($this->discount_start_date && $now->lt($this->discount_start_date)) {
            return false;
        }

        if ($this->discount_end_date && $now->gt($this->discount_end_date)) {
            return false;
        }

        return true;
    }

    /**
     * Calculate the discounted price for a given billing cycle.
     * Returns the original price if discount is not applicable.
     */
    public function discountedPrice(string $billingCycle): float
    {
        $originalPrice = match ($billingCycle) {
            'monthly' => (float) $this->monthly_price,
            'yearly' => (float) $this->yearly_price,
            default => (float) $this->monthly_price,
        };

        if (! $this->isDiscountActive()) {
            return $originalPrice;
        }

        $scope = $this->discount_scope ?? 'both';
        $applicable = in_array($scope, ['monthly', 'both']) && $billingCycle === 'monthly'
            || in_array($scope, ['annual', 'both']) && $billingCycle === 'yearly';

        if (! $applicable) {
            return $originalPrice;
        }

        $discount = $originalPrice * ((float) $this->discount_percentage / 100);

        return max(0, $originalPrice - $discount);
    }

    /**
     * Format the discounted monthly price.
     */
    public function formattedDiscountedMonthlyPrice(): string
    {
        return '₦'.number_format($this->discountedPrice('monthly'), 0);
    }

    /**
     * Format the discounted yearly price.
     */
    public function formattedDiscountedYearlyPrice(): string
    {
        return '₦'.number_format($this->discountedPrice('yearly'), 0);
    }

    /**
     * Get a human-readable discount scope label.
     */
    public function getDiscountScopeLabelAttribute(): string
    {
        return match ($this->discount_scope) {
            'monthly' => 'Monthly Only',
            'annual' => 'Annual Only',
            'both' => 'Both Cycles',
            default => 'Both Cycles',
        };
    }
}
