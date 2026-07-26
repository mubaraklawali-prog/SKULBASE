@props([
    'title' => '',
    'subtitle' => '',
    'icon' => null,
    'backUrl' => null,
    'backLabel' => 'Back',
    'class' => '',
])

<div class="sb-section-header {{ $class }}" style="margin-bottom:var(--space-6);" {{ $attributes->except('class') }}>
    <div>
        @if($backUrl)
            <a href="{{ $backUrl }}" class="sb-page-header-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
                {{ $backLabel }}
            </a>
        @endif
        <div style="display:flex;align-items:center;gap:var(--space-3);">
            @if($icon)
                <div style="flex-shrink:0;display:flex;">{!! $icon !!}</div>
            @endif
            <div>
                <h2 style="font-size:var(--font-size-2xl);font-weight:var(--font-weight-bold);color:var(--text-heading);margin:0;">{{ $title }}</h2>
                @if($subtitle)
                    <p style="font-size:var(--font-size-sm);color:var(--text-muted);margin:var(--space-1) 0 0;">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
    </div>
    @if(isset($actions))
        <div style="display:flex;align-items:center;gap:var(--space-3);flex-shrink:0;">
            {{ $actions }}
        </div>
    @endif
</div>
