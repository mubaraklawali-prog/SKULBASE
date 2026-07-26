@props([
    'value' => 0,
    'max' => 100,
    'variant' => 'primary',
    'height' => 8,
    'showLabel' => false,
    'class' => '',
])

@php
    $percentage = min(100, max(0, ($max > 0 ? ($value / $max) * 100 : 0)));
    $barClass = 'sb-progress-bar';
    if ($variant !== 'primary') $barClass .= ' sb-progress-bar-' . $variant;
@endphp

<div class="sb-progress {{ $class }}" style="height: {{ $height }}px;" {{ $attributes->except(['class', 'value', 'max', 'variant', 'height', 'showLabel']) }}>
    <div class="{{ $barClass }}" style="width: {{ $percentage }}%;"></div>
</div>
@if($showLabel)
    <span style="font-size: var(--font-size-xs); color: var(--text-muted); margin-top: 4px;">{{ round($percentage) }}%</span>
@endif
