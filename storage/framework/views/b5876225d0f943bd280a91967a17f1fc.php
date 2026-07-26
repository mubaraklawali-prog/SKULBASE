<?php $__env->startSection('title', 'Add Teacher - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="mb-4">
        <h2>Add Teacher</h2>
        <p class="text-muted mb-0">Register a new teacher</p>
    </div>

    <form method="POST" action="<?php echo e(route('teachers.store')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="card stat-card mb-4">
            <div class="card-body" style="padding: 32px;">
                <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Personal Information</h5>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="first_name" class="sb-form-label">First Name <span class="required">*</span></label>
                        <input
                            type="text"
                            name="first_name"
                            id="first_name"
                            value="<?php echo e(old('first_name')); ?>"
                            placeholder="First name"
                            class="sb-form-input <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required
                        >
                        <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="sb-form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="last_name" class="sb-form-label">Last Name <span class="required">*</span></label>
                        <input
                            type="text"
                            name="last_name"
                            id="last_name"
                            value="<?php echo e(old('last_name')); ?>"
                            placeholder="Last name"
                            class="sb-form-input <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required
                        >
                        <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="sb-form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="other_name" class="sb-form-label">Other Name</label>
                        <input
                            type="text"
                            name="other_name"
                            id="other_name"
                            value="<?php echo e(old('other_name')); ?>"
                            placeholder="Other name"
                            class="sb-form-input <?php $__errorArgs = ['other_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        >
                        <?php $__errorArgs = ['other_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="sb-form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="gender" class="sb-form-label">Gender <span class="required">*</span></label>
                        <select
                            name="gender"
                            id="gender"
                            class="sb-form-select <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required
                        >
                            <option value="">Select Gender</option>
                            <option value="male" <?php echo e(old('gender') === 'male' ? 'selected' : ''); ?>>Male</option>
                            <option value="female" <?php echo e(old('gender') === 'female' ? 'selected' : ''); ?>>Female</option>
                        </select>
                        <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="sb-form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="email" class="sb-form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="<?php echo e(old('email')); ?>"
                            placeholder="teacher@example.com"
                            class="sb-form-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        >
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="sb-form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="phone" class="sb-form-label">Phone <span class="required">*</span></label>
                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            value="<?php echo e(old('phone')); ?>"
                            placeholder="Phone number"
                            class="sb-form-input <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required
                        >
                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="sb-form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="address" class="sb-form-label">Address</label>
                        <textarea
                            name="address"
                            id="address"
                            placeholder="Home address"
                            class="sb-form-textarea <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            rows="2"
                        ><?php echo e(old('address')); ?></textarea>
                        <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="sb-form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card stat-card mb-4">
            <div class="card-body" style="padding: 32px;">
                <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Employment Details</h5>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="school_id" class="sb-form-label">School <span class="required">*</span></label>
                        <select
                            name="school_id"
                            id="school_id"
                            class="sb-form-select <?php $__errorArgs = ['school_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required
                        >
                            <option value="">Select School</option>
                            <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($school->id); ?>" <?php echo e(old('school_id') == $school->id ? 'selected' : ''); ?>>
                                    <?php echo e($school->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['school_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="sb-form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="qualification" class="sb-form-label">Qualification</label>
                        <input
                            type="text"
                            name="qualification"
                            id="qualification"
                            value="<?php echo e(old('qualification')); ?>"
                            placeholder="e.g. B.Ed, M.Sc"
                            class="sb-form-input <?php $__errorArgs = ['qualification'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        >
                        <?php $__errorArgs = ['qualification'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="sb-form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="employment_date" class="sb-form-label">Employment Date</label>
                        <input
                            type="date"
                            name="employment_date"
                            id="employment_date"
                            value="<?php echo e(old('employment_date')); ?>"
                            class="sb-form-input <?php $__errorArgs = ['employment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        >
                        <?php $__errorArgs = ['employment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="sb-form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="photo" class="sb-form-label">Photo</label>
                        <input
                            type="file"
                            name="photo"
                            id="photo"
                            accept="image/*"
                            class="sb-form-input <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        >
                        <small>JPG, PNG. Max 2MB.</small>
                        <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="sb-form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="status" class="sb-form-label">Status <span class="required">*</span></label>
                        <select
                            name="status"
                            id="status"
                            class="sb-form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required
                        >
                            <option value="1" <?php echo e(old('status', '1') === '1' ? 'selected' : ''); ?>>Active</option>
                            <option value="0" <?php echo e(old('status') === '0' ? 'selected' : ''); ?>>Inactive</option>
                        </select>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="sb-form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card stat-card mb-4">
            <div class="card-body" style="padding: 32px;">
                <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Login Account</h5>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input
                                type="checkbox"
                                name="create_login_account"
                                id="create_login_account"
                                value="1"
                                class="form-check-input"
                                <?php echo e(old('create_login_account') ? 'checked' : ''); ?>

                            >
                            <label for="create_login_account" class="form-check-label" style="font-weight: 500; margin-left: 4px;">
                                Create login account for this teacher
                            </label>
                        </div>
                        <small class="text-muted">A login account will be created using the teacher's email address. A temporary password will be generated and shown after creation. The teacher will be required to change their password on first login.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card stat-card mb-4">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="subjects" class="sb-form-label">Subjects</label>
                        <select
                            name="subjects[]"
                            id="subjects"
                            class="sb-form-select <?php $__errorArgs = ['subjects'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            multiple
                            size="5"
                        >
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subject->id); ?>" <?php echo e(in_array($subject->id, old('subjects', [])) ? 'selected' : ''); ?>>
                                    <?php echo e($subject->name); ?><?php echo e($subject->code ? ' (' . $subject->code . ')' : ''); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <small>Hold Ctrl/Cmd to select multiple</small>
                        <?php $__errorArgs = ['subjects'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="sb-form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="school_classes" class="sb-form-label">Classes</label>
                        <select
                            name="school_classes[]"
                            id="school_classes"
                            class="sb-form-select <?php $__errorArgs = ['school_classes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            multiple
                            size="5"
                        >
                            <?php $__currentLoopData = $schoolClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>" <?php echo e(in_array($class->id, old('school_classes', [])) ? 'selected' : ''); ?>>
                                    <?php echo e($class->name); ?><?php echo e($class->section ? ' - ' . $class->section : ''); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <small>Hold Ctrl/Cmd to select multiple</small>
                        <?php $__errorArgs = ['school_classes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="sb-form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card stat-card mb-4">
            <div class="card-body" style="padding: 32px;">
                <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Teacher Permissions</h5>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input
                                type="checkbox"
                                name="can_mark_attendance"
                                id="can_mark_attendance"
                                value="1"
                                class="form-check-input"
                                <?php echo e(old('can_mark_attendance') ? 'checked' : ''); ?>

                            >
                            <label for="can_mark_attendance" class="form-check-label" style="font-weight: 500; margin-left: 4px;">
                                Can Mark Attendance
                            </label>
                        </div>
                        <small class="text-muted">Allow this teacher to view and record student attendance.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-2 mb-4">
            <button type="submit" class="sb-btn sb-btn-primary">
                Save Teacher
            </button>
            <a href="<?php echo e(route('teachers.index')); ?>" class="sb-btn sb-btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/teachers/create.blade.php ENDPATH**/ ?>