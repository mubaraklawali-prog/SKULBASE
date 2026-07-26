@props([
    'id' => null,
    'align' => 'right',
    'width' => '220px',
    'class' => '',
])

@php
    $dropdownId = $id ?: 'dropdown_' . uniqid();
@endphp

<div class="sb-dropdown {{ $class }}" style="position:relative;display:inline-block;" {{ $attributes->except('class') }}>
    {{ $trigger ?? '' }}
    <div class="sb-dropdown-menu" id="{{ $dropdownId }}-menu" style="display:none;position:absolute;top:calc(100% + 4px);{{ $align === 'left' ? 'left:0;' : 'right:0;' }}min-width:{{ $width }};background:var(--card);border:1px solid var(--border);border-radius:var(--radius-lg);box-shadow:var(--shadow-dropdown);z-index:var(--z-dropdown);padding:var(--space-1);animation:sb-fade-in 0.15s ease;">
        {{ $slot }}
    </div>
</div>

<script>
(function() {
    const trigger = document.querySelector('[data-dropdown-toggle="{{ $dropdownId }}"]');
    const menu = document.getElementById('{{ $dropdownId }}-menu');
    if (trigger && menu) {
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            document.querySelectorAll('.sb-dropdown-menu').forEach(function(m) {
                if (m !== menu) m.style.display = 'none';
            });
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        });
    }
})();
</script>
