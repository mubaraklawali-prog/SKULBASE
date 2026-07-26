<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?php echo $__env->yieldContent('title', 'Skulbase'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        /* ── Layout Shell ─────────────────────────────── */
        html, body {
            overflow-x: hidden;
        }

        body {
            background: var(--background);
            font-family: var(--font-family);
            margin: 0;
        }

        .sb-main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            padding: var(--page-padding);
            min-height: calc(100vh - var(--navbar-height));
            max-width: 100%;
            overflow-x: hidden;
        }

        .sb-content-wrapper {
            max-width: var(--content-max-width);
        }

        /* ── Topbar ───────────────────────────────────── */
        .sb-topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--navbar-height);
            background: var(--navbar-bg);
            border-bottom: 1px solid var(--navbar-border);
            box-shadow: 0 1px 8px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.02);
            z-index: var(--z-navbar);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 var(--space-6);
            transition: left var(--duration-normal) var(--ease-default);
        }

        .sb-topbar-left {
            display: flex;
            align-items: center;
            gap: var(--space-4);
        }

        .sb-topbar-right {
            display: flex;
            align-items: center;
            gap: var(--space-3);
        }

        .sb-topbar-toggle {
            display: none;
            background: none;
            border: none;
            padding: var(--space-2);
            cursor: pointer;
            color: var(--text-body);
            border-radius: var(--radius-md);
            transition: background-color var(--duration-fast) var(--ease-default);
        }

        .sb-topbar-toggle:hover {
            background-color: var(--gray-100);
        }

        /* Search */
        .sb-topbar-search {
            display: flex;
            align-items: center;
            gap: var(--space-2);
            padding: var(--space-2) var(--space-3);
            background: var(--gray-50);
            border: 1px solid var(--border);
            border-radius: var(--radius-full);
            min-width: 320px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sb-topbar-search:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(91,33,255,0.1);
            background: var(--white);
            min-width: 380px;
        }

        .sb-topbar-search-icon {
            color: var(--text-disabled);
            flex-shrink: 0;
        }

        .sb-topbar-search-input {
            border: none;
            background: none;
            outline: none;
            font-size: var(--font-size-sm);
            color: var(--text-body);
            width: 100%;
        }

        .sb-topbar-search-input::placeholder {
            color: var(--text-disabled);
        }

        .sb-topbar-search-kbd {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 22px;
            height: 22px;
            padding: 0 var(--space-1);
            font-size: 11px;
            font-family: var(--font-family-mono);
            color: var(--text-disabled);
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            flex-shrink: 0;
        }

        /* Icon button */
        .sb-topbar-icon-btn {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: none;
            border: none;
            border-radius: var(--radius-md);
            color: var(--text-muted);
            cursor: pointer;
            transition: background-color var(--duration-fast) var(--ease-default),
                        color var(--duration-fast) var(--ease-default);
        }

        .sb-topbar-icon-btn:hover {
            background-color: var(--gray-100);
            color: var(--text-heading);
        }

        .sb-topbar-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            min-width: 16px;
            height: 16px;
            padding: 0 4px;
            font-size: 10px;
            font-weight: var(--font-weight-semibold);
            line-height: 16px;
            text-align: center;
            color: var(--white);
            background-color: var(--danger);
            border-radius: var(--radius-full);
        }

        /* Profile */
        .sb-topbar-profile {
            display: flex;
            align-items: center;
            gap: var(--space-2);
            padding: var(--space-1) var(--space-2);
            background: none;
            border: 1px solid transparent;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: background-color var(--duration-fast) var(--ease-default);
        }

        .sb-topbar-profile:hover {
            background-color: var(--gray-50);
        }

        .sb-topbar-profile-info {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .sb-topbar-profile-name {
            font-size: var(--font-size-sm);
            font-weight: var(--font-weight-medium);
            color: var(--text-heading);
            line-height: var(--line-height-tight);
        }

        .sb-topbar-profile-role {
            font-size: 11px;
            line-height: var(--line-height-tight);
        }

        .sb-topbar-profile-chevron {
            color: var(--text-disabled);
            transition: transform var(--duration-fast) var(--ease-default);
        }

        .sb-dropdown.open .sb-topbar-profile-chevron {
            transform: rotate(180deg);
        }

        .sb-dropdown.open .sb-dropdown-menu {
            display: block;
        }

        .sb-dropdown-menu {
            display: none;
        }

        /* ── Sidebar Backdrop ──────────────────────────── */
        .sb-sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: calc(var(--z-sidebar) - 1);
        }

        /* ── Sidebar Styles ────────────────────────────── */
        .sb-sidebar-brand {
            padding: var(--space-5) var(--space-5);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            background: linear-gradient(135deg, rgba(91,33,255,0.08) 0%, transparent 50%);
        }

        .sb-sidebar-brand-school {
            display: flex;
            align-items: center;
            gap: var(--space-3);
        }

        .sb-sidebar-logo {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-xl);
            object-fit: cover;
            border: 2px solid rgba(91, 33, 255, 0.3);
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(91, 33, 255, 0.2);
        }

        .sb-sidebar-logo-placeholder {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: var(--font-weight-bold);
            color: var(--white);
            background: linear-gradient(135deg, rgba(91,33,255,0.35) 0%, rgba(79,29,232,0.2) 100%);
            flex-shrink: 0;
            border: 1px solid rgba(91, 33, 255, 0.25);
        }

        .sb-sidebar-brand-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .sb-sidebar-school-name {
            font-size: var(--font-size-sm);
            font-weight: var(--font-weight-semibold);
            color: var(--white);
            line-height: var(--line-height-tight);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            letter-spacing: -0.01em;
        }

        .sb-sidebar-school-motto {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.3);
            font-style: italic;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 1px;
        }

        .sb-sidebar-brand-default {
            display: flex;
            align-items: center;
        }

        .sb-brand-skul {
            color: var(--white);
            font-size: var(--font-size-xl);
            font-weight: var(--font-weight-bold);
            letter-spacing: var(--letter-spacing-tight);
        }

        .sb-brand-base {
            color: var(--primary-400);
            font-size: var(--font-size-xl);
            font-weight: var(--font-weight-bold);
            letter-spacing: var(--letter-spacing-tight);
        }

        /* ── Sidebar Navigation ────────────────────────── */
        .sb-sidebar-nav {
            padding: var(--space-3) 0;
        }

        .sb-sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sb-sidebar-section {
            padding: var(--space-5) var(--space-5) var(--space-2);
            font-size: 10px;
            font-weight: var(--font-weight-bold);
            color: rgba(91, 33, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            position: relative;
        }

        .sb-sidebar-section::before {
            content: '';
            position: absolute;
            left: var(--space-5);
            bottom: -2px;
            width: 24px;
            height: 2px;
            background: linear-gradient(90deg, rgba(91,33,255,0.3) 0%, transparent 100%);
            border-radius: 1px;
        }

        .sb-sidebar-item {
            padding: 0 var(--space-3);
            margin-bottom: 2px;
        }

        .sb-sidebar-link {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            padding: 10px var(--space-4);
            color: rgba(255, 255, 255, 0.5);
            font-size: var(--font-size-sm);
            font-weight: var(--font-weight-medium);
            border-radius: var(--radius-lg);
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
        }

        .sb-sidebar-link:hover {
            color: rgba(255, 255, 255, 0.95);
            background: rgba(91, 33, 255, 0.08);
        }

        .sb-sidebar-link.active {
            color: var(--white);
            background: linear-gradient(135deg, rgba(91,33,255,0.3) 0%, rgba(91,33,255,0.12) 100%);
            box-shadow: 0 0 24px -4px rgba(91, 33, 255, 0.2);
        }

        .sb-sidebar-link svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            opacity: 0.45;
            transition: all 0.2s ease;
        }

        .sb-sidebar-link:hover svg,
        .sb-sidebar-link.active svg {
            opacity: 1;
        }

        .sb-sidebar-link.active svg {
            filter: drop-shadow(0 0 6px rgba(91, 33, 255, 0.4));
        }

        .sb-sidebar-chevron {
            width: 14px;
            height: 14px;
            margin-left: auto;
            opacity: 0.3;
            transition: transform var(--duration-fast) var(--ease-default);
        }

        .sb-sidebar-parent.open .sb-sidebar-chevron {
            transform: rotate(90deg);
        }

        .sb-sidebar-submenu {
            display: none;
            list-style: none;
            padding: var(--space-1) 0 var(--space-1) var(--space-8);
            margin: 0;
        }

        .sb-sidebar-parent.open .sb-sidebar-submenu {
            display: block;
        }

        .sb-sidebar-submenu .sb-sidebar-link {
            padding: 7px var(--space-3);
            font-size: var(--font-size-xs);
        }

        .sb-sidebar-submenu .sb-sidebar-link svg {
            width: 16px;
            height: 16px;
        }

        /* Sidebar Footer */
        .sb-sidebar-footer {
            padding: var(--space-4) var(--space-5);
            border-top: 1px solid rgba(255, 255, 255, 0.04);
        }

        .sb-sidebar-version {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.18);
            letter-spacing: 0.02em;
        }

        /* Sidebar scrollbar */
        .sb-sidebar::-webkit-scrollbar { width: 3px; }
        .sb-sidebar::-webkit-scrollbar-track { background: transparent; }
        .sb-sidebar::-webkit-scrollbar-thumb { background: rgba(91, 33, 255, 0.12); border-radius: 4px; }
        .sb-sidebar::-webkit-scrollbar-thumb:hover { background: rgba(91, 33, 255, 0.2); }

        /* ── Footer ────────────────────────────────────── */
        .sb-footer {
            text-align: center;
            padding: var(--space-5) var(--space-6) var(--space-6);
            color: var(--text-disabled);
            font-size: var(--font-size-xs);
            line-height: 1.8;
        }

        .sb-footer strong {
            color: var(--text-muted);
        }

        /* ── Flash Messages ────────────────────────────── */
        .sb-flash {
            display: flex;
            align-items: center;
            gap: var(--space-2);
            padding: var(--space-3) var(--space-5);
            border-radius: var(--radius-lg);
            margin-bottom: var(--space-5);
            font-size: var(--font-size-sm);
            font-weight: var(--font-weight-medium);
        }

        .sb-flash-success { background: var(--success-light); color: var(--success-dark); }
        .sb-flash-error   { background: var(--danger-light);  color: var(--danger-dark); }
        .sb-flash-info    { background: var(--info-light);    color: var(--info-dark); }
        .sb-flash-warning { background: var(--warning-light); color: var(--warning-dark); }

        .sb-flash ul {
            margin: 0;
            padding-left: var(--space-4);
        }

        /* ── Subscription Warning Banner ───────────────── */
        .sb-subscription-banner {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            padding: var(--space-3) var(--space-5);
            background: var(--warning-light);
            border: 1px solid var(--warning);
            border-left: 4px solid var(--warning);
            border-radius: var(--radius-lg);
            margin-bottom: var(--space-5);
            font-size: var(--font-size-sm);
            color: var(--warning-dark);
        }

        .sb-subscription-banner svg {
            flex-shrink: 0;
            color: var(--warning);
        }

        .sb-subscription-banner-text {
            flex: 1;
        }

        .sb-subscription-banner .sb-btn {
            white-space: nowrap;
            margin-left: auto;
        }

        /* ── Welcome Section ───────────────────────────── */
        .welcome-section {
            margin-bottom: var(--space-6);
        }

        .welcome-section h2 {
            font-weight: var(--font-weight-bold);
            font-size: var(--font-size-2xl);
            color: var(--text-heading);
            margin: 0;
        }

        .welcome-section p {
            color: var(--text-muted);
            margin: var(--space-1) 0 0 0;
        }

        /* ── Stat Cards ────────────────────────────────── */
        .stat-card {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            transition: transform var(--duration-fast) var(--ease-default),
                        box-shadow var(--duration-fast) var(--ease-default);
            background: var(--card);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-card-hover);
        }

        .stat-card .card-body {
            padding: var(--space-5) var(--space-6);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-card .stat-number {
            font-size: var(--font-size-3xl);
            font-weight: var(--font-weight-bold);
            margin: 0;
            line-height: var(--line-height-tight);
            color: var(--text-heading);
        }

        .stat-card .stat-label {
            font-size: var(--font-size-xs);
            color: var(--text-muted);
            font-weight: var(--font-weight-medium);
            margin: 0;
        }

        /* ── Action Cards ──────────────────────────────── */
        .action-card {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            height: 100%;
            background: var(--card);
        }

        .action-card .card-header {
            background: none;
            border-bottom: 1px solid var(--border);
            padding: var(--space-4) var(--space-5);
            font-weight: var(--font-weight-semibold);
            font-size: var(--font-size-sm);
            color: var(--text-heading);
        }

        .action-card .card-body {
            padding: var(--space-5);
        }

        .action-link {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            padding: var(--space-2) var(--space-4);
            border-radius: var(--radius-md);
            color: var(--text-body);
            text-decoration: none;
            transition: background-color var(--duration-fast) var(--ease-default),
                        color var(--duration-fast) var(--ease-default);
            font-size: var(--font-size-sm);
            font-weight: var(--font-weight-medium);
        }

        .action-link:hover {
            background: var(--gray-50);
            color: var(--primary);
        }

        .action-link svg {
            width: 20px;
            height: 20px;
            color: var(--text-muted);
            flex-shrink: 0;
        }

        .action-link:hover svg {
            color: var(--primary);
        }

        /* ── Activity Feed ─────────────────────────────── */
        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: var(--space-3);
            padding: var(--space-3) 0;
            border-bottom: 1px solid var(--divider);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-dot {
            width: 8px;
            height: 8px;
            border-radius: var(--radius-full);
            margin-top: 6px;
            flex-shrink: 0;
        }

        .activity-text {
            font-size: var(--font-size-xs);
            color: var(--text-body);
            margin: 0;
        }

        .activity-time {
            font-size: 11px;
            color: var(--text-disabled);
        }

        /* ── Badge System (defined in components.css) ──── */
        /* All badge variants now live in design-system/components.css */

        /* ── Table System (legacy compat) ──────────────── */
        .sb-table th {
            padding: var(--space-3) var(--space-5);
            font-size: var(--font-size-xs);
            font-weight: var(--font-weight-semibold);
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: var(--gray-50);
            border-bottom: 1px solid var(--border);
        }

        .sb-table td {
            padding: var(--space-3) var(--space-5);
            vertical-align: middle;
        }

        .sb-table td.text-end {
            text-align: right;
        }

        /* ── Form System (legacy compat) ───────────────── */
        .sb-form-label {
            display: block;
            font-weight: var(--font-weight-medium);
            font-size: var(--font-size-sm);
            color: var(--text-heading);
            margin-bottom: var(--space-2);
        }

        .sb-form-label .required {
            color: var(--danger);
        }

        .sb-form-input,
        .sb-form-select,
        .sb-form-textarea {
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            padding: var(--space-2) var(--space-4);
            font-size: var(--font-size-sm);
            color: var(--text-body);
            background: var(--white);
            transition: border-color var(--duration-fast) var(--ease-default),
                        box-shadow var(--duration-fast) var(--ease-default);
            width: 100%;
        }

        .sb-form-input:focus,
        .sb-form-select:focus,
        .sb-form-textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: var(--shadow-input-focus);
        }

        .sb-form-input.error,
        .sb-form-select.error,
        .sb-form-textarea.error {
            border-color: var(--danger);
        }

        .sb-form-error {
            color: var(--danger);
            font-size: var(--font-size-xs);
            margin-top: var(--space-1);
        }

        .sb-form-select-sm {
            padding: var(--space-1) var(--space-3);
            font-size: var(--font-size-xs);
            width: auto;
        }

        .sb-form-help {
            color: var(--text-muted);
            font-size: var(--font-size-xs);
            margin-top: var(--space-1);
            display: block;
        }

        /* ── Button System (legacy compat) ─────────────── */
        .sb-btn:disabled,
        .sb-btn[disabled] {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .sb-btn-dark {
            background: var(--sidebar-bg);
            color: var(--white);
        }

        .sb-btn-dark:hover {
            background: var(--gray-800);
            color: var(--white);
        }

        .sb-btn-outline-danger {
            background: var(--danger-light);
            color: var(--danger-dark);
        }

        .sb-btn-outline-danger:hover {
            background: var(--danger);
            color: var(--white);
        }

        .sb-btn-outline-warning {
            background: var(--warning-light);
            color: var(--warning-dark);
        }

        .sb-btn-outline-warning:hover {
            background: var(--warning);
            color: var(--white);
        }

        .sb-btn-outline-success {
            background: var(--success-light);
            color: var(--success-dark);
        }

        .sb-btn-outline-success:hover {
            background: var(--success);
            color: var(--white);
        }

        /* ── Search Bar ────────────────────────────────── */
        .sb-search-bar {
            display: flex;
            gap: var(--space-2);
            align-items: center;
            flex-wrap: wrap;
        }

        /* ── Table Actions ─────────────────────────────── */
        .table-actions {
            display: flex;
            gap: 4px;
            justify-content: flex-end;
            flex-wrap: nowrap;
        }

        /* ── Focus States (Accessibility) ──────────────── */
        .sb-btn:focus-visible,
        .sb-form-input:focus-visible,
        .sb-form-select:focus-visible,
        .sb-form-textarea:focus-visible,
        .sb-sidebar-link:focus-visible,
        .action-link:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* ── Touch Device Optimizations ────────────────── */
        @media (hover: none) {
            .stat-card:hover,
            .sb-card:hover {
                transform: none;
            }
        }

        /* ── Tablet (≤991px) ───────────────────────────── */
        @media (max-width: 991.98px) {
            .sb-topbar {
                padding: 0 var(--space-4);
            }

            .sb-main-content {
                padding: var(--space-5) var(--space-4);
            }
        }

        /* ── Mobile (≤767px) ───────────────────────────── */
        @media (max-width: 767.98px) {
            .sb-sidebar {
                transform: translateX(-100%);
                width: 280px;
                max-width: 85vw;
            }

            .sb-sidebar.show {
                transform: translateX(0);
            }

            .sb-topbar {
                left: 0;
                height: 56px;
                padding: 0 var(--space-3);
                max-width: 100vw;
                overflow-x: hidden;
            }

            .sb-topbar-toggle {
                display: flex;
            }

            .sb-sidebar-backdrop.show {
                display: block;
            }

            .sb-main-content {
                margin-left: 0;
                margin-top: 56px;
                padding: var(--space-3);
                width: 100%;
                max-width: 100vw;
                overflow-x: hidden;
            }

            .sb-topbar-search {
                min-width: 0;
                flex: 1;
            }

            .sb-topbar-search-kbd {
                display: none;
            }

            .sb-topbar-profile-info {
                display: none;
            }

            .sb-topbar-profile {
                padding: var(--space-1);
            }

            .sb-sidebar-link {
                padding: 12px var(--space-4);
                font-size: 15px;
            }

            .sb-sidebar-brand {
                padding: var(--space-4) var(--space-5);
            }

            .sb-sidebar-brand-school-name {
                font-size: var(--font-size-base);
            }

            .sb-sidebar-section-header h2,
            .welcome-section h2 {
                font-size: var(--font-size-xl);
            }

            .stat-card .card-body { padding: var(--space-4); }
            .stat-card .stat-number { font-size: var(--font-size-2xl); }
            .sb-card-body { padding: var(--space-4); }

            .sb-section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: var(--space-3);
            }

            .sb-search-bar {
                flex-direction: column;
            }

            .sb-search-bar .sb-form-input,
            .sb-search-bar .sb-form-select,
            .sb-search-bar .sb-btn {
                max-width: none !important;
                width: 100%;
            }

            .table-actions {
                flex-wrap: wrap;
                gap: 4px;
            }

            .table-actions .sb-btn-sm {
                padding: 4px 8px;
                font-size: 11px;
            }

            .table-actions .d-md-inline-flex {
                display: none !important;
            }

            .sb-table th { padding: var(--space-2) var(--space-3); font-size: 11px; }
            .sb-table td { padding: var(--space-2) var(--space-3); font-size: var(--font-size-xs); }

            .sb-subscription-banner {
                flex-wrap: wrap;
            }

            .sb-flash {
                padding: var(--space-2) var(--space-3);
                font-size: var(--font-size-xs);
                word-break: break-word;
            }

            /* Touch-friendly inputs */
            .sb-form-input,
            .sb-form-select,
            .sb-form-textarea,
            .sb-input,
            .sb-select,
            .sb-textarea {
                font-size: 16px;
            }

            /* Full-width buttons on mobile */
            .btn-block-mobile {
                display: flex;
                width: 100%;
            }

            /* Compact tables */
            .table-responsive {
                border: none;
            }
        }

        /* ── Safe Area Insets (Notch Devices) ─────────── */
        @supports (padding: env(safe-area-inset-bottom)) {
            .sb-main-content {
                padding-bottom: calc(var(--page-padding) + env(safe-area-inset-bottom));
            }
            .sb-topbar {
                padding-top: env(safe-area-inset-top);
            }
            .sb-sidebar {
                padding-bottom: env(safe-area-inset-bottom);
            }
        }

        /* ── Extra Small (<576px) ─────────────────────── */
        @media (max-width: 575.98px) {
            .sb-main-content {
                padding: var(--space-3);
            }

            .sb-content-wrapper {
                gap: var(--space-3);
            }

            .row.g-3 {
                gap: var(--space-2) !important;
            }

            .row.g-4 {
                gap: var(--space-3) !important;
            }

            .row.g-3 > [class*="col-"],
            .row.g-4 > [class*="col-"] {
                padding-left: var(--space-1);
                padding-right: var(--space-1);
            }
        }
    </style>
</head>

<body>

    <div class="sb-sidebar-backdrop" id="sidebarBackdrop"></div>

    <?php echo $__env->make('layouts.partials._sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->make('layouts.partials._navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="sb-main-content">
        <div class="sb-content-wrapper">
            <?php if(session('success')): ?>
                <div class="sb-flash sb-flash-success">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="sb-flash sb-flash-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('info')): ?>
                <div class="sb-flash sb-flash-info">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <?php echo e(session('info')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('warning')): ?>
                <div class="sb-flash sb-flash-warning">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    <?php echo e(session('warning')); ?>

                </div>
            <?php endif; ?>

            <?php
                $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
            ?>

            <?php if($errors->any()): ?>
                <div class="sb-flash sb-flash-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if(request()->attributes->get('subscription_warning')): ?>
                <?php $subWarning = request()->attributes->get('subscription_warning'); ?>
                <div class="sb-subscription-banner">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <div class="sb-subscription-banner-text">
                        <?php echo e($subWarning['message']); ?>

                    </div>
                    <a href="<?php echo e(route('school.subscription.index')); ?>" class="sb-btn sb-btn-sm sb-btn-primary">
                        Renew Now
                    </a>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

    <?php echo $__env->make('layouts.partials._footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
        (function() {
            var sidebar = document.getElementById('sidebar');
            var backdrop = document.getElementById('sidebarBackdrop');
            var toggle = document.getElementById('sidebarToggle');

            if (toggle && sidebar && backdrop) {
                toggle.addEventListener('click', function() {
                    var isOpen = sidebar.classList.toggle('show');
                    backdrop.classList.toggle('show');
                    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    sidebar.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
                });

                backdrop.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                    toggle.setAttribute('aria-expanded', 'false');
                    sidebar.setAttribute('aria-hidden', 'true');
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                        backdrop.classList.remove('show');
                        toggle.setAttribute('aria-expanded', 'false');
                        sidebar.setAttribute('aria-hidden', 'true');
                        toggle.focus();
                    }
                });
            }

            var sidebarToggles = document.querySelectorAll('.sb-sidebar-toggle');
            sidebarToggles.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var parent = this.closest('.sb-sidebar-parent');
                    if (parent) {
                        var isOpen = parent.classList.toggle('open');
                        this.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    }
                });
            });
        })();
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/layouts/app.blade.php ENDPATH**/ ?>