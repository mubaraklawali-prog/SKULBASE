<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Skulbase</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        body {
            background: linear-gradient(135deg, var(--nav-bg, #0a1628) 0%, #1a2d50 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-family, 'Segoe UI', system-ui, -apple-system, sans-serif);
        }
        .login-card {
            background: var(--card, #fff);
            border-radius: var(--radius-xl, 16px);
            box-shadow: var(--shadow-modal, 0 20px 60px rgba(0, 0, 0, 0.3));
            padding: var(--space-10, 40px);
            width: 100%;
            max-width: 420px;
        }
        .login-card h1 {
            text-align: center;
            font-size: var(--font-size-2xl, 24px);
            font-weight: var(--font-weight-bold, 700);
            color: var(--text-heading, #0a1628);
            margin-bottom: var(--space-2, 8px);
        }
        .login-card .subtitle {
            text-align: center;
            color: var(--text-muted, #6c757d);
            font-size: var(--font-size-sm, 14px);
            margin-bottom: var(--space-8, 32px);
        }
        .sb-form-input {
            border-radius: var(--radius-md, 8px);
            border: 1px solid var(--border, #dee2e6);
            padding: var(--space-3, 10px) var(--space-4, 16px);
            font-size: var(--font-size-sm, 14px);
            color: var(--text, #333);
            background: var(--card, #fff);
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }
        .sb-form-input:focus {
            border-color: var(--primary, #5B21FF);
            outline: none;
            box-shadow: 0 0 0 3px var(--primary-focus, rgba(124, 58, 237, 0.15));
        }
        .sb-form-label {
            display: block;
            font-weight: var(--font-weight-medium, 500);
            font-size: var(--font-size-sm, 14px);
            color: var(--text, #333);
            margin-bottom: var(--space-1-5, 6px);
        }
        .sb-btn-primary {
            background: var(--primary, #5B21FF);
            color: var(--white, #fff);
            border: none;
            border-radius: var(--radius-md, 8px);
            padding: var(--space-3, 12px) var(--space-8, 32px);
            font-size: var(--font-size-base, 16px);
            font-weight: var(--font-weight-semibold, 600);
            cursor: pointer;
            transition: background 0.2s;
            width: 100%;
        }
        .sb-btn-primary:hover { background: var(--primary-hover, #6D28D9); }
        .sb-btn-primary:focus-visible { outline: 2px solid var(--primary, #5B21FF); outline-offset: 2px; }
        .sb-flash-error {
            background: var(--danger-light, #f8d7da);
            color: var(--danger-dark, #842029);
            padding: var(--space-3, 12px) var(--space-5, 20px);
            border-radius: var(--radius-md, 8px);
            margin-bottom: var(--space-5, 20px);
            font-size: var(--font-size-sm, 14px);
        }
        .sb-form-error {
            color: var(--danger, #dc3545);
            font-size: var(--font-size-xs, 13px);
            margin-top: var(--space-1, 4px);
        }
        .sb-password-requirements {
            background: var(--gray-50, #f8f9fa);
            border-radius: var(--radius-md, 8px);
            padding: var(--space-3, 12px) var(--space-4, 16px);
            margin-top: var(--space-3, 12px);
            margin-bottom: var(--space-2, 8px);
            font-size: var(--font-size-xs, 13px);
            color: var(--text-secondary, #495057);
        }
        .sb-password-requirements strong {
            display: block;
            margin-bottom: var(--space-1, 4px);
        }
        .sb-password-requirements span {
            display: block;
        }
        .links {
            text-align: center;
            margin-top: var(--space-6, 24px);
            font-size: var(--font-size-sm, 14px);
            color: var(--text-muted, #6c757d);
        }
        .links a {
            color: var(--primary, #5B21FF);
            text-decoration: none;
            font-weight: var(--font-weight-medium, 500);
        }
        .links a:hover { text-decoration: underline; }
    </style>
</head>

<body>
    <main class="login-card" role="main">
        <h1>SkulBase</h1>
        <p class="subtitle">Create a new password for your account</p>

        <?php if($errors->any()): ?>
            <div class="sb-flash-error" role="alert">
                <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('password.update')); ?>" novalidate>
            <?php echo csrf_field(); ?>

            <input type="hidden" name="token" value="<?php echo e($token); ?>">

            <div class="mb-3">
                <label for="reset-email" class="sb-form-label">Email</label>
                <input type="email" id="reset-email" name="email" class="sb-form-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e($email ?? old('email')); ?>" required autofocus autocomplete="email">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="sb-form-error" role="alert"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label for="reset-password" class="sb-form-label">New Password</label>
                <input type="password" id="reset-password" name="password" class="sb-form-input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       required autocomplete="new-password">
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="sb-form-error" role="alert"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label for="reset-password-confirm" class="sb-form-label">Confirm New Password</label>
                <input type="password" id="reset-password-confirm" name="password_confirmation" class="sb-form-input"
                       required autocomplete="new-password">
            </div>

            <div class="sb-password-requirements">
                <strong>Password must contain:</strong>
                <span>At least 8 characters</span>
                <span>One uppercase letter (A-Z)</span>
                <span>One lowercase letter (a-z)</span>
                <span>One number (0-9)</span>
            </div>

            <button type="submit" class="sb-btn-primary" style="margin-top: var(--space-4, 16px);">
                Reset Password
            </button>
        </form>

        <div class="links">
            <a href="<?php echo e(route('login')); ?>">Back to Login</a>
        </div>

        <div style="text-align: center; margin-top: var(--space-8, 32px); color: var(--text-muted, #adb5bd); font-size: var(--font-size-xs, 12px);">
            Designed &amp; Developed by Mubarak Lawal
        </div>
    </main>
</body>

</html>
<?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/auth/passwords/reset.blade.php ENDPATH**/ ?>