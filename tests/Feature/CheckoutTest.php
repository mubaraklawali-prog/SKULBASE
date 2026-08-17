<?php

use App\Models\AffiliateSetting;
use App\Models\Commission;
use App\Models\PaymentTransaction;
use App\Models\Plan;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config(['services.paystack.public_key' => 'pk_test_fake_key']);
    config(['services.paystack.secret_key' => 'sk_test_fake_key']);
    config(['services.paystack.base_url' => 'https://api.paystack.co']);

    AffiliateSetting::updateOrCreate(['key' => 'default_commission_rate'], ['value' => '20.00']);
    AffiliateSetting::updateOrCreate(['key' => 'commission_months'], ['value' => '12']);
    AffiliateSetting::updateOrCreate(['key' => 'min_payout_amount'], ['value' => '10000.00']);
});

function checkoutMakeSchoolAndUser(string $role = 'school_admin'): array
{
    $school = School::create([
        'name' => 'Checkout Test Academy',
        'slug' => Str::slug('Checkout Test Academy'),
        'email' => 'checkout@test.com',
        'is_active' => true,
        'registration_status' => 'approved',
        'registered_at' => now(),
    ]);
    $user = User::factory()->create(['role' => $role]);
    $user->forceFill(['school_id' => $school->id])->save();

    return [$user, $school];
}

function checkoutMakePlans(): array
{
    $freeTrial = Plan::create([
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

    $premium = Plan::create([
        'name' => 'Premium',
        'slug' => 'premium',
        'monthly_price' => 20000,
        'yearly_price' => 200000,
        'student_limit' => null,
        'is_unlimited' => true,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 3,
    ]);

    $inactive = Plan::create([
        'name' => 'Discontinued',
        'slug' => 'discontinued',
        'monthly_price' => 3000,
        'yearly_price' => 30000,
        'student_limit' => 100,
        'is_unlimited' => false,
        'trial_days' => 0,
        'is_active' => false,
        'sort_order' => 99,
    ]);

    return compact('freeTrial', 'starter', 'standard', 'premium', 'inactive');
}

function fakePaystackSuccess(string $reference = 'SB-TEST-REF'): void
{
    Http::fake([
        'https://api.paystack.co/*' => Http::response([
            'status' => true,
            'message' => 'Authorization URL created',
            'data' => [
                'authorization_url' => 'https://checkout.paystack.com/test',
                'access_code' => 'test_access_code',
                'reference' => $reference,
            ],
        ], 200),
    ]);
}

function fakePaystackFailure(string $message = 'Something went wrong', int $code = 400): void
{
    Http::fake([
        'https://api.paystack.co/*' => Http::response([
            'status' => false,
            'message' => $message,
        ], $code),
    ]);
}

/*
|--------------------------------------------------------------------------
| Checkout Page Loading
|--------------------------------------------------------------------------
*/
it('loads the checkout page for a valid paid plan', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();
    $plans = checkoutMakePlans();

    $this->actingAs($user)
        ->get(route('school.subscription.checkout', [
            'plan_id' => $plans['starter']->id,
            'billing_cycle' => 'monthly',
        ]))
        ->assertOk()
        ->assertSee('Checkout')
        ->assertSee('Starter')
        ->assertSee('₦5,000.00')
        ->assertSee('Pay ₦5,000.00');
});

it('redirects non-school-admin users away from checkout', function (): void {
    $user = User::factory()->create(['role' => 'student']);
    $plans = checkoutMakePlans();

    $this->actingAs($user)
        ->get(route('school.subscription.checkout', [
            'plan_id' => $plans['starter']->id,
            'billing_cycle' => 'monthly',
        ]))
        ->assertForbidden();
});

/*
|--------------------------------------------------------------------------
| Pricing Calculations
|--------------------------------------------------------------------------
*/
it('calculates correct amount for Starter monthly', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();
    $plans = checkoutMakePlans();

    $this->actingAs($user)
        ->get(route('school.subscription.checkout', [
            'plan_id' => $plans['starter']->id,
            'billing_cycle' => 'monthly',
        ]))
        ->assertOk()
        ->assertSee('₦5,000.00');
});

it('calculates correct amount for Standard monthly', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();
    $plans = checkoutMakePlans();

    $this->actingAs($user)
        ->get(route('school.subscription.checkout', [
            'plan_id' => $plans['standard']->id,
            'billing_cycle' => 'monthly',
        ]))
        ->assertOk()
        ->assertSee('₦10,000.00');
});

it('calculates correct amount for Premium monthly', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();
    $plans = checkoutMakePlans();

    $this->actingAs($user)
        ->get(route('school.subscription.checkout', [
            'plan_id' => $plans['premium']->id,
            'billing_cycle' => 'monthly',
        ]))
        ->assertOk()
        ->assertSee('₦20,000.00');
});

it('uses annual price for yearly billing cycle', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();
    $plans = checkoutMakePlans();

    $this->actingAs($user)
        ->get(route('school.subscription.checkout', [
            'plan_id' => $plans['starter']->id,
            'billing_cycle' => 'yearly',
        ]))
        ->assertOk()
        ->assertSee('₦50,000.00')
        ->assertSee('Yearly');
});

it('applies discount when plan has active discount', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();
    $plans = checkoutMakePlans();

    $plans['standard']->update([
        'discount_percentage' => 20,
        'discount_start_date' => now()->subDay(),
        'discount_end_date' => now()->addMonth(),
        'discount_scope' => 'both',
    ]);

    $this->actingAs($user)
        ->get(route('school.subscription.checkout', [
            'plan_id' => $plans['standard']->id,
            'billing_cycle' => 'monthly',
        ]))
        ->assertOk()
        ->assertSee('₦8,000.00')
        ->assertSee('Original Price')
        ->assertSee('Discount');
});

/*
|--------------------------------------------------------------------------
| Free Trial Rejection
|--------------------------------------------------------------------------
*/
it('rejects Free Trial through paid checkout', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();
    $plans = checkoutMakePlans();

    $this->actingAs($user)
        ->get(route('school.subscription.checkout', [
            'plan_id' => $plans['freeTrial']->id,
            'billing_cycle' => 'monthly',
        ]))
        ->assertRedirect()
        ->assertSessionHasErrors('plan_id');
});

/*
|--------------------------------------------------------------------------
| Inactive Plan Rejection
|--------------------------------------------------------------------------
*/
it('rejects inactive plan from checkout', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();
    $plans = checkoutMakePlans();

    $this->actingAs($user)
        ->get(route('school.subscription.checkout', [
            'plan_id' => $plans['inactive']->id,
            'billing_cycle' => 'monthly',
        ]))
        ->assertRedirect()
        ->assertSessionHasErrors('plan_id');
});

/*
|--------------------------------------------------------------------------
| Server-Side Price Calculation (No Client Trust)
|--------------------------------------------------------------------------
*/
it('does not allow client-submitted price to override server calculation', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();
    $plans = checkoutMakePlans();

    fakePaystackSuccess();

    $this->actingAs($user)
        ->post(route('school.subscription.pay'), [
            'plan_id' => $plans['starter']->id,
            'billing_cycle' => 'monthly',
        ]);

    $transaction = PaymentTransaction::where('school_id', $school->id)->first();
    expect($transaction)->not->toBeNull();
    expect($transaction->amount)->toBe('5000.00');
});

/*
|--------------------------------------------------------------------------
| PaymentTransaction Creation
|--------------------------------------------------------------------------
*/
it('creates a pending PaymentTransaction on checkout', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();
    $plans = checkoutMakePlans();

    fakePaystackSuccess();

    $this->actingAs($user)
        ->post(route('school.subscription.pay'), [
            'plan_id' => $plans['starter']->id,
            'billing_cycle' => 'monthly',
        ]);

    $transaction = PaymentTransaction::where('school_id', $school->id)->first();
    expect($transaction)->not->toBeNull();
    expect($transaction->status)->toBe('pending');
    expect($transaction->amount)->toBe('5000.00');
    expect($transaction->currency)->toBe('NGN');
    expect($transaction->gateway)->toBe('paystack');
    expect($transaction->school_id)->toBe($school->id);
    expect($transaction->subscription_id)->toBeNull();
});

/*
|--------------------------------------------------------------------------
| Paystack Initialization
|--------------------------------------------------------------------------
*/
it('sends correct amount in kobo to Paystack', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();
    $plans = checkoutMakePlans();

    fakePaystackSuccess();

    $this->actingAs($user)
        ->post(route('school.subscription.pay'), [
            'plan_id' => $plans['premium']->id,
            'billing_cycle' => 'yearly',
        ]);

    Http::assertSent(function ($request) {
        $body = json_decode($request->body(), true);

        return $request->url() === 'https://api.paystack.co/transaction/initialize'
            && $body['amount'] === 20000000
            && $body['currency'] === 'NGN'
            && $body['email'] === 'checkout@test.com';
    });
});

it('stores the Paystack reference on the transaction', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();
    $plans = checkoutMakePlans();

    fakePaystackSuccess();

    $this->actingAs($user)
        ->post(route('school.subscription.pay'), [
            'plan_id' => $plans['starter']->id,
            'billing_cycle' => 'monthly',
        ]);

    $transaction = PaymentTransaction::where('school_id', $school->id)->first();
    expect($transaction->reference)->not->toBeNull();
    expect($transaction->reference)->toStartWith('SB-');
});

it('redirects to Paystack authorization URL', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();
    $plans = checkoutMakePlans();

    Http::fake([
        'https://api.paystack.co/*' => Http::response([
            'status' => true,
            'message' => 'Authorization URL created',
            'data' => [
                'authorization_url' => 'https://checkout.paystack.com/test_auth_url',
                'access_code' => 'test_access_code',
                'reference' => 'SB-TEST-REF',
            ],
        ], 200),
    ]);

    $this->actingAs($user)
        ->post(route('school.subscription.pay'), [
            'plan_id' => $plans['starter']->id,
            'billing_cycle' => 'monthly',
        ])
        ->assertRedirect('https://checkout.paystack.com/test_auth_url');
});

/*
|--------------------------------------------------------------------------
| Paystack Initialization Failure
|--------------------------------------------------------------------------
*/
it('does not activate subscription on Paystack initialization failure', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();
    $plans = checkoutMakePlans();

    fakePaystackFailure('Something went wrong', 400);

    $this->actingAs($user)
        ->post(route('school.subscription.pay'), [
            'plan_id' => $plans['starter']->id,
            'billing_cycle' => 'monthly',
        ])
        ->assertRedirect();

    $transaction = PaymentTransaction::where('school_id', $school->id)->first();
    expect($transaction)->not->toBeNull();
    expect($transaction->status)->toBe('failed');

    expect($school->subscriptions()->count())->toBe(0);
});

it('marks transaction as failed when Paystack returns error', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();
    $plans = checkoutMakePlans();

    fakePaystackFailure('Invalid API key', 401);

    $this->actingAs($user)
        ->post(route('school.subscription.pay'), [
            'plan_id' => $plans['starter']->id,
            'billing_cycle' => 'monthly',
        ]);

    $transaction = PaymentTransaction::where('school_id', $school->id)->first();
    expect($transaction->status)->toBe('failed');
    expect($transaction->gateway_response['error'])->toContain('Invalid API key');
});

/*
|--------------------------------------------------------------------------
| No Affiliate Commission on Checkout
|--------------------------------------------------------------------------
*/
it('does not create affiliate commission during checkout initialization', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();
    $plans = checkoutMakePlans();

    fakePaystackSuccess();

    $this->actingAs($user)
        ->post(route('school.subscription.pay'), [
            'plan_id' => $plans['starter']->id,
            'billing_cycle' => 'monthly',
        ]);

    expect($school->referrals()->count())->toBe(0);
    expect(Commission::count())->toBe(0);
});

/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/
it('requires plan_id and billing_cycle for checkout page', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();

    $this->actingAs($user)
        ->get(route('school.subscription.checkout'))
        ->assertRedirect();
});

it('requires valid billing cycle', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();
    $plans = checkoutMakePlans();

    $this->actingAs($user)
        ->get(route('school.subscription.checkout', [
            'plan_id' => $plans['starter']->id,
            'billing_cycle' => 'invalid',
        ]))
        ->assertRedirect();
});

it('requires plan_id and billing_cycle for payment', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();

    $this->actingAs($user)
        ->post(route('school.subscription.pay'), [])
        ->assertSessionHasErrors(['plan_id', 'billing_cycle']);
});

/*
|--------------------------------------------------------------------------
| Callback Route
|--------------------------------------------------------------------------
*/
it('redirects callback to subscription page with error when no reference provided', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();

    $this->actingAs($user)
        ->get(route('school.subscription.checkout.callback'))
        ->assertRedirect(route('school.subscription.index'))
        ->assertSessionHas('error');
});

it('redirects callback to subscription page with error for unknown reference', function (): void {
    [$user, $school] = checkoutMakeSchoolAndUser();

    $this->actingAs($user)
        ->get(route('school.subscription.checkout.callback', ['reference' => 'SB-NONEXISTENT']))
        ->assertRedirect(route('school.subscription.index'))
        ->assertSessionHas('error');
});
