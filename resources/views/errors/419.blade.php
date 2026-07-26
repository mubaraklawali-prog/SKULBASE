@extends('errors.layout')

@section('title', 'Session Expired')

@section('content')
<div class="error-icon icon-warning">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"></circle>
        <polyline points="12 6 12 12 16 14"></polyline>
    </svg>
</div>
<div class="error-code">419</div>
<h2>Session Expired</h2>
<p>Your session has expired due to inactivity or a security token mismatch. Please sign in again to continue.</p>
<div class="error-actions">
    <a href="{{ route('login') }}" class="sb-btn sb-btn-primary">Sign In Again</a>
</div>
@endsection
