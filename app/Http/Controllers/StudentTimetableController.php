<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\School;
use App\Models\Student;
use App\Models\Timetable;
use Illuminate\View\View;

class StudentTimetableController extends Controller
{
    protected function resolveStudent(): Student
    {
        $user = auth()->user();

        $student = Student::where('school_id', $user->school_id)
            ->where('email', $user->email)
            ->first();

        if (! $student) {
            abort(404, 'Student profile not found for this account.');
        }

        return $student;
    }

    public function index(): View
    {
        $student = $this->resolveStudent();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $periods = Period::where('school_id', $student->school_id)
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();

        $entries = Timetable::where('school_id', $student->school_id)
            ->where('class_id', $student->school_class_id)
            ->where('section_id', $student->section_id)
            ->with(['subject', 'teacher', 'period'])
            ->get();

        $grid = $entries->mapWithKeys(function ($entry) {
            return [$entry->period_id.'_'.$entry->day => $entry];
        });

        $schoolClass = $student->schoolClass;
        $section = $student->section;

        return view('student.timetable.index', compact(
            'student',
            'days',
            'periods',
            'grid',
            'schoolClass',
            'section'
        ));
    }

    public function print(): View
    {
        $student = $this->resolveStudent();
        $school = School::find($student->school_id);
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $periods = Period::where('school_id', $student->school_id)
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();

        $entries = Timetable::where('school_id', $student->school_id)
            ->where('class_id', $student->school_class_id)
            ->where('section_id', $student->section_id)
            ->with(['subject', 'teacher', 'period'])
            ->get();

        $grid = $entries->mapWithKeys(function ($entry) {
            return [$entry->period_id.'_'.$entry->day => $entry];
        });

        $schoolClass = $student->schoolClass;
        $section = $student->section;

        return view('student.timetable.print', compact(
            'student',
            'school',
            'days',
            'periods',
            'grid',
            'schoolClass',
            'section'
        ));
    }
}
