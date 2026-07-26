<?php $__env->startSection('title', 'Timetables - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Timetables</h2>
            <p class="text-muted mb-0">Manage school class timetables</p>
        </div>
        <a href="<?php echo e(route('timetables.create')); ?>" class="sb-btn sb-btn-primary">+ Add Entry</a>
    </div>

    <div class="sb-card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('timetables.index')); ?>" class="row g-2">
                <div class="col-12 col-sm-6 col-md-2">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search..." class="sb-form-input">
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="class_id" class="sb-form-select">
                        <option value="">All Classes</option>
                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>" <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>>
                                <?php echo e($class->name); ?><?php echo e($class->section ? ' - ' . $class->section : ''); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="section_id" class="sb-form-select">
                        <option value="">All Sections</option>
                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($section->id); ?>" <?php echo e(request('section_id') == $section->id ? 'selected' : ''); ?>>
                                <?php echo e($section->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="teacher_id" class="sb-form-select">
                        <option value="">All Teachers</option>
                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($teacher->id); ?>" <?php echo e(request('teacher_id') == $teacher->id ? 'selected' : ''); ?>>
                                <?php echo e($teacher->full_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="subject_id" class="sb-form-select">
                        <option value="">All Subjects</option>
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($subject->id); ?>" <?php echo e(request('subject_id') == $subject->id ? 'selected' : ''); ?>>
                                <?php echo e($subject->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="day" class="sb-form-select">
                        <option value="">All Days</option>
                        <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($day); ?>" <?php echo e(request('day') == $day ? 'selected' : ''); ?>>
                                <?php echo e($day); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="sb-btn sb-btn-dark">Filter</button>
                    <?php if(request('search') || request('class_id') || request('section_id') || request('teacher_id') || request('day') || request('subject_id')): ?>
                        <a href="<?php echo e(route('timetables.index')); ?>" class="sb-btn sb-btn-outline-secondary">Clear Filters</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="sb-card sb-table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="sb-table">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Day</th>
                            <th>Period</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Notes</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $timetables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="fw-medium"><?php echo e($entry->schoolClass->name ?? '—'); ?></td>
                                <td>
                                    <?php if($entry->section): ?>
                                        <span class="badge" style="background: #e7f1ff; color: #0d6efd;">
                                            <?php echo e($entry->section->name); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        $dayColors = [
                                            'Monday' => ['bg' => '#d1e7dd', 'text' => '#0f5132'],
                                            'Tuesday' => ['bg' => '#e7f1ff', 'text' => '#0d6efd'],
                                            'Wednesday' => ['bg' => '#fff3cd', 'text' => '#664d03'],
                                            'Thursday' => ['bg' => '#f0d9ff', 'text' => '#6f42c1'],
                                            'Friday' => ['bg' => '#f8d7da', 'text' => '#842029'],
                                            'Saturday' => ['bg' => '#f0f2f5', 'text' => '#6c757d'],
                                        ];
                                        $colors = $dayColors[$entry->day] ?? $dayColors['Monday'];
                                    ?>
                                    <span class="badge" style="background: <?php echo e($colors['bg']); ?>; color: <?php echo e($colors['text']); ?>;">
                                        <?php echo e($entry->day); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($entry->period): ?>
                                        <span class="fw-medium"><?php echo e($entry->period->name); ?></span>
                                        <br>
                                        <small class="text-muted"><?php echo e($entry->period->start_time ? \Carbon\Carbon::parse($entry->period->start_time)->format('h:i A') : ''); ?> - <?php echo e($entry->period->end_time ? \Carbon\Carbon::parse($entry->period->end_time)->format('h:i A') : ''); ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-medium"><?php echo e($entry->subject->name ?? '—'); ?></td>
                                <td><?php echo e($entry->teacher->full_name ?? '—'); ?></td>
                                <td class="text-muted"><?php echo e(Str::limit($entry->notes ?? '—', 30)); ?></td>
                                <td class="text-end">
                                    <div class="table-actions">
                                        <a href="<?php echo e(route('timetables.edit', $entry)); ?>" class="sb-btn sb-btn-sm sb-btn-outline-primary">Edit</a>
                                        <form method="POST" action="<?php echo e(route('timetables.destroy', $entry)); ?>" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this timetable entry?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <p class="mb-2">No timetable entries found.</p>
                                    <a href="<?php echo e(route('timetables.create')); ?>" class="text-primary fw-medium text-decoration-none">Add your first timetable entry</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if($timetables->hasPages()): ?>
        <div class="mt-3 d-flex justify-content-center">
            <?php echo e($timetables->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/timetables/index.blade.php ENDPATH**/ ?>