@props([
    'name' => '',
    'label' => '',
    'value' => null,
    'placeholder' => '',
    'rows' => 4,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => '',
    'class' => '',
    'wrapperClass' => '',
    'id' => null,
])

@php
    $inputId = $id ?: ($name ? "textarea_{$name}" : null);
    $hasError = $error || ($name && $errors && $errors->has($name));
    $errorMsg = $error ?: ($name && $errors ? $errors->first($name) : '');
    $inputClass = $hasError ? 'sb-textarea sb-input-error' : 'sb-textarea';
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

    <textarea
        name="{{ $name }}"
        id="{{ $inputId }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        class="{{ $inputClass }}"
        {{ $attributes->except(['class', 'name', 'value', 'placeholder', 'rows', 'required', 'disabled', 'id', 'wrapperClass']) }}
    >{{ $inputValue }}</textarea>

    @if($hasError && $errorMsg)
        <div class="sb-error-text">{{ $errorMsg }}</div>
    @elseif($help)
        <div class="sb-help-text">{{ $help }}</div>
    @endif
</div>
