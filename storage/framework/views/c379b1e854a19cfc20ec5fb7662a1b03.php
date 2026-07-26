<?php $__env->startSection('title', 'Attendance Record - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Attendance Detail</h2>
            <p class="text-muted mb-0"><?php echo e($attendance->student->full_name); ?> &middot; <?php echo e($attendance->attendance_date->format('l, M d, Y')); ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('attendance.student', $attendance->student)); ?>" class="sb-btn sb-btn-outline-primary">Student History</a>
            <a href="<?php echo e(route('attendance.index')); ?>" class="sb-btn sb-btn-secondary">Back to List</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Attendance Details</h5>
                    <div class="mb-3">
                        <label class="sb-form-label">Status</label>
                        <p style="margin: 0;">
                            <?php if($attendance->status === 'present'): ?>
                                <span class="sb-badge sb-badge-present">Present</span>
                            <?php elseif($attendance->status === 'absent'): ?>
                                <span class="sb-badge sb-badge-absent">Absent</span>
                            <?php elseif($attendance->status === 'late'): ?>
                                <span class="sb-badge sb-badge-late">Late</span>
                            <?php else: ?>
                                <span class="sb-badge sb-badge-excused">Excused</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="sb-form-label">Date</label>
                        <p style="margin: 0; font-size: 15px;"><?php echo e($attendance->attendance_date->format('l, M d, Y')); ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="sb-form-label">Class</label>
                        <p style="margin: 0; font-size: 15px;"><?php echo e($attendance->schoolClass->name ?? '—'); ?><?php echo e($attendance->schoolClass->section ? ' - ' . $attendance->schoolClass->section : ''); ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="sb-form-label">Remarks</label>
                        <p style="margin: 0; font-size: 15px;"><?php echo e($attendance->remarks ?? '—'); ?></p>
                    </div>
                    <div>
                        <label class="sb-form-label">Marked By</label>
                        <p style="margin: 0; font-size: 15px;"><?php echo e($attendance->marker->full_name ?? '—'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Student Info</h5>
                    <div class="mb-3">
                        <label class="sb-form-label">Full Name</label>
                        <p style="margin: 0; font-size: 15px;"><?php echo e($attendance->student->full_name); ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="sb-form-label">Admission Number</label>
                        <p style="margin: 0; font-size: 15px;"><code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;"><?php echo e($attendance->student->admission_number); ?></code></p>
                    </div>
                    <div class="mb-3">
                        <label class="sb-form-label">School</label>
                        <p style="margin: 0; font-size: 15px;"><?php echo e($attendance->school->name ?? '—'); ?></p>
                    </div>
                    <div>
                        <label class="sb-form-label">Student Status</label>
                        <p style="margin: 0;">
                            <?php if($attendance->student->status === 'active'): ?>
                                <span class="sb-badge sb-badge-active">Active</span>
                            <?php else: ?>
                                <span class="sb-badge sb-badge-inactive">Inactive</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/attendance/show.blade.php ENDPATH**/ ?>