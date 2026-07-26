<?php $__env->startSection('title', $parent->full_name . ' - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><?php echo e($parent->full_name); ?></h2>
            <p class="text-muted mb-0">Parent profile and linked children</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('parents.edit', $parent)); ?>" class="sb-btn sb-btn-outline-primary">
                Edit Parent
            </a>
            <a href="<?php echo e(route('parents.index')); ?>" class="sb-btn sb-btn-secondary">
                Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px; text-align: center;">
                    <div style="width: 96px; height: 96px; border-radius: 50%; overflow: hidden; margin: 0 auto 16px; background: #e7f1ff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 28px; color: #0d6efd;">
                        <?php echo e(substr($parent->first_name, 0, 1)); ?><?php echo e(substr($parent->last_name, 0, 1)); ?>

                    </div>
                    <h5 style="font-weight: 600; margin-bottom: 4px;"><?php echo e($parent->full_name); ?></h5>
                    <p style="color: #6c757d; font-size: 14px; margin-bottom: 12px;"><?php echo e($parent->school->name ?? '—'); ?></p>
                    <span class="sb-badge <?php echo e($parent->status ? 'sb-badge-active' : 'sb-badge-inactive'); ?>">
                        <?php echo e($parent->status ? 'Active' : 'Inactive'); ?>

                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Contact Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label">Email</label>
                            <p style="margin: 0; font-size: 15px; color: #333;"><?php echo e($parent->email ?? '—'); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label">Phone</label>
                            <p style="margin: 0; font-size: 15px; color: #333;"><?php echo e($parent->phone ?? '—'); ?></p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="sb-form-label">Address</label>
                            <p style="margin: 0; font-size: 15px; color: #333;"><?php echo e($parent->address ?? '—'); ?></p>
                        </div>
                    </div>

                    <div style="border-top: 1px solid #e9ecef; padding-top: 16px; margin-top: 8px;">
                        <h6 style="font-weight: 600; color: #1a1a2e; margin-bottom: 12px;">Account Status</h6>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <span class="sb-badge <?php echo e($parent->user ? 'sb-badge-active' : 'sb-badge-inactive'); ?>">
                                Login Account: <?php echo e($parent->user ? 'Active' : 'None'); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body" style="padding: 24px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 style="font-weight: 600; margin-bottom: 0; color: #1a1a2e;">Linked Children</h5>
                <span class="sb-badge sb-badge-info">
                    <?php echo e($parent->children->count()); ?> <?php echo e(Str::plural('child', $parent->children->count())); ?>

                </span>
            </div>

            <?php if($parent->children->count()): ?>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <?php $__currentLoopData = $parent->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;">
                            <div>
                                <span style="font-weight: 500; font-size: 14px; color: #333;"><?php echo e($child->full_name); ?></span>
                                <span style="color: #6c757d; font-size: 13px;"> | <?php echo e($child->admission_number); ?></span>
                                <?php if($child->schoolClass): ?>
                                    <span style="color: #6c757d; font-size: 13px;"> | <?php echo e($child->schoolClass->name); ?></span>
                                <?php endif; ?>
                            </div>
                            <span class="sb-badge <?php echo e($child->status === 'active' ? 'sb-badge-active' : 'sb-badge-inactive'); ?>">
                                <?php echo e(ucfirst($child->status)); ?>

                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 32px 16px; color: #6c757d;">
                    <p style="margin: 0; font-size: 14px;">No children linked yet.</p>
                    <a href="<?php echo e(route('parents.edit', $parent)); ?>" class="sb-btn sb-btn-sm sb-btn-outline-primary mt-2">Link children</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/parents/show.blade.php ENDPATH**/ ?>