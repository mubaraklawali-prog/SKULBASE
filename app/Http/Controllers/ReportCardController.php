<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\StudentReportCard;
use App\Services\ResultComputationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportCardController extends Controller
{
    public function __construct(
        private ResultComputationService $computationService,
    ) {}

    public function showForPrint(StudentReportCard $reportCard): View
    {
        $reportCard->load('student', 'exam', 'schoolClass', 'school');

        abort_unless($reportCard->status === 'published', 403, 'Only published report cards can be printed.');

        $subjectScores = $this->computationService->getSubjectScores(
            $reportCard->exam,
            $reportCard->schoolClass,
            $reportCard->student_id
        );

        $gradingRules = \App\Models\GradingSystem::where('school_id', $reportCard->school_id)
            ->orderByDesc('min_score')
            ->get();

        return view('results.report-cards.print', compact('reportCard', 'subjectScores', 'gradingRules'));
    }

    public function downloadPdf(StudentReportCard $reportCard)
    {
        $reportCard->load('student', 'exam', 'schoolClass', 'school');

        abort_unless($reportCard->status === 'published', 403, 'Only published report cards can be printed.');

        $subjectScores = $this->computationService->getSubjectScores(
            $reportCard->exam,
            $reportCard->schoolClass,
            $reportCard->student_id
        );

        $gradingRules = \App\Models\GradingSystem::where('school_id', $reportCard->school_id)
            ->orderByDesc('min_score')
            ->get();

        $pdf = Pdf::loadView('results.report-cards.pdf', compact('reportCard', 'subjectScores', 'gradingRules'))
            ->setPaper('a4')
            ->setOption([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);

        $fileName = 'report-card-' . $reportCard->student->admission_number . '-' . $reportCard->exam->name . '.pdf';

        return $pdf->download($fileName);
    }

    public function bulkSelector(): View
    {
        $exams = Exam::where('status', true)->orderBy('name')->get();
        $classes = SchoolClass::where('status', true)->orderBy('name')->get();

        return view('results.report-cards.bulk', compact('exams', 'classes'));
    }

    public function bulkPrint(Request $request): View
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'school_class_id' => 'required|exists:school_classes,id',
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);
        $schoolClass = SchoolClass::findOrFail($validated['school_class_id']);

        $reportCards = StudentReportCard::with('student', 'exam', 'schoolClass', 'school')
            ->where('exam_id', $exam->id)
            ->where('school_class_id', $schoolClass->id)
            ->where('status', 'published')
            ->orderBy('class_position')
            ->get();

        $gradingRules = \App\Models\GradingSystem::where('school_id', $schoolClass->school_id)
            ->orderByDesc('min_score')
            ->get();

        $subjectScoresMap = [];

        foreach ($reportCards as $reportCard) {
            $subjectScoresMap[$reportCard->student_id] = $this->computationService->getSubjectScores(
                $reportCard->exam,
                $reportCard->schoolClass,
                $reportCard->student_id
            );
        }

        return view('results.report-cards.bulk-print', compact('reportCards', 'subjectScoresMap', 'exam', 'schoolClass', 'gradingRules'));
    }

    public function bulkDownload(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'school_class_id' => 'required|exists:school_classes,id',
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);
        $schoolClass = SchoolClass::findOrFail($validated['school_class_id']);

        $reportCards = StudentReportCard::with('student', 'exam', 'schoolClass', 'school')
            ->where('exam_id', $exam->id)
            ->where('school_class_id', $schoolClass->id)
            ->where('status', 'published')
            ->orderBy('class_position')
            ->get();

        $gradingRules = \App\Models\GradingSystem::where('school_id', $schoolClass->school_id)
            ->orderByDesc('min_score')
            ->get();

        $subjectScoresMap = [];

        foreach ($reportCards as $reportCard) {
            $subjectScoresMap[$reportCard->student_id] = $this->computationService->getSubjectScores(
                $reportCard->exam,
                $reportCard->schoolClass,
                $reportCard->student_id
            );
        }

        $pdf = Pdf::loadView('results.report-cards.bulk-pdf', compact('reportCards', 'subjectScoresMap', 'exam', 'schoolClass', 'gradingRules'))
            ->setPaper('a4')
            ->setOption([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);

        $fileName = 'report-cards-' . $schoolClass->name . '-' . $exam->name . '.pdf';

        return $pdf->download($fileName);
    }
}
