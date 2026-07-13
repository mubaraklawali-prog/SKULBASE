<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ResultApprovalLog;
use App\Models\SchoolClass;
use App\Models\StudentReportCard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ResultApprovalController extends Controller
{
    private const VALID_TRANSITIONS = [
        'draft' => ['submitted'],
        'submitted' => ['approved', 'rejected'],
        'approved' => ['published', 'rejected', 'draft'],
        'published' => ['draft'],
        'rejected' => ['draft'],
    ];

    public function dashboard(Request $request): View
    {
        $exams = Exam::orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();

        $query = StudentReportCard::with('student', 'exam', 'schoolClass');

        if ($request->exam_id) {
            $query->where('exam_id', $request->exam_id);
        }
        if ($request->school_class_id) {
            $query->where('school_class_id', $request->school_class_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $reportCards = $query->orderByDesc('updated_at')->paginate(20)->withQueryString();

        $statusCounts = [
            'draft' => StudentReportCard::where('status', 'draft')->count(),
            'submitted' => StudentReportCard::where('status', 'submitted')->count(),
            'approved' => StudentReportCard::where('status', 'approved')->count(),
            'published' => StudentReportCard::where('status', 'published')->count(),
            'rejected' => StudentReportCard::where('status', 'rejected')->count(),
        ];

        $recentLogs = ResultApprovalLog::with('studentReportCard.student', 'performedByUser')
            ->latest()
            ->take(10)
            ->get();

        return view('results.approvals.dashboard', compact(
            'reportCards',
            'statusCounts',
            'recentLogs',
            'exams',
            'classes',
        ));
    }

    public function submit(StudentReportCard $reportCard): RedirectResponse
    {
        $this->validateTransition($reportCard, 'submitted');

        DB::transaction(function () use ($reportCard) {
            $oldStatus = $reportCard->status;

            $reportCard->update([
                'status' => 'submitted',
                'submitted_by' => Auth::id(),
                'submitted_at' => now(),
                'rejection_reason' => null,
            ]);

            $this->logAction($reportCard, 'submitted', $oldStatus, 'submitted');
        });

        return back()->with('success', 'Results submitted for approval.');
    }

    public function approve(StudentReportCard $reportCard): RedirectResponse
    {
        $this->validateTransition($reportCard, 'approved');

        DB::transaction(function () use ($reportCard) {
            $oldStatus = $reportCard->status;

            $reportCard->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'rejection_reason' => null,
            ]);

            $this->logAction($reportCard, 'approved', $oldStatus, 'approved');
        });

        return back()->with('success', 'Results approved successfully.');
    }

    public function publish(StudentReportCard $reportCard): RedirectResponse
    {
        $this->validateTransition($reportCard, 'published');

        DB::transaction(function () use ($reportCard) {
            $oldStatus = $reportCard->status;

            $reportCard->update([
                'status' => 'published',
                'published_by' => Auth::id(),
                'published_at' => now(),
            ]);

            $this->logAction($reportCard, 'published', $oldStatus, 'published');
        });

        return back()->with('success', 'Results published successfully.');
    }

    public function unpublish(StudentReportCard $reportCard): RedirectResponse
    {
        $this->validateTransition($reportCard, 'draft');

        DB::transaction(function () use ($reportCard) {
            $oldStatus = $reportCard->status;

            $reportCard->update([
                'status' => 'draft',
                'published_at' => null,
                'published_by' => null,
            ]);

            $this->logAction($reportCard, 'unpublished', $oldStatus, 'draft');
        });

        return back()->with('success', 'Results unpublished and reverted to draft.');
    }

    public function reject(Request $request, StudentReportCard $reportCard): RedirectResponse
    {
        $this->validateTransition($reportCard, 'rejected');

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        DB::transaction(function () use ($reportCard, $validated) {
            $oldStatus = $reportCard->status;

            $reportCard->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
            ]);

            $this->logAction($reportCard, 'rejected', $oldStatus, 'rejected', $validated['rejection_reason']);
        });

        return back()->with('success', 'Results rejected.');
    }

    public function revertToDraft(StudentReportCard $reportCard): RedirectResponse
    {
        $this->validateTransition($reportCard, 'draft');

        DB::transaction(function () use ($reportCard) {
            $oldStatus = $reportCard->status;

            $reportCard->update([
                'status' => 'draft',
                'submitted_by' => null,
                'submitted_at' => null,
                'approved_by' => null,
                'approved_at' => null,
                'published_by' => null,
                'published_at' => null,
                'rejection_reason' => null,
            ]);

            $this->logAction($reportCard, 'reverted', $oldStatus, 'draft');
        });

        return back()->with('success', 'Results reverted to draft.');
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'action' => 'required|in:submit,approve,publish,revert,reject',
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);
        $schoolClass = SchoolClass::findOrFail($validated['school_class_id']);

        $action = $validated['action'];
        $statusMap = [
            'submit' => 'draft',
            'approve' => 'submitted',
            'publish' => 'approved',
            'revert' => ['submitted', 'approved', 'published', 'rejected'],
            'reject' => ['draft', 'submitted', 'approved'],
        ];

        $targetStatus = $statusMap[$action];
        $newStatus = $action === 'submit' ? 'submitted'
            : ($action === 'approve' ? 'approved'
            : ($action === 'publish' ? 'published'
            : 'draft'));

        $query = StudentReportCard::where('exam_id', $exam->id)
            ->where('school_class_id', $schoolClass->id);

        if (is_array($targetStatus)) {
            $query->whereIn('status', $targetStatus);
        } else {
            $query->where('status', $targetStatus);
        }

        $reportCards = $query->get();

        if ($reportCards->isEmpty()) {
            return back()->with('error', 'No report cards found for the selected action.');
        }

        DB::transaction(function () use ($reportCards, $action, $newStatus, $validated) {
            foreach ($reportCards as $reportCard) {
                $oldStatus = $reportCard->status;
                $updates = ['status' => $newStatus];

                if ($action === 'submit') {
                    $updates['submitted_by'] = Auth::id();
                    $updates['submitted_at'] = now();
                    $updates['rejection_reason'] = null;
                } elseif ($action === 'approve') {
                    $updates['approved_by'] = Auth::id();
                    $updates['approved_at'] = now();
                    $updates['rejection_reason'] = null;
                } elseif ($action === 'publish') {
                    $updates['published_by'] = Auth::id();
                    $updates['published_at'] = now();
                } elseif ($action === 'revert') {
                    $updates['submitted_by'] = null;
                    $updates['submitted_at'] = null;
                    $updates['approved_by'] = null;
                    $updates['approved_at'] = null;
                    $updates['published_by'] = null;
                    $updates['published_at'] = null;
                    $updates['rejection_reason'] = null;
                } elseif ($action === 'reject') {
                    $updates['rejection_reason'] = $validated['rejection_reason'] ?? null;
                }

                $reportCard->update($updates);
                $this->logAction($reportCard, $action, $oldStatus, $newStatus, $validated['rejection_reason'] ?? null);
            }
        });

        $count = $reportCards->count();
        $actionLabel = ucfirst($action === 'revert' ? 'reverted' : $action.'d');

        return back()->with('success', "{$count} report cards {$actionLabel} successfully.");
    }

    public function reports(Request $request): View
    {
        $type = $request->type ?? 'published';
        $exams = Exam::orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();

        $query = StudentReportCard::with('student', 'exam', 'schoolClass');

        if ($request->exam_id) {
            $query->where('exam_id', $request->exam_id);
        }
        if ($request->school_class_id) {
            $query->where('school_class_id', $request->school_class_id);
        }

        match ($type) {
            'published' => $query->where('status', 'published'),
            'pending' => $query->whereIn('status', ['draft', 'submitted']),
            'rejected' => $query->where('status', 'rejected'),
            'history' => null,
            default => null,
        };

        $reportCards = $type === 'history'
            ? ResultApprovalLog::with('studentReportCard.student', 'studentReportCard.exam', 'performedByUser')
                ->latest()
                ->paginate(20)
                ->withQueryString()
            : $query->latest()->paginate(20)->withQueryString();

        return view('results.approvals.reports', compact('reportCards', 'type', 'exams', 'classes'));
    }

    private function validateTransition(StudentReportCard $reportCard, string $newStatus): void
    {
        $allowed = self::VALID_TRANSITIONS[$reportCard->status] ?? [];

        if (! in_array($newStatus, $allowed)) {
            abort(403, "Cannot transition from '{$reportCard->status}' to '{$newStatus}'.");
        }
    }

    private function logAction(
        StudentReportCard $reportCard,
        string $action,
        string $oldStatus,
        string $newStatus,
        ?string $remarks = null,
    ): void {
        ResultApprovalLog::create([
            'school_id' => $reportCard->school_id,
            'student_report_card_id' => $reportCard->id,
            'action' => $action,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'performed_by' => Auth::id(),
            'remarks' => $remarks,
        ]);
    }
}
