<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
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
            gap: 32px;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
        }

        .section-subtitle {
            margin-top: 8px;
            color: var(--text-muted);
            font-size: 14px;
        }

        .primary-button {
            background-color: var(--blue-primary);
            color: #FFFFFF;
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(17, 43, 105, 0.25);
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .metric-card {
            border-radius: 20px;
            background-color: var(--highlight);
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .metric-card--link {
            color: inherit;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .metric-card--link:hover,
        .metric-card--link:focus-visible {
            box-shadow: 0 8px 24px rgba(17, 43, 105, 0.18);
            transform: translateY(-2px);
        }

        .metric-title {
            font-size: 14px;
            color: var(--text-muted);
        }

        .metric-value {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }

        .metric-description {
            font-size: 14px;
            color: var(--text-muted);
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

        .status-leave, .status-sick {
            background-color: rgba(59, 130, 246, 0.18);
            color: #1d4ed8;
        }

        .status-absent {
            background-color: rgba(239, 68, 68, 0.18);
            color: #b91c1c;
        }

        .analysis-section {
            display: grid;
            grid-template-columns: 2fr 1.5fr;
            gap: 24px;
        }

        .chart-card {
            border-radius: 24px;
            background-color: var(--highlight);
            padding: 24px;
            display: flex;
            flex-direction: column;
        }

        .chart-card--link {
            color: inherit;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .chart-card--link:hover,
        .chart-card--link:focus-visible {
            box-shadow: 0 8px 24px rgba(17, 43, 105, 0.18);
            transform: translateY(-2px);
        }

        .chart-title {
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 8px;
        }

        .chart-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 24px;
        }

        .chart-bars {
            flex: 1;
            display: flex;
            align-items: flex-end;
            gap: 16px;
        }

        .chart-bar-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .chart-bar {
            width: 100%;
            background: linear-gradient(180deg, rgba(17, 43, 105, 0.6), rgba(17, 43, 105, 0.2));
            border-radius: 12px 12px 4px 4px;
            min-height: 12px;
            transition: height 0.3s ease;
        }

        .chart-bar-label {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .chart-bar-value {
            font-size: 12px;
            color: var(--blue-primary);
            font-weight: 600;
        }

        .attendance-section {
            display: flex;
            flex-direction: column;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 18px;
            overflow: hidden;
        }

        thead {
            background-color: rgba(17, 43, 105, 0.05);
        }

        th {
            text-align: left;
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 600;
            padding: 16px;
        }

        td {
            padding: 16px;
            font-size: 14px;
            border-top: 1px solid var(--border-color);
        }

        tr:nth-child(even) td {
            background-color: #FAFAFA;
        }

        @media (max-width: 1200px) {
            .dashboard-layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                flex-direction: row;
                align-items: flex-start;
                gap: 32px;
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

            .analysis-section {
                grid-template-columns: 1fr;
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
        $maxChartValue = max(1, $monthlyChart->max('value') ?? 1);
        $startOfMonthLabel = $now->copy()->startOfMonth()->translatedFormat('d F');
        $endOfMonthLabel = $now->copy()->endOfMonth()->translatedFormat('d F Y');
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
                    <h1 class="top-header-title">Dashboard Admin</h1>
                    <p class="top-header-subtitle">
                        Halo, selamat datang {{ $user?->name ?? 'Administrator' }} &middot;
                        <span>{{ $now->translatedFormat('d F Y') }}</span>
                    </p>
                </div>
                <div class="profile-info">
                    <div class="avatar">{{ $userInitials }}</div>
                    <span class="profile-name">{{ $user?->name ?? 'Administrator' }}</span>
                </div>
            </header>

            <section class="content-wrapper">
                <div class="analysis-section">
                    <div>
                        <div class="section-header">
                            <div>
                                <h2 class="section-title">Analisis Metrik</h2>
                                <p class="section-subtitle">Kehadiran Bulan Ini</p>
                            </div>
                            <a class="primary-button" href="{{ route('reports.sheet') }}">Laporan Detail</a>
                        </div>

                        <div class="metrics-grid">
                            <a class="metric-card metric-card--link" href="{{ route('reports.sheet', ['type' => 'bulanan']) }}">
                                <span class="metric-title">Total Absensi Bulan Ini</span>
                                <p class="metric-value">{{ number_format($metrics['total_absence']) }}</p>
                                <span class="metric-description">Periode {{ $startOfMonthLabel }} - {{ $endOfMonthLabel }}</span>
                            </a>
                            <a class="metric-card metric-card--link" href="{{ route('manage-users.index') }}">
                                <span class="metric-title">Staff &amp; Karyawan</span>
                                <p class="metric-value">{{ number_format($metrics['employee_count']) }}</p>
                                <span class="metric-description">Total karyawan yang terdaftar</span>
                            </a>
                            <a class="metric-card metric-card--link" href="{{ route('attendance.index') }}">
                                <span class="metric-title">Presensi Harian</span>
                                <p class="metric-value">{{ number_format($metrics['daily_presence_count']) }}</p>
                                <span class="metric-description">Total pegawai hadir hari ini</span>
                            </a>
                            <a class="metric-card metric-card--link" href="{{ route('reports.sheet') }}">
                                <span class="metric-title">Tingkat Kehadiran</span>
                                <p class="metric-value">{{ number_format($metrics['attendance_rate'], 1) }}%</p>
                                <span class="metric-description">Persentase kehadiran pegawai bulan ini</span>
                            </a>
                        </div>
                    </div>

                    <a class="chart-card chart-card--link" href="{{ route('reports.sheet', ['type' => 'bulanan']) }}">
                        <h3 class="chart-title">Kehadiran Bulanan</h3>
                        <p class="chart-subtitle">Jumlah kehadiran selama 5 bulan terakhir</p>
                        <div class="chart-bars">
                            @forelse ($monthlyChart as $item)
                                @php($height = max(12, ($item['value'] / $maxChartValue) * 100))
                                <div class="chart-bar-wrapper">
                                    <div class="chart-bar" style="height: {{ $height }}%;" title="{{ $item['label'] }} - {{ $item['value'] }} absensi"></div>
                                    <span class="chart-bar-value">{{ $item['value'] }}</span>
                                    <span class="chart-bar-label">{{ $item['label'] }}</span>
                                </div>
                            @empty
                                <p style="font-size: 13px; color: var(--text-muted);">Belum ada data absensi</p>
                            @endforelse
                        </div>
                    </a>
                </div>

                <div class="attendance-section">
                    <div class="section-header">
                        <h2 class="section-title">Riwayat Absensi</h2>
                        <a href="{{ route('attendance.index') }}" class="primary-button">Lihat Detail</a>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Check-In</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentAttendances as $record)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $record->employee->full_name }}</td>
                                    <td>
                                        <span class="status-badge {{ $record->status_badge_class }}">
                                            {{ $record->status_label }}
                                        </span>
                                    </td>
                                    <td>{{ optional($record->check_in_time)->format('H:i') ?? '--:--' }}</td>
                                    <td>{{ $record->attendance_date->translatedFormat('d M Y') }}</td>
                                    <td>{{ $record->notes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 24px; color: var(--text-muted);">
                                        Belum ada data absensi terbaru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
