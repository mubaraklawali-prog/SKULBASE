<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ResolvesParentChildren;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use App\Models\Message;
use App\Models\StudentResult;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ParentDashboardController extends Controller
{
    use ResolvesParentChildren;

    public function __invoke(): View
    {
        $user = Auth::user();
        $parent = $user->parent;
        $school = $user->school;
        $children = $this->resolveParentChildren();

        $childIds = $children->pluck('id')->toArray();

        $children->each(function ($child) {
            $child->loadCount([
                'attendances as total_attendance_days' => fn ($q) => $q->where('attendance_date', '>=', now()->subMonth()),
                'attendances as present_days' => fn ($q) => $q->where('attendance_date', '>=', now()->subMonth())->where('status', 'present'),
            ]);
        });

        $totalChildren = $children->count();
        $totalPresentDays = $children->sum('present_days');
        $totalAttendanceDays = $children->sum('total_attendance_days');
        $overallAttendanceRate = $totalAttendanceDays > 0
            ? round(($totalPresentDays / $totalAttendanceDays) * 100)
            : 0;

        $studentIds = collect($childIds);

        $outstandingFees = 0;
        if ($studentIds->isNotEmpty()) {
            $feeStructures = FeeStructure::where('school_id', $school->id)
                ->where('status', true)
                ->whereIn('school_class_id', $children->pluck('school_class_id')->filter()->values())
                ->get();

            foreach ($feeStructures as $fs) {
                $totalPaid = FeePayment::where('fee_structure_id', $fs->id)
                    ->whereIn('student_id', $studentIds)
                    ->sum('amount_paid');
                $remaining = ($fs->amount * $totalChildren) - $totalPaid;
                if ($remaining > 0) {
                    $outstandingFees += $remaining;
                }
            }
        }

        $recentPayments = collect();
        $recentResults = collect();
        if ($studentIds->isNotEmpty()) {
            $recentPayments = FeePayment::whereIn('student_id', $studentIds)
                ->with(['student', 'feeStructure'])
                ->latest('payment_date')
                ->limit(5)
                ->get();

            $recentResults = StudentResult::whereIn('student_id', $studentIds)
                ->with(['student', 'subject', 'exam'])
                ->latest()
                ->limit(5)
                ->get();
        }

        $unreadMessages = Message::where('school_id', $school->id)
            ->whereHas('recipients', function ($q) use ($user) {
                $q->where('user_id', $user->id)->whereNull('read_at');
            })
            ->count();

        $activeAnnouncements = Announcement::where('school_id', $school->id)
            ->active()
            ->latest()
            ->limit(5)
            ->get();

        $upcomingEvents = Event::where('school_id', $school->id)
            ->published()
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->limit(5)
            ->get();

        $parentStats = [
            'total_children' => $totalChildren,
            'overall_attendance_rate' => $overallAttendanceRate,
            'outstanding_fees' => $outstandingFees,
            'unread_messages' => $unreadMessages,
        ];

        return view('parent.dashboard', compact(
            'parent',
            'school',
            'children',
            'recentPayments',
            'recentResults',
            'activeAnnouncements',
            'upcomingEvents',
            'parentStats',
        ));
    }
}
