<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commission extends Model
{
    /** @use HasFactory<Database\Factories\CommissionFactory> */
    use HasFactory;

    protected $fillable = [
        'affiliate_id',
        'referral_id',
        'subscription_id',
        'plan_id',
        'amount',
        'rate',
        'type',
        'status',
        'paid_period',
        'paid_at',
        'payment_reference',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'rate' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function referral(): BelongsTo
    {
        return $this->belongsTo(Referral::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', 'paid');
    }

    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeFirstPayment(Builder $query): Builder
    {
        return $query->where('type', 'first_payment');
    }

    public function scopeRecurring(Builder $query): Builder
    {
        return $query->where('type', 'recurring');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isFirstPayment(): bool
    {
        return $this->type === 'first_payment';
    }

    public function isRecurring(): bool
    {
        return $this->type === 'recurring';
    }

    public function formattedAmount(): string
    {
        return '₦'.number_format((float) $this->amount, 2);
    }

    public function formattedRate(): string
    {
        return number_format((float) $this->rate, 2).'%';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'sb-badge-warning',
            'approved' => 'sb-badge-info',
            'paid' => 'sb-badge-active',
            'cancelled' => 'sb-badge-inactive',
            default => 'sb-badge-info',
        };
    }
}
