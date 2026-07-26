@extends('errors.layout')

@section('title', 'Access Denied')

@section('content')
<div class="error-icon icon-danger">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
    </svg>
</div>
<div class="error-code">403</div>
<h2>Access Denied</h2>
<p>You don't have permission to access this page. If you believe this is a mistake, please contact your school administrator.</p>
<div class="error-actions">
    <a href="{{ url()->previous() }}" class="sb-btn sb-btn-ghost">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        Go Back
    </a>
    @auth
        <a href="{{ route('dashboard') }}" class="sb-btn sb-btn-primary">Dashboard</a>
    @else
        <a href="{{ route('login') }}" class="sb-btn sb-btn-primary">Sign In</a>
    @endauth
</div>
@endsection
