<?php $__env->startSection('title', 'Class Daily Report - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Class Daily Report</h2>
            <p class="text-muted mb-0">View attendance for a specific class and date</p>
        </div>
        <a href="<?php echo e(route('attendance.dashboard')); ?>" class="sb-btn sb-btn-ghost">
            Back to Dashboard
        </a>
    </div>

    <form method="GET" action="<?php echo e(route('attendance.class-report')); ?>" class="card stat-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-sm">
                    <label class="sb-form-label">Select Class</label>
                    <select name="class_id" required class="sb-form-select">
                        <option value="">-- Choose a class --</option>
                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>" <?php echo e($selectedClass == $class->id ? 'selected' : ''); ?>>
                                <?php echo e($class->name); ?><?php echo e($class->section ? ' - ' . $class->section : ''); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-12 col-sm">
                    <label class="sb-form-label">Date</label>
                    <input type="date" name="date" value="<?php echo e($selectedDate); ?>" max="<?php echo e(date('Y-m-d')); ?>" required class="sb-form-input">
                </div>
                <div class="col-12 col-sm-auto">
                    <button type="submit" class="sb-btn sb-btn-dark w-100">
                        View Report
                    </button>
                </div>
            </div>
        </div>
    </form>

    <?php if($report): ?>
        <?php
            $r = $report;
        ?>
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-present">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-present"><?php echo e($r['presentCount']); ?></p>
                            <p class="stat-label">Present</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-absent">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-absent"><?php echo e($r['absentCount']); ?></p>
                            <p class="stat-label">Absent</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-late">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-late"><?php echo e($r['lateCount']); ?></p>
                            <p class="stat-label">Late</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon sb-stat-icon-excused">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        </div>
                        <div>
                            <p class="stat-number sb-stat-number-excused"><?php echo e($r['excusedCount']); ?></p>
                            <p class="stat-label">Excused</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card stat-card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-semibold"><?php echo e($r['class']->name); ?><?php echo e($r['class']->section ? ' - ' . $r['class']->section : ''); ?></h5>
                        <p class="text-muted small mb-0 mt-1">
                            <?php echo e(now()->parse($selectedDate)->format('l, M d, Y')); ?> &middot;
                            <?php echo e($r['markedCount']); ?>/<?php echo e($r['totalStudents']); ?> students marked &middot;
                            <?php echo e($r['attendancePercentage']); ?>% attendance rate
                        </p>
                    </div>
                    <a href="<?php echo e(route('attendance.create', ['class_id' => $selectedClass, 'date' => $selectedDate])); ?>" class="sb-btn sb-btn-primary">
                        <?php echo e($r['markedCount'] > 0 ? 'Update Attendance' : 'Take Attendance'); ?>

                    </a>
                </div>
            </div>
        </div>

        <div class="card stat-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 sb-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Adm. No.</th>
                                <th>Status</th>
                                <th>History</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $r['students']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php $status = $r['attendanceMap'][$student->id] ?? null; ?>
                                <tr>
                                    <td class="text-muted"><?php echo e($index + 1); ?></td>
                                    <td class="fw-medium"><?php echo e($student->full_name); ?></td>
                                    <td>
                                        <code class="sb-code"><?php echo e($student->admission_number); ?></code>
                                    </td>
                                    <td>
                                        <?php if($status === 'present'): ?>
                                            <span class="sb-badge sb-badge-present">Present</span>
                                        <?php elseif($status === 'absent'): ?>
                                            <span class="sb-badge sb-badge-absent">Absent</span>
                                        <?php elseif($status === 'late'): ?>
                                            <span class="sb-badge sb-badge-late">Late</span>
                                        <?php elseif($status === 'excused'): ?>
                                            <span class="sb-badge sb-badge-excused">Excused</span>
                                        <?php else: ?>
                                            <span class="sb-badge sb-badge-secondary">Not Marked</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('attendance.student', $student)); ?>" class="sb-link">View History</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5">
                                        <div class="sb-empty-state">
                                            <p>No students found in this class.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/attendance/class-report.blade.php ENDPATH**/ ?>