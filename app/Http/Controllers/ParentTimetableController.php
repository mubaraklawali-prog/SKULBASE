<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\School;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParentTimetableController extends Controller
{
    protected function resolveChildren()
    {
        $user = auth()->user();

        $parent = $user->parent;

        if (! $parent) {
            abort(404, 'Parent profile not found for this account.');
        }

        return $parent->children()->where('status', 'active')->get();
    }

    public function index(Request $request): View
    {
        $children = $this->resolveChildren();
        $selectedStudentId = $request->student_id;

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $grid = collect();
        $selectedStudent = null;
        $schoolClass = null;
        $section = null;
        $periods = collect();

        if ($children->count() === 1 && ! $selectedStudentId) {
            $selectedStudent = $children->first();
            $selectedStudentId = $selectedStudent->id;
        } elseif ($selectedStudentId) {
            $selectedStudent = $children->firstWhere('id', $selectedStudentId);
        }

        if ($selectedStudent) {
            $periods = Period::where('school_id', $selectedStudent->school_id)
                ->where('status', true)
                ->orderBy('sort_order')
                ->get();

            $entries = Timetable::where('school_id', $selectedStudent->school_id)
                ->where('class_id', $selectedStudent->school_class_id)
                ->where('section_id', $selectedStudent->section_id)
                ->with(['subject', 'teacher', 'period'])
                ->get();

            $grid = $entries->mapWithKeys(function ($entry) {
                return [$entry->period_id.'_'.$entry->day => $entry];
            });

            $schoolClass = $selectedStudent->schoolClass;
            $section = $selectedStudent->section;
        }

        return view('parent.timetable.index', compact(
            'children',
            'selectedStudentId',
            'selectedStudent',
            'days',
            'periods',
            'grid',
            'schoolClass',
            'section'
        ));
    }

    public function print(Request $request): View
    {
        $children = $this->resolveChildren();
        $selectedStudentId = $request->student_id;

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $grid = collect();
        $selectedStudent = null;
        $schoolClass = null;
        $section = null;
        $periods = collect();
        $school = null;

        if ($children->count() === 1 && ! $selectedStudentId) {
            $selectedStudent = $children->first();
        } elseif ($selectedStudentId) {
            $selectedStudent = $children->firstWhere('id', $selectedStudentId);
        }

        if ($selectedStudent) {
            $school = School::find($selectedStudent->school_id);

            $periods = Period::where('school_id', $selectedStudent->school_id)
                ->where('status', true)
                ->orderBy('sort_order')
                ->get();

            $entries = Timetable::where('school_id', $selectedStudent->school_id)
                ->where('class_id', $selectedStudent->school_class_id)
                ->where('section_id', $selectedStudent->section_id)
                ->with(['subject', 'teacher', 'period'])
                ->get();

            $grid = $entries->mapWithKeys(function ($entry) {
                return [$entry->period_id.'_'.$entry->day => $entry];
            });

            $schoolClass = $selectedStudent->schoolClass;
            $section = $selectedStudent->section;
        }

        return view('parent.timetable.print', compact(
            'selectedStudent',
            'school',
            'days',
            'periods',
            'grid',
            'schoolClass',
            'section'
        ));
    }
}
