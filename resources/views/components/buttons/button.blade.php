@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'icon' => null,
    'iconRight' => null,
    'loading' => false,
    'disabled' => false,
    'block' => false,
    'type' => 'button',
    'class' => '',
    'confirm' => null,
])

@php
    $tag = $href ? 'a' : 'button';
    $classes = 'sb-btn sb-btn-' . $variant;
    if ($size === 'sm') $classes .= ' sb-btn-sm';
    if ($size === 'lg') $classes .= ' sb-btn-lg';
    if ($block) $classes .= ' w-100';
    if ($class) $classes .= ' ' . $class;
    $attrs = $href ? "href=\"{$href}\"" : "type=\"{$type}\"";
    if ($disabled && !$href) $attrs .= ' disabled';
    if ($loading) $attrs .= ' disabled';
    if ($confirm) $attrs .= " onclick=\"return confirm('" . e($confirm) . "')\"";
@endphp

<{{ $tag }} {{ $attrs }} class="{{ $classes }}" {{ $attributes->except(['class', 'href', 'type', 'disabled', 'onclick']) }}>
    @if($loading)
        <svg class="sb-btn-spinner" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation: sb-spin 0.6s linear infinite;">
            <path d="M21 12a9 9 0 11-6.219-8.56"></path>
        </svg>
    @elseif($icon)
        {!! $icon !!}
    @endif
    {{ $slot }}
    @if($iconRight)
        {!! $iconRight !!}
    @endif
</{{ $tag }}>
