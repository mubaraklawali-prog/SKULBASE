<?php $__env->startSection('title', 'Review Registration - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Review Registration</h2>
            <p class="mb-0">Review school registration application</p>
        </div>
        <a href="<?php echo e(route('pending-schools.index')); ?>" class="sb-btn sb-btn-secondary">
            &larr; Back to Pending Schools
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            
            <div class="card stat-card mb-4">
                <div class="card-header" style="background: none; border-bottom: 1px solid #e9ecef; padding: 16px 24px;">
                    <strong>School Information</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label text-muted">School Name</label>
                            <div style="font-weight: 600; font-size: 16px;"><?php echo e($school->name); ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label text-muted">School Type</label>
                            <div><?php echo e($school->school_type ?? 'Not specified'); ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label text-muted">Email</label>
                            <div><?php echo e($school->email ?? 'Not provided'); ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label text-muted">Phone</label>
                            <div><?php echo e($school->phone ?? 'Not provided'); ?></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="sb-form-label text-muted">Address</label>
                            <div><?php echo e($school->address ?? 'Not provided'); ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label text-muted">Registered</label>
                            <div><?php echo e($school->registered_at ? $school->registered_at->format('M d, Y \a\t h:i A') : '-'); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            
            <?php if($admin): ?>
            <div class="card stat-card mb-4">
                <div class="card-header" style="background: none; border-bottom: 1px solid #e9ecef; padding: 16px 24px;">
                    <strong>School Administrator</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label text-muted">Full Name</label>
                            <div style="font-weight: 600;"><?php echo e($admin->name); ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label text-muted">Email</label>
                            <div><?php echo e($admin->email); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            
            <div class="card stat-card mb-4">
                <div class="card-header" style="background: none; border-bottom: 1px solid #e9ecef; padding: 16px 24px;">
                    <strong>Actions</strong>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form method="POST" action="<?php echo e(route('pending-schools.approve', $school)); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="sb-btn sb-btn-outline-success" style="width: 100%;"
                                    onclick="return confirm('Are you sure you want to approve this school? A 30-day trial will be activated.')">
                                Approve Registration
                            </button>
                        </form>

                        <button type="button" class="sb-btn sb-btn-outline-danger" style="width: 100%;"
                                data-bs-toggle="modal" data-bs-target="#rejectModal">
                            Reject Registration
                        </button>
                    </div>
                </div>
            </div>

            
            <div class="card stat-card">
                <div class="card-header" style="background: none; border-bottom: 1px solid #e9ecef; padding: 16px 24px;">
                    <strong>Registration Details</strong>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="text-muted" style="font-size: 13px;">Status:</span>
                        <span class="sb-badge sb-badge-pending">Pending</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted" style="font-size: 13px;">Plan:</span>
                        <span style="font-weight: 500;"><?php echo e($school->name); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header" style="border-bottom: 1px solid #e9ecef;">
                <h5 class="modal-title" id="rejectModalLabel">Reject Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo e(route('pending-schools.reject', $school)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <p class="text-muted mb-3">Please provide a reason for rejecting this school registration:</p>
                    <div>
                        <label class="sb-form-label">Rejection Reason <span class="required">*</span></label>
                        <textarea name="rejection_reason" class="sb-form-textarea" rows="4" required
                                  placeholder="Enter the reason for rejection..."
                                  style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px; font-size: 14px; width: 100%;"></textarea>
                        <?php $__errorArgs = ['rejection_reason'];
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
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e9ecef;">
                    <button type="button" class="sb-btn sb-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="sb-btn sb-btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/super-admin/pending-schools/show.blade.php ENDPATH**/ ?>