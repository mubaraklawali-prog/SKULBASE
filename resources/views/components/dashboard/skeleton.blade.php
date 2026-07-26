@props([
    'type' => 'card',
    'lines' => 3,
])

@php
    $widths = [100, 85, 72, 90, 68, 95, 78, 82];
    $heights = [48, 64, 56, 72, 44, 60];
@endphp

@if($type === 'card')
    <div class="ds-skeleton-card" role="presentation" aria-hidden="true">
        <div class="ds-skeleton ds-skeleton-circle"></div>
        <div class="ds-skeleton ds-skeleton-heading"></div>
        @for($i = 0; $i < $lines; $i++)
            <div class="ds-skeleton ds-skeleton-text" style="width: {{ $widths[$i % count($widths)] }}%;"></div>
        @endfor
    </div>
@elseif($type === 'chart')
    <div class="ds-skeleton-chart-wrap" role="presentation" aria-hidden="true">
        <div class="ds-skeleton ds-skeleton-heading" style="width: 40%;"></div>
        <div class="ds-skeleton ds-skeleton-chart"></div>
    </div>
@elseif($type === 'row')
    <div class="ds-skeleton-row" role="presentation" aria-hidden="true">
        @for($i = 0; $i < $lines; $i++)
            <div class="ds-skeleton ds-skeleton-bar" style="height: {{ $heights[$i % count($heights)] }}px;"></div>
        @endfor
    </div>
@else
    <div class="ds-skeleton-list" role="presentation" aria-hidden="true">
        @for($i = 0; $i < $lines; $i++)
            <div class="ds-skeleton-list-item">
                <div class="ds-skeleton ds-skeleton-circle-sm"></div>
                <div class="ds-skeleton-list-text">
                    <div class="ds-skeleton ds-skeleton-text" style="width: {{ $widths[$i % count($widths)] }}%;"></div>
                    <div class="ds-skeleton ds-skeleton-text ds-skeleton-text-sm" style="width: {{ $widths[($i + 3) % count($widths)] }}%;"></div>
                </div>
            </div>
        @endfor
    </div>
@endif
