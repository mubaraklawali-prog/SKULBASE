@props([
    'href' => null,
    'icon' => null,
    'danger' => false,
    'disabled' => false,
    'class' => '',
])

@php
    $tag = $href ? 'a' : 'button';
    $attrs = $href ? "href=\"{$href}\"" : "type=\"button\"";
    if ($disabled) $attrs .= ' disabled';
    $colorStyle = $danger ? 'color:var(--danger);' : '';
@endphp

<{{ $tag }} {{ $attrs }} class="sb-dropdown-item {{ $class }}" style="{{$colorStyle}} {{$disabled ? 'opacity:0.5;pointer-events:none;' : ''}}" {{ $attributes->except(['class', 'href', 'type', 'disabled']) }}>
    @if($icon)
        <span style="flex-shrink:0;display:flex;">{!! $icon !!}</span>
    @endif
    {{ $slot }}
</{{ $tag }}>
