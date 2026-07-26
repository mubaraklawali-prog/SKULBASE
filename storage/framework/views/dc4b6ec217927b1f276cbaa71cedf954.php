<?php $__env->startSection('title', 'Inbox - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Messages</h2>
            <p class="text-muted mb-0">Your inbox</p>
        </div>
        <a href="<?php echo e(route('messages.create')); ?>" class="sb-btn sb-btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Compose
        </a>
    </div>

    <div class="d-flex gap-2 mb-4">
        <a href="<?php echo e(route('messages.inbox')); ?>" class="sb-btn sb-btn-primary">Inbox</a>
        <a href="<?php echo e(route('messages.sent')); ?>" class="sb-btn sb-btn-secondary">Sent</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('messages.inbox')); ?>" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="sb-form-label">Search by Subject</label>
                    <input type="text" name="search" class="sb-form-input" value="<?php echo e(request('search')); ?>" placeholder="Search messages...">
                </div>
                <div class="col-6 col-md-2">
                    <label class="sb-form-label">Status</label>
                    <select name="status" class="sb-form-select">
                        <option value="">All</option>
                        <option value="unread" <?php echo e(request('status') == 'unread' ? 'selected' : ''); ?>>Unread</option>
                        <option value="read" <?php echo e(request('status') == 'read' ? 'selected' : ''); ?>>Read</option>
                    </select>
                </div>
                <div class="col-6 col-md-1">
                    <button type="submit" class="sb-btn sb-btn-primary w-100">Filter</button>
                </div>
                <?php if(request()->hasAny(['search', 'status'])): ?>
                    <div class="col-6 col-md-1">
                        <a href="<?php echo e(route('messages.inbox')); ?>" class="sb-btn sb-btn-secondary w-100 text-center">Clear</a>
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
                            <th style="width: 30px;"></th>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr <?php echo e($message->status === 'unread' ? 'style="background: #f8f9ff;"' : ''); ?>>
                                <td>
                                    <?php if($message->status === 'unread'): ?>
                                        <span style="display: inline-block; width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></span>
                                    <?php endif; ?>
                                </td>
                                <td <?php echo e($message->status === 'unread' ? 'style="font-weight: 600;"' : ''); ?>>
                                    <?php echo e($message->sender->name ?? 'Unknown'); ?>

                                </td>
                                <td <?php echo e($message->status === 'unread' ? 'style="font-weight: 600;"' : ''); ?>>
                                    <a href="<?php echo e(route('messages.show', $message)); ?>" style="color: #0a1628; text-decoration: none;"><?php echo e($message->subject); ?></a>
                                    <?php if($message->attachment): ?>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-left: 4px;">
                                            <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                                        </svg>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted">
                                    <?php echo e($message->created_at->format('M d, Y')); ?>

                                </td>
                                <td>
                                    <?php if($message->status === 'unread'): ?>
                                        <span class="sb-badge sb-badge-unread">Unread</span>
                                    <?php else: ?>
                                        <span class="sb-badge sb-badge-read">Read</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    <div class="table-actions">
                                        <a href="<?php echo e(route('messages.show', $message)); ?>" class="sb-btn sb-btn-sm sb-btn-outline-primary">View</a>
                                        <form action="<?php echo e(route('messages.destroy', $message)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px 20px; color: #6c757d;">
                                    No messages in your inbox. <a href="<?php echo e(route('messages.create')); ?>" style="color: var(--primary);">Send a message</a>.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($messages->hasPages()): ?>
                <div class="d-flex justify-content-center mt-3">
                    <?php echo e($messages->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/messages/inbox.blade.php ENDPATH**/ ?>