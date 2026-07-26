<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Your School - Skulbase</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        body {
            background: linear-gradient(135deg, #0a1628 0%, #1a2d50 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .register-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        .register-header {
            background: linear-gradient(135deg, var(--primary, #5B21FF) 0%, #1a1a2e 100%);
            padding: 40px;
            text-align: center;
            color: #fff;
        }
        .register-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 8px 0;
        }
        .register-header p {
            margin: 0;
            opacity: 0.85;
            font-size: 15px;
        }
        .register-body {
            padding: 40px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-heading, #0a1628);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border, #e9ecef);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title .badge {
            background: var(--primary, #5B21FF);
            color: #fff;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }
        .sb-form-input, .sb-form-select {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 10px 16px;
            font-size: 14px;
            color: #333;
            background: #fff;
            transition: border-color 0.2s;
            width: 100%;
        }
        .sb-form-input:focus, .sb-form-select:focus {
            border-color: var(--primary, #5B21FF);
            outline: none;
            box-shadow: 0 0 0 3px var(--primary-focus, rgba(124, 58, 237, 0.15));
        }
        .sb-form-label {
            display: block;
            font-weight: 500;
            font-size: 14px;
            color: #333;
            margin-bottom: 6px;
        }
        .sb-form-label .required { color: #dc3545; }
        .sb-form-error { color: #dc3545; font-size: 13px; margin-top: 4px; }
        .sb-btn-primary {
            background: var(--primary, #5B21FF);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 32px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            width: 100%;
        }
        .sb-btn-primary:hover { background: var(--primary-hover, #6D28D9); }
        .sb-flash-success {
            background: #d1e7dd;
            color: #0f5132;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .login-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: #6c757d;
        }
        .login-link a {
            color: var(--primary, #5B21FF);
            text-decoration: none;
            font-weight: 500;
        }
        .login-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="register-card">
                    <div class="register-header">
                        <h1>Register Your School</h1>
                        <p>Join Skulbase and transform your school management experience</p>
                    </div>
                    <div class="register-body">
                        <?php if(session('registration_success')): ?>
                            <div class="sb-flash-success">
                                <?php echo e(session('registration_success')); ?>

                            </div>
                        <?php endif; ?>

                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger" style="border-radius: 8px;">
                                <ul style="margin: 0; padding-left: 16px;">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('school.register.submit')); ?>">
                            <?php echo csrf_field(); ?>

                            
                            <div class="section-title">
                                <span class="badge">1</span> School Information
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="school-name" class="sb-form-label">School Name <span class="required">*</span></label>
                                    <input type="text" name="school_name" id="school-name" class="sb-form-input <?php $__errorArgs = ['school_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('school_name')); ?>" placeholder="e.g. Greenfield Academy" required>
                                    <?php $__errorArgs = ['school_name'];
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
                                    <label for="school-type" class="sb-form-label">School Type</label>
                                    <select name="school_type" id="school-type" class="sb-form-select <?php $__errorArgs = ['school_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">Select type...</option>
                                        <option value="Primary" <?php echo e(old('school_type') === 'Primary' ? 'selected' : ''); ?>>Primary</option>
                                        <option value="Secondary" <?php echo e(old('school_type') === 'Secondary' ? 'selected' : ''); ?>>Secondary</option>
                                        <option value="Primary & Secondary" <?php echo e(old('school_type') === 'Primary & Secondary' ? 'selected' : ''); ?>>Primary & Secondary</option>
                                        <option value="Nursery" <?php echo e(old('school_type') === 'Nursery' ? 'selected' : ''); ?>>Nursery</option>
                                        <option value="University" <?php echo e(old('school_type') === 'University' ? 'selected' : ''); ?>>University</option>
                                    </select>
                                    <?php $__errorArgs = ['school_type'];
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
                                    <label for="school-email" class="sb-form-label">School Email <span class="required">*</span></label>
                                    <input type="email" name="school_email" id="school-email" class="sb-form-input <?php $__errorArgs = ['school_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('school_email')); ?>" placeholder="school@example.com" required>
                                    <?php $__errorArgs = ['school_email'];
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
                                    <label for="school-phone" class="sb-form-label">Phone</label>
                                    <input type="text" name="school_phone" id="school-phone" class="sb-form-input <?php $__errorArgs = ['school_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('school_phone')); ?>" placeholder="Phone number">
                                    <?php $__errorArgs = ['school_phone'];
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
                                    <label for="school-address" class="sb-form-label">Address</label>
                                    <input type="text" name="school_address" id="school-address" class="sb-form-input <?php $__errorArgs = ['school_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('school_address')); ?>" placeholder="Street address">
                                    <?php $__errorArgs = ['school_address'];
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

                            
                            <div class="section-title mt-4">
                                <span class="badge">2</span> School Administrator
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="admin-name" class="sb-form-label">Full Name <span class="required">*</span></label>
                                    <input type="text" name="admin_name" id="admin-name" class="sb-form-input <?php $__errorArgs = ['admin_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('admin_name')); ?>" placeholder="Administrator's full name" required>
                                    <?php $__errorArgs = ['admin_name'];
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
                                    <label for="admin-email" class="sb-form-label">Email <span class="required">*</span></label>
                                    <input type="email" name="admin_email" id="admin-email" class="sb-form-input <?php $__errorArgs = ['admin_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('admin_email')); ?>" placeholder="admin@example.com" required>
                                    <?php $__errorArgs = ['admin_email'];
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
                                    <label for="admin-phone" class="sb-form-label">Phone</label>
                                    <input type="text" name="admin_phone" id="admin-phone" class="sb-form-input <?php $__errorArgs = ['admin_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('admin_phone')); ?>" placeholder="Phone number">
                                    <?php $__errorArgs = ['admin_phone'];
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

                            
                            <div class="section-title mt-4">
                                <span class="badge">3</span> Subscription
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="plan-id" class="sb-form-label">Preferred Plan <span class="required">*</span></label>
                                    <select name="plan_id" id="plan-id" class="sb-form-select <?php $__errorArgs = ['plan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value="">Select a plan...</option>
                                        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($plan->id); ?>" <?php echo e(old('plan_id') == $plan->id ? 'selected' : ''); ?>>
                                                <?php echo e($plan->name); ?> - <?php echo e($plan->formattedMonthlyPrice()); ?>/month
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['plan_id'];
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
                                    <div class="form-check">
                                        <input type="checkbox" name="terms" id="terms" class="form-check-input <?php $__errorArgs = ['terms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               value="1" <?php echo e(old('terms') ? 'checked' : ''); ?> required>
                                        <label class="form-check-label" for="terms" style="font-size: 14px;">
                                            I agree to the <a href="#" style="color: var(--primary, #5B21FF);">Terms of Service</a> and <a href="#" style="color: var(--primary, #5B21FF);">Privacy Policy</a>
                                        </label>
                                    </div>
                                    <?php $__errorArgs = ['terms'];
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

                            <div class="mt-4">
                                <button type="submit" class="sb-btn-primary">
                                    Submit Registration
                                </button>
                            </div>
                        </form>

                        <div class="login-link">
                            Already have an account? <a href="<?php echo e(route('login')); ?>">Login here</a>
                        </div>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 24px; color: rgba(255,255,255,0.6); font-size: 12px;">
                    &copy; <?php echo e(date('Y')); ?> Skulbase. All Rights Reserved. | Designed &amp; Developed by Mubarak Lawal
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/auth/register-school.blade.php ENDPATH**/ ?>