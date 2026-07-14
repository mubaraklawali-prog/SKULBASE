<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Exam;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function __construct(private readonly ?int $schoolId = null) {}

    private function schoolScope($query)
    {
        return $query->when($this->schoolId !== null, fn ($q) => $q->where('school_id', $this->schoolId));
    }

    // ── Dashboard ─────────────────────────────────────────

    public function dashboardSummary(): array
    {
        return [
            'total_students' => $this->schoolScope(Student::query())->count(),
            'total_teachers' => $this->schoolScope(Teacher::query())->count(),
            'total_classes' => $this->schoolScope(SchoolClass::query())->count(),
            'total_subjects' => $this->schoolScope(Subject::query())->count(),
            'total_fee_payments' => $this->schoolScope(FeePayment::query())->count(),
            'total_collected' => (float) $this->schoolScope(FeePayment::query())->sum('amount_paid'),
            'total_outstanding' => $this->calculateOutstanding(),
            'active_students' => $this->schoolScope(Student::query()->where('status', 'active'))->count(),
            'inactive_students' => $this->schoolScope(Student::query()->where('status', '!=', 'active'))->count(),
            'active_teachers' => $this->schoolScope(Teacher::query()->where('status', true))->count(),
            'today_attendance_rate' => $this->todayAttendanceRate(),
        ];
    }

    private function calculateOutstanding(): float
    {
        $totalPaid = (float) $this->schoolScope(FeePayment::query())->sum('amount_paid');

        $totalFees = (float) FeeStructure::when($this->schoolId !== null, fn ($q) => $q->where('school_id', $this->schoolId))
            ->where('fee_structures.status', true)
            ->join('students', 'students.school_class_id', '=', 'fee_structures.school_class_id')
            ->when($this->schoolId !== null, fn ($q) => $q->where('students.school_id', $this->schoolId))
            ->where('students.status', 'active')
            ->sum(DB::raw('fee_structures.amount'));

        return max(0, $totalFees - $totalPaid);
    }

    private function todayAttendanceRate(): float
    {
        $today = Carbon::today();
        $total = $this->schoolScope(Attendance::query())
            ->where('attendance_date', $today)
            ->count();

        if ($total === 0) {
            return 0;
        }

        $present = $this->schoolScope(Attendance::query())
            ->where('attendance_date', $today)
            ->whereIn('status', ['present', 'late', 'excused'])
            ->count();

        return round(($present / $total) * 100, 1);
    }

    // ── Student Reports ───────────────────────────────────

    public function studentList(?string $search = null, ?int $classId = null, string $status = 'active')
    {
        return $this->schoolScope(Student::query())
            ->with('schoolClass')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('admission_number', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($classId, fn ($q) => $q->where('school_class_id', $classId))
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->orderBy('first_name')
            ->paginate(15)
            ->withQueryString();
    }

    public function studentsByClass(): Collection
    {
        return $this->schoolScope(SchoolClass::query())
            ->withCount(['students' => fn ($q) => $q->where('status', 'active')])
            ->orderBy('name')
            ->get();
    }

    public function studentStatusBreakdown(): array
    {
        $total = $this->schoolScope(Student::query())->count();

        $byStatus = $this->schoolScope(Student::query())
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        $byGender = $this->schoolScope(Student::query())
            ->select('gender', DB::raw('COUNT(*) as count'))
            ->groupBy('gender')
            ->get()
            ->pluck('count', 'gender')
            ->toArray();

        return [
            'total' => $total,
            'by_status' => $byStatus,
            'by_gender' => $byGender,
        ];
    }

    // ── Teacher Reports ───────────────────────────────────

    public function teacherList(?string $search = null, string $status = 'active')
    {
        return $this->schoolScope(Teacher::query())
            ->with('subjects')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($status === 'active', fn ($q) => $q->where('status', true))
            ->when($status === 'inactive', fn ($q) => $q->where('status', false))
            ->orderBy('first_name')
            ->paginate(15)
            ->withQueryString();
    }

    public function teachersBySubject(): Collection
    {
        return $this->schoolScope(Subject::query())
            ->withCount('teachers')
            ->orderBy('name')
            ->get();
    }

    public function teacherStatusBreakdown(): array
    {
        $active = $this->schoolScope(Teacher::query()->where('status', true))->count();
        $inactive = $this->schoolScope(Teacher::query()->where('status', false))->count();

        return [
            'active' => $active,
            'inactive' => $inactive,
            'total' => $active + $inactive,
        ];
    }

    // ── Attendance Reports ─────────────────────────────────

    public function attendanceSummary(?string $dateFrom = null, ?string $dateTo = null, ?int $classId = null)
    {
        $query = $this->schoolScope(Attendance::query())
            ->with(['student', 'schoolClass'])
            ->when($dateFrom, fn ($q) => $q->where('attendance_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->where('attendance_date', '<=', $dateTo))
            ->when($classId, fn ($q) => $q->where('school_class_id', $classId));

        $records = $query->get();

        $totalRecords = $records->count();
        $present = $records->where('status', 'present')->count();
        $absent = $records->where('status', 'absent')->count();
        $late = $records->where('status', 'late')->count();
        $excused = $records->where('status', 'excused')->count();
        $attendanceRate = $totalRecords > 0 ? round((($present + $late + $excused) / $totalRecords) * 100, 1) : 0;

        return [
            'total_records' => $totalRecords,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'excused' => $excused,
            'attendance_rate' => $attendanceRate,
            'records' => $records->sortBy('schoolClass.name')->values(),
        ];
    }

    public function attendanceByDate(?string $dateFrom = null, ?string $dateTo = null)
    {
        $query = $this->schoolScope(Attendance::query())
            ->select('attendance_date', 'status', DB::raw('COUNT(*) as count'));

        if ($dateFrom) {
            $query->where('attendance_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('attendance_date', '<=', $dateTo);
        }

        $records = $query->groupBy('attendance_date', 'status')
            ->orderBy('attendance_date', 'desc')
            ->get()
            ->groupBy('attendance_date');

        $results = [];
        foreach ($records as $date => $dayRecords) {
            $total = $dayRecords->sum('count');
            $present = $dayRecords->where('status', 'present')->first()->count ?? 0;
            $absent = $dayRecords->where('status', 'absent')->first()->count ?? 0;
            $late = $dayRecords->where('status', 'late')->first()->count ?? 0;
            $excused = $dayRecords->where('status', 'excused')->first()->count ?? 0;

            $results[] = [
                'date' => $date,
                'total' => $total,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'excused' => $excused,
                'rate' => $total > 0 ? round((($present + $late + $excused) / $total) * 100, 1) : 0,
            ];
        }

        return $results;
    }

    public function attendanceByClass(?string $dateFrom = null, ?string $dateTo = null)
    {
        $query = $this->schoolScope(SchoolClass::query())
            ->withCount(['students' => fn ($q) => $q->where('status', 'active')]);

        $classes = $query->orderBy('name')->get();

        return $classes->map(function ($class) use ($dateFrom, $dateTo) {
            $attendanceQuery = Attendance::where('school_class_id', $class->id)
                ->when($this->schoolId !== null, fn ($q) => $q->where('school_id', $this->schoolId));

            if ($dateFrom) {
                $attendanceQuery->where('attendance_date', '>=', $dateFrom);
            }
            if ($dateTo) {
                $attendanceQuery->where('attendance_date', '<=', $dateTo);
            }

            $total = $attendanceQuery->count();
            $present = (clone $attendanceQuery)->whereIn('status', ['present'])->count();
            $late = (clone $attendanceQuery)->where('status', 'late')->count();
            $excused = (clone $attendanceQuery)->where('status', 'excused')->count();
            $absent = (clone $attendanceQuery)->where('status', 'absent')->count();

            $daysMarked = (clone $attendanceQuery)->distinct('attendance_date')->count('attendance_date');

            return [
                'class' => $class,
                'total_students' => $class->students_count,
                'days_marked' => $daysMarked,
                'total_records' => $total,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'excused' => $excused,
                'rate' => $total > 0 ? round((($present + $late + $excused) / $total) * 100, 1) : 0,
            ];
        });
    }

    // ── Fee Reports ───────────────────────────────────────

    public function paymentHistory(?string $search = null, ?int $classId = null, ?string $dateFrom = null, ?string $dateTo = null, ?string $method = null)
    {
        return $this->schoolScope(FeePayment::query())
            ->with('student', 'feeStructure.schoolClass')
            ->when($search, function ($query, $search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('admission_number', 'like', "%{$search}%");
                })->orWhere('reference', 'like', "%{$search}%");
            })
            ->when($classId, function ($query) use ($classId) {
                $query->whereHas('feeStructure', fn ($q) => $q->where('school_class_id', $classId));
            })
            ->when($dateFrom, fn ($q) => $q->where('payment_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->where('payment_date', '<=', $dateTo))
            ->when($method, fn ($q) => $q->where('payment_method', $method))
            ->latest('payment_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();
    }

    public function outstandingFees(?int $classId = null)
    {
        $students = $this->schoolScope(Student::query())
            ->with('schoolClass')
            ->where('status', 'active')
            ->when($classId, fn ($q) => $q->where('school_class_id', $classId))
            ->orderBy('first_name')
            ->get();

        $outstanding = $students->map(function ($student) {
            $feeStructures = FeeStructure::where('school_class_id', $student->school_class_id)
                ->when($this->schoolId !== null, fn ($q) => $q->where('school_id', $this->schoolId))
                ->where('status', true)
                ->get();

            $totalFees = (float) $feeStructures->sum('amount');
            $totalPaid = (float) FeePayment::where('student_id', $student->id)
                ->whereIn('fee_structure_id', $feeStructures->pluck('id'))
                ->sum('amount_paid');

            $balance = $totalFees - $totalPaid;

            return [
                'student' => $student,
                'total_fees' => $totalFees,
                'total_paid' => $totalPaid,
                'balance' => $balance,
            ];
        })->filter(fn ($item) => $item['balance'] > 0.01)->values();

        return [
            'items' => $outstanding,
            'total_outstanding' => $outstanding->sum('balance'),
            'count' => $outstanding->count(),
        ];
    }

    public function feesCollectedByDate(?string $dateFrom = null, ?string $dateTo = null)
    {
        $query = $this->schoolScope(FeePayment::query())
            ->select('payment_date', 'payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount_paid) as total'))
            ->groupBy('payment_date', 'payment_method');

        if ($dateFrom) {
            $query->where('payment_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('payment_date', '<=', $dateTo);
        }

        $records = $query->orderBy('payment_date', 'desc')
            ->get()
            ->groupBy('payment_date');

        $results = [];
        foreach ($records as $date => $dayRecords) {
            $results[] = [
                'date' => $date,
                'total' => (float) $dayRecords->sum('total'),
                'count' => $dayRecords->sum('count'),
                'by_method' => $dayRecords->mapWithKeys(fn ($r) => [$r->payment_method => ['count' => $r->count, 'total' => (float) $r->total]]),
            ];
        }

        $grandTotal = (float) $this->schoolScope(FeePayment::query())
            ->when($dateFrom, fn ($q) => $q->where('payment_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->where('payment_date', '<=', $dateTo))
            ->sum('amount_paid');

        return [
            'days' => $results,
            'grand_total' => $grandTotal,
        ];
    }

    // ── Academic Reports ──────────────────────────────────

    public function resultsSummary(?int $examId = null, ?int $classId = null)
    {
        if (! $examId) {
            $latestExam = $this->schoolScope(Exam::query())
                ->where('status', true)
                ->latest()
                ->first();
            $examId = $latestExam?->id;
        }

        if (! $examId) {
            return null;
        }

        $query = $this->schoolScope(StudentResult::query())
            ->with(['student', 'subject', 'schoolClass', 'exam'])
            ->where('exam_id', $examId);

        if ($classId) {
            $query->where('school_class_id', $classId);
        }

        $results = $query->get();

        $totalStudents = $results->pluck('student_id')->unique()->count();
        $totalRecords = $results->count();
        $averageScore = $totalRecords > 0 ? round($results->avg('score'), 1) : 0;
        $passCount = $results->where('score', '>=', 50)->count();
        $passRate = $totalRecords > 0 ? round(($passCount / $totalRecords) * 100, 1) : 0;
        $highestScore = $totalRecords > 0 ? round($results->max('score'), 1) : 0;
        $lowestScore = $totalRecords > 0 ? round($results->min('score'), 1) : 0;

        $subjectAverages = $results->groupBy('subject_id')->map(function ($group) {
            return [
                'subject' => $group->first()->subject,
                'count' => $group->count(),
                'average' => round($group->avg('score'), 1),
                'highest' => round($group->max('score'), 1),
                'lowest' => round($group->min('score'), 1),
            ];
        })->sortByDesc('average')->values();

        return [
            'exam' => $results->first()->exam ?? null,
            'total_students' => $totalStudents,
            'total_records' => $totalRecords,
            'average_score' => $averageScore,
            'pass_rate' => $passRate,
            'pass_count' => $passCount,
            'fail_count' => $totalRecords - $passCount,
            'highest_score' => $highestScore,
            'lowest_score' => $lowestScore,
            'subject_averages' => $subjectAverages,
        ];
    }

    public function classPerformance(?int $examId = null)
    {
        if (! $examId) {
            $latestExam = $this->schoolScope(Exam::query())
                ->where('status', true)
                ->latest()
                ->first();
            $examId = $latestExam?->id;
        }

        if (! $examId) {
            return collect();
        }

        $classes = $this->schoolScope(SchoolClass::query())
            ->withCount(['students' => fn ($q) => $q->where('status', 'active')])
            ->orderBy('name')
            ->get();

        return $classes->map(function ($class) use ($examId) {
            $results = StudentResult::where('school_class_id', $class->id)
                ->where('exam_id', $examId)
                ->when($this->schoolId !== null, fn ($q) => $q->where('school_id', $this->schoolId))
                ->get();

            $totalStudents = $results->pluck('student_id')->unique()->count();
            $averageScore = $totalStudents > 0 ? round($results->avg('score'), 1) : 0;
            $passRate = $results->count() > 0
                ? round(($results->where('score', '>=', 50)->count() / $results->count()) * 100, 1)
                : 0;

            return [
                'class' => $class,
                'enrolled' => $class->students_count,
                'tested' => $totalStudents,
                'average' => $averageScore,
                'pass_rate' => $passRate,
                'highest' => $results->count() > 0 ? round($results->max('score'), 1) : 0,
                'lowest' => $results->count() > 0 ? round($results->min('score'), 1) : 0,
            ];
        });
    }

    public function subjectPerformance(?int $examId = null, ?int $classId = null)
    {
        if (! $examId) {
            $latestExam = $this->schoolScope(Exam::query())
                ->where('status', true)
                ->latest()
                ->first();
            $examId = $latestExam?->id;
        }

        if (! $examId) {
            return collect();
        }

        $subjects = $this->schoolScope(Subject::query())->orderBy('name')->get();

        return $subjects->map(function ($subject) use ($examId, $classId) {
            $query = StudentResult::where('subject_id', $subject->id)
                ->where('exam_id', $examId)
                ->when($this->schoolId !== null, fn ($q) => $q->where('school_id', $this->schoolId));

            if ($classId) {
                $query->where('school_class_id', $classId);
            }

            $results = $query->get();

            $totalStudents = $results->pluck('student_id')->unique()->count();
            $averageScore = $results->count() > 0 ? round($results->avg('score'), 1) : 0;
            $passRate = $results->count() > 0
                ? round(($results->where('score', '>=', 50)->count() / $results->count()) * 100, 1)
                : 0;

            return [
                'subject' => $subject,
                'students' => $totalStudents,
                'records' => $results->count(),
                'average' => $averageScore,
                'pass_rate' => $passRate,
                'highest' => $results->count() > 0 ? round($results->max('score'), 1) : 0,
                'lowest' => $results->count() > 0 ? round($results->min('score'), 1) : 0,
            ];
        });
    }
}
