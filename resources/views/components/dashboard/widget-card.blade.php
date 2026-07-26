@props([
    'title' => '',
    'subtitle' => '',
    'href' => null,
    'hrefLabel' => 'View All',
    'noPadding' => false,
    'class' => '',
])

<div class="ds-widget-card {{ $class }}">
    <div class="ds-widget-card-header">
        <div>
            <h5 class="ds-widget-card-title">{{ $title }}</h5>
            @if($subtitle)
                <p class="ds-widget-card-subtitle">{{ $subtitle }}</p>
            @endif
        </div>
        @if($href)
            <a href="{{ $href }}" class="ds-widget-card-link">{{ $hrefLabel }} &rarr;</a>
        @endif
    </div>
    <div class="ds-widget-card-body {{ $noPadding ? 'ds-widget-card-body--flush' : '' }}">
        {{ $slot }}
    </div>
</div>
