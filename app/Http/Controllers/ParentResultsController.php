<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ResolvesParentChildren;
use App\Models\Exam;
use App\Models\StudentReportCard;
use App\Models\StudentResult;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParentResultsController extends Controller
{
    use ResolvesParentChildren;

    public function index(Request $request): View
    {
        $children = $this->resolveParentChildren();
        $selectedStudentId = $request->student_id;
        $selectedStudent = $this->resolveSelectedChild($children, $selectedStudentId);

        $results = collect();
        $reportCards = collect();
        $exams = collect();

        if ($selectedStudent) {
            $exams = Exam::where('school_id', $selectedStudent->school_id)
                ->where('status', true)
                ->orderBy('created_at', 'desc')
                ->get();

            $selectedExamId = $request->exam_id;

            $results = StudentResult::where('student_id', $selectedStudent->id)
                ->where('school_id', $selectedStudent->school_id)
                ->with(['subject', 'assessmentType', 'exam'])
                ->when($selectedExamId, fn ($q) => $q->where('exam_id', $selectedExamId))
                ->orderByDesc('exam_id')
                ->orderBy('subject_id')
                ->get();

            $reportCards = StudentReportCard::where('student_id', $selectedStudent->id)
                ->where('school_id', $selectedStudent->school_id)
                ->with('exam')
                ->whereIn('status', ['approved', 'published'])
                ->latest('exam_id')
                ->get();
        }

        return view('parent.results.index', compact(
            'children',
            'selectedStudentId',
            'selectedStudent',
            'results',
            'reportCards',
            'exams',
        ));
    }

    public function showReportCard(StudentReportCard $reportCard): View
    {
        $children = $this->resolveParentChildren();
        $childIds = $children->pluck('id')->toArray();

        if (! in_array($reportCard->student_id, $childIds)) {
            abort(403, 'Unauthorized access to this report card.');
        }

        $reportCard->load(['exam', 'student.schoolClass']);

        $results = StudentResult::where('student_id', $reportCard->student_id)
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

        return view('parent.results.report-card', compact(
            'reportCard',
            'groupedResults',
            'children',
        ));
    }
}
