<?php $__env->startSection('title', 'Subjects - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Subjects</h2>
            <p class="text-muted mb-0">Manage all subjects</p>
        </div>
        <a href="<?php echo e(route('subjects.create')); ?>" class="sb-btn sb-btn-primary">
            + Add Subject
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('subjects.index')); ?>" class="sb-search-bar">
                <input
                    type="text"
                    name="search"
                    value="<?php echo e(request('search')); ?>"
                    placeholder="Search by name or code..."
                    class="sb-form-input"
                >
                <button type="submit" class="sb-btn sb-btn-dark">
                    Search
                </button>
                <?php if(request('search')): ?>
                    <a href="<?php echo e(route('subjects.index')); ?>" class="sb-btn sb-btn-secondary">
                        Clear
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover sb-table mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>School</th>
                            <th>Classes</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($subject->name); ?></strong></td>
                                <td>
                                    <?php if($subject->code): ?>
                                        <code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;"><?php echo e($subject->code); ?></code>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted"><?php echo e($subject->school->name ?? '—'); ?></td>
                                <td>
                                    <?php if($subject->schoolClasses->count()): ?>
                                        <?php $__currentLoopData = $subject->schoolClasses->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="sb-badge sb-badge-class">
                                                <?php echo e($class->name); ?><?php echo e($class->section ? ' (' . $class->section . ')' : ''); ?>

                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($subject->schoolClasses->count() > 3): ?>
                                            <span class="sb-badge sb-badge-info">
                                                +<?php echo e($subject->schoolClasses->count() - 3); ?> more
                                            </span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($subject->status): ?>
                                        <span class="sb-badge sb-badge-active">Active</span>
                                    <?php else: ?>
                                        <span class="sb-badge sb-badge-inactive">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <div class="table-actions">
                                        <form method="POST" action="<?php echo e(route('subjects.toggle-status', $subject)); ?>" style="margin: 0;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="sb-btn sb-btn-sm <?php echo e($subject->status ? 'sb-btn-outline-warning' : 'sb-btn-outline-success'); ?>">
                                                <?php echo e($subject->status ? 'Deactivate' : 'Activate'); ?>

                                            </button>
                                        </form>
                                        <a href="<?php echo e(route('subjects.show', $subject)); ?>" class="sb-btn sb-btn-sm sb-btn-secondary d-md-inline-flex d-none">
                                            View
                                        </a>
                                        <a href="<?php echo e(route('subjects.edit', $subject)); ?>" class="sb-btn sb-btn-sm sb-btn-outline-primary">
                                            Edit
                                        </a>
                                        <form method="POST" action="<?php echo e(route('subjects.destroy', $subject)); ?>" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this subject?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                    <p style="margin: 0; font-size: 15px;">No subjects found.</p>
                                    <a href="<?php echo e(route('subjects.create')); ?>" style="color: var(--primary); font-weight: 500; text-decoration: none;">Add your first subject</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if($subjects->hasPages()): ?>
        <div class="mt-3 d-flex justify-content-center">
            <?php echo e($subjects->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/subjects/index.blade.php ENDPATH**/ ?>