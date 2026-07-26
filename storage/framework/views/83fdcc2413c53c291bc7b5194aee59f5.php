<?php $__env->startSection('title', 'Periods - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Periods</h2>
            <p class="text-muted mb-0">Manage school timetable periods</p>
        </div>
        <a href="<?php echo e(route('periods.create')); ?>" class="sb-btn sb-btn-primary">
            + Add Period
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('periods.index')); ?>" class="sb-search-bar">
                <input
                    type="text"
                    name="search"
                    value="<?php echo e(request('search')); ?>"
                    placeholder="Search by name or type..."
                    class="sb-form-input"
                >
                <select
                    name="school_id"
                    class="sb-form-select"
                >
                    <option value="">All Schools</option>
                    <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($school->id); ?>" <?php echo e(request('school_id') == $school->id ? 'selected' : ''); ?>>
                            <?php echo e($school->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button type="submit" class="sb-btn sb-btn-dark">
                    Search
                </button>
                <?php if(request('search') || request('school_id')): ?>
                    <a href="<?php echo e(route('periods.index')); ?>" class="sb-btn sb-btn-secondary">
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
                            <th>Order</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Time</th>
                            <th>Duration</th>
                            <th>School</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td style="font-weight: 500;"><?php echo e($period->sort_order); ?></td>
                                <td style="font-weight: 500;"><?php echo e($period->name); ?></td>
                                <td>
                                    <?php
                                        $typeColors = [
                                            'academic' => ['bg' => '#e7f1ff', 'text' => '#0d6efd'],
                                            'break' => ['bg' => '#fff3cd', 'text' => '#664d03'],
                                            'lunch' => ['bg' => '#d1e7dd', 'text' => '#0f5132'],
                                            'assembly' => ['bg' => '#f0d9ff', 'text' => '#6f42c1'],
                                            'other' => ['bg' => '#f0f2f5', 'text' => '#6c757d'],
                                        ];
                                        $colors = $typeColors[$period->type] ?? $typeColors['other'];
                                    ?>
                                    <span class="sb-badge" style="background: <?php echo e($colors['bg']); ?>; color: <?php echo e($colors['text']); ?>;">
                                        <?php echo e(ucfirst($period->type)); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php echo e($period->start_time->format('h:i A')); ?> - <?php echo e($period->end_time->format('h:i A')); ?>

                                </td>
                                <td style="color: #6c757d;">
                                    <?php echo e($period->duration_minutes); ?> min
                                </td>
                                <td style="color: #6c757d;"><?php echo e($period->school->name ?? '—'); ?></td>
                                <td>
                                    <?php if($period->status): ?>
                                        <span class="sb-badge sb-badge-active">Active</span>
                                    <?php else: ?>
                                        <span class="sb-badge sb-badge-inactive">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="table-actions">
                                        <form method="POST" action="<?php echo e(route('periods.toggle-status', $period)); ?>" style="margin: 0;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="sb-btn sb-btn-sm <?php echo e($period->status ? 'sb-btn-outline-warning' : 'sb-btn-outline-success'); ?>">
                                                <?php echo e($period->status ? 'Deactivate' : 'Activate'); ?>

                                            </button>
                                        </form>
                                        <a href="<?php echo e(route('periods.show', $period)); ?>" class="sb-btn sb-btn-sm sb-btn-secondary d-md-inline-flex d-none">
                                            View
                                        </a>
                                        <a href="<?php echo e(route('periods.edit', $period)); ?>" class="sb-btn sb-btn-sm sb-btn-outline-primary">
                                            Edit
                                        </a>
                                        <form method="POST" action="<?php echo e(route('periods.destroy', $period)); ?>" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this period?');">
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
                                <td colspan="8">
                                    <div class="sb-empty-state">
                                        <p style="margin: 0; font-size: 15px;">No periods found.</p>
                                        <a href="<?php echo e(route('periods.create')); ?>">Add your first period</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if($periods->hasPages()): ?>
        <div class="mt-3" style="display: flex; justify-content: center;">
            <?php echo e($periods->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/periods/index.blade.php ENDPATH**/ ?>