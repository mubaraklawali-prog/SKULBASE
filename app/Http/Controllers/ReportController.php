<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\School;
use App\Models\SchoolClass;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReportController extends Controller
{
    private function resolveSchoolId(): ?int
    {
        $user = Auth::user();

        if ($user->role === 'super_admin') {
            return request()->filled('school_id') ? (int) request('school_id') : null;
        }

        return $user->school_id;
    }

    private function createReportService(): ReportService
    {
        return new ReportService($this->resolveSchoolId());
    }

    private function resolveSchool(): ?School
    {
        $schoolId = $this->resolveSchoolId();

        return $schoolId ? School::find($schoolId) : null;
    }

    private function scopedQuery(string $model): Builder
    {
        $schoolId = $this->resolveSchoolId();

        return $schoolId ? $model::where('school_id', $schoolId) : $model::query();
    }

    // ── Dashboard ─────────────────────────────────────────

    public function dashboard(): View
    {
        $summary = $this->createReportService()->dashboardSummary();
        $school = $this->resolveSchool();
        $schools = Auth::user()->role === 'super_admin'
            ? School::where('is_active', true)->orderBy('name')->get()
            : collect();

        return view('reports.dashboard', compact('summary', 'school', 'schools'));
    }

    // ── Student Reports ───────────────────────────────────

    public function studentList(Request $request): View
    {
        $service = $this->createReportService();
        $students = $service->studentList(
            $request->search,
            $request->class_id ? (int) $request->class_id : null,
            $request->status ?? 'active'
        );
        $classes = $this->scopedQuery(SchoolClass::class)->orderBy('name')->get();

        return view('reports.students.list', compact('students', 'classes'));
    }

    public function studentsByClass(): View
    {
        $service = $this->createReportService();
        $byClass = $service->studentsByClass();
        $statusBreakdown = $service->studentStatusBreakdown();

        return view('reports.students.by-class', compact('byClass', 'statusBreakdown'));
    }

    public function studentStatus(): View
    {
        $breakdown = $this->createReportService()->studentStatusBreakdown();

        return view('reports.students.status', compact('breakdown'));
    }

    // ── Teacher Reports ───────────────────────────────────

    public function teacherList(Request $request): View
    {
        $teachers = $this->createReportService()->teacherList(
            $request->search,
            $request->status ?? 'active'
        );

        return view('reports.teachers.list', compact('teachers'));
    }

    public function teachersBySubject(): View
    {
        $service = $this->createReportService();
        $bySubject = $service->teachersBySubject();
        $statusBreakdown = $service->teacherStatusBreakdown();

        return view('reports.teachers.by-subject', compact('bySubject', 'statusBreakdown'));
    }

    public function teacherStatus(): View
    {
        $breakdown = $this->createReportService()->teacherStatusBreakdown();

        return view('reports.teachers.status', compact('breakdown'));
    }

    // ── Attendance Reports ─────────────────────────────────

    public function attendanceSummary(Request $request): View
    {
        $data = $this->createReportService()->attendanceSummary(
            $request->date_from,
            $request->date_to,
            $request->class_id ? (int) $request->class_id : null
        );
        $classes = $this->scopedQuery(SchoolClass::class)->orderBy('name')->get();

        return view('reports.attendance.summary', array_merge($data, compact('classes')));
    }

    public function attendanceByDate(Request $request): View
    {
        $dates = $this->createReportService()->attendanceByDate(
            $request->date_from,
            $request->date_to
        );

        return view('reports.attendance.by-date', compact('dates'));
    }

    public function attendanceByClass(Request $request): View
    {
        $byClass = $this->createReportService()->attendanceByClass(
            $request->date_from,
            $request->date_to
        );

        return view('reports.attendance.by-class', compact('byClass'));
    }

    // ── Fee Reports ───────────────────────────────────────

    public function paymentHistory(Request $request): View
    {
        $payments = $this->createReportService()->paymentHistory(
            $request->search,
            $request->class_id ? (int) $request->class_id : null,
            $request->date_from,
            $request->date_to,
            $request->method
        );
        $classes = $this->scopedQuery(SchoolClass::class)->orderBy('name')->get();

        return view('reports.fees.payment-history', compact('payments', 'classes'));
    }

    public function outstandingFees(Request $request): View
    {
        $data = $this->createReportService()->outstandingFees(
            $request->class_id ? (int) $request->class_id : null
        );
        $classes = $this->scopedQuery(SchoolClass::class)->orderBy('name')->get();

        return view('reports.fees.outstanding', array_merge($data, compact('classes')));
    }

    public function feesCollectedByDate(Request $request): View
    {
        $data = $this->createReportService()->feesCollectedByDate(
            $request->date_from,
            $request->date_to
        );

        return view('reports.fees.collected-by-date', $data);
    }

    // ── Academic Reports ──────────────────────────────────

    public function resultsSummary(Request $request): View
    {
        $exams = $this->scopedQuery(Exam::class)
            ->where('status', true)
            ->orderBy('name')
            ->get();
        $classes = $this->scopedQuery(SchoolClass::class)->orderBy('name')->get();

        $summary = $this->createReportService()->resultsSummary(
            $request->exam_id ? (int) $request->exam_id : null,
            $request->class_id ? (int) $request->class_id : null
        );

        return view('reports.academic.results-summary', compact('summary', 'exams', 'classes'));
    }

    public function classPerformance(Request $request): View
    {
        $exams = $this->scopedQuery(Exam::class)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $performance = $this->createReportService()->classPerformance(
            $request->exam_id ? (int) $request->exam_id : null
        );

        return view('reports.academic.class-performance', compact('performance', 'exams'));
    }

    public function subjectPerformance(Request $request): View
    {
        $exams = $this->scopedQuery(Exam::class)
            ->where('status', true)
            ->orderBy('name')
            ->get();
        $classes = $this->scopedQuery(SchoolClass::class)->orderBy('name')->get();

        $performance = $this->createReportService()->subjectPerformance(
            $request->exam_id ? (int) $request->exam_id : null,
            $request->class_id ? (int) $request->class_id : null
        );

        return view('reports.academic.subject-performance', compact('performance', 'exams', 'classes'));
    }

    // ── PDF Exports ───────────────────────────────────────

    public function exportStudentListPdf(Request $request): Response
    {
        $students = $this->createReportService()->studentList(
            $request->search,
            $request->class_id ? (int) $request->class_id : null,
            $request->status ?? 'active'
        );

        $pdf = Pdf::loadView('reports.exports.student-list-pdf', [
            'students' => $students,
            'school' => $this->resolveSchool(),
            'generatedAt' => now()->format('M d, Y \a\t h:i A'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('student-list-report.pdf');
    }

    public function exportTeacherListPdf(): Response
    {
        $teachers = $this->createReportService()->teacherList();

        $pdf = Pdf::loadView('reports.exports.teacher-list-pdf', [
            'teachers' => $teachers,
            'school' => $this->resolveSchool(),
            'generatedAt' => now()->format('M d, Y \a\t h:i A'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('teacher-list-report.pdf');
    }

    public function exportAttendanceSummaryPdf(Request $request): Response
    {
        $data = $this->createReportService()->attendanceSummary(
            $request->date_from,
            $request->date_to,
            $request->class_id ? (int) $request->class_id : null
        );

        $pdf = Pdf::loadView('reports.exports.attendance-summary-pdf', [
            'records' => $data['records'],
            'summary' => $data,
            'school' => $this->resolveSchool(),
            'generatedAt' => now()->format('M d, Y \a\t h:i A'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('attendance-summary-report.pdf');
    }

    public function exportPaymentHistoryPdf(Request $request): Response
    {
        $payments = $this->createReportService()->paymentHistory(
            $request->search,
            $request->class_id ? (int) $request->class_id : null,
            $request->date_from,
            $request->date_to,
            $request->method
        );

        $pdf = Pdf::loadView('reports.exports.payment-history-pdf', [
            'payments' => $payments,
            'school' => $this->resolveSchool(),
            'generatedAt' => now()->format('M d, Y \a\t h:i A'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('payment-history-report.pdf');
    }

    public function exportOutstandingPdf(Request $request): Response
    {
        $data = $this->createReportService()->outstandingFees(
            $request->class_id ? (int) $request->class_id : null
        );

        $pdf = Pdf::loadView('reports.exports.outstanding-pdf', [
            'items' => $data['items'],
            'totalOutstanding' => $data['total_outstanding'],
            'school' => $this->resolveSchool(),
            'generatedAt' => now()->format('M d, Y \a\t h:i A'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('outstanding-fees-report.pdf');
    }

    public function exportResultsSummaryPdf(Request $request): Response
    {
        $summary = $this->createReportService()->resultsSummary(
            $request->exam_id ? (int) $request->exam_id : null,
            $request->class_id ? (int) $request->class_id : null
        );

        $pdf = Pdf::loadView('reports.exports.results-summary-pdf', [
            'summary' => $summary,
            'school' => $this->resolveSchool(),
            'generatedAt' => now()->format('M d, Y \a\t h:i A'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('results-summary-report.pdf');
    }

    public function exportClassPerformancePdf(Request $request): Response
    {
        $performance = $this->createReportService()->classPerformance(
            $request->exam_id ? (int) $request->exam_id : null
        );

        $pdf = Pdf::loadView('reports.exports.class-performance-pdf', [
            'performance' => $performance,
            'school' => $this->resolveSchool(),
            'generatedAt' => now()->format('M d, Y \a\t h:i A'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('class-performance-report.pdf');
    }

    public function exportSubjectPerformancePdf(Request $request): Response
    {
        $performance = $this->createReportService()->subjectPerformance(
            $request->exam_id ? (int) $request->exam_id : null,
            $request->class_id ? (int) $request->class_id : null
        );

        $pdf = Pdf::loadView('reports.exports.subject-performance-pdf', [
            'performance' => $performance,
            'school' => $this->resolveSchool(),
            'generatedAt' => now()->format('M d, Y \a\t h:i A'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('subject-performance-report.pdf');
    }

    // ── CSV Exports ───────────────────────────────────────

    public function exportStudentListCsv(Request $request): Response
    {
        $students = $this->createReportService()->studentList(
            $request->search,
            $request->class_id ? (int) $request->class_id : null,
            $request->status ?? 'active'
        );

        return $this->buildCsvResponse(
            'student-list-report.csv',
            ['S/N', 'Adm. No.', 'First Name', 'Last Name', 'Gender', 'Class', 'Email', 'Phone', 'Status'],
            $students->map(fn ($s) => [
                $s->admission_number,
                $s->first_name,
                $s->last_name,
                ucfirst($s->gender),
                $s->schoolClass->name ?? '—',
                $s->email ?? '—',
                $s->phone ?? '—',
                ucfirst($s->status),
            ])
        );
    }

    public function exportTeacherListCsv(): Response
    {
        $teachers = $this->createReportService()->teacherList();

        return $this->buildCsvResponse(
            'teacher-list-report.csv',
            ['S/N', 'First Name', 'Last Name', 'Gender', 'Email', 'Phone', 'Qualification', 'Status'],
            $teachers->map(fn ($t) => [
                $t->first_name,
                $t->last_name,
                ucfirst($t->gender),
                $t->email ?? '—',
                $t->phone,
                $t->qualification ?? '—',
                $t->status ? 'Active' : 'Inactive',
            ])
        );
    }

    public function exportAttendanceSummaryCsv(Request $request): Response
    {
        $data = $this->createReportService()->attendanceSummary(
            $request->date_from,
            $request->date_to,
            $request->class_id ? (int) $request->class_id : null
        );

        return $this->buildCsvResponse(
            'attendance-summary-report.csv',
            ['S/N', 'Student', 'Adm. No.', 'Class', 'Date', 'Status', 'Remarks'],
            $data['records']->map(fn ($a, $i) => [
                $i + 1,
                $a->student->full_name ?? '—',
                $a->student->admission_number ?? '—',
                $a->schoolClass->name ?? '—',
                $a->attendance_date->format('M d, Y'),
                ucfirst($a->status),
                $a->remarks ?? '—',
            ])
        );
    }

    public function exportPaymentHistoryCsv(Request $request): Response
    {
        $payments = $this->createReportService()->paymentHistory(
            $request->search,
            $request->class_id ? (int) $request->class_id : null,
            $request->date_from,
            $request->date_to,
            $request->method
        );

        return $this->buildCsvResponse(
            'payment-history-report.csv',
            ['S/N', 'Student', 'Adm. No.', 'Class', 'Fee Title', 'Amount', 'Method', 'Reference', 'Date'],
            $payments->map(fn ($p, $i) => [
                $i + 1,
                $p->student->full_name ?? '—',
                $p->student->admission_number ?? '—',
                $p->feeStructure->schoolClass->name ?? '—',
                $p->feeStructure->title ?? '—',
                number_format($p->amount_paid, 2),
                ucfirst($p->payment_method),
                $p->reference ?? '—',
                $p->payment_date->format('M d, Y'),
            ])
        );
    }

    public function exportOutstandingCsv(Request $request): Response
    {
        $data = $this->createReportService()->outstandingFees(
            $request->class_id ? (int) $request->class_id : null
        );

        return $this->buildCsvResponse(
            'outstanding-fees-report.csv',
            ['S/N', 'Student', 'Adm. No.', 'Class', 'Total Fees', 'Paid', 'Balance'],
            $data['items']->map(fn ($item, $i) => [
                $i + 1,
                $item['student']->full_name,
                $item['student']->admission_number,
                $item['student']->schoolClass->name ?? '—',
                number_format($item['total_fees'], 2),
                number_format($item['total_paid'], 2),
                number_format($item['balance'], 2),
            ])
        );
    }

    public function exportResultsSummaryCsv(Request $request): Response
    {
        $summary = $this->createReportService()->resultsSummary(
            $request->exam_id ? (int) $request->exam_id : null,
            $request->class_id ? (int) $request->class_id : null
        );

        if (! $summary || $summary['subject_averages']->isEmpty()) {
            return redirect()->route('reports.academic.results-summary')
                ->with('error', 'No results data available for export.');
        }

        return $this->buildCsvResponse(
            'results-summary-report.csv',
            ['Subject', 'Students', 'Average', 'Highest', 'Lowest'],
            $summary['subject_averages']->map(fn ($s) => [
                $s['subject']->name ?? '—',
                $s['count'],
                $s['average'],
                $s['highest'],
                $s['lowest'],
            ])
        );
    }

    public function exportClassPerformancePdfDirect(Request $request): Response
    {
        return $this->exportClassPerformancePdf($request);
    }

    public function exportSubjectPerformanceCsv(Request $request): Response
    {
        $performance = $this->createReportService()->subjectPerformance(
            $request->exam_id ? (int) $request->exam_id : null,
            $request->class_id ? (int) $request->class_id : null
        );

        return $this->buildCsvResponse(
            'subject-performance-report.csv',
            ['Subject', 'Students', 'Records', 'Average', 'Pass Rate', 'Highest', 'Lowest'],
            $performance->map(fn ($s) => [
                $s['subject']->name,
                $s['students'],
                $s['records'],
                $s['average'],
                $s['pass_rate'].'%',
                $s['highest'],
                $s['lowest'],
            ])
        );
    }

    // ── Helpers ───────────────────────────────────────────

    private function buildCsvResponse(string $filename, array $headers, $rows): Response
    {
        $callback = function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, $headers);

            $counter = 1;
            foreach ($rows as $row) {
                $row = array_values((array) $row);
                if (is_array($row) && isset($row[0]) && is_int($row[0])) {
                    // Already has serial number
                } else {
                    array_unshift($row, $counter);
                }
                fputcsv($handle, $row);
                $counter++;
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
