@extends('errors.layout')

@section('title', 'Too Many Requests')

@section('content')
<div class="error-icon icon-warning">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
        <line x1="12" y1="9" x2="12" y2="13"></line>
        <line x1="12" y1="17" x2="12.01" y2="17"></line>
    </svg>
</div>
<div class="error-code">429</div>
<h2>Too Many Requests</h2>
<p>You've made too many requests in a short period. Please wait a moment and try again.</p>
<div class="error-actions">
    <a href="{{ url()->previous() }}" class="sb-btn sb-btn-ghost">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        Go Back
    </a>
    <a href="{{ route('login') }}" class="sb-btn sb-btn-primary">Sign In</a>
</div>
@endsection
