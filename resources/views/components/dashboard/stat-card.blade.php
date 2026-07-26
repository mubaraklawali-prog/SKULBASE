@props([
    'title' => '',
    'value' => '0',
    'color' => 'primary',
    'description' => '',
    'change' => null,
    'changeLabel' => '',
    'href' => null,
    'prefix' => '',
    'class' => '',
])

@php
    $colorMap = [
        'primary' => ['bg' => 'rgba(91,33,255,0.08)', 'fg' => '#5B21FF'],
        'secondary' => ['bg' => '#D1FAE5', 'fg' => '#10B981'],
        'success' => ['bg' => '#F0FDF4', 'fg' => '#22C55E'],
        'warning' => ['bg' => '#FFFBEB', 'fg' => '#F59E0B'],
        'danger' => ['bg' => '#FEF2F2', 'fg' => '#EF4444'],
        'info' => ['bg' => '#EFF6FF', 'fg' => '#3B82F6'],
        'blue' => ['bg' => '#EFF6FF', 'fg' => '#3B82F6'],
        'purple' => ['bg' => 'rgba(91,33,255,0.08)', 'fg' => '#5B21FF'],
        'green' => ['bg' => '#D1FAE5', 'fg' => '#10B981'],
        'orange' => ['bg' => '#FFFBEB', 'fg' => '#F59E0B'],
        'red' => ['bg' => '#FEF2F2', 'fg' => '#EF4444'],
        'pink' => ['bg' => '#FDF2F8', 'fg' => '#EC4899'],
        'indigo' => ['bg' => '#EEF2FF', 'fg' => '#6366F1'],
        'teal' => ['bg' => '#F0FDFA', 'fg' => '#14B8A6'],
        'slate' => ['bg' => '#F1F5F9', 'fg' => '#64748B'],
    ];
    $c = $colorMap[$color] ?? $colorMap['primary'];
    $Tag = $href ? 'a' : 'div';
@endphp

<{{ $Tag }} class="ds-stat-card ds-animate-in {{ $class }}" @if($href) href="{{ $href }}" @endif>
    <div class="ds-stat-card-header">
        <span class="ds-stat-card-title">{{ $title }}</span>
        <div class="ds-stat-card-icon ds-stat-card-icon--{{ $color }}" style="background: {{ $c['bg'] }}; color: {{ $c['fg'] }};">
            {!! $icon ?? '' !!}
        </div>
    </div>
    <div class="ds-stat-card-value">{{ $prefix }}{{ $value }}</div>
    @if($change !== null)
        <div class="ds-stat-card-footer">
            <span class="ds-stat-change {{ $change >= 0 ? 'ds-stat-change-up' : 'ds-stat-change-down' }}">
                @if($change >= 0)
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"></polyline></svg>
                @else
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                @endif
                {{ abs($change) }}%
            </span>
            @if($changeLabel)
                <span class="ds-stat-change-label">{{ $changeLabel }}</span>
            @endif
        </div>
    @elseif($description)
        <div class="ds-stat-card-footer">
            <span class="ds-stat-desc">{{ $description }}</span>
        </div>
    @endif
</{{ $Tag }}>
