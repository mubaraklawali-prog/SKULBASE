<?php

use App\Models\PaymentTransaction;
use App\Models\Plan;
use App\Models\School;
use App\Models\Subscription;
use App\Services\PaystackService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config(['services.paystack.public_key' => 'pk_test_fake_key']);
    config(['services.paystack.secret_key' => 'sk_test_fake_key']);
    config(['services.paystack.base_url' => 'https://api.paystack.co']);

    $this->paystackService = app(PaystackService::class);
});

function paymentMakeSchool(string $name = 'Test Academy', string $email = 'test@academy.com'): School
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

function paymentMakeSubscription(School $school): Subscription
{
    $plan = Plan::create([
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

    return Subscription::create([
        'school_id' => $school->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'status' => 'trial',
        'starts_at' => now(),
        'trial_starts_at' => now(),
        'trial_ends_at' => now()->addDays(30),
        'is_trial' => true,
        'amount_paid' => 0,
    ]);
}

/*
|--------------------------------------------------------------------------
| PaymentTransaction Model Tests
|--------------------------------------------------------------------------
*/
it('creates a payment transaction with correct attributes', function (): void {
    $school = paymentMakeSchool();
    $subscription = paymentMakeSubscription($school);

    $transaction = PaymentTransaction::create([
        'school_id' => $school->id,
        'subscription_id' => $subscription->id,
        'amount' => 5000,
        'currency' => 'NGN',
        'gateway' => 'paystack',
        'reference' => 'SB-1-123456-ABCDEF01',
        'status' => 'pending',
    ]);

    expect($transaction)->toBeInstanceOf(PaymentTransaction::class);
    expect($transaction->school_id)->toBe($school->id);
    expect($transaction->subscription_id)->toBe($subscription->id);
    expect($transaction->amount)->toBe('5000.00');
    expect($transaction->currency)->toBe('NGN');
    expect($transaction->gateway)->toBe('paystack');
    expect($transaction->reference)->toBe('SB-1-123456-ABCDEF01');
    expect($transaction->status)->toBe('pending');
    expect($transaction->gateway_response)->toBeNull();
});

it('stores gateway response as JSON', function (): void {
    $school = paymentMakeSchool();

    $transaction = PaymentTransaction::create([
        'school_id' => $school->id,
        'amount' => 10000,
        'currency' => 'NGN',
        'gateway' => 'paystack',
        'reference' => 'SB-2-123456-ABCDEF02',
        'status' => 'success',
        'gateway_response' => [
            'status' => 'success',
            'message' => 'Authorization URL created',
            'data' => ['amount' => 1000000],
        ],
    ]);

    expect($transaction->gateway_response)->toBeArray();
    expect($transaction->gateway_response['status'])->toBe('success');
    expect($transaction->gateway_response['data']['amount'])->toBe(1000000);
});

it('prevents duplicate gateway references', function (): void {
    $school = paymentMakeSchool();

    PaymentTransaction::create([
        'school_id' => $school->id,
        'amount' => 5000,
        'currency' => 'NGN',
        'gateway' => 'paystack',
        'reference' => 'SB-1-UNIQUE-REF',
        'status' => 'pending',
    ]);

    // Second attempt with same reference should fail
    PaymentTransaction::create([
        'school_id' => $school->id,
        'amount' => 5000,
        'currency' => 'NGN',
        'gateway' => 'paystack',
        'reference' => 'SB-1-UNIQUE-REF',
        'status' => 'pending',
    ]);
})->throws(QueryException::class);

it('has correct status check methods', function (): void {
    $school = paymentMakeSchool();

    $pending = PaymentTransaction::create([
        'school_id' => $school->id, 'amount' => 1000, 'currency' => 'NGN',
        'gateway' => 'paystack', 'reference' => 'REF-PENDING', 'status' => 'pending',
    ]);

    $success = PaymentTransaction::create([
        'school_id' => $school->id, 'amount' => 2000, 'currency' => 'NGN',
        'gateway' => 'paystack', 'reference' => 'REF-SUCCESS', 'status' => 'success',
    ]);

    $failed = PaymentTransaction::create([
        'school_id' => $school->id, 'amount' => 3000, 'currency' => 'NGN',
        'gateway' => 'paystack', 'reference' => 'REF-FAILED', 'status' => 'failed',
    ]);

    $abandoned = PaymentTransaction::create([
        'school_id' => $school->id, 'amount' => 4000, 'currency' => 'NGN',
        'gateway' => 'paystack', 'reference' => 'REF-ABANDONED', 'status' => 'abandoned',
    ]);

    expect($pending->isPending())->toBeTrue();
    expect($pending->isSuccess())->toBeFalse();
    expect($success->isSuccess())->toBeTrue();
    expect($failed->isFailed())->toBeTrue();
    expect($abandoned->isAbandoned())->toBeTrue();
});

it('formats amount correctly in NGN', function (): void {
    $school = paymentMakeSchool();

    $transaction = PaymentTransaction::create([
        'school_id' => $school->id, 'amount' => 20000, 'currency' => 'NGN',
        'gateway' => 'paystack', 'reference' => 'REF-FORMAT', 'status' => 'pending',
    ]);

    expect($transaction->formattedAmount())->toBe('₦20,000.00');
});

it('converts NGN to Paystack kobo correctly', function (): void {
    expect(PaymentTransaction::toPaystackAmount(20000.00))->toBe(2000000);
    expect(PaymentTransaction::toPaystackAmount(5000.50))->toBe(500050);
    expect(PaymentTransaction::toPaystackAmount(100.00))->toBe(10000);
    expect(PaymentTransaction::toPaystackAmount(0.00))->toBe(0);
});

it('converts Paystack kobo back to NGN correctly', function (): void {
    expect(PaymentTransaction::fromPaystackAmount(2000000))->toBe(20000.0);
    expect(PaymentTransaction::fromPaystackAmount(500050))->toBe(5000.5);
    expect(PaymentTransaction::fromPaystackAmount(10000))->toBe(100.0);
    expect(PaymentTransaction::fromPaystackAmount(0))->toBe(0.0);
});

/*
|--------------------------------------------------------------------------
| PaystackService Configuration Tests
|--------------------------------------------------------------------------
*/
it('reads Paystack configuration from environment', function (): void {
    config(['services.paystack.public_key' => 'pk_test_my_key']);
    config(['services.paystack.secret_key' => 'sk_test_my_secret']);
    config(['services.paystack.base_url' => 'https://api.paystack.co']);

    expect(config('services.paystack.public_key'))->toBe('pk_test_my_key');
    expect(config('services.paystack.secret_key'))->toBe('sk_test_my_secret');
    expect(config('services.paystack.base_url'))->toBe('https://api.paystack.co');
});

it('reports as configured when keys are present', function (): void {
    config(['services.paystack.public_key' => 'pk_test_valid']);
    config(['services.paystack.secret_key' => 'sk_test_valid']);

    $service = new PaystackService;
    expect($service->isConfigured())->toBeTrue();
});

it('reports as not configured when keys are missing', function (): void {
    config(['services.paystack.public_key' => null]);
    config(['services.paystack.secret_key' => null]);

    $service = new PaystackService;
    expect($service->isConfigured())->toBeFalse();
});

it('generates unique references with school ID prefix', function (): void {
    $school = paymentMakeSchool('Unique Academy', 'unique@test.com');

    $ref1 = $this->paystackService->generateReference($school);
    $ref2 = $this->paystackService->generateReference($school);

    expect($ref1)->toStartWith('SB-'.$school->id.'-');
    expect($ref2)->toStartWith('SB-'.$school->id.'-');
    expect($ref1)->not->toBe($ref2);
});

/*
|--------------------------------------------------------------------------
| PaystackService Transaction Record Tests (No API Calls)
|--------------------------------------------------------------------------
*/
it('creates a pending transaction record via service', function (): void {
    $school = paymentMakeSchool();

    $transaction = $this->paystackService->createTransaction(
        $school,
        5000,
        'SB-1-TEST-REF'
    );

    expect($transaction)->toBeInstanceOf(PaymentTransaction::class);
    expect($transaction->status)->toBe('pending');
    expect($transaction->amount)->toBe('5000.00');
    expect($transaction->reference)->toBe('SB-1-TEST-REF');
    expect($transaction->gateway)->toBe('paystack');
    expect($transaction->currency)->toBe('NGN');
});

it('creates a pending transaction linked to a subscription', function (): void {
    $school = paymentMakeSchool();
    $subscription = paymentMakeSubscription($school);

    $transaction = $this->paystackService->createTransaction(
        $school,
        5000,
        'SB-1-SUB-REF',
        $subscription
    );

    expect($transaction->subscription_id)->toBe($subscription->id);
});

it('marks transaction as success with gateway response', function (): void {
    $school = paymentMakeSchool();

    $transaction = $this->paystackService->createTransaction($school, 5000, 'SB-1-SUCCESS-REF');

    $updated = $this->paystackService->markSuccess($transaction, [
        'status' => 'success',
        'message' => 'Verification successful',
        'data' => ['amount' => 500000],
    ]);

    expect($updated->status)->toBe('success');
    expect($updated->gateway_response['status'])->toBe('success');
    expect($updated->gateway_response['data']['amount'])->toBe(500000);
});

it('marks transaction as failed with gateway response', function (): void {
    $school = paymentMakeSchool();

    $transaction = $this->paystackService->createTransaction($school, 5000, 'SB-1-FAIL-REF');

    $updated = $this->paystackService->markFailed($transaction, [
        'status' => 'failed',
        'message' => 'Insufficient funds',
    ]);

    expect($updated->status)->toBe('failed');
    expect($updated->gateway_response['message'])->toBe('Insufficient funds');
});

it('marks transaction as abandoned', function (): void {
    $school = paymentMakeSchool();

    $transaction = $this->paystackService->createTransaction($school, 5000, 'SB-1-ABANDON-REF');

    $updated = $this->paystackService->markAbandoned($transaction);

    expect($updated->status)->toBe('abandoned');
});

it('throws when initializing without configured keys', function (): void {
    config(['services.paystack.secret_key' => null]);

    $service = new PaystackService;
    $school = paymentMakeSchool();

    $service->initializeTransaction(
        $school,
        5000,
        'test@example.com',
        'SB-1-TEST-REF'
    );
})->throws(InvalidArgumentException::class, 'Paystack secret key is not configured');

it('throws when verifying without configured keys', function (): void {
    config(['services.paystack.secret_key' => null]);

    $service = new PaystackService;
    $service->verifyTransaction('SB-1-TEST-REF');
})->throws(InvalidArgumentException::class, 'Paystack secret key is not configured');
