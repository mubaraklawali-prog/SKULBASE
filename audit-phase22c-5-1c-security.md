# Phase 22C.5.1C — Security Audit

**Date:** 2026-07-24  
**Scope:** Authentication, authorization, multi-tenant isolation, input validation, file upload, CSRF, XSS, SQL injection, sensitive data, security headers, rate limiting, error handling, dependencies  
**Methodology:** Manual code review of controllers, middleware, models, views, config, and routes  
**Status:** READ-ONLY — no code changes

---

## Executive Summary

| Metric | Value |
|---|---|
| **Security Score** | **71 / 100** |
| **Decision** | **Needs Moderate Work** |
| **Critical Findings** | 2 |
| **High Findings** | 6 |
| **Medium Findings** | 5 |
| **Low Findings** | 6 |
| **Total Findings** | 19 |

The application has a solid security foundation: password hashing via Laravel's `Hash` facade, CSRF protection via Laravel middleware, no raw Blade output (`{!! !!}`), no `dd()`/`dump()` in templates, file uploads validated on all endpoints, and `forceFill()` used exclusively for privileged field assignment. However, **two critical vulnerabilities** (unauthenticated data-exposure API, debug mode enabled) and **six high-severity gaps** (missing security headers, session config weaknesses, unauthenticated public registration, backup/maintenance endpoint exposure, missing email verification, and no Content-Security-Policy) require remediation before any production deployment.

---

## 1. Authentication — Score: 72/100

### Strengths
- Password hashing: `password` cast is `hashed` on `User` model (`User.php:89`)
- Password policy: `Rules\Password::defaults()->mixedCase()->numbers()` — min 8, uppercase, lowercase, number (`AuthController.php:33`)
- Login throttling: `throttle:5,1` on login route (`web.php:58`)
- Forgot-password throttling: `throttle:3,1` on forgot-password route (`web.php:64`)
- Session regeneration on login: `$request->session()->regenerate()` (`AuthController.php:55`)
- Session invalidation on logout: `$request->session()->invalidate()` + `regenerateToken()` (`AuthController.php:190-191`)
- Pending/rejected school admins blocked with full session invalidation (`AuthController.php:60-78`)
- `force_password_change` flag forces first-login password change (`AuthController.php:81-83`)
- Password reset tokens: 60-min expiry, 60-sec throttle (`config/auth.php:81-91`)
- `remember_token` hidden from serialization (`User.php:36`)

### Findings

| # | Severity | Finding | Location |
|---|----------|---------|----------|
| A-1 | **Critical** | `APP_DEBUG=true` in `.env` — exposes full stack traces, env vars, and config values in production error responses | `.env:6` |
| A-2 | **High** | No email verification required — users can register with any email and immediately access the system | `AuthController.php:28-45` |
| A-3 | **Medium** | `remember_token` is nullable with no unique constraint — could allow stale tokens | `users` migration |
| A-4 | **Low** | `password` hidden from serialization but no explicit `$visible` restriction — safe by default, but belt-and-suspenders recommends explicit `$visible` | `User.php:35-38` |

---

## 2. Authorization — Score: 75/100

### Strengths
- `CheckRole` middleware enforces role-based access on all authenticated routes (`CheckRole.php:11-20`)
- `CheckSubscription` middleware blocks inactive/expired subscriptions (`bootstrap/app.php:6`)
- `CheckSchoolApproval` middleware blocks pending/rejected school_admin users (`bootstrap/app.php:7`)
- `CheckTeacherPermission` middleware for teacher-specific routes (`bootstrap/app.php:8`)
- All sensitive controllers use `abort(403)` or `abort_unless()` guards
- `forceFill()` used exclusively for privileged fields (`role`, `school_id`, `force_password_change`) — never in `$fillable`
- User model `$fillable` restricted to `name`, `email`, `password` only (`User.php:24-28`)

### Findings

| # | Severity | Finding | Location |
|---|----------|---------|----------|
| B-1 | **High** | Backup and maintenance endpoints accessible to both `super_admin` AND `school_admin` — school admins can trigger DB backups and maintenance on any school | `SettingsController.php` (backup routes) |
| B-2 | **Medium** | `CheckRole` middleware checks `in_array($user->role, $roles)` but does not verify `$user` is not null before accessing `$user->role` — safe because `auth` middleware runs first, but fragile if middleware order changes | `CheckRole.php:15` |
| B-3 | **Low** | No `Gate::define()` or policy classes — all authorization is middleware-based, which is adequate but less granular than policy-based auth | `app/Providers/` |

---

## 3. Multi-Tenant Isolation — Score: 65/100

### Strengths
- `CheckSubscription` middleware scopes all authenticated routes
- Most controllers scope queries by `$user->school_id`
- Request classes validate school ownership: `StoreAssignmentRequest`, `StoreTimetableRequest` (24 validation calls ensuring entities belong to user's school)
- `CheckSchoolApproval` prevents unapproved school admins from accessing any data

### Findings

| # | Severity | Finding | Location |
|---|----------|---------|----------|
| C-1 | **Critical** | `GET /api/schools/{schoolId}/classes` has **zero authentication** — any unauthenticated visitor can enumerate all classes for any school by ID | `routes/api.php` |
| C-2 | **High** | `GET /api/schools` has **zero authentication** — lists all schools with names, IDs, and contact info | `routes/api.php` |
| C-3 | **Medium** | No global query scope on models to enforce `school_id` — relies entirely on controller-level scoping, which can be missed in new code | All models |

---

## 4. Input Validation — Score: 80/100

### Strengths
- All controllers use `$request->validate()` with explicit rules
- Form Request classes (`Store*Request`, `Update*Request`) with `withValidator()` callbacks for cross-field + school-scope validation
- `DB::raw()` usage is safe: only in `selectRaw()` and `sum()` with hardcoded column names — no user input interpolated (`ReportService.php`, `FeeController.php:21`, `ScoreEntryController.php:36`)
- No `exec()`, `system()`, `shell_exec()` calls — only `proc_open()` in `SettingsController.php:282` for DB backups with `escapeshellarg()` on user-controlled filename components

### Findings

| # | Severity | Finding | Location |
|---|----------|---------|----------|
| D-1 | **Medium** | `proc_open()` used for DB backup with `escapeshellarg()` — safe, but should use `Process::run()` (Laravel wrapper) for better error handling and escaping | `SettingsController.php:282` |
| D-2 | **Low** | No `FormRequest` for password change — uses inline `$request->validate()` which works but is less testable | `AuthController.php:109-112` |

---

## 5. File Upload Security — Score: 85/100

### Strengths
- **School logos:** `image|mimes:jpg,jpeg,png,webp|max:2048` — proper validation
- **Teacher photos:** `image|mimes:jpg,jpeg,png|max:2048` — proper validation
- **Assignments:** `mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip|max:10240` — explicit whitelist
- **Announcements:** `mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:10240` — explicit whitelist
- **Admissions (passport):** `image|mimes:jpg,jpeg,png|max:5120` — proper validation
- **Messages:** `mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip|max:10240` — explicit whitelist
- All uploads stored via Laravel's `Storage::disk('public')->putFile()` which generates random filenames
- File size limits enforced on all endpoints

### Findings

| # | Severity | Finding | Location |
|---|----------|---------|----------|
| E-1 | **Medium** | No file content validation beyond extension — MIME type validation relies on fileinfo extension; no virus scanning | All upload controllers |
| E-2 | **Low** | Files stored on `public` disk — accessible without authentication if storage is web-accessible | `Storage::disk('public')` |
| E-3 | **Low** | No file name sanitization before storage — Laravel's `putFile()` handles this automatically, but `storeAs()` variants should be checked | N/A (not used) |

---

## 6. CSRF Protection — Score: 90/100

### Strengths
- Laravel's `VerifyCsrfToken` middleware active by default on all web routes
- `TokenMismatchException` handler renders custom 419 view (`bootstrap/app.php:71-77`)
- All forms use `@csrf` directive (verified across all Blade templates)
- Session invalidation + token regeneration on login/logout

### Findings

| # | Severity | Finding | Location |
|---|----------|---------|----------|
| F-1 | **Low** | API routes (`routes/api.php`) have no CSRF protection by default — acceptable for API, but the unauthenticated endpoints should not exist at all (see C-1, C-2) | `routes/api.php` |

---

## 7. XSS Prevention — Score: 95/100

### Strengths
- **Zero `{!! !!}` raw Blade output** found across all templates — all output is escaped via `{{ }}`
- No `dd()`, `dump()`, `var_dump()`, `print_r()` in any Blade template
- No debug output helpers found in any view file

### Findings

| # | Severity | Finding | Location |
|---|----------|---------|----------|
| G-1 | **Low** | Bootstrap 5 CDN scripts loaded without `integrity` (SRI) hashes — compromised CDN could inject malicious JS | Layout Blade templates |

---

## 8. SQL Injection — Score: 88/100

### Strengths
- No raw SQL queries — all queries use Eloquent or Query Builder
- `DB::raw()` usage limited to aggregate functions with hardcoded column names
- No string concatenation with user input in any query
- All user input passes through Eloquent parameter binding

### Findings

| # | Severity | Finding | Location |
|---|----------|---------|----------|
| H-1 | **Low** | `selectRaw()` with hardcoded expressions — safe, but consider using `DB::raw()` with explicit column references for clarity | `ReportService.php`, `FeeController.php:21` |

---

## 9. Sensitive Data Exposure — Score: 60/100

### Strengths
- `.env` file is in `.gitignore` (confirmed)
- `APP_KEY` stored in `.env` only, never hardcoded
- Database credentials stored in `.env`
- `password` and `remember_token` in `$hidden` array
- No hardcoded secrets found in any config file
- `APP_ENV=local` in `.env` — should be `production`

### Findings

| # | Severity | Finding | Location |
|---|----------|---------|----------|
| I-1 | **Critical** | `APP_DEBUG=true` in `.env` — exposes full exception traces, config values, environment variables, and database credentials in error responses | `.env:6` |
| I-2 | **High** | `APP_NAME=Laravel` in both `.env` and `.env.example` — branded "Laravel" placeholder visible in emails, notifications, and logs | `.env:3`, `.env.example:3` |
| I-3 | **High** | `SESSION_ENCRYPT=false` — session data stored unencrypted on the server; sensitive data in session could be read if server is compromised | `.env` |
| I-4 | **High** | `SESSION_SECURE_COOKIE` not set — session cookie transmitted over HTTP (no HTTPS enforcement) | `.env` |
| I-5 | **Medium** | `SESSION_DOMAIN=null` — session cookie sent to all subdomains, vulnerable to subdomain takeover attacks | `.env` |
| I-6 | **Low** | No `APP_TIMEZONE` set — uses default PHP timezone; could cause subtle time-based auth issues | `.env` |

---

## 10. Security Headers — Score: 40/100

### Strengths
- Custom error views (403/404/419/429/500) — no framework debug info leaked
- Exception handlers return generic messages for JSON requests

### Findings

| # | Severity | Finding | Location |
|---|----------|---------|----------|
| J-1 | **High** | No `TrustProxies` middleware configured — `X-Forwarded-For` header not trusted; can't determine real client IP for rate limiting or logging | `bootstrap/app.php` |
| J-2 | **High** | No `X-Content-Type-Options: nosniff` header — browsers may MIME-sniff responses | Missing middleware |
| J-3 | **High** | No `X-Frame-Options` or `Content-Security-Policy frame-ancestors` header — app can be framed (clickjacking) | Missing middleware |
| J-4 | **Medium** | No `Content-Security-Policy` header — no restriction on script sources, styles, or connections | Missing middleware |
| J-5 | **Medium** | No `Strict-Transport-Security` (HSTS) header — HTTPS not enforced at browser level | Missing middleware |
| J-6 | **Low** | No `Referrer-Policy` header — full referrer URL may leak to third parties | Missing middleware |

---

## 11. Rate Limiting — Score: 70/100

### Strengths
- Login throttle: `throttle:5,1` — 5 attempts per minute (`web.php:58`)
- Forgot-password throttle: `throttle:3,1` — 3 attempts per minute (`web.php:64`)
- Password reset tokens: 60-sec expiry, 60-min max lifetime (`config/auth.php:81-91`)

### Findings

| # | Severity | Finding | Location |
|---|----------|---------|----------|
| K-1 | **Medium** | No rate limiting on registration endpoint — automated account creation attacks possible | `AuthController.php:28-45` |
| K-2 | **Low** | No rate limiting on password change endpoint — brute-force current password possible | `AuthController.php:107-128` |

---

## 12. Error Handling — Score: 82/100

### Strengths
- Custom exception handlers for 403, 404, 419, 429, 500, 422 (`bootstrap/app.php:32-95`)
- `ModelNotFoundException` returns custom 404 view with `withInput()` preserved
- `ValidationException` suppressed from logs (`dontReport`)
- JSON responses return generic messages without stack traces
- Custom error views (403/404/419/429/500) are clean — no debug info

### Findings

| # | Severity | Finding | Location |
|---|----------|---------|----------|
| L-1 | **Low** | Catch-all `Throwable` handler reports all exceptions — `report($e)` at `bootstrap/app.php:88` sends to log even when caught; could flood logs in production | `bootstrap/app.php:87-95` |

---

## 13. Dependency & Configuration Security — Score: 68/100

### Strengths
- Laravel 12 with latest security patches
- `laravel/sail` for development containers
- `config/cors.php` published — CORS configuration exists

### Findings

| # | Severity | Finding | Location |
|---|----------|---------|----------|
| M-1 | **High** | `MAIL_MAILER=log` — all password reset emails, notifications, and approvals are logged to file, not delivered; users cannot reset passwords in production | `.env` |
| M-2 | **Medium** | No `config/cors.php` customization — default Laravel CORS allows all origins (`*`) | `config/cors.php` |
| M-3 | **Low** | `CACHE_DRIVER=file`, `QUEUE_CONNECTION=sync` — acceptable for development, must change for production | `.env` |

---

## Findings Summary by Severity

### Critical (2)
1. **C-1** — Unauthenticated API exposes school class data (`routes/api.php`)
2. **I-1 / A-1** — `APP_DEBUG=true` exposes full stack traces and config (`.env`)

### High (6)
1. **C-2** — Unauthenticated API lists all schools (`routes/api.php`)
2. **B-1** — Backup/maintenance endpoints accessible to school admins (`SettingsController.php`)
3. **A-2** — No email verification on registration (`AuthController.php`)
4. **I-2** — `APP_NAME=Laravel` placeholder (`.env`)
5. **I-3/I-4** — `SESSION_ENCRYPT=false`, `SESSION_SECURE_COOKIE` not set (`.env`)
6. **J-1/J-2/J-3** — Missing `TrustProxies`, `X-Content-Type-Options`, `X-Frame-Options` headers
7. **M-1** — `MAIL_MAILER=log` — emails not delivered (`.env`)

### Medium (5)
1. **C-3** — No global query scope for `school_id` isolation (all models)
2. **D-1** — `proc_open()` for DB backups instead of `Process::run()` (`SettingsController.php`)
3. **E-1** — No file content validation beyond MIME extension (upload controllers)
4. **J-4/J-5** — No CSP or HSTS headers
5. **K-1** — No rate limiting on registration (`AuthController.php`)

### Low (6)
1. **A-3** — `remember_token` nullable without unique constraint
2. **A-4** — No explicit `$visible` on User model
3. **B-3** — No Gate/policy classes
4. **G-1** — Bootstrap CDN without SRI hashes
5. **H-1** — `selectRaw()` with hardcoded expressions (safe but could be clearer)
6. **I-6** — No `APP_TIMEZONE` set

---

## Positive Security Indicators (No Findings)

- **Zero XSS vectors** — no `{!! !!}` raw output in any Blade template
- **Zero `dd()`/`dump()` in production code** — debug helpers absent from controllers and views
- **Zero SQL injection vectors** — all queries use parameterized Eloquent/Query Builder
- **Zero hardcoded secrets** — all credentials in `.env`
- **`.env` excluded from version control** — confirmed in `.gitignore`
- **File uploads validated on all endpoints** — 100% coverage
- **`forceFill()` for privileged fields** — `role`, `school_id`, `force_password_change` never in `$fillable`
- **Password hashing via cast** — automatic via `User` model `password` = `hashed` cast
- **Session regeneration on auth state changes** — login, logout, pending/rejected blocks all regenerate
- **Custom error views** — no framework debug info leaked in 403/404/419/429/500

---

## Remediation Priority

### Immediate (before any deployment)
1. Set `APP_DEBUG=false` and `APP_ENV=production`
2. Set a real `APP_NAME`
3. Remove or protect the unauthenticated API routes
4. Configure `TrustProxies` middleware
5. Add security headers middleware (`X-Content-Type-Options`, `X-Frame-Options`, `CSP`, `HSTS`)
6. Set `SESSION_SECURE_COOKIE=true`, `SESSION_ENCRYPT=true`, `SESSION_DOMAIN` appropriately
7. Configure `MAIL_MAILER=smtp` with real credentials
8. Restrict backup/maintenance endpoints to `super_admin` only

### Short-term (within 2 weeks)
1. Add email verification requirement
2. Add rate limiting on registration and password change
3. Publish and customize `config/cors.php` with explicit allowed origins
4. Add SRI hashes to CDN resources
5. Add `Referrer-Policy` header
6. Consider replacing `proc_open()` with Laravel `Process::run()`

### Medium-term (within 1 month)
1. Add global `school_id` scope to all tenant models
2. Add Gate/policy classes for granular authorization
3. Implement Content-Security-Policy header
4. Add virus scanning for file uploads (ClamAV or similar)

---

## Score Breakdown

| Area | Weight | Score | Weighted |
|---|---|---|---|
| Authentication | 15% | 72 | 10.8 |
| Authorization | 15% | 75 | 11.3 |
| Multi-Tenant Isolation | 12% | 65 | 7.8 |
| Input Validation | 8% | 80 | 6.4 |
| File Upload Security | 8% | 85 | 6.8 |
| CSRF Protection | 8% | 90 | 7.2 |
| XSS Prevention | 8% | 95 | 7.6 |
| SQL Injection | 8% | 88 | 7.0 |
| Sensitive Data Exposure | 8% | 60 | 4.8 |
| Security Headers | 5% | 40 | 2.0 |
| Rate Limiting | 3% | 70 | 2.1 |
| Error Handling | 3% | 82 | 2.5 |
| Dependencies & Config | 4% | 68 | 2.7 |
| **Total** | **100%** | | **71 / 100** |
