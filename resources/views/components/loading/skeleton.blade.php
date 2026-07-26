@props([
    'variant' => 'md',
    'class' => '',
])

@php
    $variants = [
        'sm' => '<div class="sb-skeleton" style="height:12px;width:100%;margin-bottom:8px;"></div><div class="sb-skeleton" style="height:12px;width:70%;"></div>',
        'md' => '<div class="sb-skeleton" style="height:24px;width:60%;margin-bottom:12px;"></div><div class="sb-skeleton" style="height:14px;width:100%;margin-bottom:8px;"></div><div class="sb-skeleton" style="height:14px;width:100%;margin-bottom:8px;"></div><div class="sb-skeleton" style="height:14px;width:80%;"></div>',
        'lg' => '<div class="sb-skeleton" style="height:32px;width:40%;margin-bottom:16px;"></div><div class="sb-skeleton" style="height:14px;width:100%;margin-bottom:8px;"></div><div class="sb-skeleton" style="height:14px;width:100%;margin-bottom:8px;"></div><div class="sb-skeleton" style="height:14px;width:100%;margin-bottom:8px;"></div><div class="sb-skeleton" style="height:14px;width:60%;"></div>',
        'card' => '<div style="padding:var(--space-5);"><div class="sb-skeleton" style="height:20px;width:40%;margin-bottom:var(--space-4);"></div><div class="sb-skeleton" style="height:14px;width:100%;margin-bottom:var(--space-2);"></div><div class="sb-skeleton" style="height:14px;width:100%;margin-bottom:var(--space-2);"></div><div class="sb-skeleton" style="height:14px;width:70%;"></div></div>',
        'avatar' => '<div style="display:flex;align-items:center;gap:var(--space-3);"><div class="sb-avatar-skeleton sb-skeleton" style="width:40px;height:40px;border-radius:50%;flex-shrink:0;"></div><div style="flex:1;"><div class="sb-skeleton" style="height:14px;width:60%;margin-bottom:var(--space-2);"></div><div class="sb-skeleton" style="height:12px;width:40%;"></div></div></div>',
        'table' => '<div class="sb-skeleton-card"><div class="sb-skeleton-list"><div class="sb-skeleton-list-item"><div class="sb-avatar-skeleton sb-skeleton" style="width:32px;height:32px;border-radius:50%;"></div><div class="sb-skeleton-list-text"><div class="sb-skeleton" style="height:14px;width:60%;margin-bottom:4px;"></div><div class="sb-skeleton" style="height:12px;width:40%;"></div></div></div><div class="sb-skeleton-list-item"><div class="sb-avatar-skeleton sb-skeleton" style="width:32px;height:32px;border-radius:50%;"></div><div class="sb-skeleton-list-text"><div class="sb-skeleton" style="height:14px;width:60%;margin-bottom:4px;"></div><div class="sb-skeleton" style="height:12px;width:40%;"></div></div></div><div class="sb-skeleton-list-item"><div class="sb-avatar-skeleton sb-skeleton" style="width:32px;height:32px;border-radius:50%;"></div><div class="sb-skeleton-list-text"><div class="sb-skeleton" style="height:14px;width:60%;margin-bottom:4px;"></div><div class="sb-skeleton" style="height:12px;width:40%;"></div></div></div></div></div>',
        'chart' => '<div class="sb-skeleton-chart-wrap"><div class="sb-skeleton" style="height:18px;width:35%;margin-bottom:var(--space-4);"></div><div class="sb-skeleton-chart" style="height:200px;"></div></div>',
    ];
@endphp

<div class="{{ $class }}" {{ $attributes->except('class') }}>
    {!! $variants[$variant] ?? $variants['md'] !!}
</div>
