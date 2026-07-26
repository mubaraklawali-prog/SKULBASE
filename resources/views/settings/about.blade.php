@extends('layouts.app')

@section('title', 'About Skulbase - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>About Skulbase</h2>
            <p class="text-muted mb-0">Application information and credits</p>
        </div>
        <a href="{{ route('settings.index') }}" class="sb-btn sb-btn-secondary">← Back to Settings</a>
    </div>

    <div class="row">
        {{-- Brand Card --}}
        <div class="col-md-8 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 32px;">
                    <div class="text-center mb-4">
                        <h3 style="font-weight: 700; color: #1a1a2e; margin-bottom: 4px; font-size: 28px;">Skul<span style="color: var(--primary);">base</span></h3>
                        <div class="sb-badge sb-badge-info">Version {{ $systemInfo['app_version'] }}</div>
                        <p style="color: #6c757d; font-size: 15px; margin-bottom: 0;">School Management System</p>
                    </div>

                    <hr style="border-color: #e9ecef; margin: 24px 0;">

                    <p style="color: #333; font-size: 14px; line-height: 1.8; text-align: center; max-width: 600px; margin: 0 auto 24px;">
                        Skulbase is a modern School Management System designed to simplify school administration through student management, attendance, results, fees, admissions, messaging, announcements, assignments, events, reports, and more.
                    </p>

                    <hr style="border-color: #e9ecef; margin: 24px 0;">

                    <div class="text-center">
                        <p style="color: #6c757d; font-size: 13px; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Designed &amp; Developed by</p>
                        <h5 style="font-weight: 700; color: #1a1a2e; margin-bottom: 4px;">Mubarak Lawal</h5>
                        <p style="color: #6c757d; font-size: 13px; margin-bottom: 4px;">Founder</p>
                        <p style="color: var(--primary); font-size: 14px; font-weight: 600; margin-bottom: 4px;">Mubarak Digital Solutions</p>
                        <p style="color: #adb5bd; font-size: 13px; margin-bottom: 0;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: -2px; margin-right: 4px;">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            Sokoto, Nigeria
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- System Info Card --}}
        <div class="col-md-4 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body">
                    <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                        System Information
                    </h5>

                    <div class="table-responsive">
                        <table class="table table-borderless" style="margin-bottom: 0;">
                            <tbody>
                                <tr>
                                    <td style="font-weight: 500; color: #6c757d; padding: 8px 0; border-bottom: 1px solid #f0f0f0; font-size: 13px;">Laravel Version</td>
                                    <td style="color: #333; padding: 8px 0; border-bottom: 1px solid #f0f0f0; font-size: 13px;">{{ $systemInfo['laravel_version'] }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500; color: #6c757d; padding: 8px 0; border-bottom: 1px solid #f0f0f0; font-size: 13px;">PHP Version</td>
                                    <td style="color: #333; padding: 8px 0; border-bottom: 1px solid #f0f0f0; font-size: 13px;">{{ $systemInfo['php_version'] }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500; color: #6c757d; padding: 8px 0; border-bottom: 1px solid #f0f0f0; font-size: 13px;">Database Driver</td>
                                    <td style="color: #333; padding: 8px 0; border-bottom: 1px solid #f0f0f0; font-size: 13px;">{{ ucfirst($systemInfo['database_driver']) }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500; color: #6c757d; padding: 8px 0; border-bottom: 1px solid #f0f0f0; font-size: 13px;">Environment</td>
                                    <td style="color: #333; padding: 8px 0; border-bottom: 1px solid #f0f0f0; font-size: 13px;">
                                        <span class="sb-badge {{ $systemInfo['app_env'] === 'production' ? 'sb-badge-active' : 'sb-badge-pending' }}">
                                            {{ ucfirst($systemInfo['app_env']) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500; color: #6c757d; padding: 8px 0; border-bottom: 1px solid #f0f0f0; font-size: 13px;">App Version</td>
                                    <td style="color: #333; padding: 8px 0; border-bottom: 1px solid #f0f0f0; font-size: 13px;">{{ $systemInfo['app_version'] }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500; color: #6c757d; padding: 8px 0; font-size: 13px;">Current Year</td>
                                    <td style="color: #333; padding: 8px 0; font-size: 13px;">{{ $systemInfo['current_year'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Copyright --}}
    <div class="text-center" style="padding: 16px 0 0;">
        <p style="color: #adb5bd; font-size: 13px; margin-bottom: 4px;">&copy; {{ date('Y') }} Mubarak Digital Solutions</p>
        <p style="color: #adb5bd; font-size: 12px; margin-bottom: 0;">All Rights Reserved.</p>
    </div>
</div>
@endsection
