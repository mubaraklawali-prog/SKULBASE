<?php $__env->startSection('title', 'Score Entry Dashboard - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Score Entry Dashboard</h2>
            <p class="text-muted mb-0">Overview of student score entries</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('results.scores.create')); ?>" class="sb-btn sb-btn-primary">Enter Scores</a>
            <a href="<?php echo e(route('results.scores.history')); ?>" class="sb-btn sb-btn-dark">View History</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon sb-stat-icon-excused">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    </div>
                    <div>
                        <p class="stat-number sb-stat-number-excused"><?php echo e(number_format($totalEntries)); ?></p>
                        <p class="stat-label">Total Score Entries</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon sb-stat-icon-present">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                    </div>
                    <div>
                        <p class="stat-number sb-stat-number-present"><?php echo e($examsWithScores); ?></p>
                        <p class="stat-label">Exams with Scores</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon sb-stat-icon-late">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    </div>
                    <div>
                        <p class="stat-number sb-stat-number-late"><?php echo e($subjectsWithScores); ?></p>
                        <p class="stat-label">Subjects with Scores</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon sb-stat-icon-absent">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div>
                        <p class="stat-number sb-stat-number-absent"><?php echo e($pendingEntries); ?></p>
                        <p class="stat-label">Pending Entries</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Recent Score Entries</h5>
                    <?php if($recentEntries->isEmpty()): ?>
                        <p class="text-muted mb-0">No score entries yet. <a href="<?php echo e(route('results.scores.create')); ?>" class="sb-link">Enter scores now</a></p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 sb-table">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Class</th>
                                        <th>Subject</th>
                                        <th>Exam</th>
                                        <th>Assessment</th>
                                        <th>Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $recentEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="fw-medium"><?php echo e($entry->student->full_name ?? '—'); ?></td>
                                            <td class="text-muted"><?php echo e($entry->schoolClass->name ?? '—'); ?></td>
                                            <td class="text-muted"><?php echo e($entry->subject->name ?? '—'); ?></td>
                                            <td class="text-muted"><?php echo e($entry->exam->name ?? '—'); ?></td>
                                            <td class="text-muted"><?php echo e($entry->assessmentType->name ?? '—'); ?></td>
                                            <td class="fw-semibold"><?php echo e($entry->score); ?>%</td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Quick Actions</h5>
                    <div class="d-flex flex-column gap-2">
                        <a href="<?php echo e(route('results.scores.create')); ?>" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            Enter New Scores
                        </a>
                        <a href="<?php echo e(route('results.scores.edit')); ?>" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                            Edit Existing Scores
                        </a>
                        <a href="<?php echo e(route('results.scores.history')); ?>" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            Score History
                        </a>
                        <a href="<?php echo e(route('results.reports.subject')); ?>" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                            Subject Report
                        </a>
                        <a href="<?php echo e(route('results.reports.class')); ?>" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                            Class Report
                        </a>
                        <a href="<?php echo e(route('results.reports.exam')); ?>" class="action-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            Exam Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if($topScorers->isNotEmpty()): ?>
        <div class="card stat-card">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Top Performers</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 sb-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Average Score</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $topScorers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $scorer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-muted"><?php echo e($index + 1); ?></td>
                                    <td class="fw-medium"><?php echo e($scorer->student->full_name ?? '—'); ?></td>
                                    <td>
                                        <span class="sb-badge <?php echo e($scorer->avg_score >= 70 ? 'sb-badge-present' : ($scorer->avg_score >= 50 ? 'sb-badge-late' : 'sb-badge-absent')); ?>"><?php echo e(number_format($scorer->avg_score, 1)); ?>%</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?php echo e(route('results.scores.student-report', $scorer->student)); ?>" class="sb-link">View Report</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/results/scores/dashboard.blade.php ENDPATH**/ ?>