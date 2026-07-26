<?php $__env->startSection('title', 'Schools - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Schools</h2>
            <p class="text-muted mb-0">Manage all registered schools</p>
        </div>
        <a href="<?php echo e(route('schools.create')); ?>" class="sb-btn sb-btn-primary">
            + Add School
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('schools.index')); ?>" class="sb-search-bar">
                <input
                    type="text"
                    name="search"
                    value="<?php echo e(request('search')); ?>"
                    placeholder="Search by name, email or slug..."
                    class="sb-form-input"
                >
                <button type="submit" class="sb-btn sb-btn-dark">
                    Search
                </button>
                <?php if(request('search')): ?>
                    <a href="<?php echo e(route('schools.index')); ?>" class="sb-btn sb-btn-secondary">
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
                            <th>Slug</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($school->name); ?></strong></td>
                                <td>
                                    <code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;"><?php echo e($school->slug); ?></code>
                                </td>
                                <td class="text-muted"><?php echo e($school->email ?? '—'); ?></td>
                                <td class="text-muted"><?php echo e($school->phone ?? '—'); ?></td>
                                <td>
                                    <?php if($school->is_active): ?>
                                        <span class="sb-badge sb-badge-active">Active</span>
                                    <?php else: ?>
                                        <span class="sb-badge sb-badge-inactive">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <div class="table-actions">
                                        <a href="<?php echo e(route('schools.edit', $school)); ?>" class="sb-btn sb-btn-sm sb-btn-outline-primary">
                                            Edit
                                        </a>
                                        <form method="POST" action="<?php echo e(route('schools.toggle-status', $school)); ?>" style="margin: 0;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="sb-btn sb-btn-sm <?php echo e($school->is_active ? 'sb-btn-outline-warning' : 'sb-btn-outline-success'); ?>">
                                                <?php echo e($school->is_active ? 'Deactivate' : 'Activate'); ?>

                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('schools.destroy', $school)); ?>" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this school?');">
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
                                    <p style="margin: 0; font-size: 15px;">No schools found.</p>
                                    <a href="<?php echo e(route('schools.create')); ?>" style="color: var(--primary); font-weight: 500; text-decoration: none;">Add your first school</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if($schools->hasPages()): ?>
        <div class="mt-3 d-flex justify-content-center">
            <?php echo e($schools->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/schools/index.blade.php ENDPATH**/ ?>