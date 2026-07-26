<?php $__env->startSection('title', 'Classes - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Classes</h2>
            <p>Manage all classes and sections</p>
        </div>
        <a href="<?php echo e(route('classes.create')); ?>" class="sb-btn sb-btn-primary">+ Add Class</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('classes.index')); ?>" class="sb-search-bar">
                <input
                    type="text"
                    name="search"
                    value="<?php echo e(request('search')); ?>"
                    placeholder="Search by name or section..."
                    class="sb-form-input"
                    style="max-width: 400px;"
                >
                <button type="submit" class="sb-btn sb-btn-dark">Search</button>
                <?php if(request('search')): ?>
                    <a href="<?php echo e(route('classes.index')); ?>" class="sb-btn sb-btn-secondary">Clear</a>
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
                            <th>Section</th>
                            <th>School</th>
                            <th>Students</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $schoolClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <a href="<?php echo e(route('classes.show', $class)); ?>" style="color: #333; text-decoration: none; font-weight: 500;">
                                        <?php echo e($class->name); ?>

                                    </a>
                                </td>
                                <td style="color: #6c757d;"><?php echo e($class->section ?? '—'); ?></td>
                                <td style="color: #6c757d;"><?php echo e($class->school->name ?? '—'); ?></td>
                                <td>
                                    <a href="<?php echo e(route('classes.show', $class)); ?>" style="text-decoration: none;">
                                        <span class="sb-badge sb-badge-info">
                                            <?php echo e($class->students_count); ?> <?php echo e(Str::plural('student', $class->students_count)); ?>

                                        </span>
                                    </a>
                                </td>
                                <td>
                                    <?php if($class->status): ?>
                                        <span class="sb-badge sb-badge-active">Active</span>
                                    <?php else: ?>
                                        <span class="sb-badge sb-badge-inactive">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="table-actions">
                                        <form method="POST" action="<?php echo e(route('classes.toggle-status', $class)); ?>" style="margin: 0;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="sb-btn sb-btn-sm <?php echo e($class->status ? 'sb-btn-outline-warning' : 'sb-btn-outline-success'); ?>">
                                                <?php echo e($class->status ? 'Deactivate' : 'Activate'); ?>

                                            </button>
                                        </form>
                                        <a href="<?php echo e(route('classes.edit', $class)); ?>" class="sb-btn sb-btn-sm sb-btn-outline-primary">Edit</a>
                                        <form method="POST" action="<?php echo e(route('classes.destroy', $class)); ?>" style="margin: 0;" onsubmit="return confirm('Are you sure?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6">
                                    <div class="sb-empty-state">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                                        </svg>
                                        <h5>No classes found</h5>
                                        <p>Get started by creating your first class.</p>
                                        <a href="<?php echo e(route('classes.create')); ?>">+ Add Class</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if($schoolClasses->hasPages()): ?>
        <div class="mt-3" style="display: flex; justify-content: center;">
            <?php echo e($schoolClasses->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/classes/index.blade.php ENDPATH**/ ?>