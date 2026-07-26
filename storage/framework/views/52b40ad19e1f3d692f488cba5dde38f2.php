<?php $__env->startSection('title', 'Parents - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Parents</h2>
            <p class="text-muted mb-0">Manage parent accounts and children linking</p>
        </div>
        <a href="<?php echo e(route('parents.create')); ?>" class="sb-btn sb-btn-primary">
            Add Parent
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body" style="padding: 16px 24px;">
            <form method="GET" action="<?php echo e(route('parents.index')); ?>">
                <div class="d-flex gap-2">
                    <input
                        type="text"
                        name="search"
                        value="<?php echo e(request('search')); ?>"
                        placeholder="Search by name, email, or phone..."
                        class="sb-form-input"
                        style="max-width: 400px;"
                    >
                    <button type="submit" class="sb-btn sb-btn-primary">Search</button>
                    <?php if(request('search')): ?>
                        <a href="<?php echo e(route('parents.index')); ?>" class="sb-btn sb-btn-secondary">Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body" style="padding: 0;">
            <?php if($parents->count()): ?>
                <div style="overflow-x: auto;">
                    <table class="sb-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Children</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e(route('parents.show', $parent)); ?>" style="font-weight: 500; color: #333; text-decoration: none;">
                                            <?php echo e($parent->full_name); ?>

                                        </a>
                                    </td>
                                    <td><?php echo e($parent->email ?? '—'); ?></td>
                                    <td><?php echo e($parent->phone ?? '—'); ?></td>
                                    <td>
                                        <span class="sb-badge sb-badge-info">
                                            <?php echo e($parent->children->count()); ?> <?php echo e(Str::plural('child', $parent->children->count())); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="sb-badge <?php echo e($parent->status ? 'sb-badge-active' : 'sb-badge-inactive'); ?>">
                                            <?php echo e($parent->status ? 'Active' : 'Inactive'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="<?php echo e(route('parents.edit', $parent)); ?>" class="sb-btn sb-btn-sm sb-btn-outline-primary">Edit</a>
                                            <form method="POST" action="<?php echo e(route('parents.destroy', $parent)); ?>" onsubmit="return confirm('Are you sure you want to delete this parent?');" style="margin: 0;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div style="padding: 16px;">
                    <?php echo e($parents->links()); ?>

                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 48px 16px; color: #6c757d;">
                    <p style="margin: 0; font-size: 16px;">No parents found.</p>
                    <a href="<?php echo e(route('parents.create')); ?>" class="sb-btn sb-btn-primary mt-3">Add First Parent</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/parents/index.blade.php ENDPATH**/ ?>