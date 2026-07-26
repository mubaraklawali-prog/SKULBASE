<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => '',
    'subtitle' => '',
    'href' => null,
    'hrefLabel' => 'View All',
    'noPadding' => false,
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
    'subtitle' => '',
    'href' => null,
    'hrefLabel' => 'View All',
    'noPadding' => false,
    'class' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="ds-widget-card <?php echo e($class); ?>">
    <div class="ds-widget-card-header">
        <div>
            <h5 class="ds-widget-card-title"><?php echo e($title); ?></h5>
            <?php if($subtitle): ?>
                <p class="ds-widget-card-subtitle"><?php echo e($subtitle); ?></p>
            <?php endif; ?>
        </div>
        <?php if($href): ?>
            <a href="<?php echo e($href); ?>" class="ds-widget-card-link"><?php echo e($hrefLabel); ?> &rarr;</a>
        <?php endif; ?>
    </div>
    <div class="ds-widget-card-body <?php echo e($noPadding ? 'ds-widget-card-body--flush' : ''); ?>">
        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/components/dashboard/widget-card.blade.php ENDPATH**/ ?>