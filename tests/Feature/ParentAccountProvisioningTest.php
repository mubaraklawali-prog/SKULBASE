<?php

use App\Models\ParentModel;
use App\Models\Plan;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subscription;
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

    $this->schoolClass = SchoolClass::create([
        'school_id' => $this->school->id,
        'name' => 'JSS 1',
        'status' => true,
    ]);
});

it('creates parent without login account', function (): void {
    $response = $this->actingAs($this->schoolAdmin)->post(route('parents.store'), [
        'school_id' => $this->school->id,
        'first_name' => 'Muhammad',
        'last_name' => 'Bello',
        'email' => 'belo@test.com',
        'phone' => '+2348012345678',
    ]);

    $response->assertRedirect(route('parents.index'));
    $response->assertSessionHas('success', 'Parent created successfully.');

    $parent = ParentModel::where('email', 'belo@test.com')->first();
    $this->assertNotNull($parent);
    $this->assertNull($parent->user_id);
    $this->assertDatabaseMissing('users', [
        'email' => 'belo@test.com',
        'role' => 'parent',
    ]);
});

it('creates parent with login account', function (): void {
    $response = $this->actingAs($this->schoolAdmin)->post(route('parents.store'), [
        'school_id' => $this->school->id,
        'first_name' => 'Muhammad',
        'last_name' => 'Bello',
        'email' => 'belo@test.com',
        'phone' => '+2348012345678',
        'create_login_account' => '1',
    ]);

    $parent = ParentModel::where('email', 'belo@test.com')->first();
    $this->assertNotNull($parent);
    $this->assertNotNull($parent->user_id);

    $parentUser = User::where('email', 'belo@test.com')->first();
    $this->assertNotNull($parentUser);
    $this->assertEquals('parent', $parentUser->role);
    $this->assertEquals($this->school->id, $parentUser->school_id);
    $this->assertTrue($parentUser->force_password_change);

    $response->assertRedirect(route('parents.credentials', $parent));
    $response->assertSessionHas('credentials');
});

it('links students to parent during creation', function (): void {
    $student1 = Student::create([
        'school_id' => $this->school->id,
        'admission_number' => 'ADM-001',
        'first_name' => 'Ahmad',
        'last_name' => 'Bello',
        'gender' => 'male',
        'date_of_birth' => '2015-01-01',
        'school_class_id' => $this->schoolClass->id,
        'status' => 'active',
    ]);

    $student2 = Student::create([
        'school_id' => $this->school->id,
        'admission_number' => 'ADM-002',
        'first_name' => 'Fatima',
        'last_name' => 'Bello',
        'gender' => 'female',
        'date_of_birth' => '2017-06-15',
        'school_class_id' => $this->schoolClass->id,
        'status' => 'active',
    ]);

    $this->actingAs($this->schoolAdmin)->post(route('parents.store'), [
        'school_id' => $this->school->id,
        'first_name' => 'Muhammad',
        'last_name' => 'Bello',
        'email' => 'belo@test.com',
        'phone' => '+2348012345678',
        'student_ids' => [$student1->id, $student2->id],
    ]);

    $parent = ParentModel::where('email', 'belo@test.com')->first();
    $this->assertNotNull($parent);
    $this->assertCount(2, $parent->children);
    $this->assertTrue($parent->children->contains($student1->id));
    $this->assertTrue($parent->children->contains($student2->id));
});

it('prevents duplicate parent accounts by email in same school', function (): void {
    ParentModel::create([
        'school_id' => $this->school->id,
        'first_name' => 'Existing',
        'last_name' => 'Parent',
        'email' => 'duplicate@test.com',
        'phone' => '+2348012345678',
        'status' => true,
    ]);

    $response = $this->actingAs($this->schoolAdmin)->post(route('parents.store'), [
        'school_id' => $this->school->id,
        'first_name' => 'New',
        'last_name' => 'Parent',
        'email' => 'duplicate@test.com',
        'phone' => '+2348099999999',
    ]);

    $parentCount = ParentModel::where('email', 'duplicate@test.com')->count();
    $this->assertEquals(1, $parentCount);
});

it('prevents duplicate parent accounts by phone in same school', function (): void {
    ParentModel::create([
        'school_id' => $this->school->id,
        'first_name' => 'Existing',
        'last_name' => 'Parent',
        'email' => 'existing@test.com',
        'phone' => '+2348012345678',
        'status' => true,
    ]);

    $response = $this->actingAs($this->schoolAdmin)->post(route('parents.store'), [
        'school_id' => $this->school->id,
        'first_name' => 'New',
        'last_name' => 'Parent',
        'email' => 'new@test.com',
        'phone' => '+2348012345678',
    ]);

    $parentCount = ParentModel::where('phone', '+2348012345678')->count();
    $this->assertEquals(1, $parentCount);
});

it('links students to existing parent when duplicate detected', function (): void {
    $existingParent = ParentModel::create([
        'school_id' => $this->school->id,
        'first_name' => 'Existing',
        'last_name' => 'Parent',
        'email' => 'existing@test.com',
        'phone' => '+2348012345678',
        'status' => true,
    ]);

    $student = Student::create([
        'school_id' => $this->school->id,
        'admission_number' => 'ADM-001',
        'first_name' => 'Ahmad',
        'last_name' => 'Bello',
        'gender' => 'male',
        'date_of_birth' => '2015-01-01',
        'school_class_id' => $this->schoolClass->id,
        'status' => 'active',
    ]);

    $response = $this->actingAs($this->schoolAdmin)->post(route('parents.store'), [
        'school_id' => $this->school->id,
        'first_name' => 'Existing',
        'last_name' => 'Parent',
        'email' => 'existing@test.com',
        'phone' => '+2348012345678',
        'student_ids' => [$student->id],
    ]);

    $existingParent->refresh();
    $this->assertCount(1, $existingParent->children);
    $this->assertTrue($existingParent->children->contains($student->id));
});

it('creates parent and links student during student registration', function (): void {
    $response = $this->actingAs($this->schoolAdmin)->post(route('students.store'), [
        'school_id' => $this->school->id,
        'admission_number' => 'ADM-001',
        'first_name' => 'Ahmad',
        'last_name' => 'Bello',
        'gender' => 'male',
        'date_of_birth' => '2015-01-01',
        'school_class_id' => $this->schoolClass->id,
        'new_parent_first_name' => 'Muhammad',
        'new_parent_last_name' => 'Bello',
        'new_parent_email' => 'belo@test.com',
        'new_parent_phone' => '+2348012345678',
    ]);

    $student = Student::where('admission_number', 'ADM-001')->first();
    $this->assertNotNull($student);

    $parent = ParentModel::where('email', 'belo@test.com')->first();
    $this->assertNotNull($parent);
    $this->assertEquals('Muhammad', $parent->first_name);
    $this->assertEquals('Bello', $parent->last_name);

    $this->assertCount(1, $parent->children);
    $this->assertTrue($parent->children->contains($student->id));
});

it('links student to existing parent during student registration', function (): void {
    $existingParent = ParentModel::create([
        'school_id' => $this->school->id,
        'first_name' => 'Muhammad',
        'last_name' => 'Bello',
        'email' => 'belo@test.com',
        'phone' => '+2348012345678',
        'status' => true,
    ]);

    $response = $this->actingAs($this->schoolAdmin)->post(route('students.store'), [
        'school_id' => $this->school->id,
        'admission_number' => 'ADM-001',
        'first_name' => 'Fatima',
        'last_name' => 'Bello',
        'gender' => 'female',
        'date_of_birth' => '2017-06-15',
        'school_class_id' => $this->schoolClass->id,
        'existing_parent_id' => $existingParent->id,
    ]);

    $existingParent->refresh();
    $this->assertCount(1, $existingParent->children);
});

it('creates parent with login account during student registration', function (): void {
    $response = $this->actingAs($this->schoolAdmin)->post(route('students.store'), [
        'school_id' => $this->school->id,
        'admission_number' => 'ADM-001',
        'first_name' => 'Ahmad',
        'last_name' => 'Bello',
        'gender' => 'male',
        'date_of_birth' => '2015-01-01',
        'school_class_id' => $this->schoolClass->id,
        'new_parent_first_name' => 'Muhammad',
        'new_parent_last_name' => 'Bello',
        'new_parent_email' => 'belo@test.com',
        'new_parent_phone' => '+2348012345678',
        'create_parent_account' => '1',
    ]);

    $parent = ParentModel::where('email', 'belo@test.com')->first();
    $this->assertNotNull($parent);
    $this->assertNotNull($parent->user_id);

    $parentUser = User::where('email', 'belo@test.com')->first();
    $this->assertEquals('parent', $parentUser->role);
    $this->assertTrue($parentUser->force_password_change);

    $response->assertRedirect(route('parents.credentials', $parent));
});

it('detects duplicate parent during student registration', function (): void {
    ParentModel::create([
        'school_id' => $this->school->id,
        'first_name' => 'Existing',
        'last_name' => 'Parent',
        'email' => 'existing@test.com',
        'phone' => '+2348012345678',
        'status' => true,
    ]);

    $this->actingAs($this->schoolAdmin)->post(route('students.store'), [
        'school_id' => $this->school->id,
        'admission_number' => 'ADM-001',
        'first_name' => 'Ahmad',
        'last_name' => 'Bello',
        'gender' => 'male',
        'date_of_birth' => '2015-01-01',
        'school_class_id' => $this->schoolClass->id,
        'new_parent_first_name' => 'New',
        'new_parent_last_name' => 'Parent',
        'new_parent_email' => 'existing@test.com',
    ]);

    $parentCount = ParentModel::where('email', 'existing@test.com')->count();
    $this->assertEquals(1, $parentCount);

    $student = Student::where('admission_number', 'ADM-001')->first();
    $existingParent = ParentModel::where('email', 'existing@test.com')->first();
    $this->assertCount(1, $existingParent->children);
    $this->assertTrue($existingParent->children->contains($student->id));
});

it('shows credentials page for parent account', function (): void {
    $response = $this->actingAs($this->schoolAdmin)->post(route('parents.store'), [
        'school_id' => $this->school->id,
        'first_name' => 'Muhammad',
        'last_name' => 'Bello',
        'email' => 'belo@test.com',
        'phone' => '+2348012345678',
        'create_login_account' => '1',
    ]);

    $parent = ParentModel::where('email', 'belo@test.com')->first();

    $response = $this->actingAs($this->schoolAdmin)->get(route('parents.credentials', $parent));
    $response->assertOk();
    $response->assertSee('belo@test.com');
    $response->assertSee('Parent Login Credentials');
});

it('prevents accessing credentials page without session', function (): void {
    $parent = ParentModel::create([
        'school_id' => $this->school->id,
        'first_name' => 'Muhammad',
        'last_name' => 'Bello',
        'email' => 'belo@test.com',
        'phone' => '+2348012345678',
        'status' => true,
    ]);

    $response = $this->actingAs($this->schoolAdmin)->get(route('parents.credentials', $parent));
    $response->assertNotFound();
});

it('shows parent index page', function (): void {
    $response = $this->actingAs($this->schoolAdmin)->get(route('parents.index'));
    $response->assertOk();
    $response->assertSee('Parents');
});

it('shows parent create form', function (): void {
    $response = $this->actingAs($this->schoolAdmin)->get(route('parents.create'));
    $response->assertOk();
    $response->assertSee('Add Parent');
    $response->assertSee('Create login account');
});

it('shows parent profile with linked children', function (): void {
    $parent = ParentModel::create([
        'school_id' => $this->school->id,
        'first_name' => 'Muhammad',
        'last_name' => 'Bello',
        'email' => 'belo@test.com',
        'phone' => '+2348012345678',
        'status' => true,
    ]);

    $student = Student::create([
        'school_id' => $this->school->id,
        'admission_number' => 'ADM-001',
        'first_name' => 'Ahmad',
        'last_name' => 'Bello',
        'gender' => 'male',
        'date_of_birth' => '2015-01-01',
        'school_class_id' => $this->schoolClass->id,
        'status' => 'active',
    ]);

    $parent->children()->attach($student->id);

    $response = $this->actingAs($this->schoolAdmin)->get(route('parents.show', $parent));
    $response->assertOk();
    $response->assertSee('Muhammad Bello');
    $response->assertSee('Ahmad Bello');
});

it('allows parent to login and view dashboard', function (): void {
    $parent = ParentModel::create([
        'school_id' => $this->school->id,
        'first_name' => 'Muhammad',
        'last_name' => 'Bello',
        'email' => 'belo@test.com',
        'phone' => '+2348012345678',
        'status' => true,
    ]);

    $parentUser = User::create([
        'name' => 'Muhammad Bello',
        'email' => 'belo@test.com',
        'password' => bcrypt('password'),
    ]);
    $parentUser->forceFill(['role' => 'parent', 'school_id' => $this->school->id])->save();

    $parent->update(['user_id' => $parentUser->id]);

    $student = Student::create([
        'school_id' => $this->school->id,
        'admission_number' => 'ADM-001',
        'first_name' => 'Ahmad',
        'last_name' => 'Bello',
        'gender' => 'male',
        'date_of_birth' => '2015-01-01',
        'school_class_id' => $this->schoolClass->id,
        'status' => 'active',
    ]);

    $parent->children()->attach($student->id);

    $response = $this->post(route('login'), [
        'email' => 'belo@test.com',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('parent.dashboard'));

    $response = $this->actingAs($parentUser)->get(route('parent.dashboard'));
    $response->assertOk();
    $response->assertSee('Ahmad Bello');
});

it('parent cannot view unrelated students', function (): void {
    $parent = ParentModel::create([
        'school_id' => $this->school->id,
        'first_name' => 'Muhammad',
        'last_name' => 'Bello',
        'email' => 'belo@test.com',
        'phone' => '+2348012345678',
        'status' => true,
    ]);

    $parentUser = User::create([
        'name' => 'Muhammad Bello',
        'email' => 'belo@test.com',
        'password' => bcrypt('password'),
    ]);
    $parentUser->forceFill(['role' => 'parent', 'school_id' => $this->school->id])->save();

    $parent->update(['user_id' => $parentUser->id]);

    $unrelatedStudent = Student::create([
        'school_id' => $this->school->id,
        'admission_number' => 'ADM-002',
        'first_name' => 'Unrelated',
        'last_name' => 'Student',
        'gender' => 'male',
        'date_of_birth' => '2015-01-01',
        'school_class_id' => $this->schoolClass->id,
        'status' => 'active',
    ]);

    $response = $this->actingAs($parentUser)->get(route('parent.dashboard'));
    $response->assertOk();
    $response->assertDontSee('Unrelated Student');
});

it('deletes parent and detachs children', function (): void {
    $parent = ParentModel::create([
        'school_id' => $this->school->id,
        'first_name' => 'Muhammad',
        'last_name' => 'Bello',
        'email' => 'belo@test.com',
        'phone' => '+2348012345678',
        'status' => true,
    ]);

    $student = Student::create([
        'school_id' => $this->school->id,
        'admission_number' => 'ADM-001',
        'first_name' => 'Ahmad',
        'last_name' => 'Bello',
        'gender' => 'male',
        'date_of_birth' => '2015-01-01',
        'school_class_id' => $this->schoolClass->id,
        'status' => 'active',
    ]);

    $parent->children()->attach($student->id);

    $response = $this->actingAs($this->schoolAdmin)->delete(route('parents.destroy', $parent));
    $response->assertRedirect(route('parents.index'));

    $this->assertDatabaseMissing('parents', ['id' => $parent->id]);
    $this->assertDatabaseMissing('parent_student', ['parent_id' => $parent->id]);
});

it('updates parent information and children links', function (): void {
    $parent = ParentModel::create([
        'school_id' => $this->school->id,
        'first_name' => 'Muhammad',
        'last_name' => 'Bello',
        'email' => 'belo@test.com',
        'phone' => '+2348012345678',
        'status' => true,
    ]);

    $student1 = Student::create([
        'school_id' => $this->school->id,
        'admission_number' => 'ADM-001',
        'first_name' => 'Ahmad',
        'last_name' => 'Bello',
        'gender' => 'male',
        'date_of_birth' => '2015-01-01',
        'school_class_id' => $this->schoolClass->id,
        'status' => 'active',
    ]);

    $student2 = Student::create([
        'school_id' => $this->school->id,
        'admission_number' => 'ADM-002',
        'first_name' => 'Fatima',
        'last_name' => 'Bello',
        'gender' => 'female',
        'date_of_birth' => '2017-06-15',
        'school_class_id' => $this->schoolClass->id,
        'status' => 'active',
    ]);

    $parent->children()->attach($student1->id);

    $response = $this->actingAs($this->schoolAdmin)->put(route('parents.update', $parent), [
        'school_id' => $this->school->id,
        'first_name' => 'Muhammad',
        'last_name' => 'Bello',
        'email' => 'belo_updated@test.com',
        'phone' => '+2348099999999',
        'status' => '1',
        'student_ids' => [$student2->id],
    ]);

    $parent->refresh();
    $this->assertEquals('belo_updated@test.com', $parent->email);
    $this->assertCount(1, $parent->children);
    $this->assertTrue($parent->children->contains($student2->id));
    $this->assertFalse($parent->children->contains($student1->id));
});

it('forces password change on first login for parent with account', function (): void {
    $parentUser = User::create([
        'name' => 'Muhammad Bello',
        'email' => 'belo@test.com',
        'password' => bcrypt('password'),
    ]);
    $parentUser->forceFill([
        'role' => 'parent',
        'school_id' => $this->school->id,
        'force_password_change' => true,
    ])->save();

    $response = $this->post(route('login'), [
        'email' => 'belo@test.com',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('password.change'));
});

it('allows parent to change password on first login', function (): void {
    $parentUser = User::create([
        'name' => 'Muhammad Bello',
        'email' => 'belo@test.com',
        'password' => bcrypt('password'),
    ]);
    $parentUser->forceFill([
        'role' => 'parent',
        'school_id' => $this->school->id,
        'force_password_change' => true,
    ])->save();

    $response = $this->actingAs($parentUser)->post(route('password.change.submit'), [
        'current_password' => 'password',
        'password' => 'Newpassword123',
        'password_confirmation' => 'Newpassword123',
    ]);

    $response->assertRedirect(route('dashboard'));

    $parentUser->refresh();
    $this->assertFalse($parentUser->force_password_change);
    $this->assertTrue(Hash::check('Newpassword123', $parentUser->password));
});

it('validates required fields on parent creation', function (): void {
    $response = $this->actingAs($this->schoolAdmin)->post(route('parents.store'), []);

    $response->assertSessionHasErrors([
        'school_id',
        'first_name',
        'last_name',
    ]);
});

it('validates student_ids exist', function (): void {
    $response = $this->actingAs($this->schoolAdmin)->post(route('parents.store'), [
        'school_id' => $this->school->id,
        'first_name' => 'Muhammad',
        'last_name' => 'Bello',
        'student_ids' => [9999],
    ]);

    $response->assertSessionHasErrors('student_ids.*');
});
