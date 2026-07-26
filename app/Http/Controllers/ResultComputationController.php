<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\StudentReportCard;
use App\Models\StudentResult;
use App\Models\Subject;
use App\Services\ResultComputationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResultComputationController extends Controller
{
    public function __construct(
        private ResultComputationService $computationService,
    ) {}

    private function schoolId(): ?int
    {
        $user = auth()->user();

        return $user->role === 'super_admin' ? null : $user->school_id;
    }

    public function dashboard(): View
    {
        $schoolId = $this->schoolId();

        $totalReportCards = StudentReportCard::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->count();
        $publishedResults = StudentReportCard::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->where('status', 'published')->count();
        $draftResults = StudentReportCard::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->where('status', 'draft')->count();
        $approvedResults = StudentReportCard::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->where('status', 'approved')->count();

        $classAverage = StudentReportCard::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->avg('average_score');

        $topPerformers = StudentReportCard::with('student', 'schoolClass')
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->where('status', '!=', 'draft')
            ->orderByDesc('average_score')
            ->take(10)
            ->get();

        $recentComputations = StudentReportCard::with('student', 'exam', 'schoolClass')
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->latest()
            ->take(10)
            ->get();

        return view('results.computations.dashboard', compact(
            'totalReportCards',
            'publishedResults',
            'draftResults',
            'approvedResults',
            'classAverage',
            'topPerformers',
            'recentComputations',
        ));
    }

    public function compute(Request $request): View
    {
        $schoolId = $this->schoolId();

        $exams = Exam::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->where('status', true)->orderBy('name')->get();
        $classes = SchoolClass::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->where('status', true)->orderBy('name')->get();

        $selectedExam = $request->exam_id;
        $selectedClass = $request->school_class_id;

        $computationResult = null;
        $reportCards = collect();
        $subjectScores = [];

        if ($selectedExam && $selectedClass) {
            $reportCards = StudentReportCard::with('student', 'schoolClass')
                ->where('exam_id', $selectedExam)
                ->where('school_class_id', $selectedClass)
                ->orderBy('class_position')
                ->get();

            if ($reportCards->isNotEmpty()) {
                $firstStudent = $reportCards->first()->student;
                $exam = Exam::find($selectedExam);
                $class = SchoolClass::find($selectedClass);
                $subjectScores = $this->computationService->getSubjectScores($exam, $class, $firstStudent->id);
            }
        }

        return view('results.computations.compute', compact(
            'exams',
            'classes',
            'selectedExam',
            'selectedClass',
            'reportCards',
            'computationResult',
            'subjectScores',
        ));
    }

    public function runComputation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'school_class_id' => 'required|exists:school_classes,id',
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);
        $schoolClass = SchoolClass::findOrFail($validated['school_class_id']);

        if ($exam->school_id !== $schoolClass->school_id) {
            return back()->withErrors(['exam_id' => 'Exam does not belong to the same school as the class.'])->withInput();
        }

        $result = $this->computationService->computeForClass($exam, $schoolClass);

        return redirect()
            ->route('results.computations.compute', [
                'exam_id' => $exam->id,
                'school_class_id' => $schoolClass->id,
            ])
            ->with('success', $result['message']);
    }

    public function show(StudentReportCard $reportCard): View
    {
        $schoolId = $this->schoolId();

        if ($schoolId && $reportCard->school_id !== $schoolId) {
            abort(403, 'Unauthorized access.');
        }

        $reportCard->load('student', 'exam', 'schoolClass', 'school');

        $subjectScores = $this->computationService->getSubjectScores(
            $reportCard->exam,
            $reportCard->schoolClass,
            $reportCard->student_id
        );

        return view('results.computations.show', compact('reportCard', 'subjectScores'));
    }

    public function updateComment(Request $request, StudentReportCard $reportCard): RedirectResponse
    {
        $schoolId = $this->schoolId();

        if ($schoolId && $reportCard->school_id !== $schoolId) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'teacher_comment' => 'nullable|string|max:1000',
            'principal_comment' => 'nullable|string|max:1000',
        ]);

        $reportCard->update($validated);

        return redirect()
            ->route('results.computations.show', $reportCard)
            ->with('success', 'Comments updated successfully.');
    }

    public function approve(Exam $exam, SchoolClass $schoolClass): RedirectResponse
    {
        return redirect()->route('results.approvals.dashboard', [
            'exam_id' => $exam->id,
            'school_class_id' => $schoolClass->id,
        ]);
    }

    public function publish(Exam $exam, SchoolClass $schoolClass): RedirectResponse
    {
        return redirect()->route('results.approvals.dashboard', [
            'exam_id' => $exam->id,
            'school_class_id' => $schoolClass->id,
        ]);
    }

    public function classRanking(Request $request): View
    {
        $schoolId = $this->schoolId();

        $exams = Exam::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $classes = SchoolClass::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();

        $selectedExam = $request->exam_id;
        $selectedClass = $request->school_class_id;

        $rankings = collect();

        if ($selectedExam && $selectedClass) {
            $rankings = StudentReportCard::with('student', 'schoolClass')
                ->where('exam_id', $selectedExam)
                ->where('school_class_id', $selectedClass)
                ->orderBy('class_position')
                ->get();
        }

        $exam = $selectedExam ? Exam::find($selectedExam) : null;
        $class = $selectedClass ? SchoolClass::find($selectedClass) : null;

        return view('results.computations.class-ranking', compact(
            'exams',
            'classes',
            'selectedExam',
            'selectedClass',
            'rankings',
            'exam',
            'class',
        ));
    }

    public function subjectRanking(Request $request): View
    {
        $schoolId = $this->schoolId();

        $exams = Exam::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $classes = SchoolClass::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $subjects = Subject::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();

        $selectedExam = $request->exam_id;
        $selectedClass = $request->school_class_id;
        $selectedSubject = $request->subject_id;

        $rankings = collect();
        $subject = null;

        if ($selectedExam && $selectedClass && $selectedSubject) {
            $results = StudentResult::with('student')
                ->where('exam_id', $selectedExam)
                ->where('school_class_id', $selectedClass)
                ->where('subject_id', $selectedSubject)
                ->get();

            $studentScores = $results->groupBy('student_id')->map(function ($studentResults) {
                $total = $studentResults->sum('score');
                $count = $studentResults->count();
                $avg = $count > 0 ? round($total / $count, 2) : 0;

                return [
                    'student' => $studentResults->first()->student,
                    'total_score' => $total,
                    'average_score' => $avg,
                ];
            })->sortByDesc('average_score')->values();

            $rank = 1;
            $rankings = $studentScores->map(function ($item) use (&$rank) {
                $item['position'] = $rank++;

                return $item;
            });

            $subject = Subject::find($selectedSubject);
        }

        $exam = $selectedExam ? Exam::find($selectedExam) : null;
        $class = $selectedClass ? SchoolClass::find($selectedClass) : null;

        return view('results.computations.subject-ranking', compact(
            'exams',
            'classes',
            'subjects',
            'selectedExam',
            'selectedClass',
            'selectedSubject',
            'rankings',
            'exam',
            'class',
            'subject',
        ));
    }

    public function topPerformers(Request $request): View
    {
        $schoolId = $this->schoolId();

        $exams = Exam::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();

        $selectedExam = $request->exam_id;
        $limit = $request->limit ?? 20;

        $performers = collect();

        if ($selectedExam) {
            $performers = StudentReportCard::with('student', 'schoolClass')
                ->where('exam_id', $selectedExam)
                ->orderByDesc('average_score')
                ->take($limit)
                ->get();
        }

        $exam = $selectedExam ? Exam::find($selectedExam) : null;

        return view('results.computations.top-performers', compact(
            'exams',
            'selectedExam',
            'performers',
            'exam',
            'limit',
        ));
    }

    public function analytics(Request $request): View
    {
        $schoolId = $this->schoolId();

        $exams = Exam::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();
        $classes = SchoolClass::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();

        $selectedExam = $request->exam_id;
        $selectedClass = $request->school_class_id;

        $analytics = null;

        if ($selectedExam) {
            $query = StudentReportCard::where('exam_id', $selectedExam);

            if ($selectedClass) {
                $query->where('school_class_id', $selectedClass);
            }

            $reportCards = $query->get();

            $totalStudents = $reportCards->count();
            $averageScore = $reportCards->avg('average_score');
            $highestScore = $reportCards->max('average_score');
            $lowestScore = $reportCards->min('average_score');
            $passCount = $reportCards->where('average_score', '>=', 50)->count();
            $failCount = $totalStudents - $passCount;
            $passRate = $totalStudents > 0 ? round(($passCount / $totalStudents) * 100, 1) : 0;

            $gradeDistribution = $reportCards->pluck('overall_grade')->filter()->countBy()->sortDesc();
            $statusDistribution = $reportCards->pluck('status')->countBy();

            $classAverages = $reportCards->groupBy('school_class_id')->map(function ($cards) {
                return [
                    'class' => $cards->first()->schoolClass,
                    'average' => round($cards->avg('average_score'), 2),
                    'count' => $cards->count(),
                ];
            })->sortByDesc('average');

            $analytics = compact(
                'totalStudents',
                'averageScore',
                'highestScore',
                'lowestScore',
                'passCount',
                'failCount',
                'passRate',
                'gradeDistribution',
                'statusDistribution',
                'classAverages',
            );
        }

        $exam = $selectedExam ? Exam::find($selectedExam) : null;
        $class = $selectedClass ? SchoolClass::find($selectedClass) : null;

        return view('results.computations.analytics', compact(
            'exams',
            'classes',
            'selectedExam',
            'selectedClass',
            'analytics',
            'exam',
            'class',
        ));
    }
}
