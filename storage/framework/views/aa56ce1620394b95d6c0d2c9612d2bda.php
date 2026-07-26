<?php $__env->startSection('title', 'Report Cards - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Report Cards</h2>
            <p class="text-muted mb-0">Print or download student report cards</p>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body sb-card-body">
            <h5 class="sb-detail-label mb-4">Select Exam & Class</h5>

            <form method="POST" action="<?php echo e(route('results.report-cards.bulk-print')); ?>" id="bulkForm">
                <?php echo csrf_field(); ?>
                <div class="row mb-4">
                    <div class="col-md-5 mb-3">
                        <label class="sb-form-label">Exam</label>
                        <select name="exam_id" required class="sb-form-select">
                            <option value="">Select Exam</option>
                            <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($exam->id); ?>"><?php echo e($exam->name); ?> <?php if($exam->term): ?>(<?php echo e($exam->term); ?><?php if($exam->session): ?> - <?php echo e($exam->session); ?><?php endif; ?>)<?php endif; ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['exam_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="sb-form-error"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-5 mb-3">
                        <label class="sb-form-label">Class</label>
                        <select name="school_class_id" required class="sb-form-select">
                            <option value="">Select Class</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?><?php echo e($class->section ? ' - ' . $class->section : ''); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['school_class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="sb-form-error"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end gap-2">
                        <button type="submit" formaction="<?php echo e(route('results.report-cards.bulk-print')); ?>" formtarget="_blank" class="sb-btn sb-btn-primary">Print</button>
                        <button type="submit" formaction="<?php echo e(route('results.report-cards.bulk-pdf')); ?>" class="sb-btn sb-btn-dark">PDF</button>
                    </div>
                </div>
            </form>

            <hr class="sb-divider">

            <h6 class="sb-detail-label mb-3">How it works</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="sb-step-number">1</div>
                        <div>
                            <p class="fw-semibold mb-0 small">Select Exam & Class</p>
                            <p class="text-muted small mb-0 mt-1">Choose the exam and class for which you want to print report cards.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="sb-step-number">2</div>
                        <div>
                            <p class="fw-semibold mb-0 small">Choose Output</p>
                            <p class="text-muted small mb-0 mt-1">Click <strong>Print</strong> for browser print dialog, or <strong>PDF</strong> to download.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="sb-step-number sb-step-number-warning">!</div>
                        <div>
                            <p class="fw-semibold mb-0 small">Only Published</p>
                            <p class="text-muted small mb-0 mt-1">Only <span class="sb-badge sb-badge-success">Published</span> report cards can be printed.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/results/report-cards/bulk.blade.php ENDPATH**/ ?>