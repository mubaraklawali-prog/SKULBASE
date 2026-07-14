<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimetableRequest;
use App\Http\Requests\UpdateTimetableRequest;
use App\Models\Period;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Timetable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TimetableController extends Controller
{
    public function index(Request $request): View
    {
        $timetables = Timetable::query()
            ->with('schoolClass', 'section', 'subject', 'teacher', 'period')
            ->when($request->class_id, function ($query, $classId) {
                $query->where('class_id', $classId);
            })
            ->when($request->section_id, function ($query, $sectionId) {
                $query->where('section_id', $sectionId);
            })
            ->when($request->teacher_id, function ($query, $teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->when($request->day, function ($query, $day) {
                $query->where('day', $day);
            })
            ->when($request->subject_id, function ($query, $subjectId) {
                $query->where('subject_id', $subjectId);
            })
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('schoolClass', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    })
                        ->orWhereHas('subject', function ($sq) use ($search) {
                            $sq->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('teacher', function ($sq) use ($search) {
                            $sq->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        })
                        ->orWhere('notes', 'like', "%{$search}%");
                });
            })
            ->orderBy('day')
            ->orderBy('class_id')
            ->orderBy('section_id')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $classes = SchoolClass::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $teachers = Teacher::orderBy('first_name')->get();
        $subjects = Subject::orderBy('name')->get();

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return view('timetables.index', compact(
            'timetables',
            'classes',
            'sections',
            'teachers',
            'subjects',
            'days'
        ));
    }

    public function create(): View
    {
        $classes = SchoolClass::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::orderBy('first_name')->get();
        $periods = Period::where('status', true)->orderBy('sort_order')->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return view('timetables.create', compact(
            'classes',
            'sections',
            'subjects',
            'teachers',
            'periods',
            'days'
        ));
    }

    public function store(StoreTimetableRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            Timetable::create($validated);
        });

        return redirect()
            ->route('timetables.index')
            ->with('success', 'Timetable entry created successfully.');
    }

    public function edit(Timetable $timetable): View
    {
        $timetable->load('schoolClass', 'section', 'subject', 'teacher', 'period');

        $classes = SchoolClass::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::orderBy('first_name')->get();
        $periods = Period::where('status', true)->orderBy('sort_order')->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return view('timetables.edit', compact(
            'timetable',
            'classes',
            'sections',
            'subjects',
            'teachers',
            'periods',
            'days'
        ));
    }

    public function update(UpdateTimetableRequest $request, Timetable $timetable): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($timetable, $validated) {
            $timetable->update($validated);
        });

        return redirect()
            ->route('timetables.index')
            ->with('success', 'Timetable entry updated successfully.');
    }

    public function destroy(Timetable $timetable): RedirectResponse
    {
        $timetable->delete();

        return redirect()
            ->route('timetables.index')
            ->with('success', 'Timetable entry deleted successfully.');
    }

    public function grid(Request $request): View
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $selectedClassId = $request->class_id;
        $selectedSectionId = $request->section_id;

        $classes = SchoolClass::where('school_id', auth()->user()->school_id)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $sections = Section::where('school_id', auth()->user()->school_id)
            ->orderBy('name')
            ->get();

        $periods = Period::where('school_id', auth()->user()->school_id)
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();

        $grid = collect();
        $selectedClass = null;
        $selectedSection = null;

        if ($selectedClassId && $selectedSectionId) {
            $selectedClass = SchoolClass::find($selectedClassId);
            $selectedSection = Section::find($selectedSectionId);

            $entries = Timetable::where('school_id', auth()->user()->school_id)
                ->where('class_id', $selectedClassId)
                ->where('section_id', $selectedSectionId)
                ->with(['subject', 'teacher', 'period'])
                ->get();

            $grid = $entries->mapWithKeys(function ($entry) {
                return [$entry->period_id.'_'.$entry->day => $entry];
            });
        }

        return view('timetables.grid', compact(
            'days',
            'periods',
            'grid',
            'classes',
            'sections',
            'selectedClassId',
            'selectedSectionId',
            'selectedClass',
            'selectedSection'
        ));
    }

    public function print(Request $request): View
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $selectedClassId = $request->class_id;
        $selectedSectionId = $request->section_id;

        $school = School::find(auth()->user()->school_id);

        $periods = Period::where('school_id', auth()->user()->school_id)
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();

        $selectedClass = null;
        $selectedSection = null;
        $grid = collect();

        if ($selectedClassId && $selectedSectionId) {
            $selectedClass = SchoolClass::find($selectedClassId);
            $selectedSection = Section::find($selectedSectionId);

            $entries = Timetable::where('school_id', auth()->user()->school_id)
                ->where('class_id', $selectedClassId)
                ->where('section_id', $selectedSectionId)
                ->with(['subject', 'teacher', 'period'])
                ->get();

            $grid = $entries->mapWithKeys(function ($entry) {
                return [$entry->period_id.'_'.$entry->day => $entry];
            });
        }

        return view('timetables.print', compact(
            'days',
            'periods',
            'grid',
            'school',
            'selectedClass',
            'selectedSection'
        ));
    }

    public function getSections(Request $request): JsonResponse
    {
        $sections = Section::where('school_id', $request->school_id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($sections);
    }

    public function getSubjects(Request $request): JsonResponse
    {
        $subjects = Subject::where('school_id', $request->school_id)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        return response()->json($subjects);
    }

    public function getTeachers(Request $request): JsonResponse
    {
        $teachers = Teacher::where('school_id', $request->school_id)
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'other_name']);

        return response()->json($teachers);
    }

    public function getPeriods(Request $request): JsonResponse
    {
        $periods = Period::where('school_id', $request->school_id)
            ->where('status', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'start_time', 'end_time']);

        return response()->json($periods);
    }

    public function getClassesBySchool(Request $request): JsonResponse
    {
        $classes = SchoolClass::where('school_id', $request->school_id)
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($classes);
    }
}
