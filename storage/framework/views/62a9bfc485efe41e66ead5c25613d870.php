<?php $__env->startSection('title', 'Assignments - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Assignments</h2>
            <p class="text-muted mb-0">Manage homework and class assignments</p>
        </div>
        <a href="<?php echo e(route('assignments.create')); ?>" class="sb-btn sb-btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            New Assignment
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('assignments.index')); ?>" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="sb-form-label">Search</label>
                    <input type="text" name="search" class="sb-form-input" value="<?php echo e(request('search')); ?>" placeholder="Search by title...">
                </div>
                <div class="col-md-2">
                    <label class="sb-form-label">Class</label>
                    <select name="class_id" class="sb-form-select">
                        <option value="">All Classes</option>
                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>" <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>><?php echo e($class->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="sb-form-label">Subject</label>
                    <select name="subject_id" class="sb-form-select">
                        <option value="">All Subjects</option>
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($subject->id); ?>" <?php echo e(request('subject_id') == $subject->id ? 'selected' : ''); ?>><?php echo e($subject->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="sb-form-label">Status</label>
                    <select name="status" class="sb-form-select">
                        <option value="">All Status</option>
                        <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>>Draft</option>
                        <option value="published" <?php echo e(request('status') == 'published' ? 'selected' : ''); ?>>Published</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="sb-btn sb-btn-primary w-100">Filter</button>
                </div>
                <?php if(request()->hasAny(['search', 'class_id', 'subject_id', 'status'])): ?>
                    <div class="col-md-1">
                        <a href="<?php echo e(route('assignments.index')); ?>" class="sb-btn sb-btn-secondary w-100 text-center">Clear</a>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover sb-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <a href="<?php echo e(route('assignments.show', $assignment)); ?>" style="color: #0a1628; font-weight: 600; text-decoration: none;"><?php echo e($assignment->title); ?></a>
                                    <?php if($assignment->total_marks): ?>
                                        <br><small class="text-muted"><?php echo e($assignment->total_marks); ?> marks</small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($assignment->schoolClass->name ?? '—'); ?></td>
                                <td><?php echo e($assignment->subject->name ?? '—'); ?></td>
                                <td><?php echo e($assignment->teacher->full_name ?? '—'); ?></td>
                                <td>
                                    <span class="<?php echo e($assignment->due_date->isPast() ? 'text-danger' : 'text-muted'); ?>">
                                        <?php echo e($assignment->due_date->format('M d, Y')); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($assignment->status === 'published'): ?>
                                        <span class="sb-badge sb-badge-published">Published</span>
                                    <?php else: ?>
                                        <span class="sb-badge sb-badge-draft">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    <a href="<?php echo e(route('assignments.show', $assignment)); ?>" class="sb-btn sb-btn-sm sb-btn-outline-primary">View</a>
                                    <a href="<?php echo e(route('assignments.edit', $assignment)); ?>" class="sb-btn sb-btn-sm sb-btn-secondary">Edit</a>
                                    <form action="<?php echo e(route('assignments.destroy', $assignment)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                    No assignments found. <a href="<?php echo e(route('assignments.create')); ?>" style="color: var(--primary);">Create your first assignment</a>.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($assignments->hasPages()): ?>
                <div class="d-flex justify-content-center mt-3">
                    <?php echo e($assignments->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/assignments/index.blade.php ENDPATH**/ ?>