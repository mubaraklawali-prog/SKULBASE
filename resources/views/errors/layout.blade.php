<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Error') — SkulBase</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .error-page {
            text-align: center;
            max-width: 480px;
            width: 100%;
        }
        .error-page .error-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 24px;
            background: #e7f1ff;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-page .error-icon svg {
            width: 40px;
            height: 40px;
            color: var(--primary, #5B21FF);
        }
        .error-page .error-icon.icon-warning { background: #fff3cd; }
        .error-page .error-icon.icon-warning svg { color: #664d03; }
        .error-page .error-icon.icon-danger { background: #f8d7da; }
        .error-page .error-icon.icon-danger svg { color: #842029; }
        .error-page .error-code {
            font-size: 64px;
            font-weight: 800;
            color: var(--text-heading);
            line-height: 1;
            margin-bottom: 8px;
        }
        .error-page h2 {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 12px;
        }
        .error-page p {
            font-size: 15px;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .error-page .error-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .error-page .sb-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        .error-page .sb-btn-primary { background: var(--primary, #5B21FF); color: #fff; }
        .error-page .sb-btn-primary:hover { background: #3a8ae8; }
        .error-page .sb-btn-ghost { background: #f0f2f5; color: #333; }
        .error-page .sb-btn-ghost:hover { background: #e2e4e8; }
        .error-page .brand {
            margin-top: 48px;
            font-size: 14px;
            color: #adb5bd;
        }
        .error-page .brand a { color: var(--primary, #5B21FF); text-decoration: none; font-weight: 600; }
        .error-page .brand a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="error-page">
        @yield('content')
        <div class="brand">
            <a href="{{ url('/') }}">SkulBase</a> — School Management System
        </div>
    </div>
</body>
</html>
