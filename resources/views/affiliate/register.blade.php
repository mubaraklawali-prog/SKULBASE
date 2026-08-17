<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become an Affiliate - Skulbase</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(135deg, var(--nav-bg, #0a1628) 0%, #1a2d50 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--space-6, 24px) 16px;
            font-family: var(--font-family, 'Segoe UI', system-ui, -apple-system, sans-serif);
        }
        .aff-card {
            background: var(--card, #fff);
            border-radius: var(--radius-xl, 16px);
            box-shadow: var(--shadow-modal, 0 20px 60px rgba(0, 0, 0, 0.3));
            width: 100%;
            max-width: 520px;
            overflow: hidden;
        }
        .aff-header {
            background: linear-gradient(135deg, var(--primary, #5B21FF) 0%, #1a1a2e 100%);
            padding: 28px 32px;
            text-align: center;
            color: #fff;
        }
        .aff-header h1 {
            font-size: var(--font-size-2xl, 24px);
            font-weight: var(--font-weight-bold, 700);
            margin: 0 0 6px 0;
        }
        .aff-header p {
            margin: 0;
            opacity: 0.85;
            font-size: var(--font-size-sm, 14px);
        }
        .aff-body {
            padding: 28px 32px 32px;
        }
        .sb-form-label {
            display: block;
            font-weight: var(--font-weight-medium, 500);
            font-size: var(--font-size-sm, 14px);
            color: var(--text, #333);
            margin-bottom: var(--space-1-5, 6px);
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
        .sb-btn-primary {
            background: var(--primary, #5B21FF);
            color: var(--white, #fff);
            border: none;
            border-radius: var(--radius-md, 8px);
            padding: var(--space-3, 12px) var(--space-8, 32px);
            font-size: var(--font-size-base, 16px);
            font-weight: var(--font-weight-semibold, 600);
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            width: 100%;
        }
        .sb-btn-primary:hover { background: var(--primary-hover, #6D28D9); }
        .sb-flash-success {
            background: var(--success-light, #d1e7dd);
            color: var(--success-dark, #0f5132);
            padding: var(--space-3, 12px) var(--space-5, 20px);
            border-radius: var(--radius-md, 8px);
            margin-bottom: var(--space-5, 20px);
            font-size: var(--font-size-sm, 14px);
        }
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
        .terms {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: var(--font-size-xs, 13px);
            color: var(--text-muted, #6c757d);
            margin-top: var(--space-2, 8px);
        }
    </style>
</head>

<body>
    <main class="aff-card" role="main">
        <div class="aff-header">
            <h1>SkulBase Affiliate Program</h1>
            <p>Earn 20% recurring commissions for every school you refer</p>
        </div>

        <div class="aff-body">
            @if ($errors->any())
                <div class="sb-flash-error" role="alert">
                    <ul style="margin: 0; padding-left: 16px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('affiliates.register.store') }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="reg-name" class="sb-form-label">Full Name</label>
                    <input type="text" id="reg-name" name="name" class="sb-form-input @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required autofocus autocomplete="name">
                    @error('name')
                        <div class="sb-form-error" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="reg-email" class="sb-form-label">Email Address</label>
                    <input type="email" id="reg-email" name="email" class="sb-form-input @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                        <div class="sb-form-error" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="reg-phone" class="sb-form-label">Phone Number <span style="color: var(--text-disabled);">(optional)</span></label>
                    <input type="text" id="reg-phone" name="phone" class="sb-form-input"
                           value="{{ old('phone') }}" autocomplete="tel">
                    @error('phone')
                        <div class="sb-form-error" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="reg-password" class="sb-form-label">Password</label>
                    <input type="password" id="reg-password" name="password" class="sb-form-input @error('password') is-invalid @enderror"
                           required autocomplete="new-password">
                    @error('password')
                        <div class="sb-form-error" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="reg-password-confirm" class="sb-form-label">Confirm Password</label>
                    <input type="password" id="reg-password-confirm" name="password_confirmation" class="sb-form-input"
                           required autocomplete="new-password">
                </div>

                <div class="terms mb-4">
                    <input type="checkbox" id="reg-terms" name="terms" value="1" required>
                    <label for="reg-terms">I agree to the affiliate program terms and conditions.</label>
                </div>

                <button type="submit" class="sb-btn-primary">Create Affiliate Account</button>
            </form>

            <div class="links">
                Already have an account? <a href="{{ route('affiliates.login') }}">Affiliate login</a>
            </div>

            <div class="links">
                Want to register a school instead? <a href="{{ route('school.register') }}">Register your school</a>
            </div>

            <div style="text-align: center; margin-top: var(--space-8, 32px); color: var(--text-muted, #adb5bd); font-size: var(--font-size-xs, 12px);">
                Designed &amp; Developed by Mubarak Lawal
            </div>
        </div>
    </main>
</body>

</html>
