@props([
    'actions' => [],
])

@if($actions->isNotEmpty() || (is_array($actions) && count($actions) > 0))
    <div class="ds-quick-actions">
        @if(is_array($actions))
            @foreach($actions as $action)
                <a href="{{ $action['href'] ?? '#' }}" class="ds-quick-action">
                    <div class="ds-quick-action-icon" style="background: {{ $action['bg'] ?? 'rgba(91,33,255,0.08)' }}; color: {{ $action['color'] ?? '#5B21FF' }};">
                        {!! $action['icon'] ?? '' !!}
                    </div>
                    <span class="ds-quick-action-label">{{ $action['label'] ?? '' }}</span>
                </a>
            @endforeach
        @endif
    </div>
@endif
