@props([
    'name' => '',
    'label' => '',
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => '',
    'icon' => null,
    'class' => '',
    'wrapperClass' => '',
    'id' => null,
])

@php
    $inputId = $id ?: ($name ? "input_{$name}" : null);
    $hasError = $error || ($name && $errors && $errors->has($name));
    $errorMsg = $error ?: ($name && $errors ? $errors->first($name) : '');
    $inputClass = $hasError ? 'sb-input sb-input-error' : 'sb-input';
    if ($class) $inputClass .= ' ' . $class;
    $inputValue = $value ?? ($name ? old($name) : '');
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <label class="sb-label" @if($inputId) for="{{ $inputId }}" @endif>
            {{ $label }}
            @if($required)
                <span class="sb-label-required"></span>
            @endif
        </label>
    @endif

    <div style="position:relative;">
        @if($icon)
            <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);pointer-events:none;display:flex;">{!! $icon !!}</span>
        @endif
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $inputId }}"
            value="{{ $inputValue }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="{{ $inputClass }}"
            @if($icon) style="padding-left:40px;" @endif
            {{ $attributes->except(['class', 'name', 'type', 'value', 'placeholder', 'required', 'disabled', 'id', 'wrapperClass']) }}
        >
    </div>

    @if($hasError && $errorMsg)
        <div class="sb-error-text">{{ $errorMsg }}</div>
    @elseif($help)
        <div class="sb-help-text">{{ $help }}</div>
    @endif
</div>
