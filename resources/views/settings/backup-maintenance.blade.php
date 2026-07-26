@extends('layouts.app')

@section('title', 'Backup & Maintenance - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Backup & Maintenance</h2>
            <p class="text-muted mb-0">System maintenance and backup tools</p>
        </div>
        <a href="{{ route('settings.index') }}" class="sb-btn sb-btn-secondary">← Back to Settings</a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        {{-- Card 1: System Information --}}
        <div class="col-md-6 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body">
                    <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                                    <td style="font-weight: 500; color: #6c757d; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">Laravel Version</td>
                                    <td style="color: #333; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">{{ $systemInfo['laravel_version'] }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500; color: #6c757d; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">PHP Version</td>
                                    <td style="color: #333; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">{{ $systemInfo['php_version'] }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500; color: #6c757d; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">Database Driver</td>
                                    <td style="color: #333; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">{{ ucfirst($systemInfo['database_driver']) }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500; color: #6c757d; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">Application Environment</td>
                                    <td style="color: #333; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                                        <span class="sb-badge {{ $systemInfo['app_env'] === 'production' ? 'sb-badge-active' : 'sb-badge-pending' }}">
                                            {{ ucfirst($systemInfo['app_env']) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500; color: #6c757d; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">Application URL</td>
                                    <td style="color: #333; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">{{ $systemInfo['app_url'] }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500; color: #6c757d; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">Current Timezone</td>
                                    <td style="color: #333; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">{{ $systemInfo['timezone'] }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500; color: #6c757d; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">Current Server Time</td>
                                    <td style="color: #333; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">{{ $systemInfo['server_time'] }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500; color: #6c757d; padding: 8px 0;">Storage Usage</td>
                                    <td style="color: #333; padding: 8px 0;">{{ $systemInfo['storage_usage'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            {{-- Card 2: Cache Management --}}
            <div class="card stat-card mb-4">
                <div class="card-body">
                    <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffc107" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                            <path d="M2 17l10 5 10-5"></path>
                            <path d="M2 12l10 5 10-5"></path>
                        </svg>
                        Cache Management
                    </h5>

                    <p style="font-size: 13px; color: #6c757d; margin-bottom: 16px;">Clear cached data to free up memory and resolve issues.</p>

                    <div class="row g-2">
                        <div class="col-6">
                            <form method="POST" action="{{ route('settings.backup-maintenance.clear-cache') }}" onsubmit="return confirm('Are you sure you want to clear the application cache?');">
                                @csrf
                                <button type="submit" class="sb-btn sb-btn-primary w-100">Clear App Cache</button>
                            </form>
                        </div>
                        <div class="col-6">
                            <form method="POST" action="{{ route('settings.backup-maintenance.clear-config-cache') }}" onsubmit="return confirm('Are you sure you want to clear the config cache?');">
                                @csrf
                                <button type="submit" class="sb-btn sb-btn-primary w-100">Clear Config Cache</button>
                            </form>
                        </div>
                        <div class="col-6">
                            <form method="POST" action="{{ route('settings.backup-maintenance.clear-route-cache') }}" onsubmit="return confirm('Are you sure you want to clear the route cache?');">
                                @csrf
                                <button type="submit" class="sb-btn sb-btn-outline-primary w-100">Clear Route Cache</button>
                            </form>
                        </div>
                        <div class="col-6">
                            <form method="POST" action="{{ route('settings.backup-maintenance.clear-view-cache') }}" onsubmit="return confirm('Are you sure you want to clear the view cache?');">
                                @csrf
                                <button type="submit" class="sb-btn sb-btn-danger w-100">Clear View Cache</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: Storage Maintenance --}}
            <div class="card stat-card mb-4">
                <div class="card-body">
                    <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#20c997" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                        </svg>
                        Storage Maintenance
                    </h5>

                    <p style="font-size: 13px; color: #6c757d; margin-bottom: 16px;">Create a symbolic link from public storage to the app's storage directory.</p>

                    <form method="POST" action="{{ route('settings.backup-maintenance.create-storage-link') }}" onsubmit="return confirm('Create storage link?');">
                        @csrf
                        <button type="submit" class="sb-btn sb-btn-outline-success">Create Storage Link</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            {{-- Card 4: Database Backup --}}
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body">
                    <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fd7e14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                            <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                            <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                        </svg>
                        Database Backup
                    </h5>

                    <p style="font-size: 13px; color: #6c757d; margin-bottom: 16px;">Create a timestamped SQL backup of your database. Backups are stored in <code>storage/app/backups/</code>.</p>

                    <form method="POST" action="{{ route('settings.backup-maintenance.create-backup') }}" onsubmit="return confirm('Create a database backup? This may take a moment for large databases.');">
                        @csrf
                        <button type="submit" class="sb-btn sb-btn-outline-warning">Create Database Backup</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            {{-- Card 5: Maintenance Mode --}}
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body">
                    <h5 style="font-weight: 600; color: #1a1a2e; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
                        </svg>
                        Maintenance Mode
                    </h5>

                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                        <span style="font-size: 13px; color: #6c757d;">Current Status:</span>
                        @if($isMaintenanceMode)
                            <span class="sb-badge sb-badge-rejected">ENABLED</span>
                        @else
                            <span class="sb-badge sb-badge-active">DISABLED</span>
                        @endif
                    </div>

                    <p style="font-size: 13px; color: #6c757d; margin-bottom: 16px;">
                        @if($isMaintenanceMode)
                            The application is currently in maintenance mode. Only administrators can access the site.
                        @else
                            When enabled, the application will show a maintenance page to all non-admin users.
                        @endif
                    </p>

                    <div class="d-flex gap-2">
                        @if(!$isMaintenanceMode)
                            <form method="POST" action="{{ route('settings.backup-maintenance.enable-maintenance') }}" onsubmit="return confirm('Enable maintenance mode? Non-admin users will not be able to access the site.');">
                                @csrf
                                <button type="submit" class="sb-btn sb-btn-danger">Enable Maintenance Mode</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('settings.backup-maintenance.disable-maintenance') }}" onsubmit="return confirm('Disable maintenance mode? The site will be accessible to all users again.');">
                                @csrf
                                <button type="submit" class="sb-btn sb-btn-primary">Disable Maintenance Mode</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
