@props([
    'striped' => false,
    'hover' => true,
    'compact' => false,
    'responsive' => true,
    'class' => '',
])

@php
    $classes = 'sb-table';
    if ($hover) $classes .= ' table-hover';
    if ($class) $classes .= ' ' . $class;
@endphp

@if($responsive)
    <div class="table-responsive sb-table-wrapper">
@endif
    <table class="{{ $classes }}" {{ $attributes->except('class') }}>
        {{ $slot }}
    </table>
@if($responsive)
    </div>
@endif
