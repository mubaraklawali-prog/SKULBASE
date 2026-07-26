@extends('errors.layout')

@section('title', 'Server Error')

@section('content')
<div class="error-icon icon-danger">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="12" y1="8" x2="12" y2="12"></line>
        <line x1="12" y1="16" x2="12.01" y2="16"></line>
    </svg>
</div>
<div class="error-code">500</div>
<h2>Something Went Wrong</h2>
<p>An unexpected error occurred on our end. Our team has been notified. Please try again in a few moments.</p>
<div class="error-actions">
    <a href="{{ url()->previous() }}" class="sb-btn sb-btn-ghost">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        Go Back
    </a>
    <a href="{{ route('login') }}" class="sb-btn sb-btn-primary">Sign In</a>
</div>
@endsection
