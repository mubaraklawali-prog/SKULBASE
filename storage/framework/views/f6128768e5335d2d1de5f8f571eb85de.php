<?php $__env->startSection('title', 'Report Card - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .child-selector .form-check {
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        margin-bottom: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .child-selector .form-check:hover {
        border-color: #4f9cf7;
        background: #f8f9ff;
    }
    .child-selector .form-check-input:checked + .form-check-label {
        font-weight: 600;
        color: #0a1628;
    }
    .child-selector .form-check:has(.form-check-input:checked) {
        border-color: #4f9cf7;
        background: #f0f7ff;
    }
    .summary-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px;
        background: #fff;
    }
    .summary-stat {
        text-align: center;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 10px;
    }
    .summary-stat .label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        font-weight: 600;
    }
    .summary-stat .value {
        font-size: 28px;
        font-weight: 700;
        color: #0a1628;
    }
    .summary-stat .value small {
        font-size: 14px;
        color: #6c757d;
        font-weight: 400;
    }
    .grade-badge-lg {
        display: inline-block;
        padding: 8px 24px;
        border-radius: 24px;
        font-size: 16px;
        font-weight: 700;
        background: #d1e7dd;
        color: #0f5132;
    }
    .remarks-box {
        background: #f8f9fa;
        border-left: 4px solid #4f9cf7;
        border-radius: 0 8px 8px 0;
        padding: 16px 20px;
        margin-top: 16px;
    }
    @media print {
        .no-print { display: none !important; }
        .card { border: 1px solid #dee2e6 !important; box-shadow: none !important; }
    }
</style>

<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <div>
            <h2>Report Card</h2>
            <p class="text-muted mb-0">Detailed exam report for <?php echo e($reportCard->student->full_name ?? ''); ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('parent.results.index', ['student_id' => $reportCard->student_id])); ?>"
               class="sb-btn sb-btn-outline-secondary d-inline-flex align-items-center gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to Results
            </a>
            <button onclick="window.print()" class="sb-btn sb-btn-outline-secondary d-inline-flex align-items-center gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Print
            </button>
        </div>
    </div>

    <div class="summary-card mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h4 style="font-weight: 700; color: #0a1628; margin-bottom: 4px;"><?php echo e($reportCard->exam->name ?? 'Report Card'); ?></h4>
                <p class="text-muted mb-0" style="font-size: 14px;">
                    <?php echo e($reportCard->student->full_name ?? ''); ?>

                    <?php if($reportCard->student?->schoolClass): ?>
                        &mdash; <?php echo e($reportCard->student->schoolClass->name); ?><?php echo e($reportCard->student->section ? ' — ' . $reportCard->student->section->name : ''); ?>

                    <?php endif; ?>
                </p>
            </div>
            <?php if($reportCard->grade): ?>
                <span class="grade-badge-lg"><?php echo e($reportCard->grade); ?></span>
            <?php endif; ?>
        </div>

        <div class="row g-3">
            <div class="col-md-3 col-6">
                <div class="summary-stat">
                    <div class="label">Average</div>
                    <div class="value"><?php echo e($reportCard->average ?? '—'); ?><small>%</small></div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="summary-stat">
                    <div class="label">Grade</div>
                    <div class="value"><?php echo e($reportCard->grade ?? '—'); ?></div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="summary-stat">
                    <div class="label">Position</div>
                    <div class="value"><?php echo e($reportCard->position ?? '—'); ?><small> <?php echo e($reportCard->position ? ordinal($reportCard->position) : ''); ?></small></div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="summary-stat">
                    <div class="label">Total Students</div>
                    <div class="value"><?php echo e($reportCard->total_students ?? '—'); ?></div>
                </div>
            </div>
        </div>

        <?php if($reportCard->remarks): ?>
            <div class="remarks-box">
                <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; color: #4f9cf7; margin-bottom: 6px;">Remarks</div>
                <p style="margin: 0; color: #333; font-size: 14px;"><?php echo e($reportCard->remarks); ?></p>
            </div>
        <?php endif; ?>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="p-4 pb-3">
                <h5 style="font-weight: 700; color: #0a1628; margin-bottom: 4px;">Subject Scores</h5>
                <p class="text-muted mb-0" style="font-size: 13px;">Breakdown of scores by subject</p>
            </div>
            <?php if($groupedResults && count($groupedResults)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 14px;">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th style="font-weight: 600; color: #495057; padding: 12px 16px;">Subject</th>
                                <th style="font-weight: 600; color: #495057; padding: 12px 16px;">Assessment Type</th>
                                <th style="font-weight: 600; color: #495057; padding: 12px 16px; text-align: center;">Score</th>
                                <th style="font-weight: 600; color: #495057; padding: 12px 16px;">Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $groupedResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subjectId => $subjectData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td colspan="4" style="background: #f0f7ff; font-weight: 700; color: #0a1628; font-size: 14px; border-bottom: 2px solid #4f9cf7; padding: 10px 16px;">
                                        <?php echo e($subjectData['subject']); ?>

                                    </td>
                                </tr>
                                <?php $__currentLoopData = $subjectData['scores']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td style="padding: 12px 16px;"><?php echo e($subjectData['subject']); ?></td>
                                        <td style="padding: 12px 16px;"><?php echo e($score['assessment_type'] ?? '—'); ?></td>
                                        <td style="padding: 12px 16px; text-align: center;">
                                            <span class="fw-bold" style="font-size: 15px;"><?php echo e($score['score'] ?? '—'); ?></span>
                                        </td>
                                        <td style="padding: 12px 16px; color: #6c757d; font-size: 13px;">—</td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div style="padding: 48px 20px; text-align: center;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ced4da" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 16px;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    <h5 style="color: #6c757d; font-weight: 600; margin-bottom: 8px;">No Scores Available</h5>
                    <p style="color: #adb5bd; margin: 0;">No subject scores found for this report card.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/parent/results/report-card.blade.php ENDPATH**/ ?>