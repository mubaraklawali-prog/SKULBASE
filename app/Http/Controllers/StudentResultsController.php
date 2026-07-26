<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ResolvesStudent;
use App\Models\Exam;
use App\Models\StudentReportCard;
use App\Models\StudentResult;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentResultsController extends Controller
{
    use ResolvesStudent;

    public function index(Request $request): View
    {
        $student = $this->resolveStudent();

        $exams = Exam::where('school_id', $student->school_id)
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $selectedExamId = $request->exam_id;

        $results = StudentResult::where('student_id', $student->id)
            ->where('school_id', $student->school_id)
            ->with(['subject', 'assessmentType', 'exam'])
            ->when($selectedExamId, fn ($q) => $q->where('exam_id', $selectedExamId))
            ->orderByDesc('exam_id')
            ->orderBy('subject_id')
            ->get();

        $reportCards = StudentReportCard::where('student_id', $student->id)
            ->where('school_id', $student->school_id)
            ->with('exam')
            ->whereIn('status', ['approved', 'published'])
            ->latest('exam_id')
            ->get();

        return view('student.results.index', compact(
            'student',
            'results',
            'reportCards',
            'exams',
        ));
    }

    public function showReportCard(StudentReportCard $reportCard): View
    {
        $student = $this->resolveStudent();

        if ($reportCard->student_id !== $student->id) {
            abort(403, 'Unauthorized access to this report card.');
        }

        $reportCard->load(['exam', 'student.schoolClass']);

        $results = StudentResult::where('student_id', $student->id)
            ->where('exam_id', $reportCard->exam_id)
            ->with(['subject', 'assessmentType'])
            ->orderBy('subject_id')
            ->get();

        $groupedResults = $results->groupBy('subject_id')->map(function ($subjectResults) {
            $subject = $subjectResults->first()->subject;
            $assessmentScores = $subjectResults->map(fn ($r) => [
                'assessment_type' => $r->assessmentType->name ?? '—',
                'score' => $r->score,
                'percentage' => $r->assessmentType->percentage ?? 0,
            ]);

            return [
                'subject' => $subject->name ?? '—',
                'scores' => $assessmentScores,
                'total' => $subjectResults->sum('score'),
            ];
        });

        return view('student.results.report-card', compact(
            'reportCard',
            'groupedResults',
            'student',
        ));
    }
}
