<?php $__env->startSection('title', 'My Subscription - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="mb-4">
        <h2>My Subscription</h2>
        <p class="text-muted mb-0">Manage your school's subscription and billing</p>
    </div>

    <?php if($subscription && $subscription->isTrial()): ?>
        <div class="alert alert-info d-flex align-items-center mb-4" style="border-radius: 10px; border-left: 4px solid var(--primary); background: #f0f7ff;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px; flex-shrink: 0;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <div>
                <strong>Free Trial Active</strong> — You have <strong><?php echo e($subscription->daysRemaining()); ?> days</strong> remaining in your free trial. No payment required during the trial period.
            </div>
        </div>
    <?php endif; ?>

    <?php if($subscription && $subscription->isGrace()): ?>
        <div class="alert alert-warning d-flex align-items-center mb-4" style="border-radius: 10px; border-left: 4px solid #ffc107; background: #fff8e1;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffc107" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px; flex-shrink: 0;">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
            <div>
                <strong>Your trial has expired.</strong> Renew your subscription to continue using Skulbase. You have <strong><?php echo e($subscription->daysRemaining()); ?> days</strong> remaining in your grace period.
            </div>
        </div>
    <?php endif; ?>

    <?php if($subscription && $subscription->isActive()): ?>
        <div class="alert alert-success d-flex align-items-center mb-4" style="border-radius: 10px; border-left: 4px solid #28a745; background: #f0faf0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px; flex-shrink: 0;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <div>
                <strong>Subscription Active</strong> — Your subscription is active and expires on <strong><?php echo e($subscription->expires_at->format('d M Y')); ?></strong>.
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Current Plan</h5>

                    <?php if($subscription): ?>
                        <div class="mb-3">
                            <label class="sb-form-label">Plan Name</label>
                            <p style="margin: 0; font-size: 20px; font-weight: 600; color: #333;"><?php echo e($subscription->plan->name ?? '—'); ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Status</label>
                            <p style="margin: 0;">
                                <span class="sb-badge <?php echo e($subscription->status_badge); ?>">
                                    <?php echo e(ucfirst($subscription->status)); ?>

                                </span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Billing Cycle</label>
                            <p style="margin: 0; font-size: 15px; color: #333;"><?php echo e(ucfirst($subscription->billing_cycle)); ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">
                                <?php echo e($subscription->isTrial() ? 'Trial Remaining' : ($subscription->isActive() ? 'Subscription Remaining' : 'Grace Remaining')); ?>

                            </label>
                            <p style="margin: 0; font-size: 18px; font-weight: 600; color: #333;">
                                <?php if($subscription->daysRemaining() !== null): ?>
                                    <?php echo e($subscription->daysRemaining()); ?> days
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Student Limit</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">
                                <?php if($subscription->plan && $subscription->plan->is_unlimited): ?>
                                    <span class="sb-badge sb-badge-info">Unlimited</span>
                                <?php else: ?>
                                    <?php echo e(number_format($subscription->plan->student_limit ?? 0)); ?> students
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Monthly Price</label>
                            <p style="margin: 0; font-size: 15px; color: #333;"><?php echo e($subscription->plan->formattedMonthlyPrice() ?? '—'); ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="sb-form-label">Yearly Price</label>
                            <p style="margin: 0; font-size: 15px; color: #333;"><?php echo e($subscription->plan->formattedYearlyPrice() ?? '—'); ?></p>
                        </div>

                        <div class="d-flex gap-2 mt-3" style="border-top: 1px solid #e9ecef; padding-top: 16px;">
                            <a href="<?php echo e(route('school.subscription.checkout', ['plan_id' => $subscription->plan_id, 'billing_cycle' => $subscription->billing_cycle])); ?>" class="sb-btn sb-btn-primary">
                                Renew Subscription
                            </a>
                            <a href="<?php echo e(route('school.subscription.checkout', ['plan_id' => $subscription->plan_id, 'billing_cycle' => $subscription->billing_cycle])); ?>" class="sb-btn sb-btn-outline-primary">
                                Upgrade Plan
                            </a>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 32px 16px; color: #6c757d;">
                            <p style="margin: 0; font-size: 15px;">No active subscription found.</p>
                            <p style="margin: 8px 0 0; font-size: 14px;">Contact support to set up your subscription.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Subscription History</h5>

                    <?php if($history->count()): ?>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;">
                                    <div>
                                        <span style="font-weight: 500; font-size: 14px; color: #333;"><?php echo e($record->plan->name ?? '—'); ?></span>
                                        <div style="font-size: 12px; color: #6c757d; margin-top: 2px;">
                                            <?php echo e(ucfirst($record->billing_cycle)); ?> — <?php echo e($record->created_at->format('d M Y')); ?>

                                        </div>
                                    </div>
                                    <span class="sb-badge <?php echo e($record->status_badge); ?>">
                                        <?php echo e(ucfirst($record->status)); ?>

                                    </span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 32px 16px; color: #6c757d;">
                            <p style="margin: 0; font-size: 14px;">No subscription history yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/school/subscription/index.blade.php ENDPATH**/ ?>