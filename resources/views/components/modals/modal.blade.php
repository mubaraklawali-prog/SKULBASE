@props([
    'title' => '',
    'size' => 'md',
    'closeable' => true,
    'id' => null,
    'class' => '',
])

@php
    $modalId = $id ?: 'modal_' . uniqid();
    $sizeMap = [
        'sm' => 'max-width: 420px;',
        'md' => 'max-width: 520px;',
        'lg' => 'max-width: 720px;',
        'xl' => 'max-width: 960px;',
    ];
    $sizeStyle = $sizeMap[$size] ?? $sizeMap['md'];
@endphp

<div class="sb-modal-backdrop" id="{{ $modalId }}" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);backdrop-filter:blur(4px);z-index:var(--z-modal-backdrop);align-items:center;justify-content:center;padding:var(--space-6);" {{ $attributes->except('class') }}>
    <div class="sb-modal-dialog {{ $class }}" style="{$sizeStyle}max-height:85vh;background:var(--card);border-radius:var(--radius-xl);box-shadow:var(--shadow-modal);display:flex;flex-direction:column;overflow:hidden;width:100%;">
        @if($title || $closeable || isset($header))
            <div class="sb-modal-header" style="display:flex;align-items:center;justify-content:space-between;padding:var(--space-5) var(--space-6);border-bottom:1px solid var(--border);">
                <div>
                    <h3 style="margin:0;font-size:var(--font-size-lg);font-weight:var(--font-weight-semibold);color:var(--text-heading);">{{ $title }}</h3>
                    @if(isset($subtitle))
                        <p style="margin:var(--space-1) 0 0;font-size:var(--font-size-sm);color:var(--text-muted);">{{ $subtitle }}</p>
                    @endif
                </div>
                @if(isset($header))
                    {{ $header }}
                @endif
                @if($closeable)
                    <button type="button" class="sb-modal-close" aria-label="Close" data-dismiss-modal="{{ $modalId }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                @endif
            </div>
        @endif
        <div class="sb-modal-body" style="padding:var(--space-6);overflow-y:auto;flex:1;">
            {{ $slot }}
        </div>
        @if(isset($footer))
            <div class="sb-modal-footer" style="display:flex;align-items:center;justify-content:flex-end;gap:var(--space-3);padding:var(--space-4) var(--space-6);border-top:1px solid var(--border);background:var(--gray-50);border-radius:0 0 var(--radius-xl) var(--radius-xl);">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
