<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ResolvesTeacher;
use App\Models\AssessmentType;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TeacherScoreEntryController extends Controller
{
    use ResolvesTeacher;

    public function create(Request $request): View
    {
        $teacher = $this->resolveTeacher();

        $classIds = $teacher->schoolClasses->pluck('id')->toArray();
        $subjectIds = $teacher->subjects->pluck('id')->toArray();

        $exams = Exam::where('school_id', $teacher->school_id)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $classes = SchoolClass::whereIn('id', $classIds)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $subjects = collect();
        $assessmentTypes = collect();
        $students = collect();
        $existingScores = [];
        $existingRemarks = [];

        $selectedExam = $request->exam_id;
        $selectedClass = $request->school_class_id;
        $selectedSubject = $request->subject_id;
        $selectedAssessmentType = $request->assessment_type_id;

        if ($selectedClass) {
            $subjects = Subject::whereIn('id', $subjectIds)
                ->whereHas('schoolClasses', function ($query) use ($selectedClass) {
                    $query->where('school_classes.id', $selectedClass);
                })
                ->where('status', true)
                ->orderBy('name')
                ->get();

            $students = Student::where('school_class_id', $selectedClass)
                ->where('status', 'active')
                ->orderBy('first_name')
                ->get();
        }

        if ($selectedExam) {
            $assessmentTypes = AssessmentType::where('school_id', $teacher->school_id)
                ->where('status', true)
                ->orderBy('name')
                ->get();
        }

        if ($selectedExam && $selectedClass && $selectedSubject && $selectedAssessmentType) {
            $existingScores = StudentResult::where('exam_id', $selectedExam)
                ->where('school_class_id', $selectedClass)
                ->where('subject_id', $selectedSubject)
                ->where('assessment_type_id', $selectedAssessmentType)
                ->pluck('score', 'student_id')
                ->toArray();

            $existingRemarks = StudentResult::where('exam_id', $selectedExam)
                ->where('school_class_id', $selectedClass)
                ->where('subject_id', $selectedSubject)
                ->where('assessment_type_id', $selectedAssessmentType)
                ->pluck('remarks', 'student_id')
                ->toArray();
        }

        return view('teacher.scores.create', compact(
            'teacher',
            'exams',
            'classes',
            'subjects',
            'assessmentTypes',
            'students',
            'selectedExam',
            'selectedClass',
            'selectedSubject',
            'selectedAssessmentType',
            'existingScores',
            'existingRemarks',
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $teacher = $this->resolveTeacher();

        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'assessment_type_id' => 'required|exists:assessment_types,id',
            'scores' => 'required|array',
            'scores.*.student_id' => 'required|exists:students,id',
            'scores.*.score' => 'required|numeric|min:0|max:100',
            'scores.*.remarks' => 'nullable|string|max:500',
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);
        $schoolClass = SchoolClass::findOrFail($validated['school_class_id']);
        $subject = Subject::findOrFail($validated['subject_id']);
        $assessmentType = AssessmentType::findOrFail($validated['assessment_type_id']);

        abort_if($exam->school_id !== $teacher->school_id, 403, 'Unauthorized access.');
        abort_if($schoolClass->school_id !== $teacher->school_id, 403, 'Unauthorized access.');

        if (! $teacher->schoolClasses->contains($schoolClass->id)) {
            return back()->withErrors(['school_class_id' => 'You are not assigned to this class.'])->withInput();
        }

        if (! $teacher->subjects->contains($subject->id)) {
            return back()->withErrors(['subject_id' => 'You are not assigned to this subject.'])->withInput();
        }

        $studentIds = array_column($validated['scores'], 'student_id');
        $validStudents = Student::where('school_class_id', $schoolClass->id)
            ->whereIn('id', $studentIds)
            ->pluck('id')
            ->toArray();

        $invalidStudents = array_diff($studentIds, $validStudents);
        if (! empty($invalidStudents)) {
            return back()->withErrors(['scores' => 'Some selected students do not belong to the chosen class.'])->withInput();
        }

        DB::transaction(function () use ($validated, $schoolClass, $teacher) {
            foreach ($validated['scores'] as $scoreData) {
                StudentResult::updateOrCreate(
                    [
                        'student_id' => $scoreData['student_id'],
                        'exam_id' => $validated['exam_id'],
                        'subject_id' => $validated['subject_id'],
                        'assessment_type_id' => $validated['assessment_type_id'],
                    ],
                    [
                        'school_id' => $schoolClass->school_id,
                        'school_class_id' => $validated['school_class_id'],
                        'teacher_id' => $teacher->id,
                        'score' => $scoreData['score'],
                        'remarks' => $scoreData['remarks'] ?? null,
                    ]
                );
            }
        });

        return redirect()
            ->route('teacher.scores.history')
            ->with('success', 'Scores saved successfully.');
    }

    public function history(Request $request): View
    {
        $teacher = $this->resolveTeacher();

        $scores = StudentResult::with('student', 'exam', 'subject', 'schoolClass', 'assessmentType')
            ->where('teacher_id', $teacher->id)
            ->where('school_id', $teacher->school_id)
            ->when($request->exam_id, fn ($q, $v) => $q->where('exam_id', $v))
            ->when($request->school_class_id, fn ($q, $v) => $q->where('school_class_id', $v))
            ->when($request->subject_id, fn ($q, $v) => $q->where('subject_id', $v))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $exams = Exam::where('school_id', $teacher->school_id)->orderBy('name')->get();
        $classes = SchoolClass::whereIn('id', $teacher->schoolClasses->pluck('id'))->orderBy('name')->get();
        $subjects = Subject::whereIn('id', $teacher->subjects->pluck('id'))->orderBy('name')->get();

        return view('teacher.scores.history', compact(
            'teacher',
            'scores',
            'exams',
            'classes',
            'subjects',
        ));
    }
}
