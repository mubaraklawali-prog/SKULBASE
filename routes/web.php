<?php

use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssessmentTypeController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\GradingSystemController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ParentTimetableController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\ReportCardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResultApprovalController;
use App\Http\Controllers\ResultComputationController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\ScoreEntryController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentTimetableController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherTimetableController;
use App\Http\Controllers\TimetableController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])
        ->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Public Admission Form
Route::get('/admissions/apply', [AdmissionController::class, 'form'])
    ->name('admissions.form');
Route::post('/admissions/apply', [AdmissionController::class, 'submit'])
    ->name('admissions.submit');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])
        ->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::middleware('role:super_admin')->group(function () {
        Route::get('/schools', [SchoolController::class, 'index'])
            ->name('schools.index');

        Route::get('/schools/create', [SchoolController::class, 'create'])
            ->name('schools.create');

        Route::post('/schools', [SchoolController::class, 'store'])
            ->name('schools.store');

        Route::get('/schools/{school}/edit', [SchoolController::class, 'edit'])
            ->name('schools.edit');

        Route::put('/schools/{school}', [SchoolController::class, 'update'])
            ->name('schools.update');

        Route::delete('/schools/{school}', [SchoolController::class, 'destroy'])
            ->name('schools.destroy');

        Route::patch('/schools/{school}/toggle-status', [SchoolController::class, 'toggleStatus'])
            ->name('schools.toggle-status');

        Route::get('/students', [StudentController::class, 'index'])
            ->name('students.index');

        Route::get('/students/create', [StudentController::class, 'create'])
            ->name('students.create');

        Route::post('/students', [StudentController::class, 'store'])
            ->name('students.store');

        Route::get('/students/{student}/edit', [StudentController::class, 'edit'])
            ->name('students.edit');

        Route::put('/students/{student}', [StudentController::class, 'update'])
            ->name('students.update');

        Route::delete('/students/{student}', [StudentController::class, 'destroy'])
            ->name('students.destroy');

        Route::get('/classes', [SchoolClassController::class, 'index'])
            ->name('classes.index');

        Route::get('/classes/create', [SchoolClassController::class, 'create'])
            ->name('classes.create');

        Route::post('/classes', [SchoolClassController::class, 'store'])
            ->name('classes.store');

        Route::get('/classes/{schoolClass}', [SchoolClassController::class, 'show'])
            ->name('classes.show');

        Route::get('/classes/{schoolClass}/edit', [SchoolClassController::class, 'edit'])
            ->name('classes.edit');

        Route::put('/classes/{schoolClass}', [SchoolClassController::class, 'update'])
            ->name('classes.update');

        Route::delete('/classes/{schoolClass}', [SchoolClassController::class, 'destroy'])
            ->name('classes.destroy');

        Route::patch('/classes/{schoolClass}/toggle-status', [SchoolClassController::class, 'toggleStatus'])
            ->name('classes.toggle-status');

        Route::get('/subjects', [SubjectController::class, 'index'])
            ->name('subjects.index');

        Route::get('/subjects/create', [SubjectController::class, 'create'])
            ->name('subjects.create');

        Route::post('/subjects', [SubjectController::class, 'store'])
            ->name('subjects.store');

        Route::get('/subjects/{subject}', [SubjectController::class, 'show'])
            ->name('subjects.show');

        Route::get('/subjects/{subject}/edit', [SubjectController::class, 'edit'])
            ->name('subjects.edit');

        Route::put('/subjects/{subject}', [SubjectController::class, 'update'])
            ->name('subjects.update');

        Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])
            ->name('subjects.destroy');

        Route::patch('/subjects/{subject}/toggle-status', [SubjectController::class, 'toggleStatus'])
            ->name('subjects.toggle-status');

        Route::get('/teachers', [TeacherController::class, 'index'])
            ->name('teachers.index');

        Route::get('/teachers/create', [TeacherController::class, 'create'])
            ->name('teachers.create');

        Route::post('/teachers', [TeacherController::class, 'store'])
            ->name('teachers.store');

        Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])
            ->name('teachers.show');

        Route::get('/teachers/{teacher}/edit', [TeacherController::class, 'edit'])
            ->name('teachers.edit');

        Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])
            ->name('teachers.update');

        Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])
            ->name('teachers.destroy');

        Route::patch('/teachers/{teacher}/toggle-status', [TeacherController::class, 'toggleStatus'])
            ->name('teachers.toggle-status');

        // Attendance
        Route::get('/attendance/dashboard', [AttendanceController::class, 'dashboard'])
            ->name('attendance.dashboard');

        Route::get('/attendance', [AttendanceController::class, 'index'])
            ->name('attendance.index');

        Route::get('/attendance/take', [AttendanceController::class, 'create'])
            ->name('attendance.create');

        Route::post('/attendance', [AttendanceController::class, 'store'])
            ->name('attendance.store');

        Route::get('/attendance/student/{student}', [AttendanceController::class, 'studentAttendance'])
            ->name('attendance.student');

        Route::get('/attendance/class-report', [AttendanceController::class, 'classReport'])
            ->name('attendance.class-report');

        Route::get('/attendance/class-report/{schoolClass}', [AttendanceController::class, 'classReport'])
            ->name('attendance.class-report.show');

        Route::get('/attendance/monthly-report', [AttendanceController::class, 'monthlyReport'])
            ->name('attendance.monthly-report');

        Route::get('/attendance/monthly-report/{schoolClass}', [AttendanceController::class, 'monthlyReport'])
            ->name('attendance.monthly-report.show');

        Route::get('/attendance/{attendance}', [AttendanceController::class, 'show'])
            ->name('attendance.show');

        // Fees
        Route::get('/fees', [FeeController::class, 'dashboard'])
            ->name('fees.dashboard');

        Route::get('/fees/structures', [FeeController::class, 'feeStructureIndex'])
            ->name('fees.structures.index');

        Route::get('/fees/structures/create', [FeeController::class, 'feeStructureCreate'])
            ->name('fees.structures.create');

        Route::post('/fees/structures', [FeeController::class, 'feeStructureStore'])
            ->name('fees.structures.store');

        Route::get('/fees/structures/{feeStructure}/edit', [FeeController::class, 'feeStructureEdit'])
            ->name('fees.structures.edit');

        Route::put('/fees/structures/{feeStructure}', [FeeController::class, 'feeStructureUpdate'])
            ->name('fees.structures.update');

        Route::delete('/fees/structures/{feeStructure}', [FeeController::class, 'feeStructureDestroy'])
            ->name('fees.structures.destroy');

        Route::get('/fees/payments', [FeeController::class, 'paymentIndex'])
            ->name('fees.payments.index');

        Route::get('/fees/payments/create', [FeeController::class, 'paymentCreate'])
            ->name('fees.payments.create');

        Route::post('/fees/payments', [FeeController::class, 'paymentStore'])
            ->name('fees.payments.store');

        Route::get('/fees/payments/{payment}', [FeeController::class, 'paymentShow'])
            ->name('fees.payments.show');

        Route::get('/fees/payments/{payment}/receipt', [FeeController::class, 'receipt'])
            ->name('fees.payments.receipt');

        Route::get('/fees/student/{student}', [FeeController::class, 'studentFinance'])
            ->name('fees.student');

        Route::get('/fees/reports/outstanding', [FeeController::class, 'outstandingReport'])
            ->name('fees.reports.outstanding');

        Route::get('/fees/reports/paid', [FeeController::class, 'paidReport'])
            ->name('fees.reports.paid');

        Route::get('/fees/reports/class-summary', [FeeController::class, 'classSummaryReport'])
            ->name('fees.reports.class-summary');

        Route::get('/fees/reports/daily-collections', [FeeController::class, 'dailyCollectionsReport'])
            ->name('fees.reports.daily-collections');

        Route::get('/fees/reports/monthly-collections', [FeeController::class, 'monthlyCollectionsReport'])
            ->name('fees.reports.monthly-collections');

        // Results
        Route::get('/results', [ResultsController::class, 'dashboard'])
            ->name('results.dashboard');

        Route::get('/results/assessment-types', [AssessmentTypeController::class, 'index'])
            ->name('results.assessment-types.index');

        Route::get('/results/assessment-types/create', [AssessmentTypeController::class, 'create'])
            ->name('results.assessment-types.create');

        Route::post('/results/assessment-types', [AssessmentTypeController::class, 'store'])
            ->name('results.assessment-types.store');

        Route::get('/results/assessment-types/{assessmentType}/edit', [AssessmentTypeController::class, 'edit'])
            ->name('results.assessment-types.edit');

        Route::put('/results/assessment-types/{assessmentType}', [AssessmentTypeController::class, 'update'])
            ->name('results.assessment-types.update');

        Route::delete('/results/assessment-types/{assessmentType}', [AssessmentTypeController::class, 'destroy'])
            ->name('results.assessment-types.destroy');

        Route::patch('/results/assessment-types/{assessmentType}/toggle-status', [AssessmentTypeController::class, 'toggleStatus'])
            ->name('results.assessment-types.toggle-status');

        Route::get('/results/exams', [ExamController::class, 'index'])
            ->name('results.exams.index');

        Route::get('/results/exams/create', [ExamController::class, 'create'])
            ->name('results.exams.create');

        Route::post('/results/exams', [ExamController::class, 'store'])
            ->name('results.exams.store');

        Route::get('/results/exams/{exam}/edit', [ExamController::class, 'edit'])
            ->name('results.exams.edit');

        Route::put('/results/exams/{exam}', [ExamController::class, 'update'])
            ->name('results.exams.update');

        Route::delete('/results/exams/{exam}', [ExamController::class, 'destroy'])
            ->name('results.exams.destroy');

        Route::patch('/results/exams/{exam}/toggle-status', [ExamController::class, 'toggleStatus'])
            ->name('results.exams.toggle-status');

        Route::get('/results/grading-systems', [GradingSystemController::class, 'index'])
            ->name('results.grading-systems.index');

        Route::get('/results/grading-systems/create', [GradingSystemController::class, 'create'])
            ->name('results.grading-systems.create');

        Route::post('/results/grading-systems', [GradingSystemController::class, 'store'])
            ->name('results.grading-systems.store');

        Route::get('/results/grading-systems/{gradingSystem}/edit', [GradingSystemController::class, 'edit'])
            ->name('results.grading-systems.edit');

        Route::put('/results/grading-systems/{gradingSystem}', [GradingSystemController::class, 'update'])
            ->name('results.grading-systems.update');

        Route::delete('/results/grading-systems/{gradingSystem}', [GradingSystemController::class, 'destroy'])
            ->name('results.grading-systems.destroy');

        // Score Entry
        Route::get('/results/scores', [ScoreEntryController::class, 'dashboard'])
            ->name('results.scores.dashboard');

        Route::get('/results/scores/entry', [ScoreEntryController::class, 'create'])
            ->name('results.scores.create');

        Route::post('/results/scores/entry', [ScoreEntryController::class, 'store'])
            ->name('results.scores.store');

        Route::get('/results/scores/edit', [ScoreEntryController::class, 'edit'])
            ->name('results.scores.edit');

        Route::put('/results/scores/edit', [ScoreEntryController::class, 'update'])
            ->name('results.scores.update');

        Route::get('/results/scores/history', [ScoreEntryController::class, 'history'])
            ->name('results.scores.history');

        Route::get('/results/scores/{score}', [ScoreEntryController::class, 'show'])
            ->name('results.scores.show');

        Route::delete('/results/scores/{score}', [ScoreEntryController::class, 'destroy'])
            ->name('results.scores.destroy');

        Route::get('/results/scores/student/{student}', [ScoreEntryController::class, 'studentReport'])
            ->name('results.scores.student-report');

        Route::get('/results/reports/subject', [ScoreEntryController::class, 'subjectReport'])
            ->name('results.reports.subject');

        Route::get('/results/reports/class', [ScoreEntryController::class, 'classReport'])
            ->name('results.reports.class');

        Route::get('/results/reports/exam', [ScoreEntryController::class, 'examReport'])
            ->name('results.reports.exam');

        // Result Computation
        Route::get('/results/computations', [ResultComputationController::class, 'dashboard'])
            ->name('results.computations.dashboard');

        Route::get('/results/computations/compute', [ResultComputationController::class, 'compute'])
            ->name('results.computations.compute');

        Route::post('/results/computations/compute', [ResultComputationController::class, 'runComputation'])
            ->name('results.computations.run');

        Route::get('/results/computations/{reportCard}', [ResultComputationController::class, 'show'])
            ->name('results.computations.show');

        Route::put('/results/computations/{reportCard}/comment', [ResultComputationController::class, 'updateComment'])
            ->name('results.computations.update-comment');

        Route::post('/results/computations/{exam}/approve/{schoolClass}', [ResultComputationController::class, 'approve'])
            ->name('results.computations.approve');

        Route::post('/results/computations/{exam}/publish/{schoolClass}', [ResultComputationController::class, 'publish'])
            ->name('results.computations.publish');

        Route::get('/results/rankings/class', [ResultComputationController::class, 'classRanking'])
            ->name('results.rankings.class');

        Route::get('/results/rankings/subject', [ResultComputationController::class, 'subjectRanking'])
            ->name('results.rankings.subject');

        Route::get('/results/rankings/top-performers', [ResultComputationController::class, 'topPerformers'])
            ->name('results.rankings.top-performers');

        Route::get('/results/analytics', [ResultComputationController::class, 'analytics'])
            ->name('results.analytics');

        // Periods
        Route::get('/periods', [PeriodController::class, 'index'])
            ->name('periods.index');

        Route::get('/periods/create', [PeriodController::class, 'create'])
            ->name('periods.create');

        Route::post('/periods', [PeriodController::class, 'store'])
            ->name('periods.store');

        Route::get('/periods/{period}', [PeriodController::class, 'show'])
            ->name('periods.show');

        Route::get('/periods/{period}/edit', [PeriodController::class, 'edit'])
            ->name('periods.edit');

        Route::put('/periods/{period}', [PeriodController::class, 'update'])
            ->name('periods.update');

        Route::delete('/periods/{period}', [PeriodController::class, 'destroy'])
            ->name('periods.destroy');

        Route::patch('/periods/{period}/toggle-status', [PeriodController::class, 'toggleStatus'])
            ->name('periods.toggle-status');

        // Timetables
        Route::get('/timetables', [TimetableController::class, 'index'])
            ->name('timetables.index');

        Route::get('/timetables/create', [TimetableController::class, 'create'])
            ->name('timetables.create');

        Route::post('/timetables', [TimetableController::class, 'store'])
            ->name('timetables.store');

        Route::get('/timetables/{timetable}/edit', [TimetableController::class, 'edit'])
            ->name('timetables.edit');

        Route::put('/timetables/{timetable}', [TimetableController::class, 'update'])
            ->name('timetables.update');

        Route::delete('/timetables/{timetable}', [TimetableController::class, 'destroy'])
            ->name('timetables.destroy');

        Route::get('/timetables/grid', [TimetableController::class, 'grid'])
            ->name('timetables.grid');

        Route::get('/timetables/print', [TimetableController::class, 'print'])
            ->name('timetables.print');

        // Timetable API (AJAX dropdowns)
        Route::get('/timetables/api/sections', [TimetableController::class, 'getSections'])
            ->name('timetables.api.sections');

        Route::get('/timetables/api/subjects', [TimetableController::class, 'getSubjects'])
            ->name('timetables.api.subjects');

        Route::get('/timetables/api/teachers', [TimetableController::class, 'getTeachers'])
            ->name('timetables.api.teachers');

        Route::get('/timetables/api/periods', [TimetableController::class, 'getPeriods'])
            ->name('timetables.api.periods');

        Route::get('/timetables/api/classes', [TimetableController::class, 'getClassesBySchool'])
            ->name('timetables.api.classes');

        // Report Cards
        Route::get('/results/report-cards', [ReportCardController::class, 'bulkSelector'])
            ->name('results.report-cards.bulk');

        Route::post('/results/report-cards/bulk/print', [ReportCardController::class, 'bulkPrint'])
            ->name('results.report-cards.bulk-print');

        Route::post('/results/report-cards/bulk/pdf', [ReportCardController::class, 'bulkDownload'])
            ->name('results.report-cards.bulk-pdf');

        Route::get('/results/report-cards/{reportCard}/print', [ReportCardController::class, 'showForPrint'])
            ->name('results.report-cards.print');

        Route::get('/results/report-cards/{reportCard}/pdf', [ReportCardController::class, 'downloadPdf'])
            ->name('results.report-cards.pdf');

        // Result Approval Workflow
        Route::get('/results/approvals', [ResultApprovalController::class, 'dashboard'])
            ->name('results.approvals.dashboard');

        Route::post('/results/approvals/{reportCard}/submit', [ResultApprovalController::class, 'submit'])
            ->name('results.approvals.submit');

        Route::post('/results/approvals/{reportCard}/approve', [ResultApprovalController::class, 'approve'])
            ->name('results.approvals.approve');

        Route::post('/results/approvals/{reportCard}/publish', [ResultApprovalController::class, 'publish'])
            ->name('results.approvals.publish');

        Route::post('/results/approvals/{reportCard}/unpublish', [ResultApprovalController::class, 'unpublish'])
            ->name('results.approvals.unpublish');

        Route::post('/results/approvals/{reportCard}/reject', [ResultApprovalController::class, 'reject'])
            ->name('results.approvals.reject');

        Route::post('/results/approvals/{reportCard}/revert', [ResultApprovalController::class, 'revertToDraft'])
            ->name('results.approvals.revert');

        Route::post('/results/approvals/bulk', [ResultApprovalController::class, 'bulkAction'])
            ->name('results.approvals.bulk-action');

        Route::get('/results/approvals/reports', [ResultApprovalController::class, 'reports'])
            ->name('results.approvals.reports');
    });

    // Assignments (Super Admin, School Admin, and Teachers)
    Route::middleware('role:super_admin,school_admin,teacher')->group(function () {
        Route::get('/assignments', [AssignmentController::class, 'index'])
            ->name('assignments.index');

        Route::get('/assignments/create', [AssignmentController::class, 'create'])
            ->name('assignments.create');

        Route::post('/assignments', [AssignmentController::class, 'store'])
            ->name('assignments.store');

        Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])
            ->name('assignments.show');

        Route::get('/assignments/{assignment}/edit', [AssignmentController::class, 'edit'])
            ->name('assignments.edit');

        Route::put('/assignments/{assignment}', [AssignmentController::class, 'update'])
            ->name('assignments.update');

        Route::delete('/assignments/{assignment}', [AssignmentController::class, 'destroy'])
            ->name('assignments.destroy');
    });

    // Announcements
    Route::get('/announcements', [AnnouncementController::class, 'index'])
        ->name('announcements.index');

    Route::middleware('role:super_admin,school_admin')->group(function () {
        Route::get('/announcements/create', [AnnouncementController::class, 'create'])
            ->name('announcements.create');

        Route::post('/announcements', [AnnouncementController::class, 'store'])
            ->name('announcements.store');

        Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])
            ->name('announcements.edit');

        Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])
            ->name('announcements.update');

        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])
            ->name('announcements.destroy');
    });

    Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])
        ->name('announcements.show');

    // Messages
    Route::get('/messages', [MessageController::class, 'inbox'])
        ->name('messages.inbox');

    Route::get('/messages/sent', [MessageController::class, 'sent'])
        ->name('messages.sent');

    Route::get('/messages/compose', [MessageController::class, 'create'])
        ->name('messages.create');

    Route::post('/messages', [MessageController::class, 'store'])
        ->name('messages.store');

    Route::get('/messages/{message}', [MessageController::class, 'show'])
        ->name('messages.show');

    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])
        ->name('messages.destroy');

    // Events
    Route::get('/events', [EventController::class, 'index'])
        ->name('events.index');

    Route::middleware('role:super_admin,school_admin')->group(function () {
        Route::get('/events/create', [EventController::class, 'create'])
            ->name('events.create');

        Route::post('/events', [EventController::class, 'store'])
            ->name('events.store');

        Route::get('/events/{event}/edit', [EventController::class, 'edit'])
            ->name('events.edit');

        Route::put('/events/{event}', [EventController::class, 'update'])
            ->name('events.update');

        Route::delete('/events/{event}', [EventController::class, 'destroy'])
            ->name('events.destroy');
    });

    Route::get('/events/{event}', [EventController::class, 'show'])
        ->name('events.show');

    // Admissions (Super Admin, School Admin)
    Route::middleware('role:super_admin,school_admin')->group(function () {
        Route::get('/admissions', [AdmissionController::class, 'index'])
            ->name('admissions.index');

        Route::get('/admissions/{admission}', [AdmissionController::class, 'show'])
            ->name('admissions.show');

        Route::get('/admissions/{admission}/edit', [AdmissionController::class, 'edit'])
            ->name('admissions.edit');

        Route::put('/admissions/{admission}', [AdmissionController::class, 'update'])
            ->name('admissions.update');

        Route::delete('/admissions/{admission}', [AdmissionController::class, 'destroy'])
            ->name('admissions.destroy');

        Route::post('/admissions/{admission}/approve', [AdmissionController::class, 'approve'])
            ->name('admissions.approve');

        Route::post('/admissions/{admission}/reject', [AdmissionController::class, 'reject'])
            ->name('admissions.reject');
    });

    // ── Reports & Analytics ───────────────────────────────
    Route::middleware('role:super_admin,school_admin')->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'dashboard'])
            ->name('dashboard');

        // Student Reports
        Route::get('/students', [ReportController::class, 'studentList'])
            ->name('students.list');
        Route::get('/students/by-class', [ReportController::class, 'studentsByClass'])
            ->name('students.by-class');
        Route::get('/students/status', [ReportController::class, 'studentStatus'])
            ->name('students.status');

        // Teacher Reports
        Route::get('/teachers', [ReportController::class, 'teacherList'])
            ->name('teachers.list');
        Route::get('/teachers/by-subject', [ReportController::class, 'teachersBySubject'])
            ->name('teachers.by-subject');
        Route::get('/teachers/status', [ReportController::class, 'teacherStatus'])
            ->name('teachers.status');

        // Attendance Reports
        Route::get('/attendance/summary', [ReportController::class, 'attendanceSummary'])
            ->name('attendance.summary');
        Route::get('/attendance/by-date', [ReportController::class, 'attendanceByDate'])
            ->name('attendance.by-date');
        Route::get('/attendance/by-class', [ReportController::class, 'attendanceByClass'])
            ->name('attendance.by-class');

        // Fee Reports
        Route::get('/fees/payments', [ReportController::class, 'paymentHistory'])
            ->name('fees.payments');
        Route::get('/fees/outstanding', [ReportController::class, 'outstandingFees'])
            ->name('fees.outstanding');
        Route::get('/fees/collected', [ReportController::class, 'feesCollectedByDate'])
            ->name('fees.collected');

        // Academic Reports
        Route::get('/academic/results', [ReportController::class, 'resultsSummary'])
            ->name('academic.results');
        Route::get('/academic/class-performance', [ReportController::class, 'classPerformance'])
            ->name('academic.class-performance');
        Route::get('/academic/subject-performance', [ReportController::class, 'subjectPerformance'])
            ->name('academic.subject-performance');

        // PDF Exports
        Route::get('/export/students/pdf', [ReportController::class, 'exportStudentListPdf'])
            ->name('export.students.pdf');
        Route::get('/export/teachers/pdf', [ReportController::class, 'exportTeacherListPdf'])
            ->name('export.teachers.pdf');
        Route::get('/export/attendance/pdf', [ReportController::class, 'exportAttendanceSummaryPdf'])
            ->name('export.attendance.pdf');
        Route::get('/export/payments/pdf', [ReportController::class, 'exportPaymentHistoryPdf'])
            ->name('export.payments.pdf');
        Route::get('/export/outstanding/pdf', [ReportController::class, 'exportOutstandingPdf'])
            ->name('export.outstanding.pdf');
        Route::get('/export/results/pdf', [ReportController::class, 'exportResultsSummaryPdf'])
            ->name('export.results.pdf');
        Route::get('/export/class-performance/pdf', [ReportController::class, 'exportClassPerformancePdf'])
            ->name('export.class-performance.pdf');
        Route::get('/export/subject-performance/pdf', [ReportController::class, 'exportSubjectPerformancePdf'])
            ->name('export.subject-performance.pdf');

        // CSV Exports
        Route::get('/export/students/csv', [ReportController::class, 'exportStudentListCsv'])
            ->name('export.students.csv');
        Route::get('/export/teachers/csv', [ReportController::class, 'exportTeacherListCsv'])
            ->name('export.teachers.csv');
        Route::get('/export/attendance/csv', [ReportController::class, 'exportAttendanceSummaryCsv'])
            ->name('export.attendance.csv');
        Route::get('/export/payments/csv', [ReportController::class, 'exportPaymentHistoryCsv'])
            ->name('export.payments.csv');
        Route::get('/export/outstanding/csv', [ReportController::class, 'exportOutstandingCsv'])
            ->name('export.outstanding.csv');
        Route::get('/export/results/csv', [ReportController::class, 'exportResultsSummaryCsv'])
            ->name('export.results.csv');
        Route::get('/export/class-performance/csv', [ReportController::class, 'exportClassPerformancePdfDirect'])
            ->name('export.class-performance.csv');
        Route::get('/export/subject-performance/csv', [ReportController::class, 'exportSubjectPerformanceCsv'])
            ->name('export.subject-performance.csv');
    });

    // Teacher Routes
    Route::middleware('role:teacher')->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/timetable', [TeacherTimetableController::class, 'index'])
            ->name('timetable.index');

        Route::get('/timetable/print', [TeacherTimetableController::class, 'print'])
            ->name('timetable.print');
    });

    // Student Routes
    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {
        Route::get('/timetable', [StudentTimetableController::class, 'index'])
            ->name('timetable.index');

        Route::get('/timetable/print', [StudentTimetableController::class, 'print'])
            ->name('timetable.print');
    });

    // Parent Routes
    Route::middleware('role:parent')->prefix('parent')->name('parent.')->group(function () {
        Route::get('/timetable', [ParentTimetableController::class, 'index'])
            ->name('timetable.index');

        Route::get('/timetable/print', [ParentTimetableController::class, 'print'])
            ->name('timetable.print');
    });
});
