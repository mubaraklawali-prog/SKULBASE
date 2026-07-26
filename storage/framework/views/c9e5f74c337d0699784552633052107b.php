<?php $__env->startSection('title', 'Teacher Login Credentials - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Teacher Login Credentials</h2>
            <p class="text-muted mb-0">Login account created for <?php echo e($teacher->full_name); ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('teachers.show', $teacher)); ?>" class="sb-btn sb-btn-primary">
                View Teacher Profile
            </a>
            <a href="<?php echo e(route('teachers.index')); ?>" class="sb-btn sb-btn-secondary">
                Back to List
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card stat-card mb-4" id="credentials-card">
                <div class="card-body" style="padding: 32px;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <div style="width: 64px; height: 64px; border-radius: 50%; background: #d1e7dd; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#0f5132" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z"/>
                            </svg>
                        </div>
                        <h5 style="font-weight: 600; color: #0f5132; margin-bottom: 4px;">Account Created Successfully</h5>
                        <p style="color: #6c757d; font-size: 14px; margin: 0;">Share these credentials with the teacher</p>
                    </div>

                    <div style="background: #f8f9fa; border-radius: 8px; padding: 20px; margin-bottom: 16px;">
                        <div style="margin-bottom: 12px;">
                            <label style="font-weight: 500; font-size: 12px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Email (Username)</label>
                            <p id="credential-email" style="margin: 4px 0 0; font-size: 15px; font-weight: 600; color: #333; font-family: monospace; background: #fff; padding: 8px 12px; border-radius: 6px; border: 1px solid #e9ecef;">
                                <?php echo e($credentials['name']); ?>

                            </p>
                        </div>
                        <div>
                            <label style="font-weight: 500; font-size: 12px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Password</label>
                            <p id="credential-password" style="margin: 4px 0 0; font-size: 15px; font-weight: 600; color: #333; font-family: monospace; background: #fff; padding: 8px 12px; border-radius: 6px; border: 1px solid #e9ecef;">
                                <?php echo e($credentials['password']); ?>

                            </p>
                        </div>
                    </div>

                    <div style="background: #fff3cd; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px;">
                        <p style="margin: 0; font-size: 13px; color: #664d03;">
                            <strong>Important:</strong> The teacher will be required to change their password on first login. Share these credentials through a secure channel.
                        </p>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="sb-btn sb-btn-primary" onclick="copyCredentials()" style="flex: 1;">
                            Copy Credentials
                        </button>
                        <button type="button" class="sb-btn sb-btn-outline-primary" onclick="printCredentials()" style="flex: 1;">
                            Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyCredentials() {
        const email = document.getElementById('credential-email').textContent.trim();
        const password = document.getElementById('credential-password').textContent.trim();
        const text = `Login Credentials for Skulbase\n\nEmail: ${email}\nPassword: ${password}\n\nNote: You will be required to change your password on first login.`;

        navigator.clipboard.writeText(text).then(function() {
            const btn = event.target;
            const originalText = btn.textContent;
            btn.textContent = 'Copied!';
            btn.disabled = true;
            setTimeout(function() {
                btn.textContent = originalText;
                btn.disabled = false;
            }, 2000);
        });
    }

    function printCredentials() {
        const card = document.getElementById('credentials-card');
        const email = document.getElementById('credential-email').textContent.trim();
        const password = document.getElementById('credential-password').textContent.trim();

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Teacher Credentials - <?php echo e($teacher->full_name); ?></title>
                <style>
                    body { font-family: 'Segoe UI', system-ui, sans-serif; padding: 40px; color: #333; }
                    h2 { color: #0a1628; margin-bottom: 8px; }
                    .subtitle { color: #6c757d; font-size: 14px; margin-bottom: 24px; }
                    .credentials { background: #f8f9fa; border-radius: 8px; padding: 20px; margin-bottom: 16px; }
                    .label { font-weight: 500; font-size: 12px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; }
                    .value { font-size: 15px; font-weight: 600; font-family: monospace; margin: 4px 0 16px; }
                    .warning { background: #fff3cd; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #664d03; margin-top: 16px; }
                    @media print { body { padding: 20px; } }
                </style>
            </head>
            <body>
                <h2>Skulbase - Teacher Login Credentials</h2>
                <p class="subtitle"><?php echo e($teacher->full_name); ?> | <?php echo e($teacher->school->name ?? ''); ?></p>
                <div class="credentials">
                    <div class="label">Email (Username)</div>
                    <div class="value">${email}</div>
                    <div class="label">Password</div>
                    <div class="value">${password}</div>
                </div>
                <div class="warning">
                    <strong>Note:</strong> The teacher will be required to change their password on first login.
                </div>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/teachers/create-credentials.blade.php ENDPATH**/ ?>