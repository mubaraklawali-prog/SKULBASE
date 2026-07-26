<?php

use App\Models\Plan;
use App\Models\School;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->plan = Plan::create([
        'name' => 'Premium',
        'slug' => 'premium',
        'monthly_price' => 10000,
        'yearly_price' => 100000,
        'student_limit' => 1000,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $this->superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'super@skulbase.com',
        'password' => bcrypt('password'),
    ]);
    $this->superAdmin->forceFill(['role' => 'super_admin'])->save();
});

it('displays the public registration form', function (): void {
    $response = $this->get(route('school.register'));

    $response->assertOk();
    $response->assertSee('Register Your School');
    $response->assertSee('School Information');
    $response->assertSee('School Administrator');
    $response->assertSee('Subscription');
});

it('shows active plans on registration form', function (): void {
    $response = $this->get(route('school.register'));

    $response->assertOk();
    $response->assertSee('Premium');
});

it('validates required fields on registration', function (): void {
    $response = $this->post(route('school.register.submit'), []);

    $response->assertSessionHasErrors([
        'school_name',
        'school_email',
        'admin_name',
        'admin_email',
        'plan_id',
        'terms',
    ]);
});

it('validates email uniqueness for school', function (): void {
    School::create([
        'name' => 'Existing School',
        'slug' => 'existing-school',
        'email' => 'existing@test.com',
        'is_active' => true,
    ]);

    $response = $this->post(route('school.register.submit'), [
        'school_name' => 'New School',
        'school_email' => 'existing@test.com',
        'admin_name' => 'Admin User',
        'admin_email' => 'admin@test.com',
        'plan_id' => $this->plan->id,
        'terms' => '1',
    ]);

    $response->assertSessionHasErrors('school_email');
});

it('validates email uniqueness for admin user', function (): void {
    User::create([
        'name' => 'Existing User',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post(route('school.register.submit'), [
        'school_name' => 'New School',
        'school_email' => 'school@test.com',
        'admin_name' => 'Admin User',
        'admin_email' => 'admin@test.com',
        'plan_id' => $this->plan->id,
        'terms' => '1',
    ]);

    $response->assertSessionHasErrors('admin_email');
});

it('validates plan exists', function (): void {
    $response = $this->post(route('school.register.submit'), [
        'school_name' => 'New School',
        'school_email' => 'school@test.com',
        'admin_name' => 'Admin User',
        'admin_email' => 'admin@test.com',
        'plan_id' => 9999,
        'terms' => '1',
    ]);

    $response->assertSessionHasErrors('plan_id');
});

it('validates terms acceptance', function (): void {
    $response = $this->post(route('school.register.submit'), [
        'school_name' => 'New School',
        'school_email' => 'school@test.com',
        'admin_name' => 'Admin User',
        'admin_email' => 'admin@test.com',
        'plan_id' => $this->plan->id,
    ]);

    $response->assertSessionHasErrors('terms');
});

it('successfully registers a school with pending status', function (): void {
    $response = $this->post(route('school.register.submit'), [
        'school_name' => 'Greenfield Academy',
        'school_type' => 'Secondary',
        'school_email' => 'greenfield@test.com',
        'school_phone' => '08012345678',
        'school_address' => '123 Education Lane',
        'admin_name' => 'John Doe',
        'admin_email' => 'john@greenfield.com',
        'admin_phone' => '08098765432',
        'plan_id' => $this->plan->id,
        'terms' => '1',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('registration_success');

    $school = School::where('email', 'greenfield@test.com')->first();
    expect($school)->not->toBeNull();
    expect($school->name)->toBe('Greenfield Academy');
    expect($school->school_type)->toBe('Secondary');
    expect($school->registration_status)->toBe('pending');
    expect($school->is_active)->toBeFalse();
    expect($school->registered_at)->not->toBeNull();

    $admin = User::where('email', 'john@greenfield.com')->first();
    expect($admin)->not->toBeNull();
    expect($admin->role)->toBe('school_admin');
    expect($admin->school_id)->toBe($school->id);
});

it('generates unique slug for school', function (): void {
    School::create([
        'name' => 'Greenfield Academy',
        'slug' => 'greenfield-academy',
        'is_active' => true,
    ]);

    $this->post(route('school.register.submit'), [
        'school_name' => 'Greenfield Academy',
        'school_email' => 'greenfield2@test.com',
        'admin_name' => 'Admin',
        'admin_email' => 'admin@greenfield2.com',
        'plan_id' => $this->plan->id,
        'terms' => '1',
    ]);

    $school = School::where('email', 'greenfield2@test.com')->first();
    expect($school->slug)->toBe('greenfield-academy-1');
});

it('sets school as inactive and pending on registration', function (): void {
    $this->post(route('school.register.submit'), [
        'school_name' => 'New Academy',
        'school_email' => 'new@test.com',
        'admin_name' => 'Admin',
        'admin_email' => 'admin@new.com',
        'plan_id' => $this->plan->id,
        'terms' => '1',
    ]);

    $school = School::where('email', 'new@test.com')->first();
    expect($school->is_active)->toBeFalse();
    expect($school->registration_status)->toBe('pending');
    expect($school->registered_at)->not->toBeNull();
});

// ── Login Protection Tests ──────────────────────────────

it('blocks pending school admin from logging in', function (): void {
    $school = School::create([
        'name' => 'Pending School',
        'slug' => 'pending-school',
        'email' => 'pending@test.com',
        'is_active' => false,
        'registration_status' => 'pending',
        'registered_at' => now(),
    ]);

    $admin = User::create([
        'name' => 'Pending Admin',
        'email' => 'pending_admin@test.com',
        'password' => bcrypt('password'),
    ]);
    $admin->forceFill(['role' => 'school_admin', 'school_id' => $school->id])->save();

    $response = $this->post(route('login'), [
        'email' => 'pending_admin@test.com',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('pending_approval');
    $this->assertGuest();
});

it('blocks rejected school admin from logging in', function (): void {
    $school = School::create([
        'name' => 'Rejected School',
        'slug' => 'rejected-school',
        'email' => 'rejected@test.com',
        'is_active' => false,
        'registration_status' => 'rejected',
        'rejected_at' => now(),
        'rejection_reason' => 'Incomplete documentation',
    ]);

    $admin = User::create([
        'name' => 'Rejected Admin',
        'email' => 'rejected_admin@test.com',
        'password' => bcrypt('password'),
    ]);
    $admin->forceFill(['role' => 'school_admin', 'school_id' => $school->id])->save();

    $response = $this->post(route('login'), [
        'email' => 'rejected_admin@test.com',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('rejected');
    $this->assertGuest();
});

it('allows approved school admin to log in', function (): void {
    $school = School::create([
        'name' => 'Approved School',
        'slug' => 'approved-school',
        'email' => 'approved@test.com',
        'is_active' => true,
        'registration_status' => 'approved',
        'approved_at' => now(),
    ]);

    Subscription::create([
        'school_id' => $school->id,
        'plan_id' => $this->plan->id,
        'billing_cycle' => 'monthly',
        'status' => 'trial',
        'starts_at' => now(),
        'trial_starts_at' => now(),
        'trial_ends_at' => now()->addDays(30),
        'is_trial' => true,
    ]);

    $admin = User::create([
        'name' => 'Approved Admin',
        'email' => 'approved_admin@test.com',
        'password' => bcrypt('password'),
    ]);
    $admin->forceFill(['role' => 'school_admin', 'school_id' => $school->id])->save();

    $response = $this->post(route('login'), [
        'email' => 'approved_admin@test.com',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($admin);
});

it('redirects pending school admin when accessing dashboard', function (): void {
    $school = School::create([
        'name' => 'Pending School',
        'slug' => 'pending-school-2',
        'email' => 'pending2@test.com',
        'is_active' => false,
        'registration_status' => 'pending',
        'registered_at' => now(),
    ]);

    $admin = User::create([
        'name' => 'Pending Admin 2',
        'email' => 'pending_admin2@test.com',
        'password' => bcrypt('password'),
    ]);
    $admin->forceFill(['role' => 'school_admin', 'school_id' => $school->id])->save();

    $this->actingAs($admin);

    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
    $this->assertGuest();
});

// ── Super Admin Pending Schools Tests ───────────────────

it('super admin can view pending schools list', function (): void {
    $this->actingAs($this->superAdmin);

    $response = $this->get(route('pending-schools.index'));

    $response->assertOk();
    $response->assertSee('Pending Schools');
});

it('non-super-admin cannot view pending schools', function (): void {
    $school = School::create([
        'name' => 'Test School',
        'slug' => 'test-school',
        'is_active' => true,
    ]);

    Subscription::create([
        'school_id' => $school->id,
        'plan_id' => $this->plan->id,
        'billing_cycle' => 'monthly',
        'status' => 'trial',
        'starts_at' => now(),
        'trial_starts_at' => now(),
        'trial_ends_at' => now()->addDays(30),
        'is_trial' => true,
    ]);

    $admin = User::create([
        'name' => 'School Admin',
        'email' => 'school_admin@test.com',
        'password' => bcrypt('password'),
    ]);
    $admin->forceFill(['role' => 'school_admin', 'school_id' => $school->id])->save();

    $this->actingAs($admin);

    $response = $this->get(route('pending-schools.index'));

    $response->assertForbidden();
});

it('super admin sees pending schools in list', function (): void {
    $pendingSchool = School::create([
        'name' => 'Pending Academy',
        'slug' => 'pending-academy',
        'email' => 'pending_ac@test.com',
        'is_active' => false,
        'registration_status' => 'pending',
        'registered_at' => now(),
    ]);

    $this->actingAs($this->superAdmin);

    $response = $this->get(route('pending-schools.index'));

    $response->assertOk();
    $response->assertSee('Pending Academy');
});

it('super admin can view pending school details', function (): void {
    $school = School::create([
        'name' => 'Detail School',
        'slug' => 'detail-school',
        'email' => 'detail@test.com',
        'is_active' => false,
        'registration_status' => 'pending',
        'registered_at' => now(),
    ]);

    $admin = User::create([
        'name' => 'Detail Admin',
        'email' => 'detail_admin@test.com',
        'password' => bcrypt('password'),
    ]);
    $admin->forceFill(['role' => 'school_admin', 'school_id' => $school->id])->save();

    $this->actingAs($this->superAdmin);

    $response = $this->get(route('pending-schools.show', $school));

    $response->assertOk();
    $response->assertSee('Detail School');
    $response->assertSee('Detail Admin');
});

// ── Approval Tests ──────────────────────────────────────

it('super admin can approve a pending school', function (): void {
    $school = School::create([
        'name' => 'Approve School',
        'slug' => 'approve-school',
        'email' => 'approve@test.com',
        'is_active' => false,
        'registration_status' => 'pending',
        'registered_at' => now(),
    ]);

    $this->actingAs($this->superAdmin);

    $response = $this->post(route('pending-schools.approve', $school));

    $response->assertRedirect(route('pending-schools.index'));
    $response->assertSessionHas('success');

    $school->refresh();
    expect($school->registration_status)->toBe('approved');
    expect($school->is_active)->toBeTrue();
    expect($school->approved_at)->not->toBeNull();
});

it('approval creates a trial subscription', function (): void {
    $school = School::create([
        'name' => 'Trial School',
        'slug' => 'trial-school',
        'email' => 'trial@test.com',
        'is_active' => false,
        'registration_status' => 'pending',
        'registered_at' => now(),
    ]);

    $this->actingAs($this->superAdmin);

    $this->post(route('pending-schools.approve', $school));

    $subscription = Subscription::where('school_id', $school->id)->first();
    expect($subscription)->not->toBeNull();
    expect($subscription->status)->toBe('trial');
    expect($subscription->is_trial)->toBeTrue();
});

it('cannot approve a non-pending school', function (): void {
    $school = School::create([
        'name' => 'Active School',
        'slug' => 'active-school',
        'email' => 'active@test.com',
        'is_active' => true,
    ]);

    $this->actingAs($this->superAdmin);

    $response = $this->post(route('pending-schools.approve', $school));

    $response->assertRedirect();
    $response->assertSessionHas('error');
});

it('approved school admin can login after approval', function (): void {
    $school = School::create([
        'name' => 'Login After Approval',
        'slug' => 'login-after-approval',
        'email' => 'login_after@test.com',
        'is_active' => false,
        'registration_status' => 'pending',
        'registered_at' => now(),
    ]);

    $admin = User::create([
        'name' => 'Approval Admin',
        'email' => 'approval_admin@test.com',
        'password' => bcrypt('password'),
    ]);
    $admin->forceFill(['role' => 'school_admin', 'school_id' => $school->id])->save();

    $this->actingAs($this->superAdmin);
    $this->post(route('pending-schools.approve', $school));

    $this->post(route('logout'));
    $this->assertGuest();

    $response = $this->post(route('login'), [
        'email' => 'approval_admin@test.com',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('password.change'));
});

// ── Rejection Tests ─────────────────────────────────────

it('super admin can reject a pending school with reason', function (): void {
    $school = School::create([
        'name' => 'Reject School',
        'slug' => 'reject-school',
        'email' => 'reject@test.com',
        'is_active' => false,
        'registration_status' => 'pending',
        'registered_at' => now(),
    ]);

    $this->actingAs($this->superAdmin);

    $response = $this->post(route('pending-schools.reject', $school), [
        'rejection_reason' => 'Incomplete documentation provided.',
    ]);

    $response->assertRedirect(route('pending-schools.index'));
    $response->assertSessionHas('success');

    $school->refresh();
    expect($school->registration_status)->toBe('rejected');
    expect($school->rejected_at)->not->toBeNull();
    expect($school->rejection_reason)->toBe('Incomplete documentation provided.');
});

it('rejection requires a reason', function (): void {
    $school = School::create([
        'name' => 'Reject School 2',
        'slug' => 'reject-school-2',
        'email' => 'reject2@test.com',
        'is_active' => false,
        'registration_status' => 'pending',
        'registered_at' => now(),
    ]);

    $this->actingAs($this->superAdmin);

    $response = $this->post(route('pending-schools.reject', $school), [
        'rejection_reason' => '',
    ]);

    $response->assertSessionHasErrors('rejection_reason');
});

it('cannot reject a non-pending school', function (): void {
    $school = School::create([
        'name' => 'Active School 2',
        'slug' => 'active-school-2',
        'email' => 'active2@test.com',
        'is_active' => true,
    ]);

    $this->actingAs($this->superAdmin);

    $response = $this->post(route('pending-schools.reject', $school), [
        'rejection_reason' => 'Test reason',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');
});

// ── Existing Functionality Preserved ────────────────────

it('super admin can still create school directly', function (): void {
    $this->actingAs($this->superAdmin);

    $response = $this->post(route('schools.store'), [
        'name' => 'Direct Created School',
        'slug' => 'direct-created-school',
        'email' => 'direct@test.com',
        'phone' => '08012345678',
        'address' => '123 Main St',
        'city' => 'Lagos',
        'state' => 'Lagos',
        'country' => 'Nigeria',
    ]);

    $response->assertRedirect(route('schools.index'));
    $response->assertSessionHas('success');

    $school = School::where('slug', 'direct-created-school')->first();
    expect($school)->not->toBeNull();
    expect($school->is_active)->toBeTrue();
    // Direct-created schools should not have registration_status set
    expect($school->registration_status)->toBeNull();

    $subscription = Subscription::where('school_id', $school->id)->first();
    expect($subscription)->not->toBeNull();
    expect($subscription->status)->toBe('trial');
});

it('super admin can toggle school status', function (): void {
    $school = School::create([
        'name' => 'Toggle School',
        'slug' => 'toggle-school',
        'email' => 'toggle@test.com',
        'is_active' => true,
    ]);

    $this->actingAs($this->superAdmin);

    $response = $this->patch(route('schools.toggle-status', $school));

    $response->assertRedirect(route('schools.index'));
    $school->refresh();
    expect($school->is_active)->toBeFalse();
});

// ── Search & Pagination Tests ───────────────────────────

it('super admin can search pending schools', function (): void {
    School::create([
        'name' => 'Searchable Academy',
        'slug' => 'searchable-academy',
        'email' => 'searchable@test.com',
        'is_active' => false,
        'registration_status' => 'pending',
        'registered_at' => now(),
    ]);

    School::create([
        'name' => 'Other Academy',
        'slug' => 'other-academy',
        'email' => 'other@test.com',
        'is_active' => false,
        'registration_status' => 'pending',
        'registered_at' => now(),
    ]);

    $this->actingAs($this->superAdmin);

    $response = $this->get(route('pending-schools.index', ['search' => 'Searchable']));

    $response->assertOk();
    $response->assertSee('Searchable Academy');
    $response->assertDontSee('Other Academy');
});

// ── Middleware Protection Tests ─────────────────────────

it('guest cannot access pending schools', function (): void {
    $response = $this->get(route('pending-schools.index'));

    expect($response->status())->not->toBe(200);
    $response->assertDontSee('Pending Schools');
});

it('school admin cannot access pending schools', function (): void {
    $school = School::create([
        'name' => 'Regular School',
        'slug' => 'regular-school',
        'email' => 'regular@test.com',
        'is_active' => true,
    ]);

    Subscription::create([
        'school_id' => $school->id,
        'plan_id' => $this->plan->id,
        'billing_cycle' => 'monthly',
        'status' => 'trial',
        'starts_at' => now(),
        'trial_starts_at' => now(),
        'trial_ends_at' => now()->addDays(30),
        'is_trial' => true,
    ]);

    $admin = User::create([
        'name' => 'Regular Admin',
        'email' => 'regular_admin@test.com',
        'password' => bcrypt('password'),
    ]);
    $admin->forceFill(['role' => 'school_admin', 'school_id' => $school->id])->save();

    $this->actingAs($admin);

    $response = $this->get(route('pending-schools.index'));

    $response->assertForbidden();
});

// ── Welcome Page Tests ──────────────────────────────────

it('welcome page shows register and login links', function (): void {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('Register Your School');
    $response->assertSee(route('school.register'));
    $response->assertSee('Login');
    $response->assertSee(route('login'));
});

it('login page shows register school link', function (): void {
    $response = $this->get(route('login'));

    $response->assertOk();
    $response->assertSee('Register your school');
    $response->assertSee(route('school.register'));
});
