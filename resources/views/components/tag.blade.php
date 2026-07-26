@props([
    'label' => '',
    'variant' => 'neutral',
    'size' => 'md',
    'class' => '',
])

@php
    $classes = 'sb-tag';
    if ($variant !== 'neutral') $classes .= ' sb-badge-' . $variant;
    if ($class) $classes .= ' ' . $class;
@endphp

<span class="{{ $classes }}" {{ $attributes->except('class') }}>
    {{ $label ?: $slot }}
</span>
