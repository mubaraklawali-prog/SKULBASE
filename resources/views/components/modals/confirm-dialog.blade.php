@props([
    'variant' => 'danger',
    'title' => 'Are you sure?',
    'message' => 'This action cannot be undone.',
    'confirmLabel' => 'Confirm',
    'cancelLabel' => 'Cancel',
    'icon' => null,
    'formId' => null,
])

@php
    $variantColors = [
        'danger' => ['bg' => '#FEF2F2', 'fg' => '#EF4444', 'btn' => 'sb-btn-danger'],
        'warning' => ['bg' => '#FFFBEB', 'fg' => '#F59E0B', 'btn' => 'sb-btn-warning'],
        'info' => ['bg' => '#EFF6FF', 'fg' => '#3B82F6', 'btn' => 'sb-btn-primary'],
    ];
    $v = $variantColors[$variant] ?? $variantColors['danger'];
@endphp

<div class="sb-alert sb-alert-{{ $variant === 'danger' ? 'danger' : ($variant === 'warning' ? 'warning' : 'info') }}" style="margin-bottom:var(--space-4);" {{ $attributes->except('class') }}>
    <div style="display:flex;align-items:flex-start;gap:var(--space-3);">
        <div style="width:32px;height:32px;border-radius:50%;background:{{ $v['bg'] }};color:{{ $v['fg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            {!! $icon ?? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>' !!}
        </div>
        <div style="flex:1;">
            <p style="margin:0 0 var(--space-2);font-weight:var(--font-weight-semibold);color:var(--text-heading);">{{ $title }}</p>
            <p style="margin:0;font-size:var(--font-size-sm);color:var(--text-muted);">{{ $message }}</p>
        </div>
    </div>
</div>
