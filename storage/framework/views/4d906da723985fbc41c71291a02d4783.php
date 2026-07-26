

<?php
    $userRole = auth()->user()->role ?? '';
    $hasSchool = (bool) auth()->user()->school_id;
    $isSchoolAdmin = $userRole === 'school_admin';
    $isSuperAdmin = $userRole === 'super_admin';
    $sidebarSchool = Auth::user()->school ?? null;
?>

<aside class="sb-sidebar" id="sidebar" aria-hidden="true" aria-label="Sidebar">
    
    <div class="sb-sidebar-brand">
        <?php if($sidebarSchool && $sidebarSchool->logo): ?>
            <div class="sb-sidebar-brand-school">
                <img src="<?php echo e(Storage::disk('public')->url($sidebarSchool->logo)); ?>" alt="<?php echo e($sidebarSchool->name); ?>" class="sb-sidebar-logo">
                <div class="sb-sidebar-brand-text">
                    <span class="sb-sidebar-school-name"><?php echo e($sidebarSchool->name); ?></span>
                    <?php if($sidebarSchool->motto): ?>
                        <span class="sb-sidebar-school-motto"><?php echo e($sidebarSchool->motto); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php elseif($sidebarSchool): ?>
            <div class="sb-sidebar-brand-school">
                <div class="sb-sidebar-logo-placeholder"><?php echo e(substr($sidebarSchool->name, 0, 2)); ?></div>
                <div class="sb-sidebar-brand-text">
                    <span class="sb-sidebar-school-name"><?php echo e($sidebarSchool->name); ?></span>
                    <?php if($sidebarSchool->motto): ?>
                        <span class="sb-sidebar-school-motto"><?php echo e($sidebarSchool->motto); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="sb-sidebar-brand-default">
                <span class="sb-brand-skul">Skul</span><span class="sb-brand-base">Base</span>
            </div>
        <?php endif; ?>
    </div>

    
    <nav class="sb-sidebar-nav" aria-label="Main navigation">
        <ul class="sb-sidebar-menu">

            
            <li class="sb-sidebar-item">
                <a class="sb-sidebar-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect></svg>
                    <span>Dashboard</span>
                </a>
            </li>

            
            <?php if($isSuperAdmin): ?>
                <li class="sb-sidebar-section">System</li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('schools.*') ? 'active' : ''); ?>" href="<?php echo e(route('schools.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                        <span>Schools</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('pending-schools.*') ? 'active' : ''); ?>" href="<?php echo e(route('pending-schools.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <span>Pending Schools</span>
                    </a>
                </li>
            <?php endif; ?>

            
            <?php if($isSchoolAdmin || $isSuperAdmin): ?>
                <li class="sb-sidebar-section">Management</li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('students.*') ? 'active' : ''); ?>" href="<?php echo e(route('students.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Students</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('teachers.*') ? 'active' : ''); ?>" href="<?php echo e(route('teachers.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path><line x1="8" y1="7" x2="16" y2="7"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
                        <span>Teachers</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('parents.*') ? 'active' : ''); ?>" href="<?php echo e(route('parents.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Parents</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('classes.*') ? 'active' : ''); ?>" href="<?php echo e(route('classes.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                        <span>Classes</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('subjects.*') ? 'active' : ''); ?>" href="<?php echo e(route('subjects.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path><line x1="8" y1="7" x2="16" y2="7"></line><line x1="8" y1="11" x2="16" y2="11"></line><line x1="8" y1="15" x2="12" y2="15"></line></svg>
                        <span>Subjects</span>
                    </a>
                </li>
            <?php endif; ?>

            
            <?php if($isSchoolAdmin || $isSuperAdmin): ?>
                <li class="sb-sidebar-section">Academics</li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('assignments.*') ? 'active' : ''); ?>" href="<?php echo e(route('assignments.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <span>Assignments</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('attendance.*') ? 'active' : ''); ?>" href="<?php echo e(route('attendance.dashboard')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><path d="M8 14h.01"></path><path d="M12 14h.01"></path><path d="M16 14h.01"></path><path d="M8 18h.01"></path><path d="M12 18h.01"></path><path d="M16 18h.01"></path></svg>
                        <span>Attendance</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('fees.*') ? 'active' : ''); ?>" href="<?php echo e(route('fees.dashboard')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        <span>Fees</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('results.*') ? 'active' : ''); ?>" href="<?php echo e(route('results.dashboard')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        <span>Results</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('results.scores.*') || request()->routeIs('results.reports.*') ? 'active' : ''); ?>" href="<?php echo e(route('results.scores.dashboard')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        <span>Scores</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('results.report-cards.*') ? 'active' : ''); ?>" href="<?php echo e(route('results.report-cards.bulk')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"></path><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                        <span>Report Cards</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('results.approvals.*') ? 'active' : ''); ?>" href="<?php echo e(route('results.approvals.dashboard')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                        <span>Approvals</span>
                    </a>
                </li>
            <?php endif; ?>

            
            <?php if($userRole === 'teacher'): ?>
                <?php
                    $currentTeacher = auth()->user()->teacher;
                ?>
                <li class="sb-sidebar-section">Teaching</li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('teacher.profile') ? 'active' : ''); ?>" href="<?php echo e(route('teacher.profile')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span>My Profile</span>
                    </a>
                </li>
                <?php if($currentTeacher && $currentTeacher->can_mark_attendance): ?>
                    <li class="sb-sidebar-item">
                        <a class="sb-sidebar-link <?php echo e(request()->routeIs('teacher.attendance.*') ? 'active' : ''); ?>" href="<?php echo e(route('teacher.attendance.index')); ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <span>Attendance</span>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('teacher.scores.*') ? 'active' : ''); ?>" href="<?php echo e(route('teacher.scores.create')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        <span>Score Entry</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('assignments.*') ? 'active' : ''); ?>" href="<?php echo e(route('assignments.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        <span>Assignments</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('teacher.timetable.*') ? 'active' : ''); ?>" href="<?php echo e(route('teacher.timetable.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <span>Timetable</span>
                    </a>
                </li>
            <?php endif; ?>

            
            <?php if($userRole === 'student'): ?>
                <li class="sb-sidebar-section">My Studies</li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('student.attendance.*') ? 'active' : ''); ?>" href="<?php echo e(route('student.attendance.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <span>Attendance</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('student.results.*') ? 'active' : ''); ?>" href="<?php echo e(route('student.results.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        <span>Results</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('student.assignments.*') ? 'active' : ''); ?>" href="<?php echo e(route('student.assignments.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        <span>Assignments</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('student.fees.*') ? 'active' : ''); ?>" href="<?php echo e(route('student.fees.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        <span>Fees</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('student.timetable.*') ? 'active' : ''); ?>" href="<?php echo e(route('student.timetable.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <span>Timetable</span>
                    </a>
                </li>
            <?php endif; ?>

            
            <?php if($userRole === 'parent'): ?>
                <?php
                    $isParentChild = request()->routeIs('parent.*');
                ?>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('parent.profile') ? 'active' : ''); ?>" href="<?php echo e(route('parent.profile')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span>My Profile</span>
                    </a>
                </li>

                <li class="sb-sidebar-section">Children</li>
                <li class="sb-sidebar-item sb-sidebar-parent <?php echo e($isParentChild ? 'open' : ''); ?>">
                    <button type="button" class="sb-sidebar-link sb-sidebar-toggle" aria-expanded="<?php echo e($isParentChild ? 'true' : 'false'); ?>" aria-controls="sidebar-children">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span>Children</span>
                        <svg class="sb-sidebar-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                    <ul class="sb-sidebar-submenu" id="sidebar-children" role="menu">
                        <li class="sb-sidebar-item">
                            <a class="sb-sidebar-link <?php echo e(request()->routeIs('parent.attendance.*') ? 'active' : ''); ?>" href="<?php echo e(route('parent.attendance.index')); ?>">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                <span>Attendance</span>
                            </a>
                        </li>
                        <li class="sb-sidebar-item">
                            <a class="sb-sidebar-link <?php echo e(request()->routeIs('parent.fees.*') ? 'active' : ''); ?>" href="<?php echo e(route('parent.fees.index')); ?>">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                <span>Fees</span>
                            </a>
                        </li>
                        <li class="sb-sidebar-item">
                            <a class="sb-sidebar-link <?php echo e(request()->routeIs('parent.results.*') ? 'active' : ''); ?>" href="<?php echo e(route('parent.results.index')); ?>">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                <span>Results</span>
                            </a>
                        </li>
                        <li class="sb-sidebar-item">
                            <a class="sb-sidebar-link <?php echo e(request()->routeIs('parent.assignments.*') ? 'active' : ''); ?>" href="<?php echo e(route('parent.assignments.index')); ?>">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                                <span>Assignments</span>
                            </a>
                        </li>
                        <li class="sb-sidebar-item">
                            <a class="sb-sidebar-link <?php echo e(request()->routeIs('parent.timetable.*') ? 'active' : ''); ?>" href="<?php echo e(route('parent.timetable.index')); ?>">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect></svg>
                                <span>Timetable</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sb-sidebar-section">Account</li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('password.change') ? 'active' : ''); ?>" href="<?php echo e(route('password.change')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <span>Change Password</span>
                    </a>
                </li>
            <?php endif; ?>

            
            <?php if($isSchoolAdmin || $isSuperAdmin): ?>
                <li class="sb-sidebar-section">Communication</li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('announcements.*') ? 'active' : ''); ?>" href="<?php echo e(route('announcements.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        <span>Announcements</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('messages.*') ? 'active' : ''); ?>" href="<?php echo e(route('messages.inbox')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <span>Messages</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('events.*') ? 'active' : ''); ?>" href="<?php echo e(route('events.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <span>Calendar</span>
                    </a>
                </li>
            <?php endif; ?>

            
            <?php if($isSchoolAdmin || $isSuperAdmin): ?>
                <li class="sb-sidebar-section">Operations</li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('admissions.*') ? 'active' : ''); ?>" href="<?php echo e(route('admissions.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                        <span>Admissions</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>" href="<?php echo e(route('reports.dashboard')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        <span>Reports</span>
                    </a>
                </li>
            <?php endif; ?>

            
            <?php if($isSuperAdmin): ?>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('plans.*') ? 'active' : ''); ?>" href="<?php echo e(route('plans.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        <span>Plans</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('subscriptions.*') ? 'active' : ''); ?>" href="<?php echo e(route('subscriptions.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 4H3a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h18a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                        <span>Subscriptions</span>
                    </a>
                </li>
            <?php endif; ?>

            
            <?php if($isSchoolAdmin): ?>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('school.subscription.*') ? 'active' : ''); ?>" href="<?php echo e(route('school.subscription.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 4H3a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h18a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                        <span>My Subscription</span>
                    </a>
                </li>
            <?php endif; ?>

            
            <?php if(!in_array($userRole, ['super_admin', 'school_admin'])): ?>
                <li class="sb-sidebar-section">Communication</li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('announcements.*') ? 'active' : ''); ?>" href="<?php echo e(route('announcements.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        <span>Announcements</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('messages.*') ? 'active' : ''); ?>" href="<?php echo e(route('messages.inbox')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <span>Messages</span>
                    </a>
                </li>
                <li class="sb-sidebar-item">
                    <a class="sb-sidebar-link <?php echo e(request()->routeIs('events.*') ? 'active' : ''); ?>" href="<?php echo e(route('events.index')); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <span>Calendar</span>
                    </a>
                </li>
            <?php endif; ?>

        </ul>
    </nav>

    
    <div class="sb-sidebar-footer">
        <span class="sb-sidebar-version">v1.0</span>
    </div>
</aside>
<?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/layouts/partials/_sidebar.blade.php ENDPATH**/ ?>