<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ResolvesStudent;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use App\Models\StudentResult;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    use ResolvesStudent;

    public function __invoke(): View
    {
        $student = $this->resolveStudent();

        $attendanceRate = Attendance::where('student_id', $student->id)
            ->where('school_id', $student->school_id)
            ->where('attendance_date', '>=', Carbon::now()->subMonth())
            ->selectRaw('count(*) as total, sum(case when status in ("present","late","excused") then 1 else 0 end) as present')
            ->first();

        $totalDays = (int) ($attendanceRate->total ?? 0);
        $presentDays = (int) ($attendanceRate->present ?? 0);
        $rate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

        $totalFees = FeeStructure::where('school_class_id', $student->school_class_id)
            ->where('school_id', $student->school_id)
            ->where('status', true)
            ->sum('amount');

        $totalPaid = FeePayment::where('student_id', $student->id)
            ->where('school_id', $student->school_id)
            ->sum('amount_paid');

        $outstanding = max(0, (float) $totalFees - (float) $totalPaid);

        $upcomingAssignments = Assignment::where('class_id', $student->school_class_id)
            ->where('school_id', $student->school_id)
            ->where('due_date', '>=', Carbon::today())
            ->with(['subject', 'teacher'])
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        $recentResults = $student->studentResults()
            ->where('school_id', $student->school_id)
            ->with(['subject', 'assessmentType', 'exam'])
            ->latest()
            ->limit(5)
            ->get();

        $latestReportCard = $student->studentReportCards()
            ->where('school_id', $student->school_id)
            ->whereIn('status', ['approved', 'published'])
            ->with('exam')
            ->latest()
            ->first();

        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();

        $attendanceTrend = Attendance::where('student_id', $student->id)
            ->where('school_id', $student->school_id)
            ->where('attendance_date', '>=', $sixMonthsAgo)
            ->orderBy('attendance_date')
            ->get()
            ->groupBy(fn ($a) => $a->attendance_date->format('M Y'))
            ->map(function ($group) {
                $total = $group->count();
                $present = $group->whereIn('status', ['present', 'late', 'excused'])->count();

                return $total > 0 ? round(($present / $total) * 100, 1) : 0;
            })
            ->toArray();

        $scorePerformance = StudentResult::where('student_id', $student->id)
            ->where('school_id', $student->school_id)
            ->with('subject')
            ->latest('id')
            ->get()
            ->groupBy('subject_id')
            ->map(function ($group) {
                return [
                    'subject' => $group->first()->subject->name ?? 'Unknown',
                    'average' => round($group->avg('score'), 1),
                ];
            })
            ->values();

        $totalAssignments = Assignment::where('class_id', $student->school_class_id)
            ->where('school_id', $student->school_id)
            ->count();

        $overdueAssignments = Assignment::where('class_id', $student->school_class_id)
            ->where('school_id', $student->school_id)
            ->where('due_date', '<', Carbon::today())
            ->count();

        $upcomingCount = $upcomingAssignments->count();
        $completedCount = max(0, $totalAssignments - $overdueAssignments - $upcomingCount);

        $chartData = [
            'attendance_trend_labels' => array_keys($attendanceTrend),
            'attendance_trend_data' => array_values($attendanceTrend),
            'score_subject_labels' => $scorePerformance->pluck('subject')->toArray(),
            'score_subject_data' => $scorePerformance->pluck('average')->toArray(),
            'assignment_labels' => ['Upcoming', 'Overdue', 'Completed'],
            'assignment_data' => [$upcomingCount, $overdueAssignments, $completedCount],
        ];

        return view('student.dashboard', compact(
            'student',
            'rate',
            'totalDays',
            'presentDays',
            'totalPaid',
            'outstanding',
            'upcomingAssignments',
            'recentResults',
            'latestReportCard',
            'chartData',
        ));
    }
}
