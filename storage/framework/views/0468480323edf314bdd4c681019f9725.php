<?php $__env->startSection('title', 'My Schedule - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .timetable-grid-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
    }
    .timetable-grid {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        min-width: 900px;
    }
    .timetable-grid thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #0a1628;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 12px;
        text-align: center;
        white-space: nowrap;
    }
    .timetable-grid thead th:first-child {
        position: sticky;
        left: 0;
        z-index: 20;
        background: #0a1628;
        text-align: left;
        min-width: 160px;
    }
    .timetable-grid tbody td {
        padding: 10px 12px;
        vertical-align: top;
        border-bottom: 1px solid #f0f0f0;
        min-height: 80px;
        height: 80px;
        transition: background 0.15s;
    }
    .timetable-grid tbody tr:hover td {
        background: #f8f9ff;
    }
    .timetable-grid tbody td:first-child {
        position: sticky;
        left: 0;
        z-index: 5;
        background: #f8f9fa;
        font-weight: 600;
        font-size: 13px;
        color: #333;
        white-space: nowrap;
    }
    .timetable-grid tbody tr:hover td:first-child {
        background: #eef1f5;
    }
    .period-time {
        font-size: 11px;
        color: #6c757d;
        margin-top: 2px;
    }
    .lesson-card {
        background: #f0f7ff;
        border-left: 3px solid #4f9cf7;
        border-radius: 6px;
        padding: 8px 10px;
    }
    .lesson-card .subject-name {
        font-weight: 600;
        font-size: 13px;
        color: #1a1a2e;
        margin-bottom: 3px;
    }
    .lesson-card .class-section {
        font-size: 12px;
        color: #6c757d;
    }
    .lesson-card .room-info {
        font-size: 11px;
        color: #adb5bd;
        margin-top: 2px;
    }
    .break-badge, .lunch-badge, .assembly-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 56px;
    }
    .break-badge span {
        background: #fff3cd;
        color: #664d03;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .lunch-badge span {
        background: #d1e7dd;
        color: #0f5132;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .assembly-badge span {
        background: #f0d9ff;
        color: #6f42c1;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .free-cell {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 56px;
        color: #ced4da;
        font-size: 13px;
        font-style: italic;
    }
    .day-header-mon { background: #1a5c3a !important; }
    .day-header-tue { background: #1a4a7a !important; }
    .day-header-wed { background: #7a5c1a !important; }
    .day-header-thu { background: #5c1a7a !important; }
    .day-header-fri { background: #7a1a2a !important; }
    .day-header-sat { background: #4a5568 !important; }
</style>

<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>My Schedule</h2>
            <p class="text-muted mb-0"><?php echo e($teacher->full_name); ?> — Weekly teaching timetable</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('teacher.timetable.print')); ?>"
               target="_blank"
               class="sb-btn sb-btn-outline-secondary d-inline-flex align-items-center gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Print
            </a>
        </div>
    </div>

    <div class="card stat-card mb-3">
        <div class="card-body py-3 px-4">
            <div class="d-flex align-items-center gap-3">
                <span style="background: #0a1628; color: #fff; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                    <?php echo e($teacher->full_name); ?>

                </span>
                <span style="color: #6c757d; font-size: 13px;">
                    <?php echo e($grid->count()); ?> lesson<?php echo e($grid->count() !== 1 ? 's' : ''); ?> this week
                </span>
            </div>
        </div>
    </div>

    <div class="timetable-grid-wrapper">
        <table class="timetable-grid">
            <thead>
                <tr>
                    <th>Period</th>
                    <?php
                        $dayHeaderClasses = [
                            'Monday' => 'day-header-mon',
                            'Tuesday' => 'day-header-tue',
                            'Wednesday' => 'day-header-wed',
                            'Thursday' => 'day-header-thu',
                            'Friday' => 'day-header-fri',
                            'Saturday' => 'day-header-sat',
                        ];
                    ?>
                    <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="<?php echo e($dayHeaderClasses[$day] ?? ''); ?>"><?php echo e(strtoupper(substr($day, 0, 3))); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div><?php echo e($period->name); ?></div>
                            <?php if($period->start_time && $period->end_time): ?>
                                <div class="period-time">
                                    <?php echo e(\Carbon\Carbon::parse($period->start_time)->format('h:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($period->end_time)->format('h:i A')); ?>

                                </div>
                            <?php endif; ?>
                        </td>
                        <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $key = $period->id . '_' . $day;
                                $entry = $grid->get($key);
                            ?>
                            <td>
                                <?php if(in_array($period->type, ['break', 'lunch', 'assembly'])): ?>
                                    <div class="<?php echo e($period->type); ?>-badge">
                                        <span><?php echo e(ucfirst($period->type)); ?></span>
                                    </div>
                                <?php elseif($entry): ?>
                                    <div class="lesson-card">
                                        <div class="subject-name"><?php echo e($entry->subject->name ?? '—'); ?></div>
                                        <div class="class-section">
                                            <?php echo e($entry->schoolClass->name ?? ''); ?><?php echo e($entry->section ? ' — ' . $entry->section->name : ''); ?>

                                        </div>
                                        <?php if($entry->notes): ?>
                                            <div class="room-info"><?php echo e(\Illuminate\Support\Str::limit($entry->notes, 25)); ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="free-cell">Free Period</div>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="<?php echo e(count($days) + 1); ?>" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                            No active periods found for this school.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/teacher/timetable/index.blade.php ENDPATH**/ ?>