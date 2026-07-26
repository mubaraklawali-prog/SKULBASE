<?php

namespace Database\Seeders;

use App\Models\Admission;
use App\Models\Announcement;
use App\Models\AssessmentType;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Exam;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use App\Models\GradingSystem;
use App\Models\Message;
use App\Models\MessageRecipient;
use App\Models\ParentModel;
use App\Models\Period;
use App\Models\Plan;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\SchoolSetting;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentReportCard;
use App\Models\StudentResult;
use App\Models\Subject;
use App\Models\Subscription;
use App\Models\Teacher;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    protected School $school;

    protected int $schoolId;

    protected array $teachers = [];

    protected array $teacherIds = [];

    protected array $classes = [];

    protected array $classIds = [];

    protected array $sections = [];

    protected array $sectionIds = [];

    protected array $subjects = [];

    protected array $subjectIds = [];

    protected array $students = [];

    protected array $periods = [];

    protected array $assessmentTypes = [];

    protected array $exams = [];

    protected int $adminUserId;

    protected int $schoolAdminUserId;

    protected array $parentData = [];

    public function run(): void
    {
        DB::transaction(function () {
            $this->createSchool();
            $this->createUsers();
            $this->createSections();
            $this->createClasses();
            $this->createSubjects();
            $this->createClassSubjectPivots();
            $this->createTeachers();
            $this->createTeacherSubjectPivots();
            $this->createTeacherClassPivots();
            $this->createStudents();
            $this->createParents();
            $this->createPeriods();
            $this->createTimetables();
            $this->createAssessmentTypes();
            $this->createExams();
            $this->createGradingSystems();
            $this->createFeeStructures();
            $this->createFeePayments();
            $this->createAttendance();
            $this->createStudentResults();
            $this->createReportCards();
            $this->createAssignments();
            $this->createAnnouncements();
            $this->createEvents();
            $this->createMessages();
            $this->createAdmissions();
            $this->createSchoolSettings();
            $this->createSubscription();
        });

        $this->printSummary();
    }

    protected function createSchool(): void
    {
        $this->school = School::updateOrCreate(
            ['slug' => 'skulbase-international'],
            [
                'name' => 'Skulbase International School',
                'slug' => 'skulbase-international',
                'motto' => 'Excellence in Knowledge, Character and Service',
                'website' => 'https://skulbaseacademy.edu.ng',
                'email' => 'info@skulbaseacademy.edu.ng',
                'phone' => '+234 801 234 5678',
                'logo' => null,
                'address' => '15 Ibrahim Babangida Way',
                'city' => 'Sokoto',
                'state' => 'Sokoto',
                'country' => 'Nigeria',
                'principal_name' => 'Dr. Amina Bello',
                'is_active' => true,
            ]
        );

        $this->schoolId = $this->school->id;
    }

    protected function createUsers(): void
    {
        $existingAdmin = User::where('email', 'admin@skulbase.com')->first();
        if ($existingAdmin) {
            $existingAdmin->forceFill([
                'name' => 'Super Admin',
                'email' => 'admin@skulbase.com',
                'password' => bcrypt('password'),
                'role' => 'super_admin',
                'school_id' => null,
            ])->save();
            $this->adminUserId = $existingAdmin->id;
        } else {
            $user = User::create([
                'name' => 'Super Admin',
                'email' => 'admin@skulbase.com',
                'password' => bcrypt('password'),
            ]);
            $user->forceFill(['role' => 'super_admin', 'school_id' => null])->save();
            $this->adminUserId = $user->id;
        }

        $schoolAdmin = User::firstOrCreate(
            ['email' => 'admin@skulbase-academy.edu.ng'],
            [
                'name' => 'Mallam Ibrahim Yusuf',
                'password' => bcrypt('password'),
            ]
        );
        $schoolAdmin->forceFill([
            'role' => 'school_admin',
            'school_id' => $this->schoolId,
        ])->save();
        $this->schoolAdminUserId = $schoolAdmin->id;

        $teacherUserData = [
            ['first_name' => 'Usman', 'last_name' => 'Bello', 'email' => 'usman.bello@skulbase.edu.ng'],
            ['first_name' => 'Fatima', 'last_name' => 'Abdullahi', 'email' => 'fatima.abdullahi@skulbase.edu.ng'],
            ['first_name' => 'Ibrahim', 'last_name' => 'Musa', 'email' => 'ibrahim.musa@skulbase.edu.ng'],
            ['first_name' => 'Aisha', 'last_name' => 'Danjuma', 'email' => 'aisha.danjuma@skulbase.edu.ng'],
            ['first_name' => 'Yusuf', 'last_name' => 'Garba', 'email' => 'yusuf.garba@skulbase.edu.ng'],
            ['first_name' => 'Hauwa', 'last_name' => 'Suleiman', 'email' => 'hauwa.suleiman@skulbase.edu.ng'],
            ['first_name' => 'Abubakar', 'last_name' => 'Dikko', 'email' => 'abubakar.dikko@skulbase.edu.ng'],
            ['first_name' => 'Maryam', 'last_name' => 'Aliyu', 'email' => 'maryam.aliyu@skulbase.edu.ng'],
            ['first_name' => 'Salisu', 'last_name' => 'Tanko', 'email' => 'salisu.tanko@skulbase.edu.ng'],
            ['first_name' => 'Hadiza', 'last_name' => 'Balarabe', 'email' => 'hadiza.balarabe@skulbase.edu.ng'],
            ['first_name' => 'Garba', 'last_name' => 'Abubakar', 'email' => 'garba.abubakar@skulbase.edu.ng'],
            ['first_name' => 'Rukayya', 'last_name' => 'Ahmad', 'email' => 'rukayya.ahmad@skulbase.edu.ng'],
        ];

        foreach ($teacherUserData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['first_name'].' '.$data['last_name'],
                    'password' => bcrypt('password'),
                ]
            );
            $user->forceFill([
                'role' => 'teacher',
                'school_id' => $this->schoolId,
            ])->save();
        }

        $parentUserData = [
            ['first_name' => 'Chinedu', 'last_name' => 'Okoro', 'email' => 'chinedu.okoro@email.com'],
            ['first_name' => 'Blessing', 'last_name' => 'Eze', 'email' => 'blessing.eze@email.com'],
            ['first_name' => 'Musa', 'last_name' => 'Danjuma', 'email' => 'musa.danjuma@email.com'],
            ['first_name' => 'Fatima', 'last_name' => 'Abubakar', 'email' => 'fatima.abubakar@email.com'],
        ];

        foreach ($parentUserData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['first_name'].' '.$data['last_name'],
                    'password' => bcrypt('password'),
                ]
            );
            $user->forceFill([
                'role' => 'parent',
                'school_id' => $this->schoolId,
            ])->save();
        }
    }

    protected function createSections(): void
    {
        foreach (['Section A', 'Section B'] as $name) {
            $section = Section::updateOrCreate(
                ['school_id' => $this->schoolId, 'name' => $name],
                ['school_id' => $this->schoolId, 'name' => $name]
            );
            $this->sections[$name] = $section;
            $this->sectionIds[$name] = $section->id;
        }
    }

    protected function createClasses(): void
    {
        $classNames = ['JSS 1', 'JSS 2', 'JSS 3', 'SS 1', 'SS 2', 'SS 3'];
        $sectionNames = ['Section A', 'Section B'];

        foreach ($classNames as $className) {
            foreach ($sectionNames as $sectionName) {
                $sectionLetter = $sectionName === 'Section A' ? 'A' : 'B';
                $fullName = $className.' '.$sectionLetter;
                $shortCode = str_replace(['JSS ', 'SS '], ['J', 'S'], $className).$sectionLetter;

                $class = SchoolClass::updateOrCreate(
                    ['school_id' => $this->schoolId, 'name' => $fullName],
                    [
                        'school_id' => $this->schoolId,
                        'name' => $fullName,
                        'section' => $sectionLetter,
                        'description' => $fullName.' class',
                        'status' => true,
                    ]
                );
                $this->classes[$shortCode] = $class;
                $this->classIds[$shortCode] = $class->id;
            }
        }
    }

    protected function createSubjects(): void
    {
        $subjectData = [
            ['name' => 'Mathematics', 'code' => 'MTH'],
            ['name' => 'English Language', 'code' => 'ENG'],
            ['name' => 'Physics', 'code' => 'PHY'],
            ['name' => 'Chemistry', 'code' => 'CHM'],
            ['name' => 'Biology', 'code' => 'BIO'],
            ['name' => 'Economics', 'code' => 'ECO'],
            ['name' => 'Computer Science', 'code' => 'CMP'],
            ['name' => 'Civic Education', 'code' => 'CVE'],
            ['name' => 'Agricultural Science', 'code' => 'AGR'],
            ['name' => 'Literature in English', 'code' => 'LIT'],
        ];

        foreach ($subjectData as $data) {
            $subject = Subject::updateOrCreate(
                ['school_id' => $this->schoolId, 'code' => $data['code']],
                [
                    'school_id' => $this->schoolId,
                    'name' => $data['name'],
                    'code' => $data['code'],
                    'description' => $data['name'].' subject',
                    'status' => true,
                ]
            );
            $this->subjects[$data['code']] = $subject;
            $this->subjectIds[$data['code']] = $subject->id;
        }
    }

    protected function createClassSubjectPivots(): void
    {
        $jssSubjects = ['MTH', 'ENG', 'BIO', 'CMP', 'CVE', 'AGR', 'LIT'];
        $ssSubjects = ['MTH', 'ENG', 'PHY', 'CHM', 'BIO', 'ECO', 'CMP', 'CVE', 'AGR', 'LIT'];

        $jssClasses = ['J1A', 'J1B', 'J2A', 'J2B', 'J3A', 'J3B'];
        $ssClasses = ['S1A', 'S1B', 'S2A', 'S2B', 'S3A', 'S3B'];

        foreach ($jssClasses as $code) {
            foreach ($jssSubjects as $subCode) {
                DB::table('class_subject')->updateOrInsert(
                    ['school_class_id' => $this->classIds[$code], 'subject_id' => $this->subjectIds[$subCode]],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }

        foreach ($ssClasses as $code) {
            foreach ($ssSubjects as $subCode) {
                DB::table('class_subject')->updateOrInsert(
                    ['school_class_id' => $this->classIds[$code], 'subject_id' => $this->subjectIds[$subCode]],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }

    protected function createTeachers(): void
    {
        $teacherData = [
            ['first_name' => 'Usman', 'last_name' => 'Bello', 'other_name' => null, 'gender' => 'male', 'email' => 'usman.bello@skulbase.edu.ng', 'phone' => '+234 802 345 6789', 'qualification' => 'M.Sc Mathematics', 'employment_date' => '2022-09-01'],
            ['first_name' => 'Fatima', 'last_name' => 'Abdullahi', 'other_name' => null, 'gender' => 'female', 'email' => 'fatima.abdullahi@skulbase.edu.ng', 'phone' => '+234 803 456 7890', 'qualification' => 'B.A English', 'employment_date' => '2021-01-15'],
            ['first_name' => 'Ibrahim', 'last_name' => 'Musa', 'other_name' => null, 'gender' => 'male', 'email' => 'ibrahim.musa@skulbase.edu.ng', 'phone' => '+234 804 567 8901', 'qualification' => 'B.Sc Biology', 'employment_date' => '2023-02-01'],
            ['first_name' => 'Aisha', 'last_name' => 'Danjuma', 'other_name' => null, 'gender' => 'female', 'email' => 'aisha.danjuma@skulbase.edu.ng', 'phone' => '+234 805 678 9012', 'qualification' => 'M.Sc Chemistry', 'employment_date' => '2022-08-15'],
            ['first_name' => 'Yusuf', 'last_name' => 'Garba', 'other_name' => null, 'gender' => 'male', 'email' => 'yusuf.garba@skulbase.edu.ng', 'phone' => '+234 806 789 0123', 'qualification' => 'B.A Political Science', 'employment_date' => '2023-01-10'],
            ['first_name' => 'Hauwa', 'last_name' => 'Suleiman', 'other_name' => null, 'gender' => 'female', 'email' => 'hauwa.suleiman@skulbase.edu.ng', 'phone' => '+234 807 890 1234', 'qualification' => 'M.Sc Computer Science', 'employment_date' => '2021-09-01'],
            ['first_name' => 'Abubakar', 'last_name' => 'Dikko', 'other_name' => null, 'gender' => 'male', 'email' => 'abubakar.dikko@skulbase.edu.ng', 'phone' => '+234 808 901 2345', 'qualification' => 'M.Sc Economics', 'employment_date' => '2022-03-01'],
            ['first_name' => 'Maryam', 'last_name' => 'Aliyu', 'other_name' => null, 'gender' => 'female', 'email' => 'maryam.aliyu@skulbase.edu.ng', 'phone' => '+234 809 012 3456', 'qualification' => 'B.A French', 'employment_date' => '2023-06-01'],
            ['first_name' => 'Salisu', 'last_name' => 'Tanko', 'other_name' => null, 'gender' => 'male', 'email' => 'salisu.tanko@skulbase.edu.ng', 'phone' => '+234 810 123 4567', 'qualification' => 'M.A History', 'employment_date' => '2022-01-15'],
            ['first_name' => 'Hadiza', 'last_name' => 'Balarabe', 'other_name' => null, 'gender' => 'female', 'email' => 'hadiza.balarabe@skulbase.edu.ng', 'phone' => '+234 811 234 5678', 'qualification' => 'B.Sc Mathematics', 'employment_date' => '2023-04-01'],
            ['first_name' => 'Garba', 'last_name' => 'Abubakar', 'other_name' => null, 'gender' => 'male', 'email' => 'garba.abubakar@skulbase.edu.ng', 'phone' => '+234 812 345 6789', 'qualification' => 'B.Sc Physical Education', 'employment_date' => '2021-11-01'],
            ['first_name' => 'Rukayya', 'last_name' => 'Ahmad', 'other_name' => null, 'gender' => 'female', 'email' => 'rukayya.ahmad@skulbase.edu.ng', 'phone' => '+234 813 456 7890', 'qualification' => 'B.A Fine Arts', 'employment_date' => '2022-06-01'],
        ];

        foreach ($teacherData as $data) {
            $teacher = Teacher::updateOrCreate(
                ['school_id' => $this->schoolId, 'email' => $data['email']],
                [
                    'school_id' => $this->schoolId,
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'other_name' => $data['other_name'],
                    'gender' => $data['gender'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'address' => 'Sokoto, Nigeria',
                    'qualification' => $data['qualification'],
                    'employment_date' => $data['employment_date'],
                    'photo' => null,
                    'status' => true,
                ]
            );
            $this->teachers[$data['first_name'].' '.$data['last_name']] = $teacher;
            $this->teacherIds[$data['first_name'].' '.$data['last_name']] = $teacher->id;
        }
    }

    protected function createTeacherSubjectPivots(): void
    {
        $assignments = [
            'Usman Bello' => ['MTH', 'PHY'],
            'Fatima Abdullahi' => ['ENG', 'LIT'],
            'Ibrahim Musa' => ['BIO', 'AGR'],
            'Aisha Danjuma' => ['CHM'],
            'Yusuf Garba' => ['CVE'],
            'Hauwa Suleiman' => ['CMP'],
            'Abubakar Dikko' => ['ECO'],
            'Maryam Aliyu' => [],
            'Salisu Tanko' => [],
            'Hadiza Balarabe' => ['MTH'],
            'Garba Abubakar' => [],
            'Rukayya Ahmad' => [],
        ];

        foreach ($assignments as $teacherName => $subjectCodes) {
            if (! isset($this->teacherIds[$teacherName])) {
                continue;
            }
            $teacherId = $this->teacherIds[$teacherName];
            foreach ($subjectCodes as $code) {
                if (isset($this->subjectIds[$code])) {
                    DB::table('teacher_subject')->updateOrInsert(
                        ['teacher_id' => $teacherId, 'subject_id' => $this->subjectIds[$code]],
                        ['created_at' => now(), 'updated_at' => now()]
                    );
                }
            }
        }
    }

    protected function createTeacherClassPivots(): void
    {
        $teacherClassMap = [
            'Usman Bello' => ['J1A', 'J1B', 'S1A', 'S1B', 'S2A', 'S2B', 'S3A', 'S3B'],
            'Fatima Abdullahi' => ['J1A', 'J1B', 'J2A', 'J2B', 'J3A', 'J3B', 'S1A', 'S1B', 'S2A', 'S2B', 'S3A', 'S3B'],
            'Ibrahim Musa' => ['J1A', 'J1B', 'J2A', 'J2B', 'S1A', 'S1B'],
            'Aisha Danjuma' => ['S1A', 'S1B', 'S2A', 'S2B', 'S3A', 'S3B'],
            'Yusuf Garba' => ['J1A', 'J1B', 'J2A', 'J2B', 'J3A', 'J3B'],
            'Hauwa Suleiman' => ['J1A', 'J1B', 'J2A', 'J2B', 'S1A', 'S1B', 'S2A', 'S2B'],
            'Abubakar Dikko' => ['S1A', 'S1B', 'S2A', 'S2B', 'S3A', 'S3B'],
            'Maryam Aliyu' => ['J1A', 'J1B', 'J2A', 'J2B'],
            'Salisu Tanko' => ['J3A', 'J3B', 'S1A', 'S1B'],
            'Hadiza Balarabe' => ['S2A', 'S2B', 'S3A', 'S3B'],
            'Garba Abubakar' => ['J1A', 'J1B', 'J2A', 'J2B', 'J3A', 'J3B', 'S1A', 'S1B', 'S2A', 'S2B', 'S3A', 'S3B'],
            'Rukayya Ahmad' => ['J2A', 'J2B', 'J3A', 'J3B'],
        ];

        foreach ($teacherClassMap as $teacherName => $classCodes) {
            if (! isset($this->teacherIds[$teacherName])) {
                continue;
            }
            $teacherId = $this->teacherIds[$teacherName];
            foreach ($classCodes as $code) {
                if (isset($this->classIds[$code])) {
                    DB::table('teacher_school_class')->updateOrInsert(
                        ['teacher_id' => $teacherId, 'school_class_id' => $this->classIds[$code]],
                        ['created_at' => now(), 'updated_at' => now()]
                    );
                }
            }
        }
    }

    protected function createStudents(): void
    {
        $studentData = [
            // JSS 1A (J1A) - 5 students
            ['first_name' => 'Abdullahi', 'last_name' => 'Bello', 'gender' => 'male', 'dob' => '2012-03-15', 'class' => 'J1A'],
            ['first_name' => 'Amina', 'last_name' => 'Yusuf', 'gender' => 'female', 'dob' => '2012-07-22', 'class' => 'J1A'],
            ['first_name' => 'Yusuf', 'last_name' => 'Ibrahim', 'gender' => 'male', 'dob' => '2012-01-10', 'class' => 'J1A'],
            ['first_name' => 'Fatima', 'last_name' => 'Ibrahim', 'gender' => 'female', 'dob' => '2012-05-30', 'class' => 'J1A'],
            ['first_name' => 'Ahmed', 'last_name' => 'Garba', 'gender' => 'male', 'dob' => '2012-09-18', 'class' => 'J1A'],

            // JSS 1B (J1B) - 4 students
            ['first_name' => 'Aisha', 'last_name' => 'Bello', 'gender' => 'female', 'dob' => '2012-04-12', 'class' => 'J1B'],
            ['first_name' => 'Aliyu', 'last_name' => 'Danjuma', 'gender' => 'male', 'dob' => '2012-08-05', 'class' => 'J1B'],
            ['first_name' => 'Halima', 'last_name' => 'Garba', 'gender' => 'female', 'dob' => '2012-11-25', 'class' => 'J1B'],
            ['first_name' => 'Ibrahim', 'last_name' => 'Musa', 'gender' => 'male', 'dob' => '2012-02-14', 'class' => 'J1B'],

            // JSS 2A (J2A) - 4 students
            ['first_name' => 'Zainab', 'last_name' => 'Musa', 'gender' => 'female', 'dob' => '2011-06-20', 'class' => 'J2A'],
            ['first_name' => 'Mohammed', 'last_name' => 'Abdullahi', 'gender' => 'male', 'dob' => '2011-01-08', 'class' => 'J2A'],
            ['first_name' => 'Hauwa', 'last_name' => 'Bello', 'gender' => 'female', 'dob' => '2011-10-15', 'class' => 'J2A'],
            ['first_name' => 'Umar', 'last_name' => 'Faruk', 'gender' => 'male', 'dob' => '2011-03-28', 'class' => 'J2A'],

            // JSS 2B (J2B) - 3 students
            ['first_name' => 'Maryam', 'last_name' => 'Yusuf', 'gender' => 'female', 'dob' => '2011-07-03', 'class' => 'J2B'],
            ['first_name' => 'Bashir', 'last_name' => 'Ahmad', 'gender' => 'male', 'dob' => '2011-12-12', 'class' => 'J2B'],
            ['first_name' => 'Hafsat', 'last_name' => 'Garba', 'gender' => 'female', 'dob' => '2011-09-09', 'class' => 'J2B'],

            // JSS 3A (J3A) - 3 students
            ['first_name' => 'Usman', 'last_name' => 'Danjuma', 'gender' => 'male', 'dob' => '2010-04-17', 'class' => 'J3A'],
            ['first_name' => 'Rukayya', 'last_name' => 'Aliyu', 'gender' => 'female', 'dob' => '2010-08-25', 'class' => 'J3A'],
            ['first_name' => 'Isah', 'last_name' => 'Tanko', 'gender' => 'male', 'dob' => '2010-11-30', 'class' => 'J3A'],

            // JSS 3B (J3B) - 2 students
            ['first_name' => 'Sa\'adatu', 'last_name' => 'Balarabe', 'gender' => 'female', 'dob' => '2010-05-14', 'class' => 'J3B'],
            ['first_name' => 'Abdulrahman', 'last_name' => 'Dikko', 'gender' => 'male', 'dob' => '2010-02-21', 'class' => 'J3B'],

            // SS 1A (S1A) - 5 students
            ['first_name' => 'Chinedu', 'last_name' => 'Okoro', 'gender' => 'male', 'dob' => '2009-06-10', 'class' => 'S1A'],
            ['first_name' => 'Obianuju', 'last_name' => 'Okoro', 'gender' => 'female', 'dob' => '2009-09-22', 'class' => 'S1A'],
            ['first_name' => 'Blessing', 'last_name' => 'Eze', 'gender' => 'female', 'dob' => '2009-01-15', 'class' => 'S1A'],
            ['first_name' => 'Musa', 'last_name' => 'Danjuma Jr.', 'gender' => 'male', 'dob' => '2009-03-08', 'class' => 'S1A'],
            ['first_name' => 'Fadila', 'last_name' => 'Danjuma', 'gender' => 'female', 'dob' => '2009-07-19', 'class' => 'S1A'],

            // SS 1B (S1B) - 3 students
            ['first_name' => 'Tijjani', 'last_name' => 'Abubakar', 'gender' => 'male', 'dob' => '2009-04-05', 'class' => 'S1B'],
            ['first_name' => 'Hadiza', 'last_name' => 'Musa', 'gender' => 'female', 'dob' => '2009-10-30', 'class' => 'S1B'],
            ['first_name' => 'Abdulaziz', 'last_name' => 'Garba', 'gender' => 'male', 'dob' => '2009-12-12', 'class' => 'S1B'],

            // SS 2A (S2A) - 5 students
            ['first_name' => 'Nnamdi', 'last_name' => 'Okoro Jr.', 'gender' => 'male', 'dob' => '2008-02-14', 'class' => 'S2A'],
            ['first_name' => 'Adaeze', 'last_name' => 'Eze', 'gender' => 'female', 'dob' => '2008-06-28', 'class' => 'S2A'],
            ['first_name' => 'Ibrahim', 'last_name' => 'Abubakar', 'gender' => 'male', 'dob' => '2008-09-03', 'class' => 'S2A'],
            ['first_name' => 'Safiya', 'last_name' => 'Bello', 'gender' => 'female', 'dob' => '2008-01-20', 'class' => 'S2A'],
            ['first_name' => 'Yakubu', 'last_name' => 'Ahmad', 'gender' => 'male', 'dob' => '2008-04-11', 'class' => 'S2A'],

            // SS 2B (S2B) - 3 students
            ['first_name' => 'Chidinma', 'last_name' => 'Eze Jr.', 'gender' => 'female', 'dob' => '2008-08-16', 'class' => 'S2B'],
            ['first_name' => 'Lawal', 'last_name' => 'Garba', 'gender' => 'male', 'dob' => '2008-03-25', 'class' => 'S2B'],
            ['first_name' => 'Amina', 'last_name' => 'Aliyu', 'gender' => 'female', 'dob' => '2008-11-07', 'class' => 'S2B'],

            // SS 3A (S3A) - 3 students
            ['first_name' => 'Emeka', 'last_name' => 'Okoro III', 'gender' => 'male', 'dob' => '2007-05-18', 'class' => 'S3A'],
            ['first_name' => 'Ngozi', 'last_name' => 'Okoro', 'gender' => 'female', 'dob' => '2007-10-02', 'class' => 'S3A'],
            ['first_name' => 'Ishaq', 'last_name' => 'Bello', 'gender' => 'male', 'dob' => '2007-07-27', 'class' => 'S3A'],

            // SS 3B (S3B) - 2 students
            ['first_name' => 'Nneka', 'last_name' => 'Eze III', 'gender' => 'female', 'dob' => '2007-09-14', 'class' => 'S3B'],
            ['first_name' => 'Yakubu', 'last_name' => 'Dikko', 'gender' => 'male', 'dob' => '2007-12-01', 'class' => 'S3B'],
        ];

        $admissionCounter = 1;
        foreach ($studentData as $data) {
            $classId = $this->classIds[$data['class']];
            $sectionCode = substr($data['class'], -1);
            $sectionId = $this->sectionIds[$sectionCode === 'A' ? 'Section A' : 'Section B'];

            $student = Student::updateOrCreate(
                ['school_id' => $this->schoolId, 'first_name' => $data['first_name'], 'last_name' => $data['last_name']],
                [
                    'school_id' => $this->schoolId,
                    'admission_number' => 'ADM/2026/'.str_pad($admissionCounter, 3, '0', STR_PAD_LEFT),
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'gender' => $data['gender'],
                    'date_of_birth' => $data['dob'],
                    'email' => strtolower($data['first_name'].'.'.$data['last_name']).'@student.skulbase.edu.ng',
                    'phone' => null,
                    'address' => 'Sokoto, Nigeria',
                    'class' => $data['class'],
                    'school_class_id' => $classId,
                    'section_id' => $sectionId,
                    'status' => 'active',
                ]
            );
            $this->students[] = $student;
            $admissionCounter++;
        }
    }

    protected function createParents(): void
    {
        $parentRecords = [
            [
                'first_name' => 'Chinedu', 'last_name' => 'Okoro', 'email' => 'chinedu.okoro@email.com',
                'phone' => '+234 814 567 8901', 'student_names' => [['Chinedu', 'Okoro'], ['Obianuju', 'Okoro']],
            ],
            [
                'first_name' => 'Blessing', 'last_name' => 'Eze', 'email' => 'blessing.eze@email.com',
                'phone' => '+234 815 678 9012', 'student_names' => [['Blessing', 'Eze']],
            ],
            [
                'first_name' => 'Musa', 'last_name' => 'Danjuma', 'email' => 'musa.danjuma@email.com',
                'phone' => '+234 816 789 0123', 'student_names' => [['Musa', 'Danjuma Jr.'], ['Fadila', 'Danjuma']],
            ],
            [
                'first_name' => 'Fatima', 'last_name' => 'Abubakar', 'email' => 'fatima.abubakar@email.com',
                'phone' => '+234 817 890 1234', 'student_names' => [['Tijjani', 'Abubakar']],
            ],
        ];

        foreach ($parentRecords as $pData) {
            $user = User::where('email', $pData['email'])->first();
            if (! $user) {
                continue;
            }

            $parent = ParentModel::updateOrCreate(
                ['school_id' => $this->schoolId, 'user_id' => $user->id],
                [
                    'school_id' => $this->schoolId,
                    'user_id' => $user->id,
                    'first_name' => $pData['first_name'],
                    'last_name' => $pData['last_name'],
                    'email' => $pData['email'],
                    'phone' => $pData['phone'],
                    'address' => 'Sokoto, Nigeria',
                    'status' => true,
                ]
            );

            foreach ($pData['student_names'] as [$firstName, $lastName]) {
                $student = Student::where('school_id', $this->schoolId)
                    ->where('first_name', $firstName)
                    ->where('last_name', $lastName)
                    ->first();

                if ($student) {
                    DB::table('parent_student')->updateOrInsert(
                        ['parent_id' => $parent->id, 'student_id' => $student->id],
                        ['created_at' => now(), 'updated_at' => now()]
                    );
                }
            }

            $this->parentData[] = ['user_id' => $user->id, 'parent' => $parent];
        }
    }

    protected function createPeriods(): void
    {
        $periodData = [
            ['name' => 'First Assembly', 'type' => 'assembly', 'start' => '07:30', 'end' => '08:00', 'duration' => 30, 'order' => 1],
            ['name' => 'First Period', 'type' => 'academic', 'start' => '08:00', 'end' => '08:40', 'duration' => 40, 'order' => 2],
            ['name' => 'Second Period', 'type' => 'academic', 'start' => '08:45', 'end' => '09:25', 'duration' => 40, 'order' => 3],
            ['name' => 'Third Period', 'type' => 'academic', 'start' => '09:30', 'end' => '10:10', 'duration' => 40, 'order' => 4],
            ['name' => 'Break', 'type' => 'break', 'start' => '10:10', 'end' => '10:30', 'duration' => 20, 'order' => 5],
            ['name' => 'Fourth Period', 'type' => 'academic', 'start' => '10:30', 'end' => '11:10', 'duration' => 40, 'order' => 6],
            ['name' => 'Fifth Period', 'type' => 'academic', 'start' => '11:15', 'end' => '11:55', 'duration' => 40, 'order' => 7],
            ['name' => 'Lunch', 'type' => 'lunch', 'start' => '12:00', 'end' => '12:40', 'duration' => 40, 'order' => 8],
            ['name' => 'Sixth Period', 'type' => 'academic', 'start' => '12:45', 'end' => '13:25', 'duration' => 40, 'order' => 9],
            ['name' => 'Seventh Period', 'type' => 'academic', 'start' => '13:30', 'end' => '14:10', 'duration' => 40, 'order' => 10],
        ];

        foreach ($periodData as $data) {
            $period = Period::updateOrCreate(
                ['school_id' => $this->schoolId, 'name' => $data['name']],
                [
                    'school_id' => $this->schoolId,
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'start_time' => $data['start'],
                    'end_time' => $data['end'],
                    'duration_minutes' => $data['duration'],
                    'sort_order' => $data['order'],
                    'status' => true,
                ]
            );
            $this->periods[$data['name']] = $period;
        }
    }

    protected function createTimetables(): void
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $academicPeriods = ['First Period', 'Second Period', 'Third Period', 'Fourth Period', 'Fifth Period', 'Sixth Period', 'Seventh Period'];

        // JSS1A: Full week, 7 periods per day — each day has a unique subject order
        $jss1aSchedule = [
            'Monday' => ['MTH', 'ENG', 'BIO', 'CMP', 'CVE', 'AGR', 'LIT'],
            'Tuesday' => ['ENG', 'MTH', 'CMP', 'BIO', 'LIT', 'CVE', 'AGR'],
            'Wednesday' => ['BIO', 'CMP', 'MTH', 'ENG', 'AGR', 'LIT', 'CVE'],
            'Thursday' => ['CMP', 'ENG', 'CVE', 'MTH', 'BIO', 'AGR', 'LIT'],
            'Friday' => ['LIT', 'BIO', 'AGR', 'CVE', 'MTH', 'CMP', 'ENG'],
        ];

        $teacherMap = [
            'MTH' => 'Usman Bello', 'ENG' => 'Fatima Abdullahi', 'BIO' => 'Ibrahim Musa',
            'CMP' => 'Hauwa Suleiman', 'CVE' => 'Yusuf Garba', 'AGR' => 'Ibrahim Musa',
            'LIT' => 'Fatima Abdullahi', 'PHY' => 'Usman Bello', 'CHM' => 'Aisha Danjuma',
            'ECO' => 'Abubakar Dikko',
        ];

        foreach ($days as $day) {
            $subjects = $jss1aSchedule[$day];
            for ($i = 0; $i < count($academicPeriods); $i++) {
                $this->safeTimetableInsert(
                    $this->classIds['J1A'], $this->sectionIds['Section A'],
                    $day, $this->periods[$academicPeriods[$i]]->id,
                    $this->subjectIds[$subjects[$i]], $this->teacherIds[$teacherMap[$subjects[$i]]]
                );
            }
        }

        // SS1A: Mon/Wed/Fri — periods 6-7 only (when JSS1A has non-conflicting teachers)
        $ss1aSchedule = [
            'Monday' => ['PHY', 'ECO'],
            'Wednesday' => ['ECO', 'CMP'],
            'Friday' => ['CHM', 'CMP'],
        ];
        $ss1aTeachers = ['PHY' => 'Usman Bello', 'ECO' => 'Abubakar Dikko', 'CMP' => 'Hauwa Suleiman', 'CHM' => 'Aisha Danjuma'];

        foreach ($ss1aSchedule as $day => $subjects) {
            $periodsToUse = ['Sixth Period', 'Seventh Period'];
            for ($i = 0; $i < count($subjects); $i++) {
                $this->safeTimetableInsert(
                    $this->classIds['S1A'], $this->sectionIds['Section A'],
                    $day, $this->periods[$periodsToUse[$i]]->id,
                    $this->subjectIds[$subjects[$i]], $this->teacherIds[$ss1aTeachers[$subjects[$i]]]
                );
            }
        }

        // SS2A: Tue/Thu — periods 6-7 only
        $ss2aSchedule = [
            'Tuesday' => ['PHY', 'CHM'],
            'Thursday' => ['ECO', 'PHY'],
        ];
        $ss2aTeachers = ['PHY' => 'Usman Bello', 'CHM' => 'Aisha Danjuma', 'ECO' => 'Abubakar Dikko'];

        foreach ($ss2aSchedule as $day => $subjects) {
            $periodsToUse = ['Sixth Period', 'Seventh Period'];
            for ($i = 0; $i < count($subjects); $i++) {
                $this->safeTimetableInsert(
                    $this->classIds['S2A'], $this->sectionIds['Section A'],
                    $day, $this->periods[$periodsToUse[$i]]->id,
                    $this->subjectIds[$subjects[$i]], $this->teacherIds[$ss2aTeachers[$subjects[$i]]]
                );
            }
        }
    }

    protected function safeTimetableInsert(int $classId, int $sectionId, string $day, int $periodId, int $subjectId, int $teacherId): void
    {
        try {
            DB::table('timetables')->updateOrInsert(
                ['class_id' => $classId, 'section_id' => $sectionId, 'day' => $day, 'period_id' => $periodId],
                [
                    'school_id' => $this->schoolId,
                    'subject_id' => $subjectId,
                    'teacher_id' => $teacherId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        } catch (\Exception $e) {
            // Skip if teacher is already booked at this day+period
        }
    }

    protected function createAssessmentTypes(): void
    {
        $types = [
            ['name' => 'Test 1', 'percentage' => 10.00],
            ['name' => 'Test 2', 'percentage' => 10.00],
            ['name' => 'Assignment', 'percentage' => 10.00],
            ['name' => 'Mid-Term Exam', 'percentage' => 20.00],
            ['name' => 'Final Exam', 'percentage' => 50.00],
        ];

        foreach ($types as $data) {
            $type = AssessmentType::updateOrCreate(
                ['school_id' => $this->schoolId, 'name' => $data['name']],
                [
                    'school_id' => $this->schoolId,
                    'name' => $data['name'],
                    'percentage' => $data['percentage'],
                    'status' => true,
                ]
            );
            $this->assessmentTypes[$data['name']] = $type;
        }
    }

    protected function createExams(): void
    {
        $examData = [
            ['name' => 'First Term Examination 2025/2026', 'term' => 'First Term', 'session' => '2025/2026', 'start' => '2025-12-01', 'end' => '2025-12-12'],
            ['name' => 'Second Term Examination 2025/2026', 'term' => 'Second Term', 'session' => '2025/2026', 'start' => '2026-04-06', 'end' => '2026-04-17'],
        ];

        foreach ($examData as $data) {
            $exam = Exam::updateOrCreate(
                ['school_id' => $this->schoolId, 'name' => $data['name']],
                [
                    'school_id' => $this->schoolId,
                    'name' => $data['name'],
                    'term' => $data['term'],
                    'session' => $data['session'],
                    'start_date' => $data['start'],
                    'end_date' => $data['end'],
                    'status' => true,
                ]
            );
            $this->exams[$data['name']] = $exam;
        }
    }

    protected function createGradingSystems(): void
    {
        $grades = [
            ['min' => 70.00, 'max' => 100.00, 'grade' => 'A', 'remark' => 'Excellent', 'point' => 5.00],
            ['min' => 60.00, 'max' => 69.99, 'grade' => 'B', 'remark' => 'Very Good', 'point' => 4.00],
            ['min' => 50.00, 'max' => 59.99, 'grade' => 'C', 'remark' => 'Good', 'point' => 3.00],
            ['min' => 40.00, 'max' => 49.99, 'grade' => 'D', 'remark' => 'Pass', 'point' => 2.00],
            ['min' => 0.00, 'max' => 39.99, 'grade' => 'F', 'remark' => 'Fail', 'point' => 0.00],
        ];

        foreach ($grades as $data) {
            GradingSystem::updateOrCreate(
                ['school_id' => $this->schoolId, 'min_score' => $data['min'], 'max_score' => $data['max']],
                [
                    'school_id' => $this->schoolId,
                    'min_score' => $data['min'],
                    'max_score' => $data['max'],
                    'grade' => $data['grade'],
                    'remark' => $data['remark'],
                    'grade_point' => $data['point'],
                ]
            );
        }
    }

    protected function createFeeStructures(): void
    {
        $feeMap = [
            'J1A' => ['Tuition' => 150000, 'Development Levy' => 20000, 'Uniform' => 15000],
            'J1B' => ['Tuition' => 150000, 'Development Levy' => 20000, 'Uniform' => 15000],
            'J2A' => ['Tuition' => 155000, 'Development Levy' => 20000, 'Uniform' => 15000],
            'J2B' => ['Tuition' => 155000, 'Development Levy' => 20000, 'Uniform' => 15000],
            'J3A' => ['Tuition' => 160000, 'Development Levy' => 22000, 'Uniform' => 15000],
            'J3B' => ['Tuition' => 160000, 'Development Levy' => 22000, 'Uniform' => 15000],
            'S1A' => ['Tuition' => 180000, 'Development Levy' => 25000, 'Uniform' => 18000],
            'S1B' => ['Tuition' => 180000, 'Development Levy' => 25000, 'Uniform' => 18000],
            'S2A' => ['Tuition' => 185000, 'Development Levy' => 25000, 'Uniform' => 18000],
            'S2B' => ['Tuition' => 185000, 'Development Levy' => 25000, 'Uniform' => 18000],
            'S3A' => ['Tuition' => 190000, 'Development Levy' => 28000, 'Uniform' => 18000],
            'S3B' => ['Tuition' => 190000, 'Development Levy' => 28000, 'Uniform' => 18000],
        ];

        foreach ($feeMap as $classCode => $fees) {
            $classId = $this->classIds[$classCode];
            foreach ($fees as $title => $amount) {
                FeeStructure::updateOrCreate(
                    ['school_id' => $this->schoolId, 'school_class_id' => $classId, 'title' => $title],
                    [
                        'school_id' => $this->schoolId,
                        'school_class_id' => $classId,
                        'title' => $title,
                        'amount' => $amount,
                        'description' => $title.' for Second Term 2025/2026',
                        'term' => 'Second Term',
                        'session' => '2025/2026',
                        'due_date' => '2026-02-15',
                        'status' => true,
                    ]
                );
            }
        }
    }

    protected function createFeePayments(): void
    {
        $paymentMethods = ['cash', 'transfer', 'card'];
        $schoolAdminUserId = $this->schoolAdminUserId;
        $studentCount = count($this->students);

        foreach ($this->students as $index => $student) {
            $classCode = $student->class;
            $feeStructures = FeeStructure::where('school_id', $this->schoolId)
                ->where('school_class_id', $student->school_class_id)
                ->get();

            if ($feeStructures->isEmpty()) {
                continue;
            }

            $totalFees = $feeStructures->sum('amount');
            $rand = mt_rand(1, 100);

            if ($rand <= 20) {
                // 20% - full payment on all fee structures
                foreach ($feeStructures as $fs) {
                    FeePayment::updateOrCreate(
                        ['school_id' => $this->schoolId, 'student_id' => $student->id, 'fee_structure_id' => $fs->id],
                        [
                            'school_id' => $this->schoolId,
                            'student_id' => $student->id,
                            'fee_structure_id' => $fs->id,
                            'amount_paid' => $fs->amount,
                            'payment_date' => Carbon::now()->subDays(mt_rand(1, 30))->format('Y-m-d'),
                            'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                            'reference' => 'PAY/'.strtoupper(Str::random(8)),
                            'remarks' => 'Full payment',
                            'recorded_by' => $schoolAdminUserId,
                        ]
                    );
                }
            } elseif ($rand <= 80) {
                // 60% - partial payment (50-90%)
                foreach ($feeStructures as $fs) {
                    $percentage = mt_rand(50, 90) / 100;
                    $amountPaid = round($fs->amount * $percentage, 2);
                    FeePayment::updateOrCreate(
                        ['school_id' => $this->schoolId, 'student_id' => $student->id, 'fee_structure_id' => $fs->id],
                        [
                            'school_id' => $this->schoolId,
                            'student_id' => $student->id,
                            'fee_structure_id' => $fs->id,
                            'amount_paid' => $amountPaid,
                            'payment_date' => Carbon::now()->subDays(mt_rand(1, 45))->format('Y-m-d'),
                            'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                            'reference' => 'PAY/'.strtoupper(Str::random(8)),
                            'remarks' => 'Partial payment ('.round($percentage * 100).'%)',
                            'recorded_by' => $schoolAdminUserId,
                        ]
                    );
                }
            }
            // 20% - no payments (outstanding)
        }
    }

    protected function createAttendance(): void
    {
        $attendanceClasses = ['J1A', 'S1A', 'S2A'];
        $statuses = ['present', 'present', 'present', 'present', 'present', 'present', 'present', 'absent', 'late', 'excused'];
        $teacherIds = array_values($this->teacherIds);

        $today = Carbon::now();
        $schoolDays = [];
        $checkDate = $today->copy()->subDays(20);

        while (count($schoolDays) < 15) {
            if (! $checkDate->isSaturday() && ! $checkDate->isSunday()) {
                $schoolDays[] = $checkDate->copy()->format('Y-m-d');
            }
            $checkDate->addDay();
        }

        foreach ($attendanceClasses as $classCode) {
            $classId = $this->classIds[$classCode];
            $classStudents = array_filter($this->students, fn ($s) => $s->class === $classCode);

            foreach ($schoolDays as $date) {
                foreach ($classStudents as $student) {
                    $status = $statuses[array_rand($statuses)];
                    Attendance::updateOrCreate(
                        ['student_id' => $student->id, 'attendance_date' => $date],
                        [
                            'school_id' => $this->schoolId,
                            'student_id' => $student->id,
                            'school_class_id' => $classId,
                            'attendance_date' => $date,
                            'status' => $status,
                            'remarks' => $status === 'excused' ? 'Medical appointment' : null,
                            'marked_by' => $teacherIds[array_rand($teacherIds)],
                        ]
                    );
                }
            }
        }
    }

    protected function createStudentResults(): void
    {
        $firstTermExam = $this->exams['First Term Examination 2025/2026'];
        $assessmentTypes = array_values($this->assessmentTypes);

        $resultSubjectsByClass = [
            'J1A' => ['MTH', 'ENG', 'BIO', 'CMP', 'CVE'],
            'S1A' => ['MTH', 'ENG', 'PHY', 'CHM', 'BIO', 'ECO', 'CMP'],
            'S2A' => ['MTH', 'ENG', 'PHY', 'CHM', 'BIO', 'ECO', 'CMP'],
        ];

        $teacherSubjectMap = [
            'MTH' => 'Usman Bello',
            'ENG' => 'Fatima Abdullahi',
            'PHY' => 'Usman Bello',
            'CHM' => 'Aisha Danjuma',
            'BIO' => 'Ibrahim Musa',
            'ECO' => 'Abubakar Dikko',
            'CMP' => 'Hauwa Suleiman',
            'CVE' => 'Yusuf Garba',
            'AGR' => 'Ibrahim Musa',
            'LIT' => 'Fatima Abdullahi',
        ];

        foreach ($resultSubjectsByClass as $classCode => $subjectCodes) {
            $classStudents = array_filter($this->students, fn ($s) => $s->class === $classCode);

            foreach ($classStudents as $student) {
                foreach ($subjectCodes as $subjectCode) {
                    $subjectId = $this->subjectIds[$subjectCode];
                    $teacherId = $this->teacherIds[$teacherSubjectMap[$subjectCode]] ?? null;

                    foreach ($assessmentTypes as $at) {
                        $baseScore = mt_rand(35, 85);
                        if ($at->name === 'Test 1') {
                            $score = min(10, round($baseScore * 0.12, 2));
                        } elseif ($at->name === 'Test 2') {
                            $score = min(10, round($baseScore * 0.11, 2));
                        } elseif ($at->name === 'Assignment') {
                            $score = min(10, round($baseScore * 0.11, 2));
                        } elseif ($at->name === 'Mid-Term Exam') {
                            $score = min(20, round($baseScore * 0.22, 2));
                        } else {
                            $score = min(50, round($baseScore * 0.55, 2));
                        }

                        $score = max(0, min($at->percentage, $score));

                        StudentResult::updateOrCreate(
                            ['student_id' => $student->id, 'exam_id' => $firstTermExam->id, 'subject_id' => $subjectId, 'assessment_type_id' => $at->id],
                            [
                                'school_id' => $this->schoolId,
                                'exam_id' => $firstTermExam->id,
                                'student_id' => $student->id,
                                'school_class_id' => $student->school_class_id,
                                'subject_id' => $subjectId,
                                'assessment_type_id' => $at->id,
                                'teacher_id' => $teacherId,
                                'score' => $score,
                                'remarks' => null,
                            ]
                        );
                    }
                }
            }
        }
    }

    protected function createReportCards(): void
    {
        $firstTermExam = $this->exams['First Term Examination 2025/2026'];
        $gradingSystem = GradingSystem::where('school_id', $this->schoolId)->get();

        $reportClasses = ['J1A', 'S1A', 'S2A'];
        $schoolAdminUserId = $this->schoolAdminUserId;

        foreach ($reportClasses as $classCode) {
            $classId = $this->classIds[$classCode];
            $classStudents = array_filter($this->students, fn ($s) => $s->class === $classCode);
            $studentAverages = [];

            foreach ($classStudents as $student) {
                $results = StudentResult::where('student_id', $student->id)
                    ->where('exam_id', $firstTermExam->id)
                    ->get();

                if ($results->isEmpty()) {
                    continue;
                }

                $totalScore = $results->sum('score');
                $subjectCount = $results->groupBy('subject_id')->count();
                $totalMax = 0;
                foreach ($results->groupBy('subject_id') as $subjectResults) {
                    $totalMax += $subjectResults->sum(fn ($r) => $r->assessment_type->percentage ?? 10);
                }

                $averageScore = $totalMax > 0 ? round(($totalScore / $totalMax) * 100, 2) : 0;

                $overallGrade = 'F';
                $overallRemark = 'Fail';
                foreach ($gradingSystem as $grade) {
                    if ($averageScore >= $grade->min_score && $averageScore <= $grade->max_score) {
                        $overallGrade = $grade->grade;
                        $overallRemark = $grade->remark;
                        break;
                    }
                }

                $subjectsPassed = 0;
                $subjectsFailed = 0;
                foreach ($results->groupBy('subject_id') as $subjectResults) {
                    $subjectTotal = $subjectResults->sum('score');
                    $subjectMax = $subjectResults->sum(fn ($r) => $r->assessment_type->percentage ?? 10);
                    $subjectAverage = $subjectMax > 0 ? ($subjectTotal / $subjectMax) * 100 : 0;
                    if ($subjectAverage >= 40) {
                        $subjectsPassed++;
                    } else {
                        $subjectsFailed++;
                    }
                }

                $studentAverages[$student->id] = [
                    'total_score' => $totalScore,
                    'average_score' => $averageScore,
                    'overall_grade' => $overallGrade,
                    'overall_remark' => $overallRemark,
                    'total_subjects' => $subjectCount,
                    'subjects_passed' => $subjectsPassed,
                    'subjects_failed' => $subjectsFailed,
                ];
            }

            // Sort by average score descending to assign positions
            uasort($studentAverages, fn ($a, $b) => $b['average_score'] <=> $a['average_score']);
            $position = 1;
            foreach ($studentAverages as $studentId => &$data) {
                $data['class_position'] = $position;
                $position++;
            }
            unset($data);

            $comments = [
                'Excellent' => ['teacher' => 'A outstanding student who consistently excels in all areas.', 'principal' => 'Keep up the excellent work. You are a role model.'],
                'Very Good' => ['teacher' => 'A very diligent student with strong academic performance.', 'principal' => 'Well done. Continue to aim higher.'],
                'Good' => ['teacher' => 'A good student who is making steady progress.', 'principal' => 'Good performance. Keep striving for excellence.'],
                'Pass' => ['teacher' => 'An average student who needs to put in more effort.', 'principal' => 'With more dedication, you can achieve much more.'],
                'Fail' => ['teacher' => 'This student needs serious improvement and extra attention.', 'principal' => 'Please see me for a discussion on improvement plans.'],
            ];

            foreach ($studentAverages as $studentId => $data) {
                $student = Student::find($studentId);
                if (! $student) {
                    continue;
                }

                $attendanceRecords = Attendance::where('student_id', $studentId)
                    ->where('school_class_id', $classId)
                    ->get();
                $totalDays = $attendanceRecords->count();
                $presentDays = $attendanceRecords->where('status', 'present')->count();
                $lateDays = $attendanceRecords->where('status', 'late')->count();
                $attendancePercentage = $totalDays > 0 ? round(($presentDays + $lateDays * 0.5) / $totalDays * 100, 2) : 0;

                $gradeComments = $comments[$data['overall_remark']] ?? $comments['Pass'];

                StudentReportCard::updateOrCreate(
                    ['student_id' => $studentId, 'exam_id' => $firstTermExam->id],
                    [
                        'school_id' => $this->schoolId,
                        'exam_id' => $firstTermExam->id,
                        'student_id' => $studentId,
                        'school_class_id' => $classId,
                        'total_score' => $data['total_score'],
                        'average_score' => $data['average_score'],
                        'overall_grade' => $data['overall_grade'],
                        'overall_remark' => $data['overall_remark'],
                        'class_position' => $data['class_position'],
                        'total_subjects' => $data['total_subjects'],
                        'subjects_passed' => $data['subjects_passed'],
                        'subjects_failed' => $data['subjects_failed'],
                        'attendance_percentage' => $attendancePercentage,
                        'teacher_comment' => $gradeComments['teacher'],
                        'principal_comment' => $gradeComments['principal'],
                        'status' => 'published',
                        'published_at' => now(),
                        'submitted_by' => $schoolAdminUserId,
                        'submitted_at' => now(),
                        'approved_by' => $schoolAdminUserId,
                        'approved_at' => now(),
                        'published_by' => $schoolAdminUserId,
                    ]
                );
            }
        }
    }

    protected function createAssignments(): void
    {
        $assignmentData = [
            [
                'title' => 'Quadratic Equations Worksheet',
                'description' => 'Solve all 20 quadratic equations using factorization and quadratic formula methods.',
                'instructions' => 'Show all working steps. Submit in the mathematics assignment file.',
                'subject' => 'MTH', 'teacher' => 'Usman Bello', 'class' => 'S1A',
                'total_marks' => 20, 'due_date' => Carbon::now()->addDays(14)->format('Y-m-d'),
            ],
            [
                'title' => 'Essay: My Dream Career',
                'description' => 'Write a 500-word essay about your dream career and why you chose it.',
                'instructions' => 'Use proper essay format with introduction, body, and conclusion. Handwritten submissions only.',
                'subject' => 'ENG', 'teacher' => 'Fatima Abdullahi', 'class' => 'J1A',
                'total_marks' => 20, 'due_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
            ],
            [
                'title' => 'Lab Report: Chemical Reactions',
                'description' => 'Write a detailed lab report on the chemical reactions observed during the practical session.',
                'instructions' => 'Include aim, materials, procedure, observations, and conclusion. Typed reports preferred.',
                'subject' => 'CHM', 'teacher' => 'Aisha Danjuma', 'class' => 'S2A',
                'total_marks' => 30, 'due_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
            ],
            [
                'title' => 'Biology Cell Structure Diagram',
                'description' => 'Draw and label a detailed diagram of plant and animal cell structures.',
                'instructions' => 'Use A4 graph paper. Include at least 8 labeled parts for each cell type.',
                'subject' => 'BIO', 'teacher' => 'Ibrahim Musa', 'class' => 'S1A',
                'total_marks' => 20, 'due_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            ],
            [
                'title' => 'Data Processing Assignment',
                'description' => 'Create a spreadsheet to calculate student grades using Microsoft Excel.',
                'instructions' => 'Use formulas for auto-calculation. Submit on a flash drive.',
                'subject' => 'CMP', 'teacher' => 'Hauwa Suleiman', 'class' => 'S1A',
                'total_marks' => 25, 'due_date' => Carbon::now()->addDays(12)->format('Y-m-d'),
            ],
            [
                'title' => 'Elasticity of Demand Problem Set',
                'description' => 'Solve all 15 problems on price elasticity of demand and supply.',
                'instructions' => 'Show diagrams where applicable. Submit in the economics classwork file.',
                'subject' => 'ECO', 'teacher' => 'Abubakar Dikko', 'class' => 'S2A',
                'total_marks' => 15, 'due_date' => Carbon::now()->addDays(8)->format('Y-m-d'),
            ],
            [
                'title' => 'Civic Education: Rights of Citizens',
                'description' => 'Write an essay on the fundamental rights of Nigerian citizens as guaranteed by the 1999 Constitution.',
                'instructions' => 'Minimum 300 words. Reference specific sections of the Constitution.',
                'subject' => 'CVE', 'teacher' => 'Yusuf Garba', 'class' => 'J2A',
                'total_marks' => 20, 'due_date' => Carbon::now()->addDays(6)->format('Y-m-d'),
            ],
            [
                'title' => 'Mathematics: Simultaneous Equations',
                'description' => 'Solve 10 pairs of simultaneous equations using substitution and elimination methods.',
                'instructions' => 'Show all steps clearly. Use exercise book, not loose sheets.',
                'subject' => 'MTH', 'teacher' => 'Usman Bello', 'class' => 'J1A',
                'total_marks' => 10, 'due_date' => Carbon::now()->addDays(9)->format('Y-m-d'),
            ],
        ];

        foreach ($assignmentData as $data) {
            Assignment::create([
                'school_id' => $this->schoolId,
                'teacher_id' => $this->teacherIds[$data['teacher']],
                'class_id' => $this->classIds[$data['class']],
                'subject_id' => $this->subjectIds[$data['subject']],
                'title' => $data['title'],
                'description' => $data['description'],
                'instructions' => $data['instructions'],
                'attachment' => null,
                'total_marks' => $data['total_marks'],
                'status' => 'published',
                'due_date' => $data['due_date'],
            ]);
        }
    }

    protected function createAnnouncements(): void
    {
        $announcementData = [
            [
                'title' => 'Welcome Back to School — Second Term 2025/2026',
                'message' => 'Dear parents, students, and staff, we welcome you back to Skulbase International School for the Second Term of the 2025/2026 academic session. We pray for a productive and successful term. School resumes on Monday, 6th January 2026. Classes begin at 8:00 AM sharp. Please ensure all students come with their complete uniform and learning materials.',
                'audience' => 'everyone',
                'expires_at' => Carbon::now()->addMonths(3),
            ],
            [
                'title' => 'PTA Meeting Notice — January 2026',
                'message' => 'This is to inform all parents that the Parent-Teacher Association meeting has been scheduled for Saturday, 25th January 2026 at 10:00 AM in the school main hall. All parents are strongly encouraged to attend. Agenda items include: Academic performance review, School development plans, Fee payment discussions, and Inter-house sports planning.',
                'audience' => 'parents',
                'expires_at' => Carbon::now()->addMonths(1),
            ],
            [
                'title' => 'Mid-Term Break Schedule',
                'message' => 'Please be informed that the mid-term break will begin on Friday, 14th February 2026 and classes resume on Monday, 24th February 2026. Students are expected to complete all assigned homework during the break. We wish everyone a restful and productive break.',
                'audience' => 'everyone',
                'expires_at' => Carbon::now()->addMonths(2),
            ],
            [
                'title' => 'Staff Meeting — Curriculum Review',
                'message' => 'All teaching staff are required to attend a mandatory staff meeting on Wednesday, 22nd January 2026 at 3:00 PM in the staff room. The meeting will focus on mid-term assessment preparation, curriculum coverage review, and the upcoming inter-house sports event. Attendance is compulsory.',
                'audience' => 'teachers',
                'expires_at' => Carbon::now()->addWeeks(3),
            ],
            [
                'title' => 'Annual Inter-House Sports Competition',
                'message' => 'Skulbase International School is pleased to announce the Annual Inter-House Sports Competition scheduled for Friday, 7th March 2026 at the school sports field. Events include: 100m, 200m, 400m races, relay, long jump, high jump, shot put, and tug-of-war. All students are encouraged to participate. Parents are warmly invited to attend and cheer their children.',
                'audience' => 'everyone',
                'expires_at' => Carbon::now()->addMonths(2),
            ],
        ];

        foreach ($announcementData as $data) {
            Announcement::create([
                'school_id' => $this->schoolId,
                'user_id' => $this->schoolAdminUserId,
                'title' => $data['title'],
                'message' => $data['message'],
                'audience' => $data['audience'],
                'attachment' => null,
                'status' => 'published',
                'expires_at' => $data['expires_at'],
            ]);
        }
    }

    protected function createEvents(): void
    {
        $eventData = [
            [
                'title' => 'Mid-Term Break',
                'description' => 'School will be closed for mid-term break. Students should use this time to rest and complete assignments.',
                'event_type' => 'holiday',
                'event_date' => '2026-02-14',
                'start_time' => null,
                'end_time' => null,
                'location' => null,
            ],
            [
                'title' => 'PTA Meeting',
                'description' => 'Parent-Teacher Association meeting to discuss academic progress and school development.',
                'event_type' => 'meeting',
                'event_date' => '2026-01-25',
                'start_time' => '10:00',
                'end_time' => '12:00',
                'location' => 'School Main Hall',
            ],
            [
                'title' => 'Inter-House Sports Competition',
                'description' => 'Annual inter-house sports competition featuring track and field events.',
                'event_type' => 'sports',
                'event_date' => '2026-03-07',
                'start_time' => '08:00',
                'end_time' => '15:00',
                'location' => 'School Sports Field',
            ],
            [
                'title' => 'Second Term Examination',
                'description' => 'Second term examinations for all classes. Students should begin revision early.',
                'event_type' => 'exam',
                'event_date' => '2026-04-06',
                'start_time' => '08:00',
                'end_time' => '14:00',
                'location' => 'Examination Halls',
            ],
            [
                'title' => 'Christmas Break',
                'description' => 'School will be closed for Christmas and New Year break. Classes resume January 6, 2026.',
                'event_type' => 'holiday',
                'event_date' => '2025-12-19',
                'start_time' => null,
                'end_time' => null,
                'location' => null,
            ],
        ];

        foreach ($eventData as $data) {
            Event::create([
                'school_id' => $this->schoolId,
                'user_id' => $this->schoolAdminUserId,
                'title' => $data['title'],
                'description' => $data['description'],
                'event_type' => $data['event_type'],
                'event_date' => $data['event_date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'location' => $data['location'],
                'status' => 'published',
            ]);
        }
    }

    protected function createMessages(): void
    {
        $teacherUserId1 = User::where('email', 'usman.bello@skulbase.edu.ng')->first();
        $teacherUserId2 = User::where('email', 'fatima.abdullahi@skulbase.edu.ng')->first();
        $parentUser1 = User::where('email', 'chinedu.okoro@email.com')->first();

        if ($teacherUserId1 && $this->schoolAdminUserId) {
            $msg = Message::create([
                'school_id' => $this->schoolId,
                'sender_id' => $this->schoolAdminUserId,
                'recipient_id' => $teacherUserId1->id,
                'recipient_role' => null,
                'subject' => 'Curriculum Coverage Progress',
                'message' => 'Dear Mr. Usman, kindly submit your curriculum coverage report for the first term before the end of this week. We need it for the academic committee meeting. Thank you.',
                'attachment' => null,
                'status' => 'unread',
            ]);
            MessageRecipient::create([
                'message_id' => $msg->id,
                'user_id' => $teacherUserId1->id,
                'status' => 'unread',
            ]);
        }

        if ($teacherUserId2 && $this->schoolAdminUserId) {
            $msg = Message::create([
                'school_id' => $this->schoolId,
                'sender_id' => $this->schoolAdminUserId,
                'recipient_id' => $teacherUserId2->id,
                'recipient_role' => null,
                'subject' => 'English Department — Result Submission',
                'message' => 'Dear Mrs. Fatima, the English department results are due by Friday. Please ensure all test scores and assignment marks are uploaded to the system. Let me know if you need any assistance.',
                'attachment' => null,
                'status' => 'unread',
            ]);
            MessageRecipient::create([
                'message_id' => $msg->id,
                'user_id' => $teacherUserId2->id,
                'status' => 'unread',
            ]);
        }

        if ($parentUser1 && $this->schoolAdminUserId) {
            $msg = Message::create([
                'school_id' => $this->schoolId,
                'sender_id' => $this->schoolAdminUserId,
                'recipient_id' => $parentUser1->id,
                'recipient_role' => null,
                'subject' => 'Outstanding Fee Payment Reminder',
                'message' => 'Dear Dr. Okoro, this is a gentle reminder that the outstanding school fee balance for Chinedu and Obianuju has not been fully settled. Kindly visit the bursary office or make a transfer at your earliest convenience. Thank you.',
                'attachment' => null,
                'status' => 'unread',
            ]);
            MessageRecipient::create([
                'message_id' => $msg->id,
                'user_id' => $parentUser1->id,
                'status' => 'unread',
            ]);
        }

        if ($teacherUserId1 && $parentUser1) {
            $msg = Message::create([
                'school_id' => $this->schoolId,
                'sender_id' => $parentUser1->id,
                'recipient_id' => $teacherUserId1->id,
                'recipient_role' => null,
                'subject' => 'Query About Chinedu\'s Mathematics Performance',
                'message' => 'Good day Mr. Usman, I noticed from the first term results that Chinedu scored below expectation in Mathematics. Could you please advise on how we can help him improve at home? Thank you for your dedication to our children\'s education.',
                'attachment' => null,
                'status' => 'unread',
            ]);
            MessageRecipient::create([
                'message_id' => $msg->id,
                'user_id' => $teacherUserId1->id,
                'status' => 'unread',
            ]);
        }
    }

    protected function createAdmissions(): void
    {
        $admissionData = [
            [
                'full_name' => 'Ibrahim Suleiman',
                'gender' => 'male',
                'dob' => '2012-06-15',
                'parent_name' => 'Alhaji Suleiman Abubakar',
                'parent_phone' => '+234 818 901 2345',
                'parent_email' => 'suleiman@email.com',
                'class' => 'J1A',
                'previous_school' => 'Central Primary School, Sokoto',
            ],
            [
                'full_name' => 'Grace Okonkwo',
                'gender' => 'female',
                'dob' => '2011-09-20',
                'parent_name' => 'Mr. Emmanuel Okonkwo',
                'parent_phone' => '+234 819 012 3456',
                'parent_email' => 'emmanuel.okonkwo@email.com',
                'class' => 'J2A',
                'previous_school' => 'Federal Government College, Sokoto',
            ],
            [
                'full_name' => 'Tunde Afolabi',
                'gender' => 'male',
                'dob' => '2009-03-08',
                'parent_name' => 'Mrs. Abiodun Afolabi',
                'parent_phone' => '+234 820 123 4567',
                'parent_email' => 'abiodun.afolabi@email.com',
                'class' => 'S1A',
                'previous_school' => 'Nigerian Military School, Zaria',
            ],
        ];

        foreach ($admissionData as $index => $data) {
            Admission::updateOrCreate(
                ['school_id' => $this->schoolId, 'full_name' => $data['full_name']],
                [
                    'school_id' => $this->schoolId,
                    'application_number' => Admission::generateApplicationNumber($this->schoolId),
                    'full_name' => $data['full_name'],
                    'gender' => $data['gender'],
                    'date_of_birth' => $data['dob'],
                    'parent_name' => $data['parent_name'],
                    'parent_phone' => $data['parent_phone'],
                    'parent_email' => $data['parent_email'],
                    'address' => 'Sokoto, Nigeria',
                    'class_id' => $this->classIds[$data['class']],
                    'previous_school' => $data['previous_school'],
                    'passport' => null,
                    'status' => 'pending',
                ]
            );
        }
    }

    protected function createSchoolSettings(): void
    {
        SchoolSetting::updateOrCreate(
            ['school_id' => $this->schoolId],
            [
                'school_id' => $this->schoolId,
                'current_session' => '2025/2026',
                'current_term' => 'Second Term',
                'school_open_time' => '07:30',
                'school_close_time' => '15:00',
                'timezone' => 'Africa/Lagos',
                'date_format' => 'd/m/Y',
                'time_format' => '12h',
                'currency' => 'NGN',
                'currency_symbol' => "\u{20A6}",
                'maintenance_mode' => false,
                'maintenance_message' => 'The system is currently under maintenance. Please check back later.',
                'email_notifications' => true,
                'assignment_notifications' => true,
                'attendance_notifications' => true,
                'result_notifications' => true,
                'fee_notifications' => true,
                'announcement_notifications' => true,
                'event_notifications' => true,
                'admission_notifications' => true,
                'default_sender_name' => 'Skulbase International School',
                'default_reply_email' => 'info@skulbaseacademy.edu.ng',
            ]
        );
    }

    protected function createSubscription(): void
    {
        $premiumPlan = Plan::where('slug', 'premium')->first();

        if (! $premiumPlan) {
            $premiumPlan = Plan::create([
                'name' => 'Premium',
                'slug' => 'premium',
                'description' => 'For large institutions requiring unlimited students and priority support.',
                'monthly_price' => 20000,
                'yearly_price' => 200000,
                'student_limit' => null,
                'is_unlimited' => true,
                'trial_days' => 30,
                'is_active' => true,
                'sort_order' => 3,
            ]);
        }

        $existingSub = Subscription::where('school_id', $this->schoolId)
            ->where('plan_id', $premiumPlan->id)
            ->first();

        if (! $existingSub) {
            $now = Carbon::now();
            Subscription::create([
                'school_id' => $this->schoolId,
                'plan_id' => $premiumPlan->id,
                'billing_cycle' => 'yearly',
                'status' => 'active',
                'starts_at' => $now->copy()->subMonths(3),
                'expires_at' => $now->copy()->addMonths(9),
                'trial_starts_at' => null,
                'trial_ends_at' => null,
                'grace_ends_at' => null,
                'cancelled_at' => null,
                'is_trial' => false,
                'amount_paid' => 200000,
                'payment_reference' => 'SUB/'.strtoupper(Str::random(10)),
                'notes' => 'Premium annual subscription for demo school',
            ]);
        }
    }

    protected function printSummary(): void
    {
        $schoolId = $this->schoolId;

        $counts = [
            'Schools' => School::count(),
            'Users' => User::count(),
            'Sections' => Section::where('school_id', $schoolId)->count(),
            'Classes' => SchoolClass::where('school_id', $schoolId)->count(),
            'Subjects' => Subject::where('school_id', $schoolId)->count(),
            'Teachers' => Teacher::where('school_id', $schoolId)->count(),
            'Students' => Student::where('school_id', $schoolId)->count(),
            'Parents' => ParentModel::where('school_id', $schoolId)->count(),
            'Periods' => Period::where('school_id', $schoolId)->count(),
            'Timetable Entries' => Timetable::where('school_id', $schoolId)->count(),
            'Assessment Types' => AssessmentType::where('school_id', $schoolId)->count(),
            'Exams' => Exam::where('school_id', $schoolId)->count(),
            'Grading Rules' => GradingSystem::where('school_id', $schoolId)->count(),
            'Fee Structures' => FeeStructure::where('school_id', $schoolId)->count(),
            'Fee Payments' => FeePayment::where('school_id', $schoolId)->count(),
            'Attendance Records' => Attendance::where('school_id', $schoolId)->count(),
            'Student Results' => StudentResult::where('school_id', $schoolId)->count(),
            'Report Cards' => StudentReportCard::where('school_id', $schoolId)->count(),
            'Assignments' => Assignment::where('school_id', $schoolId)->count(),
            'Announcements' => Announcement::where('school_id', $schoolId)->count(),
            'Events' => Event::where('school_id', $schoolId)->count(),
            'Messages' => Message::where('school_id', $schoolId)->count(),
            'Admissions' => Admission::where('school_id', $schoolId)->count(),
            'School Settings' => SchoolSetting::where('school_id', $schoolId)->count(),
            'Subscriptions' => Subscription::where('school_id', $schoolId)->count(),
        ];

        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════════════');
        $this->command->info('  SKULBASE DEMO ENVIRONMENT — SEED COMPLETE');
        $this->command->info('═══════════════════════════════════════════════════════════════');
        $this->command->info('');
        $this->command->info('  Records Created:');

        foreach ($counts as $label => $count) {
            $this->command->info('    '.str_pad($label, 22, ' ').': '.number_format($count));
        }

        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════════════');
        $this->command->info('  LOGIN CREDENTIALS');
        $this->command->info('═══════════════════════════════════════════════════════════════');
        $this->command->info('');
        $this->command->info('  Super Admin:');
        $this->command->info('    Email    : admin@skulbase.com');
        $this->command->info('    Password : password');
        $this->command->info('');
        $this->command->info('  School Admin:');
        $this->command->info('    Email    : admin@skulbase-academy.edu.ng');
        $this->command->info('    Password : password');
        $this->command->info('');
        $this->command->info('  Teacher (Mathematics):');
        $this->command->info('    Email    : usman.bello@skulbase.edu.ng');
        $this->command->info('    Password : password');
        $this->command->info('');
        $this->command->info('  Teacher (English):');
        $this->command->info('    Email    : fatima.abdullahi@skulbase.edu.ng');
        $this->command->info('    Password : password');
        $this->command->info('');
        $this->command->info('  Parent 1:');
        $this->command->info('    Email    : chinedu.okoro@email.com');
        $this->command->info('    Password : password');
        $this->command->info('');
        $this->command->info('  Parent 2:');
        $this->command->info('    Email    : blessing.eze@email.com');
        $this->command->info('    Password : password');
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════════════');
    }
}
