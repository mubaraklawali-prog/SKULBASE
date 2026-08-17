<?php $__env->startSection('title', 'Checkout - ' . $plan->name . ' - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="mb-4">
        <h2>Checkout</h2>
        <p class="text-muted mb-0">Complete your subscription payment</p>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger" style="border-radius: 10px; border-left: 4px solid #dc3545; background: #fff5f5;">
            <strong>Payment Error</strong>
            <ul class="mb-0 mt-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card stat-card mb-4" style="border: 2px solid var(--primary);">
                <div class="card-body" style="padding: 28px;">
                    <h5 style="font-weight: 600; margin-bottom: 24px; color: #1a1a2e;">Order Summary</h5>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <label class="sb-form-label mb-0">School</label>
                        <span style="font-weight: 600; color: #333;"><?php echo e($school->name); ?></span>
                    </div>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <label class="sb-form-label mb-0">Plan</label>
                        <span style="font-weight: 600; color: #333; font-size: 18px;"><?php echo e($plan->name); ?></span>
                    </div>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <label class="sb-form-label mb-0">Billing Cycle</label>
                        <span style="color: #333;"><?php echo e(ucfirst($billingCycle)); ?></span>
                    </div>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <label class="sb-form-label mb-0">Student Limit</label>
                        <span style="color: #333;">
                            <?php if($plan->is_unlimited): ?>
                                <span class="sb-badge sb-badge-info">Unlimited</span>
                            <?php else: ?>
                                <?php echo e(number_format($plan->student_limit ?? 0)); ?> students
                            <?php endif; ?>
                        </span>
                    </div>

                    <hr style="margin: 20px 0; border-color: #e9ecef;">

                    <?php if($hasDiscount): ?>
                        <div class="mb-2 d-flex justify-content-between align-items-center">
                            <label class="sb-form-label mb-0">Original Price</label>
                            <span style="color: #6c757d; text-decoration: line-through;">₦<?php echo e(number_format($basePrice, 2)); ?></span>
                        </div>
                        <div class="mb-2 d-flex justify-content-between align-items-center">
                            <label class="sb-form-label mb-0">Discount (<?php echo e($plan->discount_percentage); ?>%)</label>
                            <span style="color: #28a745; font-weight: 500;">-₦<?php echo e(number_format($discountAmount, 2)); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <label class="sb-form-label mb-0">Email</label>
                        <span style="color: #333;"><?php echo e($school->email); ?></span>
                    </div>

                    <hr style="margin: 20px 0; border-color: #e9ecef;">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <label class="sb-form-label mb-0" style="font-size: 18px;">Total</label>
                        <span style="font-size: 24px; font-weight: 700; color: var(--primary);">₦<?php echo e(number_format($finalPrice, 2)); ?></span>
                    </div>

                    <form method="POST" action="<?php echo e(route('school.subscription.pay')); ?>" id="payForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="plan_id" value="<?php echo e($plan->id); ?>">
                        <input type="hidden" name="billing_cycle" value="<?php echo e($billingCycle); ?>">
                        <button type="submit" class="sb-btn sb-btn-primary w-100" style="padding: 14px; font-size: 16px; font-weight: 600;">
                            Pay ₦<?php echo e(number_format($finalPrice, 2)); ?> with Paystack
                        </button>
                    </form>

                    <p class="text-muted small text-center mt-3 mb-0">
                        You will be redirected to Paystack to complete your payment securely.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/school/subscription/checkout.blade.php ENDPATH**/ ?>