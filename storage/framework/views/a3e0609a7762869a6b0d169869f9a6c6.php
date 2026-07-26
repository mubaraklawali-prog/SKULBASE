<?php $__env->startSection('title', 'Page Not Found'); ?>

<?php $__env->startSection('content'); ?>
<div class="error-icon icon-warning">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"></circle>
        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        <line x1="8" y1="11" x2="14" y2="11"></line>
    </svg>
</div>
<div class="error-code">404</div>
<h2>Page Not Found</h2>
<p>The page you're looking for doesn't exist or may have been moved. Please check the URL or navigate back to your dashboard.</p>
<div class="error-actions">
    <a href="<?php echo e(url()->previous()); ?>" class="sb-btn sb-btn-ghost">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        Go Back
    </a>
    <?php if(auth()->guard()->check()): ?>
        <a href="<?php echo e(route('dashboard')); ?>" class="sb-btn sb-btn-primary">Dashboard</a>
    <?php else: ?>
        <a href="<?php echo e(route('login')); ?>" class="sb-btn sb-btn-primary">Sign In</a>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('errors.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/errors/404.blade.php ENDPATH**/ ?>