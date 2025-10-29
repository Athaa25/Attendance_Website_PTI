<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pegawai</title>
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

        .detail-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .detail-title {
            font-size: 24px;
            margin: 0;
            color: var(--blue-primary);
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

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
        }

        .detail-card {
            border-radius: 24px;
            border: 1px solid var(--border-color);
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .detail-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
        }

        .detail-value {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .detail-actions {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-top: 16px;
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

            .detail-actions {
                flex-direction: column;
                align-items: stretch;
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
                    <h1 class="top-header-title">Detail Pegawai</h1>
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
                <div class="detail-header">
                    <div>
                        <h2 class="detail-title">{{ $employee->full_name }}</h2>
                        <div style="color: var(--text-muted); font-size: 14px;">
                            {{ $employee->position->name ?? 'Jabatan belum diatur' }} &mdash; {{ $employee->department->name ?? 'Departemen belum diatur' }}
                        </div>
                    </div>
                    <span class="status-badge status-{{ $employee->employment_status }}">{{ $employee->employment_status_label }}</span>
                </div>

                <div class="detail-grid">
                    <div class="detail-card">
                        <div class="detail-label">Email</div>
                        <div class="detail-value">{{ $employee->user->email }}</div>

                        <div class="detail-label">Email Kantor</div>
                        <div class="detail-value">{{ $employee->work_email ?? '—' }}</div>

                        <div class="detail-label">Nomor Telepon</div>
                        <div class="detail-value">{{ $employee->phone ?? '—' }}</div>

                        <div class="detail-label">Username</div>
                        <div class="detail-value">{{ $employee->user->username }}</div>

                        <div class="detail-label">Role Sistem</div>
                        <div class="detail-value">{{ ucfirst($employee->user->role) }}</div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-label">Kode Pegawai</div>
                        <div class="detail-value">{{ $employee->employee_code }}</div>

                        <div class="detail-label">Jadwal Kerja</div>
                        <div class="detail-value">
                            @if ($employee->schedule)
                                {{ $employee->schedule->name }} ({{ $employee->schedule->start_time->format('H:i') }} - {{ $employee->schedule->end_time->format('H:i') }})
                            @else
                                —
                            @endif
                        </div>

                        <div class="detail-label">Tanggal Masuk</div>
                        <div class="detail-value">{{ optional($employee->hire_date)->translatedFormat('d F Y') ?? '—' }}</div>

                        <div class="detail-label">Gaji Pokok</div>
                        <div class="detail-value">
                            {{ $employee->salary ? 'Rp ' . number_format($employee->salary, 0, ',', '.') : '—' }}
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-label">NIK</div>
                        <div class="detail-value">{{ $employee->national_id ?? '—' }}</div>

                        <div class="detail-label">Tempat, Tanggal Lahir</div>
                        <div class="detail-value">
                            @if ($employee->place_of_birth || $employee->date_of_birth)
                                {{ $employee->place_of_birth ?? '' }}{{ $employee->place_of_birth && $employee->date_of_birth ? ', ' : '' }}{{ optional($employee->date_of_birth)->translatedFormat('d F Y') }}
                            @else
                                —
                            @endif
                        </div>

                        <div class="detail-label">Jenis Kelamin</div>
                        <div class="detail-value">
                            @if ($employee->gender === 'male')
                                Laki-laki
                            @elseif ($employee->gender === 'female')
                                Perempuan
                            @else
                                —
                            @endif
                        </div>

                        <div class="detail-label">Alamat</div>
                        <div class="detail-value">{{ $employee->address ?? '—' }}</div>
                    </div>
                </div>

                <div class="detail-actions">
                    <a href="{{ route('manage-users.index') }}" class="btn btn-secondary">Kembali ke daftar</a>
                    <a href="{{ route('manage-users.edit', $employee) }}" class="btn btn-primary">Edit Data Pegawai</a>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
