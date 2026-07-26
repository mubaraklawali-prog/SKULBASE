<?php $__env->startSection('title', 'Teachers - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Teachers</h2>
            <p>Manage all teachers</p>
        </div>
        <a href="<?php echo e(route('teachers.create')); ?>" class="sb-btn sb-btn-primary">+ Add Teacher</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('teachers.index')); ?>" class="sb-search-bar">
                <input
                    type="text"
                    name="search"
                    value="<?php echo e(request('search')); ?>"
                    placeholder="Search by name, email or phone..."
                    class="sb-form-input"
                    style="max-width: 400px;"
                >
                <button type="submit" class="sb-btn sb-btn-dark">Search</button>
                <?php if(request('search')): ?>
                    <a href="<?php echo e(route('teachers.index')); ?>" class="sb-btn sb-btn-secondary">Clear</a>
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
                            <th>Teacher</th>
                            <th>School</th>
                            <th>Phone</th>
                            <th>Subjects</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width: 36px; height: 36px; border-radius: 50%; overflow: hidden; flex-shrink: 0; background: #e7f1ff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px; color: #0d6efd;">
                                            <?php echo e(substr($teacher->first_name, 0, 1)); ?><?php echo e(substr($teacher->last_name, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <div style="font-weight: 500;"><?php echo e($teacher->full_name); ?></div>
                                            <div style="font-size: 12px; color: #6c757d;"><?php echo e($teacher->email ?? '—'); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="color: #6c757d;"><?php echo e($teacher->school->name ?? '—'); ?></td>
                                <td style="color: #6c757d;"><?php echo e($teacher->phone); ?></td>
                                <td>
                                    <?php if($teacher->subjects->count()): ?>
                                        <?php $__currentLoopData = $teacher->subjects->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="sb-badge-tag"><?php echo e($subject->name); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($teacher->subjects->count() > 2): ?>
                                            <span class="sb-badge-count">+<?php echo e($teacher->subjects->count() - 2); ?></span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span style="color: #6c757d;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($teacher->status): ?>
                                        <span class="sb-badge sb-badge-active">Active</span>
                                    <?php else: ?>
                                        <span class="sb-badge sb-badge-inactive">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="table-actions">
                                        <form method="POST" action="<?php echo e(route('teachers.toggle-status', $teacher)); ?>" style="margin: 0;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="sb-btn sb-btn-sm <?php echo e($teacher->status ? 'sb-btn-outline-warning' : 'sb-btn-outline-success'); ?>">
                                                <?php echo e($teacher->status ? 'Deactivate' : 'Activate'); ?>

                                            </button>
                                        </form>
                                        <a href="<?php echo e(route('teachers.show', $teacher)); ?>" class="sb-btn sb-btn-sm sb-btn-secondary d-md-inline-flex d-none">View</a>
                                        <a href="<?php echo e(route('teachers.edit', $teacher)); ?>" class="sb-btn sb-btn-sm sb-btn-outline-primary">Edit</a>
                                        <form method="POST" action="<?php echo e(route('teachers.destroy', $teacher)); ?>" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this teacher?');">
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
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                            <line x1="8" y1="7" x2="16" y2="7"></line>
                                            <line x1="8" y1="11" x2="14" y2="11"></line>
                                        </svg>
                                        <h5>No teachers found</h5>
                                        <p>Get started by adding your first teacher.</p>
                                        <a href="<?php echo e(route('teachers.create')); ?>">+ Add Teacher</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if($teachers->hasPages()): ?>
        <div class="mt-3" style="display: flex; justify-content: center;">
            <?php echo e($teachers->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/teachers/index.blade.php ENDPATH**/ ?>