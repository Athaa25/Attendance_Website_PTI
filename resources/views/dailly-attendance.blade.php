<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Harian</title>
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

        .btn-danger {
            background-color: rgba(239, 68, 68, 0.18);
            color: #b91c1c;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(17, 43, 105, 0.2);
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 16px;
        }

        .summary-card {
            border-radius: 20px;
            background-color: var(--highlight);
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .summary-label {
            font-size: 12px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .summary-value {
            font-size: 26px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        thead {
            background-color: rgba(17, 43, 105, 0.05);
        }

        th {
            font-size: 12px;
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

        .status-leave {
            background-color: rgba(59, 130, 246, 0.18);
            color: #1d4ed8;
        }

        .status-sick {
            background-color: rgba(14, 165, 233, 0.18);
            color: #0f766e;
        }

        .status-absent {
            background-color: rgba(239, 68, 68, 0.18);
            color: #b91c1c;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-control {
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            background-color: var(--highlight);
            font-family: inherit;
            font-size: 14px;
            color: var(--text-dark);
        }

        .form-control:focus {
            outline: 2px solid rgba(17, 43, 105, 0.25);
            background-color: #fff;
        }

        textarea.form-control {
            min-height: 140px;
            resize: vertical;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-top: 16px;
        }

        .helper-text {
            font-size: 12px;
            color: var(--text-muted);
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

            .filter-actions,
            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    @php
        $page = $page ?? 'list';
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
                    <h1 class="top-header-title">Absensi Harian</h1>
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

                @if ($page === 'list')
                    <form method="GET" class="filter-bar">
                        <div class="filter-group">
                            <label for="date">Tanggal</label>
                            <div class="filter-input">
                                <input type="date" id="date" name="date" value="{{ $attendanceDate->format('Y-m-d') }}">
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
                        <div class="filter-group">
                            <label for="search">Pencarian</label>
                            <div class="filter-input">
                                <input type="search" id="search" name="search" placeholder="Nama atau kode pegawai" value="{{ $filters['search'] ?? '' }}">
                            </div>
                        </div>
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-secondary">Terapkan</button>
                        </div>
                    </form>

                    <div class="summary-grid">
                        <div class="summary-card">
                            <span class="summary-label">Total Pegawai</span>
                            <span class="summary-value">{{ $summary['total_employees'] }}</span>
                        </div>
                        <div class="summary-card">
                            <span class="summary-label">Hadir</span>
                            <span class="summary-value">{{ $summary['present'] }}</span>
                        </div>
                        <div class="summary-card">
                            <span class="summary-label">Terlambat</span>
                            <span class="summary-value">{{ $summary['late'] }}</span>
                        </div>
                        <div class="summary-card">
                            <span class="summary-label">Izin</span>
                            <span class="summary-value">{{ $summary['leave'] }}</span>
                        </div>
                        <div class="summary-card">
                            <span class="summary-label">Sakit</span>
                            <span class="summary-value">{{ $summary['sick'] }}</span>
                        </div>
                        <div class="summary-card">
                            <span class="summary-label">Alpa</span>
                            <span class="summary-value">{{ $summary['absent'] }}</span>
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Departemen</th>
                                <th>Status</th>
                                <th>Check-In</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($records as $record)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $record->employee->full_name }}
                                        <div style="font-size: 12px; color: var(--text-muted);">
                                            {{ $record->employee->employee_code }}
                                        </div>
                                    </td>
                                    <td>{{ $record->employee->department->name ?? '—' }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $record->status }}">
                                            {{ $record->status_label }}
                                        </span>
                                    </td>
                                    <td>{{ optional($record->check_in_time)->format('H:i') ?? '--:--' }}</td>
                                    <td>{{ $record->notes ?? '—' }}</td>
                                    <td>
                                        <a class="btn btn-secondary" href="{{ route('attendance.edit', $record) }}">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 24px; color: var(--text-muted);">
                                        Belum ada data absensi pada tanggal ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @else
                    <div class="form-header">
                        <div>
                            <h2 class="form-title">Edit Absensi Pegawai</h2>
                            <p class="form-subtitle">
                                {{ $record->employee->full_name }} &middot;
                                {{ $attendanceDate->translatedFormat('d F Y') }}
                            </p>
                        </div>
                        <a href="{{ route('attendance.index', ['date' => $attendanceDate->format('Y-m-d')]) }}" class="btn btn-secondary">Kembali</a>
                    </div>

                    <form method="POST" action="{{ route('attendance.update', $record) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="status">Status Kehadiran</label>
                                <select id="status" name="status" class="form-control" required>
                                    @foreach ($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ old('status', $record->status) === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="check_in_time">Jam Masuk</label>
                                <input id="check_in_time" name="check_in_time" type="time" class="form-control" value="{{ old('check_in_time', optional($record->check_in_time)->format('H:i')) }}">
                                <p class="helper-text">
                                    Jadwal: {{ optional($record->employee->schedule)->start_time?->format('H:i') ?? '08:00' }}
                                </p>
                            </div>
                            <div class="form-group">
                                <label for="check_out_time">Jam Pulang</label>
                                <input id="check_out_time" name="check_out_time" type="time" class="form-control" value="{{ old('check_out_time', optional($record->check_out_time)->format('H:i')) }}">
                            </div>
                            <div class="form-group form-row-span">
                                <label for="notes">Keterangan</label>
                                <textarea id="notes" name="notes" class="form-control">{{ old('notes', $record->notes) }}</textarea>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('attendance.index', ['date' => $attendanceDate->format('Y-m-d')]) }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                @endif
            </section>
        </main>
    </div>
</body>
</html>
