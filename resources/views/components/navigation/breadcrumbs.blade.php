@props([
    'items' => [],
    'class' => '',
])

@if(!empty($items))
    <nav class="sb-breadcrumb {{ $class }}" aria-label="Breadcrumb" {{ $attributes->except('class') }}>
        <ol style="display:flex;align-items:center;gap:var(--space-2);list-style:none;margin:0;padding:0;font-size:var(--font-size-sm);">
            <li class="sb-breadcrumb-item" style="display:flex;align-items:center;gap:var(--space-2);">
                <a href="{{ url('/dashboard') }}" style="color:var(--text-muted);text-decoration:none;display:flex;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                </a>
            </li>
            @foreach($items as $item)
                <li class="sb-breadcrumb-separator" aria-hidden="true" style="color:var(--text-disabled);">/</li>
                <li class="sb-breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                    @if(is_array($item))
                        @if(!$loop->last)
                            <a href="{{ $item['url'] ?? '#' }}" style="color:var(--text-link);text-decoration:none;">{{ $item['label'] ?? '' }}</a>
                        @else
                            <span style="color:var(--text-heading);font-weight:var(--font-weight-medium);">{{ $item['label'] ?? '' }}</span>
                        @endif
                    @else
                        @if(!$loop->last)
                            <span style="color:var(--text-muted);">{{ $item }}</span>
                        @else
                            <span style="color:var(--text-heading);font-weight:var(--font-weight-medium);">{{ $item }}</span>
                        @endif
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
