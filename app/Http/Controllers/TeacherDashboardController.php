<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ResolvesTeacher;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Message;
use App\Models\StudentResult;
use App\Models\Timetable;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class TeacherDashboardController extends Controller
{
    use ResolvesTeacher;

    public function __invoke(): View
    {
        $teacher = $this->resolveTeacher();

        $classes = $teacher->schoolClasses()->withCount('students')->get();
        $subjects = $teacher->subjects;

        $totalStudents = $classes->sum('students_count');

        $upcomingAssignments = Assignment::where('teacher_id', $teacher->id)
            ->where('school_id', $teacher->school_id)
            ->where('due_date', '>=', Carbon::today())
            ->with(['schoolClass', 'subject'])
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        $recentResults = StudentResult::where('teacher_id', $teacher->id)
            ->where('school_id', $teacher->school_id)
            ->with(['student', 'subject', 'exam'])
            ->latest()
            ->limit(5)
            ->get();

        $recentAttendanceCount = Attendance::where('marked_by', $teacher->id)
            ->where('school_id', $teacher->school_id)
            ->where('attendance_date', '>=', Carbon::now()->subWeek())
            ->count();

        $pendingAssignments = Assignment::where('teacher_id', $teacher->id)
            ->where('school_id', $teacher->school_id)
            ->where('due_date', '<', Carbon::today())
            ->count();

        $todayDay = Carbon::now()->format('l');

        $todayTimetable = Timetable::where('teacher_id', $teacher->id)
            ->where('school_id', $teacher->school_id)
            ->where('day', $todayDay)
            ->with(['schoolClass', 'subject', 'period'])
            ->get()
            ->sortBy('period.sort_order');

        $unreadMessages = Message::where('school_id', $teacher->school_id)
            ->whereHas('recipients', function ($q) use ($teacher) {
                $q->where('user_id', $teacher->user_id)->whereNull('read_at');
            })
            ->count();

        $activeAnnouncements = Announcement::where('school_id', $teacher->school_id)
            ->active()
            ->latest()
            ->limit(5)
            ->get();

        $todayAttendanceMarked = Attendance::where('marked_by', $teacher->id)
            ->where('school_id', $teacher->school_id)
            ->where('attendance_date', Carbon::today())
            ->count();

        $weeklyAttendance = Attendance::where('marked_by', $teacher->id)
            ->where('school_id', $teacher->school_id)
            ->where('attendance_date', '>=', Carbon::now()->startOfWeek())
            ->selectRaw('attendance_date, count(*) as total, sum(case when status in ("present","late","excused") then 1 else 0 end) as present')
            ->groupBy('attendance_date')
            ->orderBy('attendance_date')
            ->get();

        $totalAssignments = Assignment::where('teacher_id', $teacher->id)
            ->where('school_id', $teacher->school_id)
            ->count();

        $overdueAssignments = Assignment::where('teacher_id', $teacher->id)
            ->where('school_id', $teacher->school_id)
            ->where('due_date', '<', Carbon::today())
            ->count();

        $upcomingCount = Assignment::where('teacher_id', $teacher->id)
            ->where('school_id', $teacher->school_id)
            ->where('due_date', '>=', Carbon::today())
            ->count();

        $completedCount = max(0, $totalAssignments - $overdueAssignments - $upcomingCount);

        $classStudentData = $classes->pluck('students_count', 'name')->toArray();

        $chartData = [
            'weekly_labels' => $weeklyAttendance->pluck('attendance_date')->map(fn ($d) => Carbon::parse($d)->format('D'))->toArray(),
            'weekly_present' => $weeklyAttendance->pluck('present')->map(fn ($v) => (int) $v)->toArray(),
            'weekly_total' => $weeklyAttendance->pluck('total')->map(fn ($v) => (int) $v)->toArray(),
            'assignment_labels' => ['Upcoming', 'Overdue', 'Completed'],
            'assignment_data' => [$upcomingCount, $overdueAssignments, $completedCount],
            'class_labels' => array_keys($classStudentData),
            'class_data' => array_values($classStudentData),
        ];

        $teacherStats = [
            'total_classes' => $classes->count(),
            'total_students' => $totalStudents,
            'total_subjects' => $subjects->count(),
            'today_classes' => $todayTimetable->count(),
            'today_attendance_marked' => $todayAttendanceMarked,
            'unread_messages' => $unreadMessages,
            'pending_assignments' => $pendingAssignments,
        ];

        return view('teacher.dashboard', compact(
            'teacher',
            'classes',
            'subjects',
            'totalStudents',
            'upcomingAssignments',
            'recentResults',
            'recentAttendanceCount',
            'pendingAssignments',
            'todayTimetable',
            'activeAnnouncements',
            'teacherStats',
            'chartData',
        ));
    }
}
