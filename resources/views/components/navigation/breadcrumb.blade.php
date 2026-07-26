@props([
    'items' => [],
    'class' => '',
])

@if(count($items) > 0)
    <nav class="sb-breadcrumb {{ $class }}" aria-label="Breadcrumb" {{ $attributes->except('class') }}>
        <ol style="display:flex;align-items:center;gap:var(--space-2);list-style:none;margin:0;padding:0;">
            @foreach($items as $index => $item)
                @if($index > 0)
                    <li class="sb-breadcrumb-separator" aria-hidden="true">/</li>
                @endif
                <li class="sb-breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                    @if(is_array($item))
                        @if(!$loop->last)
                            <a href="{{ $item['url'] ?? '#' }}">{{ $item['label'] ?? '' }}</a>
                        @else
                            <span>{{ $item['label'] ?? '' }}</span>
                        @endif
                    @else
                        <span>{{ $item }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
