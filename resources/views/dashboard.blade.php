@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="welcome-section">
        <h2>Dashboard</h2>
        <p>School management overview for Skulbase.</p>
    </div>

    <div class="row g-4">

        <div class="col-lg-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <p class="stat-label">Total Students</p>
                    <h2 class="stat-number">1,248</h2>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <p class="stat-label">Teachers</p>
                    <h2 class="stat-number">72</h2>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <p class="stat-label">Classes</p>
                    <h2 class="stat-number">18</h2>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <p class="stat-label">Revenue</p>
                    <h2 class="stat-number">₦0</h2>
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-4">

        <div class="col-lg-8">
            <div class="card action-card">
                <div class="card-header">
                    Recent Activities
                </div>

                <div class="card-body">

                    <div class="activity-item">
                        <div class="activity-dot bg-success"></div>
                        <div>
                            <p class="activity-text">Welcome to Skulbase.</p>
                            <small class="activity-time">Just now</small>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-dot bg-primary"></div>
                        <div>
                            <p class="activity-text">Student module coming soon.</p>
                            <small class="activity-time">Today</small>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card action-card">
                <div class="card-header">
                    Quick Actions
                </div>

                <div class="card-body d-grid gap-2">
                    <button class="btn btn-primary">Add Student</button>
                    <button class="btn btn-success">Add Teacher</button>
                    <button class="btn btn-warning">Create Class</button>
                </div>
            </div>
        </div>

    </div>

@endsection
