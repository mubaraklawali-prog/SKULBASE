<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    private function schoolId(): ?int
    {
        $user = auth()->user();

        return $user->role === 'super_admin' ? null : $user->school_id;
    }

    public function dashboard(): View
    {
        $schoolId = $this->schoolId();
        $today = now()->toDateString();

        $statusCounts = Attendance::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->where('attendance_date', $today)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $totalPresentToday = $statusCounts->get('present', 0);
        $totalAbsentToday = $statusCounts->get('absent', 0);
        $totalLateToday = $statusCounts->get('late', 0);
        $totalExcusedToday = $statusCounts->get('excused', 0);
        $totalMarkedToday = $totalPresentToday + $totalAbsentToday + $totalLateToday + $totalExcusedToday;

        $attendancePercentage = $totalMarkedToday > 0
            ? round(($totalPresentToday / $totalMarkedToday) * 100, 1)
            : 0;

        $recentAttendances = Attendance::with('student', 'schoolClass', 'marker')
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->where('attendance_date', $today)
            ->latest()
            ->take(10)
            ->get();

        $classesWithTodayAttendance = SchoolClass::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->withCount(['students' => fn ($q) => $q->where('status', 'active'), 'attendances' => fn ($q) => $q->where('attendance_date', $today)])
            ->get();

        return view('attendance.dashboard', compact(
            'today',
            'totalPresentToday',
            'totalAbsentToday',
            'totalLateToday',
            'totalExcusedToday',
            'totalMarkedToday',
            'attendancePercentage',
            'recentAttendances',
            'classesWithTodayAttendance',
        ));
    }

    public function create(Request $request): View
    {
        $schoolId = $this->schoolId();
        $classes = SchoolClass::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $selectedClass = $request->class_id;
        $selectedDate = $request->date ?? now()->toDateString();

        $students = collect();

        if ($selectedClass) {
            $students = Student::where('school_class_id', $selectedClass)
                ->where('status', 'active')
                ->orderBy('first_name')
                ->get();

            $existingAttendances = Attendance::where('school_class_id', $selectedClass)
                ->where('attendance_date', $selectedDate)
                ->pluck('status', 'student_id')
                ->toArray();
        } else {
            $existingAttendances = [];
        }

        return view('attendance.create', compact(
            'classes',
            'selectedClass',
            'selectedDate',
            'students',
            'existingAttendances',
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'attendance_date' => 'required|date|before_or_equal:today',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
            'attendances.*.remarks' => 'nullable|string|max:500',
        ]);

        $class = SchoolClass::findOrFail($validated['school_class_id']);
        $attendanceDate = $validated['attendance_date'];

        $studentIds = array_column($validated['attendances'], 'student_id');
        $validStudents = Student::where('school_class_id', $class->id)
            ->whereIn('id', $studentIds)
            ->pluck('id')
            ->toArray();

        $invalidStudents = array_diff($studentIds, $validStudents);
        if (! empty($invalidStudents)) {
            return back()
                ->withErrors(['attendances' => 'Some selected students do not belong to the chosen class.'])
                ->withInput();
        }

        DB::transaction(function () use ($validated, $class, $attendanceDate) {
            foreach ($validated['attendances'] as $attendance) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $attendance['student_id'],
                        'attendance_date' => $attendanceDate,
                    ],
                    [
                        'school_id' => $class->school_id,
                        'school_class_id' => $class->id,
                        'status' => $attendance['status'],
                        'remarks' => $attendance['remarks'] ?? null,
                        'marked_by' => auth()->id(),
                    ]
                );
            }
        });

        return redirect()
            ->route('attendance.class-report.show', $class)
            ->with('success', 'Attendance recorded successfully for '.now()->parse($attendanceDate)->format('M d, Y').'.');
    }

    public function index(Request $request): View
    {
        $schoolId = $this->schoolId();

        $attendances = Attendance::query()
            ->with('student', 'schoolClass', 'school', 'marker')
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->when($request->date, function ($query, $date) {
                $query->where('attendance_date', $date);
            })
            ->when($request->class_id, function ($query, $classId) {
                $query->where('school_class_id', $classId);
            })
            ->when($request->student_id, function ($query, $studentId) {
                $query->where('student_id', $studentId);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest('attendance_date')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $classes = SchoolClass::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $students = Student::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('first_name')->get();

        return view('attendance.index', compact('attendances', 'classes', 'students'));
    }

    public function show(Attendance $attendance): View
    {
        $attendance->load('student', 'schoolClass', 'school', 'marker');

        return view('attendance.show', compact('attendance'));
    }

    public function studentAttendance(Student $student): View
    {
        $student->load('school', 'schoolClass');

        $totalDays = Attendance::where('student_id', $student->id)->count();
        $presentDays = Attendance::where('student_id', $student->id)->where('status', 'present')->count();
        $absentDays = Attendance::where('student_id', $student->id)->where('status', 'absent')->count();
        $lateDays = Attendance::where('student_id', $student->id)->where('status', 'late')->count();
        $excusedDays = Attendance::where('student_id', $student->id)->where('status', 'excused')->count();
        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

        $attendances = Attendance::with('schoolClass', 'marker')
            ->where('student_id', $student->id)
            ->latest('attendance_date')
            ->paginate(20);

        return view('attendance.student', compact(
            'student',
            'totalDays',
            'presentDays',
            'absentDays',
            'lateDays',
            'excusedDays',
            'attendancePercentage',
            'attendances',
        ));
    }

    public function classReport(Request $request): View
    {
        $schoolId = $this->schoolId();
        $classes = SchoolClass::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $selectedClass = $request->class_id;
        $selectedDate = $request->date ?? now()->toDateString();

        $report = null;

        if ($selectedClass) {
            $class = SchoolClass::findOrFail($selectedClass);
            $students = Student::where('school_class_id', $class->id)
                ->where('status', 'active')
                ->orderBy('first_name')
                ->get();

            $attendanceMap = Attendance::where('school_class_id', $class->id)
                ->where('attendance_date', $selectedDate)
                ->pluck('status', 'student_id')
                ->toArray();

            $totalStudents = $students->count();
            $presentCount = collect($attendanceMap)->filter(fn ($s) => $s === 'present')->count();
            $absentCount = collect($attendanceMap)->filter(fn ($s) => $s === 'absent')->count();
            $lateCount = collect($attendanceMap)->filter(fn ($s) => $s === 'late')->count();
            $excusedCount = collect($attendanceMap)->filter(fn ($s) => $s === 'excused')->count();
            $markedCount = count($attendanceMap);
            $attendancePercentage = $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100, 1) : 0;

            $report = compact(
                'class',
                'students',
                'attendanceMap',
                'totalStudents',
                'presentCount',
                'absentCount',
                'lateCount',
                'excusedCount',
                'markedCount',
                'attendancePercentage',
            );
        }

        return view('attendance.class-report', compact('classes', 'selectedClass', 'selectedDate', 'report'));
    }

    public function monthlyReport(Request $request): View
    {
        $schoolId = $this->schoolId();
        $classes = SchoolClass::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $selectedClass = $request->class_id;
        $selectedMonth = $request->month ?? now()->format('Y-m');

        $report = null;

        if ($selectedClass) {
            $class = SchoolClass::findOrFail($selectedClass);
            $startDate = now()->parse($selectedMonth)->startOfMonth()->toDateString();
            $endDate = now()->parse($selectedMonth)->endOfMonth()->toDateString();

            $students = Student::where('school_class_id', $class->id)
                ->where('status', 'active')
                ->orderBy('first_name')
                ->get();

            $attendanceRecords = Attendance::where('school_class_id', $class->id)
                ->whereBetween('attendance_date', [$startDate, $endDate])
                ->get()
                ->groupBy('student_id');

            $studentStats = $students->map(function ($student) use ($attendanceRecords) {
                $records = $attendanceRecords->get($student->id, collect());
                $total = $records->count();
                $present = $records->where('status', 'present')->count();
                $absent = $records->where('status', 'absent')->count();
                $late = $records->where('status', 'late')->count();
                $excused = $records->where('status', 'excused')->count();
                $percentage = $total > 0 ? round(($present / $total) * 100, 1) : 0;

                return [
                    'student' => $student,
                    'total' => $total,
                    'present' => $present,
                    'absent' => $absent,
                    'late' => $late,
                    'excused' => $excused,
                    'percentage' => $percentage,
                ];
            });

            $totalSchoolDays = Attendance::where('school_class_id', $class->id)
                ->whereBetween('attendance_date', [$startDate, $endDate])
                ->distinct('attendance_date')
                ->count('attendance_date');

            $totalPresentAll = $studentStats->sum('present');
            $totalAbsencesAll = $studentStats->sum('absent');
            $totalStudents = $students->count();
            $overallPercentage = $totalStudents > 0 && $totalSchoolDays > 0
                ? round(($totalPresentAll / ($totalStudents * $totalSchoolDays)) * 100, 1)
                : 0;

            $report = compact(
                'class',
                'studentStats',
                'totalSchoolDays',
                'totalPresentAll',
                'totalAbsencesAll',
                'totalStudents',
                'overallPercentage',
            );
        }

        return view('attendance.monthly-report', compact('classes', 'selectedClass', 'selectedMonth', 'report'));
    }
}
