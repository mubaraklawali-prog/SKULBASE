<?php $__env->startSection('title', 'Affiliates - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2>Affiliates</h2>
            <p class="mb-0">Manage referral partners and their commissions</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('affiliates.settings')); ?>" class="sb-btn sb-btn-outline-primary sb-btn-sm">Program Settings</a>
            <a href="<?php echo e(route('payouts.index')); ?>" class="sb-btn sb-btn-outline-primary sb-btn-sm">Payouts</a>
        </div>
    </div>
</div>


<div class="row g-3 g-md-4 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number"><?php echo e(number_format($totals['total'])); ?></p>
            <p class="stat-label">Total</p>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number" style="color: var(--success);"><?php echo e(number_format($totals['active'])); ?></p>
            <p class="stat-label">Active</p>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number" style="color: var(--warning);"><?php echo e(number_format($totals['pending'])); ?></p>
            <p class="stat-label">Pending</p>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number" style="color: var(--danger);"><?php echo e(number_format($totals['suspended'])); ?></p>
            <p class="stat-label">Suspended</p>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number"><?php echo e(number_format($totals['pending_commissions'])); ?></p>
            <p class="stat-label">Pending Commissions</p>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number"><?php echo e(number_format($totals['pending_payouts'])); ?></p>
            <p class="stat-label">Pending Payouts</p>
        </div></div>
    </div>
</div>

<div class="card stat-card">
    <div class="card-body">
        <div class="sb-search-bar mb-3">
            <form method="GET" action="<?php echo e(route('affiliates.index')); ?>" class="d-flex gap-2 flex-wrap" style="width: 100%;">
                <input type="text" name="search" class="sb-form-input" style="max-width: 300px;"
                       placeholder="Search by name, email, or code..." value="<?php echo e(request('search')); ?>">
                <select name="status" class="sb-form-select" style="width: auto;">
                    <option value="">All Statuses</option>
                    <option value="pending" <?php if(request('status') === 'pending'): echo 'selected'; endif; ?>>Pending</option>
                    <option value="active" <?php if(request('status') === 'active'): echo 'selected'; endif; ?>>Active</option>
                    <option value="suspended" <?php if(request('status') === 'suspended'): echo 'selected'; endif; ?>>Suspended</option>
                </select>
                <button type="submit" class="sb-btn sb-btn-primary sb-btn-sm">Filter</button>
                <?php if(request('search') || request('status')): ?>
                    <a href="<?php echo e(route('affiliates.index')); ?>" class="sb-btn sb-btn-secondary sb-btn-sm">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <?php if($affiliates->count() > 0): ?>
            <div class="table-responsive">
                <table class="table sb-table mb-0">
                    <thead>
                        <tr>
                            <th>Affiliate</th>
                            <th>Code</th>
                            <th>Status</th>
                            <th>Referrals</th>
                            <th>Commissions</th>
                            <th>Pending Amount</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $affiliates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $affiliate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <strong><?php echo e($affiliate->name); ?></strong>
                                    <br><small class="text-muted"><?php echo e($affiliate->email); ?></small>
                                </td>
                                <td>
                                    <span class="sb-badge sb-badge-info"><?php echo e($affiliate->code); ?></span>
                                    <br><small class="text-muted"><?php echo e(number_format($affiliate->clicks)); ?> clicks</small>
                                </td>
                                <td>
                                    <?php
                                        $badge = match ($affiliate->status) {
                                            'active' => 'sb-badge-active',
                                            'pending' => 'sb-badge-pending',
                                            'suspended' => 'sb-badge-inactive',
                                            default => 'sb-badge-info',
                                        };
                                    ?>
                                    <span class="sb-badge <?php echo e($badge); ?>"><?php echo e(ucfirst($affiliate->status)); ?></span>
                                </td>
                                <td><?php echo e(number_format($affiliate->referrals_count)); ?></td>
                                <td><?php echo e(number_format($affiliate->commissions_count)); ?></td>
                                <td>₦<?php echo e(number_format((float) ($affiliate->pending_commission_sum ?? 0), 2)); ?></td>
                                <td class="text-end">
                                    <div class="table-actions">
                                        <a href="<?php echo e(route('affiliates.show', $affiliate)); ?>" class="sb-btn sb-btn-outline-primary sb-btn-sm" title="View">View</a>
                                        <?php if($affiliate->isPending() || $affiliate->isSuspended()): ?>
                                            <form method="POST" action="<?php echo e(route('affiliates.activate', $affiliate)); ?>" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="sb-btn sb-btn-outline-success sb-btn-sm" title="Activate">Activate</button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if($affiliate->isActive()): ?>
                                            <form method="POST" action="<?php echo e(route('affiliates.suspend', $affiliate)); ?>" class="d-inline" onsubmit="return confirm('Suspend this affiliate? Their referral codes will stop working.');">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="sb-btn sb-btn-outline-warning sb-btn-sm" title="Suspend">Suspend</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <?php echo e($affiliates->links()); ?>

            </div>
        <?php else: ?>
            <div class="sb-empty-state">
                <h5>No Affiliates Found</h5>
                <p>Affiliates appear here once they register through the public affiliate signup form.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/super-admin/affiliates/index.blade.php ENDPATH**/ ?>