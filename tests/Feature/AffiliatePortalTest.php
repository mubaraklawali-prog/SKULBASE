<?php

use App\Models\Affiliate;
use App\Models\Commission;
use App\Models\Payout;
use App\Models\Referral;
use App\Models\School;
use App\Models\User;
use App\Services\AffiliateService;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertAuthenticatedAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('renders the affiliate registration page', function () {
    get(route('affiliates.register'))
        ->assertOk()
        ->assertSee('Become an Affiliate');
});

it('renders the affiliate login page', function () {
    get(route('affiliates.login'))
        ->assertOk()
        ->assertSee('Affiliate Login');
});

it('registers a public affiliate and redirects to the affiliate dashboard', function () {
    post(route('affiliates.register.store'), [
        'name' => 'Jane Affiliate',
        'email' => 'jane@example.com',
        'phone' => '08011223344',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'terms' => '1',
    ])
        ->assertRedirect(route('affiliate.dashboard'));

    assertAuthenticated();

    $user = User::where('email', 'jane@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->role)->toBe('affiliate');

    $affiliate = Affiliate::where('email', 'jane@example.com')->first();

    expect($affiliate)->not->toBeNull();
    expect($affiliate->code)->not->toBeNull();
    expect($affiliate->status)->toBe('pending');
    expect($affiliate->user_id)->toBe($user->id);
});

it('logs an affiliate in and redirects to the affiliate dashboard', function () {
    $service = app(AffiliateService::class);
    $affiliate = $service->registerWithAccount([
        'name' => 'John Affiliate',
        'email' => 'john@example.com',
        'password' => 'Password123!',
    ]);

    post(route('affiliates.login.submit'), [
        'email' => 'john@example.com',
        'password' => 'Password123!',
    ])
        ->assertRedirect(route('affiliate.dashboard'));

    assertAuthenticatedAs($affiliate->user);
});

it('rejects invalid affiliate credentials', function () {
    $service = app(AffiliateService::class);
    $service->registerWithAccount([
        'name' => 'Jack Affiliate',
        'email' => 'jack@example.com',
        'password' => 'Password123!',
    ]);

    post(route('affiliates.login.submit'), [
        'email' => 'jack@example.com',
        'password' => 'WrongPassword1!',
    ])->assertSessionHasErrors('email');

    assertGuest();
});

it('allows a non-affiliate role to see the main dashboard redirect target', function () {
    $user = User::factory()->create(['role' => 'affiliate']);
    Affiliate::factory()->create(['user_id' => $user->id]);

    actingAs($user);

    get(route('dashboard'))->assertRedirect(route('affiliate.dashboard'));
});

it('shows the affiliate dashboard for an authenticated affiliate', function () {
    $user = User::factory()->create(['role' => 'affiliate']);
    $affiliate = Affiliate::factory()->active()->create(['user_id' => $user->id]);

    actingAs($affiliate->user);

    get(route('affiliate.dashboard'))
        ->assertOk()
        ->assertSee($affiliate->name)
        ->assertSee($affiliate->code);
});

it('does not allow non-affiliates to view the affiliate dashboard', function () {
    $user = User::factory()->create(['role' => 'school_admin']);

    actingAs($user);

    get(route('affiliate.dashboard'))->assertForbidden();
});

it('only shows the super admin affiliate management pages to super admins', function () {
    $user = User::factory()->create(['role' => 'school_admin']);

    actingAs($user);

    get(route('affiliates.index'))->assertForbidden();
});

it('renders the super admin affiliate management pages', function () {
    $admin = User::factory()->create(['role' => 'super_admin']);
    $affiliate = Affiliate::factory()->active()->create([
        'user_id' => User::factory()->create(['role' => 'affiliate'])->id,
    ]);

    actingAs($admin);

    get(route('affiliates.index'))
        ->assertOk()
        ->assertSee($affiliate->name);

    get(route('affiliates.show', $affiliate))
        ->assertOk()
        ->assertSee($affiliate->code);

    get(route('affiliates.settings'))->assertOk()->assertSee('Default Commission Rate');

    get(route('payouts.index'))->assertOk();
});

it('lets an active affiliate request a payout', function () {
    $user = User::factory()->create(['role' => 'affiliate']);
    $affiliate = Affiliate::factory()->active()->create(['user_id' => $user->id]);

    $school = School::create([
        'name' => 'Test School',
        'slug' => 'test-school',
        'email' => 'test@school.com',
        'is_active' => true,
        'registration_status' => 'approved',
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

    Commission::create([
        'affiliate_id' => $affiliate->id,
        'referral_id' => $referral->id,
        'amount' => 50000,
        'rate' => 20.00,
        'type' => 'first_payment',
        'status' => 'approved',
        'paid_period' => now()->format('Y-m'),
    ]);

    actingAs($user);

    post(route('affiliate.payouts.request'), [
        'amount' => '50000',
        'method' => 'bank_transfer',
        'payout_details' => 'Account details',
    ])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect(Payout::where('affiliate_id', $affiliate->id)->where('status', 'pending')->count())->toBe(1);
});

it('rejects a payout below the minimum amount', function () {
    $user = User::factory()->create(['role' => 'affiliate']);
    $affiliate = Affiliate::factory()->active()->create(['user_id' => $user->id]);

    actingAs($user);

    post(route('affiliate.payouts.request'), [
        'amount' => '100',
        'method' => 'cash',
    ])
        ->assertSessionHasErrors('amount');

    expect(Payout::where('affiliate_id', $affiliate->id)->count())->toBe(0);
});

it('blocks payout requests for pending affiliates', function () {
    $user = User::factory()->create(['role' => 'affiliate']);
    Affiliate::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

    actingAs($user);

    post(route('affiliate.payouts.request'), [
        'amount' => '50000',
        'method' => 'cash',
    ])->assertForbidden();
});
