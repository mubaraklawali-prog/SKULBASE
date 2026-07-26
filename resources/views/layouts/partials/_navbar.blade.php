{{-- ================================================================
     NAVBAR PARTIAL — Enterprise Top Navigation Bar
     Mobile toggle, search, notifications, profile dropdown.
     ================================================================ --}}

@php
    $currentUser = Auth::user();
    $userRole = $currentUser->role ?? '';
    $roleLabel = match($userRole) {
        'super_admin' => 'Super Admin',
        'school_admin' => 'School Admin',
        'teacher' => 'Teacher',
        'student' => 'Student',
        'parent' => 'Parent',
        default => ucfirst($userRole),
    };
    $roleBadgeClass = match($userRole) {
        'super_admin' => 'sb-badge-primary',
        'school_admin' => 'sb-badge-info',
        'teacher' => 'sb-badge-secondary',
        'student' => 'sb-badge-success',
        'parent' => 'sb-badge-warning',
        default => 'sb-badge-neutral',
    };
    $initials = strtoupper(substr($currentUser->name ?? 'U', 0, 1));
    $schoolName = $currentUser->school?->name ?? null;
@endphp

<header class="sb-topbar" id="topbar">
    <div class="sb-topbar-left">
        <button class="sb-topbar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        <div class="sb-topbar-search">
            <svg class="sb-topbar-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" class="sb-topbar-search-input" placeholder="Search students, teachers, schools..." aria-label="Search" id="globalSearch">
            <kbd class="sb-topbar-search-kbd">/</kbd>
        </div>
    </div>

    <div class="sb-topbar-right">
        <button class="sb-topbar-icon-btn" aria-label="Notifications" aria-haspopup="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <span class="sb-topbar-badge" aria-hidden="true">3</span>
        </button>

        <div class="sb-dropdown" id="profileDropdown">
            <button class="sb-topbar-profile" aria-expanded="false" aria-controls="profileDropdownMenu" aria-haspopup="true" id="profileDropdownBtn">
                <div class="sb-avatar sb-avatar-sm sb-avatar-primary" aria-hidden="true">
                    {{ $initials }}
                </div>
                <span class="sb-topbar-profile-info">
                    <span class="sb-topbar-profile-name">{{ $currentUser->name ?? 'User' }}</span>
                    <span class="sb-topbar-profile-role {{ $roleBadgeClass }}">{{ $schoolName ? $schoolName . ' · ' . $roleLabel : $roleLabel }}</span>
                </span>
                <svg class="sb-topbar-profile-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>
            <div class="sb-dropdown-menu" id="profileDropdownMenu" role="menu" aria-labelledby="profileDropdownBtn">
                <a href="{{ route('dashboard') }}" class="sb-dropdown-item" role="menuitem">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect></svg>
                    Dashboard
                </a>
                <a href="{{ route('password.change') }}" class="sb-dropdown-item" role="menuitem">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    Change Password
                </a>
                <div class="sb-dropdown-divider" role="separator"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sb-dropdown-item" role="menuitem" style="color: var(--danger); width: 100%; background: none; border: none; text-align: left; cursor: pointer; font: inherit;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
(function() {
    var dropdown = document.getElementById('profileDropdown');
    var toggleBtn = document.getElementById('profileDropdownBtn');
    if (dropdown && toggleBtn) {
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            var isOpen = dropdown.classList.toggle('open');
            toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
                toggleBtn.setAttribute('aria-expanded', 'false');
            }
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && dropdown.classList.contains('open')) {
                dropdown.classList.remove('open');
                toggleBtn.setAttribute('aria-expanded', 'false');
                toggleBtn.focus();
            }
        });
    }
})();
</script>
