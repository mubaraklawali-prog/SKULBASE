@extends('layouts.app')

@section('title', 'Dashboard - Skulbase')

@section('content')
    <div class="welcome-section">
        <h2>Welcome back, {{ Auth::user()->name ?? 'User' }}!</h2>
        <p>Here is what is happening at Skulbase today.</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #e8f0fe; color: #1a73e8;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-number">1,245</p>
                        <p class="stat-label">Total Students</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #e6f9ed; color: #1e8a3e;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            <line x1="8" y1="7" x2="16" y2="7"></line>
                            <line x1="8" y1="11" x2="14" y2="11"></line>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-number">84</p>
                        <p class="stat-label">Total Teachers</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #fef3e2; color: #e67e22;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-number">36</p>
                        <p class="stat-label">Total Classes</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #fce8e6; color: #d93025;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-number">KES 1.2M</p>
                        <p class="stat-label">Total Revenue</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card action-card">
                <div class="card-header">Recent Activities</div>
                <div class="card-body">
                    <div class="activity-item">
                        <div class="activity-dot" style="background: #1a73e8;"></div>
                        <div>
                            <p class="activity-text">New student <strong>John Kamau</strong> enrolled in Grade 8A</p>
                            <span class="activity-time">2 hours ago</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-dot" style="background: #1e8a3e;"></div>
                        <div>
                            <p class="activity-text">Teacher <strong>Mary Wanjiku</strong> marked attendance for Class 7B</p>
                            <span class="activity-time">4 hours ago</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-dot" style="background: #e67e22;"></div>
                        <div>
                            <p class="activity-text">Fee payment of <strong>KES 15,000</strong> received from Peter Otieno</p>
                            <span class="activity-time">Yesterday at 3:45 PM</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-dot" style="background: #d93025;"></div>
                        <div>
                            <p class="activity-text">End-of-term exam results published for Grade 6</p>
                            <span class="activity-time">Yesterday at 11:20 AM</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-dot" style="background: #1a73e8;"></div>
                        <div>
                            <p class="activity-text">New parent-teacher conference scheduled for next week</p>
                            <span class="activity-time">2 days ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card action-card">
                <div class="card-header">Quick Actions</div>
                <div class="card-body">
                    <a href="#" class="action-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Add New Student
                    </a>
                    <a href="#" class="action-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                            <path d="M8 14h.01"></path>
                            <path d="M12 14h.01"></path>
                            <path d="M16 14h.01"></path>
                            <path d="M8 18h.01"></path>
                            <path d="M12 18h.01"></path>
                            <path d="M16 18h.01"></path>
                        </svg>
                        Record Attendance
                    </a>
                    <a href="#" class="action-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        Process Fee Payment
                    </a>
                    <a href="#" class="action-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                        </svg>
                        Publish Results
                    </a>
                    <a href="#" class="action-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                        </svg>
                        Generate Report
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
