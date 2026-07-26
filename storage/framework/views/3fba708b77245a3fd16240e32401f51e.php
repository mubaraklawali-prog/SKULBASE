<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => '',
    'value' => '0',
    'color' => 'primary',
    'description' => '',
    'change' => null,
    'changeLabel' => '',
    'href' => null,
    'prefix' => '',
    'class' => '',
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
    'title' => '',
    'value' => '0',
    'color' => 'primary',
    'description' => '',
    'change' => null,
    'changeLabel' => '',
    'href' => null,
    'prefix' => '',
    'class' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $colorMap = [
        'primary' => ['bg' => 'rgba(91,33,255,0.08)', 'fg' => '#5B21FF'],
        'secondary' => ['bg' => '#D1FAE5', 'fg' => '#10B981'],
        'success' => ['bg' => '#F0FDF4', 'fg' => '#22C55E'],
        'warning' => ['bg' => '#FFFBEB', 'fg' => '#F59E0B'],
        'danger' => ['bg' => '#FEF2F2', 'fg' => '#EF4444'],
        'info' => ['bg' => '#EFF6FF', 'fg' => '#3B82F6'],
        'blue' => ['bg' => '#EFF6FF', 'fg' => '#3B82F6'],
        'purple' => ['bg' => 'rgba(91,33,255,0.08)', 'fg' => '#5B21FF'],
        'green' => ['bg' => '#D1FAE5', 'fg' => '#10B981'],
        'orange' => ['bg' => '#FFFBEB', 'fg' => '#F59E0B'],
        'red' => ['bg' => '#FEF2F2', 'fg' => '#EF4444'],
        'pink' => ['bg' => '#FDF2F8', 'fg' => '#EC4899'],
        'indigo' => ['bg' => '#EEF2FF', 'fg' => '#6366F1'],
        'teal' => ['bg' => '#F0FDFA', 'fg' => '#14B8A6'],
        'slate' => ['bg' => '#F1F5F9', 'fg' => '#64748B'],
    ];
    $c = $colorMap[$color] ?? $colorMap['primary'];
    $Tag = $href ? 'a' : 'div';
?>

<<?php echo e($Tag); ?> class="ds-stat-card ds-animate-in <?php echo e($class); ?>" <?php if($href): ?> href="<?php echo e($href); ?>" <?php endif; ?>>
    <div class="ds-stat-card-header">
        <span class="ds-stat-card-title"><?php echo e($title); ?></span>
        <div class="ds-stat-card-icon ds-stat-card-icon--<?php echo e($color); ?>" style="background: <?php echo e($c['bg']); ?>; color: <?php echo e($c['fg']); ?>;">
            <?php echo $icon ?? ''; ?>

        </div>
    </div>
    <div class="ds-stat-card-value"><?php echo e($prefix); ?><?php echo e($value); ?></div>
    <?php if($change !== null): ?>
        <div class="ds-stat-card-footer">
            <span class="ds-stat-change <?php echo e($change >= 0 ? 'ds-stat-change-up' : 'ds-stat-change-down'); ?>">
                <?php if($change >= 0): ?>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"></polyline></svg>
                <?php else: ?>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                <?php endif; ?>
                <?php echo e(abs($change)); ?>%
            </span>
            <?php if($changeLabel): ?>
                <span class="ds-stat-change-label"><?php echo e($changeLabel); ?></span>
            <?php endif; ?>
        </div>
    <?php elseif($description): ?>
        <div class="ds-stat-card-footer">
            <span class="ds-stat-desc"><?php echo e($description); ?></span>
        </div>
    <?php endif; ?>
</<?php echo e($Tag); ?>>
<?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/components/dashboard/stat-card.blade.php ENDPATH**/ ?>