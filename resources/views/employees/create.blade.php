<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pegawai</title>
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

        .form-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
        }

        .form-title {
            font-size: 22px;
            margin: 0;
            color: var(--blue-primary);
        }

        .form-subtitle {
            margin-top: 6px;
            color: var(--text-muted);
            font-size: 14px;
        }

        .alert-error {
            border-radius: 18px;
            border: 1px solid rgba(239, 68, 68, 0.25);
            background-color: rgba(239, 68, 68, 0.12);
            padding: 16px 20px;
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
            min-height: 120px;
            resize: vertical;
        }

        .form-row-span {
            grid-column: span 2;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 8px;
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

        .helper-text {
            font-size: 12px;
            color: var(--text-muted);
        }

        .error-text {
            font-size: 12px;
            color: var(--danger);
            margin-top: 4px;
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

            .form-row-span {
                grid-column: span 1;
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
                    <h1 class="top-header-title">Tambah Pegawai</h1>
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
                <div class="form-header">
                    <div>
                        <h2 class="form-title">Formulir Pegawai Baru</h2>
                        <p class="form-subtitle">Lengkapi data berikut untuk menambahkan pegawai ke dalam sistem.</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert-error">
                        <strong>Gagal menyimpan data.</strong>
                        <ul style="margin: 8px 0 0 16px; padding: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('manage-users.store') }}">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="full_name">Nama Lengkap</label>
                            <input id="full_name" name="full_name" type="text" class="form-control" value="{{ old('full_name') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="employee_code">Kode Pegawai</label>
                            <input id="employee_code" name="employee_code" type="text" class="form-control" value="{{ old('employee_code') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="work_email">Email Kantor (opsional)</label>
                            <input id="work_email" name="work_email" type="email" class="form-control" value="{{ old('work_email') }}">
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input id="username" name="username" type="text" class="form-control" value="{{ old('username') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role Sistem</label>
                            <select id="role" name="role" class="form-control" required>
                                @foreach ($roleOptions as $value => $label)
                                    <option value="{{ $value }}" {{ old('role') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="password">Kata Sandi</label>
                            <input id="password" name="password" type="password" class="form-control" required>
                            <p class="helper-text">Minimal 8 karakter.</p>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Nomor Telepon</label>
                            <input id="phone" name="phone" type="text" class="form-control" value="{{ old('phone') }}">
                        </div>
                        <div class="form-group">
                            <label for="gender">Jenis Kelamin</label>
                            <select id="gender" name="gender" class="form-control">
                                <option value="">Pilih</option>
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="national_id">NIK</label>
                            <input id="national_id" name="national_id" type="text" class="form-control" value="{{ old('national_id') }}">
                        </div>
                        <div class="form-group">
                            <label for="place_of_birth">Tempat Lahir</label>
                            <input id="place_of_birth" name="place_of_birth" type="text" class="form-control" value="{{ old('place_of_birth') }}">
                        </div>
                        <div class="form-group">
                            <label for="date_of_birth">Tanggal Lahir</label>
                            <input id="date_of_birth" name="date_of_birth" type="date" class="form-control" value="{{ old('date_of_birth') }}">
                        </div>
                        <div class="form-group">
                            <label for="hire_date">Tanggal Masuk</label>
                            <input id="hire_date" name="hire_date" type="date" class="form-control" value="{{ old('hire_date') }}">
                        </div>
                        <div class="form-group">
                            <label for="employment_status">Status Kepegawaian</label>
                            <select id="employment_status" name="employment_status" class="form-control" required>
                                @foreach ($statusOptions as $value => $label)
                                    <option value="{{ $value }}" {{ old('employment_status') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="salary">Gaji Pokok</label>
                            <input id="salary" name="salary" type="number" step="0.01" class="form-control" value="{{ old('salary') }}">
                        </div>
                        <div class="form-group">
                            <label for="department_id">Departemen</label>
                            <select id="department_id" name="department_id" class="form-control" required>
                                <option value="">Pilih Departemen</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="position_id">Jabatan</label>
                            <select id="position_id" name="position_id" class="form-control" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach ($positions as $position)
                                    <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                                        {{ $position->name }} ({{ $position->department->name ?? 'Tanpa Departemen' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="schedule_id">Jadwal Kerja</label>
                            <select id="schedule_id" name="schedule_id" class="form-control" required>
                                <option value="">Pilih Jadwal</option>
                                @foreach ($schedules as $schedule)
                                    <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                        {{ $schedule->name }} ({{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group form-row-span">
                            <label for="address">Alamat</label>
                            <textarea id="address" name="address" class="form-control">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('manage-users.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Pegawai</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
