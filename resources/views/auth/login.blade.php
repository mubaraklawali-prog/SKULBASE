<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Skulbase</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            transition: background 0.2s, box-shadow 0.2s;
            width: 100%;
        }
        .sb-btn-primary:hover { background: var(--primary-hover, #6D28D9); }
        .sb-btn-primary:focus-visible { outline: 2px solid var(--primary, #5B21FF); outline-offset: 2px; }
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
        .sb-flash-warning {
            background: var(--warning-light, #fff3cd);
            color: var(--warning-dark, #664d03);
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
    </style>
</head>

<body>
    <main class="login-card" role="main">
        <h1>SkulBase</h1>
        <p class="subtitle">Sign in to your account</p>

        @if (session('pending_approval'))
            <div class="sb-flash-warning" role="alert">
                {{ session('pending_approval') }}
            </div>
        @endif

        @if (session('rejected'))
            <div class="sb-flash-error" role="alert">
                {{ session('rejected') }}
            </div>
        @endif

        @if (session('registration_success'))
            <div class="sb-flash-success" role="status">
                {{ session('registration_success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="sb-flash-error" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            <div class="mb-3">
                <label for="login-email" class="sb-form-label">Email</label>
                <input type="email" id="login-email" name="email" class="sb-form-input @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required autofocus autocomplete="email">
                @error('email')
                    <div class="sb-form-error" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="login-password" class="sb-form-label">Password</label>
                <input type="password" id="login-password" name="password" class="sb-form-input @error('password') is-invalid @enderror"
                       required autocomplete="current-password">
                @error('password')
                    <div class="sb-form-error" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="sb-btn-primary" style="margin-top: var(--space-4, 16px);">
                Login
            </button>
        </form>

        <div class="links">
            <a href="{{ route('password.request') }}">Forgot your password?</a>
        </div>

        <div class="links">
            <a href="{{ route('school.register') }}">Register your school</a>
        </div>

        <div style="text-align: center; margin-top: var(--space-8, 32px); color: var(--text-muted, #adb5bd); font-size: var(--font-size-xs, 12px);">
            Designed &amp; Developed by Mubarak Lawal
        </div>
    </main>
</body>

</html>
