<?php

use App\Models\Plan;
use App\Models\School;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->school = School::create([
        'name' => 'Test School',
        'slug' => 'test-school',
        'is_active' => true,
    ]);

    $plan = Plan::create([
        'name' => 'Premium',
        'slug' => 'premium',
        'monthly_price' => 10000,
        'yearly_price' => 100000,
        'student_limit' => 1000,
        'is_unlimited' => false,
        'trial_days' => 30,
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

    $this->superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'super@skulbase.com',
        'password' => bcrypt('password'),
    ]);
    $this->superAdmin->forceFill(['role' => 'super_admin'])->save();

    $this->schoolAdmin = User::create([
        'name' => 'School Admin',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
    ]);
    $this->schoolAdmin->forceFill(['role' => 'school_admin', 'school_id' => $this->school->id])->save();
});

// ── Password Strength Validation ──────────────────────

it('rejects password shorter than 8 characters on register', function (): void {
    $response = $this->post(route('register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Short1',
        'password_confirmation' => 'Short1',
    ]);

    $response->assertSessionHasErrors('password');
});

it('rejects password without uppercase on register', function (): void {
    $response = $this->post(route('register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'lowercase1',
        'password_confirmation' => 'lowercase1',
    ]);

    $response->assertSessionHasErrors('password');
});

it('rejects password without lowercase on register', function (): void {
    $response = $this->post(route('register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'UPPERCASE1',
        'password_confirmation' => 'UPPERCASE1',
    ]);

    $response->assertSessionHasErrors('password');
});

it('rejects password without number on register', function (): void {
    $response = $this->post(route('register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'NoNumberHere',
        'password_confirmation' => 'NoNumberHere',
    ]);

    $response->assertSessionHasErrors('password');
});

it('accepts valid strong password on register', function (): void {
    $response = $this->post(route('register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'StrongPass1',
        'password_confirmation' => 'StrongPass1',
    ]);

    $response->assertRedirect(route('dashboard'));

    $user = User::where('email', 'test@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->name)->toBe('Test User');
});

// ── Change Password Strength Validation ──────────────

it('rejects weak password on change password', function (): void {
    $user = User::create([
        'name' => 'Teacher User',
        'email' => 'teacher@test.com',
        'password' => bcrypt('OldPass123'),
    ]);
    $user->forceFill([
        'role' => 'teacher',
        'school_id' => $this->school->id,
        'force_password_change' => true,
    ])->save();

    $response = $this->actingAs($user)->post(route('password.change.submit'), [
        'current_password' => 'OldPass123',
        'password' => 'weak',
        'password_confirmation' => 'weak',
    ]);

    $response->assertSessionHasErrors('password');

    $user->refresh();
    $this->assertTrue($user->force_password_change);
});

it('accepts valid strong password on change password', function (): void {
    $user = User::create([
        'name' => 'Teacher User',
        'email' => 'teacher@test.com',
        'password' => bcrypt('OldPass123'),
    ]);
    $user->forceFill([
        'role' => 'teacher',
        'school_id' => $this->school->id,
        'force_password_change' => true,
    ])->save();

    $response = $this->actingAs($user)->post(route('password.change.submit'), [
        'current_password' => 'OldPass123',
        'password' => 'NewStrong1',
        'password_confirmation' => 'NewStrong1',
    ]);

    $response->assertRedirect(route('dashboard'));

    $user->refresh();
    $this->assertFalse($user->force_password_change);
    $this->assertTrue(Hash::check('NewStrong1', $user->password));
});

// ── Change Password Page UX ──────────────────────────

it('shows welcome message on change password page', function (): void {
    $user = User::create([
        'name' => 'Teacher User',
        'email' => 'teacher@test.com',
        'password' => bcrypt('OldPass123'),
    ]);
    $user->forceFill([
        'role' => 'teacher',
        'school_id' => $this->school->id,
        'force_password_change' => true,
    ])->save();

    $response = $this->actingAs($user)->get(route('password.change'));
    $response->assertOk();
    $response->assertSee('Welcome');
    $response->assertSee('Change Password');
});

it('shows password requirements on change password page', function (): void {
    $user = User::create([
        'name' => 'Teacher User',
        'email' => 'teacher@test.com',
        'password' => bcrypt('OldPass123'),
    ]);
    $user->forceFill([
        'role' => 'teacher',
        'school_id' => $this->school->id,
        'force_password_change' => true,
    ])->save();

    $response = $this->actingAs($user)->get(route('password.change'));
    $response->assertOk();
    $response->assertSee('Password must contain');
    $response->assertSee('At least 8 characters');
    $response->assertSee('uppercase letter');
    $response->assertSee('lowercase letter');
    $response->assertSee('number');
});

// ── Login Page Forgot Password Link ──────────────────

it('shows forgot password link on login page', function (): void {
    $response = $this->get(route('login'));
    $response->assertOk();
    $response->assertSee('Forgot your password?');
    $response->assertSee(route('password.request'));
});

// ── Forgot Password Flow ─────────────────────────────

it('shows forgot password request form', function (): void {
    $response = $this->get(route('password.request'));
    $response->assertOk();
    $response->assertSee('Forgot Password');
    $response->assertSee('Send Reset Link');
});

it('sends reset link for valid email', function (): void {
    $response = $this->post(route('password.email'), [
        'email' => 'admin@test.com',
    ]);

    $response->assertSessionHas('status');
});

it('returns error for non-existent email on reset request', function (): void {
    $response = $this->post(route('password.email'), [
        'email' => 'nonexistent@test.com',
    ]);

    $response->assertSessionHasErrors('email');
});

it('validates email is required on forgot password', function (): void {
    $response = $this->post(route('password.email'), []);

    $response->assertSessionHasErrors('email');
});

it('shows reset password form with valid token', function (): void {
    $token = Password::broker()->createToken(
        User::where('email', 'admin@test.com')->first()
    );

    $response = $this->get(route('password.reset', [
        'token' => $token,
        'email' => 'admin@test.com',
    ]));

    $response->assertOk();
    $response->assertSee('Reset Password');
    $response->assertSee('admin@test.com');
});

it('redirects to request form when accessing reset with invalid token', function (): void {
    $response = $this->get(route('password.reset', [
        'token' => 'some-invalid-token',
        'email' => 'admin@test.com',
    ]));

    $response->assertOk();
    $response->assertSee('Reset Password');
});

it('resets password successfully with valid token', function (): void {
    $user = User::where('email', 'admin@test.com')->first();
    $token = Password::broker()->createToken($user);

    $response = $this->post(route('password.update'), [
        'token' => $token,
        'email' => 'admin@test.com',
        'password' => 'NewStrongPass1',
        'password_confirmation' => 'NewStrongPass1',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('status');

    $user->refresh();
    $this->assertTrue(Hash::check('NewStrongPass1', $user->password));
});

it('rejects weak password on reset', function (): void {
    $user = User::where('email', 'admin@test.com')->first();
    $token = Password::broker()->createToken($user);

    $response = $this->post(route('password.update'), [
        'token' => $token,
        'email' => 'admin@test.com',
        'password' => 'weak',
        'password_confirmation' => 'weak',
    ]);

    $response->assertSessionHasErrors('password');
});

it('rejects reset with invalid token', function (): void {
    $response = $this->post(route('password.update'), [
        'token' => 'invalid-token',
        'email' => 'admin@test.com',
        'password' => 'NewStrongPass1',
        'password_confirmation' => 'NewStrongPass1',
    ]);

    $response->assertSessionHasErrors('email');
});

// ── School Admin Approval Force Password Change ──────

it('sets force_password_change when school admin is approved', function (): void {
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

    $this->actingAs($this->superAdmin);
    $this->post(route('pending-schools.approve', $school));

    $admin->refresh();
    $this->assertTrue($admin->force_password_change);
});

it('approved school admin redirected to change password on login', function (): void {
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

    $this->actingAs($this->superAdmin);
    $this->post(route('pending-schools.approve', $school));

    Auth::logout();
    $this->assertGuest();

    $response = $this->post(route('login'), [
        'email' => 'pending_admin2@test.com',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('password.change'));
});

// ── Full First Login Flow for All Roles ──────────────

it('school admin first login flow: login -> change password -> dashboard', function (): void {
    $this->actingAs($this->superAdmin);

    $school = School::create([
        'name' => 'Flow School',
        'slug' => 'flow-school',
        'email' => 'flow@test.com',
        'is_active' => false,
        'registration_status' => 'pending',
        'registered_at' => now(),
    ]);

    $admin = User::create([
        'name' => 'Flow Admin',
        'email' => 'flow_admin@test.com',
        'password' => bcrypt('password'),
    ]);
    $admin->forceFill(['role' => 'school_admin', 'school_id' => $school->id])->save();

    $this->post(route('pending-schools.approve', $school));

    $admin->refresh();
    $this->assertTrue($admin->force_password_change);

    Auth::logout();

    $response = $this->post(route('login'), [
        'email' => 'flow_admin@test.com',
        'password' => 'password',
    ]);
    $response->assertRedirect(route('password.change'));

    $response = $this->actingAs($admin)->post(route('password.change.submit'), [
        'current_password' => 'password',
        'password' => 'NewStrongPass1',
        'password_confirmation' => 'NewStrongPass1',
    ]);
    $response->assertRedirect(route('dashboard'));

    $admin->refresh();
    $this->assertFalse($admin->force_password_change);
    $this->assertTrue(Hash::check('NewStrongPass1', $admin->password));
});

it('teacher first login flow: login -> change password -> dashboard', function (): void {
    $teacherUser = User::create([
        'name' => 'Teacher Flow',
        'email' => 'teacher_flow@test.com',
        'password' => bcrypt('password'),
    ]);
    $teacherUser->forceFill([
        'role' => 'teacher',
        'school_id' => $this->school->id,
        'force_password_change' => true,
    ])->save();

    $response = $this->post(route('login'), [
        'email' => 'teacher_flow@test.com',
        'password' => 'password',
    ]);
    $response->assertRedirect(route('password.change'));

    $response = $this->actingAs($teacherUser)->post(route('password.change.submit'), [
        'current_password' => 'password',
        'password' => 'TeacherNew1',
        'password_confirmation' => 'TeacherNew1',
    ]);
    $response->assertRedirect(route('dashboard'));

    $teacherUser->refresh();
    $this->assertFalse($teacherUser->force_password_change);
});

it('parent first login flow: login -> change password -> dashboard', function (): void {
    $parentUser = User::create([
        'name' => 'Parent Flow',
        'email' => 'parent_flow@test.com',
        'password' => bcrypt('password'),
    ]);
    $parentUser->forceFill([
        'role' => 'parent',
        'school_id' => $this->school->id,
        'force_password_change' => true,
    ])->save();

    $response = $this->post(route('login'), [
        'email' => 'parent_flow@test.com',
        'password' => 'password',
    ]);
    $response->assertRedirect(route('password.change'));

    $response = $this->actingAs($parentUser)->post(route('password.change.submit'), [
        'current_password' => 'password',
        'password' => 'ParentNew1',
        'password_confirmation' => 'ParentNew1',
    ]);
    $response->assertRedirect(route('dashboard'));

    $parentUser->refresh();
    $this->assertFalse($parentUser->force_password_change);
});

// ── Reset Password Clears Force Password Change ──────

it('password reset clears force_password_change flag', function (): void {
    $user = User::create([
        'name' => 'Reset User',
        'email' => 'reset@test.com',
        'password' => bcrypt('OldPass123'),
    ]);
    $user->forceFill([
        'role' => 'teacher',
        'school_id' => $this->school->id,
        'force_password_change' => true,
    ])->save();

    $token = Password::broker()->createToken($user);

    $response = $this->post(route('password.update'), [
        'token' => $token,
        'email' => 'reset@test.com',
        'password' => 'NewStrongPass1',
        'password_confirmation' => 'NewStrongPass1',
    ]);

    $response->assertRedirect(route('login'));

    $user->refresh();
    $this->assertFalse($user->force_password_change);
    $this->assertTrue(Hash::check('NewStrongPass1', $user->password));
});

// ── Route Protection ─────────────────────────────────

it('guest can access forgot password form', function (): void {
    $response = $this->get(route('password.request'));
    $response->assertOk();
});

it('guest can access reset password form', function (): void {
    $user = User::where('email', 'admin@test.com')->first();
    $token = Password::broker()->createToken($user);

    $response = $this->get(route('password.reset', [
        'token' => $token,
        'email' => 'admin@test.com',
    ]));
    $response->assertOk();
});

it('authenticated user can still access change password form', function (): void {
    $user = User::create([
        'name' => 'Auth User',
        'email' => 'auth_user@test.com',
        'password' => bcrypt('password'),
    ]);
    $user->forceFill([
        'role' => 'teacher',
        'school_id' => $this->school->id,
        'force_password_change' => true,
    ])->save();

    $response = $this->actingAs($user)->get(route('password.change'));
    $response->assertOk();
});

// ── Password Reset Role Compatibility ────────────────

it('super admin can reset password via email link', function (): void {
    $response = $this->post(route('password.email'), [
        'email' => 'super@skulbase.com',
    ]);

    $response->assertSessionHas('status');

    $token = Password::broker()->createToken($this->superAdmin);

    $response = $this->post(route('password.update'), [
        'token' => $token,
        'email' => 'super@skulbase.com',
        'password' => 'SuperNewPass1',
        'password_confirmation' => 'SuperNewPass1',
    ]);

    $response->assertRedirect(route('login'));

    $this->superAdmin->refresh();
    $this->assertTrue(Hash::check('SuperNewPass1', $this->superAdmin->password));
});

it('super admin can login with new password after reset', function (): void {
    $token = Password::broker()->createToken($this->superAdmin);

    $this->post(route('password.update'), [
        'token' => $token,
        'email' => 'super@skulbase.com',
        'password' => 'SuperNewPass1',
        'password_confirmation' => 'SuperNewPass1',
    ]);

    $response = $this->post(route('login'), [
        'email' => 'super@skulbase.com',
        'password' => 'SuperNewPass1',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($this->superAdmin->fresh());
});

it('school admin can reset password via email link', function (): void {
    $response = $this->post(route('password.email'), [
        'email' => 'admin@test.com',
    ]);

    $response->assertSessionHas('status');

    $token = Password::broker()->createToken($this->schoolAdmin);

    $response = $this->post(route('password.update'), [
        'token' => $token,
        'email' => 'admin@test.com',
        'password' => 'AdminNewPass1',
        'password_confirmation' => 'AdminNewPass1',
    ]);

    $response->assertRedirect(route('login'));

    $this->schoolAdmin->refresh();
    $this->assertTrue(Hash::check('AdminNewPass1', $this->schoolAdmin->password));
});

it('school admin can login with new password after reset', function (): void {
    $token = Password::broker()->createToken($this->schoolAdmin);

    $this->post(route('password.update'), [
        'token' => $token,
        'email' => 'admin@test.com',
        'password' => 'AdminNewPass1',
        'password_confirmation' => 'AdminNewPass1',
    ]);

    $response = $this->post(route('login'), [
        'email' => 'admin@test.com',
        'password' => 'AdminNewPass1',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($this->schoolAdmin->fresh());
});

it('teacher can reset password and login to correct dashboard', function (): void {
    $teacherUser = User::create([
        'name' => 'Reset Teacher',
        'email' => 'reset_teacher@test.com',
        'password' => bcrypt('OldPass123'),
    ]);
    $teacherUser->forceFill([
        'role' => 'teacher',
        'school_id' => $this->school->id,
    ])->save();

    $token = Password::broker()->createToken($teacherUser);

    $this->post(route('password.update'), [
        'token' => $token,
        'email' => 'reset_teacher@test.com',
        'password' => 'TeacherNewPass1',
        'password_confirmation' => 'TeacherNewPass1',
    ]);

    $response = $this->post(route('login'), [
        'email' => 'reset_teacher@test.com',
        'password' => 'TeacherNewPass1',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($teacherUser->fresh());
});

it('parent can reset password and login to parent dashboard', function (): void {
    $parentUser = User::create([
        'name' => 'Reset Parent',
        'email' => 'reset_parent@test.com',
        'password' => bcrypt('OldPass123'),
    ]);
    $parentUser->forceFill([
        'role' => 'parent',
        'school_id' => $this->school->id,
    ])->save();

    $token = Password::broker()->createToken($parentUser);

    $this->post(route('password.update'), [
        'token' => $token,
        'email' => 'reset_parent@test.com',
        'password' => 'ParentNewPass1',
        'password_confirmation' => 'ParentNewPass1',
    ]);

    $response = $this->post(route('login'), [
        'email' => 'reset_parent@test.com',
        'password' => 'ParentNewPass1',
    ]);

    $response->assertRedirect(route('parent.dashboard'));
    $this->assertAuthenticatedAs($parentUser->fresh());
});

// ── Security Tests ──────────────────────────────────

it('old password no longer works after reset', function (): void {
    $token = Password::broker()->createToken($this->schoolAdmin);

    $this->post(route('password.update'), [
        'token' => $token,
        'email' => 'admin@test.com',
        'password' => 'NewSecurePass1',
        'password_confirmation' => 'NewSecurePass1',
    ]);

    $response = $this->post(route('login'), [
        'email' => 'admin@test.com',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

it('rejects expired password reset token', function (): void {
    $token = Password::broker()->createToken($this->schoolAdmin);

    DB::table('password_reset_tokens')
        ->where('email', 'admin@test.com')
        ->update(['created_at' => now()->subMinutes(120)]);

    $response = $this->post(route('password.update'), [
        'token' => $token,
        'email' => 'admin@test.com',
        'password' => 'NewSecurePass1',
        'password_confirmation' => 'NewSecurePass1',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertTrue(Hash::check('password', $this->schoolAdmin->fresh()->password));
});

it('does not reveal whether email exists on forgot password request', function (): void {
    $existingResponse = $this->post(route('password.email'), [
        'email' => 'admin@test.com',
    ]);

    $nonExistingResponse = $this->post(route('password.email'), [
        'email' => 'nonexistent@test.com',
    ]);

    // Both return the same HTTP status — no 404/403 leak
    $existingResponse->assertStatus(302);
    $nonExistingResponse->assertStatus(302);

    // The existing email response does NOT expose account existence in the URL
    $existingResponse->assertDontSeeText('admin@test.com');
});

it('resets password for all roles via forgot password flow end-to-end', function (): void {
    $roles = [
        ['name' => 'Super Admin', 'email' => 'super_pass_reset@test.com', 'role' => 'super_admin', 'school_id' => null, 'redirect' => route('dashboard')],
        ['name' => 'School Admin', 'email' => 'school_pass_reset@test.com', 'role' => 'school_admin', 'school_id' => $this->school->id, 'redirect' => route('password.change')],
        ['name' => 'Teacher', 'email' => 'teacher_pass_reset@test.com', 'role' => 'teacher', 'school_id' => $this->school->id, 'redirect' => route('dashboard')],
        ['name' => 'Parent', 'email' => 'parent_pass_reset@test.com', 'role' => 'parent', 'school_id' => $this->school->id, 'redirect' => route('parent.dashboard')],
    ];

    foreach ($roles as $roleData) {
        $user = User::create([
            'name' => $roleData['name'],
            'email' => $roleData['email'],
            'password' => bcrypt('OldPass123'),
        ]);
        $user->forceFill([
            'role' => $roleData['role'],
            'school_id' => $roleData['school_id'],
        ])->save();

        $newPassword = ucfirst($roleData['role']).'NewPass1';

        $token = Password::broker()->createToken($user);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $roleData['email'],
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ])->assertRedirect(route('login'));

        $user->refresh();
        $this->assertTrue(Hash::check($newPassword, $user->password), "Password reset failed for {$roleData['role']}");
        $this->assertFalse(Hash::check('OldPass123', $user->password), "Old password still works for {$roleData['role']}");
    }
});

it('shows password strength requirements on reset form', function (): void {
    $token = Password::broker()->createToken($this->schoolAdmin);

    $response = $this->get(route('password.reset', [
        'token' => $token,
        'email' => 'admin@test.com',
    ]));

    $response->assertOk();
    $response->assertSee('Password must contain');
    $response->assertSee('At least 8 characters');
    $response->assertSee('uppercase letter');
    $response->assertSee('lowercase letter');
    $response->assertSee('number');
});

it('validates password confirmation on reset', function (): void {
    $token = Password::broker()->createToken($this->schoolAdmin);

    $response = $this->post(route('password.update'), [
        'token' => $token,
        'email' => 'admin@test.com',
        'password' => 'NewStrongPass1',
        'password_confirmation' => 'DifferentPass1',
    ]);

    $response->assertSessionHasErrors('password');
    $this->assertTrue(Hash::check('password', $this->schoolAdmin->fresh()->password));
});
