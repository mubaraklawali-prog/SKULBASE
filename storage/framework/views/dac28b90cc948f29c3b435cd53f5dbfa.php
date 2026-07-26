<?php $__env->startSection('title', 'My Profile - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>My Profile</h2>
            <p class="text-muted mb-0">Your teaching profile and assignments</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card stat-card">
                <div class="card-body" style="padding: 24px; text-align: center;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden; background: #e7f1ff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 28px; color: #0d6efd; margin: 0 auto 16px;">
                        <?php echo e(substr($teacher->first_name, 0, 1)); ?><?php echo e(substr($teacher->last_name, 0, 1)); ?>

                    </div>
                    <h4 style="font-weight: 600; margin-bottom: 4px;"><?php echo e($teacher->full_name); ?></h4>
                    <p class="text-muted" style="font-size: 14px; margin-bottom: 16px;"><?php echo e($teacher->email); ?></p>

                    <div style="text-align: left; border-top: 1px solid #e9ecef; padding-top: 16px;">
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="text-muted" style="font-size: 13px;">Phone</span>
                            <span style="font-size: 13px; font-weight: 500;"><?php echo e($teacher->phone ?: 'N/A'); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="text-muted" style="font-size: 13px;">Gender</span>
                            <span style="font-size: 13px; font-weight: 500; text-transform: capitalize;"><?php echo e($teacher->gender); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="text-muted" style="font-size: 13px;">Qualification</span>
                            <span style="font-size: 13px; font-weight: 500;"><?php echo e($teacher->qualification ?: 'N/A'); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                            <span class="text-muted" style="font-size: 13px;">Employment Date</span>
                            <span style="font-size: 13px; font-weight: 500;"><?php echo e($teacher->employment_date?->format('M d, Y') ?: 'N/A'); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                            <span class="text-muted" style="font-size: 13px;">Can Mark Attendance</span>
                            <span style="font-size: 13px; font-weight: 500;">
                                <?php if($teacher->can_mark_attendance): ?>
                                    <span style="color: #198754;">Yes</span>
                                <?php else: ?>
                                    <span style="color: #6c757d;">No</span>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card stat-card mb-4">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 16px;">Assigned Classes</h5>
                    <?php $__empty_1 = true; $__currentLoopData = $teacher->schoolClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 8px;">
                            <div>
                                <span style="font-weight: 500; font-size: 14px;"><?php echo e($class->name); ?></span>
                                <?php if($class->section): ?>
                                    <span class="text-muted" style="font-size: 13px;"> - <?php echo e($class->section); ?></span>
                                <?php endif; ?>
                            </div>
                            <span class="text-muted" style="font-size: 13px;"><?php echo e($class->students_count); ?> students</span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted mb-0" style="font-size: 14px;">No classes assigned.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card stat-card">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 16px;">Assigned Subjects</h5>
                    <?php $__empty_1 = true; $__currentLoopData = $teacher->subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #f8f9fa; border-radius: 8px; margin-bottom: 8px;">
                            <span style="font-weight: 500; font-size: 14px;"><?php echo e($subject->name); ?></span>
                            <?php if($subject->code): ?>
                                <span class="text-muted" style="font-size: 13px;"><?php echo e($subject->code); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted mb-0" style="font-size: 14px;">No subjects assigned.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/teacher/profile.blade.php ENDPATH**/ ?>