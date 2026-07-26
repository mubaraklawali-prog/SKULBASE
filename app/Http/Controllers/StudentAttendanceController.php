<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ResolvesStudent;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class StudentAttendanceController extends Controller
{
    use ResolvesStudent;

    public function index(Request $request): View
    {
        $student = $this->resolveStudent();
        $month = $request->month ?? Carbon::now()->format('Y-m');

        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $attendances = Attendance::where('student_id', $student->id)
            ->where('school_id', $student->school_id)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->with('schoolClass')
            ->orderBy('attendance_date', 'desc')
            ->get();

        $stats = [
            'total_days' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'excused' => $attendances->where('status', 'excused')->count(),
            'attendance_rate' => 0,
        ];

        if ($stats['total_days'] > 0) {
            $stats['attendance_rate'] = round(
                (($stats['present'] + $stats['late'] + $stats['excused']) / $stats['total_days']) * 100,
                1,
            );
        }

        return view('student.attendance.index', compact(
            'student',
            'attendances',
            'stats',
            'month',
        ));
    }
}
