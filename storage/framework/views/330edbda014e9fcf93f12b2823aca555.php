<?php $__env->startSection('title', "My Child's Fees - Skulbase"); ?>

<?php $__env->startSection('content'); ?>
<style>
    .child-selector .form-check {
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        margin-bottom: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .child-selector .form-check:hover {
        border-color: #4f9cf7;
        background: #f8f9ff;
    }
    .child-selector .form-check-input:checked + .form-check-label {
        font-weight: 600;
        color: #0a1628;
    }
    .child-selector .form-check:has(.form-check-input:checked) {
        border-color: #4f9cf7;
        background: #f0f7ff;
    }
    .fee-structure-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .fee-structure-item:last-child {
        border-bottom: none;
    }
    .fee-structure-amount {
        font-weight: 700;
        font-size: 15px;
        color: #0a1628;
    }
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-paid {
        background: #d1e7dd;
        color: #0f5132;
    }
    .status-partial {
        background: #fff3cd;
        color: #664d03;
    }
    .status-unpaid {
        background: #f8d7da;
        color: #842029;
    }
</style>

<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>My Child's Fees</h2>
            <p class="text-muted mb-0">View fee structures and payment history</p>
        </div>
    </div>

    <?php if($children->count() > 1): ?>
        <div class="card stat-card mb-4">
            <div class="card-body">
                <h6 style="font-weight: 600; margin-bottom: 12px; color: #0a1628;">Select Child</h6>
                <form method="GET" action="<?php echo e(route('parent.fees.index')); ?>" class="child-selector">
                    <div class="row g-2">
                        <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="student_id" id="child_<?php echo e($child->id); ?>" value="<?php echo e($child->id); ?>" <?php echo e(old('student_id', $selectedStudentId) == $child->id ? 'checked' : ''); ?> onchange="this.form.submit()">
                                    <label class="form-check-label" for="child_<?php echo e($child->id); ?>">
                                        <strong><?php echo e($child->full_name); ?></strong>
                                        <br><small style="color: #6c757d;"><?php echo e($child->schoolClass->name ?? ''); ?><?php echo e($child->section ? ' — ' . $child->section->name : ''); ?></small>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if($selectedStudent): ?>
        <div class="card stat-card mb-3">
            <div class="card-body py-3 px-4">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span style="background: #0a1628; color: #fff; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                        <?php echo e($selectedStudent->full_name); ?>

                    </span>
                    <span style="background: #e7f1ff; color: #0d6efd; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                        <?php echo e($selectedStudent->schoolClass->name ?? ''); ?><?php echo e($selectedStudent->section ? ' — ' . $selectedStudent->section->name : ''); ?>

                    </span>
                </div>
            </div>
        </div>

        
        <div class="row g-3 mb-4">
            <div class="col-md-4 col-sm-6">
                <div class="card stat-card h-100">
                    <div class="card-body text-center">
                        <div style="width: 48px; height: 48px; background: #e7f1ff; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0d6efd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                <line x1="2" y1="10" x2="22" y2="10"></line>
                            </svg>
                        </div>
                        <h6 style="color: #6c757d; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Total Fees</h6>
                        <h4 style="color: #0a1628; font-weight: 700; margin: 0;">₦<?php echo e(number_format($summary['total_fees'] ?? 0, 2)); ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card stat-card h-100">
                    <div class="card-body text-center">
                        <div style="width: 48px; height: 48px; background: #d1e7dd; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0f5132" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <h6 style="color: #6c757d; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Amount Paid</h6>
                        <h4 style="color: #0f5132; font-weight: 700; margin: 0;">₦<?php echo e(number_format($summary['total_paid'] ?? 0, 2)); ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="card stat-card h-100">
                    <div class="card-body text-center">
                        <div style="width: 48px; height: 48px; background: #f8d7da; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#842029" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                        </div>
                        <h6 style="color: #6c757d; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Outstanding Balance</h6>
                        <h4 style="color: #842029; font-weight: 700; margin: 0;">₦<?php echo e(number_format($summary['balance'] ?? 0, 2)); ?></h4>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card stat-card mb-4">
            <div class="card-body">
                <h6 style="font-weight: 600; color: #0a1628; margin-bottom: 16px;">Fee Structures</h6>
                <?php $__empty_1 = true; $__currentLoopData = $feeStructures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="fee-structure-item">
                        <div>
                            <div style="font-weight: 600; font-size: 14px; color: #0a1628;"><?php echo e($fee->title); ?></div>
                            <?php if($fee->description): ?>
                                <small style="color: #6c757d;"><?php echo e($fee->description); ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="fee-structure-amount">₦<?php echo e(number_format($fee->amount, 2)); ?></span>
                            <?php
                                $status = $fee->computed_status ?? 'unpaid';
                                $statusClass = match($status) {
                                    'paid' => 'status-paid',
                                    'partial' => 'status-partial',
                                    default => 'status-unpaid',
                                };
                            ?>
                            <span class="status-badge <?php echo e($statusClass); ?>"><?php echo e(ucfirst($status)); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div style="text-align: center; padding: 30px 20px; color: #6c757d;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ced4da" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        <p style="margin: 0;">No fee structures found for this student.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card stat-card">
            <div class="card-body">
                <h6 style="font-weight: 600; color: #0a1628; margin-bottom: 16px;">Payment History</h6>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Fee</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td style="white-space: nowrap;"><?php echo e(\Carbon\Carbon::parse($payment->payment_date ?? $payment->created_at)->format('d M Y')); ?></td>
                                    <td><?php echo e($payment->feeStructure->name ?? '—'); ?></td>
                                    <td style="font-weight: 600;">₦<?php echo e(number_format($payment->amount_paid, 2)); ?></td>
                                    <td><?php echo e(ucfirst($payment->payment_method ?? '—')); ?></td>
                                    <td><code style="font-size: 12px; background: #f8f9fa; padding: 2px 8px; border-radius: 4px;"><?php echo e($payment->reference ?? '—'); ?></code></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ced4da" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px;">
                                            <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                            <line x1="2" y1="10" x2="22" y2="10"></line>
                                        </svg>
                                        <p style="margin: 0;">No payment records found for this student.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card stat-card">
            <div class="card-body" style="padding: 60px 20px; text-align: center;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ced4da" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 16px;">
                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                    <line x1="2" y1="10" x2="22" y2="10"></line>
                </svg>
                <h5 style="color: #6c757d; font-weight: 600; margin-bottom: 8px;">Select a Child</h5>
                <p style="color: #adb5bd; margin: 0;">Choose a child above to view their fees and payment history.</p>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/parent/fees/index.blade.php ENDPATH**/ ?>