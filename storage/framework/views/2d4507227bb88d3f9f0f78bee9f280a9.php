<?php $__env->startSection('title', 'Pending Schools - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Pending Schools</h2>
            <p class="mb-0">Review and approve school registration requests</p>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="sb-flash sb-flash-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="sb-flash sb-flash-error">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="card stat-card">
        <div class="card-body">
            <div class="sb-search-bar mb-3">
                <form method="GET" action="<?php echo e(route('pending-schools.index')); ?>" class="d-flex gap-2 flex-wrap" style="width: 100%;">
                    <input type="text" name="search" class="sb-form-input" style="max-width: 300px;"
                           placeholder="Search by name or email..." value="<?php echo e(request('search')); ?>">
                    <button type="submit" class="sb-btn sb-btn-primary sb-btn-sm">Search</button>
                    <?php if(request('search')): ?>
                        <a href="<?php echo e(route('pending-schools.index')); ?>" class="sb-btn sb-btn-secondary sb-btn-sm">Clear</a>
                    <?php endif; ?>
                </form>
            </div>

            <?php if($schools->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table sb-table mb-0">
                        <thead>
                            <tr>
                                <th>School Name</th>
                                <th>Admin</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Registered</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($school->name); ?></strong>
                                        <?php if($school->school_type): ?>
                                            <br><small class="text-muted"><?php echo e($school->school_type); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($school->users()->where('role', 'school_admin')->first()->name ?? '-'); ?></td>
                                    <td><?php echo e($school->email ?? '-'); ?></td>
                                    <td><?php echo e($school->phone ?? '-'); ?></td>
                                    <td><?php echo e($school->registered_at ? $school->registered_at->format('M d, Y') : '-'); ?></td>
                                    <td>
                                        <span class="sb-badge sb-badge-pending">Pending</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="table-actions">
                                            <a href="<?php echo e(route('pending-schools.show', $school)); ?>"
                                               class="sb-btn sb-btn-outline-primary sb-btn-sm" title="View">
                                                View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <?php echo e($schools->links()); ?>

                </div>
            <?php else: ?>
                <div class="sb-empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                    </svg>
                    <h5>No Pending Schools</h5>
                    <p>There are no school registrations waiting for approval.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/super-admin/pending-schools/index.blade.php ENDPATH**/ ?>