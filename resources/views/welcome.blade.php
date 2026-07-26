<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skulbase - School Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(135deg, #0a1628 0%, #1a2d50 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #fff;
        }
        .hero { text-align: center; max-width: 600px; padding: 40px; }
        .hero h1 { font-size: 48px; font-weight: 700; margin-bottom: 16px; }
        .hero h1 span { color: var(--primary); }
        .hero p { font-size: 18px; opacity: 0.85; margin-bottom: 40px; line-height: 1.6; }
        .hero-links { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
        .btn-register {
            background: var(--primary); color: #fff; border: none; border-radius: 8px;
            padding: 14px 32px; font-size: 16px; font-weight: 600; text-decoration: none;
            transition: background 0.2s; display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-register:hover { background: #3a8ae8; color: #fff; }
        .btn-login {
            background: transparent; color: #fff; border: 1px solid rgba(255,255,255,0.3);
            border-radius: 8px; padding: 14px 32px; font-size: 16px; font-weight: 500;
            text-decoration: none; transition: all 0.2s; display: inline-flex;
            align-items: center; gap: 8px;
        }
        .btn-login:hover { background: rgba(255,255,255,0.1); color: #fff; }
    </style>
</head>
<body>
    <div class="hero">
        <h1>Welcome to <span>Skul</span>base</h1>
        <p>A modern school management platform for administrators, teachers, students, and parents.</p>
        <div class="hero-links">
            <a href="{{ route('school.register') }}" class="btn-register">
                Register Your School
            </a>
            <a href="{{ route('login') }}" class="btn-login">
                Login
            </a>
        </div>
        <div style="margin-top: 60px; color: rgba(255,255,255,0.5); font-size: 12px;">
            &copy; {{ date('Y') }} Skulbase. All Rights Reserved. | Designed &amp; Developed by Mubarak Lawal
        </div>
    </div>
</body>
</html>
