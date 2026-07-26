<?php

use App\Models\Attendance;
use App\Models\Plan;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

    $this->teacherWithPermission = User::create([
        'name' => 'Teacher With Permission',
        'email' => 'teacher_perm@test.com',
        'password' => bcrypt('password'),
    ]);
    $this->teacherWithPermission->forceFill(['role' => 'teacher', 'school_id' => $this->school->id])->save();

    $this->teacherRecordWithPermission = Teacher::create([
        'school_id' => $this->school->id,
        'first_name' => 'Teacher',
        'last_name' => 'WithPermission',
        'email' => 'teacher_perm@test.com',
        'phone' => '+1234567890',
        'gender' => 'male',
        'status' => true,
        'can_mark_attendance' => true,
    ]);

    $this->teacherWithoutPermission = User::create([
        'name' => 'Teacher Without Permission',
        'email' => 'teacher_noperm@test.com',
        'password' => bcrypt('password'),
    ]);
    $this->teacherWithoutPermission->forceFill(['role' => 'teacher', 'school_id' => $this->school->id])->save();

    $this->teacherRecordWithoutPermission = Teacher::create([
        'school_id' => $this->school->id,
        'first_name' => 'Teacher',
        'last_name' => 'WithoutPermission',
        'email' => 'teacher_noperm@test.com',
        'phone' => '+0987654321',
        'gender' => 'female',
        'status' => true,
        'can_mark_attendance' => false,
    ]);

    $this->class = SchoolClass::create([
        'school_id' => $this->school->id,
        'name' => 'JSS 1A',
        'status' => true,
    ]);

    $this->teacherRecordWithPermission->schoolClasses()->attach($this->class->id);
});

it('allows school admin to enable attendance permission on teacher create', function (): void {
    $this->actingAs($this->schoolAdmin);

    $response = $this->post('/teachers', [
        'school_id' => $this->school->id,
        'first_name' => 'New',
        'last_name' => 'Teacher',
        'gender' => 'male',
        'phone' => '+1112223333',
        'can_mark_attendance' => '1',
    ]);

    $response->assertRedirect(route('teachers.index'));

    $teacher = Teacher::where('first_name', 'New')->first();
    $this->assertTrue($teacher->can_mark_attendance);
});

it('allows school admin to disable attendance permission on teacher create', function (): void {
    $this->actingAs($this->schoolAdmin);

    $response = $this->post('/teachers', [
        'school_id' => $this->school->id,
        'first_name' => 'New',
        'last_name' => 'Teacher',
        'gender' => 'male',
        'phone' => '+1112223333',
    ]);

    $response->assertRedirect(route('teachers.index'));

    $teacher = Teacher::where('first_name', 'New')->first();
    $this->assertFalse($teacher->can_mark_attendance);
});

it('allows school admin to toggle attendance permission on teacher edit', function (): void {
    $this->actingAs($this->schoolAdmin);

    $this->assertFalse($this->teacherRecordWithoutPermission->can_mark_attendance);

    $response = $this->put("/teachers/{$this->teacherRecordWithoutPermission->id}", [
        'school_id' => $this->school->id,
        'first_name' => 'Teacher',
        'last_name' => 'WithoutPermission',
        'gender' => 'female',
        'phone' => '+0987654321',
        'status' => '1',
        'can_mark_attendance' => '1',
    ]);

    $response->assertRedirect(route('teachers.index'));

    $this->teacherRecordWithoutPermission->refresh();
    $this->assertTrue($this->teacherRecordWithoutPermission->can_mark_attendance);
});

it('allows school admin to revoke attendance permission on teacher edit', function (): void {
    $this->actingAs($this->schoolAdmin);

    $this->assertTrue($this->teacherRecordWithPermission->can_mark_attendance);

    $response = $this->put("/teachers/{$this->teacherRecordWithPermission->id}", [
        'school_id' => $this->school->id,
        'first_name' => 'Teacher',
        'last_name' => 'WithPermission',
        'gender' => 'male',
        'phone' => '+1234567890',
        'status' => '1',
    ]);

    $response->assertRedirect(route('teachers.index'));

    $this->teacherRecordWithPermission->refresh();
    $this->assertFalse($this->teacherRecordWithPermission->can_mark_attendance);
});

it('shows attendance sidebar link for teacher with permission', function (): void {
    $this->actingAs($this->teacherWithPermission);

    $response = $this->get('/dashboard');

    $response->assertRedirect(route('teacher.dashboard'));
    $response = $this->get(route('teacher.dashboard'));
    $response->assertOk();
    $response->assertSee('teacher/attendance');
});

it('does not show attendance sidebar link for teacher without permission', function (): void {
    $this->actingAs($this->teacherWithoutPermission);

    $response = $this->get('/dashboard');

    $response->assertRedirect(route('teacher.dashboard'));
    $response = $this->get(route('teacher.dashboard'));
    $response->assertOk();
    $response->assertDontSee('teacher/attendance');
});

it('returns 403 when teacher without permission accesses attendance index', function (): void {
    $this->actingAs($this->teacherWithoutPermission);

    $response = $this->get(route('teacher.attendance.index'));

    $response->assertStatus(403);
});

it('returns 403 when teacher without permission accesses take attendance', function (): void {
    $this->actingAs($this->teacherWithoutPermission);

    $response = $this->get(route('teacher.attendance.create'));

    $response->assertStatus(403);
});

it('returns 403 when teacher without permission accesses class report', function (): void {
    $this->actingAs($this->teacherWithoutPermission);

    $response = $this->get(route('teacher.attendance.class-report'));

    $response->assertStatus(403);
});

it('allows teacher with permission to access attendance index', function (): void {
    $this->actingAs($this->teacherWithPermission);

    $response = $this->get(route('teacher.attendance.index'));

    $response->assertOk();
});

it('allows teacher with permission to access take attendance page', function (): void {
    $this->actingAs($this->teacherWithPermission);

    $response = $this->get(route('teacher.attendance.create'));

    $response->assertOk();
});

it('allows teacher with permission to access class report', function (): void {
    $this->actingAs($this->teacherWithPermission);

    $response = $this->get(route('teacher.attendance.class-report'));

    $response->assertOk();
});

it('scopes teacher attendance to their assigned classes only', function (): void {
    $this->actingAs($this->teacherWithPermission);

    $response = $this->get(route('teacher.attendance.create', ['class_id' => $this->class->id]));

    $response->assertOk();
});

it('prevents teacher from recording attendance for unassigned class', function (): void {
    $this->actingAs($this->teacherWithPermission);

    $unassignedClass = SchoolClass::create([
        'school_id' => $this->school->id,
        'name' => 'SS 2B',
        'status' => true,
    ]);

    $student = Student::create([
        'school_id' => $this->school->id,
        'school_class_id' => $unassignedClass->id,
        'first_name' => 'Test',
        'last_name' => 'Student',
        'gender' => 'male',
        'date_of_birth' => '2010-01-01',
        'admission_number' => 'ADM001',
        'status' => 'active',
    ]);

    $response = $this->post(route('teacher.attendance.store'), [
        'school_class_id' => $unassignedClass->id,
        'attendance_date' => now()->toDateString(),
        'attendances' => [
            ['student_id' => $student->id, 'status' => 'present'],
        ],
    ]);

    $response->assertStatus(403);
});

it('allows teacher with permission to store attendance for assigned class', function (): void {
    $this->actingAs($this->teacherWithPermission);

    $student = Student::create([
        'school_id' => $this->school->id,
        'school_class_id' => $this->class->id,
        'first_name' => 'Test',
        'last_name' => 'Student',
        'gender' => 'male',
        'date_of_birth' => '2010-01-01',
        'admission_number' => 'ADM001',
        'status' => 'active',
    ]);

    $response = $this->post(route('teacher.attendance.store'), [
        'school_class_id' => $this->class->id,
        'attendance_date' => now()->toDateString(),
        'attendances' => [
            ['student_id' => $student->id, 'status' => 'present'],
        ],
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('attendances', [
        'student_id' => $student->id,
        'school_id' => $this->school->id,
        'school_class_id' => $this->class->id,
        'status' => 'present',
        'marked_by' => $this->teacherRecordWithPermission->id,
    ]);
});

it('allows teacher with permission to view attendance show page', function (): void {
    $this->actingAs($this->teacherWithPermission);

    $student = Student::create([
        'school_id' => $this->school->id,
        'school_class_id' => $this->class->id,
        'first_name' => 'Test',
        'last_name' => 'Student',
        'gender' => 'male',
        'date_of_birth' => '2010-01-01',
        'admission_number' => 'ADM001',
        'status' => 'active',
    ]);

    $attendance = Attendance::create([
        'school_id' => $this->school->id,
        'student_id' => $student->id,
        'school_class_id' => $this->class->id,
        'attendance_date' => now()->toDateString(),
        'status' => 'present',
        'marked_by' => $this->teacherRecordWithPermission->id,
    ]);

    $response = $this->get(route('teacher.attendance.show', $attendance));

    $response->assertOk();
});

it('prevents teacher from viewing attendance record from another school', function (): void {
    $this->actingAs($this->teacherWithPermission);

    $otherSchool = School::create([
        'name' => 'Other School',
        'slug' => 'other-school',
        'is_active' => true,
    ]);

    $otherClass = SchoolClass::create([
        'school_id' => $otherSchool->id,
        'name' => 'JSS 1A',
        'status' => true,
    ]);

    $otherStudent = Student::create([
        'school_id' => $otherSchool->id,
        'school_class_id' => $otherClass->id,
        'first_name' => 'Other',
        'last_name' => 'Student',
        'gender' => 'male',
        'date_of_birth' => '2010-01-01',
        'admission_number' => 'ADM002',
        'status' => 'active',
    ]);

    $attendance = Attendance::create([
        'school_id' => $otherSchool->id,
        'student_id' => $otherStudent->id,
        'school_class_id' => $otherClass->id,
        'attendance_date' => now()->toDateString(),
        'status' => 'present',
    ]);

    $response = $this->get(route('teacher.attendance.show', $attendance));

    $response->assertStatus(403);
});

it('does not break existing school admin attendance functionality', function (): void {
    $this->actingAs($this->schoolAdmin);

    $response = $this->get(route('attendance.dashboard'));

    $response->assertOk();
});

it('does not break existing super admin functionality', function (): void {
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'super@test.com',
        'password' => bcrypt('password'),
    ]);
    $superAdmin->forceFill(['role' => 'super_admin', 'school_id' => null])->save();

    $this->actingAs($superAdmin);

    $response = $this->get(route('attendance.dashboard'));

    $response->assertOk();
});

it('teacher attendance index only shows records from their school', function (): void {
    $this->actingAs($this->teacherWithPermission);

    $student = Student::create([
        'school_id' => $this->school->id,
        'school_class_id' => $this->class->id,
        'first_name' => 'Test',
        'last_name' => 'Student',
        'gender' => 'male',
        'date_of_birth' => '2010-01-01',
        'admission_number' => 'ADM001',
        'status' => 'active',
    ]);

    Attendance::create([
        'school_id' => $this->school->id,
        'student_id' => $student->id,
        'school_class_id' => $this->class->id,
        'attendance_date' => now()->toDateString(),
        'status' => 'present',
        'marked_by' => $this->teacherRecordWithPermission->id,
    ]);

    $response = $this->get(route('teacher.attendance.index'));

    $response->assertOk();
    $response->assertSee('Test');
});
