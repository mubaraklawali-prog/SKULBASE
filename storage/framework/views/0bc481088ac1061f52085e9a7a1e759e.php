<?php $__env->startSection('title', 'Attendance Records - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Attendance Records</h2>
            <p class="text-muted mb-0">Browse and filter all attendance records</p>
        </div>
        <a href="<?php echo e(route('attendance.create')); ?>" class="sb-btn sb-btn-primary">Take Attendance</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('attendance.index')); ?>" class="sb-search-bar">
                <input type="date" name="date" value="<?php echo e(request('date')); ?>" max="<?php echo e(date('Y-m-d')); ?>" placeholder="Date" class="sb-form-input">
                <select name="class_id" class="sb-form-select">
                    <option value="">All Classes</option>
                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($class->id); ?>" <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>>
                            <?php echo e($class->name); ?><?php echo e($class->section ? ' - ' . $class->section : ''); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <select name="status" class="sb-form-select">
                    <option value="">All Statuses</option>
                    <option value="present" <?php echo e(request('status') === 'present' ? 'selected' : ''); ?>>Present</option>
                    <option value="absent" <?php echo e(request('status') === 'absent' ? 'selected' : ''); ?>>Absent</option>
                    <option value="late" <?php echo e(request('status') === 'late' ? 'selected' : ''); ?>>Late</option>
                    <option value="excused" <?php echo e(request('status') === 'excused' ? 'selected' : ''); ?>>Excused</option>
                </select>
                <button type="submit" class="sb-btn sb-btn-dark">Filter</button>
                <?php if(request()->hasAny(['date', 'class_id', 'status'])): ?>
                    <a href="<?php echo e(route('attendance.index')); ?>" class="sb-btn sb-btn-secondary">Clear</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="sb-table table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Marked By</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($record->attendance_date->format('M d, Y')); ?></td>
                                <td style="font-weight: 500;">
                                    <a href="<?php echo e(route('attendance.student', $record->student)); ?>" style="color: #333; text-decoration: none;"><?php echo e($record->student->full_name); ?></a>
                                </td>
                                <td><?php echo e($record->schoolClass->name ?? '—'); ?></td>
                                <td>
                                    <?php if($record->status === 'present'): ?>
                                        <span class="sb-badge sb-badge-present">Present</span>
                                    <?php elseif($record->status === 'absent'): ?>
                                        <span class="sb-badge sb-badge-absent">Absent</span>
                                    <?php elseif($record->status === 'late'): ?>
                                        <span class="sb-badge sb-badge-late">Late</span>
                                    <?php else: ?>
                                        <span class="sb-badge sb-badge-excused">Excused</span>
                                    <?php endif; ?>
                                </td>
                                <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo e($record->remarks ?? '—'); ?></td>
                                <td><?php echo e($record->marker->full_name ?? '—'); ?></td>
                                <td class="text-end">
                                    <a href="<?php echo e(route('attendance.show', $record)); ?>" style="color: var(--primary); font-weight: 500; text-decoration: none; font-size: 13px;">View</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7">
                                    <div class="sb-empty-state">
                                        <p style="margin: 0; font-size: 15px;">No attendance records found.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if($attendances->hasPages()): ?>
        <div class="mt-3 d-flex justify-content-center">
            <?php echo e($attendances->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/attendance/index.blade.php ENDPATH**/ ?>