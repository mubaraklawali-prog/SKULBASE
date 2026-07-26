<?php $__env->startSection('title', "My Child's Assignments - Skulbase"); ?>

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
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-upcoming {
        background: #e7f1ff;
        color: #0d6efd;
    }
    .status-submitted {
        background: #d1e7dd;
        color: #0f5132;
    }
    .status-overdue {
        background: #f8d7da;
        color: #842029;
    }
</style>

<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>My Child's Assignments</h2>
            <p class="text-muted mb-0">View and track your child's assignments</p>
        </div>
    </div>

    <?php if($children->count() > 1): ?>
        <div class="card stat-card mb-4">
            <div class="card-body">
                <h6 style="font-weight: 600; margin-bottom: 12px; color: #0a1628;">Select Child</h6>
                <form method="GET" action="<?php echo e(route('parent.assignments.index')); ?>" class="child-selector">
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
        <div class="card stat-card mb-3">
            <div class="card-body py-3 px-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <span style="background: #0a1628; color: #fff; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                            <?php echo e($selectedStudent->full_name); ?>

                        </span>
                        <span style="color: #6c757d; font-size: 13px;">
                            <?php echo e($assignments->count()); ?> assignment<?php echo e($assignments->count() !== 1 ? 's' : ''); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card stat-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th style="font-weight: 600; color: #0a1628;">Title</th>
                                <th style="font-weight: 600; color: #0a1628;">Subject</th>
                                <th style="font-weight: 600; color: #0a1628;">Teacher</th>
                                <th style="font-weight: 600; color: #0a1628;">Due Date</th>
                                <th style="font-weight: 600; color: #0a1628;">Status</th>
                                <th style="font-weight: 600; color: #0a1628;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td style="font-weight: 500;"><?php echo e($assignment->title); ?></td>
                                    <td><?php echo e($assignment->subject->name ?? '—'); ?></td>
                                    <td><?php echo e($assignment->teacher->full_name ?? '—'); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($assignment->due_date)->format('M d, Y')); ?></td>
                                    <td>
                                        <?php
                                            $status = strtolower($assignment->status ?? 'upcoming');
                                            $statusClass = match($status) {
                                                'submitted' => 'status-submitted',
                                                'overdue' => 'status-overdue',
                                                default => 'status-upcoming',
                                            };
                                        ?>
                                        <span class="status-badge <?php echo e($statusClass); ?>"><?php echo e(ucfirst($status)); ?></span>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('parent.assignments.show', $assignment->id)); ?>" class="sb-btn sb-btn-sm sb-btn-outline-primary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                        No assignments found for this student.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card stat-card">
            <div class="card-body" style="padding: 60px 20px; text-align: center;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ced4da" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 16px;">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <h5 style="color: #6c757d; font-weight: 600; margin-bottom: 8px;">Select a Child</h5>
                <p style="color: #adb5bd; margin: 0;">Choose a child above to view their assignments.</p>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/parent/assignments/index.blade.php ENDPATH**/ ?>