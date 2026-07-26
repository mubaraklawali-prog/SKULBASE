<?php $__env->startSection('title', 'Teacher Dashboard - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $school = Auth::user()->school;
    $schoolSetting = $school?->setting;
?>

<?php if (isset($component)) { $__componentOriginal0befbe0186681e93c8b8de70927507df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0befbe0186681e93c8b8de70927507df = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.welcome-banner','data' => ['school' => $school,'schoolSetting' => $schoolSetting]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.welcome-banner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['school' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($school),'schoolSetting' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($schoolSetting)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0befbe0186681e93c8b8de70927507df)): ?>
<?php $attributes = $__attributesOriginal0befbe0186681e93c8b8de70927507df; ?>
<?php unset($__attributesOriginal0befbe0186681e93c8b8de70927507df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0befbe0186681e93c8b8de70927507df)): ?>
<?php $component = $__componentOriginal0befbe0186681e93c8b8de70927507df; ?>
<?php unset($__componentOriginal0befbe0186681e93c8b8de70927507df); ?>
<?php endif; ?>


<?php if($pendingAssignments > 0): ?>
    <div class="sb-alert sb-alert-warning mb-4">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <span>You have <?php echo e($pendingAssignments); ?> overdue assignment<?php echo e($pendingAssignments > 1 ? 's' : ''); ?> that need attention.</span>
    </div>
<?php endif; ?>


<div class="row g-3 mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6">
        <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'My Classes','value' => $teacherStats['total_classes'],'color' => 'purple','description' => 'Assigned classes']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'My Classes','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($teacherStats['total_classes']),'color' => 'purple','description' => 'Assigned classes']); ?>
             <?php $__env->slot('icon', null, []); ?> 
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal457ade557f73eaa008f851091260abe1)): ?>
<?php $attributes = $__attributesOriginal457ade557f73eaa008f851091260abe1; ?>
<?php unset($__attributesOriginal457ade557f73eaa008f851091260abe1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal457ade557f73eaa008f851091260abe1)): ?>
<?php $component = $__componentOriginal457ade557f73eaa008f851091260abe1; ?>
<?php unset($__componentOriginal457ade557f73eaa008f851091260abe1); ?>
<?php endif; ?>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'My Students','value' => $teacherStats['total_students'],'color' => 'green','description' => 'Across all classes']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'My Students','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($teacherStats['total_students']),'color' => 'green','description' => 'Across all classes']); ?>
             <?php $__env->slot('icon', null, []); ?> 
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal457ade557f73eaa008f851091260abe1)): ?>
<?php $attributes = $__attributesOriginal457ade557f73eaa008f851091260abe1; ?>
<?php unset($__attributesOriginal457ade557f73eaa008f851091260abe1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal457ade557f73eaa008f851091260abe1)): ?>
<?php $component = $__componentOriginal457ade557f73eaa008f851091260abe1; ?>
<?php unset($__componentOriginal457ade557f73eaa008f851091260abe1); ?>
<?php endif; ?>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Today\'s Classes','value' => $teacherStats['today_classes'],'color' => 'info','description' => 'Scheduled today']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Today\'s Classes','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($teacherStats['today_classes']),'color' => 'info','description' => 'Scheduled today']); ?>
             <?php $__env->slot('icon', null, []); ?> 
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal457ade557f73eaa008f851091260abe1)): ?>
<?php $attributes = $__attributesOriginal457ade557f73eaa008f851091260abe1; ?>
<?php unset($__attributesOriginal457ade557f73eaa008f851091260abe1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal457ade557f73eaa008f851091260abe1)): ?>
<?php $component = $__componentOriginal457ade557f73eaa008f851091260abe1; ?>
<?php unset($__componentOriginal457ade557f73eaa008f851091260abe1); ?>
<?php endif; ?>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Attendance This Week','value' => $recentAttendanceCount,'color' => 'warning','description' => 'Records marked']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Attendance This Week','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($recentAttendanceCount),'color' => 'warning','description' => 'Records marked']); ?>
             <?php $__env->slot('icon', null, []); ?> 
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal457ade557f73eaa008f851091260abe1)): ?>
<?php $attributes = $__attributesOriginal457ade557f73eaa008f851091260abe1; ?>
<?php unset($__attributesOriginal457ade557f73eaa008f851091260abe1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal457ade557f73eaa008f851091260abe1)): ?>
<?php $component = $__componentOriginal457ade557f73eaa008f851091260abe1; ?>
<?php unset($__componentOriginal457ade557f73eaa008f851091260abe1); ?>
<?php endif; ?>
    </div>
</div>


<?php if($todayTimetable->isNotEmpty()): ?>
    <div class="mb-4">
        <?php if (isset($component)) { $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'Today\'s Schedule','subtitle' => ''.e(Carbon::now()->format('l, F j, Y')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Today\'s Schedule','subtitle' => ''.e(Carbon::now()->format('l, F j, Y')).'']); ?>
            <div class="ds-timetable-list">
                <?php $__currentLoopData = $todayTimetable; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="ds-timetable-slot">
                        <div class="ds-timetable-time">
                            <span class="ds-timetable-period-name"><?php echo e($slot->period->name ?? ''); ?></span>
                            <span class="ds-timetable-period-time"><?php echo e($slot->period->start_time ?? ''); ?> - <?php echo e($slot->period->end_time ?? ''); ?></span>
                        </div>
                        <div class="ds-timetable-divider"></div>
                        <div class="ds-timetable-details">
                            <span class="ds-timetable-subject"><?php echo e($slot->subject->name ?? 'N/A'); ?></span>
                            <span class="ds-timetable-class"><?php echo e($slot->schoolClass->name ?? 'N/A'); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e)): ?>
<?php $attributes = $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e; ?>
<?php unset($__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e)): ?>
<?php $component = $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e; ?>
<?php unset($__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e); ?>
<?php endif; ?>
    </div>
<?php endif; ?>


<div class="mb-4">
    <?php if (isset($component)) { $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'Quick Actions']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Quick Actions']); ?>
        <div class="ds-quick-actions">
            <?php if($teacher->can_mark_attendance): ?>
                <a href="<?php echo e(route('teacher.attendance.create')); ?>" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--primary-light); color: var(--primary);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                    <span class="ds-quick-action-label">Take Attendance</span>
                </a>
            <?php endif; ?>
            <a href="<?php echo e(route('teacher.scores.create')); ?>" class="ds-quick-action">
                <div class="ds-quick-action-icon" style="background: var(--success-light); color: var(--success);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </div>
                <span class="ds-quick-action-label">Enter Scores</span>
            </a>
            <a href="<?php echo e(route('assignments.create')); ?>" class="ds-quick-action">
                <div class="ds-quick-action-icon" style="background: var(--info-light); color: var(--info);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                </div>
                <span class="ds-quick-action-label">New Assignment</span>
            </a>
            <a href="<?php echo e(route('teacher.timetable.index')); ?>" class="ds-quick-action">
                <div class="ds-quick-action-icon" style="background: var(--warning-light); color: var(--warning);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect></svg>
                </div>
                <span class="ds-quick-action-label">View Timetable</span>
            </a>
            <a href="<?php echo e(route('messages.inbox')); ?>" class="ds-quick-action">
                <div class="ds-quick-action-icon" style="background: var(--danger-light); color: var(--danger);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                </div>
                <span class="ds-quick-action-label">Messages</span>
            </a>
            <?php if($teacher->can_mark_attendance): ?>
            <a href="<?php echo e(route('teacher.attendance.index')); ?>" class="ds-quick-action">
                <div class="ds-quick-action-icon" style="background: #EEF2FF; color: #6366F1;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <span class="ds-quick-action-label">Attendance History</span>
            </a>
            <?php endif; ?>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e)): ?>
<?php $attributes = $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e; ?>
<?php unset($__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e)): ?>
<?php $component = $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e; ?>
<?php unset($__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e); ?>
<?php endif; ?>
</div>


<div class="ds-dashboard-grid ds-dashboard-grid--sidebar mb-4">
    
    <?php if (isset($component)) { $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'My Classes']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'My Classes']); ?>
        <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="ds-list-item">
                <div class="ds-list-item-info">
                    <p class="ds-list-item-name"><?php echo e($class->name); ?><?php if($class->section): ?> — <?php echo e($class->section); ?><?php endif; ?></p>
                    <p class="ds-list-item-meta"><?php echo e($class->students_count); ?> students</p>
                </div>
                <div class="ds-list-item-value">
                    <span class="sb-badge sb-badge-primary"><?php echo e($class->students_count); ?></span>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php if (isset($component)) { $__componentOriginal50f6691cb7e71446f1706a70a912a0e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50f6691cb7e71446f1706a70a912a0e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.empty-state','data' => ['message' => 'No classes assigned yet','icon' => 'empty','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['message' => 'No classes assigned yet','icon' => 'empty','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal50f6691cb7e71446f1706a70a912a0e8)): ?>
<?php $attributes = $__attributesOriginal50f6691cb7e71446f1706a70a912a0e8; ?>
<?php unset($__attributesOriginal50f6691cb7e71446f1706a70a912a0e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal50f6691cb7e71446f1706a70a912a0e8)): ?>
<?php $component = $__componentOriginal50f6691cb7e71446f1706a70a912a0e8; ?>
<?php unset($__componentOriginal50f6691cb7e71446f1706a70a912a0e8); ?>
<?php endif; ?>
        <?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e)): ?>
<?php $attributes = $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e; ?>
<?php unset($__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e)): ?>
<?php $component = $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e; ?>
<?php unset($__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e); ?>
<?php endif; ?>

    
    <div class="ds-dashboard-sidebar-stack">
        <?php if (isset($component)) { $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'My Subjects']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'My Subjects']); ?>
            <?php $__empty_1 = true; $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="ds-list-item">
                    <div class="ds-list-item-info">
                        <p class="ds-list-item-name"><?php echo e($subject->name); ?></p>
                    </div>
                    <span class="sb-badge sb-badge-secondary">Active</span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php if (isset($component)) { $__componentOriginal50f6691cb7e71446f1706a70a912a0e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50f6691cb7e71446f1706a70a912a0e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.empty-state','data' => ['message' => 'No subjects assigned yet','icon' => 'empty','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['message' => 'No subjects assigned yet','icon' => 'empty','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal50f6691cb7e71446f1706a70a912a0e8)): ?>
<?php $attributes = $__attributesOriginal50f6691cb7e71446f1706a70a912a0e8; ?>
<?php unset($__attributesOriginal50f6691cb7e71446f1706a70a912a0e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal50f6691cb7e71446f1706a70a912a0e8)): ?>
<?php $component = $__componentOriginal50f6691cb7e71446f1706a70a912a0e8; ?>
<?php unset($__componentOriginal50f6691cb7e71446f1706a70a912a0e8); ?>
<?php endif; ?>
            <?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e)): ?>
<?php $attributes = $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e; ?>
<?php unset($__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e)): ?>
<?php $component = $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e; ?>
<?php unset($__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e); ?>
<?php endif; ?>

        <?php if($activeAnnouncements->isNotEmpty()): ?>
            <?php if (isset($component)) { $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'Announcements']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Announcements']); ?>
                <?php $__currentLoopData = $activeAnnouncements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="ds-announcement-item">
                        <div class="ds-announcement-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        </div>
                        <div class="ds-announcement-content">
                            <p class="ds-announcement-title"><?php echo e($announcement->title); ?></p>
                            <p class="ds-announcement-meta"><?php echo e($announcement->created_at->diffForHumans()); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e)): ?>
<?php $attributes = $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e; ?>
<?php unset($__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e)): ?>
<?php $component = $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e; ?>
<?php unset($__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e); ?>
<?php endif; ?>
        <?php endif; ?>
    </div>
</div>


<div class="ds-dashboard-grid ds-dashboard-grid--2 mb-4">
    
    <?php if (isset($component)) { $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'Upcoming Assignments','href' => route('assignments.index'),'hrefLabel' => 'View All']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Upcoming Assignments','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('assignments.index')),'hrefLabel' => 'View All']); ?>
        <?php $__empty_1 = true; $__currentLoopData = $upcomingAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="ds-list-item">
                <div class="ds-list-item-info">
                    <p class="ds-list-item-name"><?php echo e($assignment->title); ?></p>
                    <p class="ds-list-item-meta"><?php echo e($assignment->schoolClass->name ?? ''); ?> &middot; <?php echo e($assignment->subject->name ?? ''); ?></p>
                </div>
                <span class="sb-badge sb-badge-warning">Due <?php echo e($assignment->due_date->format('M d')); ?></span>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php if (isset($component)) { $__componentOriginal50f6691cb7e71446f1706a70a912a0e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50f6691cb7e71446f1706a70a912a0e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.empty-state','data' => ['message' => 'No upcoming assignments','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['message' => 'No upcoming assignments','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal50f6691cb7e71446f1706a70a912a0e8)): ?>
<?php $attributes = $__attributesOriginal50f6691cb7e71446f1706a70a912a0e8; ?>
<?php unset($__attributesOriginal50f6691cb7e71446f1706a70a912a0e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal50f6691cb7e71446f1706a70a912a0e8)): ?>
<?php $component = $__componentOriginal50f6691cb7e71446f1706a70a912a0e8; ?>
<?php unset($__componentOriginal50f6691cb7e71446f1706a70a912a0e8); ?>
<?php endif; ?>
        <?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e)): ?>
<?php $attributes = $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e; ?>
<?php unset($__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e)): ?>
<?php $component = $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e; ?>
<?php unset($__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'Recent Score Entries','href' => route('teacher.scores.history'),'hrefLabel' => 'View All']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Recent Score Entries','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('teacher.scores.history')),'hrefLabel' => 'View All']); ?>
        <?php $__empty_1 = true; $__currentLoopData = $recentResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="ds-list-item">
                <div class="ds-list-item-info">
                    <p class="ds-list-item-name"><?php echo e($result->student->full_name ?? 'N/A'); ?></p>
                    <p class="ds-list-item-meta"><?php echo e($result->subject->name ?? ''); ?> &middot; <?php echo e($result->exam->name ?? ''); ?></p>
                </div>
                <div class="ds-list-item-value" style="color: <?php echo e($result->score >= 70 ? 'var(--success)' : ($result->score >= 50 ? 'var(--warning)' : 'var(--danger)')); ?>;">
                    <?php echo e($result->score); ?>%
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php if (isset($component)) { $__componentOriginal50f6691cb7e71446f1706a70a912a0e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50f6691cb7e71446f1706a70a912a0e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.empty-state','data' => ['message' => 'No score entries yet','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['message' => 'No score entries yet','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal50f6691cb7e71446f1706a70a912a0e8)): ?>
<?php $attributes = $__attributesOriginal50f6691cb7e71446f1706a70a912a0e8; ?>
<?php unset($__attributesOriginal50f6691cb7e71446f1706a70a912a0e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal50f6691cb7e71446f1706a70a912a0e8)): ?>
<?php $component = $__componentOriginal50f6691cb7e71446f1706a70a912a0e8; ?>
<?php unset($__componentOriginal50f6691cb7e71446f1706a70a912a0e8); ?>
<?php endif; ?>
        <?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e)): ?>
<?php $attributes = $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e; ?>
<?php unset($__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e)): ?>
<?php $component = $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e; ?>
<?php unset($__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e); ?>
<?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (!window.SkulCharts) return;

        const weekLabels = <?php echo json_encode($chartData['weekly_labels'], 15, 512) ?>;
        const weekPresent = <?php echo json_encode($chartData['weekly_present'], 15, 512) ?>;
        const weekTotal = <?php echo json_encode($chartData['weekly_total'], 15, 512) ?>;
        if (weekLabels.length) {
            window.SkulCharts.createBarChart('chartWeeklyAttendance', {
                labels: weekLabels,
                datasets: [
                    { label: 'Total', data: weekTotal, backgroundColor: '#E2E8F0', borderRadius: 6 },
                    { label: 'Present', data: weekPresent, backgroundColor: '#10B981', borderRadius: 6 },
                ],
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/teacher/dashboard.blade.php ENDPATH**/ ?>