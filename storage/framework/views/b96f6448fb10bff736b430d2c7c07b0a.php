<?php $__env->startSection('title', 'Students - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Students</h2>
            <p>Manage all registered students</p>
        </div>
        <a href="<?php echo e(route('students.create')); ?>" class="sb-btn sb-btn-primary">+ Add Student</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('students.index')); ?>" class="sb-search-bar">
                <input
                    type="text"
                    name="search"
                    value="<?php echo e(request('search')); ?>"
                    placeholder="Search by name, admission number or email..."
                    class="sb-form-input"
                    style="max-width: 320px;"
                >
                <select
                    name="class_id"
                    class="sb-form-select"
                    style="max-width: 220px;"
                    onchange="this.form.submit()"
                >
                    <option value="">All Classes</option>
                    <?php $__currentLoopData = $schoolClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($class->id); ?>" <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>>
                            <?php echo e($class->name); ?><?php echo e($class->section ? ' - ' . $class->section : ''); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button type="submit" class="sb-btn sb-btn-dark">Search</button>
                <?php if(request('search') || request('class_id')): ?>
                    <a href="<?php echo e(route('students.index')); ?>" class="sb-btn sb-btn-secondary">Clear</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover sb-table mb-0">
                    <thead>
                        <tr>
                            <th>Adm. No.</th>
                            <th>Name</th>
                            <th>School</th>
                            <th>Class</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;"><?php echo e($student->admission_number); ?></code>
                                </td>
                                <td style="font-weight: 500;"><?php echo e($student->full_name); ?></td>
                                <td style="color: #6c757d;"><?php echo e($student->school->name ?? '—'); ?></td>
                                <td>
                                    <?php if($student->schoolClass): ?>
                                        <span class="sb-badge sb-badge-class">
                                            <?php echo e($student->schoolClass->name); ?><?php echo e($student->schoolClass->section ? ' - ' . $student->schoolClass->section : ''); ?>

                                        </span>
                                    <?php else: ?>
                                        <span style="color: #6c757d;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td style="color: #6c757d; text-transform: capitalize;"><?php echo e($student->gender); ?></td>
                                <td>
                                    <?php if($student->status === 'active'): ?>
                                        <span class="sb-badge sb-badge-active">Active</span>
                                    <?php else: ?>
                                        <span class="sb-badge sb-badge-inactive">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="table-actions">
                                        <a href="<?php echo e(route('students.edit', $student)); ?>" class="sb-btn sb-btn-sm sb-btn-outline-primary">Edit</a>
                                        <form method="POST" action="<?php echo e(route('students.destroy', $student)); ?>" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="sb-btn sb-btn-sm sb-btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7">
                                    <div class="sb-empty-state">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                        <h5>No students found</h5>
                                        <p>Get started by registering your first student.</p>
                                        <a href="<?php echo e(route('students.create')); ?>">+ Add Student</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if($students->hasPages()): ?>
        <div class="mt-3" style="display: flex; justify-content: center;">
            <?php echo e($students->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/students/index.blade.php ENDPATH**/ ?>