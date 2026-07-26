@props([
    'school' => null,
    'subscription' => null,
    'schoolSetting' => null,
    'superAdmin' => false,
])

@php
    $user = Auth::user();
    $hour = (int) now()->format('H');
    $greeting = match(true) {
        $hour < 12 => 'Good Morning',
        $hour < 17 => 'Good Afternoon',
        default => 'Good Evening',
    };
    $systemHealth = 'Operational';
@endphp

@if($superAdmin)
    <div class="ds-hero ds-hero--platform">
        <div class="ds-hero-bg-orb ds-hero-bg-orb--1"></div>
        <div class="ds-hero-bg-orb ds-hero-bg-orb--2"></div>
        <div class="ds-hero-dots"></div>
        <div class="ds-hero-content">
            <div class="ds-hero-text">
                <p class="ds-hero-greeting">{{ $greeting }}</p>
                <h1 class="ds-hero-title">Welcome back, {{ $user->name ?? 'Admin' }}</h1>
                <p class="ds-hero-subtitle">Monitor schools, subscriptions and platform performance from one place.</p>
                <div class="ds-hero-chips">
                    <span class="ds-hero-chip">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        {{ now()->format('l, M d, Y') }}
                    </span>
                    <span class="ds-hero-chip">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        {{ now()->format('h:i A') }}
                    </span>
                    <span class="ds-hero-chip">
                        <span class="ds-hero-chip-dot ds-hero-chip-dot--green"></span>
                        {{ $systemHealth }}
                    </span>
                </div>
            </div>
            <div class="ds-hero-visual">
                <div class="ds-hero-illustration">
                    <svg viewBox="0 0 240 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="20" y="20" width="200" height="140" rx="12" fill="rgba(255,255,255,0.08)" stroke="rgba(255,255,255,0.12)" stroke-width="1"/>
                        <rect x="20" y="20" width="200" height="32" rx="12" fill="rgba(255,255,255,0.06)"/>
                        <circle cx="36" cy="36" r="4" fill="#EF4444"/>
                        <circle cx="50" cy="36" r="4" fill="#F59E0B"/>
                        <circle cx="64" cy="36" r="4" fill="#22C55E"/>
                        <line x1="20" y1="52" x2="220" y2="52" stroke="rgba(255,255,255,0.08)" stroke-width="1"/>
                        <rect x="36" y="68" width="60" height="8" rx="4" fill="rgba(255,255,255,0.12)"/>
                        <rect x="36" y="84" width="40" height="6" rx="3" fill="rgba(255,255,255,0.08)"/>
                        <rect x="36" y="100" width="80" height="40" rx="8" fill="rgba(255,255,255,0.06)" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
                        <polyline points="44,128 60,116 76,124 92,108 108,120" stroke="#5B21FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        <circle cx="44" cy="128" r="3" fill="#5B21FF"/>
                        <circle cx="60" cy="116" r="3" fill="#5B21FF"/>
                        <circle cx="76" cy="124" r="3" fill="#5B21FF"/>
                        <circle cx="92" cy="108" r="3" fill="#5B21FF"/>
                        <circle cx="108" cy="120" r="3" fill="#5B21FF"/>
                        <rect x="130" y="68" width="74" height="24" rx="6" fill="rgba(91,33,255,0.15)" stroke="rgba(91,33,255,0.3)" stroke-width="1"/>
                        <rect x="138" y="76" width="30" height="4" rx="2" fill="rgba(255,255,255,0.3)"/>
                        <rect x="138" y="84" width="18" height="3" rx="1.5" fill="rgba(255,255,255,0.15)"/>
                        <rect x="130" y="100" width="36" height="40" rx="6" fill="rgba(34,197,94,0.12)" stroke="rgba(34,197,94,0.2)" stroke-width="1"/>
                        <rect x="138" y="110" width="20" height="4" rx="2" fill="rgba(34,197,94,0.3)"/>
                        <rect x="138" y="120" width="14" height="3" rx="1.5" fill="rgba(34,197,94,0.2)"/>
                        <rect x="174" y="100" width="30" height="40" rx="6" fill="rgba(245,158,11,0.12)" stroke="rgba(245,158,11,0.2)" stroke-width="1"/>
                        <rect x="180" y="110" width="18" height="4" rx="2" fill="rgba(245,158,11,0.3)"/>
                        <rect x="180" y="120" width="12" height="3" rx="1.5" fill="rgba(245,158,11,0.2)"/>
                    </svg>
                </div>
                <div class="ds-hero-glass">
                    <div class="ds-hero-glass-inner">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <div>
                            <span class="ds-hero-glass-value">100%</span>
                            <span class="ds-hero-glass-label">System Uptime</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif($school)
    <div class="ds-hero ds-hero--school">
        <div class="ds-hero-bg-orb ds-hero-bg-orb--1"></div>
        <div class="ds-hero-bg-orb ds-hero-bg-orb--2"></div>
        <div class="ds-hero-dots"></div>
        <div class="ds-hero-content">
            <div class="ds-hero-text">
                <p class="ds-hero-greeting">{{ $greeting }}</p>
                <h1 class="ds-hero-title">{{ $school->name }}</h1>
                @if($school->motto)
                    <p class="ds-hero-motto">"{{ $school->motto }}"</p>
                @endif
                <p class="ds-hero-subtitle">Welcome back, {{ $user->name }}. Here's your school overview.</p>
                <div class="ds-hero-chips">
                    @if($subscription)
                        @php $sub = $subscription; @endphp
                        <span class="ds-hero-chip ds-hero-chip--badge ds-hero-chip--{{ $sub->status }}">
                            <span class="ds-hero-chip-dot ds-hero-chip-dot--{{ $sub->is_trial ? 'yellow' : ($sub->isActive() ? 'green' : 'red') }}"></span>
                            @if($sub->is_trial) Trial
                            @elseif($sub->isActive()) {{ $sub->plan->name ?? 'Active' }}
                            @elseif($sub->isGrace()) Grace Period
                            @else {{ ucfirst($sub->status) }}
                            @endif
                        </span>
                    @endif
                    @if($schoolSetting && $schoolSetting->current_session)
                        <span class="ds-hero-chip">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            {{ $schoolSetting->current_session }} &middot; {{ $schoolSetting->current_term ?? '' }}
                        </span>
                    @endif
                    <span class="ds-hero-chip">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        {{ now()->format('h:i A') }}
                    </span>
                    <span class="ds-hero-chip">
                        <span class="ds-hero-chip-dot ds-hero-chip-dot--green"></span>
                        {{ $systemHealth }}
                    </span>
                </div>
            </div>
            <div class="ds-hero-visual">
                <div class="ds-hero-illustration">
                    <svg viewBox="0 0 240 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="20" y="20" width="200" height="140" rx="12" fill="rgba(255,255,255,0.08)" stroke="rgba(255,255,255,0.12)" stroke-width="1"/>
                        <rect x="20" y="20" width="200" height="32" rx="12" fill="rgba(255,255,255,0.06)"/>
                        <circle cx="36" cy="36" r="4" fill="#EF4444"/>
                        <circle cx="50" cy="36" r="4" fill="#F59E0B"/>
                        <circle cx="64" cy="36" r="4" fill="#22C55E"/>
                        <line x1="20" y1="52" x2="220" y2="52" stroke="rgba(255,255,255,0.08)" stroke-width="1"/>
                        <rect x="36" y="68" width="60" height="8" rx="4" fill="rgba(255,255,255,0.12)"/>
                        <rect x="36" y="84" width="40" height="6" rx="3" fill="rgba(255,255,255,0.08)"/>
                        <rect x="36" y="100" width="80" height="40" rx="8" fill="rgba(255,255,255,0.06)" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
                        <polyline points="44,128 60,116 76,124 92,108 108,120" stroke="#5B21FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        <circle cx="44" cy="128" r="3" fill="#5B21FF"/>
                        <circle cx="60" cy="116" r="3" fill="#5B21FF"/>
                        <circle cx="76" cy="124" r="3" fill="#5B21FF"/>
                        <circle cx="92" cy="108" r="3" fill="#5B21FF"/>
                        <circle cx="108" cy="120" r="3" fill="#5B21FF"/>
                        <rect x="130" y="68" width="74" height="24" rx="6" fill="rgba(91,33,255,0.15)" stroke="rgba(91,33,255,0.3)" stroke-width="1"/>
                        <rect x="138" y="76" width="30" height="4" rx="2" fill="rgba(255,255,255,0.3)"/>
                        <rect x="138" y="84" width="18" height="3" rx="1.5" fill="rgba(255,255,255,0.15)"/>
                        <rect x="130" y="100" width="36" height="40" rx="6" fill="rgba(34,197,94,0.12)" stroke="rgba(34,197,94,0.2)" stroke-width="1"/>
                        <rect x="138" y="110" width="20" height="4" rx="2" fill="rgba(34,197,94,0.3)"/>
                        <rect x="138" y="120" width="14" height="3" rx="1.5" fill="rgba(34,197,94,0.2)"/>
                        <rect x="174" y="100" width="30" height="40" rx="6" fill="rgba(245,158,11,0.12)" stroke="rgba(245,158,11,0.2)" stroke-width="1"/>
                        <rect x="180" y="110" width="18" height="4" rx="2" fill="rgba(245,158,11,0.3)"/>
                        <rect x="180" y="120" width="12" height="3" rx="1.5" fill="rgba(245,158,11,0.2)"/>
                    </svg>
                </div>
                <div class="ds-hero-glass">
                    <div class="ds-hero-glass-inner">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <div>
                            <span class="ds-hero-glass-value">Active</span>
                            <span class="ds-hero-glass-label">School Status</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="ds-hero">
        <div class="ds-hero-bg-orb ds-hero-bg-orb--1"></div>
        <div class="ds-hero-bg-orb ds-hero-bg-orb--2"></div>
        <div class="ds-hero-dots"></div>
        <div class="ds-hero-content">
            <div class="ds-hero-text">
                <p class="ds-hero-greeting">{{ $greeting }}</p>
                <h1 class="ds-hero-title">Welcome back, {{ $user->name ?? 'User' }}</h1>
                <p class="ds-hero-subtitle">Here's what's happening at your school today.</p>
                <div class="ds-hero-chips">
                    <span class="ds-hero-chip">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        {{ now()->format('l, M d, Y') }}
                    </span>
                    <span class="ds-hero-chip">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        {{ now()->format('h:i A') }}
                    </span>
                    <span class="ds-hero-chip">
                        <span class="ds-hero-chip-dot ds-hero-chip-dot--green"></span>
                        {{ $systemHealth }}
                    </span>
                </div>
            </div>
            <div class="ds-hero-visual">
                <div class="ds-hero-illustration">
                    <svg viewBox="0 0 240 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="20" y="20" width="200" height="140" rx="12" fill="rgba(255,255,255,0.08)" stroke="rgba(255,255,255,0.12)" stroke-width="1"/>
                        <rect x="20" y="20" width="200" height="32" rx="12" fill="rgba(255,255,255,0.06)"/>
                        <circle cx="36" cy="36" r="4" fill="#EF4444"/>
                        <circle cx="50" cy="36" r="4" fill="#F59E0B"/>
                        <circle cx="64" cy="36" r="4" fill="#22C55E"/>
                        <line x1="20" y1="52" x2="220" y2="52" stroke="rgba(255,255,255,0.08)" stroke-width="1"/>
                        <rect x="36" y="68" width="60" height="8" rx="4" fill="rgba(255,255,255,0.12)"/>
                        <rect x="36" y="84" width="40" height="6" rx="3" fill="rgba(255,255,255,0.08)"/>
                        <rect x="36" y="100" width="80" height="40" rx="8" fill="rgba(255,255,255,0.06)" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
                        <polyline points="44,128 60,116 76,124 92,108 108,120" stroke="#5B21FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        <circle cx="44" cy="128" r="3" fill="#5B21FF"/>
                        <circle cx="60" cy="116" r="3" fill="#5B21FF"/>
                        <circle cx="76" cy="124" r="3" fill="#5B21FF"/>
                        <circle cx="92" cy="108" r="3" fill="#5B21FF"/>
                        <circle cx="108" cy="120" r="3" fill="#5B21FF"/>
                        <rect x="130" y="68" width="74" height="24" rx="6" fill="rgba(91,33,255,0.15)" stroke="rgba(91,33,255,0.3)" stroke-width="1"/>
                        <rect x="138" y="76" width="30" height="4" rx="2" fill="rgba(255,255,255,0.3)"/>
                        <rect x="138" y="84" width="18" height="3" rx="1.5" fill="rgba(255,255,255,0.15)"/>
                        <rect x="130" y="100" width="36" height="40" rx="6" fill="rgba(34,197,94,0.12)" stroke="rgba(34,197,94,0.2)" stroke-width="1"/>
                        <rect x="138" y="110" width="20" height="4" rx="2" fill="rgba(34,197,94,0.3)"/>
                        <rect x="138" y="120" width="14" height="3" rx="1.5" fill="rgba(34,197,94,0.2)"/>
                        <rect x="174" y="100" width="30" height="40" rx="6" fill="rgba(245,158,11,0.12)" stroke="rgba(245,158,11,0.2)" stroke-width="1"/>
                        <rect x="180" y="110" width="18" height="4" rx="2" fill="rgba(245,158,11,0.3)"/>
                        <rect x="180" y="120" width="12" height="3" rx="1.5" fill="rgba(245,158,11,0.2)"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
@endif
