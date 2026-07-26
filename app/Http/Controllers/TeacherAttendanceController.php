<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TeacherAttendanceController extends Controller
{
    protected function resolveTeacher(Request $request): Teacher
    {
        $teacher = $request->attributes->get('teacher');

        if (! $teacher) {
            abort(403, 'Teacher profile not found.');
        }

        return $teacher;
    }

    public function index(Request $request): View
    {
        $teacher = $this->resolveTeacher($request);

        $attendances = Attendance::query()
            ->where('school_id', $teacher->school_id)
            ->with('student', 'schoolClass', 'school', 'marker')
            ->when($request->date, function ($query, $date) {
                $query->where('attendance_date', $date);
            })
            ->when($request->class_id, function ($query, $classId) {
                $query->where('school_class_id', $classId);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest('attendance_date')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $classes = SchoolClass::where('school_id', $teacher->school_id)
            ->whereIn('id', $teacher->schoolClasses->pluck('id'))
            ->orderBy('name')
            ->get();

        return view('attendance.index', compact('attendances', 'classes'));
    }

    public function create(Request $request): View
    {
        $teacher = $this->resolveTeacher($request);

        $classes = SchoolClass::where('school_id', $teacher->school_id)
            ->whereIn('id', $teacher->schoolClasses->pluck('id'))
            ->orderBy('name')
            ->get();

        $selectedClass = $request->class_id;
        $selectedDate = $request->date ?? now()->toDateString();

        $students = collect();
        $existingAttendances = [];

        if ($selectedClass) {
            $students = Student::where('school_class_id', $selectedClass)
                ->where('school_id', $teacher->school_id)
                ->where('status', 'active')
                ->orderBy('first_name')
                ->get();

            $existingAttendances = Attendance::where('school_class_id', $selectedClass)
                ->where('attendance_date', $selectedDate)
                ->pluck('status', 'student_id')
                ->toArray();
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
        $teacher = $this->resolveTeacher($request);

        $validated = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'attendance_date' => 'required|date|before_or_equal:today',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
            'attendances.*.remarks' => 'nullable|string|max:500',
        ]);

        $class = SchoolClass::findOrFail($validated['school_class_id']);

        abort_if(
            $class->school_id !== $teacher->school_id || ! $teacher->schoolClasses->contains($class->id),
            403,
            'Unauthorized access to this class.'
        );

        $attendanceDate = $validated['attendance_date'];

        $studentIds = array_column($validated['attendances'], 'student_id');
        $validStudents = Student::where('school_class_id', $class->id)
            ->where('school_id', $teacher->school_id)
            ->whereIn('id', $studentIds)
            ->pluck('id')
            ->toArray();

        $invalidStudents = array_diff($studentIds, $validStudents);
        if (! empty($invalidStudents)) {
            return back()
                ->withErrors(['attendances' => 'Some selected students do not belong to the chosen class.'])
                ->withInput();
        }

        DB::transaction(function () use ($validated, $class, $attendanceDate, $teacher) {
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
                        'marked_by' => $teacher->id,
                    ]
                );
            }
        });

        return redirect()
            ->route('teacher.attendance.class-report.show', $class)
            ->with('success', 'Attendance recorded successfully for '.now()->parse($attendanceDate)->format('M d, Y').'.');
    }

    public function show(Attendance $attendance): View
    {
        $teacher = $this->resolveTeacher(request());

        abort_if($attendance->school_id !== $teacher->school_id, 403, 'Unauthorized access.');

        $attendance->load('student', 'schoolClass', 'school', 'marker');

        return view('attendance.show', compact('attendance'));
    }

    public function classReport(Request $request): View
    {
        $teacher = $this->resolveTeacher($request);

        $classes = SchoolClass::where('school_id', $teacher->school_id)
            ->whereIn('id', $teacher->schoolClasses->pluck('id'))
            ->orderBy('name')
            ->get();

        $selectedClass = $request->class_id;
        $selectedDate = $request->date ?? now()->toDateString();

        $report = null;

        if ($selectedClass) {
            $class = SchoolClass::where('id', $selectedClass)
                ->where('school_id', $teacher->school_id)
                ->firstOrFail();

            $students = Student::where('school_class_id', $class->id)
                ->where('school_id', $teacher->school_id)
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

    public function classReportShow(SchoolClass $schoolClass): View
    {
        $teacher = $this->resolveTeacher(request());

        abort_if($schoolClass->school_id !== $teacher->school_id || ! $teacher->schoolClasses->contains($schoolClass->id), 403, 'Unauthorized access.');

        return $this->classReport(request()->merge(['class_id' => $schoolClass->id]));
    }

    public function monthlyReport(Request $request): View
    {
        $teacher = $this->resolveTeacher($request);

        $classes = SchoolClass::where('school_id', $teacher->school_id)
            ->whereIn('id', $teacher->schoolClasses->pluck('id'))
            ->orderBy('name')
            ->get();

        $selectedClass = $request->class_id;
        $selectedMonth = $request->month ?? now()->format('Y-m');

        $report = null;

        if ($selectedClass) {
            $class = SchoolClass::where('id', $selectedClass)
                ->where('school_id', $teacher->school_id)
                ->firstOrFail();

            $startDate = now()->parse($selectedMonth)->startOfMonth()->toDateString();
            $endDate = now()->parse($selectedMonth)->endOfMonth()->toDateString();

            $students = Student::where('school_class_id', $class->id)
                ->where('school_id', $teacher->school_id)
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

    public function monthlyReportShow(SchoolClass $schoolClass): View
    {
        $teacher = $this->resolveTeacher(request());

        abort_if($schoolClass->school_id !== $teacher->school_id || ! $teacher->schoolClasses->contains($schoolClass->id), 403, 'Unauthorized access.');

        return $this->monthlyReport(request()->merge(['class_id' => $schoolClass->id]));
    }
}
