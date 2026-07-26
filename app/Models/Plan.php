<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $casts = [
        'monthly_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
        'student_limit' => 'integer',
        'is_unlimited' => 'boolean',
        'trial_days' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
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
        return '₦'.number_format((float) $this->monthly_price, 2);
    }

    public function formattedYearlyPrice(): string
    {
        return '₦'.number_format((float) $this->yearly_price, 2);
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
}
