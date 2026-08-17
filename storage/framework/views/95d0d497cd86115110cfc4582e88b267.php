<?php $__env->startSection('title', 'Affiliate Payouts - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2>Affiliate Payouts</h2>
            <p class="mb-0">Review and process payout requests</p>
        </div>
        <a href="<?php echo e(route('affiliates.index')); ?>" class="sb-btn sb-btn-outline-primary sb-btn-sm">Affiliates</a>
    </div>
</div>


<div class="row g-3 g-md-4 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number"><?php echo e(number_format($totals['total'])); ?></p>
            <p class="stat-label">Total Requests</p>
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
            <p class="stat-number" style="color: var(--info);"><?php echo e(number_format($totals['processing'])); ?></p>
            <p class="stat-label">Processing</p>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number" style="color: var(--success);"><?php echo e(number_format($totals['paid'])); ?></p>
            <p class="stat-label">Paid</p>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number" style="color: var(--danger);"><?php echo e(number_format($totals['cancelled'])); ?></p>
            <p class="stat-label">Cancelled</p>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100"><div class="card-body">
            <p class="stat-number">₦<?php echo e(number_format($totals['pending_amount'], 2)); ?></p>
            <p class="stat-label">Pending ₦</p>
        </div></div>
    </div>
</div>

<div class="card stat-card">
    <div class="card-body">
        <div class="sb-search-bar mb-3">
            <form method="GET" action="<?php echo e(route('payouts.index')); ?>" class="d-flex gap-2 flex-wrap" style="width: 100%;">
                <select name="status" class="sb-form-select" style="width: auto;">
                    <option value="">All Statuses</option>
                    <option value="pending" <?php if(request('status') === 'pending'): echo 'selected'; endif; ?>>Pending</option>
                    <option value="processing" <?php if(request('status') === 'processing'): echo 'selected'; endif; ?>>Processing</option>
                    <option value="paid" <?php if(request('status') === 'paid'): echo 'selected'; endif; ?>>Paid</option>
                    <option value="cancelled" <?php if(request('status') === 'cancelled'): echo 'selected'; endif; ?>>Cancelled</option>
                </select>
                <button type="submit" class="sb-btn sb-btn-primary sb-btn-sm">Filter</button>
                <?php if(request('status')): ?>
                    <a href="<?php echo e(route('payouts.index')); ?>" class="sb-btn sb-btn-secondary sb-btn-sm">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <?php if($payouts->count() > 0): ?>
            <div class="table-responsive">
                <table class="table sb-table mb-0">
                    <thead>
                        <tr>
                            <th>Affiliate</th>
                            <th>Requested</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $payouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <strong><?php echo e($payout->affiliate->name ?? 'Unknown'); ?></strong>
                                    <br><small class="text-muted"><?php echo e($payout->affiliate->email ?? ''); ?></small>
                                </td>
                                <td><?php echo e($payout->requested_at ? $payout->requested_at->format('M d, Y') : '-'); ?></td>
                                <td>
                                    <?php echo e(ucfirst(str_replace('_', ' ', $payout->method))); ?>

                                    <?php if($payout->method === 'bank_transfer' && $payout->payout_details): ?>
                                        <br><small class="text-muted"><?php echo e($payout->payout_details['bank_name'] ?? ''); ?> · <?php echo e($payout->payout_details['account_name'] ?? ''); ?> · <?php echo e($payout->payout_details['account_number'] ?? ''); ?></small>
                                    <?php elseif($payout->method === 'cash'): ?>
                                        <br><small class="text-muted">Cash pickup</small>
                                    <?php elseif($payout->payout_details): ?>
                                        <br><small class="text-muted"><?php echo e($payout->payout_details['email'] ?? $payout->payout_details['wallet'] ?? $payout->payout_details['details'] ?? ''); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($payout->formattedAmount()); ?></td>
                                <td>
                                    <span class="sb-badge <?php echo e($payout->status_badge); ?>"><?php echo e(ucfirst($payout->status)); ?></span>
                                </td>
                                <td class="text-end">
                                    <?php if($payout->isPending()): ?>
                                        <div class="table-actions">
                                            <form method="POST" action="<?php echo e(route('payouts.approve', $payout)); ?>" class="d-inline"
                                                  onsubmit="return confirm('Mark this payout as approved (paid out)?');">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="sb-btn sb-btn-outline-success sb-btn-sm" title="Approve">Approve</button>
                                            </form>
                                            <form method="POST" action="<?php echo e(route('payouts.reject', $payout)); ?>" class="d-inline"
                                                  onsubmit="return confirm('Reject this payout request?');">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="sb-btn sb-btn-outline-danger sb-btn-sm" title="Reject">Reject</button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <?php if($payout->notes): ?>
                                            <small class="text-muted"><?php echo e($payout->notes); ?></small>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <?php echo e($payouts->links()); ?>

            </div>
        <?php else: ?>
            <div class="sb-empty-state">
                <h5>No Payout Requests</h5>
                <p>Payout requests from affiliates will appear here.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/super-admin/payouts/index.blade.php ENDPATH**/ ?>