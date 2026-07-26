@props([
    'variant' => 'info',
    'title' => null,
    'dismissible' => false,
    'icon' => null,
    'class' => '',
])

@php
    $classes = 'sb-alert sb-alert-' . $variant;
    if ($class) $classes .= ' ' . $class;
    $iconMap = [
        'success' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
        'warning' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
        'danger' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>',
        'info' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
    ];
@endphp

<div class="{{ $classes }}" role="alert" {{ $attributes->except('class') }}>
    @if($icon !== false)
        <span style="flex-shrink:0;">{!! $icon ?? ($iconMap[$variant] ?? '') !!}</span>
    @endif
    <div style="flex:1;">
        @if($title)
            <strong style="display:block;margin-bottom:2px;">{{ $title }}</strong>
        @endif
        {{ $slot }}
    </div>
    @if($dismissible)
        <button type="button" class="sb-alert-dismiss" aria-label="Dismiss" data-dismiss-alert>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    @endif
</div>
