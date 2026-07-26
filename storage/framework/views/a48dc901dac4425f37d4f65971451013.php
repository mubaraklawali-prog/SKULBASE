<?php $__env->startSection('title', 'Reports Dashboard - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Reports & Analytics</h2>
            <p class="text-muted mb-0">
                <?php if($school): ?>
                    <?php echo e($school->name); ?> — School-wide overview and insights
                <?php else: ?>
                    All Schools — Aggregated overview and insights
                <?php endif; ?>
            </p>
        </div>
    </div>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($schools->isNotEmpty()): ?>
        <form method="GET" action="<?php echo e(route('reports.dashboard')); ?>" class="card stat-card mb-4">
            <div class="card-body">
                <div class="d-flex gap-3 align-items-end">
                    <div style="flex: 3;">
                        <label class="sb-form-label">Select School</label>
                        <select name="school_id" class="sb-form-select" onchange="this.form.submit()">
                            <option value="">All Schools (Aggregated)</option>
                            <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($s->id); ?>" <?php echo e(request('school_id') == $s->id ? 'selected' : ''); ?>><?php echo e($s->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #e7f1ff; color: #0d6efd;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #0d6efd;"><?php echo e(number_format($summary['total_students'])); ?></p>
                        <p class="stat-label">Total Students</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #d1e7dd; color: #0f5132;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #0f5132;"><?php echo e(number_format($summary['total_teachers'])); ?></p>
                        <p class="stat-label">Total Teachers</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #fff3cd; color: #664d03;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #664d03;"><?php echo e(number_format($summary['total_classes'])); ?></p>
                        <p class="stat-label">Total Classes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #f3e8ff; color: #7c3aed;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #7c3aed;"><?php echo e(number_format($summary['total_subjects'])); ?></p>
                        <p class="stat-label">Total Subjects</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #d1e7dd; color: #0f5132;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #0f5132;">₦<?php echo e(number_format($summary['total_collected'], 2)); ?></p>
                        <p class="stat-label">Total Collected</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #f8d7da; color: #842029;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #842029;">₦<?php echo e(number_format($summary['total_outstanding'], 2)); ?></p>
                        <p class="stat-label">Outstanding Fees</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #e7f1ff; color: #0d6efd;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <div>
                        <p class="stat-number" style="color: #0d6efd;"><?php echo e($summary['today_attendance_rate']); ?>%</p>
                        <p class="stat-label">Today's Attendance</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $reportParams = array_filter(['school_id' => request('school_id')]); ?>

    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card stat-card" style="height: 100%; cursor: pointer;" onclick="window.location='<?php echo e(route('reports.students.list', $reportParams)); ?>'">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #e7f1ff; color: #0d6efd;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                        </div>
                        <div>
                            <h6 style="font-weight: 600; margin: 0;">Student Reports</h6>
                            <small class="text-muted">List, by class, status breakdown</small>
                        </div>
                    </div>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card stat-card" style="height: 100%; cursor: pointer;" onclick="window.location='<?php echo e(route('reports.teachers.list', $reportParams)); ?>'">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #d1e7dd; color: #0f5132;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                        </div>
                        <div>
                            <h6 style="font-weight: 600; margin: 0;">Teacher Reports</h6>
                            <small class="text-muted">List, by subject, status breakdown</small>
                        </div>
                    </div>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%; cursor: pointer;" onclick="window.location='<?php echo e(route('reports.attendance.summary', $reportParams)); ?>'">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #fff3cd; color: #664d03;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </div>
                        <div>
                            <h6 style="font-weight: 600; margin: 0;">Attendance</h6>
                            <small class="text-muted">Summary, by date, by class</small>
                        </div>
                    </div>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%; cursor: pointer;" onclick="window.location='<?php echo e(route('reports.fees.payments', $reportParams)); ?>'">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #d1e7dd; color: #0f5132;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        </div>
                        <div>
                            <h6 style="font-weight: 600; margin: 0;">Fee Reports</h6>
                            <small class="text-muted">Payments, outstanding, collections</small>
                        </div>
                    </div>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%; cursor: pointer;" onclick="window.location='<?php echo e(route('reports.academic.results', $reportParams)); ?>'">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: #f3e8ff; color: #7c3aed;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                        </div>
                        <div>
                            <h6 style="font-weight: 600; margin: 0;">Academic</h6>
                            <small class="text-muted">Results, class & subject perf.</small>
                        </div>
                    </div>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/reports/dashboard.blade.php ENDPATH**/ ?>