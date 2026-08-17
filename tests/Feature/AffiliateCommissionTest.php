<?php

use App\Models\Affiliate;
use App\Models\AffiliateSetting;
use App\Models\Plan;
use App\Models\School;
use App\Models\Subscription;
use App\Services\AffiliateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->plan = Plan::create([
        'name' => 'Premium',
        'slug' => 'premium',
        'monthly_price' => 10000,
        'yearly_price' => 100000,
        'student_limit' => null,
        'is_unlimited' => true,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $this->affiliateService = app(AffiliateService::class);

    AffiliateSetting::updateOrCreate(['key' => 'default_commission_rate'], ['value' => '20.00']);
    AffiliateSetting::updateOrCreate(['key' => 'commission_months'], ['value' => '12']);
});

function makeSchool(string $name = 'Academy', string $email = 'academy@test.com'): School
{
    return School::create([
        'name' => $name,
        'slug' => Str::slug($name),
        'email' => $email,
        'is_active' => false,
        'registration_status' => 'pending',
        'registered_at' => now(),
    ]);
}

function makePaidSubscription(School $school, int $planId, float $amount = 10000, ?Carbon $startsAt = null): Subscription
{
    return Subscription::create([
        'school_id' => $school->id,
        'plan_id' => $planId,
        'billing_cycle' => 'monthly',
        'status' => 'active',
        'starts_at' => $startsAt ?? now(),
        'expires_at' => ($startsAt ?? now())->copy()->addMonth(),
        'is_trial' => false,
        'amount_paid' => $amount,
    ]);
}

it('registers a public affiliate with a unique referral code', function (): void {
    $affiliate = $this->affiliateService->register([
        'name' => 'Jane Marketer',
        'email' => 'jane@example.com',
        'status' => 'active',
    ]);

    expect($affiliate)->not->toBeNull();
    expect($affiliate->status)->toBe('active');
    expect($affiliate->code)->toBeString();
    expect(strlen($affiliate->code))->toBe(config('affiliate.code_length'));
    expect(Affiliate::where('code', $affiliate->code)->count())->toBe(1);
});

it('creates a registered referral when a school signs up with a valid code', function (): void {
    $affiliate = Affiliate::factory()->active()->create();
    $school = makeSchool();

    $referral = $this->affiliateService->handleSchoolRegistration($school, $affiliate->code);

    expect($referral)->not->toBeNull();
    expect($referral->status)->toBe('registered');
    expect($referral->school_id)->toBe($school->id);
    expect($referral->referred_email)->toBe($school->email);
    expect($school->fresh()->affiliate_id)->toBe($affiliate->id);
});

it('ignores invalid or non-active referral codes', function (): void {
    $school = makeSchool();

    expect($this->affiliateService->handleSchoolRegistration($school, 'INVALIDCODE'))->toBeNull();

    $suspended = Affiliate::factory()->suspended()->create();

    expect($this->affiliateService->handleSchoolRegistration($school, $suspended->code))->toBeNull();
    expect($school->fresh()->affiliate_id)->toBeNull();
});

it('marks a referral as approved when the school is approved', function (): void {
    $affiliate = Affiliate::factory()->active()->create();
    $school = makeSchool();
    $referral = $this->affiliateService->handleSchoolRegistration($school, $affiliate->code);

    $this->affiliateService->handleSchoolApproval($school);

    expect($referral->fresh()->status)->toBe('approved');
});

it('creates a first payment commission at the default rate when a paid subscription starts', function (): void {
    $affiliate = Affiliate::factory()->active()->create();
    $school = makeSchool();
    $this->affiliateService->handleSchoolRegistration($school, $affiliate->code);
    $subscription = makePaidSubscription($school, $this->plan->id, 10000);

    $this->affiliateService->handleSubscriptionPayment($subscription);

    $referral = $school->referrals()->first();
    $commission = $referral->commissions()->first();

    expect($commission)->not->toBeNull();
    expect($commission->type)->toBe('first_payment');
    expect($commission->status)->toBe('pending');
    expect($commission->amount)->toBe('2000.00');
    expect($commission->rate)->toBe('20.00');

    expect($referral->fresh()->status)->toBe('converted');
    expect($referral->fresh()->first_paid_at)->not->toBeNull();
    expect($referral->fresh()->commission_eligible_until)->not->toBeNull();
});

it('creates recurring commissions for renewals within the 12-month window', function (): void {
    $affiliate = Affiliate::factory()->active()->create();
    $school = makeSchool();
    $this->affiliateService->handleSchoolRegistration($school, $affiliate->code);
    $this->affiliateService->handleSubscriptionPayment(makePaidSubscription($school, $this->plan->id, 10000));

    $this->affiliateService->handleSubscriptionPayment(makePaidSubscription($school, $this->plan->id, 10000, now()->addMonth()));

    $referral = $school->referrals()->first();
    $commissions = $referral->commissions;

    expect($commissions)->toHaveCount(2);
    expect($commissions->where('type', 'first_payment'))->toHaveCount(1);
    expect($commissions->where('type', 'recurring'))->toHaveCount(1);
    expect($commissions->where('type', 'recurring')->first()->amount)->toBe('2000.00');
    expect($referral->fresh()->status)->toBe('converted');
});

it('does not create a commission for renewals after the 12-month window', function (): void {
    $affiliate = Affiliate::factory()->active()->create();
    $school = makeSchool();
    $this->affiliateService->handleSchoolRegistration($school, $affiliate->code);
    $this->affiliateService->handleSubscriptionPayment(makePaidSubscription($school, $this->plan->id, 10000));

    $this->affiliateService->handleSubscriptionPayment(makePaidSubscription($school, $this->plan->id, 10000, now()->addMonths(13)));

    $referral = $school->referrals()->first();

    expect($referral->commissions)->toHaveCount(1);
    expect($referral->fresh()->status)->toBe('expired');
});

it('does not generate commissions for trial subscriptions', function (): void {
    $affiliate = Affiliate::factory()->active()->create();
    $school = makeSchool();
    $this->affiliateService->handleSchoolRegistration($school, $affiliate->code);

    $trial = Subscription::create([
        'school_id' => $school->id,
        'plan_id' => $this->plan->id,
        'billing_cycle' => 'monthly',
        'status' => 'trial',
        'starts_at' => now(),
        'trial_starts_at' => now(),
        'trial_ends_at' => now()->addDays(30),
        'is_trial' => true,
        'amount_paid' => 0,
    ]);

    $this->affiliateService->handleSubscriptionPayment($trial);

    expect($school->referrals()->first()->commissions()->count())->toBe(0);
});

it('prevents duplicate commissions for the same paid period', function (): void {
    $affiliate = Affiliate::factory()->active()->create();
    $school = makeSchool();
    $this->affiliateService->handleSchoolRegistration($school, $affiliate->code);
    $subscription = makePaidSubscription($school, $this->plan->id, 10000);

    $this->affiliateService->handleSubscriptionPayment($subscription);
    $this->affiliateService->handleSubscriptionPayment($subscription);

    expect($school->referrals()->first()->commissions()->count())->toBe(1);
});

it('uses the affiliate-specific commission rate override', function (): void {
    $affiliate = Affiliate::factory()->active()->create(['commission_rate' => 25]);
    $school = makeSchool();
    $this->affiliateService->handleSchoolRegistration($school, $affiliate->code);
    $this->affiliateService->handleSubscriptionPayment(makePaidSubscription($school, $this->plan->id, 10000));

    $commission = $school->referrals()->first()->commissions()->first();

    expect($commission->amount)->toBe('2500.00');
    expect($commission->rate)->toBe('25.00');
});

it('cancels payable commissions when a payment is refunded or reversed', function (): void {
    $affiliate = Affiliate::factory()->active()->create();
    $school = makeSchool();
    $this->affiliateService->handleSchoolRegistration($school, $affiliate->code);
    $subscription = makePaidSubscription($school, $this->plan->id, 10000);
    $this->affiliateService->handleSubscriptionPayment($subscription);

    $commission = $school->referrals()->first()->commissions()->first();
    expect($commission->status)->toBe('pending');

    $this->affiliateService->voidCommissionsForSubscription($subscription);

    expect($commission->fresh()->status)->toBe('cancelled');
});

it('does not reward an affiliate once suspended', function (): void {
    $affiliate = Affiliate::factory()->active()->create();
    $school = makeSchool();
    $this->affiliateService->handleSchoolRegistration($school, $affiliate->code);
    $affiliate->update(['status' => 'suspended']);

    $this->affiliateService->handleSubscriptionPayment(makePaidSubscription($school, $this->plan->id, 10000));

    expect($school->referrals()->first()->commissions()->count())->toBe(0);
});
