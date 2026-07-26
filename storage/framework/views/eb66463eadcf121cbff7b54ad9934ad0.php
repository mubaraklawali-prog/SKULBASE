<?php $__env->startSection('title', 'Result Approval Workflow - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Result Approval Workflow</h2>
            <p>Manage the approval and publishing of student results</p>
        </div>
        <a href="<?php echo e(route('results.approvals.reports')); ?>" class="sb-btn sb-btn-dark">View Reports</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-2 mb-3">
            <a href="<?php echo e(route('results.approvals.dashboard', ['status' => 'draft'])); ?>" class="text-decoration-none">
                <div class="card stat-card h-100 <?php echo e(request('status') === 'draft' ? 'border-primary' : ''); ?>">
                    <div class="card-body sb-card-body text-center">
                        <p class="stat-number" style="color: #6c757d; font-size: 24px;"><?php echo e(number_format($statusCounts['draft'])); ?></p>
                        <p class="stat-label">Draft</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2 mb-3">
            <a href="<?php echo e(route('results.approvals.dashboard', ['status' => 'submitted'])); ?>" class="text-decoration-none">
                <div class="card stat-card h-100 <?php echo e(request('status') === 'submitted' ? 'border-primary' : ''); ?>">
                    <div class="card-body sb-card-body text-center">
                        <p class="stat-number" style="color: #0d6efd; font-size: 24px;"><?php echo e(number_format($statusCounts['submitted'])); ?></p>
                        <p class="stat-label">Submitted</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2 mb-3">
            <a href="<?php echo e(route('results.approvals.dashboard', ['status' => 'approved'])); ?>" class="text-decoration-none">
                <div class="card stat-card h-100 <?php echo e(request('status') === 'approved' ? 'border-warning' : ''); ?>">
                    <div class="card-body sb-card-body text-center">
                        <p class="stat-number" style="color: #664d03; font-size: 24px;"><?php echo e(number_format($statusCounts['approved'])); ?></p>
                        <p class="stat-label">Approved</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2 mb-3">
            <a href="<?php echo e(route('results.approvals.dashboard', ['status' => 'published'])); ?>" class="text-decoration-none">
                <div class="card stat-card h-100 <?php echo e(request('status') === 'published' ? 'border-success' : ''); ?>">
                    <div class="card-body sb-card-body text-center">
                        <p class="stat-number" style="color: #0f5132; font-size: 24px;"><?php echo e(number_format($statusCounts['published'])); ?></p>
                        <p class="stat-label">Published</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2 mb-3">
            <a href="<?php echo e(route('results.approvals.dashboard', ['status' => 'rejected'])); ?>" class="text-decoration-none">
                <div class="card stat-card h-100 <?php echo e(request('status') === 'rejected' ? 'border-danger' : ''); ?>">
                    <div class="card-body sb-card-body text-center">
                        <p class="stat-number" style="color: #dc3545; font-size: 24px;"><?php echo e(number_format($statusCounts['rejected'])); ?></p>
                        <p class="stat-label">Rejected</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2 mb-3">
            <a href="<?php echo e(route('results.approvals.dashboard')); ?>" class="text-decoration-none">
                <div class="card stat-card h-100">
                    <div class="card-body sb-card-body text-center">
                        <p class="stat-number" style="color: #1a1a2e; font-size: 24px;"><?php echo e(number_format(array_sum($statusCounts))); ?></p>
                        <p class="stat-label">Total</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body sb-card-body">
            <h5 class="fw-semibold mb-3">Filter & Bulk Actions</h5>
            <form method="POST" action="<?php echo e(route('results.approvals.bulk-action')); ?>" id="bulkActionForm">
                <?php echo csrf_field(); ?>
                <div class="row align-items-end">
                    <div class="col-md-2 mb-3">
                        <label class="sb-form-label">Exam</label>
                        <select name="exam_id" required class="sb-form-select">
                            <option value="">-- All Exams --</option>
                            <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($exam->id); ?>"><?php echo e($exam->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="sb-form-label">Class</label>
                        <select name="school_class_id" required class="sb-form-select">
                            <option value="">-- All Classes --</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?><?php echo e($class->section ? ' - ' . $class->section : ''); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="sb-form-label">Bulk Action</label>
                        <select name="action" required class="sb-form-select">
                            <option value="">-- Select Action --</option>
                            <option value="submit">Submit All (Draft → Submitted)</option>
                            <option value="approve">Approve All (Submitted → Approved)</option>
                            <option value="publish">Publish All (Approved → Published)</option>
                            <option value="revert">Revert All to Draft</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3" id="rejectionReasonGroup" style="display: none;">
                        <label class="sb-form-label">Rejection Reason</label>
                        <input type="text" name="rejection_reason" class="sb-form-input" placeholder="Enter reason...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <button type="submit" onclick="return confirm('Are you sure you want to perform this bulk action?')" class="sb-btn sb-btn-primary">Execute</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h5 class="fw-semibold mb-0">Report Cards</h5>
                        <form method="GET" action="<?php echo e(route('results.approvals.dashboard')); ?>" class="d-flex gap-2">
                            <select name="exam_id" class="sb-form-select sb-form-select-sm">
                                <option value="">All Exams</option>
                                <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($exam->id); ?>" <?php echo e(request('exam_id') == $exam->id ? 'selected' : ''); ?>><?php echo e($exam->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <select name="school_class_id" class="sb-form-select sb-form-select-sm">
                                <option value="">All Classes</option>
                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>" <?php echo e(request('school_class_id') == $class->id ? 'selected' : ''); ?>><?php echo e($class->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-secondary">Filter</button>
                        </form>
                    </div>

                    <?php if($reportCards->isEmpty()): ?>
                        <div class="sb-empty-state">
                            <p>No report cards found.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="sb-table table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Exam</th>
                                        <th>Class</th>
                                        <th>Average</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $reportCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="fw-medium"><?php echo e($card->student->full_name ?? '—'); ?></td>
                                            <td><?php echo e($card->exam->name ?? '—'); ?></td>
                                            <td><?php echo e($card->schoolClass->name ?? '—'); ?><?php echo e($card->schoolClass->section ? ' - ' . $card->schoolClass->section : ''); ?></td>
                                            <td class="fw-semibold"><?php echo e(number_format($card->average_score, 1)); ?>%</td>
                                            <td>
                                                <?php if($card->status === 'published'): ?>
                                                    <span class="sb-badge sb-badge-published">Published</span>
                                                <?php elseif($card->status === 'approved'): ?>
                                                    <span class="sb-badge sb-badge-pending">Approved</span>
                                                <?php elseif($card->status === 'submitted'): ?>
                                                    <span class="sb-badge sb-badge-info">Submitted</span>
                                                <?php elseif($card->status === 'rejected'): ?>
                                                    <span class="sb-badge sb-badge-rejected">Rejected</span>
                                                <?php else: ?>
                                                    <span class="sb-badge sb-badge-draft">Draft</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex gap-1 justify-content-end">
                                                    <?php if($card->status === 'draft'): ?>
                                                        <form method="POST" action="<?php echo e(route('results.approvals.submit', $card)); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-primary">Submit</button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <?php if($card->status === 'submitted'): ?>
                                                        <form method="POST" action="<?php echo e(route('results.approvals.approve', $card)); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-warning">Approve</button>
                                                        </form>
                                                        <form method="POST" action="<?php echo e(route('results.approvals.reject', $card)); ?>" class="reject-form">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="rejection_reason" class="reject-reason" value="">
                                                            <button type="submit" onclick="event.preventDefault(); var reason = prompt('Rejection reason:'); if(reason){this.form.querySelector('.reject-reason').value=reason;this.form.submit();}" class="sb-btn sb-btn-sm sb-btn-outline-danger">Reject</button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <?php if($card->status === 'approved'): ?>
                                                        <form method="POST" action="<?php echo e(route('results.approvals.publish', $card)); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-success">Publish</button>
                                                        </form>
                                                        <form method="POST" action="<?php echo e(route('results.approvals.reject', $card)); ?>" class="reject-form">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="rejection_reason" class="reject-reason" value="">
                                                            <button type="submit" onclick="event.preventDefault(); var reason = prompt('Rejection reason:'); if(reason){this.form.querySelector('.reject-reason').value=reason;this.form.submit();}" class="sb-btn sb-btn-sm sb-btn-outline-danger">Reject</button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <?php if($card->status === 'published'): ?>
                                                        <form method="POST" action="<?php echo e(route('results.approvals.unpublish', $card)); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" onclick="return confirm('Unpublish this report card?')" class="sb-btn sb-btn-sm sb-btn-secondary">Unpublish</button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <?php if(in_array($card->status, ['submitted', 'approved', 'published', 'rejected'])): ?>
                                                        <form method="POST" action="<?php echo e(route('results.approvals.revert', $card)); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" onclick="return confirm('Revert to draft?')" class="sb-btn sb-btn-sm sb-btn-secondary">Revert</button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <a href="<?php echo e(route('results.computations.show', $card)); ?>" class="sb-link">View</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php if($card->rejection_reason): ?>
                                            <tr>
                                                <td colspan="6" style="padding: 0 16px 8px; background: #fff5f5;">
                                                    <small style="color: #842029;"><strong>Rejection reason:</strong> <?php echo e($card->rejection_reason); ?></small>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <?php echo e($reportCards->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card stat-card mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Workflow</h5>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-center gap-2 p-2" style="background: #f0f2f5; border-radius: 8px;">
                            <span class="sb-badge sb-badge-draft" style="padding: 2px 8px; border-radius: 4px; font-size: 11px;">1</span>
                            <span class="small fw-medium">Draft — Results can be edited</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 p-2" style="background: #f0f2f5; border-radius: 8px;">
                            <span class="sb-badge sb-badge-info" style="padding: 2px 8px; border-radius: 4px; font-size: 11px;">2</span>
                            <span class="small fw-medium">Submitted — Scores locked</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 p-2" style="background: #f0f2f5; border-radius: 8px;">
                            <span class="sb-badge sb-badge-pending" style="padding: 2px 8px; border-radius: 4px; font-size: 11px;">3</span>
                            <span class="small fw-medium">Approved — Ready for publication</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 p-2" style="background: #f0f2f5; border-radius: 8px;">
                            <span class="sb-badge sb-badge-published" style="padding: 2px 8px; border-radius: 4px; font-size: 11px;">4</span>
                            <span class="small fw-medium">Published — Printable & visible</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 p-2" style="background: #fff5f5; border-radius: 8px;">
                            <span class="sb-badge sb-badge-rejected" style="padding: 2px 8px; border-radius: 4px; font-size: 11px;">R</span>
                            <span class="small fw-medium">Rejected — Returns to Draft</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card stat-card">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Recent Activity</h5>
                    <?php if($recentLogs->isEmpty()): ?>
                        <p class="text-muted small mb-0">No recent activity.</p>
                    <?php else: ?>
                        <?php $__currentLoopData = $recentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="py-2" style="border-bottom: 1px solid #f0f0f0; <?php echo e($loop->last ? 'border-bottom: none;' : ''); ?>">
                                <div class="d-flex align-items-center gap-2">
                                    <?php if($log->action === 'submitted'): ?>
                                        <span class="sb-badge sb-badge-info" style="padding: 2px 8px; border-radius: 4px; font-size: 10px;">SUBMIT</span>
                                    <?php elseif($log->action === 'approved'): ?>
                                        <span class="sb-badge sb-badge-pending" style="padding: 2px 8px; border-radius: 4px; font-size: 10px;">APPROVE</span>
                                    <?php elseif($log->action === 'published'): ?>
                                        <span class="sb-badge sb-badge-published" style="padding: 2px 8px; border-radius: 4px; font-size: 10px;">PUBLISH</span>
                                    <?php elseif($log->action === 'rejected'): ?>
                                        <span class="sb-badge sb-badge-rejected" style="padding: 2px 8px; border-radius: 4px; font-size: 10px;">REJECT</span>
                                    <?php else: ?>
                                        <span class="sb-badge sb-badge-draft" style="padding: 2px 8px; border-radius: 4px; font-size: 10px;"><?php echo e(strtoupper($log->action)); ?></span>
                                    <?php endif; ?>
                                    <span class="small text-muted"><?php echo e($log->performedByUser->name ?? 'System'); ?></span>
                                </div>
                                <p class="small mb-0 mt-1"><?php echo e($log->studentReportCard->student->full_name ?? '—'); ?> — <?php echo e($log->old_status); ?> → <?php echo e($log->new_status); ?></p>
                                <small class="text-muted"><?php echo e($log->created_at->diffForHumans()); ?></small>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/results/approvals/dashboard.blade.php ENDPATH**/ ?>