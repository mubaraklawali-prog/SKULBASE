<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Exam;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use App\Models\Message;
use App\Models\ParentModel;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Teacher;
use App\Models\User;
use App\Services\ReportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $schoolId = $user->school_id;
        $school = $user->school;
        $isSuperAdmin = $user->role === 'super_admin';
        $isParent = $user->role === 'parent';

        if ($isParent) {
            return $this->parentDashboard($user, $school);
        }

        if ($user->role === 'student') {
            return $this->studentDashboard();
        }

        if ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        if ($isSuperAdmin) {
            return $this->superAdminDashboard();
        }

        return $this->schoolAdminDashboard($user, $school, $schoolId);
    }

    private function superAdminDashboard(): View
    {
        $totalSchools = School::count();
        $activeSchools = School::where('is_active', true)->count();
        $pendingSchools = School::where('registration_status', 'pending')->count();
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalUsers = User::count();
        $totalRevenue = FeePayment::sum('amount_paid');

        $recentSchools = School::latest()->limit(5)->get();

        $platformStats = [
            'total_schools' => $totalSchools,
            'active_schools' => $activeSchools,
            'pending_schools' => $pendingSchools,
            'total_students' => $totalStudents,
            'total_teachers' => $totalTeachers,
            'total_users' => $totalUsers,
            'total_revenue' => (float) $totalRevenue,
        ];

        $recentActivity = School::query()
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn ($s) => [
                'type' => 'school',
                'text' => "School \"{$s->name}\" was ".($s->is_active ? 'activated' : 'created'),
                'time' => $s->updated_at,
                'icon' => 'building',
                'color' => $s->is_active ? '#1e8a3e' : '#6c757d',
            ]);

        return view('dashboard', [
            'school' => null,
            'stats' => null,
            'recentActivity' => $recentActivity,
            'upcomingEvents' => collect(),
            'subscription' => null,
            'schoolSetting' => null,
            'isSuperAdmin' => true,
            'platformStats' => $platformStats,
            'recentSchools' => $recentSchools,
        ]);
    }

    private function schoolAdminDashboard(User $user, ?School $school, ?int $schoolId): View
    {
        $reportService = new ReportService($schoolId);
        $stats = $reportService->dashboardSummary();

        $recentActivity = $this->getRecentActivity($schoolId);
        $upcomingEvents = $this->getUpcomingEvents($schoolId);
        $subscription = $school?->activeSubscription;
        $schoolSetting = $school?->setting;

        $today = Carbon::today();
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();

        $pendingAdmissions = Admission::where('school_id', $schoolId)
            ->where('status', 'pending')
            ->count();

        $totalParents = ParentModel::where('school_id', $schoolId)->count();

        $recentAdmissions = Admission::where('school_id', $schoolId)
            ->latest()
            ->limit(5)
            ->get();

        $recentPayments = FeePayment::where('school_id', $schoolId)
            ->with('student', 'feeStructure')
            ->latest('payment_date')
            ->limit(5)
            ->get();

        $activeAnnouncements = Announcement::where('school_id', $schoolId)
            ->active()
            ->latest()
            ->limit(5)
            ->get();

        $unreadMessages = Message::where('school_id', $schoolId)
            ->whereHas('recipients', function ($q) use ($user) {
                $q->where('user_id', $user->id)->whereNull('read_at');
            })
            ->count();

        $studentGrowth = Student::where('school_id', $schoolId)
            ->where('created_at', '>=', $sixMonthsAgo)
            ->orderBy('created_at')
            ->get()
            ->groupBy(fn ($s) => $s->created_at->format('M Y'))
            ->map(fn ($group) => $group->count())
            ->toArray();

        $feeCollection = FeePayment::where('school_id', $schoolId)
            ->where('payment_date', '>=', $sixMonthsAgo)
            ->orderBy('payment_date')
            ->get()
            ->groupBy(fn ($p) => $p->payment_date->format('M Y'))
            ->map(fn ($group) => (float) $group->sum('amount_paid'))
            ->toArray();

        $studentsByClass = SchoolClass::where('school_id', $schoolId)
            ->withCount(['students' => fn ($q) => $q->where('status', 'active')])
            ->orderBy('name')
            ->get()
            ->filter(fn ($c) => $c->students_count > 0)
            ->pluck('students_count', 'name')
            ->toArray();

        $recentExam = Exam::where('school_id', $schoolId)
            ->where('status', true)
            ->latest('end_date')
            ->first();

        $academicSummary = null;
        if ($recentExam) {
            $avgScore = StudentResult::where('school_id', $schoolId)
                ->where('exam_id', $recentExam->id)
                ->avg('score');

            $totalResults = StudentResult::where('school_id', $schoolId)
                ->where('exam_id', $recentExam->id)
                ->count();

            $academicSummary = [
                'exam_name' => $recentExam->name,
                'avg_score' => round((float) $avgScore, 1),
                'total_results' => $totalResults,
            ];
        }

        $schoolAdminStats = [
            'pending_admissions' => $pendingAdmissions,
            'total_parents' => $totalParents,
            'unread_messages' => $unreadMessages,
            'active_announcements' => $activeAnnouncements->count(),
        ];

        $chartData = [
            'student_growth_labels' => array_keys($studentGrowth),
            'student_growth_data' => array_values($studentGrowth),
            'fee_collection_labels' => array_keys($feeCollection),
            'fee_collection_data' => array_values($feeCollection),
            'students_by_class_labels' => array_keys($studentsByClass),
            'students_by_class_data' => array_values($studentsByClass),
        ];

        return view('dashboard', compact(
            'school',
            'stats',
            'recentActivity',
            'upcomingEvents',
            'subscription',
            'schoolSetting',
            'recentAdmissions',
            'recentPayments',
            'activeAnnouncements',
            'academicSummary',
            'chartData',
            'schoolAdminStats',
        ) + ['isSuperAdmin' => false]);
    }

    private function parentDashboard($user, $school): View
    {
        $parent = $user->parent;

        $children = $parent
            ? $parent->children()->where('students.status', 'active')->with(['schoolClass', 'section'])->get()
            : collect();

        $childIds = $children->pluck('id');
        $childClassIds = $children->pluck('school_class_id')->unique();

        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        $attendanceStats = Attendance::whereIn('student_id', $childIds)
            ->where('attendance_date', $today)
            ->selectRaw('count(*) as total, sum(case when status in ("present","late","excused") then 1 else 0 end) as present')
            ->first();

        $todayAttendance = (int) ($attendanceStats->total ?? 0);
        $todayPresent = (int) ($attendanceStats->present ?? 0);
        $attendanceRate = $todayAttendance > 0
            ? round(($todayPresent / $todayAttendance) * 100, 1)
            : 0;

        $totalFees = FeeStructure::whereIn('school_class_id', $childClassIds)
            ->where('school_id', $user->school_id)
            ->where('status', true)
            ->sum('amount');

        $totalPaid = FeePayment::whereIn('student_id', $childIds)
            ->where('school_id', $user->school_id)
            ->sum('amount_paid');

        $outstanding = max(0, (float) $totalFees - (float) $totalPaid);

        $recentPayments = FeePayment::whereIn('student_id', $childIds)
            ->where('school_id', $user->school_id)
            ->with('student', 'feeStructure')
            ->latest('payment_date')
            ->limit(5)
            ->get();

        $upcomingEvents = $this->getUpcomingEvents($user->school_id);

        $recentActivity = $this->getParentRecentActivity($childIds, $user->school_id);

        $subscription = $school?->activeSubscription;
        $schoolSetting = $school?->setting;

        $parentStats = [
            'total_children' => $children->count(),
            'today_attendance_rate' => $attendanceRate,
            'total_fees' => (float) $totalFees,
            'total_paid' => (float) $totalPaid,
            'outstanding' => $outstanding,
        ];

        return view('dashboard', [
            'school' => $school,
            'stats' => (new ReportService($user->school_id))->dashboardSummary(),
            'recentActivity' => $recentActivity,
            'upcomingEvents' => $upcomingEvents,
            'subscription' => $subscription,
            'schoolSetting' => $schoolSetting,
            'isSuperAdmin' => false,
            'isParent' => true,
            'children' => $children,
            'parentStats' => $parentStats,
            'recentPayments' => $recentPayments,
        ]);
    }

    private function getParentRecentActivity($childIds, ?int $schoolId): Collection
    {
        $since = Carbon::now()->subDays(7);

        $payments = FeePayment::whereIn('student_id', $childIds)
            ->where('school_id', $schoolId)
            ->where('created_at', '>=', $since)
            ->with('student')
            ->latest('created_at')
            ->limit(3)
            ->get()
            ->map(fn ($p) => [
                'type' => 'payment',
                'text' => 'Fee payment of ₦'.number_format($p->amount_paid)." for {$p->student->full_name}",
                'time' => $p->created_at,
                'icon' => 'dollar-sign',
                'color' => '#e67e22',
            ]);

        $announcements = Announcement::where('school_id', $schoolId)
            ->where('status', 'published')
            ->where(function ($q) {
                $q->where('audience', 'everyone')->orWhere('audience', 'parents');
            })
            ->where('created_at', '>=', $since)
            ->latest('created_at')
            ->limit(3)
            ->get()
            ->map(fn ($a) => [
                'type' => 'announcement',
                'text' => "Announcement: {$a->title}",
                'time' => $a->created_at,
                'icon' => 'megaphone',
                'color' => '#6f42c1',
            ]);

        return $payments->concat($announcements)
            ->sortByDesc('time')
            ->take(6)
            ->values();
    }

    private function getRecentActivity(?int $schoolId): Collection
    {
        $now = Carbon::now();
        $since = $now->copy()->subDays(7);

        $students = Student::query()
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->where('created_at', '>=', $since)
            ->latest('created_at')
            ->limit(3)
            ->get()
            ->map(fn ($s) => [
                'type' => 'student',
                'text' => "New student {$s->full_name} registered",
                'time' => $s->created_at,
                'icon' => 'user-plus',
                'color' => '#1a73e8',
            ]);

        $teachers = Teacher::query()
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->where('created_at', '>=', $since)
            ->latest('created_at')
            ->limit(3)
            ->get()
            ->map(fn ($t) => [
                'type' => 'teacher',
                'text' => "Teacher {$t->first_name} {$t->last_name} added",
                'time' => $t->created_at,
                'icon' => 'book-open',
                'color' => '#1e8a3e',
            ]);

        $payments = FeePayment::query()
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->where('created_at', '>=', $since)
            ->latest('created_at')
            ->limit(3)
            ->get()
            ->map(fn ($p) => [
                'type' => 'payment',
                'text' => 'Fee payment of ₦'.number_format($p->amount_paid).' received',
                'time' => $p->created_at,
                'icon' => 'dollar-sign',
                'color' => '#e67e22',
            ]);

        $announcements = Announcement::query()
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->where('created_at', '>=', $since)
            ->latest('created_at')
            ->limit(2)
            ->get()
            ->map(fn ($a) => [
                'type' => 'announcement',
                'text' => "Announcement: {$a->title}",
                'time' => $a->created_at,
                'icon' => 'megaphone',
                'color' => '#6f42c1',
            ]);

        return $students->concat($teachers)
            ->concat($payments)
            ->concat($announcements)
            ->sortByDesc('time')
            ->take(8)
            ->values();
    }

    private function getUpcomingEvents(?int $schoolId): Collection
    {
        return Event::query()
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->where('event_date', '>=', Carbon::today())
            ->orderBy('event_date')
            ->limit(5)
            ->get();
    }

    private function studentDashboard(): RedirectResponse
    {
        return redirect()->route('student.dashboard');
    }
}
