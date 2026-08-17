<?php $__env->startSection('title', 'Affiliate Details - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2><?php echo e($affiliate->name); ?></h2>
            <p class="mb-0"><?php echo e($affiliate->email); ?><?php echo e($affiliate->phone ? ' · '.$affiliate->phone : ''); ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('affiliates.index')); ?>" class="sb-btn sb-btn-outline-secondary sb-btn-sm">Back</a>
            <?php if($affiliate->isPending() || $affiliate->isSuspended()): ?>
                <form method="POST" action="<?php echo e(route('affiliates.activate', $affiliate)); ?>" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="sb-btn sb-btn-outline-success sb-btn-sm">Activate</button>
                </form>
            <?php endif; ?>
            <?php if($affiliate->isActive()): ?>
                <form method="POST" action="<?php echo e(route('affiliates.suspend', $affiliate)); ?>" class="d-inline" onsubmit="return confirm('Suspend this affiliate? Their referral codes will stop working.');">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="sb-btn sb-btn-outline-warning sb-btn-sm">Suspend</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card stat-card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <p class="mb-1 text-muted small">Status</p>
                <?php
                    $badge = match ($affiliate->status) {
                        'active' => 'sb-badge-active',
                        'pending' => 'sb-badge-pending',
                        'suspended' => 'sb-badge-inactive',
                        default => 'sb-badge-info',
                    };
                ?>
                <span class="sb-badge <?php echo e($badge); ?>"><?php echo e(ucfirst($affiliate->status)); ?></span>
            </div>
            <div class="col-md-3">
                <p class="mb-1 text-muted small">Referral Code</p>
                <span class="sb-badge sb-badge-info"><?php echo e($affiliate->code); ?></span>
            </div>
            <div class="col-md-3">
                <p class="mb-1 text-muted small">Commission Rate</p>
                <strong><?php echo e(number_format($affiliate->effectiveCommissionRate(), 2)); ?>%</strong>
            </div>
            <div class="col-md-3">
                <p class="mb-1 text-muted small">Total Clicks</p>
                <strong><?php echo e(number_format($affiliate->clicks)); ?></strong>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 g-md-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number">₦<?php echo e(number_format($summary['total_earned'], 2)); ?></p>
            <p class="stat-label">Total Earned</p>
        </div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number">₦<?php echo e(number_format($summary['pending'], 2)); ?></p>
            <p class="stat-label">Pending</p>
        </div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number">₦<?php echo e(number_format($summary['approved'], 2)); ?></p>
            <p class="stat-label">Approved</p>
        </div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number">₦<?php echo e(number_format($summary['paid'], 2)); ?></p>
            <p class="stat-label">Paid</p>
        </div></div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card action-card mb-4">
            <div class="card-header">Referred Schools</div>
            <div class="card-body">
                <?php if($referrals->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table sb-table mb-0">
                            <thead>
                                <tr>
                                    <th>School</th>
                                    <th>Status</th>
                                    <th>First Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $referrals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $referral): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($referral->school->name ?? 'Pending'); ?></strong>
                                            <br><small class="text-muted"><?php echo e($referral->referred_email); ?></small>
                                        </td>
                                        <td>
                                            <?php
                                                $refBadge = match ($referral->status) {
                                                    'registered' => 'sb-badge-pending',
                                                    'approved' => 'sb-badge-info',
                                                    'converted' => 'sb-badge-active',
                                                    'expired', 'cancelled' => 'sb-badge-inactive',
                                                    default => 'sb-badge-info',
                                                };
                                            ?>
                                            <span class="sb-badge <?php echo e($refBadge); ?>"><?php echo e(ucfirst($referral->status)); ?></span>
                                        </td>
                                        <td><?php echo e($referral->first_paid_at ? $referral->first_paid_at->format('M d, Y') : '-'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3"><?php echo e($referrals->links()); ?></div>
                <?php else: ?>
                    <div class="sb-empty-state"><h5>No Referrals</h5><p>This affiliate has not referred any schools yet.</p></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card action-card mb-4">
            <div class="card-header">Commissions</div>
            <div class="card-body">
                <?php if($commissions->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table sb-table mb-0">
                            <thead>
                                <tr>
                                    <th>School</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $commissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($commission->referral->school->name ?? 'Unknown'); ?></strong>
                                            <br><small class="text-muted"><?php echo e(ucwords(str_replace('_', ' ', $commission->type))); ?> · <?php echo e($commission->paid_period); ?></small>
                                        </td>
                                        <td><?php echo e($commission->formattedAmount()); ?></td>
                                        <td>
                                            <span class="sb-badge <?php echo e($commission->status_badge); ?>"><?php echo e(ucfirst($commission->status)); ?></span>
                                        </td>
                                        <td class="text-end">
                                            <?php if($commission->isPending()): ?>
                                                <div class="table-actions">
                                                    <form method="POST" action="<?php echo e(route('affiliates.commissions.approve', [$affiliate, $commission])); ?>" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="sb-btn sb-btn-outline-success sb-btn-sm" title="Approve">Approve</button>
                                                    </form>
                                                    <form method="POST" action="<?php echo e(route('affiliates.commissions.cancel', [$affiliate, $commission])); ?>" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="sb-btn sb-btn-outline-danger sb-btn-sm" title="Cancel">Cancel</button>
                                                    </form>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted small">—</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3"><?php echo e($commissions->links()); ?></div>
                <?php else: ?>
                    <div class="sb-empty-state"><h5>No Commissions</h5><p>Commissions appear once referred schools start paying.</p></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card action-card mb-4">
    <div class="card-header">Payout Requests</div>
    <div class="card-body">
        <?php if($payouts->count() > 0): ?>
            <div class="table-responsive">
                <table class="table sb-table mb-0">
                    <thead>
                        <tr>
                            <th>Requested</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $payouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($payout->requested_at ? $payout->requested_at->format('M d, Y') : '-'); ?></td>
                                <td><?php echo e($payout->method); ?></td>
                                <td><?php echo e($payout->formattedAmount()); ?></td>
                                <td>
                                    <span class="sb-badge <?php echo e($payout->status_badge); ?>"><?php echo e(ucfirst($payout->status)); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-3"><?php echo e($payouts->links()); ?></div>
        <?php else: ?>
            <div class="sb-empty-state"><h5>No Payouts</h5><p>This affiliate has not requested any payouts.</p></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/super-admin/affiliates/show.blade.php ENDPATH**/ ?>