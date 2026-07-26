<?php $__env->startSection('title', 'Events - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>School Calendar</h2>
            <p class="text-muted mb-0">View and manage school events</p>
        </div>
        <?php if($canManage): ?>
            <a href="<?php echo e(route('events.create')); ?>" class="sb-btn sb-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Event
            </a>
        <?php endif; ?>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('events.index')); ?>" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="sb-form-label">Search</label>
                    <input type="text" name="search" class="sb-form-input" value="<?php echo e(request('search')); ?>" placeholder="Search by title...">
                </div>
                <div class="col-md-2">
                    <label class="sb-form-label">Event Type</label>
                    <select name="event_type" class="sb-form-select">
                        <option value="">All Types</option>
                        <option value="academic" <?php echo e(request('event_type') == 'academic' ? 'selected' : ''); ?>>Academic</option>
                        <option value="exam" <?php echo e(request('event_type') == 'exam' ? 'selected' : ''); ?>>Exam</option>
                        <option value="holiday" <?php echo e(request('event_type') == 'holiday' ? 'selected' : ''); ?>>Holiday</option>
                        <option value="meeting" <?php echo e(request('event_type') == 'meeting' ? 'selected' : ''); ?>>Meeting</option>
                        <option value="sports" <?php echo e(request('event_type') == 'sports' ? 'selected' : ''); ?>>Sports</option>
                        <option value="other" <?php echo e(request('event_type') == 'other' ? 'selected' : ''); ?>>Other</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="sb-form-label">Date From</label>
                    <input type="date" name="date_from" class="sb-form-input" value="<?php echo e(request('date_from')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="sb-form-label">Date To</label>
                    <input type="date" name="date_to" class="sb-form-input" value="<?php echo e(request('date_to')); ?>">
                </div>
                <?php if($canManage): ?>
                    <div class="col-md-1">
                        <label class="sb-form-label">Status</label>
                        <select name="status" class="sb-form-select">
                            <option value="">All</option>
                            <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>>Draft</option>
                            <option value="published" <?php echo e(request('status') == 'published' ? 'selected' : ''); ?>>Published</option>
                        </select>
                    </div>
                <?php endif; ?>
                <div class="col-md-1">
                    <button type="submit" class="sb-btn sb-btn-primary w-100">Filter</button>
                </div>
                <?php if(request()->hasAny(['search', 'event_type', 'date_from', 'date_to', 'status'])): ?>
                    <div class="col-md-1">
                        <a href="<?php echo e(route('events.index')); ?>" class="sb-btn sb-btn-secondary w-100 text-center">Clear</a>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover sb-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Time</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td style="white-space: nowrap;">
                                    <strong class="<?php echo e($event->event_date->isPast() ? 'text-danger' : ''); ?>">
                                        <?php echo e($event->event_date->format('M d, Y')); ?>

                                    </strong>
                                    <?php if($event->event_date->isToday()): ?>
                                        <br><small style="color: var(--primary); font-weight: 600;">Today</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('events.show', $event)); ?>" style="color: #0a1628; font-weight: 600; text-decoration: none;"><?php echo e($event->title); ?></a>
                                </td>
                                <td>
                                    <?php
                                        $typeColors = [
                                            'academic' => ['bg' => '#e7f1ff', 'text' => '#0d6efd'],
                                            'exam' => ['bg' => '#f8d7da', 'text' => '#dc3545'],
                                            'holiday' => ['bg' => '#d1e7dd', 'text' => '#0f5132'],
                                            'meeting' => ['bg' => '#fff3cd', 'text' => '#664d03'],
                                            'sports' => ['bg' => '#e7f1ff', 'text' => '#0d6efd'],
                                            'other' => ['bg' => '#f0f2f5', 'text' => '#6c757d'],
                                        ];
                                        $color = $typeColors[$event->event_type] ?? $typeColors['other'];
                                    ?>
                                    <span class="sb-badge sb-badge-tag" style="background: <?php echo e($color['bg']); ?>; color: <?php echo e($color['text']); ?>; text-transform: capitalize;"><?php echo e($event->event_type); ?></span>
                                </td>
                                <td class="text-muted">
                                    <?php if($event->start_time && $event->end_time): ?>
                                        <?php echo e($event->start_time->format('h:i A')); ?> - <?php echo e($event->end_time->format('h:i A')); ?>

                                    <?php elseif($event->start_time): ?>
                                        <?php echo e($event->start_time->format('h:i A')); ?>

                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted">
                                    <?php echo e($event->location ?? '—'); ?>

                                </td>
                                <td>
                                    <?php if($event->status === 'published'): ?>
                                        <span class="sb-badge sb-badge-published">Published</span>
                                    <?php else: ?>
                                        <span class="sb-badge sb-badge-draft">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    <a href="<?php echo e(route('events.show', $event)); ?>" class="sb-btn sb-btn-sm sb-btn-outline-primary">View</a>
                                    <?php if($canManage): ?>
                                        <a href="<?php echo e(route('events.edit', $event)); ?>" class="sb-btn sb-btn-sm sb-btn-secondary">Edit</a>
                                        <form action="<?php echo e(route('events.destroy', $event)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                    No events found.
                                    <?php if($canManage): ?>
                                        <a href="<?php echo e(route('events.create')); ?>" style="color: var(--primary);">Create your first event</a>.
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($events->hasPages()): ?>
                <div class="d-flex justify-content-center mt-3">
                    <?php echo e($events->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/events/index.blade.php ENDPATH**/ ?>