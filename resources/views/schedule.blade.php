<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule</title>
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
            --warning: #F59E0B;
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

        .schedule-card {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .schedule-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .schedule-title {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--blue-primary);
        }

        .schedule-subtitle {
            margin: 6px 0 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .schedule-actions {
            display: flex;
            gap: 16px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn:focus {
            outline: none;
        }

        .btn-primary {
            background-color: var(--blue-primary);
            color: #FFFFFF;
            box-shadow: 0 10px 25px rgba(17, 43, 105, 0.15);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 30px rgba(17, 43, 105, 0.2);
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 24px;
            border: 1px solid var(--border-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
        }

        thead {
            background-color: rgba(17, 43, 105, 0.04);
        }

        th {
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            color: var(--text-muted);
            padding: 16px 24px;
            letter-spacing: 0.01em;
        }

        td {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-color);
            font-weight: 500;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background-color: rgba(17, 43, 105, 0.04);
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .icon-button {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background-color: rgba(17, 43, 105, 0.08);
            color: var(--blue-primary);
        }

        .icon-button img {
            width: 16px;
            height: 16px;
        }

        .icon-button.edit {
            background-color: rgba(17, 43, 105, 0.1);
        }

        .icon-button.delete {
            background-color: rgba(239, 68, 68, 0.12);
            color: var(--danger);
        }

        .icon-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(17, 43, 105, 0.15);
        }

        .icon-button.delete:hover {
            box-shadow: 0 8px 18px rgba(239, 68, 68, 0.15);
        }

        .shift-id {
            color: var(--blue-primary);
            font-weight: 600;
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
        }

        @media (max-width: 768px) {
            .dashboard-layout {
                padding: 24px;
            }

            .top-header,
            .content-wrapper {
                padding: 24px;
            }

            .top-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .profile-info {
                width: 100%;
                justify-content: space-between;
            }

            .schedule-header {
                flex-direction: column;
                align-items: flex-start;
            }

            table {
                min-width: 100%;
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

        $schedules = [
            ['id' => '01', 'name' => 'Pagi', 'check_in' => '08.00', 'check_out' => '16.00'],
            ['id' => '02', 'name' => 'Siang', 'check_in' => '12.00', 'check_out' => '20.00'],
            ['id' => '03', 'name' => 'Malam', 'check_in' => '20.00', 'check_out' => '04.00'],
            ['id' => '04', 'name' => 'Pagi', 'check_in' => '08.00', 'check_out' => '16.00'],
            ['id' => '05', 'name' => 'Siang', 'check_in' => '12.00', 'check_out' => '20.00'],
            ['id' => '06', 'name' => 'Malam', 'check_in' => '20.00', 'check_out' => '04.00'],
            ['id' => '07', 'name' => 'Pagi', 'check_in' => '08.00', 'check_out' => '16.00'],
            ['id' => '08', 'name' => 'Siang', 'check_in' => '12.00', 'check_out' => '20.00'],
            ['id' => '09', 'name' => 'Malam', 'check_in' => '20.00', 'check_out' => '04.00'],
            ['id' => '10', 'name' => 'Pagi', 'check_in' => '08.00', 'check_out' => '16.00'],
        ];
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
                    <a href="{{ route('schedule.index') }}" class="sidebar-nav-item {{ request()->routeIs('schedule.index') ? 'active' : '' }}">
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
                    <h1 class="top-header-title">Schedule</h1>
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
                <div class="schedule-card">
                    <div class="schedule-header">
                        <div>
                            <h2 class="schedule-title">Daftar Shift</h2>
                            <p class="schedule-subtitle">Jam masuk dan keluar setiap shift</p>
                        </div>
                        <div class="schedule-actions">
                            <button class="btn btn-primary" type="button">
                                Add
                            </button>
                        </div>
                    </div>

                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Id Shift</th>
                                    <th>Shift Name</th>
                                    <th>Check in</th>
                                    <th>Check out</th>
                                    <th style="text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedules as $schedule)
                                    <tr>
                                        <td class="shift-id">{{ $schedule['id'] }}</td>
                                        <td>{{ $schedule['name'] }}</td>
                                        <td>{{ $schedule['check_in'] }}</td>
                                        <td>{{ $schedule['check_out'] }}</td>
                                        <td>
                                            <div class="actions" style="justify-content: flex-end;">
                                                <button class="icon-button edit" type="button" title="Edit">
                                                    <img src="{{ asset('images/edit-icon.png') }}" alt="Edit schedule">
                                                </button>
                                                <button class="icon-button delete delete-btn" type="button" title="Delete">
                                                    <img src="{{ asset('images/delete-icon.png') }}" alt="Delete schedule">
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        document.querySelectorAll('.delete-btn').forEach((button) => {
            button.addEventListener('click', () => {
                const confirmed = window.confirm('Apakah Anda yakin ingin menghapus data shift ini?');
                if (confirmed) {
                    alert('Data shift telah dihapus (simulasi).');
                }
            });
        });
    </script>
</body>
</html>
