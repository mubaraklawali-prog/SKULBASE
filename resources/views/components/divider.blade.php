@props([
    'label' => '',
    'color' => null,
    'class' => '',
])

<hr class="sb-divider {{ $class }}" {{ $attributes->except('class') }}>
