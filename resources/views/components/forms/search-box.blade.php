@props([
    'name' => '',
    'label' => '',
    'value' => null,
    'type' => 'text',
    'placeholder' => 'Search...',
    'class' => '',
    'wrapperClass' => '',
    'id' => null,
    'route' => null,
    'method' => 'GET',
    'clearUrl' => null,
])

@php
    $inputId = $id ?: ($name ? "search_{$name}" : null);
    $currentValue = $value ?? request($name, '');
@endphp

<form method="{{ $method }}" @if($route) action="{{ $route }}" @endif class="sb-search-bar {{ $wrapperClass }}" {{ $attributes->except(['class', 'name', 'placeholder', 'route', 'method', 'clearUrl', 'wrapperClass', 'id']) }}>
    <div style="position:relative;flex:1;">
        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);pointer-events:none;display:flex;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </span>
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $inputId }}" value="{{ $currentValue }}" placeholder="{{ $placeholder }}" class="sb-input {{ $class }}" style="padding-left:40px;">
    </div>
    <button type="submit" class="sb-btn sb-btn-primary">Search</button>
    @if($clearUrl || request($name))
        <a href="{{ $clearUrl ?? request()->url() }}" class="sb-btn sb-btn-ghost">Clear</a>
    @endif
</form>
