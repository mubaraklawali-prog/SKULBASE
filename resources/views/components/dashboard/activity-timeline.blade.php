@props([
    'items' => collect(),
    'emptyMessage' => 'No recent activity',
])

@php
    $iconMap = [
        'student' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>',
        'teacher' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>',
        'payment' => '<line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>',
        'announcement' => '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path>',
        'school' => '<path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path>',
        'event' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>',
    ];
@endphp

<div class="ds-timeline">
    @forelse($items as $item)
        <div class="ds-timeline-item">
            <div class="ds-timeline-icon" style="background: {{ $item['color'] ?? '#64748B' }}20; color: {{ $item['color'] ?? '#64748B' }};">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    {!! $iconMap[$item['icon'] ?? ''] ?? $iconMap['announcement'] !!}
                </svg>
            </div>
            <div class="ds-timeline-content">
                <p class="ds-timeline-text">{{ $item['text'] ?? '' }}</p>
                <span class="ds-timeline-time">{{ $item['time']?->diffForHumans() ?? '' }}</span>
            </div>
        </div>
    @empty
        <x-dashboard.empty-state :message="$emptyMessage" size="sm" />
    @endforelse
</div>
