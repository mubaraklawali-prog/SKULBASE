<?php $__env->startSection('title', 'Plans - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Pricing Plans</h2>
            <p class="text-muted mb-0">Manage subscription plans and pricing</p>
        </div>
        <a href="<?php echo e(route('plans.create')); ?>" class="sb-btn sb-btn-primary">
            + Add Plan
        </a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('plans.index')); ?>" class="sb-search-bar">
                <input
                    type="text"
                    name="search"
                    value="<?php echo e(request('search')); ?>"
                    placeholder="Search by name or slug..."
                    class="sb-form-input"
                >
                <button type="submit" class="sb-btn sb-btn-dark">
                    Search
                </button>
                <?php if(request('search')): ?>
                    <a href="<?php echo e(route('plans.index')); ?>" class="sb-btn sb-btn-secondary">
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
                            <th>Plan Name</th>
                            <th>Monthly Price</th>
                            <th>Yearly Price</th>
                            <th>Student Limit</th>
                            <th>Trial</th>
                            <th>Discount</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <strong><?php echo e($plan->name); ?></strong>
                                    <div style="font-size: 12px; color: #6c757d;"><?php echo e($plan->slug); ?></div>
                                </td>
                                <td>
                                    <?php if($plan->isDiscountActive() && in_array($plan->discount_scope, ['monthly', 'both'])): ?>
                                        <span style="text-decoration: line-through; color: #999; font-size: 12px;"><?php echo e($plan->formattedMonthlyPrice()); ?></span>
                                        <br>
                                        <strong style="color: #dc3545;"><?php echo e($plan->formattedDiscountedMonthlyPrice()); ?></strong>
                                    <?php else: ?>
                                        <strong><?php echo e($plan->formattedMonthlyPrice()); ?></strong>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($plan->isDiscountActive() && in_array($plan->discount_scope, ['annual', 'both'])): ?>
                                        <span style="text-decoration: line-through; color: #999; font-size: 12px;"><?php echo e($plan->formattedYearlyPrice()); ?></span>
                                        <br>
                                        <strong style="color: #dc3545;"><?php echo e($plan->formattedDiscountedYearlyPrice()); ?></strong>
                                    <?php else: ?>
                                        <strong><?php echo e($plan->formattedYearlyPrice()); ?></strong>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($plan->is_unlimited): ?>
                                        <span class="sb-badge sb-badge-info">Unlimited</span>
                                    <?php else: ?>
                                        <?php echo e(number_format($plan->student_limit ?? 0)); ?>

                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($plan->trial_days); ?>d</td>
                                <td>
                                    <?php if($plan->isDiscountActive()): ?>
                                        <span class="sb-badge sb-badge-active"><?php echo e($plan->discount_percentage); ?>% off</span>
                                        <div style="font-size: 11px; color: #6c757d; margin-top: 2px;"><?php echo e($plan->discount_scope_label); ?></div>
                                    <?php else: ?>
                                        <span style="color: #999;">None</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($plan->is_active): ?>
                                        <span class="sb-badge sb-badge-active">Active</span>
                                    <?php else: ?>
                                        <span class="sb-badge sb-badge-inactive">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <div class="table-actions">
                                        <form method="POST" action="<?php echo e(route('plans.toggle-status', $plan)); ?>" style="margin: 0;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="sb-btn sb-btn-sm <?php echo e($plan->is_active ? 'sb-btn-outline-warning' : 'sb-btn-outline-success'); ?>">
                                                <?php echo e($plan->is_active ? 'Deactivate' : 'Activate'); ?>

                                            </button>
                                        </form>
                                        <a href="<?php echo e(route('plans.show', $plan)); ?>" class="sb-btn sb-btn-sm sb-btn-secondary d-md-inline-flex d-none">
                                            View
                                        </a>
                                        <a href="<?php echo e(route('plans.edit', $plan)); ?>" class="sb-btn sb-btn-sm sb-btn-outline-primary">
                                            Edit
                                        </a>
                                        <form method="POST" action="<?php echo e(route('plans.destroy', $plan)); ?>" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this plan?');">
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
                                <td colspan="8" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                    <p style="margin: 0; font-size: 15px;">No plans found.</p>
                                    <a href="<?php echo e(route('plans.create')); ?>" style="color: var(--primary); font-weight: 500; text-decoration: none;">Add your first plan</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if($plans->hasPages()): ?>
        <div class="mt-3 d-flex justify-content-center">
            <?php echo e($plans->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/plans/index.blade.php ENDPATH**/ ?>