<?php

namespace App\Http\Controllers;

use App\Models\AssessmentType;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ScoreEntryController extends Controller
{
    private function schoolId(): ?int
    {
        $user = auth()->user();

        return $user->role === 'super_admin' ? null : $user->school_id;
    }

    public function dashboard(): View
    {
        $schoolId = $this->schoolId();

        $totalEntries = StudentResult::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->count();
        $examsWithScores = StudentResult::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->distinct('exam_id')->count('exam_id');
        $subjectsWithScores = StudentResult::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->distinct('subject_id')->count('subject_id');

        $totalStudents = Student::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->where('status', 'active')->count();
        $studentsWithScores = StudentResult::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->distinct('student_id')->count('student_id');
        $pendingEntries = $totalStudents - $studentsWithScores;

        $recentEntries = StudentResult::with('student', 'exam', 'subject', 'schoolClass', 'assessmentType')
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->latest()
            ->take(10)
            ->get();

        $topScorers = StudentResult::with('student')
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->select('student_id', DB::raw('AVG(score) as avg_score'))
            ->groupBy('student_id')
            ->orderByDesc('avg_score')
            ->take(5)
            ->get();

        return view('results.scores.dashboard', compact(
            'totalEntries',
            'examsWithScores',
            'subjectsWithScores',
            'pendingEntries',
            'recentEntries',
            'topScorers',
        ));
    }

    public function create(Request $request): View
    {
        $schoolId = $this->schoolId();

        $exams = Exam::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->where('status', true)->orderBy('name')->get();
        $classes = SchoolClass::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->where('status', true)->orderBy('name')->get();
        $subjects = collect();
        $assessmentTypes = collect();
        $students = collect();
        $existingScores = [];

        $selectedExam = $request->exam_id;
        $selectedClass = $request->school_class_id;
        $selectedSubject = $request->subject_id;
        $selectedAssessmentType = $request->assessment_type_id;

        if ($selectedClass) {
            $subjects = Subject::whereHas('schoolClasses', function ($query) use ($selectedClass) {
                $query->where('school_classes.id', $selectedClass);
            })->where('status', true)->orderBy('name')->get();

            $students = Student::where('school_class_id', $selectedClass)
                ->where('status', 'active')
                ->orderBy('first_name')
                ->get();
        }

        if ($selectedExam) {
            $assessmentTypes = AssessmentType::where('school_id', Exam::find($selectedExam)?->school_id)
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
        } else {
            $existingScores = [];
            $existingRemarks = [];
        }

        return view('results.scores.create', compact(
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
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'assessment_type_id' => 'required|exists:assessment_types,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'scores' => 'required|array',
            'scores.*.student_id' => 'required|exists:students,id',
            'scores.*.score' => 'required|numeric|min:0|max:100',
            'scores.*.remarks' => 'nullable|string|max:500',
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);
        $schoolClass = SchoolClass::findOrFail($validated['school_class_id']);
        $subject = Subject::findOrFail($validated['subject_id']);
        $assessmentType = AssessmentType::findOrFail($validated['assessment_type_id']);

        if ($subject->school_id !== $schoolClass->school_id) {
            return back()->withErrors(['subject_id' => 'Subject does not belong to the selected class school.'])->withInput();
        }

        if ($exam->school_id !== $schoolClass->school_id) {
            return back()->withErrors(['exam_id' => 'Exam does not belong to the same school as the class.'])->withInput();
        }

        if ($assessmentType->school_id !== $schoolClass->school_id) {
            return back()->withErrors(['assessment_type_id' => 'Assessment type does not belong to the same school.'])->withInput();
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

        DB::transaction(function () use ($validated, $schoolClass) {
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
                        'teacher_id' => $validated['teacher_id'] ?? null,
                        'score' => $scoreData['score'],
                        'remarks' => $scoreData['remarks'] ?? null,
                    ]
                );
            }
        });

        return redirect()
            ->route('results.scores.history')
            ->with('success', 'Scores saved successfully.');
    }

    public function edit(Request $request): View
    {
        $schoolId = $this->schoolId();

        $exams = Exam::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->where('status', true)->orderBy('name')->get();
        $classes = SchoolClass::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->where('status', true)->orderBy('name')->get();
        $subjects = collect();
        $assessmentTypes = collect();
        $students = collect();
        $existingScores = [];

        $selectedExam = $request->exam_id;
        $selectedClass = $request->school_class_id;
        $selectedSubject = $request->subject_id;
        $selectedAssessmentType = $request->assessment_type_id;

        if ($selectedClass) {
            $subjects = Subject::whereHas('schoolClasses', function ($query) use ($selectedClass) {
                $query->where('school_classes.id', $selectedClass);
            })->where('status', true)->orderBy('name')->get();

            $students = Student::where('school_class_id', $selectedClass)
                ->where('status', 'active')
                ->orderBy('first_name')
                ->get();
        }

        if ($selectedExam) {
            $assessmentTypes = AssessmentType::where('school_id', Exam::find($selectedExam)?->school_id)
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
        } else {
            $existingScores = [];
            $existingRemarks = [];
        }

        return view('results.scores.edit', compact(
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

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'assessment_type_id' => 'required|exists:assessment_types,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'scores' => 'required|array',
            'scores.*.student_id' => 'required|exists:students,id',
            'scores.*.score' => 'required|numeric|min:0|max:100',
            'scores.*.remarks' => 'nullable|string|max:500',
        ]);

        $schoolClass = SchoolClass::findOrFail($validated['school_class_id']);

        $studentIds = array_column($validated['scores'], 'student_id');
        $validStudents = Student::where('school_class_id', $schoolClass->id)
            ->whereIn('id', $studentIds)
            ->pluck('id')
            ->toArray();

        $invalidStudents = array_diff($studentIds, $validStudents);
        if (! empty($invalidStudents)) {
            return back()->withErrors(['scores' => 'Some selected students do not belong to the chosen class.'])->withInput();
        }

        DB::transaction(function () use ($validated, $schoolClass) {
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
                        'teacher_id' => $validated['teacher_id'] ?? null,
                        'score' => $scoreData['score'],
                        'remarks' => $scoreData['remarks'] ?? null,
                    ]
                );
            }
        });

        return redirect()
            ->route('results.scores.history')
            ->with('success', 'Scores updated successfully.');
    }

    public function history(Request $request): View
    {
        $schoolId = $this->schoolId();

        $scores = StudentResult::query()
            ->with('student', 'exam', 'subject', 'schoolClass', 'assessmentType', 'teacher')
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->when($request->exam_id, function ($query, $examId) {
                $query->where('exam_id', $examId);
            })
            ->when($request->school_class_id, function ($query, $classId) {
                $query->where('school_class_id', $classId);
            })
            ->when($request->subject_id, function ($query, $subjectId) {
                $query->where('subject_id', $subjectId);
            })
            ->when($request->assessment_type_id, function ($query, $assessmentTypeId) {
                $query->where('assessment_type_id', $assessmentTypeId);
            })
            ->when($request->student_id, function ($query, $studentId) {
                $query->where('student_id', $studentId);
            })
            ->when($request->teacher_id, function ($query, $teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->when($request->date_from, function ($query, $date) {
                $query->where('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->where('created_at', '<=', $date.' 23:59:59');
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $exams = Exam::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $classes = SchoolClass::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $subjects = Subject::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $assessmentTypes = AssessmentType::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $students = Student::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('first_name')->get();
        $teachers = Teacher::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('first_name')->get();

        return view('results.scores.history', compact(
            'scores',
            'exams',
            'classes',
            'subjects',
            'assessmentTypes',
            'students',
            'teachers',
        ));
    }

    public function show(StudentResult $score): View
    {
        $schoolId = $this->schoolId();

        if ($schoolId && $score->school_id !== $schoolId) {
            abort(403, 'Unauthorized access.');
        }

        $score->load('student', 'exam', 'subject', 'schoolClass', 'assessmentType', 'teacher', 'school');

        return view('results.scores.show', compact('score'));
    }

    public function destroy(StudentResult $score): RedirectResponse
    {
        $schoolId = $this->schoolId();

        if ($schoolId && $score->school_id !== $schoolId) {
            abort(403, 'Unauthorized access.');
        }

        $score->delete();

        return redirect()
            ->route('results.scores.history')
            ->with('success', 'Score entry deleted successfully.');
    }

    public function studentReport(Student $student): View
    {
        $schoolId = $this->schoolId();

        if ($schoolId && $student->school_id !== $schoolId) {
            abort(403, 'Unauthorized access.');
        }

        $student->load('school', 'schoolClass');

        $scores = StudentResult::with('exam', 'subject', 'assessmentType', 'teacher')
            ->where('student_id', $student->id)
            ->orderBy('exam_id')
            ->orderBy('subject_id')
            ->orderBy('assessment_type_id')
            ->get();

        $groupedScores = $scores->groupBy('exam.id');

        $exams = Exam::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $classes = SchoolClass::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();

        return view('results.scores.student-report', compact(
            'student',
            'scores',
            'groupedScores',
            'exams',
            'classes',
        ));
    }

    public function subjectReport(Request $request): View
    {
        $schoolId = $this->schoolId();

        $subjects = Subject::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $exams = Exam::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $classes = SchoolClass::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();

        $selectedSubject = $request->subject_id;
        $selectedExam = $request->exam_id;
        $selectedClass = $request->school_class_id;

        $report = null;

        if ($selectedSubject && $selectedExam) {
            $query = StudentResult::with('student', 'assessmentType', 'teacher')
                ->where('subject_id', $selectedSubject)
                ->where('exam_id', $selectedExam);

            if ($selectedClass) {
                $query->where('school_class_id', $selectedClass);
            }

            $scores = $query->orderBy('student_id')
                ->orderBy('assessment_type_id')
                ->get();

            $subject = Subject::find($selectedSubject);
            $exam = Exam::find($selectedExam);
            $class = $selectedClass ? SchoolClass::find($selectedClass) : null;

            $studentAverages = $scores->groupBy('student_id')->map(function ($studentScores) {
                $avg = $studentScores->avg('score');

                return [
                    'student' => $studentScores->first()->student,
                    'avg_score' => round($avg, 2),
                    'scores' => $studentScores,
                ];
            })->sortByDesc('avg_score')->values();

            $report = compact('subject', 'exam', 'class', 'scores', 'studentAverages');
        }

        return view('results.scores.subject-report', compact(
            'subjects',
            'exams',
            'classes',
            'selectedSubject',
            'selectedExam',
            'selectedClass',
            'report',
        ));
    }

    public function classReport(Request $request): View
    {
        $schoolId = $this->schoolId();

        $classes = SchoolClass::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $exams = Exam::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $subjects = Subject::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();

        $selectedClass = $request->school_class_id;
        $selectedExam = $request->exam_id;
        $selectedSubject = $request->subject_id;

        $report = null;

        if ($selectedClass && $selectedExam) {
            $query = StudentResult::with('student', 'subject', 'assessmentType', 'teacher')
                ->where('school_class_id', $selectedClass)
                ->where('exam_id', $selectedExam);

            if ($selectedSubject) {
                $query->where('subject_id', $selectedSubject);
            }

            $scores = $query->orderBy('student_id')
                ->orderBy('subject_id')
                ->orderBy('assessment_type_id')
                ->get();

            $class = SchoolClass::find($selectedClass);
            $exam = Exam::find($selectedExam);
            $subject = $selectedSubject ? Subject::find($selectedSubject) : null;

            $studentAverages = $scores->groupBy('student_id')->map(function ($studentScores) {
                $avg = $studentScores->avg('score');

                return [
                    'student' => $studentScores->first()->student,
                    'avg_score' => round($avg, 2),
                    'total_subjects' => $studentScores->pluck('subject_id')->unique()->count(),
                    'scores' => $studentScores,
                ];
            })->sortByDesc('avg_score')->values();

            $report = compact('class', 'exam', 'subject', 'scores', 'studentAverages');
        }

        return view('results.scores.class-report', compact(
            'classes',
            'exams',
            'subjects',
            'selectedClass',
            'selectedExam',
            'selectedSubject',
            'report',
        ));
    }

    public function examReport(Request $request): View
    {
        $schoolId = $this->schoolId();

        $exams = Exam::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $classes = SchoolClass::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $subjects = Subject::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();

        $selectedExam = $request->exam_id;
        $selectedClass = $request->school_class_id;
        $selectedSubject = $request->subject_id;

        $report = null;

        if ($selectedExam) {
            $query = StudentResult::with('student', 'subject', 'assessmentType', 'teacher', 'schoolClass')
                ->where('exam_id', $selectedExam);

            if ($selectedClass) {
                $query->where('school_class_id', $selectedClass);
            }

            if ($selectedSubject) {
                $query->where('subject_id', $selectedSubject);
            }

            $scores = $query->orderBy('school_class_id')
                ->orderBy('subject_id')
                ->orderBy('student_id')
                ->get();

            $exam = Exam::find($selectedExam);
            $class = $selectedClass ? SchoolClass::find($selectedClass) : null;
            $subject = $selectedSubject ? Subject::find($selectedSubject) : null;

            $classSummary = $scores->groupBy('school_class_id')->map(function ($classScores) {
                return [
                    'class' => $classScores->first()->schoolClass,
                    'total_entries' => $classScores->count(),
                    'avg_score' => round($classScores->avg('score'), 2),
                    'highest' => $classScores->max('score'),
                    'lowest' => $classScores->min('score'),
                ];
            });

            $subjectSummary = $scores->groupBy('subject_id')->map(function ($subjectScores) {
                return [
                    'subject' => $subjectScores->first()->subject,
                    'total_entries' => $subjectScores->count(),
                    'avg_score' => round($subjectScores->avg('score'), 2),
                    'highest' => $subjectScores->max('score'),
                    'lowest' => $subjectScores->min('score'),
                ];
            });

            $report = compact('exam', 'class', 'subject', 'scores', 'classSummary', 'subjectSummary');
        }

        return view('results.scores.exam-report', compact(
            'exams',
            'classes',
            'subjects',
            'selectedExam',
            'selectedClass',
            'selectedSubject',
            'report',
        ));
    }
}
