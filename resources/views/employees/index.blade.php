<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pegawai</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        :root {
            --blue-primary: #112B69;
            --text-dark: #1F1F1F;
            --text-muted: #6F6F6F;
            --background: #F5F5F5;
            --card-background: #FFFFFF;
            --border-color: #E5E7EB;
            --highlight: #F3F4F6;
            --danger: #EF4444;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Poppins", "Segoe UI", sans-serif;
            background-color: var(--background);
            color: var(--text-dark);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .dashboard-layout {
            min-height: 100vh;
            display: flex;
            gap: 24px;
            padding: 32px;
        }

        .sidebar {
            width: 240px;
            background-color: var(--card-background);
            border-radius: 32px;
            padding: 32px 24px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 40px rgba(17, 43, 105, 0.08);
        }

        .sidebar-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
        }

        .sidebar-section-title {
            font-size: 14px;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 0.1em;
            margin-bottom: 16px;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .sidebar-nav-group {
            margin-bottom: 24px;
        }

        .sidebar-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 12px;
            font-weight: 500;
            color: var(--text-muted);
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .sidebar-nav-item img {
            width: 20px;
            height: 20px;
        }

        .sidebar-nav-item.active,
        .sidebar-nav-item:hover {
            background-color: rgba(17, 43, 105, 0.08);
            color: var(--blue-primary);
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
        }

        .logout-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--blue-primary);
            font-weight: 600;
        }

        .logout-link img {
            width: 20px;
            height: 20px;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .top-header {
            background-color: var(--card-background);
            border-radius: 32px;
            padding: 24px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 10px 40px rgba(17, 43, 105, 0.05);
        }

        .top-header-title {
            font-size: 24px;
            color: var(--blue-primary);
            font-weight: 700;
            margin: 0;
        }

        .top-header-subtitle {
            margin: 4px 0 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .profile-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(17, 43, 105, 0.1), rgba(17, 43, 105, 0.25));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--blue-primary);
        }

        .profile-name {
            font-weight: 600;
        }

        .content-wrapper {
            background-color: var(--card-background);
            border-radius: 32px;
            padding: 32px;
            box-shadow: 0 10px 40px rgba(17, 43, 105, 0.05);
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .status-banner {
            padding: 16px 20px;
            border-radius: 16px;
            background-color: rgba(17, 43, 105, 0.08);
            color: var(--blue-primary);
            font-weight: 500;
        }

        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .filter-group label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
        }

        .filter-input {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background-color: var(--highlight);
        }

        .filter-input input,
        .filter-input select {
            border: none;
            background: transparent;
            font-family: inherit;
            font-size: 14px;
            color: var(--text-dark);
            width: 100%;
        }

        .filter-input input:focus,
        .filter-input select:focus {
            outline: none;
        }

        .filter-actions {
            margin-left: auto;
            display: flex;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-primary {
            background-color: var(--blue-primary);
            color: #FFFFFF;
            box-shadow: 0 8px 20px rgba(17, 43, 105, 0.2);
        }

        .btn-secondary {
            background-color: var(--highlight);
            color: var(--blue-primary);
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(17, 43, 105, 0.2);
        }

        .table-card {
            border-radius: 24px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: rgba(17, 43, 105, 0.05);
        }

        th {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            padding: 16px;
            text-align: left;
        }

        td {
            padding: 16px;
            border-top: 1px solid var(--border-color);
            font-size: 14px;
        }

        tr:nth-child(even) td {
            background-color: rgba(17, 43, 105, 0.02);
        }

        .table-name-link {
            color: var(--blue-primary);
            font-weight: 600;
        }

        .table-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .action-link {
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 10px;
            border: 1px solid transparent;
            font-weight: 600;
        }

        .action-link.detail {
            background-color: rgba(17, 43, 105, 0.08);
            color: var(--blue-primary);
        }

        .action-link.edit {
            background-color: rgba(59, 130, 246, 0.12);
            color: #1d4ed8;
        }

        .action-link.delete {
            background-color: rgba(239, 68, 68, 0.12);
            color: #b91c1c;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-present {
            background-color: rgba(34, 197, 94, 0.18);
            color: #15803d;
        }

        .status-late {
            background-color: rgba(234, 179, 8, 0.18);
            color: #b45309;
        }

        .status-leave,
        .status-sick {
            background-color: rgba(59, 130, 246, 0.18);
            color: #1d4ed8;
        }

        .status-absent {
            background-color: rgba(239, 68, 68, 0.18);
            color: #b91c1c;
        }

        .status-active {
            background-color: rgba(34, 197, 94, 0.18);
            color: #15803d;
        }

        .status-probation {
            background-color: rgba(234, 179, 8, 0.18);
            color: #b45309;
        }

        .status-contract {
            background-color: rgba(59, 130, 246, 0.18);
            color: #1d4ed8;
        }

        .status-inactive {
            background-color: rgba(148, 163, 184, 0.2);
            color: #475569;
        }

        .status-resigned {
            background-color: rgba(239, 68, 68, 0.18);
            color: #b91c1c;
        }

        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            border-top: 1px solid var(--border-color);
            font-size: 14px;
        }

        .pagination-controls {
            display: flex;
            gap: 12px;
        }

        .pagination-button {
            padding: 8px 14px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background-color: var(--card-background);
            color: var(--text-dark);
            font-weight: 500;
        }

        .pagination-button.disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        @media (max-width: 1200px) {
            .dashboard-layout {
                flex-direction: column;
                padding: 24px;
            }

            .sidebar {
                width: 100%;
                flex-direction: row;
                align-items: flex-start;
                gap: 24px;
            }

            .sidebar-nav {
                flex: 1;
                flex-direction: row;
                flex-wrap: wrap;
            }

            .sidebar-footer {
                border-top: none;
                border-left: 1px solid var(--border-color);
                padding-top: 0;
                padding-left: 24px;
            }

            .filter-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>
    @php
        $user = auth()->user();
        $userInitials = \Illuminate\Support\Str::of($user?->name ?? '')
            ->trim()
            ->explode(' ')
            ->filter()
            ->map(fn ($segment) => mb_strtoupper(mb_substr($segment, 0, 1)))
            ->take(2)
            ->implode('');
        if ($userInitials === '') {
            $userInitials = 'AD';
        }
    @endphp
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="sidebar-logo">
                <img src="{{ asset('images/RMDOO_logo.png') }}" alt="RMDOO Logo" width="120" height="60">
            </div>
            <div class="sidebar-nav">
                <div class="sidebar-nav-group">
                    <p class="sidebar-section-title">Menu</p>
                    <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <img src="{{ asset('images/dashboard-icon.png') }}" alt="Dashboard Icon">
                        Dashboard
                    </a>
                </div>

                <div class="sidebar-nav-group">
                    <p class="sidebar-section-title">Users Management</p>
                    <a href="{{ route('manage-users.index') }}" class="sidebar-nav-item {{ request()->routeIs('manage-users.*') ? 'active' : '' }}">
                        <img src="{{ asset('images/manage-user-icon.png') }}" alt="Manage User Icon">
                        Manage User
                    </a>
                    <a href="#" class="sidebar-nav-item">
                        <img src="{{ asset('images/user-setting-icon.png') }}" alt="User Setting Icon">
                        User Setting
                    </a>
                </div>

                <div class="sidebar-nav-group">
                    <p class="sidebar-section-title">Attendance</p>
                    <a href="#" class="sidebar-nav-item">
                        <img src="{{ asset('images/schedule-icon.png') }}" alt="Schedule Icon">
                        Schedule
                    </a>
                    <a href="{{ route('attendance.index') }}" class="sidebar-nav-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                        <img src="{{ asset('images/daily-attendance-icon.png') }}" alt="Daily Attendance Icon">
                        Daily Attendance
                    </a>
                    <a href="{{ route('reports.sheet') }}" class="sidebar-nav-item {{ request()->routeIs('reports.sheet') ? 'active' : '' }}">
                        <img src="{{ asset('images/sheet-report-icon.png') }}" alt="Sheet Report Icon">
                        Sheet Report
                    </a>
                </div>
            </div>

            <div class="sidebar-footer">
                <a href="{{ route('logout') }}" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <img src="{{ asset('images/logout-box-icon.png') }}" alt="Logout Icon">
                    Keluar
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </aside>

        <main class="main-content">
            <header class="top-header">
                <div>
                    <h1 class="top-header-title">Kelola Pegawai</h1>
                    <p class="top-header-subtitle">
                        Halo, {{ $user?->name ?? 'Administrator' }} &middot;
                        <span>{{ now()->translatedFormat('d F Y') }}</span>
                    </p>
                </div>
                <div class="profile-info">
                    <div class="avatar">{{ $userInitials }}</div>
                    <span class="profile-name">{{ $user?->name ?? 'Administrator' }}</span>
                </div>
            </header>

            <section class="content-wrapper">
                @if (session('status'))
                    <div class="status-banner">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="GET" class="filter-bar">
                    <div class="filter-group">
                        <label for="search">Pencarian</label>
                        <div class="filter-input">
                            <input type="search" id="search" name="search" placeholder="Nama atau kode pegawai" value="{{ $filters['search'] ?? '' }}">
                        </div>
                    </div>
                    <div class="filter-group">
                        <label for="department_id">Departemen</label>
                        <div class="filter-input">
                            <select id="department_id" name="department_id">
                                <option value="">Semua</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ ($filters['department_id'] ?? null) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="filter-group">
                        <label for="status">Status</label>
                        <div class="filter-input">
                            <select id="status" name="status">
                                <option value="">Semua</option>
                                @foreach ($statusOptions as $value => $label)
                                    <option value="{{ $value }}" {{ ($filters['status'] ?? '') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-secondary">Terapkan</button>
                        <a href="{{ route('manage-users.create') }}" class="btn btn-primary">
                            <span>+</span> Tambah Pegawai
                        </a>
                    </div>
                </form>

                <div class="table-card">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Departemen</th>
                                <th>Jadwal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employees as $employee)
                                <tr>
                                    <td>{{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <a class="table-name-link" href="{{ route('manage-users.show', $employee) }}">
                                            {{ $employee->full_name }}
                                        </a>
                                        <div style="font-size: 12px; color: var(--text-muted);">
                                            {{ $employee->user->email }}
                                        </div>
                                    </td>
                                    <td>{{ $employee->department->name ?? '—' }}</td>
                                    <td>{{ $employee->schedule->name ?? '—' }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $employee->employment_status }}">
                                            {{ $employee->employment_status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a class="action-link detail" href="{{ route('manage-users.show', $employee) }}">Detail</a>
                                            <a class="action-link edit" href="{{ route('manage-users.edit', $employee) }}">Edit</a>
                                            <form method="POST" action="{{ route('manage-users.destroy', $employee) }}" onsubmit="return confirm('Hapus pegawai ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-link delete">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 24px; color: var(--text-muted);">
                                        Belum ada data pegawai yang sesuai filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($employees->hasPages())
                        <div class="pagination">
                            <span>Halaman {{ $employees->currentPage() }} dari {{ $employees->lastPage() }}</span>
                            <div class="pagination-controls">
                                <a class="pagination-button {{ $employees->onFirstPage() ? 'disabled' : '' }}" href="{{ $employees->previousPageUrl() ?? '#' }}">Sebelumnya</a>
                                <a class="pagination-button {{ $employees->hasMorePages() ? '' : 'disabled' }}" href="{{ $employees->nextPageUrl() ?? '#' }}">Selanjutnya</a>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        </main>
    </div>
</body>
</html>
