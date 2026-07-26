<?php $__env->startSection('title', "My Child's Results - Skulbase"); ?>

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
    .exam-group-header {
        background: #f0f7ff;
        font-weight: 700;
        color: #0a1628;
        font-size: 14px;
        border-bottom: 2px solid #4f9cf7;
    }
    .report-card-item {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        background: #fff;
        transition: box-shadow 0.2s;
    }
    .report-card-item:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    }
    .grade-badge {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    .status-active { background: #d1e7dd; color: #0f5132; }
    .status-pending { background: #fff3cd; color: #664d03; }
    .status-inactive { background: #f8d7da; color: #842029; }
</style>

<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>My Child's Results</h2>
            <p class="text-muted mb-0">View scores, grades, and report cards</p>
        </div>
    </div>

    <?php if($children->count() > 1): ?>
        <div class="card stat-card mb-4">
            <div class="card-body">
                <h6 style="font-weight: 600; margin-bottom: 12px; color: #0a1628;">Select Child</h6>
                <form method="GET" action="<?php echo e(route('parent.results.index')); ?>" class="child-selector" id="childSelectorForm">
                    <div class="row g-2">
                        <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="student_id" id="child_<?php echo e($child->id); ?>" value="<?php echo e($child->id); ?>" <?php echo e(old('student_id', $selectedStudentId) == $child->id ? 'checked' : ''); ?> onchange="this.form.submit()">
                                    <label class="form-check-label" for="child_<?php echo e($child->id); ?>">
                                        <strong><?php echo e($child->full_name); ?></strong>
                                        <br><small style="color: #6c757d;"><?php echo e($child->schoolClass->name ?? ''); ?><?php echo e($child->section ? ' — ' . $child->section->name : ''); ?></small>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if($selectedStudent): ?>
        <div class="card stat-card mb-4">
            <div class="card-body py-3 px-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <span style="background: #0a1628; color: #fff; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                            <?php echo e($selectedStudent->full_name); ?>

                        </span>
                    </div>
                    <div>
                        <form method="GET" action="<?php echo e(route('parent.results.index')); ?>" class="d-inline-flex align-items-center gap-2">
                            <input type="hidden" name="student_id" value="<?php echo e($selectedStudentId); ?>">
                            <label for="exam_filter" class="fw-semibold text-muted" style="font-size: 13px;">Exam:</label>
                            <select name="exam_id" id="exam_filter" class="form-select form-select-sm" style="width: auto; min-width: 200px;" onchange="this.form.submit()">
                                <option value="">All Exams</option>
                                <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($exam->id); ?>" <?php echo e(request('exam_id') == $exam->id ? 'selected' : ''); ?>><?php echo e($exam->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card stat-card mb-4">
                    <div class="card-body p-0">
                        <div class="p-4 pb-3">
                            <h5 style="font-weight: 700; color: #0a1628; margin-bottom: 4px;">Subject Scores</h5>
                            <p class="text-muted mb-0" style="font-size: 13px;">Detailed scores per subject</p>
                        </div>
                        <?php if($results && $results->count()): ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="font-size: 14px;">
                                    <thead style="background: #f8f9fa;">
                                        <tr>
                                            <th style="font-weight: 600; color: #495057; padding: 12px 16px;">Subject</th>
                                            <th style="font-weight: 600; color: #495057; padding: 12px 16px;">Assessment Type</th>
                                            <th style="font-weight: 600; color: #495057; padding: 12px 16px; text-align: center;">Score</th>
                                            <th style="font-weight: 600; color: #495057; padding: 12px 16px;">Exam</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $grouped = $results->groupBy(fn($r) => $r->exam?->name ?? 'Unassigned');
                                        ?>
                                        <?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $examName => $examResults): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td colspan="4" class="exam-group-header px-3 py-2"><?php echo e($examName); ?></td>
                                            </tr>
                                            <?php $__currentLoopData = $examResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td style="padding: 12px 16px; font-weight: 500;"><?php echo e($result->subject->name ?? '—'); ?></td>
                                                    <td style="padding: 12px 16px;"><?php echo e($result->assessmentType->name ?? $result->assessment_type ?? '—'); ?></td>
                                                    <td style="padding: 12px 16px; text-align: center;">
                                                        <span class="fw-bold" style="font-size: 15px;"><?php echo e($result->score ?? '—'); ?></span>
                                                    </td>
                                                    <td style="padding: 12px 16px; font-size: 13px; color: #6c757d;"><?php echo e($result->exam->name ?? '—'); ?></td>
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
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                                <h5 style="color: #6c757d; font-weight: 600; margin-bottom: 8px;">No Scores Available</h5>
                                <p style="color: #adb5bd; margin: 0;">No subject scores have been recorded yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card stat-card">
                    <div class="card-body">
                        <h5 style="font-weight: 700; color: #0a1628; margin-bottom: 4px;">Report Cards</h5>
                        <p class="text-muted mb-3" style="font-size: 13px;">Exam summaries and overall grades</p>

                        <?php if($reportCards && $reportCards->count()): ?>
                            <div class="d-flex flex-column gap-3">
                                <?php $__currentLoopData = $reportCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="report-card-item">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 style="font-weight: 700; color: #0a1628; margin-bottom: 2px;"><?php echo e($card->exam->name ?? '—'); ?></h6>
                                                <small class="text-muted"><?php echo e($selectedStudent->full_name); ?></small>
                                            </div>
                                            <span class="grade-badge status-active"><?php echo e($card->grade ?? '—'); ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div>
                                                <div style="font-size: 12px; color: #6c757d; margin-bottom: 2px;">Average</div>
                                                <div style="font-size: 22px; font-weight: 700; color: #0a1628;"><?php echo e($card->average ?? '—'); ?><span style="font-size: 13px; color: #6c757d; font-weight: 400;">%</span></div>
                                            </div>
                                            <a href="<?php echo e(route('parent.results.report-card', ['reportCard' => $card->id])); ?>"
                                               class="sb-btn sb-btn-outline-primary d-inline-flex align-items-center gap-2"
                                               style="font-size: 13px; padding: 8px 16px;">
                                                View Report
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="9 18 15 12 9 6"></polyline>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div style="padding: 48px 20px; text-align: center;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ced4da" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 16px;">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                <h5 style="color: #6c757d; font-weight: 600; margin-bottom: 8px;">No Report Cards</h5>
                                <p style="color: #adb5bd; margin: 0;">No report cards have been generated yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card stat-card">
            <div class="card-body" style="padding: 60px 20px; text-align: center;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ced4da" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 16px;">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                <h5 style="color: #6c757d; font-weight: 600; margin-bottom: 8px;">Select a Child</h5>
                <p style="color: #adb5bd; margin: 0;">Choose a child above to view their results.</p>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/parent/results/index.blade.php ENDPATH**/ ?>