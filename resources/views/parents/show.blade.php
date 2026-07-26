@extends('layouts.app')

@section('title', $parent->full_name . ' - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ $parent->full_name }}</h2>
            <p class="text-muted mb-0">Parent profile and linked children</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('parents.edit', $parent) }}" class="sb-btn sb-btn-outline-primary">
                Edit Parent
            </a>
            <a href="{{ route('parents.index') }}" class="sb-btn sb-btn-secondary">
                Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px; text-align: center;">
                    <div style="width: 96px; height: 96px; border-radius: 50%; overflow: hidden; margin: 0 auto 16px; background: #e7f1ff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 28px; color: #0d6efd;">
                        {{ substr($parent->first_name, 0, 1) }}{{ substr($parent->last_name, 0, 1) }}
                    </div>
                    <h5 style="font-weight: 600; margin-bottom: 4px;">{{ $parent->full_name }}</h5>
                    <p style="color: #6c757d; font-size: 14px; margin-bottom: 12px;">{{ $parent->school->name ?? '—' }}</p>
                    <span class="sb-badge {{ $parent->status ? 'sb-badge-active' : 'sb-badge-inactive' }}">
                        {{ $parent->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <h5 style="font-weight: 600; margin-bottom: 20px; color: #1a1a2e;">Contact Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label">Email</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ $parent->email ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sb-form-label">Phone</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ $parent->phone ?? '—' }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="sb-form-label">Address</label>
                            <p style="margin: 0; font-size: 15px; color: #333;">{{ $parent->address ?? '—' }}</p>
                        </div>
                    </div>

                    <div style="border-top: 1px solid #e9ecef; padding-top: 16px; margin-top: 8px;">
                        <h6 style="font-weight: 600; color: #1a1a2e; margin-bottom: 12px;">Account Status</h6>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <span class="sb-badge {{ $parent->user ? 'sb-badge-active' : 'sb-badge-inactive' }}">
                                Login Account: {{ $parent->user ? 'Active' : 'None' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body" style="padding: 24px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 style="font-weight: 600; margin-bottom: 0; color: #1a1a2e;">Linked Children</h5>
                <span class="sb-badge sb-badge-info">
                    {{ $parent->children->count() }} {{ Str::plural('child', $parent->children->count()) }}
                </span>
            </div>

            @if($parent->children->count())
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    @foreach($parent->children as $child)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;">
                            <div>
                                <span style="font-weight: 500; font-size: 14px; color: #333;">{{ $child->full_name }}</span>
                                <span style="color: #6c757d; font-size: 13px;"> | {{ $child->admission_number }}</span>
                                @if($child->schoolClass)
                                    <span style="color: #6c757d; font-size: 13px;"> | {{ $child->schoolClass->name }}</span>
                                @endif
                            </div>
                            <span class="sb-badge {{ $child->status === 'active' ? 'sb-badge-active' : 'sb-badge-inactive' }}">
                                {{ ucfirst($child->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 32px 16px; color: #6c757d;">
                    <p style="margin: 0; font-size: 14px;">No children linked yet.</p>
                    <a href="{{ route('parents.edit', $parent) }}" class="sb-btn sb-btn-sm sb-btn-outline-primary mt-2">Link children</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
