@props([
    'title' => '',
    'subtitle' => '',
    'href' => null,
    'hrefLabel' => 'View All',
    'noPadding' => false,
    'flush' => false,
    'hover' => false,
    'class' => '',
    'headerClass' => '',
    'bodyClass' => '',
])

@php
    $classes = 'sb-card';
    if ($hover) $classes .= ' sb-stat-card';
    if ($class) $classes .= ' ' . $class;
@endphp

<div class="{{ $classes }}" {{ $attributes->except('class') }}>
    @if($title || $href || isset($header))
        <div class="sb-card-header {{ $headerClass }}">
            <div>
                @if($title)
                    <h5 style="margin:0;font-size:var(--font-size-base);font-weight:var(--font-weight-semibold);color:var(--text-heading);">{{ $title }}</h5>
                @endif
                @if($subtitle)
                    <p style="margin:var(--space-1) 0 0;font-size:var(--font-size-xs);color:var(--text-muted);">{{ $subtitle }}</p>
                @endif
            </div>
            @if(isset($headerActions))
                {{ $headerActions }}
            @elseif($href)
                <a href="{{ $href }}" class="sb-dropdown-item" style="font-size:var(--font-size-sm);color:var(--primary);text-decoration:none;white-space:nowrap;">{{ $hrefLabel }} &rarr;</a>
            @endif
        </div>
    @endif
    <div class="sb-card-body {{ $noPadding || $flush ? 'p-0' : '' }} {{ $bodyClass }}">
        {{ $slot }}
    </div>
    @if(isset($footer))
        <div class="sb-card-footer">
            {{ $footer }}
        </div>
    @endif
</div>
