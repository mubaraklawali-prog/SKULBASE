@props([
    'title' => '',
    'value' => '0',
    'color' => 'primary',
    'description' => '',
    'change' => null,
    'changeLabel' => '',
    'href' => null,
    'prefix' => '',
    'icon' => null,
    'class' => '',
])

@php
    $colorMap = [
        'primary' => ['bg' => 'rgba(91,33,255,0.08)', 'fg' => '#5B21FF'],
        'secondary' => ['bg' => '#D1FAE5', 'fg' => '#10B981'],
        'success' => ['bg' => '#D1FAE5', 'fg' => '#10B981'],
        'warning' => ['bg' => '#FFFBEB', 'fg' => '#F59E0B'],
        'danger' => ['bg' => '#FEF2F2', 'fg' => '#EF4444'],
        'info' => ['bg' => '#EFF6FF', 'fg' => '#3B82F6'],
    ];
    $c = $colorMap[$color] ?? $colorMap['primary'];
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} class="sb-stat-card {{ $class }}" @if($href) href="{{ $href }}" @endif {{ $attributes->except(['class', 'href']) }} style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius-xl);padding:var(--space-6);box-shadow:var(--shadow-card);transition:all 0.25s cubic-bezier(0.4,0,0.2,1);display:flex;flex-direction:column;height:100%;text-decoration:none;color:inherit;">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:var(--space-4);">
        <span style="font-size:var(--font-size-sm);font-weight:var(--font-weight-medium);color:var(--text-muted);">{{ $title }}</span>
        @if($icon)
            <div style="width:48px;height:48px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:{{ $c['bg'] }};color:{{ $c['fg'] }};">
                {!! $icon !!}
            </div>
        @endif
    </div>
    <div style="font-size:2rem;font-weight:var(--font-weight-bold);color:var(--text-heading);line-height:1.1;margin-bottom:var(--space-2);letter-spacing:-0.025em;">{{ $prefix }}{{ $value }}</div>
    @if($change !== null)
        <div style="display:flex;align-items:center;gap:var(--space-2);margin-top:auto;">
            <span style="display:inline-flex;align-items:center;gap:2px;font-size:var(--font-size-xs);font-weight:var(--font-weight-semibold);padding:2px 8px;border-radius:var(--radius-full);{{ $change >= 0 ? 'color:var(--success-dark);background:var(--success-light);' : 'color:var(--danger-dark);background:var(--danger-light);' }}">
                @if($change >= 0)
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"></polyline></svg>
                @else
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                @endif
                {{ abs($change) }}%
            </span>
            @if($changeLabel)
                <span style="font-size:var(--font-size-xs);color:var(--text-muted);">{{ $changeLabel }}</span>
            @endif
        </div>
    @elseif($description)
        <div style="display:flex;align-items:center;gap:var(--space-2);margin-top:auto;">
            <span style="font-size:var(--font-size-xs);color:var(--text-muted);">{{ $description }}</span>
        </div>
    @endif
</{{ $tag }}>
