<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'school_id',
        'plan_id',
        'billing_cycle',
        'status',
        'starts_at',
        'expires_at',
        'trial_starts_at',
        'trial_ends_at',
        'grace_ends_at',
        'cancelled_at',
        'is_trial',
        'amount_paid',
        'payment_reference',
        'notes',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'trial_starts_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'grace_ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'is_trial' => 'boolean',
        'amount_paid' => 'decimal:2',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function scopeForSchool(Builder $query, int $schoolId): Builder
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeTrial(Builder $query): Builder
    {
        return $query->where('status', 'trial');
    }

    public function scopeGrace(Builder $query): Builder
    {
        return $query->where('status', 'grace');
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('status', 'expired');
    }

    public function isTrial(): bool
    {
        return $this->status === 'trial';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isGrace(): bool
    {
        return $this->status === 'grace';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function daysRemaining(): ?int
    {
        if ($this->isTrial() && $this->trial_ends_at) {
            $days = (int) Carbon::now()->diffInDays($this->trial_ends_at, false);

            return max(0, $days);
        }

        if ($this->isActive() && $this->expires_at) {
            $days = (int) Carbon::now()->diffInDays($this->expires_at, false);

            return max(0, $days);
        }

        if ($this->isGrace() && $this->grace_ends_at) {
            $days = (int) Carbon::now()->diffInDays($this->grace_ends_at, false);

            return max(0, $days);
        }

        return null;
    }

    public function isExpiredToday(): bool
    {
        if ($this->isTrial() && $this->trial_ends_at) {
            return $this->trial_ends_at->isPast();
        }

        if ($this->isActive() && $this->expires_at) {
            return $this->expires_at->isPast();
        }

        if ($this->isGrace() && $this->grace_ends_at) {
            return $this->grace_ends_at->isPast();
        }

        return $this->isExpired();
    }

    public function canAccessSystem(): bool
    {
        return in_array($this->status, ['trial', 'active', 'grace']);
    }

    public function canModifyData(): bool
    {
        return in_array($this->status, ['trial', 'active']);
    }

    public function formattedAmountPaid(): string
    {
        return '₦'.number_format((float) $this->amount_paid, 2);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'trial' => 'sb-badge-info',
            'active' => 'sb-badge-active',
            'grace' => 'sb-badge-warning',
            'expired' => 'sb-badge-inactive',
            'cancelled' => 'sb-badge-inactive',
            default => 'sb-badge-info',
        };
    }
}
