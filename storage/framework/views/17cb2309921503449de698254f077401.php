<?php $__env->startSection('title', 'Announcements - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Announcements</h2>
            <p class="text-muted mb-0">Notice board & school-wide announcements</p>
        </div>
        <?php if($canManage): ?>
            <a href="<?php echo e(route('announcements.create')); ?>" class="sb-btn sb-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Announcement
            </a>
        <?php endif; ?>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('announcements.index')); ?>" class="row g-2 align-items-end">
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="sb-form-label">Search</label>
                    <input type="text" name="search" class="sb-form-input" value="<?php echo e(request('search')); ?>" placeholder="Search by title...">
                </div>
                <div class="col-6 col-md-2">
                    <label class="sb-form-label">Audience</label>
                    <select name="audience" class="sb-form-select">
                        <option value="">All Audiences</option>
                        <option value="everyone" <?php echo e(request('audience') == 'everyone' ? 'selected' : ''); ?>>Everyone</option>
                        <option value="teachers" <?php echo e(request('audience') == 'teachers' ? 'selected' : ''); ?>>Teachers</option>
                        <option value="students" <?php echo e(request('audience') == 'students' ? 'selected' : ''); ?>>Students</option>
                        <option value="parents" <?php echo e(request('audience') == 'parents' ? 'selected' : ''); ?>>Parents</option>
                    </select>
                </div>
                <?php if($canManage): ?>
                    <div class="col-6 col-md-2">
                        <label class="sb-form-label">Status</label>
                        <select name="status" class="sb-form-select">
                            <option value="">All Status</option>
                            <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>>Draft</option>
                            <option value="published" <?php echo e(request('status') == 'published' ? 'selected' : ''); ?>>Published</option>
                        </select>
                    </div>
                <?php endif; ?>
                <div class="col-6 col-md-1">
                    <button type="submit" class="sb-btn sb-btn-primary w-100">Filter</button>
                </div>
                <?php if(request()->hasAny(['search', 'audience', 'status'])): ?>
                    <div class="col-6 col-md-1">
                        <a href="<?php echo e(route('announcements.index')); ?>" class="sb-btn sb-btn-secondary w-100 text-center">Clear</a>
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
                            <th>Title</th>
                            <th>Audience</th>
                            <th>Created By</th>
                            <th>Expires</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <a href="<?php echo e(route('announcements.show', $announcement)); ?>" style="color: #0a1628; font-weight: 600; text-decoration: none;"><?php echo e($announcement->title); ?></a>
                                    <?php if($announcement->attachment): ?>
                                        <br><small class="text-muted">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;">
                                                <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                                            </svg>
                                            Attachment
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="sb-badge sb-badge-info" style="text-transform: capitalize;"><?php echo e($announcement->audience); ?></span>
                                </td>
                                <td><?php echo e($announcement->creator->name ?? '—'); ?></td>
                                <td>
                                    <?php if($announcement->expires_at): ?>
                                        <span class="<?php echo e($announcement->expires_at->isPast() ? 'text-danger' : 'text-muted'); ?>">
                                            <?php echo e($announcement->expires_at->format('M d, Y')); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">Never</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($announcement->status === 'published'): ?>
                                        <span class="sb-badge sb-badge-published">Published</span>
                                    <?php else: ?>
                                        <span class="sb-badge sb-badge-draft">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    <div class="table-actions">
                                        <a href="<?php echo e(route('announcements.show', $announcement)); ?>" class="sb-btn sb-btn-sm sb-btn-outline-primary">View</a>
                                        <?php if($canManage): ?>
                                            <a href="<?php echo e(route('announcements.edit', $announcement)); ?>" class="sb-btn sb-btn-sm sb-btn-secondary">Edit</a>
                                            <form action="<?php echo e(route('announcements.destroy', $announcement)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                    No announcements found.
                                    <?php if($canManage): ?>
                                        <a href="<?php echo e(route('announcements.create')); ?>" style="color: var(--primary);">Create your first announcement</a>.
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($announcements->hasPages()): ?>
                <div class="d-flex justify-content-center mt-3">
                    <?php echo e($announcements->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/announcements/index.blade.php ENDPATH**/ ?>