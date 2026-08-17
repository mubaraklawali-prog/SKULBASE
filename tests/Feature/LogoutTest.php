<?php

use App\Models\Plan;
use App\Models\School;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->school = School::create([
        'name' => 'Logout Test School',
        'slug' => 'logout-test-school',
        'is_active' => true,
    ]);

    $plan = Plan::create([
        'name' => 'Premium',
        'slug' => 'premium',
        'monthly_price' => 10000,
        'yearly_price' => 100000,
        'student_limit' => 1000,
        'is_unlimited' => false,
        'is_active' => true,
    ]);

    Subscription::create([
        'school_id' => $this->school->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'yearly',
        'status' => 'active',
        'starts_at' => now()->subMonth(),
        'expires_at' => now()->addYear(),
        'is_trial' => false,
        'amount_paid' => 100000,
    ]);
});

it('logs out super admin and redirects to login', function (): void {
    $user = User::create([
        'name' => 'Super Admin',
        'email' => 'super@skulbase.com',
        'password' => bcrypt('password'),
    ]);
    $user->forceFill(['role' => 'super_admin'])->save();

    $this->actingAs($user);
    $this->assertAuthenticated();

    $this->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

it('logs out school admin and redirects to login', function (): void {
    $user = User::create([
        'name' => 'School Admin',
        'email' => 'admin@logout.com',
        'password' => bcrypt('password'),
    ]);
    $user->forceFill(['role' => 'school_admin', 'school_id' => $this->school->id])->save();

    $this->actingAs($user);
    $this->assertAuthenticated();

    $this->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

it('logs out teacher and redirects to login', function (): void {
    $user = User::create([
        'name' => 'Teacher',
        'email' => 'teacher@logout.com',
        'password' => bcrypt('password'),
    ]);
    $user->forceFill(['role' => 'teacher', 'school_id' => $this->school->id])->save();

    $this->actingAs($user);
    $this->assertAuthenticated();

    $this->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

it('logs out parent and redirects to login', function (): void {
    $user = User::create([
        'name' => 'Parent',
        'email' => 'parent@logout.com',
        'password' => bcrypt('password'),
    ]);
    $user->forceFill(['role' => 'parent', 'school_id' => $this->school->id])->save();

    $this->actingAs($user);
    $this->assertAuthenticated();

    $this->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

it('logs out student and redirects to login', function (): void {
    $user = User::create([
        'name' => 'Student',
        'email' => 'student@logout.com',
        'password' => bcrypt('password'),
    ]);
    $user->forceFill(['role' => 'student', 'school_id' => $this->school->id])->save();

    $this->actingAs($user);
    $this->assertAuthenticated();

    $this->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

it('logs out affiliate and redirects to login', function (): void {
    $user = User::create([
        'name' => 'Affiliate',
        'email' => 'affiliate@logout.com',
        'password' => bcrypt('password'),
    ]);
    $user->forceFill(['role' => 'affiliate'])->save();

    $this->actingAs($user);
    $this->assertAuthenticated();

    $this->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

it('invalidates the session after logout', function (): void {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'session@logout.com',
        'password' => bcrypt('password'),
    ]);
    $user->forceFill(['role' => 'super_admin'])->save();

    $this->actingAs($user);

    $sessionId = Session::getId();

    $this->post(route('logout'))->assertRedirect(route('login'));

    $this->assertGuest();
    $this->assertNotEquals($sessionId, Session::getId());
});

it('user is guest after logout and cannot be re-authenticated without login', function (): void {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'protect@logout.com',
        'password' => bcrypt('password'),
    ]);
    $user->forceFill(['role' => 'super_admin'])->save();

    $this->actingAs($user);
    $this->assertAuthenticated();

    $this->post(route('logout'));
    $this->assertGuest();

    $this->assertNotEquals($user->id, auth()->id());
});

it('can logout immediately after login', function (): void {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'quick@logout.com',
        'password' => bcrypt('password'),
    ]);
    $user->forceFill(['role' => 'super_admin'])->save();

    $this->post(route('login'), [
        'email' => 'quick@logout.com',
        'password' => 'password',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);

    $this->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

it('logout route only requires auth not subscription or school approval', function (): void {
    $pendingSchool = School::create([
        'name' => 'Pending School',
        'slug' => 'pending-school',
        'is_active' => false,
        'registration_status' => 'pending',
    ]);

    $user = User::create([
        'name' => 'Pending Admin',
        'email' => 'pending@logout.com',
        'password' => bcrypt('password'),
    ]);
    $user->forceFill(['role' => 'school_admin', 'school_id' => $pendingSchool->id])->save();

    $this->actingAs($user);

    $this->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

it('school admin with no subscription can still logout', function (): void {
    $noSubSchool = School::create([
        'name' => 'No Sub School',
        'slug' => 'no-sub-school',
        'is_active' => true,
    ]);

    $user = User::create([
        'name' => 'No Sub Admin',
        'email' => 'nosub@logout.com',
        'password' => bcrypt('password'),
    ]);
    $user->forceFill(['role' => 'school_admin', 'school_id' => $noSubSchool->id])->save();

    $this->actingAs($user);

    $this->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});
