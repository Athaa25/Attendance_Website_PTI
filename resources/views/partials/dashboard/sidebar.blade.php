<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('images/RMDOO_logo.png') }}" alt="RMDOO Logo" width="120" height="60">
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-nav-group">
            <p class="sidebar-section-title">Menu</p>
            <a class="sidebar-nav-item active" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/grid-icon.png') }}" alt="Dashboard Icon">
                Dashboard
            </a>
            <a class="sidebar-nav-item" href="{{ route('attendance.index') }}">
                <img src="{{ asset('images/attendance-icon.png') }}" alt="Attendance Icon">
                Absensi Harian
            </a>
            <a class="sidebar-nav-item" href="{{ route('schedule.index') }}">
                <img src="{{ asset('images/clock-icon.png') }}" alt="Schedule Icon">
                Jadwal Kerja
            </a>
            <a class="sidebar-nav-item" href="{{ route('reports.sheet') }}">
                <img src="{{ asset('images/document-icon.png') }}" alt="Report Icon">
                Laporan Absensi
            </a>
        </div>

        <div class="sidebar-nav-group">
            <p class="sidebar-section-title">Kelola Data</p>
            <a class="sidebar-nav-item" href="{{ route('manage-users.index') }}">
                <img src="{{ asset('images/users-icon.png') }}" alt="Users Icon">
                Pegawai
            </a>
            <a class="sidebar-nav-item" href="{{ route('departments.index') }}">
                <img src="{{ asset('images/building-icon.png') }}" alt="Departments Icon">
                Departemen
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
