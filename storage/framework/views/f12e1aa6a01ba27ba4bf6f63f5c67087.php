<?php $__env->startSection('title', 'Affiliate Program Settings - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2>Affiliate Program Settings</h2>
            <p class="mb-0">Configure commission rates and payout rules</p>
        </div>
        <a href="<?php echo e(route('affiliates.index')); ?>" class="sb-btn sb-btn-outline-secondary sb-btn-sm">Back to Affiliates</a>
    </div>
</div>

<div class="card stat-card">
    <div class="card-body" style="max-width: 640px;">
        <form method="POST" action="<?php echo e(route('affiliates.settings.update')); ?>" novalidate>
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="mb-3">
                <label for="setting-rate" class="sb-form-label">Default Commission Rate (%)</label>
                <input type="number" id="setting-rate" name="default_commission_rate" class="sb-form-input"
                       min="0" max="100" step="0.01" value="<?php echo e($settings['default_commission_rate']); ?>" required>
                <small class="sb-form-help">Applied to all affiliates unless overridden per affiliate. Can be any value 0–100.</small>
                <?php $__errorArgs = ['default_commission_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="sb-form-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label for="setting-months" class="sb-form-label">Commission Months</label>
                <input type="number" id="setting-months" name="commission_months" class="sb-form-input"
                       min="1" max="60" value="<?php echo e($settings['commission_months']); ?>" required>
                <small class="sb-form-help">How long recurring commissions are paid after a referred school's first payment.</small>
                <?php $__errorArgs = ['commission_months'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="sb-form-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4">
                <label for="setting-min" class="sb-form-label">Minimum Payout Amount (₦)</label>
                <input type="number" id="setting-min" name="min_payout_amount" class="sb-form-input"
                       min="0" step="0.01" value="<?php echo e($settings['min_payout_amount']); ?>" required>
                <?php $__errorArgs = ['min_payout_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="sb-form-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit" class="sb-btn sb-btn-primary">Save Settings</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/super-admin/affiliates/settings.blade.php ENDPATH**/ ?>