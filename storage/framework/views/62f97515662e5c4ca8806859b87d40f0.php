<?php $__env->startSection('title', 'Dashboard - ' . ($school->name ?? 'Skulbase')); ?>

<?php $__env->startSection('content'); ?>
<?php
    $isSuperAdmin = $isSuperAdmin ?? (Auth::user()->role === 'super_admin');
    $isParent = $isParent ?? (Auth::user()->role === 'parent');
?>

<?php if($isSuperAdmin): ?>
    
    <?php if (isset($component)) { $__componentOriginal0befbe0186681e93c8b8de70927507df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0befbe0186681e93c8b8de70927507df = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.welcome-banner','data' => ['superAdmin' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.welcome-banner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['superAdmin' => true]); ?>
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

    
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Total Schools','value' => number_format($platformStats['total_schools']),'color' => 'purple','description' => 'All registered schools']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Schools','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($platformStats['total_schools'])),'color' => 'purple','description' => 'All registered schools']); ?>
                 <?php $__env->slot('icon', null, []); ?> 
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Active Schools','value' => number_format($platformStats['active_schools']),'color' => 'green','description' => 'Currently active']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Active Schools','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($platformStats['active_schools'])),'color' => 'green','description' => 'Currently active']); ?>
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
        <div class="col-xl-3 col-lg-6 col-md-6">
            <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Pending Approvals','value' => number_format($platformStats['pending_schools']),'color' => 'warning','description' => 'Awaiting review']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Pending Approvals','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($platformStats['pending_schools'])),'color' => 'warning','description' => 'Awaiting review']); ?>
                 <?php $__env->slot('icon', null, []); ?> 
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Total Users','value' => number_format($platformStats['total_users']),'color' => 'info','description' => 'All platform users']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Users','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($platformStats['total_users'])),'color' => 'info','description' => 'All platform users']); ?>
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
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-xl-4 col-lg-6 col-md-6">
            <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Total Students','value' => number_format($platformStats['total_students']),'color' => 'blue','description' => 'Across all schools']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Students','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($platformStats['total_students'])),'color' => 'blue','description' => 'Across all schools']); ?>
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
        <div class="col-xl-4 col-lg-6 col-md-6">
            <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Total Teachers','value' => number_format($platformStats['total_teachers']),'color' => 'green','description' => 'Across all schools']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Teachers','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($platformStats['total_teachers'])),'color' => 'green','description' => 'Across all schools']); ?>
                 <?php $__env->slot('icon', null, []); ?> 
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path><line x1="8" y1="7" x2="16" y2="7"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
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
        <div class="col-xl-4 col-lg-6 col-md-6">
            <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Total Revenue','value' => '₦'.e(number_format($platformStats['total_revenue'])).'','color' => 'secondary','description' => 'All fee payments']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Revenue','value' => '₦'.e(number_format($platformStats['total_revenue'])).'','color' => 'secondary','description' => 'All fee payments']); ?>
                 <?php $__env->slot('icon', null, []); ?> 
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
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

    <div class="ds-dashboard-grid ds-dashboard-grid--sidebar mb-4">
        
        <?php if (isset($component)) { $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'Recent Schools','href' => route('schools.index'),'hrefLabel' => 'View All']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Recent Schools','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('schools.index')),'hrefLabel' => 'View All']); ?>
            <?php if($recentSchools->isEmpty()): ?>
                <?php if (isset($component)) { $__componentOriginal50f6691cb7e71446f1706a70a912a0e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50f6691cb7e71446f1706a70a912a0e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.empty-state','data' => ['message' => 'No schools registered yet','icon' => 'empty','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['message' => 'No schools registered yet','icon' => 'empty','size' => 'sm']); ?>
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
            <?php else: ?>
                <?php $__currentLoopData = $recentSchools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="ds-school-item">
                        <div class="ds-school-avatar"><?php echo e(substr($s->name, 0, 2)); ?></div>
                        <div class="ds-school-info">
                            <p class="ds-school-name"><?php echo e($s->name); ?></p>
                            <p class="ds-school-meta"><?php echo e($s->email ?? 'No email'); ?> &middot; <?php echo e($s->created_at->diffForHumans()); ?></p>
                        </div>
                        <span class="sb-badge <?php echo e($s->is_active ? 'sb-badge-success' : 'sb-badge-neutral'); ?>">
                            <?php echo e($s->is_active ? 'Active' : 'Inactive'); ?>

                        </span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'Quick Actions']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Quick Actions']); ?>
            <div class="ds-quick-actions">
                <a href="<?php echo e(route('schools.index')); ?>" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--primary-light); color: var(--primary);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                    </div>
                    <span class="ds-quick-action-label">Manage Schools</span>
                </a>
                <a href="<?php echo e(route('pending-schools.index')); ?>" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--warning-light); color: var(--warning);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    </div>
                    <span class="ds-quick-action-label">Review Pending</span>
                </a>
                <a href="<?php echo e(route('plans.index')); ?>" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--success-light); color: var(--success);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    </div>
                    <span class="ds-quick-action-label">Manage Plans</span>
                </a>
                <a href="<?php echo e(route('subscriptions.index')); ?>" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--info-light); color: var(--info);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 4H3a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h18a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                    </div>
                    <span class="ds-quick-action-label">Subscriptions</span>
                </a>
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

    
    <div class="ds-dashboard-grid ds-dashboard-grid--full mb-4">
        <?php if (isset($component)) { $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'Recent Platform Activity']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Recent Platform Activity']); ?>
            <?php if($recentActivity->isEmpty()): ?>
                <?php if (isset($component)) { $__componentOriginal50f6691cb7e71446f1706a70a912a0e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50f6691cb7e71446f1706a70a912a0e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.empty-state','data' => ['message' => 'No recent activity','icon' => 'calendar','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['message' => 'No recent activity','icon' => 'calendar','size' => 'sm']); ?>
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
            <?php else: ?>
                <?php if (isset($component)) { $__componentOriginal0a38c7c7091492d35b9edec0fbd0f803 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a38c7c7091492d35b9edec0fbd0f803 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.activity-timeline','data' => ['items' => $recentActivity]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.activity-timeline'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($recentActivity)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a38c7c7091492d35b9edec0fbd0f803)): ?>
<?php $attributes = $__attributesOriginal0a38c7c7091492d35b9edec0fbd0f803; ?>
<?php unset($__attributesOriginal0a38c7c7091492d35b9edec0fbd0f803); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a38c7c7091492d35b9edec0fbd0f803)): ?>
<?php $component = $__componentOriginal0a38c7c7091492d35b9edec0fbd0f803; ?>
<?php unset($__componentOriginal0a38c7c7091492d35b9edec0fbd0f803); ?>
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

<?php elseif($isParent): ?>
    
    <?php if (isset($component)) { $__componentOriginal0befbe0186681e93c8b8de70927507df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0befbe0186681e93c8b8de70927507df = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.welcome-banner','data' => ['school' => $school,'subscription' => $subscription,'schoolSetting' => $schoolSetting]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.welcome-banner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['school' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($school),'subscription' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($subscription),'schoolSetting' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($schoolSetting)]); ?>
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

    
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'My Children','value' => $parentStats['total_children'],'color' => 'purple']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'My Children','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($parentStats['total_children']),'color' => 'purple']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Today\'s Attendance','value' => ''.e($parentStats['today_attendance_rate']).'%','color' => 'green']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Today\'s Attendance','value' => ''.e($parentStats['today_attendance_rate']).'%','color' => 'green']); ?>
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
        <div class="col-xl-3 col-lg-6 col-md-6">
            <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Fees Paid','value' => '₦'.e(number_format($parentStats['total_paid'])).'','color' => 'green']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Fees Paid','value' => '₦'.e(number_format($parentStats['total_paid'])).'','color' => 'green']); ?>
                 <?php $__env->slot('icon', null, []); ?> 
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Outstanding Fees','value' => '₦'.e(number_format($parentStats['outstanding'])).'','color' => 'red']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Outstanding Fees','value' => '₦'.e(number_format($parentStats['outstanding'])).'','color' => 'red']); ?>
                 <?php $__env->slot('icon', null, []); ?> 
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
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

    <div class="row g-4">
        <div class="col-lg-7">
            <?php if (isset($component)) { $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'My Children']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'My Children']); ?>
                <?php $__empty_1 = true; $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="ds-child-card mb-3">
                        <div class="ds-child-header">
                            <div class="ds-child-avatar">
                                <?php echo e(substr($child->first_name, 0, 1)); ?><?php echo e(substr($child->last_name, 0, 1)); ?>

                            </div>
                            <div>
                                <h5 class="ds-child-name"><?php echo e($child->full_name); ?></h5>
                                <p class="ds-child-detail"><?php echo e($child->admission_number); ?> &middot; <?php echo e($child->schoolClass->name ?? ''); ?></p>
                            </div>
                        </div>

                        <div class="ds-child-stats">
                            <div class="ds-child-stat">
                                <div class="ds-child-stat-value"><?php echo e($child->present_days ?? 0); ?>/<?php echo e($child->total_attendance_days ?? 0); ?></div>
                                <div class="ds-child-stat-label">Attendance</div>
                            </div>
                            <div class="ds-child-stat">
                                <?php
                                    $childAttendRate = ($child->total_attendance_days ?? 0) > 0
                                        ? round(($child->present_days ?? 0) / $child->total_attendance_days * 100)
                                        : 0;
                                ?>
                                <div class="ds-child-stat-value" style="color: <?php echo e($childAttendRate >= 75 ? 'var(--success)' : ($childAttendRate >= 50 ? 'var(--warning)' : 'var(--danger)')); ?>;"><?php echo e($childAttendRate); ?>%</div>
                                <div class="ds-child-stat-label">Rate</div>
                            </div>
                        </div>

                        <div class="ds-child-links">
                            <a href="<?php echo e(route('parent.attendance.index', ['student_id' => $child->id])); ?>" class="ds-child-link">
                                <span>Attendance</span>
                                <span class="ds-child-link-arrow">&rarr;</span>
                            </a>
                            <a href="<?php echo e(route('parent.results.index', ['student_id' => $child->id])); ?>" class="ds-child-link">
                                <span>Results</span>
                                <span class="ds-child-link-arrow">&rarr;</span>
                            </a>
                            <a href="<?php echo e(route('parent.fees.index', ['student_id' => $child->id])); ?>" class="ds-child-link">
                                <span>Fees</span>
                                <span class="ds-child-link-arrow">&rarr;</span>
                            </a>
                            <a href="<?php echo e(route('parent.assignments.index', ['student_id' => $child->id])); ?>" class="ds-child-link">
                                <span>Assignments</span>
                                <span class="ds-child-link-arrow">&rarr;</span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <?php if (isset($component)) { $__componentOriginal50f6691cb7e71446f1706a70a912a0e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50f6691cb7e71446f1706a70a912a0e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.empty-state','data' => ['message' => 'No children linked to your account','icon' => 'users','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['message' => 'No children linked to your account','icon' => 'users','size' => 'sm']); ?>
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

        <div class="col-lg-5">
            <?php if (isset($component)) { $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'Recent Activity','class' => 'mb-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Recent Activity','class' => 'mb-4']); ?>
                <?php if($recentActivity->isEmpty()): ?>
                    <?php if (isset($component)) { $__componentOriginal50f6691cb7e71446f1706a70a912a0e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50f6691cb7e71446f1706a70a912a0e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.empty-state','data' => ['message' => 'No recent activity','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['message' => 'No recent activity','size' => 'sm']); ?>
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
                <?php else: ?>
                    <?php if (isset($component)) { $__componentOriginal0a38c7c7091492d35b9edec0fbd0f803 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a38c7c7091492d35b9edec0fbd0f803 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.activity-timeline','data' => ['items' => $recentActivity]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.activity-timeline'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($recentActivity)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a38c7c7091492d35b9edec0fbd0f803)): ?>
<?php $attributes = $__attributesOriginal0a38c7c7091492d35b9edec0fbd0f803; ?>
<?php unset($__attributesOriginal0a38c7c7091492d35b9edec0fbd0f803); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a38c7c7091492d35b9edec0fbd0f803)): ?>
<?php $component = $__componentOriginal0a38c7c7091492d35b9edec0fbd0f803; ?>
<?php unset($__componentOriginal0a38c7c7091492d35b9edec0fbd0f803); ?>
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

            <?php if($upcomingEvents->isNotEmpty()): ?>
                <?php if (isset($component)) { $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'Upcoming Events']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Upcoming Events']); ?>
                    <?php $__currentLoopData = $upcomingEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="ds-event-item">
                            <div class="ds-event-date-box">
                                <span class="ds-event-day"><?php echo e($event->event_date->format('d')); ?></span>
                                <span class="ds-event-month"><?php echo e($event->event_date->format('M')); ?></span>
                            </div>
                            <div class="ds-event-info">
                                <p class="ds-event-title"><?php echo e($event->title); ?></p>
                                <?php if($event->description): ?>
                                    <p class="ds-event-desc"><?php echo e(Str::limit($event->description, 60)); ?></p>
                                <?php endif; ?>
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

<?php else: ?>
    
    <?php if (isset($component)) { $__componentOriginal0befbe0186681e93c8b8de70927507df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0befbe0186681e93c8b8de70927507df = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.welcome-banner','data' => ['school' => $school,'subscription' => $subscription,'schoolSetting' => $schoolSetting]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.welcome-banner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['school' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($school),'subscription' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($subscription),'schoolSetting' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($schoolSetting)]); ?>
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

    
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Total Students','value' => number_format($stats['total_students']),'color' => 'purple','description' => ''.e($stats['active_students']).' active']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Students','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($stats['total_students'])),'color' => 'purple','description' => ''.e($stats['active_students']).' active']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Total Teachers','value' => number_format($stats['total_teachers']),'color' => 'green','description' => ''.e($stats['active_teachers']).' active']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Teachers','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($stats['total_teachers'])),'color' => 'green','description' => ''.e($stats['active_teachers']).' active']); ?>
                 <?php $__env->slot('icon', null, []); ?> 
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path><line x1="8" y1="7" x2="16" y2="7"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Today\'s Attendance','value' => ''.e($stats['today_attendance_rate']).'%','color' => 'info','description' => 'Present today']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Today\'s Attendance','value' => ''.e($stats['today_attendance_rate']).'%','color' => 'info','description' => 'Present today']); ?>
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
        <div class="col-xl-3 col-lg-6 col-md-6">
            <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Pending Admissions','value' => number_format($schoolAdminStats['pending_admissions']),'color' => 'warning','description' => 'Awaiting review']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Pending Admissions','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($schoolAdminStats['pending_admissions'])),'color' => 'warning','description' => 'Awaiting review']); ?>
                 <?php $__env->slot('icon', null, []); ?> 
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
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

    
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Fees Collected','value' => '₦'.e(number_format($stats['total_collected'])).'','color' => 'green','description' => ''.e(number_format($stats['total_fee_payments'])).' payments']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Fees Collected','value' => '₦'.e(number_format($stats['total_collected'])).'','color' => 'green','description' => ''.e(number_format($stats['total_fee_payments'])).' payments']); ?>
                 <?php $__env->slot('icon', null, []); ?> 
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Outstanding Fees','value' => '₦'.e(number_format($stats['total_outstanding'])).'','color' => 'red','description' => 'Pending collection']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Outstanding Fees','value' => '₦'.e(number_format($stats['total_outstanding'])).'','color' => 'red','description' => 'Pending collection']); ?>
                 <?php $__env->slot('icon', null, []); ?> 
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Total Parents','value' => number_format($schoolAdminStats['total_parents']),'color' => 'blue','description' => 'Registered parents']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Parents','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($schoolAdminStats['total_parents'])),'color' => 'blue','description' => 'Registered parents']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'Total Classes','value' => number_format($stats['total_classes']),'color' => 'indigo','description' => ''.e($stats['total_subjects']).' subjects']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Classes','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($stats['total_classes'])),'color' => 'indigo','description' => ''.e($stats['total_subjects']).' subjects']); ?>
                 <?php $__env->slot('icon', null, []); ?> 
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
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

    
    <div class="row g-4 mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="ds-chart-card">
                <div class="ds-chart-card-header">
                    <div>
                        <h3 class="ds-chart-card-title">Student Enrollment Trend</h3>
                        <p class="ds-chart-card-subtitle">Monthly student registrations (last 6 months)</p>
                    </div>
                </div>
                <div class="ds-chart-card-body" style="height: 280px;">
                    <canvas id="chartStudentGrowth"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5">
            <div class="ds-chart-card">
                <div class="ds-chart-card-header">
                    <div>
                        <h3 class="ds-chart-card-title">Students by Class</h3>
                        <p class="ds-chart-card-subtitle">Active students per class</p>
                    </div>
                </div>
                <div class="ds-chart-card-body" style="height: 280px;">
                    <canvas id="chartStudentsByClass"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="ds-chart-card">
                <div class="ds-chart-card-header">
                    <div>
                        <h3 class="ds-chart-card-title">Fee Collection Trend</h3>
                        <p class="ds-chart-card-subtitle">Monthly fee collections (last 6 months)</p>
                    </div>
                    <div class="ds-chart-card-actions">
                        <a href="<?php echo e(route('fees.dashboard')); ?>" class="ds-widget-card-link">View Report &rarr;</a>
                    </div>
                </div>
                <div class="ds-chart-card-body" style="height: 280px;">
                    <canvas id="chartFeeCollection"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5">
            
            <div class="ds-widget-card">
                <div class="ds-widget-card-header">
                    <h3 class="ds-widget-card-title">Academic Overview</h3>
                </div>
                <div class="ds-widget-card-body">
                    <?php if($academicSummary): ?>
                        <div class="ds-academic-overview">
                            <div class="ds-academic-item">
                                <span class="ds-academic-label">Latest Exam</span>
                                <span class="ds-academic-value"><?php echo e($academicSummary['exam_name']); ?></span>
                            </div>
                            <div class="ds-academic-item">
                                <span class="ds-academic-label">Average Score</span>
                                <span class="ds-academic-value ds-academic-value--highlight"><?php echo e($academicSummary['avg_score']); ?>%</span>
                            </div>
                            <div class="ds-academic-item">
                                <span class="ds-academic-label">Results Entered</span>
                                <span class="ds-academic-value"><?php echo e(number_format($academicSummary['total_results'])); ?></span>
                            </div>
                            <div class="ds-academic-item">
                                <span class="ds-academic-label">Total Subjects</span>
                                <span class="ds-academic-value"><?php echo e(number_format($stats['total_subjects'])); ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php if (isset($component)) { $__componentOriginal50f6691cb7e71446f1706a70a912a0e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50f6691cb7e71446f1706a70a912a0e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.empty-state','data' => ['message' => 'No exam data available yet','icon' => 'clipboard','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['message' => 'No exam data available yet','icon' => 'clipboard','size' => 'sm']); ?>
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
            </div>
        </div>
    </div>

    
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
                <a href="<?php echo e(route('students.create')); ?>" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--primary-light); color: var(--primary);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                    </div>
                    <span class="ds-quick-action-label">Add Student</span>
                </a>
                <a href="<?php echo e(route('teachers.create')); ?>" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--success-light); color: var(--success);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    </div>
                    <span class="ds-quick-action-label">Add Teacher</span>
                </a>
                <a href="<?php echo e(route('attendance.create')); ?>" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--info-light); color: var(--info);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                    <span class="ds-quick-action-label">Attendance</span>
                </a>
                <a href="<?php echo e(route('results.scores.dashboard')); ?>" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--warning-light); color: var(--warning);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </div>
                    <span class="ds-quick-action-label">Record Score</span>
                </a>
                <a href="<?php echo e(route('announcements.create')); ?>" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--danger-light); color: var(--danger);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    </div>
                    <span class="ds-quick-action-label">Announce</span>
                </a>
                <a href="<?php echo e(route('classes.index')); ?>" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--primary-light); color: var(--primary);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                    </div>
                    <span class="ds-quick-action-label">Classes</span>
                </a>
                <a href="<?php echo e(route('fees.dashboard')); ?>" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--success-light); color: var(--success);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    </div>
                    <span class="ds-quick-action-label">Fees</span>
                </a>
                <a href="<?php echo e(route('reports.dashboard')); ?>" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--warning-light); color: var(--warning);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                    </div>
                    <span class="ds-quick-action-label">Reports</span>
                </a>
                <a href="<?php echo e(route('admissions.index')); ?>" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: var(--warning-light); color: var(--warning);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                    </div>
                    <span class="ds-quick-action-label">Admissions</span>
                </a>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'Recent Activity','href' => route('students.index'),'hrefLabel' => 'View All']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Recent Activity','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('students.index')),'hrefLabel' => 'View All']); ?>
            <?php if($recentActivity->isEmpty()): ?>
                <?php if (isset($component)) { $__componentOriginal50f6691cb7e71446f1706a70a912a0e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50f6691cb7e71446f1706a70a912a0e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.empty-state','data' => ['message' => 'No recent activity in the last 7 days','icon' => 'calendar','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['message' => 'No recent activity in the last 7 days','icon' => 'calendar','size' => 'sm']); ?>
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
            <?php else: ?>
                <?php if (isset($component)) { $__componentOriginal0a38c7c7091492d35b9edec0fbd0f803 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a38c7c7091492d35b9edec0fbd0f803 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.activity-timeline','data' => ['items' => $recentActivity]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.activity-timeline'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($recentActivity)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a38c7c7091492d35b9edec0fbd0f803)): ?>
<?php $attributes = $__attributesOriginal0a38c7c7091492d35b9edec0fbd0f803; ?>
<?php unset($__attributesOriginal0a38c7c7091492d35b9edec0fbd0f803); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a38c7c7091492d35b9edec0fbd0f803)): ?>
<?php $component = $__componentOriginal0a38c7c7091492d35b9edec0fbd0f803; ?>
<?php unset($__componentOriginal0a38c7c7091492d35b9edec0fbd0f803); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'Recent Admissions']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Recent Admissions']); ?>
                <?php if($recentAdmissions->isEmpty()): ?>
                    <?php if (isset($component)) { $__componentOriginal50f6691cb7e71446f1706a70a912a0e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50f6691cb7e71446f1706a70a912a0e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.empty-state','data' => ['message' => 'No recent admissions','icon' => 'users','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['message' => 'No recent admissions','icon' => 'users','size' => 'sm']); ?>
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
                <?php else: ?>
                    <?php $__currentLoopData = $recentAdmissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="ds-list-item">
                            <div class="ds-list-item-info">
                                <p class="ds-list-item-name"><?php echo e($admission->full_name); ?></p>
                                <p class="ds-list-item-meta"><?php echo e($admission->application_number); ?> &middot; <?php echo e($admission->created_at->diffForHumans()); ?></p>
                            </div>
                            <span class="sb-badge sb-badge-<?php echo e($admission->status === 'approved' ? 'success' : ($admission->status === 'rejected' ? 'danger' : 'warning')); ?>">
                                <?php echo e(ucfirst($admission->status)); ?>

                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'Notifications']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Notifications']); ?>
                <div class="ds-notification-summary">
                    <a href="<?php echo e(route('admissions.index')); ?>" class="ds-notification-item">
                        <div class="ds-notification-icon" style="background: var(--warning-light); color: var(--warning);">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                        </div>
                        <span class="ds-notification-label">Pending Admissions</span>
                        <span class="ds-notification-count"><?php echo e($schoolAdminStats['pending_admissions']); ?></span>
                    </a>
                    <a href="<?php echo e(route('messages.inbox')); ?>" class="ds-notification-item">
                        <div class="ds-notification-icon" style="background: var(--info-light); color: var(--info);">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </div>
                        <span class="ds-notification-label">Unread Messages</span>
                        <span class="ds-notification-count"><?php echo e($schoolAdminStats['unread_messages']); ?></span>
                    </a>
                    <a href="<?php echo e(route('announcements.index')); ?>" class="ds-notification-item">
                        <div class="ds-notification-icon" style="background: var(--primary-light); color: var(--primary);">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        </div>
                        <span class="ds-notification-label">Active Announcements</span>
                        <span class="ds-notification-count"><?php echo e($schoolAdminStats['active_announcements']); ?></span>
                    </a>
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
    </div>

    
    <div class="ds-dashboard-grid ds-dashboard-grid--2 mb-4">
        
        <?php if (isset($component)) { $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'Recent Payments','href' => route('fees.payments.index'),'hrefLabel' => 'View All']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Recent Payments','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('fees.payments.index')),'hrefLabel' => 'View All']); ?>
            <?php if($recentPayments->isEmpty()): ?>
                <?php if (isset($component)) { $__componentOriginal50f6691cb7e71446f1706a70a912a0e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50f6691cb7e71446f1706a70a912a0e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.empty-state','data' => ['message' => 'No recent payments','icon' => 'dollar','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['message' => 'No recent payments','icon' => 'dollar','size' => 'sm']); ?>
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
            <?php else: ?>
                <?php $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="ds-list-item">
                        <div class="ds-list-item-info">
                            <p class="ds-list-item-name"><?php echo e($payment->student->full_name ?? 'Unknown Student'); ?></p>
                            <p class="ds-list-item-meta"><?php echo e($payment->feeStructure->title ?? 'Fee'); ?> &middot; <?php echo e($payment->payment_date->format('M d, Y')); ?></p>
                        </div>
                        <span class="ds-list-item-value" style="color: var(--success);">₦<?php echo e(number_format($payment->amount_paid)); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'Active Announcements','href' => route('announcements.index'),'hrefLabel' => 'View All']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Active Announcements','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('announcements.index')),'hrefLabel' => 'View All']); ?>
            <?php if($activeAnnouncements->isEmpty()): ?>
                <?php if (isset($component)) { $__componentOriginal50f6691cb7e71446f1706a70a912a0e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50f6691cb7e71446f1706a70a912a0e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.empty-state','data' => ['message' => 'No active announcements','icon' => 'bell','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['message' => 'No active announcements','icon' => 'bell','size' => 'sm']); ?>
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
            <?php else: ?>
                <?php $__currentLoopData = $activeAnnouncements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="ds-announcement-item">
                        <div class="ds-announcement-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        </div>
                        <div class="ds-announcement-content">
                            <p class="ds-announcement-title"><?php echo e($announcement->title); ?></p>
                            <p class="ds-announcement-meta"><?php echo e($announcement->created_at->diffForHumans()); ?> &middot; <?php echo e(ucfirst($announcement->audience ?? 'everyone')); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

    
    <?php if($upcomingEvents->isNotEmpty()): ?>
        <div class="ds-dashboard-grid ds-dashboard-grid--full mb-4">
            <?php if (isset($component)) { $__componentOriginal46d7df772faf884d6cbb5c75b0b04b6e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal46d7df772faf884d6cbb5c75b0b04b6e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.widget-card','data' => ['title' => 'Upcoming Events']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.widget-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Upcoming Events']); ?>
                <?php $__currentLoopData = $upcomingEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="ds-event-item">
                        <div class="ds-event-date-box">
                            <span class="ds-event-day"><?php echo e($event->event_date->format('d')); ?></span>
                            <span class="ds-event-month"><?php echo e($event->event_date->format('M')); ?></span>
                        </div>
                        <div class="ds-event-info">
                            <p class="ds-event-title"><?php echo e($event->title); ?></p>
                            <?php if($event->description): ?>
                                <p class="ds-event-desc"><?php echo e(Str::limit($event->description, 80)); ?></p>
                            <?php endif; ?>
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
        </div>
    <?php endif; ?>

    
    <?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!window.SkulCharts) return;

            const growthLabels = <?php echo json_encode($chartData['student_growth_labels'], 15, 512) ?>;
            const growthData = <?php echo json_encode($chartData['student_growth_data'], 15, 512) ?>;
            if (growthLabels.length) {
                window.SkulCharts.createAreaChart('chartStudentGrowth', {
                    labels: growthLabels,
                    datasets: [{
                        label: 'Students',
                        data: growthData,
                        color: '#5B21FF',
                        backgroundColor: '#5B21FF18',
                    }],
                });
            }

            const classLabels = <?php echo json_encode($chartData['students_by_class_labels'], 15, 512) ?>;
            const classData = <?php echo json_encode($chartData['students_by_class_data'], 15, 512) ?>;
            if (classLabels.length) {
                window.SkulCharts.createDoughnutChart('chartStudentsByClass', {
                    labels: classLabels,
                    data: classData,
                });
            }

            const feeLabels = <?php echo json_encode($chartData['fee_collection_labels'], 15, 512) ?>;
            const feeData = <?php echo json_encode($chartData['fee_collection_data'], 15, 512) ?>;
            if (feeLabels.length) {
                window.SkulCharts.createBarChart('chartFeeCollection', {
                    labels: feeLabels,
                    datasets: [{
                        label: 'Collections',
                        data: feeData,
                        backgroundColor: '#10B981',
                        borderRadius: 6,
                    }],
                });
            }
        });
    </script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/dashboard.blade.php ENDPATH**/ ?>