@props([
    'variant' => 'primary',
    'pill' => false,
    'dot' => false,
    'class' => '',
])

@php
    $classes = 'sb-badge sb-badge-' . $variant;
    if ($class) $classes .= ' ' . $class;
@endphp

<span class="{{ $classes }}" {{ $attributes->except('class') }}>
    @if($dot)
        <span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block;margin-right:4px;"></span>
    @endif
    {{ $slot }}
</span>
