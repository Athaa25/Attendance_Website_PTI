<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi</title>
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

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .report-title-block {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .report-title {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            color: var(--blue-primary);
        }

        .report-subtitle {
            margin: 0;
            font-size: 14px;
            color: var(--text-muted);
        }

        .report-export {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-icon {
            width: 18px;
            height: 18px;
            object-fit: contain;
        }

        .report-filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: flex-end;
            padding: 24px;
            background-color: var(--highlight);
            border: 1px solid var(--border-color);
            border-radius: 24px;
        }

        .filter-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 180px;
        }

        .filter-field--wide {
            min-width: 220px;
        }

        .filter-field--view {
            min-width: 200px;
        }

        .filter-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            font-weight: 600;
        }

        .filter-control {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            background-color: #fff;
            min-height: 48px;
        }

        .filter-control select,
        .filter-control input {
            border: none;
            background: transparent;
            font-family: inherit;
            font-size: 14px;
            color: var(--text-dark);
            width: 100%;
        }

        .filter-control select:focus,
        .filter-control input:focus {
            outline: none;
        }

        .view-toggle {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: rgba(17, 43, 105, 0.08);
            border-radius: 14px;
            padding: 4px;
        }

        .view-option {
            border: none;
            background: transparent;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 13px;
            color: var(--text-muted);
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
        }

        .view-option:focus {
            outline: none;
        }

        .view-option svg {
            width: 18px;
            height: 18px;
        }

        .view-option.active {
            background-color: #fff;
            color: var(--blue-primary);
            box-shadow: 0 8px 20px rgba(17, 43, 105, 0.15);
        }

        .view-option:not(.active):hover {
            color: var(--blue-primary);
        }

        .report-filter-actions {
            margin-left: auto;
            display: flex;
            align-items: center;
        }

        .btn-filter {
            padding: 12px 28px;
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

        .employee-cell {
            display: flex;
            flex-direction: column;
        }

        .employee-name {
            font-weight: 600;
        }

        .employee-department {
            font-size: 12px;
            color: var(--text-muted);
        }

        table {
            width: 100%;
            border-collapse: collapse;
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
            font-size: 14px;
            vertical-align: middle;
        }

        tr:nth-child(even) td {
            background-color: rgba(17, 43, 105, 0.02);
        }

        .summary-wrapper {
            border-radius: 24px;
            border: 1px solid var(--border-color);
            background-color: var(--card-background);
            padding: 20px 24px 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .table-scroll {
            width: 100%;
            overflow-x: auto;
        }

        .table-scroll table {
            min-width: 900px;
        }

        .matrix-table {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            background-color: #fff;
            table-layout: fixed;
        }

        .matrix-table thead th {
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            color: var(--blue-primary);
            border-bottom: 1px solid var(--border-color);
            padding: 12px;
        }

        .matrix-table thead th:first-child,
        .matrix-table thead th:nth-child(2) {
            text-align: left;
        }

        .matrix-table thead th:nth-child(n + 3) {
            min-width: 44px;
        }

        .matrix-table tbody td,
        .matrix-table tbody th {
            border: 1px solid var(--border-color);
            padding: 10px 12px;
            font-size: 13px;
        }

        .matrix-table tbody td:first-child {
            text-align: center;
            font-weight: 600;
            width: 48px;
        }

        .matrix-table tbody td:nth-child(2) {
            text-align: left;
            min-width: 180px;
        }

        .matrix-table tbody td.matrix-cell {
            text-align: center;
            font-weight: 600;
            letter-spacing: 0.02em;
            min-width: 44px;
        }

        .matrix-table tbody tr:nth-child(even) td {
            background-color: transparent;
        }

        .matrix-table tbody tr:hover td {
            background-color: rgba(17, 43, 105, 0.05);
        }

        .detail-table {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            background-color: #fff;
        }

        .detail-table thead th {
            background-color: rgba(17, 43, 105, 0.05);
            border-bottom: 1px solid var(--border-color);
        }

        .detail-table tbody td {
            border-top: 1px solid var(--border-color);
        }

        .empty-state {
            text-align: center;
            padding: 32px;
            color: var(--text-muted);
            font-size: 15px;
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

        .legend {
            display: flex;
            gap: 16px;
            font-size: 13px;
            color: var(--text-muted);
            flex-wrap: wrap;
        }

        .legend span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .legend-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
        }

        .legend-badge.present { background-color: #16a34a; }
        .legend-badge.late { background-color: #f59e0b; }
        .legend-badge.leave { background-color: #2563eb; }
        .legend-badge.sick { background-color: #0ea5e9; }
        .legend-badge.absent { background-color: #ef4444; }

        @media print {
            body {
                margin: 0;
                padding: 0;
                background: #fff;
            }

            body * {
                visibility: hidden !important;
            }

            .report-printable,
            .report-printable * {
                visibility: visible !important;
            }

            .report-printable {
                position: absolute;
                inset: 0;
                width: 100%;
                padding: 24px;
                box-sizing: border-box;
            }

            .report-printable .table-scroll {
                overflow: visible;
                max-height: none;
            }

            .report-printable table {
                min-width: 0 !important;
                width: 100%;
            }

            .report-printable .summary-wrapper {
                border-radius: 0;
                box-shadow: none;
            }

            .report-printable .legend {
                page-break-inside: avoid;
            }

            .report-printable table,
            .report-printable th,
            .report-printable td {
                border-color: #d1d5db !important;
            }
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

            .report-filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .report-filter-actions {
                margin-left: 0;
                width: 100%;
                display: flex;
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
                    <a href="{{ route('departments.index') }}" class="sidebar-nav-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                        <img src="{{ asset('images/user-setting-icon.png') }}" alt="Departments Icon">
                        Departemen
                    </a>
                </div>

                <div class="sidebar-nav-group">
                    <p class="sidebar-section-title">Attendance</p>
                    <a href="{{ route('schedule.index') }}" class="sidebar-nav-item {{ request()->routeIs('schedule.index') ? 'active' : '' }}">
                        <img src="{{ asset('images/schedule-icon.png') }}" alt="Schedule Icon">
                        Schedule
                    </a>
                    <a href="{{ route('attendance.index') }}" class="sidebar-nav-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                        <img src="{{ asset('images/daily-attendance-icon.png') }}" alt="Attendance Icon">
                        Attendance
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
                    <h1 class="top-header-title">Laporan Absensi</h1>
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
                <div class="report-header">
                    <div class="report-title-block">
                        <h2 class="report-title">Rekapan</h2>
                        <p class="report-subtitle">Pantau kehadiran karyawan berdasarkan rentang tanggal dan nama yang dipilih.</p>
                    </div>
                    <button type="button" class="btn btn-primary report-export" id="export-pdf-button">
                        <img src="{{ asset('images/file-download-icon.png') }}" alt="Download" class="btn-icon">
                        Export PDF
                    </button>
                </div>

                <form method="GET" class="report-filter-form" id="report-filter-form">
                    @if (request()->has('type'))
                        <input type="hidden" name="type" value="{{ request('type') }}">
                    @endif
                    <input type="hidden" name="view" id="view-mode" value="{{ $viewMode }}">

                    <div class="filter-field filter-field--wide">
                        <span class="filter-label">Nama</span>
                        <div class="filter-control">
                            <select name="employee_id" id="employee_id">
                                <option value="all" {{ $selectedEmployeeId === 'all' ? 'selected' : '' }}>Semua</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ (int) $selectedEmployeeId === $employee->id ? 'selected' : '' }}>
                                        {{ $employee->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="filter-field">
                        <span class="filter-label">Mulai</span>
                        <div class="filter-control">
                            <input type="date" name="start" id="start" value="{{ $startDate->format('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="filter-field">
                        <span class="filter-label">Selesai</span>
                        <div class="filter-control">
                            <input type="date" name="end" id="end" value="{{ $endDate->format('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="filter-field filter-field--view">
                        <span class="filter-label">View</span>
                        <div class="view-toggle" role="group" aria-label="Pilih tampilan laporan">
                            <button type="button" class="view-option {{ $viewMode === 'detail' ? 'active' : '' }}" data-view-option="detail">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="3" y="5" width="18" height="3" rx="1.5" fill="currentColor" />
                                    <rect x="3" y="10.5" width="18" height="3" rx="1.5" fill="currentColor" opacity="0.6" />
                                    <rect x="3" y="16" width="18" height="3" rx="1.5" fill="currentColor" opacity="0.4" />
                                </svg>
                                <span>Detail</span>
                            </button>
                            <button type="button" class="view-option {{ $viewMode === 'summary' ? 'active' : '' }}" data-view-option="summary">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="4" y="4" width="6" height="6" rx="1.5" fill="currentColor" />
                                    <rect x="14" y="4" width="6" height="6" rx="1.5" fill="currentColor" opacity="0.7" />
                                    <rect x="4" y="14" width="6" height="6" rx="1.5" fill="currentColor" opacity="0.7" />
                                    <rect x="14" y="14" width="6" height="6" rx="1.5" fill="currentColor" />
                                </svg>
                                <span>Ringkas</span>
                            </button>
                        </div>
                    </div>

                    <div class="report-filter-actions">
                        <button type="submit" class="btn btn-primary btn-filter">Filter</button>
                    </div>
                </form>

                @if ($viewMode === 'summary')
                    <div class="report-printable">
                        <div class="summary-wrapper">
                            <div class="legend">
                                <span><span class="legend-badge present">H</span> Hadir</span>
                                <span><span class="legend-badge late">T</span> Terlambat</span>
                                <span><span class="legend-badge leave">I</span> Izin</span>
                                <span><span class="legend-badge sick">S</span> Sakit</span>
                                <span><span class="legend-badge absent">A</span> Alpa</span>
                            </div>

                            <div class="table-scroll">
                                <table class="matrix-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            @foreach ($dateRange as $date)
                                                <th>{{ $date->format('j') }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($summaryMatrix as $row)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="employee-cell">
                                                        <span class="employee-name">{{ $row['employee']->full_name }}</span>
                                                        <span class="employee-department">{{ $row['employee']->department->name ?? '—' }}</span>
                                                    </div>
                                                </td>
                                                @foreach ($dateRange as $date)
                                                    @php($key = $date->format('Y-m-d'))
                                                    @php($symbol = $row['days'][$key] ?? '')
                                                    <td class="matrix-cell">{{ $symbol }}</td>
                                                @endforeach
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ count($dateRange) + 2 }}" class="empty-state">
                                                    Tidak ada data absensi pada rentang tanggal ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="report-printable">
                        <div class="table-scroll">
                            <table class="detail-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Departemen</th>
                                        <th>Tanggal</th>
                                        <th>Check-In</th>
                                        <th>Check-Out</th>
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($records as $record)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $record->employee->full_name }}</td>
                                            <td>{{ $record->employee->department->name ?? '—' }}</td>
                                            <td>{{ $record->attendance_date->translatedFormat('d F Y') }}</td>
                                            <td>{{ optional($record->check_in_time)->format('H:i') ?? '--:--' }}</td>
                                            <td>{{ optional($record->check_out_time)->format('H:i') ?? '--:--' }}</td>
                                            <td>{{ $record->notes ?? '—' }}</td>
                                            <td>
                                                <span class="status-badge status-{{ $record->status }}">{{ $statusLabels[$record->status] ?? $record->status_label }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="empty-state">
                                                Tidak ada data absensi pada rentang tanggal ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </section>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const exportButton = document.getElementById('export-pdf-button');

            if (exportButton) {
                exportButton.addEventListener('click', function () {
                    window.print();
                });
            }

            const form = document.getElementById('report-filter-form');
            const viewInput = document.getElementById('view-mode');
            const viewButtons = document.querySelectorAll('[data-view-option]');

            if (!form || !viewInput || !viewButtons.length) {
                return;
            }

            viewButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const targetView = button.getAttribute('data-view-option');

                    if (!targetView) {
                        return;
                    }

                    viewInput.value = targetView;

                    viewButtons.forEach(function (btn) {
                        btn.classList.toggle('active', btn === button);
                    });

                    form.submit();
                });
            });
        });
    </script>
</body>
</html>
