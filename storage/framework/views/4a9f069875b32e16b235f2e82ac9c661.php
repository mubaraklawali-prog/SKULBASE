<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'items' => collect(),
    'emptyMessage' => 'No recent activity',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'items' => collect(),
    'emptyMessage' => 'No recent activity',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $iconMap = [
        'student' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>',
        'teacher' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>',
        'payment' => '<line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>',
        'announcement' => '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path>',
        'school' => '<path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path>',
        'event' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>',
    ];
?>

<div class="ds-timeline">
    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="ds-timeline-item">
            <div class="ds-timeline-icon" style="background: <?php echo e($item['color'] ?? '#64748B'); ?>20; color: <?php echo e($item['color'] ?? '#64748B'); ?>;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <?php echo $iconMap[$item['icon'] ?? ''] ?? $iconMap['announcement']; ?>

                </svg>
            </div>
            <div class="ds-timeline-content">
                <p class="ds-timeline-text"><?php echo e($item['text'] ?? ''); ?></p>
                <span class="ds-timeline-time"><?php echo e($item['time']?->diffForHumans() ?? ''); ?></span>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <?php if (isset($component)) { $__componentOriginal50f6691cb7e71446f1706a70a912a0e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50f6691cb7e71446f1706a70a912a0e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.empty-state','data' => ['message' => $emptyMessage,'size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($emptyMessage),'size' => 'sm']); ?>
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
</div>
<?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/components/dashboard/activity-timeline.blade.php ENDPATH**/ ?>