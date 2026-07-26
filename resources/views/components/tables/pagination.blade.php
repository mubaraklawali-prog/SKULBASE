@props([
    'items' => null,
    'class' => '',
])

@if($items && $items->hasPages())
    <div class="d-flex justify-content-center {{ $class }}" {{ $attributes->except('class') }}>
        {{ $items->links() }}
    </div>
@endif
