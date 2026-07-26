@props([
    'title' => '',
    'subtitle' => '',
    'id' => '',
    'height' => '280px',
    'loading' => false,
    'actions' => null,
])

<div class="ds-chart-card">
    <div class="ds-chart-card-header">
        <div>
            <h5 class="ds-chart-card-title">{{ $title }}</h5>
            @if($subtitle)
                <p class="ds-chart-card-subtitle">{{ $subtitle }}</p>
            @endif
        </div>
        @if($actions)
            <div class="ds-chart-card-actions">{{ $actions }}</div>
        @endif
    </div>
    <div class="ds-chart-card-body" style="height: {{ $height }};">
        @if($loading)
            <div class="ds-chart-skeleton">
                <div class="ds-skeleton ds-skeleton-chart"></div>
            </div>
        @else
            <canvas id="{{ $id }}"></canvas>
        @endif
    </div>
    {{ $slot }}
</div>
