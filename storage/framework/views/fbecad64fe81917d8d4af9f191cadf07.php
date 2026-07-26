<?php $__env->startSection('title', 'Subscriptions - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Subscriptions</h2>
            <p class="text-muted mb-0">Manage all school subscriptions</p>
        </div>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('subscriptions.index')); ?>" class="sb-search-bar">
                <input
                    type="text"
                    name="search"
                    value="<?php echo e(request('search')); ?>"
                    placeholder="Search by school name..."
                    class="sb-form-input"
                >
                <select name="status" class="sb-form-select" style="width: auto; min-width: 140px;">
                    <option value="">All Status</option>
                    <option value="trial" <?php echo e(request('status') === 'trial' ? 'selected' : ''); ?>>Trial</option>
                    <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="grace" <?php echo e(request('status') === 'grace' ? 'selected' : ''); ?>>Grace</option>
                    <option value="expired" <?php echo e(request('status') === 'expired' ? 'selected' : ''); ?>>Expired</option>
                    <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                </select>
                <select name="plan_id" class="sb-form-select" style="width: auto; min-width: 140px;">
                    <option value="">All Plans</option>
                    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($plan->id); ?>" <?php echo e(request('plan_id') == $plan->id ? 'selected' : ''); ?>>
                            <?php echo e($plan->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <select name="billing_cycle" class="sb-form-select" style="width: auto; min-width: 140px;">
                    <option value="">All Cycles</option>
                    <option value="monthly" <?php echo e(request('billing_cycle') === 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                    <option value="yearly" <?php echo e(request('billing_cycle') === 'yearly' ? 'selected' : ''); ?>>Yearly</option>
                </select>
                <select name="is_trial" class="sb-form-select" style="width: auto; min-width: 140px;">
                    <option value="">All Types</option>
                    <option value="1" <?php echo e(request('is_trial') === '1' ? 'selected' : ''); ?>>Trial</option>
                    <option value="0" <?php echo e(request('is_trial') === '0' ? 'selected' : ''); ?>>Paid</option>
                </select>
                <button type="submit" class="sb-btn sb-btn-dark">
                    Filter
                </button>
                <?php if(request()->hasAny(['search', 'status', 'plan_id', 'billing_cycle', 'is_trial'])): ?>
                    <a href="<?php echo e(route('subscriptions.index')); ?>" class="sb-btn sb-btn-secondary">
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
                            <th>School</th>
                            <th>Plan</th>
                            <th>Billing</th>
                            <th>Status</th>
                            <th>Trial</th>
                            <th>Amount Paid</th>
                            <th>Expires</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $query; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <strong><?php echo e($subscription->school->name ?? '—'); ?></strong>
                                    <div style="font-size: 12px; color: #6c757d;"><?php echo e($subscription->school->slug ?? ''); ?></div>
                                </td>
                                <td><strong><?php echo e($subscription->plan->name ?? '—'); ?></strong></td>
                                <td>
                                    <span class="sb-badge sb-badge-class">
                                        <?php echo e(ucfirst($subscription->billing_cycle)); ?>

                                    </span>
                                </td>
                                <td>
                                    <span class="sb-badge <?php echo e($subscription->status_badge); ?>">
                                        <?php echo e(ucfirst($subscription->status)); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($subscription->is_trial): ?>
                                        <span class="sb-badge sb-badge-info">Yes</span>
                                    <?php else: ?>
                                        <span class="text-muted">No</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($subscription->formattedAmountPaid()); ?></td>
                                <td>
                                    <?php if($subscription->expires_at): ?>
                                        <?php echo e($subscription->expires_at->format('d M Y')); ?>

                                    <?php elseif($subscription->trial_ends_at): ?>
                                        <?php echo e($subscription->trial_ends_at->format('d M Y')); ?>

                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <div class="table-actions">
                                        <a href="<?php echo e(route('subscriptions.show', $subscription)); ?>" class="sb-btn sb-btn-sm sb-btn-secondary">
                                            View
                                        </a>
                                        <form method="POST" action="<?php echo e(route('subscriptions.destroy', $subscription)); ?>" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this subscription record?');">
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
                                    <p style="margin: 0; font-size: 15px;">No subscriptions found.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if($query->hasPages()): ?>
        <div class="mt-3 d-flex justify-content-center">
            <?php echo e($query->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/subscriptions/index.blade.php ENDPATH**/ ?>