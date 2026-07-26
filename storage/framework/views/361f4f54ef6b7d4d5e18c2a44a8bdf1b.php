<?php $__env->startSection('title', "My Child's Attendance - Skulbase"); ?>

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
    .stat-card-mini {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        transition: transform 0.2s;
    }
    .stat-card-mini:hover {
        transform: translateY(-2px);
    }
    .stat-card-mini .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
    }
    .stat-card-mini .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #0a1628;
        line-height: 1;
        margin-bottom: 4px;
    }
    .stat-card-mini .stat-label {
        font-size: 13px;
        color: #6c757d;
        font-weight: 500;
    }
    .badge-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }
</style>

<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>My Child's Attendance</h2>
            <p class="text-muted mb-0">Track your child's attendance records</p>
        </div>
    </div>

    <?php if($children->count() > 1): ?>
        <div class="card stat-card mb-4">
            <div class="card-body">
                <h6 style="font-weight: 600; margin-bottom: 12px; color: #0a1628;">Select Child</h6>
                <form method="GET" action="<?php echo e(route('parent.attendance.index')); ?>" class="child-selector">
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
                        <span style="background: #e7f1ff; color: #0d6efd; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                            <?php echo e($selectedStudent->schoolClass->name ?? ''); ?><?php echo e($selectedStudent->section ? ' — ' . $selectedStudent->section->name : ''); ?>

                        </span>
                    </div>
                    <form method="GET" action="<?php echo e(route('parent.attendance.index')); ?>" class="d-flex align-items-center gap-2 flex-wrap">
                        <?php if($children->count() > 1): ?>
                            <input type="hidden" name="student_id" value="<?php echo e($selectedStudentId); ?>">
                        <?php endif; ?>
                        <label class="sb-form-label mb-0" style="white-space: nowrap;">Month:</label>
                        <input type="month" name="month" class="sb-form-input" style="width: auto;" value="<?php echo e($month); ?>" onchange="this.form.submit()">
                    </form>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card-mini">
                    <div class="stat-icon" style="background: #e7f1ff; color: #0d6efd;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div class="stat-value"><?php echo e($stats['total_days']); ?></div>
                    <div class="stat-label">Total Days</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card-mini">
                    <div class="stat-icon" style="background: #d1e7dd; color: #0f5132;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <div class="stat-value" style="color: #0f5132;"><?php echo e($stats['present']); ?></div>
                    <div class="stat-label">Present</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card-mini">
                    <div class="stat-icon" style="background: #f8d7da; color: #842029;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                    </div>
                    <div class="stat-value" style="color: #842029;"><?php echo e($stats['absent']); ?></div>
                    <div class="stat-label">Absent</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card-mini">
                    <div class="stat-icon" style="background: #fff3cd; color: #664d03;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                        </svg>
                    </div>
                    <div class="stat-value" style="color: #664d03;"><?php echo e($stats['attendance_rate']); ?>%</div>
                    <div class="stat-label">Attendance Rate</div>
                </div>
            </div>
        </div>

        <div class="card stat-card">
            <div class="card-body p-0">
                <div class="sb-section-header" style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0;">
                    <h6 style="margin: 0; font-weight: 600;">Attendance Records</h6>
                    <span style="color: #6c757d; font-size: 13px;"><?php echo e(\Carbon\Carbon::parse($month . '-01')->format('F Y')); ?></span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover sb-table mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Class</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td style="font-weight: 500;"><?php echo e(\Carbon\Carbon::parse($attendance->attendance_date)->format('D, M d, Y')); ?></td>
                                    <td>
                                        <?php
                                            $statusStyles = [
                                                'present' => 'background: #d1e7dd; color: #0f5132;',
                                                'absent' => 'background: #f8d7da; color: #842029;',
                                                'late' => 'background: #fff3cd; color: #664d03;',
                                                'excused' => 'background: #cff4fc; color: #055160;',
                                            ];
                                        ?>
                                        <span class="badge-status" style="<?php echo e($statusStyles[$attendance->status] ?? ''); ?>">
                                            <?php echo e(ucfirst($attendance->status)); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($attendance->schoolClass->name ?? '—'); ?></td>
                                    <td style="color: #6c757d;"><?php echo e($attendance->remarks ?? '—'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                        No attendance records found for this month.
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
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <line x1="20" y1="8" x2="20" y2="14"></line>
                    <line x1="23" y1="11" x2="17" y2="11"></line>
                </svg>
                <h5 style="color: #6c757d; font-weight: 600; margin-bottom: 8px;">Select a Child</h5>
                <p style="color: #adb5bd; margin: 0;">Choose a child above to view their attendance.</p>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/parent/attendance/index.blade.php ENDPATH**/ ?>