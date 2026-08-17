<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payout extends Model
{
    /** @use HasFactory<Database\Factories\PayoutFactory> */
    use HasFactory;

    protected $fillable = [
        'affiliate_id',
        'amount',
        'method',
        'status',
        'payout_details',
        'reference',
        'requested_at',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payout_details' => 'array',
        'requested_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function formattedAmount(): string
    {
        return '₦'.number_format((float) $this->amount, 2);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'sb-badge-warning',
            'processing' => 'sb-badge-info',
            'paid' => 'sb-badge-active',
            'failed', 'cancelled' => 'sb-badge-inactive',
            default => 'sb-badge-info',
        };
    }
}
