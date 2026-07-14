<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\School;
use App\Models\Teacher;
use App\Models\Timetable;
use Illuminate\View\View;

class TeacherTimetableController extends Controller
{
    protected function resolveTeacher(): Teacher
    {
        $user = auth()->user();

        $teacher = Teacher::where('school_id', $user->school_id)
            ->where('email', $user->email)
            ->first();

        if (! $teacher) {
            abort(404, 'Teacher profile not found for this account.');
        }

        return $teacher;
    }

    public function index(): View
    {
        $teacher = $this->resolveTeacher();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $periods = Period::where('school_id', $teacher->school_id)
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();

        $entries = Timetable::where('teacher_id', $teacher->id)
            ->where('school_id', $teacher->school_id)
            ->with(['schoolClass', 'section', 'subject', 'period'])
            ->get();

        $grid = $entries->mapWithKeys(function ($entry) {
            return [$entry->period_id.'_'.$entry->day => $entry];
        });

        return view('teacher.timetable.index', compact(
            'teacher',
            'days',
            'periods',
            'grid'
        ));
    }

    public function print(): View
    {
        $teacher = $this->resolveTeacher();
        $school = School::find($teacher->school_id);
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $periods = Period::where('school_id', $teacher->school_id)
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();

        $entries = Timetable::where('teacher_id', $teacher->id)
            ->where('school_id', $teacher->school_id)
            ->with(['schoolClass', 'section', 'subject', 'period'])
            ->get();

        $grid = $entries->mapWithKeys(function ($entry) {
            return [$entry->period_id.'_'.$entry->day => $entry];
        });

        return view('teacher.timetable.print', compact(
            'teacher',
            'school',
            'days',
            'periods',
            'grid'
        ));
    }
}
