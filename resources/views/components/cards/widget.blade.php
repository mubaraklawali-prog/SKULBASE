@props([
    'title' => '',
    'subtitle' => '',
    'icon' => null,
    'href' => null,
    'hrefLabel' => 'View All',
    'dismissible' => false,
    'class' => '',
])

@php
    $hasHeader = $title || $subtitle || $icon || isset($actions);
@endphp

<div class="sb-card {{ $class }}" {{ $attributes->except('class') }}>
    @if($hasHeader)
        <div class="sb-card-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:var(--space-4);">
            <div style="display:flex;align-items:center;gap:var(--space-3);min-width:0;">
                @if($icon)
                    <div style="flex-shrink:0;display:flex;">{!! $icon !!}</div>
                @endif
                <div style="min-width:0;">
                    <h5 style="margin:0;font-size:var(--font-size-base);font-weight:var(--font-weight-semibold);color:var(--text-heading);">{{ $title }}</h5>
                    @if($subtitle)
                        <p style="margin:var(--space-1) 0 0;font-size:var(--font-size-xs);color:var(--text-muted);">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:var(--space-2);flex-shrink:0;">
                @if(isset($actions))
                    {{ $actions }}
                @endif
                @if($href)
                    <a href="{{ $href }}" style="font-size:var(--font-size-sm);color:var(--primary);text-decoration:none;font-weight:var(--font-weight-medium);white-space:nowrap;">{{ $hrefLabel }} &rarr;</a>
                @endif
                @if($dismissible)
                    <button type="button" class="sb-widget-dismiss" aria-label="Dismiss" data-dismiss-widget>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                @endif
            </div>
        </div>
    @endif
    <div class="sb-card-body" {{ $attributes->except(['class', 'title', 'subtitle', 'icon', 'href', 'hrefLabel', 'dismissible']) }}>
        {{ $slot }}
    </div>
    @if(isset($footer))
        <div class="sb-card-footer">
            {{ $footer }}
        </div>
    @endif
</div>
