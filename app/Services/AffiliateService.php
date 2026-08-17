<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\AffiliateSetting;
use App\Models\Commission;
use App\Models\Payout;
use App\Models\Referral;
use App\Models\School;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use InvalidArgumentException;

class AffiliateService
{
    public function findByCode(?string $code): ?Affiliate
    {
        if (! $code) {
            return null;
        }

        return Affiliate::active()->where('code', $code)->first();
    }

    public function register(array $attributes): Affiliate
    {
        $attributes['code'] = $this->generateCode();

        return Affiliate::create($attributes);
    }

    public function registerWithAccount(array $attributes): Affiliate
    {
        return DB::transaction(function () use ($attributes): Affiliate {
            $user = User::create([
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'password' => Hash::make($attributes['password']),
            ]);

            $user->forceFill(['role' => 'affiliate'])->save();

            $affiliate = $this->register([
                'user_id' => $user->id,
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'phone' => $attributes['phone'] ?? null,
                'payout_method' => $attributes['payout_method'] ?? null,
                'payout_details' => $attributes['payout_details'] ?? null,
            ]);

            return $affiliate;
        });
    }

    public function activate(Affiliate $affiliate): void
    {
        $affiliate->update([
            'status' => 'active',
            'approved_at' => $affiliate->approved_at ?? now(),
        ]);
    }

    public function suspend(Affiliate $affiliate): void
    {
        $affiliate->update(['status' => 'suspended']);
    }

    public function generateCode(?int $length = null): string
    {
        $length = $length ?? (int) config('affiliate.code_length', 8);

        do {
            $code = Str::upper(Str::random($length));
        } while (Affiliate::where('code', $code)->exists());

        return $code;
    }

    public function handleSchoolRegistration(School $school, ?string $code, ?string $source = null): ?Referral
    {
        $affiliate = $this->findByCode($code);

        if (! $affiliate) {
            return null;
        }

        $referral = Referral::updateOrCreate(
            [
                'affiliate_id' => $affiliate->id,
                'school_id' => $school->id,
            ],
            [
                'referred_email' => $school->email,
                'status' => 'registered',
                'source' => $source,
            ]
        );

        if (! $school->affiliate_id) {
            $school->update(['affiliate_id' => $affiliate->id]);
        }

        return $referral;
    }

    public function handleSchoolApproval(School $school): void
    {
        $referral = Referral::where('school_id', $school->id)->latest()->first();

        if (! $referral || $referral->isExpired() || $referral->isCancelled()) {
            return;
        }

        $referral->update(['status' => 'approved']);
    }

    public function handleSubscriptionPayment(Subscription $subscription): void
    {
        if ($subscription->is_trial || (float) $subscription->amount_paid <= 0 || $subscription->status !== 'active') {
            return;
        }

        $referral = Referral::where('school_id', $subscription->school_id)->latest()->first();

        if (! $referral || ! $referral->affiliate || ! $referral->affiliate->isActive()) {
            return;
        }

        $period = $this->paidPeriod($subscription);

        if ($this->commissionExists($referral, $period)) {
            return;
        }

        if ($referral->first_paid_at === null) {
            $this->createFirstPaymentCommission($referral, $subscription, $period);

            return;
        }

        $this->createRecurringCommission($referral, $subscription, $period);
    }

    public function voidCommissionsForSubscription(Subscription $subscription, ?string $note = null): void
    {
        Commission::where('subscription_id', $subscription->id)
            ->whereIn('status', ['pending', 'approved'])
            ->each(function (Commission $commission) use ($note): void {
                $this->cancelCommission($commission, $note ?? 'Payment refunded, cancelled, or reversed.');
            });
    }

    public function approveCommission(Commission $commission): void
    {
        if ($commission->isCancelled()) {
            return;
        }

        $commission->update(['status' => 'approved']);
    }

    public function markCommissionPaid(Commission $commission, ?string $reference = null): void
    {
        if ($commission->isCancelled()) {
            return;
        }

        $commission->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_reference' => $reference,
        ]);
    }

    public function cancelCommission(Commission $commission, ?string $note = null): void
    {
        $commission->update([
            'status' => 'cancelled',
            'notes' => $note ? trim($commission->notes.' '.$note) : $commission->notes,
        ]);
    }

    public function requestPayout(Affiliate $affiliate, array $attributes): Payout
    {
        $amount = (float) ($attributes['amount'] ?? 0);
        $minimum = (float) $this->setting('min_payout_amount', config('affiliate.min_payout_amount', 0));

        if ($amount < $minimum) {
            throw new InvalidArgumentException("The minimum payout amount is {$minimum}.");
        }

        $hasPending = Payout::where('affiliate_id', $affiliate->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            throw new InvalidArgumentException('You already have a pending payout request. Please wait for it to be processed.');
        }

        $available = $this->availableBalance($affiliate);

        if ($amount > $available) {
            throw new InvalidArgumentException('Insufficient available balance. You have ₦'.number_format($available, 2).' available.');
        }

        return Payout::create([
            'affiliate_id' => $affiliate->id,
            'amount' => $amount,
            'method' => $attributes['method'],
            'status' => 'pending',
            'payout_details' => $attributes['payout_details'] ?? null,
            'requested_at' => now(),
            'notes' => $attributes['notes'] ?? null,
        ]);
    }

    public function availableBalance(Affiliate $affiliate): float
    {
        $approvedCommissions = (float) $affiliate->commissions()
            ->where('status', 'approved')
            ->sum('amount');

        $pendingPayouts = (float) $affiliate->payouts()
            ->where('status', 'pending')
            ->sum('amount');

        return max(0.0, $approvedCommissions - $pendingPayouts);
    }

    public function effectiveCommissionRate(Affiliate $affiliate): float
    {
        return $affiliate->effectiveCommissionRate();
    }

    public function approvePayout(Payout $payout, ?string $reference = null): void
    {
        if (! $payout->isPending()) {
            return;
        }

        $payout->update([
            'status' => 'paid',
            'reference' => $reference,
            'paid_at' => now(),
        ]);
    }

    public function rejectPayout(Payout $payout, ?string $note = null): void
    {
        if (! $payout->isPending()) {
            return;
        }

        $payout->update([
            'status' => 'cancelled',
            'notes' => $note ? trim($payout->notes.' '.$note) : $payout->notes,
        ]);
    }

    public function setting(string $key, mixed $default = null): mixed
    {
        return AffiliateSetting::value($key, $default);
    }

    public function summary(Affiliate $affiliate): array
    {
        return [
            'total_earned' => (float) $affiliate->commissions()->whereIn('status', ['pending', 'approved', 'paid'])->sum('amount'),
            'pending' => (float) $affiliate->commissions()->where('status', 'pending')->sum('amount'),
            'approved' => (float) $affiliate->commissions()->where('status', 'approved')->sum('amount'),
            'paid' => (float) $affiliate->commissions()->where('status', 'paid')->sum('amount'),
        ];
    }

    private function createFirstPaymentCommission(Referral $referral, Subscription $subscription, string $period): void
    {
        $rate = $referral->affiliate->effectiveCommissionRate();
        $amount = round(((float) $subscription->amount_paid * $rate) / 100, 2);
        $firstPaidAt = $subscription->starts_at ?? now();

        $referral->update([
            'status' => 'converted',
            'first_paid_at' => $firstPaidAt,
            'converted_at' => now(),
            'commission_eligible_until' => $firstPaidAt->copy()->addMonths(
                (int) $this->setting('commission_months', config('affiliate.commission_months', 12))
            ),
        ]);

        Commission::create([
            'affiliate_id' => $referral->affiliate_id,
            'referral_id' => $referral->id,
            'subscription_id' => $subscription->id,
            'plan_id' => $subscription->plan_id,
            'amount' => $amount,
            'rate' => $rate,
            'type' => 'first_payment',
            'status' => 'pending',
            'paid_period' => $period,
        ]);
    }

    private function createRecurringCommission(Referral $referral, Subscription $subscription, string $period): void
    {
        $paidAt = $subscription->starts_at ?? now();

        if ($paidAt->greaterThan($referral->commission_eligible_until)) {
            $referral->update(['status' => 'expired']);

            return;
        }

        $rate = $referral->affiliate->effectiveCommissionRate();
        $amount = round(((float) $subscription->amount_paid * $rate) / 100, 2);

        Commission::create([
            'affiliate_id' => $referral->affiliate_id,
            'referral_id' => $referral->id,
            'subscription_id' => $subscription->id,
            'plan_id' => $subscription->plan_id,
            'amount' => $amount,
            'rate' => $rate,
            'type' => 'recurring',
            'status' => 'pending',
            'paid_period' => $period,
        ]);
    }

    private function paidPeriod(Subscription $subscription): string
    {
        if ($subscription->starts_at === null) {
            return now()->format('Y-m');
        }

        return $subscription->billing_cycle === 'yearly'
            ? $subscription->starts_at->format('Y')
            : $subscription->starts_at->format('Y-m');
    }

    private function commissionExists(Referral $referral, string $period): bool
    {
        return Commission::where('referral_id', $referral->id)
            ->where('paid_period', $period)
            ->exists();
    }
}
