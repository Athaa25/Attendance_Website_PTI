<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('images/RMDOO_logo.png') }}" alt="RMDOO Logo" class="sidebar-logo-img">
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-nav-group">
            <p class="sidebar-section-title">Menu</p>
            <a class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/dashboard-icon.png') }}" alt="Dashboard Icon">
                Dashboard
            </a>
        </div>

        <div class="sidebar-nav-group">
            <p class="sidebar-section-title">Users Management</p>
            <a class="sidebar-nav-item {{ request()->routeIs('manage-users.*') ? 'active' : '' }}" href="{{ route('manage-users.index') }}">
                <img src="{{ asset('images/manage-user-icon.png') }}" alt="Manage User Icon">
                Manage User
            </a>
            <a class="sidebar-nav-item {{ request()->routeIs('departments.*') ? 'active' : '' }}" href="{{ route('departments.index') }}">
                <img src="{{ asset('images/user-setting-icon.png') }}" alt="Department Icon">
                Departemen
            </a>
        </div>

        <div class="sidebar-nav-group">
            <p class="sidebar-section-title">Attendance</p>
            <a class="sidebar-nav-item {{ request()->routeIs('schedule.*') ? 'active' : '' }}" href="{{ route('schedule.index') }}">
                <img src="{{ asset('images/schedule-icon.png') }}" alt="Schedule Icon">
                Schedule
            </a>
            <a class="sidebar-nav-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}" href="{{ route('attendance.index') }}">
                <img src="{{ asset('images/daily-attendance-icon.png') }}" alt="Daily Attendance Icon">
                Daily Attendance
            </a>
            <a class="sidebar-nav-item {{ request()->routeIs('face.enroll*') ? 'active' : '' }}" href="{{ route('face.enroll') }}">
                <img src="{{ asset('images/manage-user-icon.png') }}" alt="Face Enrollment Icon">
                Face Enrollment
            </a>
            <a class="sidebar-nav-item {{ request()->routeIs('reports.sheet') ? 'active' : '' }}" href="{{ route('reports.sheet') }}">
                <img src="{{ asset('images/sheet-report-icon.png') }}" alt="Sheet Report Icon">
                Sheet Report
            </a>
        </div>

        <div class="sidebar-nav-group">
            <p class="sidebar-section-title">Audit</p>
            <a class="sidebar-nav-item {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}" href="{{ route('activity-logs.index') }}">
                <span class="sidebar-icon">LG</span>
                Activity Log
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <a href="#" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <img src="{{ asset('images/logout-box-icon.png') }}" alt="Logout Icon">
            Keluar
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</aside>
