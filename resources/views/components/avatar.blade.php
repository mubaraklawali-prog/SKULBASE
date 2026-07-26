@props([
    'src' => null,
    'initials' => null,
    'size' => 'md',
    'color' => 'primary',
    'online' => false,
    'status' => null,
    'class' => '',
])

@php
    $classes = 'sb-avatar sb-avatar-' . $size . ' sb-avatar-' . $color;
    if ($class) $classes .= ' ' . $class;
    $initialsValue = $initials;
    if (!$initialsValue && !$src) {
        $initialsValue = '?';
    }
@endphp

<span class="{{ $classes }}" {{ $attributes->except('class') }}>
    @if($src)
        <img src="{{ $src }}" alt="{{ $attributes->get('alt', 'User avatar') }}" loading="lazy">
    @else
        {{ $initialsValue }}
    @endif
    @if($online)
        <span class="sb-avatar-status-indicator sb-avatar-status-online" aria-label="Online"></span>
    @endif
    @if($status)
        <span class="sb-avatar-status-indicator sb-avatar-status-{{ $status }}" aria-label="{{ ucfirst($status) }}"></span>
    @endif
</span>
