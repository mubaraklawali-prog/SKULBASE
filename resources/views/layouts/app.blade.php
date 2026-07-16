<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Skulbase')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-bg: #0a1628;
            --sidebar-hover: #162240;
            --sidebar-active: #1a2d50;
            --topbar-bg: #ffffff;
        }

        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: 64px;
            background: var(--topbar-bg);
            border-bottom: 1px solid #e9ecef;
            z-index: 1030;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 1040;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-brand h4 {
            color: #fff;
            font-weight: 700;
            letter-spacing: 1px;
            margin: 0;
            font-size: 22px;
        }

        .sidebar-brand span {
            color: #4f9cf7;
        }

        .sidebar-nav {
            padding: 16px 0;
        }

        .sidebar-nav .nav-item {
            padding: 0 12px;
        }

        .sidebar-nav .nav-link {
            color: rgba(255,255,255,0.6);
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-nav .nav-link:hover {
            color: #fff;
            background: var(--sidebar-hover);
        }

        .sidebar-nav .nav-link.active {
            color: #fff;
            background: var(--sidebar-active);
        }

        .sidebar-nav .nav-link svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            opacity: 0.7;
        }

        .sidebar-nav .nav-link:hover svg,
        .sidebar-nav .nav-link.active svg {
            opacity: 1;
        }

        .sidebar-nav .nav-parent .nav-link {
            cursor: pointer;
        }

        .sidebar-nav .nav-parent .nav-link .chevron {
            margin-left: auto;
            transition: transform 0.2s;
            width: 14px;
            height: 14px;
            opacity: 0.5;
        }

        .sidebar-nav .nav-parent.open .nav-link .chevron {
            transform: rotate(90deg);
        }

        .sidebar-nav .submenu {
            display: none;
            padding-left: 24px;
        }

        .sidebar-nav .nav-parent.open .submenu {
            display: block;
        }

        .sidebar-nav .submenu .nav-link {
            padding: 8px 16px;
            font-size: 13px;
        }

        .sidebar-nav .submenu .nav-link svg {
            width: 16px;
            height: 16px;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 64px;
            padding: 24px;
            min-height: calc(100vh - 64px);
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-user .user-name {
            font-weight: 600;
            font-size: 14px;
            color: #333;
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            padding: 8px;
            cursor: pointer;
            color: #6c757d;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .notification-btn:hover {
            background: #f0f0f0;
        }

        .notification-btn .badge {
            position: absolute;
            top: 2px;
            right: 2px;
            font-size: 10px;
            padding: 2px 5px;
        }

        .btn-logout {
            background: none;
            border: 1px solid #dee2e6;
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            color: #dc3545;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: #dc3545;
            color: #fff;
            border-color: #dc3545;
        }

        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            padding: 8px;
            cursor: pointer;
            color: #333;
            border-radius: 6px;
        }

        .sidebar-toggle:hover {
            background: #f0f0f0;
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1035;
        }

        @media (max-width: 767.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .topbar {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }

            .sidebar-backdrop.show {
                display: block;
            }
        }

        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }

        .stat-card .card-body {
            padding: 20px 24px;
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-card .stat-number {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }

        .stat-card .stat-label {
            font-size: 13px;
            color: #6c757d;
            font-weight: 500;
            margin: 0;
        }

        .action-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            height: 100%;
        }

        .action-card .card-header {
            background: none;
            border-bottom: 1px solid #e9ecef;
            padding: 16px 20px;
            font-weight: 600;
            font-size: 15px;
        }

        .action-card .card-body {
            padding: 20px;
        }

        .action-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            border-radius: 8px;
            color: #333;
            text-decoration: none;
            transition: background 0.2s;
            font-size: 14px;
            font-weight: 500;
        }

        .action-link:hover {
            background: #f0f2f5;
            color: #0d6efd;
        }

        .action-link svg {
            width: 20px;
            height: 20px;
            color: #6c757d;
            flex-shrink: 0;
        }

        .action-link:hover svg {
            color: #0d6efd;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-top: 6px;
            flex-shrink: 0;
        }

        .activity-text {
            font-size: 13px;
            color: #333;
            margin: 0;
        }

        .activity-time {
            font-size: 12px;
            color: #adb5bd;
        }

        .welcome-section {
            margin-bottom: 24px;
        }

        .welcome-section h2 {
            font-weight: 700;
            font-size: 24px;
            color: #1a1a2e;
            margin: 0;
        }

        .welcome-section p {
            color: #6c757d;
            margin: 4px 0 0 0;
        }
    </style>
</head>
<body>

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h4><span>Skul</span>base</h4>
        </div>
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('schools.*') ? 'active' : '' }}" href="{{ route('schools.index') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                        Schools
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Students
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}" href="{{ route('teachers.index') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            <line x1="8" y1="7" x2="16" y2="7"></line>
                            <line x1="8" y1="11" x2="14" y2="11"></line>
                        </svg>
                        Teachers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}" href="{{ route('classes.index') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                        Classes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}" href="{{ route('subjects.index') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            <line x1="8" y1="7" x2="16" y2="7"></line>
                            <line x1="8" y1="11" x2="16" y2="11"></line>
                            <line x1="8" y1="15" x2="12" y2="15"></line>
                        </svg>
                        Subjects
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('assignments.*') ? 'active' : '' }}" href="{{ route('assignments.index') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Assignments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}" href="{{ route('attendance.dashboard') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                            <path d="M8 14h.01"></path>
                            <path d="M12 14h.01"></path>
                            <path d="M16 14h.01"></path>
                            <path d="M8 18h.01"></path>
                            <path d="M12 18h.01"></path>
                            <path d="M16 18h.01"></path>
                        </svg>
                        Attendance
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('fees.*') ? 'active' : '' }}" href="{{ route('fees.dashboard') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        Fees
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('results.*') ? 'active' : '' }}" href="{{ route('results.dashboard') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                        </svg>
                        Results
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('results.scores.*') || request()->routeIs('results.reports.*') ? 'active' : '' }}" href="{{ route('results.scores.dashboard') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                        Scores
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('results.report-cards.*') ? 'active' : '' }}" href="{{ route('results.report-cards.bulk') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 9V2h12v7"></path>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                        Report Cards
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('results.approvals.*') ? 'active' : '' }}" href="{{ route('results.approvals.dashboard') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 11l3 3L22 4"></path>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                        Approvals
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}" href="{{ route('announcements.index') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        Announcements
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }}" href="{{ route('messages.inbox') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        Messages
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}" href="{{ route('events.index') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        School Calendar
                    </a>
                </li>
                @php $userRole = auth()->user()->role ?? ''; @endphp
                @if(in_array($userRole, ['super_admin', 'school_admin']))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admissions.*') ? 'active' : '' }}" href="{{ route('admissions.index') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                        Admissions
                    </a>
                </li>
                <li class="nav-item nav-parent {{ request()->routeIs('timetables.*') || request()->routeIs('periods.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('timetables.*') || request()->routeIs('periods.*') ? 'active' : '' }}" onclick="this.parentElement.classList.toggle('open'); return false;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Academic
                        <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                    <ul class="nav flex-column submenu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('timetables.index') ? 'active' : '' }}" href="{{ route('timetables.index') }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                Timetables
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('timetables.grid') ? 'active' : '' }}" href="{{ route('timetables.grid') }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="3" width="7" height="7"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect>
                                    <rect x="14" y="14" width="7" height="7"></rect>
                                </svg>
                                Weekly Grid
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('periods.*') ? 'active' : '' }}" href="{{ route('periods.index') }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                Periods
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if($userRole === 'teacher')
                <li class="nav-item nav-parent {{ request()->routeIs('teacher.timetable.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('teacher.timetable.*') ? 'active' : '' }}" onclick="this.parentElement.classList.toggle('open'); return false;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Academic
                        <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                    <ul class="nav flex-column submenu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('teacher.timetable.*') ? 'active' : '' }}" href="{{ route('teacher.timetable.index') }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="3" width="7" height="7"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect>
                                    <rect x="14" y="14" width="7" height="7"></rect>
                                </svg>
                                My Timetable
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if($userRole === 'student')
                <li class="nav-item nav-parent {{ request()->routeIs('student.timetable.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('student.timetable.*') ? 'active' : '' }}" onclick="this.parentElement.classList.toggle('open'); return false;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Academic
                        <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                    <ul class="nav flex-column submenu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('student.timetable.*') ? 'active' : '' }}" href="{{ route('student.timetable.index') }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="3" width="7" height="7"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect>
                                    <rect x="14" y="14" width="7" height="7"></rect>
                                </svg>
                                My Timetable
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if($userRole === 'parent')
                <li class="nav-item nav-parent {{ request()->routeIs('parent.timetable.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('parent.timetable.*') ? 'active' : '' }}" onclick="this.parentElement.classList.toggle('open'); return false;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Children
                        <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                    <ul class="nav flex-column submenu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('parent.timetable.*') ? 'active' : '' }}" href="{{ route('parent.timetable.index') }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="3" width="7" height="7"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect>
                                    <rect x="14" y="14" width="7" height="7"></rect>
                                </svg>
                                My Child Timetable
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if(in_array($userRole, ['super_admin', 'school_admin']))
                <li class="nav-item nav-parent {{ request()->routeIs('reports.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" onclick="this.parentElement.classList.toggle('open'); return false;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
                        </svg>
                        Reports & Analytics
                        <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                    <ul class="submenu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.dashboard') ? 'active' : '' }}" href="{{ route('reports.dashboard') }}">
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.students.*') ? 'active' : '' }}" href="{{ route('reports.students.list') }}">
                                <span>Student Reports</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.teachers.*') ? 'active' : '' }}" href="{{ route('reports.teachers.list') }}">
                                <span>Teacher Reports</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.attendance.*') ? 'active' : '' }}" href="{{ route('reports.attendance.summary') }}">
                                <span>Attendance Reports</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.fees.*') ? 'active' : '' }}" href="{{ route('reports.fees.payments') }}">
                                <span>Fee Reports</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.academic.*') ? 'active' : '' }}" href="{{ route('reports.academic.results') }}">
                                <span>Academic Reports</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                        Settings
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <header class="topbar">
        <div>
            <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="topbar-user">
            <button class="notification-btn" aria-label="Notifications">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span class="badge bg-danger rounded-pill">3</span>
            </button>
            <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </header>

    <main class="main-content">
        @if(session('success'))
            <div style="background: #d1e7dd; color: #0f5132; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #f8d7da; color: #842029; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        (function() {
            var sidebar = document.getElementById('sidebar');
            var backdrop = document.getElementById('sidebarBackdrop');
            var toggle = document.getElementById('sidebarToggle');

            if (toggle && sidebar && backdrop) {
                toggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    backdrop.classList.toggle('show');
                });

                backdrop.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                });
            }
        })();
    </script>

    @stack('scripts')
</body>
</html>
