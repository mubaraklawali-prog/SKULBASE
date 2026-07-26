<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ResolvesParentChildren;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ParentAttendanceController extends Controller
{
    use ResolvesParentChildren;

    public function index(Request $request): View
    {
        $children = $this->resolveParentChildren();
        $selectedStudentId = $request->student_id;
        $selectedStudent = $this->resolveSelectedChild($children, $selectedStudentId);
        $month = $request->month ?? Carbon::now()->format('Y-m');

        $attendances = collect();
        $stats = [
            'total_days' => 0,
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'excused' => 0,
            'attendance_rate' => 0,
        ];

        if ($selectedStudent) {
            $startDate = Carbon::parse($month)->startOfMonth();
            $endDate = Carbon::parse($month)->endOfMonth();

            $attendances = Attendance::where('student_id', $selectedStudent->id)
                ->where('school_id', $selectedStudent->school_id)
                ->whereBetween('attendance_date', [$startDate, $endDate])
                ->with('schoolClass')
                ->orderBy('attendance_date', 'desc')
                ->get();

            $stats['total_days'] = $attendances->count();
            $stats['present'] = $attendances->where('status', 'present')->count();
            $stats['absent'] = $attendances->where('status', 'absent')->count();
            $stats['late'] = $attendances->where('status', 'late')->count();
            $stats['excused'] = $attendances->where('status', 'excused')->count();
            $stats['attendance_rate'] = $stats['total_days'] > 0
                ? round((($stats['present'] + $stats['late'] + $stats['excused']) / $stats['total_days']) * 100, 1)
                : 0;
        }

        return view('parent.attendance.index', compact(
            'children',
            'selectedStudentId',
            'selectedStudent',
            'attendances',
            'stats',
            'month',
        ));
    }
}
