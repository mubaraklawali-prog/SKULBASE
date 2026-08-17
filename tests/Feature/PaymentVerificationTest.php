<?php

use App\Models\Affiliate;
use App\Models\AffiliateSetting;
use App\Models\Commission;
use App\Models\PaymentTransaction;
use App\Models\Plan;
use App\Models\Referral;
use App\Models\School;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config(['services.paystack.public_key' => 'pk_test_fake_key']);
    config(['services.paystack.secret_key' => 'sk_test_fake_key']);
    config(['services.paystack.base_url' => 'https://api.paystack.co']);
    config(['services.paystack.webhook_secret' => 'whsec_test_webhook_secret_key']);

    AffiliateSetting::updateOrCreate(['key' => 'default_commission_rate'], ['value' => '20.00']);
    AffiliateSetting::updateOrCreate(['key' => 'commission_months'], ['value' => '12']);
    AffiliateSetting::updateOrCreate(['key' => 'min_payout_amount'], ['value' => '10000.00']);
});

function phase3MakeSchoolAndUser(string $role = 'school_admin'): array
{
    $school = School::create([
        'name' => 'Phase3 Test Academy',
        'slug' => Str::slug('Phase3 Test Academy'),
        'email' => 'phase3@test.com',
        'is_active' => true,
        'registration_status' => 'approved',
        'registered_at' => now(),
    ]);
    $user = User::factory()->create(['role' => $role]);
    $user->forceFill(['school_id' => $school->id])->save();

    return [$user, $school];
}

function phase3MakePlans(): array
{
    $starter = Plan::create([
        'name' => 'Starter',
        'slug' => 'starter',
        'monthly_price' => 5000,
        'yearly_price' => 50000,
        'student_limit' => 300,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $standard = Plan::create([
        'name' => 'Standard',
        'slug' => 'standard',
        'monthly_price' => 10000,
        'yearly_price' => 100000,
        'student_limit' => 1000,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 2,
    ]);

    return compact('starter', 'standard');
}

function phase3MakePendingTransaction(School $school, float $amount, string $reference, string $billingCycle = 'monthly', ?int $planId = null): PaymentTransaction
{
    return PaymentTransaction::create([
        'school_id' => $school->id,
        'amount' => $amount,
        'currency' => 'NGN',
        'gateway' => 'paystack',
        'reference' => $reference,
        'status' => 'pending',
        'notes' => json_encode([
            'plan_id' => $planId,
            'plan_name' => 'Test Plan',
            'billing_cycle' => $billingCycle,
        ]),
    ]);
}

function fakePaystackVerifySuccess(float $amount, string $reference): void
{
    $koboAmount = (int) round($amount * 100);

    Http::fake([
        'https://api.paystack.co/transaction/verify/*' => Http::response([
            'status' => true,
            'message' => 'Verification successful',
            'data' => [
                'status' => 'success',
                'amount' => $koboAmount,
                'reference' => $reference,
                'currency' => 'NGN',
                'gateway_response' => ['message' => 'Successful'],
            ],
        ], 200),
    ]);
}

function fakePaystackVerifyFailed(string $reference): void
{
    Http::fake([
        'https://api.paystack.co/transaction/verify/*' => Http::response([
            'status' => true,
            'message' => 'Verification successful',
            'data' => [
                'status' => 'failed',
                'amount' => 0,
                'reference' => $reference,
                'currency' => 'NGN',
                'gateway_response' => ['message' => 'Insufficient funds'],
            ],
        ], 200),
    ]);
}

function generateWebhookPayload(string $reference, float $amount, string $event = 'charge.success'): string
{
    $koboAmount = (int) round($amount * 100);

    return json_encode([
        'event' => $event,
        'data' => [
            'reference' => $reference,
            'amount' => $koboAmount,
            'status' => 'success',
            'currency' => 'NGN',
        ],
    ]);
}

function signWebhookPayload(string $payload): string
{
    $secret = config('services.paystack.webhook_secret');

    return hash_hmac('sha512', $payload, $secret);
}

/*
|--------------------------------------------------------------------------
| 1. Webhook Signature Verification
|--------------------------------------------------------------------------
*/
it('rejects webhook with invalid signature', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();
    $transaction = phase3MakePendingTransaction($school, 5000, 'SB-TEST-1', 'monthly', $plans['starter']->id);

    $payload = generateWebhookPayload('SB-TEST-1', 5000);

    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => 'invalid_signature_here',
    ])
        ->assertStatus(400)
        ->assertJson(['message' => 'Invalid signature']);

    $transaction->refresh();
    expect($transaction->status)->toBe('pending');
});

it('accepts webhook with valid signature', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();
    $transaction = phase3MakePendingTransaction($school, 5000, 'SB-TEST-2', 'monthly', $plans['starter']->id);

    fakePaystackVerifySuccess(5000, 'SB-TEST-2');

    $payload = generateWebhookPayload('SB-TEST-2', 5000);
    $signature = signWebhookPayload($payload);

    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])
        ->assertOk();

    $transaction->refresh();
    expect($transaction->status)->toBe('success');
});

it('rejects webhook when webhook_secret is not configured', function (): void {
    config(['services.paystack.webhook_secret' => '']);

    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();
    $transaction = phase3MakePendingTransaction($school, 5000, 'SB-TEST-3', 'monthly', $plans['starter']->id);

    $payload = generateWebhookPayload('SB-TEST-3', 5000);

    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => 'any_signature',
    ])
        ->assertStatus(400);

    $transaction->refresh();
    expect($transaction->status)->toBe('pending');
});

/*
|--------------------------------------------------------------------------
| 2. Webhook Event Filtering
|--------------------------------------------------------------------------
*/
it('ignores non-charge.success events safely', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();
    $transaction = phase3MakePendingTransaction($school, 5000, 'SB-TEST-4', 'monthly', $plans['starter']->id);

    $payload = generateWebhookPayload('SB-TEST-4', 5000, 'charge.failed');
    $signature = signWebhookPayload($payload);

    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])
        ->assertOk();

    $transaction->refresh();
    expect($transaction->status)->toBe('pending');
});

it('handles webhook with unknown reference safely', function (): void {
    $payload = generateWebhookPayload('SB-UNKNOWN-REF', 5000);
    $signature = signWebhookPayload($payload);

    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])
        ->assertOk();

    expect(PaymentTransaction::where('reference', 'SB-UNKNOWN-REF')->count())->toBe(0);
});

/*
|--------------------------------------------------------------------------
| 3. Successful Payment Activates Subscription
|--------------------------------------------------------------------------
*/
it('activates subscription on successful webhook payment', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();

    // Create trial subscription first
    $trial = Subscription::create([
        'school_id' => $school->id,
        'plan_id' => $plans['starter']->id,
        'billing_cycle' => 'monthly',
        'status' => 'trial',
        'starts_at' => now(),
        'expires_at' => null,
        'trial_starts_at' => now(),
        'trial_ends_at' => now()->addDays(30),
        'is_trial' => true,
        'amount_paid' => 0,
    ]);

    $transaction = phase3MakePendingTransaction($school, 5000, 'SB-SUCCESS-1', 'monthly', $plans['starter']->id);

    fakePaystackVerifySuccess(5000, 'SB-SUCCESS-1');

    $payload = generateWebhookPayload('SB-SUCCESS-1', 5000);
    $signature = signWebhookPayload($payload);

    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])
        ->assertOk();

    $transaction->refresh();
    expect($transaction->status)->toBe('success');

    $subscription = Subscription::where('school_id', $school->id)->where('status', 'active')->first();
    expect($subscription)->not->toBeNull();
    expect($subscription->is_trial)->toBeFalse();
    expect($subscription->amount_paid)->toBe('5000.00');
    expect($subscription->expires_at)->not->toBeNull();
});

/*
|--------------------------------------------------------------------------
| 4. Payment Transaction Becomes Success
|--------------------------------------------------------------------------
*/
it('records PaymentTransaction as success with gateway_response', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();
    $transaction = phase3MakePendingTransaction($school, 10000, 'SB-SUCCESS-2', 'monthly', $plans['standard']->id);

    fakePaystackVerifySuccess(10000, 'SB-SUCCESS-2');

    $payload = generateWebhookPayload('SB-SUCCESS-2', 10000);
    $signature = signWebhookPayload($payload);

    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])
        ->assertOk();

    $transaction->refresh();
    expect($transaction->status)->toBe('success');
    expect($transaction->gateway_response)->not->toBeNull();
    expect($transaction->gateway_response['processed_at'])->not->toBeNull();
});

/*
|--------------------------------------------------------------------------
| 5. Correct Amount Required / Amount Mismatch Rejected
|--------------------------------------------------------------------------
*/
it('rejects payment when Paystack amount does not match expected amount', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();
    $transaction = phase3MakePendingTransaction($school, 5000, 'SB-AMOUNT-1', 'monthly', $plans['starter']->id);

    $koboWrongAmount = 499900; // ₦4,999 instead of ₦5,000
    Http::fake([
        'https://api.paystack.co/transaction/verify/*' => Http::response([
            'status' => true,
            'message' => 'Verification successful',
            'data' => [
                'status' => 'success',
                'amount' => $koboWrongAmount,
                'reference' => 'SB-AMOUNT-1',
                'currency' => 'NGN',
                'gateway_response' => ['message' => 'Successful'],
            ],
        ], 200),
    ]);

    $payload = generateWebhookPayload('SB-AMOUNT-1', 4999);
    $signature = signWebhookPayload($payload);

    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])
        ->assertOk();

    $transaction->refresh();
    expect($transaction->status)->toBe('failed');

    expect(Subscription::where('school_id', $school->id)->where('status', 'active')->count())->toBe(0);
});

/*
|--------------------------------------------------------------------------
| 6. Incorrect Reference Rejected
|--------------------------------------------------------------------------
*/
it('rejects payment when Paystack reference does not match', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();
    $transaction = phase3MakePendingTransaction($school, 5000, 'SB-REAL-REF', 'monthly', $plans['starter']->id);

    Http::fake([
        'https://api.paystack.co/transaction/verify/*' => Http::response([
            'status' => true,
            'message' => 'Verification successful',
            'data' => [
                'status' => 'success',
                'amount' => 500000,
                'reference' => 'SB-DIFFERENT-REF',
                'currency' => 'NGN',
                'gateway_response' => ['message' => 'Successful'],
            ],
        ], 200),
    ]);

    $payload = generateWebhookPayload('SB-REAL-REF', 5000);
    $signature = signWebhookPayload($payload);

    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])
        ->assertOk();

    $transaction->refresh();
    expect($transaction->status)->toBe('pending');
});

/*
|--------------------------------------------------------------------------
| 7. Failed Paystack Payment Does Not Activate Subscription
|--------------------------------------------------------------------------
*/
it('does not activate subscription when Paystack verification shows failure', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();
    $transaction = phase3MakePendingTransaction($school, 5000, 'SB-FAIL-1', 'monthly', $plans['starter']->id);

    fakePaystackVerifyFailed('SB-FAIL-1');

    $payload = generateWebhookPayload('SB-FAIL-1', 5000);
    $signature = signWebhookPayload($payload);

    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])
        ->assertOk();

    $transaction->refresh();
    expect($transaction->status)->toBe('failed');

    expect(Subscription::where('school_id', $school->id)->where('status', 'active')->count())->toBe(0);
});

/*
|--------------------------------------------------------------------------
| 8. Free Trial Does Not Create Payment Commission
|--------------------------------------------------------------------------
*/
it('does not create commission for Free Trial', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();

    $trialPlan = Plan::create([
        'name' => 'Free Trial',
        'slug' => 'free-trial',
        'monthly_price' => 0,
        'yearly_price' => 0,
        'student_limit' => null,
        'is_unlimited' => true,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 0,
    ]);

    $trial = Subscription::create([
        'school_id' => $school->id,
        'plan_id' => $trialPlan->id,
        'billing_cycle' => 'monthly',
        'status' => 'trial',
        'starts_at' => now(),
        'trial_starts_at' => now(),
        'trial_ends_at' => now()->addDays(30),
        'is_trial' => true,
        'amount_paid' => 0,
    ]);

    expect(Commission::where('subscription_id', $trial->id)->count())->toBe(0);
    expect($trial->is_trial)->toBeTrue();
    expect((float) $trial->amount_paid)->toBe(0.0);
});

/*
|--------------------------------------------------------------------------
| 9. Successful Paid Payment Creates Affiliate Commission
|--------------------------------------------------------------------------
*/
it('creates affiliate commission on successful paid payment', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();

    $affiliate = Affiliate::factory()->active()->create();
    $referringSchool = School::create([
        'name' => 'Referrer',
        'slug' => Str::slug('Referrer'),
        'email' => 'referrer@test.com',
        'is_active' => true,
        'registration_status' => 'approved',
        'registered_at' => now()->subMonth(),
    ]);

    $referral = Referral::create([
        'affiliate_id' => $affiliate->id,
        'school_id' => $school->id,
        'referred_email' => $school->email,
        'status' => 'converted',
        'first_paid_at' => now(),
        'converted_at' => now(),
        'commission_eligible_until' => now()->addMonths(12),
    ]);

    $transaction = phase3MakePendingTransaction($school, 5000, 'SB-COMM-1', 'monthly', $plans['starter']->id);

    fakePaystackVerifySuccess(5000, 'SB-COMM-1');

    $payload = generateWebhookPayload('SB-COMM-1', 5000);
    $signature = signWebhookPayload($payload);

    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])
        ->assertOk();

    $commission = Commission::where('referral_id', $referral->id)->first();
    expect($commission)->not->toBeNull();
    expect($commission->amount)->toBe('1000.00');
    expect($commission->rate)->toBe('20.00');
});

/*
|--------------------------------------------------------------------------
| 10. Duplicate Webhook Does Not Create Duplicate Commission
|--------------------------------------------------------------------------
*/
it('does not create duplicate commission on duplicate webhook', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();

    $affiliate = Affiliate::factory()->active()->create();
    $referral = Referral::create([
        'affiliate_id' => $affiliate->id,
        'school_id' => $school->id,
        'referred_email' => $school->email,
        'status' => 'converted',
        'first_paid_at' => now(),
        'converted_at' => now(),
        'commission_eligible_until' => now()->addMonths(12),
    ]);

    $transaction = phase3MakePendingTransaction($school, 5000, 'SB-DUP-1', 'monthly', $plans['starter']->id);

    fakePaystackVerifySuccess(5000, 'SB-DUP-1');

    $payload = generateWebhookPayload('SB-DUP-1', 5000);
    $signature = signWebhookPayload($payload);

    // First webhook
    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])->assertOk();

    $commissionCount = Commission::where('referral_id', $referral->id)->count();
    expect($commissionCount)->toBe(1);

    // Duplicate webhook
    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])->assertOk();

    expect(Commission::where('referral_id', $referral->id)->count())->toBe($commissionCount);
});

/*
|--------------------------------------------------------------------------
| 11. Duplicate Webhook Does Not Extend Subscription Twice
|--------------------------------------------------------------------------
*/
it('does not extend subscription twice on duplicate webhook', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();
    $transaction = phase3MakePendingTransaction($school, 5000, 'SB-DUP-2', 'monthly', $plans['starter']->id);

    fakePaystackVerifySuccess(5000, 'SB-DUP-2');

    $payload = generateWebhookPayload('SB-DUP-2', 5000);
    $signature = signWebhookPayload($payload);

    // First webhook
    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])->assertOk();

    $subscription1 = Subscription::where('school_id', $school->id)->where('status', 'active')->first();
    $expiresAt1 = $subscription1->expires_at->copy();

    // Duplicate webhook
    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])->assertOk();

    $subscription1->refresh();
    expect($subscription1->expires_at->eq($expiresAt1))->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| 12. Already-Successful Transaction Is Not Processed Again
|--------------------------------------------------------------------------
*/
it('does not process already-successful transaction again', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();

    $subscription = Subscription::create([
        'school_id' => $school->id,
        'plan_id' => $plans['starter']->id,
        'billing_cycle' => 'monthly',
        'status' => 'active',
        'starts_at' => now(),
        'expires_at' => now()->addMonth(),
        'is_trial' => false,
        'amount_paid' => 5000,
        'payment_reference' => 'SB-ALREADY-1',
    ]);

    $transaction = PaymentTransaction::create([
        'school_id' => $school->id,
        'subscription_id' => $subscription->id,
        'amount' => 5000,
        'currency' => 'NGN',
        'gateway' => 'paystack',
        'reference' => 'SB-ALREADY-1',
        'status' => 'success',
        'gateway_response' => ['processed_at' => now()->toIso8601String()],
    ]);

    $paymentService = app(PaymentService::class);
    $result = $paymentService->processSuccessfulPayment($transaction);

    expect($result['action'])->toBe('already_processed');

    $subscriptionCount = Subscription::where('school_id', $school->id)->where('status', 'active')->count();
    expect($subscriptionCount)->toBe(1);
});

/*
|--------------------------------------------------------------------------
| 13. Callback Performs Server-Side Verification
|--------------------------------------------------------------------------
*/
it('callback performs server-side verification and activates subscription', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();
    $transaction = phase3MakePendingTransaction($school, 5000, 'SB-CALLBACK-1', 'monthly', $plans['starter']->id);

    fakePaystackVerifySuccess(5000, 'SB-CALLBACK-1');

    $this->actingAs($user)
        ->get(route('school.subscription.checkout.callback', ['reference' => 'SB-CALLBACK-1']))
        ->assertRedirect(route('school.subscription.index'))
        ->assertSessionHas('success');

    $transaction->refresh();
    expect($transaction->status)->toBe('success');

    $subscription = Subscription::where('school_id', $school->id)->where('status', 'active')->first();
    expect($subscription)->not->toBeNull();
    expect($subscription->amount_paid)->toBe('5000.00');
});

it('callback and webhook processing same transaction is idempotent', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();
    $transaction = phase3MakePendingTransaction($school, 5000, 'SB-IDEMP-1', 'monthly', $plans['starter']->id);

    fakePaystackVerifySuccess(5000, 'SB-IDEMP-1');

    // Process via callback
    $this->actingAs($user)
        ->get(route('school.subscription.checkout.callback', ['reference' => 'SB-IDEMP-1']))
        ->assertRedirect();

    // Process via webhook (duplicate)
    $payload = generateWebhookPayload('SB-IDEMP-1', 5000);
    $signature = signWebhookPayload($payload);

    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])->assertOk();

    // Only one active subscription
    $activeCount = Subscription::where('school_id', $school->id)->where('status', 'active')->count();
    expect($activeCount)->toBe(1);
});

/*
|--------------------------------------------------------------------------
| 14. Callback with Unknown Reference
|--------------------------------------------------------------------------
*/
it('callback handles unknown reference safely', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();

    $this->actingAs($user)
        ->get(route('school.subscription.checkout.callback', ['reference' => 'SB-NONEXISTENT']))
        ->assertRedirect(route('school.subscription.index'))
        ->assertSessionHas('error');
});

it('callback handles missing reference safely', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();

    $this->actingAs($user)
        ->get(route('school.subscription.checkout.callback'))
        ->assertRedirect(route('school.subscription.index'))
        ->assertSessionHas('error');
});

/*
|--------------------------------------------------------------------------
| 15. Paystack API Verification Failure
|--------------------------------------------------------------------------
*/
it('does not activate subscription when Paystack API call fails', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();
    $transaction = phase3MakePendingTransaction($school, 5000, 'SB-APIFAIL-1', 'monthly', $plans['starter']->id);

    Http::fake([
        'https://api.paystack.co/transaction/verify/*' => Http::response([
            'status' => false,
            'message' => 'Transaction not found',
        ], 404),
    ]);

    $payload = generateWebhookPayload('SB-APIFAIL-1', 5000);
    $signature = signWebhookPayload($payload);

    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])
        ->assertOk();

    $transaction->refresh();
    expect($transaction->status)->toBe('pending');

    expect(Subscription::where('school_id', $school->id)->where('status', 'active')->count())->toBe(0);
});

/*
|--------------------------------------------------------------------------
| 16. Amount Paid Snapshot Is Correct
|--------------------------------------------------------------------------
*/
it('stores correct amount_paid from plan discounted price', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();

    $plans['standard']->update([
        'discount_percentage' => 20,
        'discount_start_date' => now()->subDay(),
        'discount_end_date' => now()->addMonth(),
        'discount_scope' => 'both',
    ]);

    $transaction = phase3MakePendingTransaction($school, 8000, 'SB-SNAP-1', 'monthly', $plans['standard']->id);

    fakePaystackVerifySuccess(8000, 'SB-SNAP-1');

    $payload = generateWebhookPayload('SB-SNAP-1', 8000);
    $signature = signWebhookPayload($payload);

    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])
        ->assertOk();

    $subscription = Subscription::where('school_id', $school->id)->where('status', 'active')->first();
    expect($subscription)->not->toBeNull();
    expect($subscription->amount_paid)->toBe('8000.00');
});

/*
|--------------------------------------------------------------------------
| 17. Monthly Payment Activates Correct Period
|--------------------------------------------------------------------------
*/
it('monthly payment activates subscription for one month', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();
    $transaction = phase3MakePendingTransaction($school, 5000, 'SB-MONTHLY-1', 'monthly', $plans['starter']->id);

    fakePaystackVerifySuccess(5000, 'SB-MONTHLY-1');

    $payload = generateWebhookPayload('SB-MONTHLY-1', 5000);
    $signature = signWebhookPayload($payload);

    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])
        ->assertOk();

    $subscription = Subscription::where('school_id', $school->id)->where('status', 'active')->first();
    expect($subscription->billing_cycle)->toBe('monthly');
    expect($subscription->expires_at->greaterThan(now()->addDays(28)))->toBeTrue();
    expect($subscription->expires_at->lessThan(now()->addDays(32)))->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| 18. Annual Payment Activates Correct Period
|--------------------------------------------------------------------------
*/
it('annual payment activates subscription for one year', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();
    $transaction = phase3MakePendingTransaction($school, 50000, 'SB-ANNUAL-1', 'yearly', $plans['starter']->id);

    fakePaystackVerifySuccess(50000, 'SB-ANNUAL-1');

    $payload = generateWebhookPayload('SB-ANNUAL-1', 50000);
    $signature = signWebhookPayload($payload);

    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])
        ->assertOk();

    $subscription = Subscription::where('school_id', $school->id)->where('status', 'active')->first();
    expect($subscription->billing_cycle)->toBe('yearly');
    expect($subscription->expires_at->greaterThan(now()->addDays(364)))->toBeTrue();
    expect($subscription->expires_at->lessThan(now()->addDays(366)))->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| 19. Non-Pending Transaction Is Skipped
|--------------------------------------------------------------------------
*/
it('skips processing non-pending transaction gracefully', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();

    $subscription = Subscription::create([
        'school_id' => $school->id,
        'plan_id' => $plans['starter']->id,
        'billing_cycle' => 'monthly',
        'status' => 'active',
        'starts_at' => now()->subMonth(),
        'expires_at' => now()->addMonth(),
        'is_trial' => false,
        'amount_paid' => 5000,
    ]);

    $transaction = PaymentTransaction::create([
        'school_id' => $school->id,
        'subscription_id' => $subscription->id,
        'amount' => 5000,
        'currency' => 'NGN',
        'gateway' => 'paystack',
        'reference' => 'SB-NONPENDING-1',
        'status' => 'success',
    ]);

    $paymentService = app(PaymentService::class);
    $result = $paymentService->processSuccessfulPayment($transaction);

    expect($result['action'])->toBe('already_processed');
});

/*
|--------------------------------------------------------------------------
| 20. Subscription Linking
|--------------------------------------------------------------------------
*/
it('links subscription to transaction after processing', function (): void {
    [$user, $school] = phase3MakeSchoolAndUser();
    $plans = phase3MakePlans();
    $transaction = phase3MakePendingTransaction($school, 5000, 'SB-LINK-1', 'monthly', $plans['starter']->id);

    fakePaystackVerifySuccess(5000, 'SB-LINK-1');

    $payload = generateWebhookPayload('SB-LINK-1', 5000);
    $signature = signWebhookPayload($payload);

    $this->postJson(route('paystack.webhook'), json_decode($payload, true), [
        'x-paystack-signature' => $signature,
    ])
        ->assertOk();

    $transaction->refresh();
    expect($transaction->subscription_id)->not->toBeNull();

    $subscription = Subscription::find($transaction->subscription_id);
    expect($subscription)->not->toBeNull();
    expect($subscription->school_id)->toBe($school->id);
});
