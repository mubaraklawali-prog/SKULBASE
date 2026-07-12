@extends('layouts.app')

@section('title', $schoolClass->name . ' - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ $schoolClass->name }}{{ $schoolClass->section ? ' - ' . $schoolClass->section : '' }}</h2>
            <p class="text-muted mb-0">{{ $schoolClass->school->name ?? '' }} &middot; {{ $students_count }} {{ Str::plural('student', $students_count) }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('classes.edit', $schoolClass) }}" class="btn" style="background: #e7f1ff; color: #0d6efd; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                Edit Class
            </a>
            <a href="{{ route('classes.index') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">
                Back to List
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Class Name</label>
                    <p style="margin: 0; font-size: 15px; font-weight: 500; color: #333;">{{ $schoolClass->name }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Section</label>
                    <p style="margin: 0; font-size: 15px; color: #333;">{{ $schoolClass->section ?? '—' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card" style="height: 100%;">
                <div class="card-body" style="padding: 24px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Status</label>
                    <p style="margin: 0;">
                        @if($schoolClass->status)
                            <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span>
                        @else
                            <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Inactive</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if($schoolClass->description)
        <div class="card stat-card mb-4">
            <div class="card-body" style="padding: 24px;">
                <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Description</label>
                <p style="margin: 0; font-size: 15px; color: #333;">{{ $schoolClass->description }}</p>
            </div>
        </div>
    @endif

    <div class="card stat-card">
        <div class="card-body p-0">
            <div style="padding: 20px 24px; border-bottom: 1px solid #e9ecef;">
                <h5 style="font-weight: 600; margin: 0; color: #1a1a2e;">Students in this Class</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="margin-bottom: 0;">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Adm. No.</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Name</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Gender</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Email</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td style="padding: 14px 20px;">
                                    <code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;">{{ $student->admission_number }}</code>
                                </td>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $student->full_name }}</td>
                                <td style="padding: 14px 20px; color: #6c757d; text-transform: capitalize;">{{ $student->gender }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $student->email ?? '—' }}</td>
                                <td style="padding: 14px 20px;">
                                    @if($student->status === 'active')
                                        <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span>
                                    @else
                                        <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                    <p style="margin: 0; font-size: 15px;">No students assigned to this class yet.</p>
                                    <a href="{{ route('students.create') }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none;">Add a student</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($students->hasPages())
        <div class="mt-3" style="display: flex; justify-content: center;">
            {{ $students->links() }}
        </div>
    @endif
</div>
@endsection
