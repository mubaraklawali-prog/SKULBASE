<?php

use App\Http\Controllers\AssessmentTypeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\GradingSystemController;
use App\Http\Controllers\ReportCardController;
use App\Http\Controllers\ResultApprovalController;
use App\Http\Controllers\ResultComputationController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\ScoreEntryController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
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

        Route::get('/attendance/{attendance}', [AttendanceController::class, 'show'])
            ->name('attendance.show');

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
});
