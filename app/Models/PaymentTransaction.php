<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'school_id',
        'subscription_id',
        'amount',
        'currency',
        'gateway',
        'reference',
        'status',
        'gateway_response',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isAbandoned(): bool
    {
        return $this->status === 'abandoned';
    }

    public function formattedAmount(): string
    {
        return '₦'.number_format((float) $this->amount, 2);
    }

    /**
     * Convert NGN amount to Paystack subunit (kobo).
     * NGN 20,000.00 → 2000000 kobo.
     */
    public static function toPaystackAmount(float $amount): int
    {
        return (int) round($amount * 100);
    }

    /**
     * Convert Paystack subunit (kobo) back to NGN.
     * 2000000 kobo → NGN 20,000.00
     */
    public static function fromPaystackAmount(int $kobo): float
    {
        return $kobo / 100;
    }
}
