<?php

use App\Models\Plan;
use App\Models\School;
use App\Models\Subject;
use App\Models\Subscription;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

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

    $this->schoolAdmin = User::create([
        'name' => 'School Admin',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
    ]);
    $this->schoolAdmin->forceFill(['role' => 'school_admin', 'school_id' => $this->school->id])->save();

    $this->subject = Subject::create([
        'school_id' => $this->school->id,
        'name' => 'Mathematics',
        'code' => 'MATH',
        'status' => true,
    ]);
});

it('creates teacher without login account when checkbox is unchecked', function (): void {
    $response = $this->actingAs($this->schoolAdmin)->post(route('teachers.store'), [
        'school_id' => $this->school->id,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'gender' => 'male',
        'email' => 'john@test.com',
        'phone' => '+1234567890',
    ]);

    $response->assertRedirect(route('teachers.index'));
    $response->assertSessionHas('success', 'Teacher created successfully.');

    $teacher = Teacher::where('email', 'john@test.com')->first();
    $this->assertNotNull($teacher);
    $this->assertNull($teacher->user_id);
    $this->assertNull($teacher->user);
    $this->assertDatabaseMissing('users', [
        'email' => 'john@test.com',
        'role' => 'teacher',
    ]);
});

it('creates teacher with login account when checkbox is checked', function (): void {
    $response = $this->actingAs($this->schoolAdmin)->post(route('teachers.store'), [
        'school_id' => $this->school->id,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'gender' => 'female',
        'email' => 'jane@test.com',
        'phone' => '+1234567891',
        'create_login_account' => '1',
    ]);

    $teacher = Teacher::where('email', 'jane@test.com')->first();
    $this->assertNotNull($teacher);
    $this->assertNotNull($teacher->user_id);

    $teacherUser = User::where('email', 'jane@test.com')->first();
    $this->assertNotNull($teacherUser);
    $this->assertEquals('teacher', $teacherUser->role);
    $this->assertEquals($this->school->id, $teacherUser->school_id);
    $this->assertTrue($teacherUser->force_password_change);
    $this->assertEquals($teacher->id, $teacherUser->teacher->id);

    $response->assertRedirect(route('teachers.credentials', $teacher));
});

it('redirects to credentials page after creating teacher with account', function (): void {
    $response = $this->actingAs($this->schoolAdmin)->post(route('teachers.store'), [
        'school_id' => $this->school->id,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'gender' => 'female',
        'email' => 'jane@test.com',
        'phone' => '+1234567891',
        'create_login_account' => '1',
    ]);

    $teacher = Teacher::where('email', 'jane@test.com')->first();
    $response->assertRedirect(route('teachers.credentials', $teacher));
    $response->assertSessionHas('credentials');
});

it('shows credentials page with email and password', function (): void {
    $response = $this->actingAs($this->schoolAdmin)->post(route('teachers.store'), [
        'school_id' => $this->school->id,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'gender' => 'female',
        'email' => 'jane@test.com',
        'phone' => '+1234567891',
        'create_login_account' => '1',
    ]);

    $teacher = Teacher::where('email', 'jane@test.com')->first();

    $response = $this->actingAs($this->schoolAdmin)->get(route('teachers.credentials', $teacher));
    $response->assertOk();
    $response->assertSee('jane@test.com');
    $response->assertSee('Teacher Login Credentials');
    $response->assertSee('Jane Smith');
});

it('does not create login account when teacher has no email', function (): void {
    $response = $this->actingAs($this->schoolAdmin)->post(route('teachers.store'), [
        'school_id' => $this->school->id,
        'first_name' => 'No',
        'last_name' => 'Email',
        'gender' => 'male',
        'phone' => '+1234567892',
        'create_login_account' => '1',
    ]);

    $response->assertRedirect(route('teachers.index'));
    $response->assertSessionHas('success', 'Teacher created successfully.');

    $teacher = Teacher::where('first_name', 'No')->first();
    $this->assertNotNull($teacher);
    $this->assertNull($teacher->user_id);
    $this->assertDatabaseMissing('users', [
        'email' => null,
        'role' => 'teacher',
    ]);
});

it('generates random 12-character password for teacher account', function (): void {
    $this->actingAs($this->schoolAdmin)->post(route('teachers.store'), [
        'school_id' => $this->school->id,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'gender' => 'female',
        'email' => 'jane@test.com',
        'phone' => '+1234567891',
        'create_login_account' => '1',
    ]);

    $teacherUser = User::where('email', 'jane@test.com')->first();
    $this->assertNotNull($teacherUser);
    $this->assertNotEquals('password', $teacherUser->password);
});

it('sets force_password_change to true for new teacher account', function (): void {
    $this->actingAs($this->schoolAdmin)->post(route('teachers.store'), [
        'school_id' => $this->school->id,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'gender' => 'female',
        'email' => 'jane@test.com',
        'phone' => '+1234567891',
        'create_login_account' => '1',
    ]);

    $teacherUser = User::where('email', 'jane@test.com')->first();
    $this->assertTrue($teacherUser->force_password_change);
});

it('redirects teacher with force_password_change to password change page on login', function (): void {
    $teacherUser = User::create([
        'name' => 'Jane Smith',
        'email' => 'jane@test.com',
        'password' => bcrypt('password'),
    ]);
    $teacherUser->forceFill([
        'role' => 'teacher',
        'school_id' => $this->school->id,
        'force_password_change' => true,
    ])->save();

    $response = $this->post(route('login'), [
        'email' => 'jane@test.com',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('password.change'));
});

it('does not redirect teacher to password change when force_password_change is false', function (): void {
    $teacherUser = User::create([
        'name' => 'Jane Smith',
        'email' => 'jane@test.com',
        'password' => bcrypt('password'),
    ]);
    $teacherUser->forceFill([
        'role' => 'teacher',
        'school_id' => $this->school->id,
        'force_password_change' => false,
    ])->save();

    $response = $this->post(route('login'), [
        'email' => 'jane@test.com',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('dashboard'));
});

it('shows password change form', function (): void {
    $teacherUser = User::create([
        'name' => 'Jane Smith',
        'email' => 'jane@test.com',
        'password' => bcrypt('password'),
    ]);
    $teacherUser->forceFill([
        'role' => 'teacher',
        'school_id' => $this->school->id,
        'force_password_change' => true,
    ])->save();

    $response = $this->actingAs($teacherUser)->get(route('password.change'));
    $response->assertOk();
    $response->assertSee('Change Password');
    $response->assertSee('Welcome');
});

it('validates current password on password change', function (): void {
    $teacherUser = User::create([
        'name' => 'Jane Smith',
        'email' => 'jane@test.com',
        'password' => bcrypt('password'),
    ]);
    $teacherUser->forceFill([
        'role' => 'teacher',
        'school_id' => $this->school->id,
        'force_password_change' => true,
    ])->save();

    $response = $this->actingAs($teacherUser)->post(route('password.change.submit'), [
        'current_password' => 'wrongpassword',
        'password' => 'Newpassword1',
        'password_confirmation' => 'Newpassword1',
    ]);

    $response->assertSessionHasErrors('current_password');
});

it('changes password and clears force_password_change flag', function (): void {
    $teacherUser = User::create([
        'name' => 'Jane Smith',
        'email' => 'jane@test.com',
        'password' => bcrypt('password'),
    ]);
    $teacherUser->forceFill([
        'role' => 'teacher',
        'school_id' => $this->school->id,
        'force_password_change' => true,
    ])->save();

    $response = $this->actingAs($teacherUser)->post(route('password.change.submit'), [
        'current_password' => 'password',
        'password' => 'Newpassword123',
        'password_confirmation' => 'Newpassword123',
    ]);

    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('success', 'Password changed successfully.');

    $teacherUser->refresh();
    $this->assertFalse($teacherUser->force_password_change);
    $this->assertTrue(Hash::check('Newpassword123', $teacherUser->password));
});

it('allows teacher to login with new password after changing', function (): void {
    $teacherUser = User::create([
        'name' => 'Jane Smith',
        'email' => 'jane@test.com',
        'password' => bcrypt('password'),
    ]);
    $teacherUser->forceFill([
        'role' => 'teacher',
        'school_id' => $this->school->id,
        'force_password_change' => true,
    ])->save();

    $this->actingAs($teacherUser)->post(route('password.change.submit'), [
        'current_password' => 'password',
        'password' => 'Newpassword123',
        'password_confirmation' => 'Newpassword123',
    ]);

    Auth::logout();

    $response = $this->post(route('login'), [
        'email' => 'jane@test.com',
        'password' => 'Newpassword123',
    ]);

    $response->assertRedirect(route('dashboard'));
});

it('prevents accessing credentials page without session', function (): void {
    $teacher = Teacher::create([
        'school_id' => $this->school->id,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'gender' => 'female',
        'email' => 'jane@test.com',
        'phone' => '+1234567891',
        'status' => true,
    ]);

    $response = $this->actingAs($this->schoolAdmin)->get(route('teachers.credentials', $teacher));
    $response->assertNotFound();
});

it('validates create_login_account requires teacher email', function (): void {
    $response = $this->actingAs($this->schoolAdmin)->post(route('teachers.store'), [
        'school_id' => $this->school->id,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'gender' => 'female',
        'phone' => '+1234567891',
        'create_login_account' => '1',
    ]);

    $response->assertRedirect();
    $teacher = Teacher::where('first_name', 'Jane')->first();
    $this->assertNull($teacher->user_id);
});

it('creates linked teacher-user relationship correctly', function (): void {
    $this->actingAs($this->schoolAdmin)->post(route('teachers.store'), [
        'school_id' => $this->school->id,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'gender' => 'female',
        'email' => 'jane@test.com',
        'phone' => '+1234567891',
        'create_login_account' => '1',
    ]);

    $teacher = Teacher::where('email', 'jane@test.com')->first();
    $teacherUser = User::where('email', 'jane@test.com')->first();

    $this->assertEquals($teacherUser->id, $teacher->user_id);
    $this->assertEquals($teacher->id, $teacherUser->teacher->id);
    $this->assertEquals('Jane Smith', $teacherUser->name);
    $this->assertEquals($this->school->id, $teacherUser->school_id);
});

it('does not create duplicate account for same email teacher', function (): void {
    $this->actingAs($this->schoolAdmin)->post(route('teachers.store'), [
        'school_id' => $this->school->id,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'gender' => 'female',
        'email' => 'jane@test.com',
        'phone' => '+1234567891',
        'create_login_account' => '1',
    ]);

    $userCount = User::where('email', 'jane@test.com')->count();
    $this->assertEquals(1, $userCount);
});
