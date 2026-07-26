@props([
    'name' => '',
    'label' => '',
    'value' => null,
    'options' => [],
    'placeholder' => 'Select...',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => '',
    'class' => '',
    'wrapperClass' => '',
    'id' => null,
])

@php
    $inputId = $id ?: ($name ? "select_{$name}" : null);
    $hasError = $error || ($name && $errors && $errors->has($name));
    $errorMsg = $error ?: ($name && $errors ? $errors->first($name) : '');
    $inputClass = $hasError ? 'sb-select sb-input-error' : 'sb-select';
    if ($class) $inputClass .= ' ' . $class;
    $selectedValue = $value ?? ($name ? old($name) : '');
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

    <select
        name="{{ $name }}"
        id="{{ $inputId }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        class="{{ $inputClass }}"
        {{ $attributes->except(['class', 'name', 'value', 'required', 'disabled', 'id', 'options', 'wrapperClass']) }}
    >
        @if($placeholder)
            <option value="" disabled {{ $selectedValue === '' || $selectedValue === null ? 'selected' : '' }}>{{ $placeholder }}</option>
        @endif
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" {{ $selectedValue == $optionValue ? 'selected' : '' }}>{{ $optionLabel }}</option>
        @endforeach
    </select>

    @if($hasError && $errorMsg)
        <div class="sb-error-text">{{ $errorMsg }}</div>
    @elseif($help)
        <div class="sb-help-text">{{ $help }}</div>
    @endif
</div>
