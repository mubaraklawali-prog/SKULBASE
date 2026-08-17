<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Referral extends Model
{
    /** @use HasFactory<Database\Factories\ReferralFactory> */
    use HasFactory;

    protected $fillable = [
        'affiliate_id',
        'school_id',
        'referred_email',
        'status',
        'source',
        'first_paid_at',
        'commission_eligible_until',
        'converted_at',
    ];

    protected $casts = [
        'first_paid_at' => 'datetime',
        'commission_eligible_until' => 'datetime',
        'converted_at' => 'datetime',
    ];

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    public function isRegistered(): bool
    {
        return $this->status === 'registered';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isConverted(): bool
    {
        return $this->status === 'converted';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isEligible(): bool
    {
        return $this->first_paid_at !== null
            && $this->commission_eligible_until !== null
            && now()->lessThanOrEqualTo($this->commission_eligible_until);
    }
}
