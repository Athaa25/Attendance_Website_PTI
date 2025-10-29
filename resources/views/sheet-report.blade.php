<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sheet Report</title>
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
            --accent: #1F6FEB;
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

        .content-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .content-title {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            color: var(--blue-primary);
        }

        .action-bar {
            display: flex;
            align-items: flex-end;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .export-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 28px;
            border-radius: 12px;
            border: none;
            background-color: var(--blue-primary);
            color: #FFFFFF;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
            box-shadow: 0 6px 18px rgba(17, 43, 105, 0.25);
        }

        .export-button img {
            width: 18px;
            height: 18px;
            object-fit: contain;
        }

        .export-button:hover {
            background-color: #143b82;
            transform: translateY(-1px);
        }

        .filters {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            flex-wrap: wrap;
        }

        .export-button,
        .edit-button {
            align-self: flex-end;
        }

        .filter-control {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .filter-label {
            font-size: 12px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .input-shell {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            background-color: var(--highlight);
            min-width: 180px;
        }

        .input-shell input,
        .input-shell select {
            border: none;
            background: transparent;
            font-family: inherit;
            font-size: 14px;
            color: var(--text-dark);
            width: 100%;
        }

        .input-shell input:focus,
        .input-shell select:focus {
            outline: none;
        }

        .edit-button {
            background-color: var(--blue-primary);
            color: #FFFFFF;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .edit-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(17, 43, 105, 0.2);
        }

        .report-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background-color: var(--card-background);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(17, 43, 105, 0.06);
        }

        .report-table thead {
            background-color: var(--highlight);
        }

        .report-table th {
            text-align: left;
            padding: 18px 24px;
            font-weight: 600;
            font-size: 14px;
            color: var(--text-muted);
        }

        .report-table tbody tr {
            border-bottom: 1px solid var(--border-color);
        }

        .report-table tbody tr:last-child {
            border-bottom: none;
        }

        .report-table td {
            padding: 18px 24px;
            font-size: 14px;
            color: var(--text-dark);
        }

        .table-scroll {
            width: 100%;
            overflow-x: auto;
            border-radius: 24px;
            background-color: var(--card-background);
            box-shadow: 0 12px 30px rgba(17, 43, 105, 0.06);
            padding: 16px;
        }

        .table-scroll .report-table {
            box-shadow: none;
            border-radius: 0;
            min-width: 1200px;
        }

        .monthly-report-table {
            border-collapse: collapse;
            table-layout: fixed;
            width: 100%;
        }

        .monthly-report-table thead {
            background-color: #F9FAFB;
        }

        .monthly-report-table th,
        .monthly-report-table td {
            border: 1px solid var(--border-color);
            padding: 12px 16px;
            font-size: 13px;
            text-align: center;
        }

        .monthly-report-table th:first-child,
        .monthly-report-table td:first-child {
            width: 56px;
            font-weight: 600;
        }

        .monthly-report-table th:nth-child(2),
        .monthly-report-table td:nth-child(2) {
            text-align: left;
            min-width: 200px;
            font-weight: 600;
        }

        .monthly-report-table tbody tr:nth-child(even) {
            background-color: rgba(17, 43, 105, 0.02);
        }

        .notes-text {
            color: var(--text-muted);
            font-size: 13px;
            font-style: italic;
        }

        @media (max-width: 1200px) {
            .dashboard-layout {
                padding: 24px;
            }
        }

        @media (max-width: 992px) {
            .dashboard-layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                flex-direction: row;
                gap: 24px;
                align-items: center;
                border-radius: 24px;
                padding: 24px;
                flex-wrap: wrap;
            }

            .sidebar-nav {
                flex: 1;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                gap: 12px;
            }

            .sidebar-footer {
                border-top: none;
                border-left: 1px solid var(--border-color);
                padding-top: 0;
                padding-left: 24px;
            }

            .content-header {
                align-items: flex-start;
            }

            .action-bar {
                justify-content: flex-start;
            }
        }

        @media (max-width: 768px) {
            .top-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .content-wrapper {
                padding: 24px;
            }

            .report-table {
                display: block;
                overflow-x: auto;
            }

            .input-shell {
                min-width: 140px;
            }
        }
    </style>
</head>
<body>
    @php
        $activeType = request('type', 'harian');
        $allowedTypes = ['harian', 'mingguan', 'bulanan', 'custom'];
        $activeType = in_array($activeType, $allowedTypes) ? $activeType : 'harian';

        $customReportEmployees = [
            'fefe-fifi-fufu-fafa' => [
                'name' => 'Fefe Fifi Fufu Fafa',
                'records' => [
                    ['status' => 'Hadir', 'time' => '09:54', 'date' => '1 September 2025', 'notes' => 'Tepat waktu'],
                    ['status' => 'Hadir', 'time' => '09:54', 'date' => '2 September 2025', 'notes' => 'Terlambat'],
                    ['status' => 'Hadir', 'time' => '09:54', 'date' => '3 September 2025', 'notes' => 'Tepat waktu'],
                    ['status' => 'Hadir', 'time' => '09:54', 'date' => '4 September 2025', 'notes' => 'Tepat waktu'],
                    ['status' => 'Izin', 'time' => '--:--', 'date' => '5 September 2025', 'notes' => 'Izin'],
                    ['status' => 'Hadir', 'time' => '09:54', 'date' => '6 September 2025', 'notes' => 'Tepat waktu'],
                    ['status' => 'Hadir', 'time' => '09:54', 'date' => '7 September 2025', 'notes' => 'Tepat waktu'],
                    ['status' => 'Hadir', 'time' => '09:54', 'date' => '8 September 2025', 'notes' => 'Terlambat'],
                    ['status' => 'Izin', 'time' => '--:--', 'date' => '9 September 2025', 'notes' => 'Izin'],
                    ['status' => 'Izin', 'time' => '--:--', 'date' => '10 September 2025', 'notes' => 'Izin hadir'],
                ],
            ],
            'rio-hu' => [
                'name' => 'Rio Hu',
                'records' => [
                    ['status' => 'Hadir', 'time' => '09:02', 'date' => '1 September 2025', 'notes' => 'Tepat waktu'],
                    ['status' => 'Hadir', 'time' => '09:10', 'date' => '2 September 2025', 'notes' => 'Terlambat'],
                    ['status' => 'Hadir', 'time' => '08:55', 'date' => '3 September 2025', 'notes' => 'Tepat waktu'],
                ],
            ],
            'pepet-siebor' => [
                'name' => 'Pepet Siebor',
                'records' => [
                    ['status' => 'Hadir', 'time' => '09:05', 'date' => '1 September 2025', 'notes' => 'Tepat waktu'],
                    ['status' => 'Sakit', 'time' => '--:--', 'date' => '2 September 2025', 'notes' => 'Sakit'],
                ],
            ],
        ];

        $defaultEmployeeKey = array_key_first($customReportEmployees);
        $selectedEmployeeKey = request('employee', $defaultEmployeeKey);
        $selectedEmployeeKey = array_key_exists($selectedEmployeeKey, $customReportEmployees)
            ? $selectedEmployeeKey
            : $defaultEmployeeKey;
        $selectedEmployee = $customReportEmployees[$selectedEmployeeKey];
    @endphp
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="sidebar-logo">
                <img src="{{ asset('images/RMDI_logo.png') }}" alt="RMDI Logo" width="120" height="60">
            </div>
            <div class="sidebar-nav">
                <div class="sidebar-nav-group">
                    <p class="sidebar-section-title">Menu</p>
                    <a href="{{ url('/dashboard') }}" class="sidebar-nav-item">
                        <img src="{{ asset('images/dashboard-icon.png') }}" alt="Dashboard Icon">
                        Dashboard
                    </a>
                </div>

                <div class="sidebar-nav-group">
                    <p class="sidebar-section-title">Users Management</p>
                    <a href="{{ url('/manage-users') }}" class="sidebar-nav-item">
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
                    <a href="{{ url('/daily-attendance') }}" class="sidebar-nav-item">
                        <img src="{{ asset('images/daily-attendance-icon.png') }}" alt="Daily Attendance Icon">
                        Daily Attendance
                    </a>
                    <a href="{{ url('/sheet-report') }}" class="sidebar-nav-item active">
                        <img src="{{ asset('images/sheet-report-icon.png') }}" alt="Sheet Report Icon">
                        Sheet Report
                    </a>
                </div>
            </div>

            <div class="sidebar-footer">
                <a href="#" class="logout-link">
                    <img src="{{ asset('images/logout-box-icon.png') }}" alt="Logout Icon">
                    Keluar
                </a>
            </div>
        </aside>

        <main class="main-content">
            <header class="top-header">
                <div>
                    <h1 class="top-header-title">Dashboard Admin</h1>
                    <p class="top-header-subtitle">Halo, Selamat Datang Prabo</p>
                </div>
                <div class="profile-info">
                    <div class="avatar">AP</div>
                    <span class="profile-name">Akbar Prabo</span>
                </div>
            </header>

            <section class="content-wrapper">
                <div class="content-header">
                    <h2 class="content-title">Rekapan</h2>
                    <div class="action-bar">
                        <button type="button" class="export-button">
                            <img src="{{ asset('images/file-download-icon.png') }}" alt="Export Icon">
                            Export PDF
                        </button>
                        <div class="filters">
                            @if ($activeType === 'harian')
                                <label class="filter-control">
                                    <span class="filter-label">Tanggal</span>
                                    <div class="input-shell">
                                        <input type="text" value="10/09/2024" readonly>
                                    </div>
                                </label>
                            @elseif ($activeType === 'mingguan')
                                <label class="filter-control">
                                    <span class="filter-label">Mulai</span>
                                    <div class="input-shell">
                                        <input type="text" value="10/19/2024" readonly>
                                    </div>
                                </label>
                                <label class="filter-control">
                                    <span class="filter-label">Selesai</span>
                                    <div class="input-shell">
                                        <input type="text" value="10/26/2024" readonly>
                                    </div>
                                </label>
                            @elseif ($activeType === 'bulanan')
                                <label class="filter-control">
                                    <span class="filter-label">Nama Pegawai</span>
                                    <div class="input-shell">
                                        <select>
                                            <option>Fefe Fifi Fufu Fafa</option>
                                            <option>Rio Hu</option>
                                            <option>Pepet Siebor</option>
                                            <option>Mie Ayam Gedangan</option>
                                            <option>Hasan Suandoro</option>
                                        </select>
                                    </div>
                                </label>
                                <label class="filter-control">
                                    <span class="filter-label">Periode</span>
                                    <div class="input-shell">
                                        <select>
                                            <option>Custom</option>
                                            <option>Januari 2024</option>
                                            <option>Februari 2024</option>
                                            <option>Maret 2024</option>
                                            <option>April 2024</option>
                                        </select>
                                    </div>
                                </label>
                            @elseif ($activeType === 'custom')
                                <label class="filter-control">
                                    <span class="filter-label">Nama Pegawai</span>
                                    <div class="input-shell">
                                        <select id="employeeSelect">
                                            @foreach ($customReportEmployees as $employeeKey => $employee)
                                                <option value="{{ $employeeKey }}" {{ $employeeKey === $selectedEmployeeKey ? 'selected' : '' }}>
                                                    {{ $employee['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </label>
                            @endif
                            <label class="filter-control">
                                <span class="filter-label">Mode Rekapan</span>
                                <div class="input-shell">
                                    <select id="reportViewSelect">
                                        <option value="harian" {{ $activeType === 'harian' ? 'selected' : '' }}>Harian</option>
                                        <option value="mingguan" {{ $activeType === 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                                        <option value="bulanan" {{ $activeType === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                        <option value="custom" {{ $activeType === 'custom' ? 'selected' : '' }}>Custom</option>
                                    </select>
                                </div>
                            </label>
                        </div>
                        <button type="button" class="edit-button">Edit</button>
                    </div>
                </div>

                @if ($activeType === 'harian')
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Departemen</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Fefe Fifi Fufu Fafa</td>
                                <td>Front End</td>
                                <td>08.50</td>
                                <td>17.45</td>
                                <td>Jika pegawai izin/alfa/sakit</td>
                                <td>Sakit</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Rio Hu</td>
                                <td>Back End</td>
                                <td>08.50</td>
                                <td>17.45</td>
                                <td>-</td>
                                <td>Hadir</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Pepet Siebor</td>
                                <td>Back End</td>
                                <td>08.50</td>
                                <td>17.45</td>
                                <td>-</td>
                                <td>Hadir</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Mie Ayam Gedangan</td>
                                <td>Administrasi</td>
                                <td>08.50</td>
                                <td>17.45</td>
                                <td>-</td>
                                <td>Hadir</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Hasan Suandoro</td>
                                <td>UI/UX</td>
                                <td>08.50</td>
                                <td>17.45</td>
                                <td>-</td>
                                <td>Hadir</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Rio Hu</td>
                                <td>Back End</td>
                                <td>08.48</td>
                                <td>17.45</td>
                                <td>-</td>
                                <td>Hadir</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Pepet Siebor</td>
                                <td>TU</td>
                                <td>08.43</td>
                                <td>17.45</td>
                                <td>-</td>
                                <td>Hadir</td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Mie Ayam Gedangan</td>
                                <td>Marketing</td>
                                <td>08.50</td>
                                <td>17.45</td>
                                <td>-</td>
                                <td>Hadir</td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Hasan Suandoro</td>
                                <td>Back End</td>
                                <td>08.50</td>
                                <td>17.45</td>
                                <td>-</td>
                                <td>Hadir</td>
                            </tr>
                        </tbody>
                    </table>
                @elseif ($activeType === 'mingguan')
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Departemen</th>
                                <th>Jumlah Presensi</th>
                                <th>Hadir</th>
                                <th>Izin</th>
                                <th>Alfa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Fefe Fifi Fufu Fafa</td>
                                <td>Front End</td>
                                <td>5</td>
                                <td>5</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Rio Hu</td>
                                <td>Back End</td>
                                <td>5</td>
                                <td>5</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Pepet Siebor</td>
                                <td>UI/UX</td>
                                <td>5</td>
                                <td>5</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Mie Ayam Gedangan</td>
                                <td>Administrasi</td>
                                <td>5</td>
                                <td>5</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Hasan Susanto</td>
                                <td>Front End</td>
                                <td>5</td>
                                <td>5</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Rio Hu</td>
                                <td>Back End</td>
                                <td>5</td>
                                <td>5</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Pepet Siebor</td>
                                <td>TU/UX</td>
                                <td>5</td>
                                <td>5</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Mie Ayam Gedangan</td>
                                <td>Marketing</td>
                                <td>5</td>
                                <td>5</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Hasan Susanto</td>
                                <td>Operational</td>
                                <td>5</td>
                                <td>5</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Mie Ayam Gedangan</td>
                                <td>Operational</td>
                                <td>5</td>
                                <td>5</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                        </tbody>
                    </table>
                @elseif ($activeType === 'custom')
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Waktu</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($selectedEmployee['records'] as $index => $record)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $selectedEmployee['name'] }}</td>
                                    <td>{{ $record['status'] }}</td>
                                    <td>{{ $record['time'] }}</td>
                                    <td>{{ $record['date'] }}</td>
                                    <td>{{ $record['notes'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="table-scroll">
                        <table class="report-table monthly-report-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    @for ($day = 1; $day <= 31; $day++)
                                        <th>{{ $day }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Fefe Fifi Fufu Fafa</td>
                                    @for ($day = 1; $day <= 31; $day++)
                                        <td></td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Rio Hiu</td>
                                    @for ($day = 1; $day <= 31; $day++)
                                        <td></td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Pepet Siebor</td>
                                    @for ($day = 1; $day <= 31; $day++)
                                        <td></td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Mie Ayam Gedangan</td>
                                    @for ($day = 1; $day <= 31; $day++)
                                        <td></td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Hasan Susanto</td>
                                    @for ($day = 1; $day <= 31; $day++)
                                        <td></td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Pepet Siebor</td>
                                    @for ($day = 1; $day <= 31; $day++)
                                        <td></td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Mie Ayam Gedangan</td>
                                    @for ($day = 1; $day <= 31; $day++)
                                        <td></td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>Hasan Susanto</td>
                                    @for ($day = 1; $day <= 31; $day++)
                                        <td></td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>Mie Ayam Gedangan</td>
                                    @for ($day = 1; $day <= 31; $day++)
                                        <td></td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>10</td>
                                    <td>Hasan Susanto</td>
                                    @for ($day = 1; $day <= 31; $day++)
                                        <td></td>
                                    @endfor
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif

                <p class="notes-text">Catatan: Jika pegawai izin/alfa/sakit harap tambahkan keterangan pada kolom terakhir.</p>
            </section>
        </main>
    </div>

    <script>
        const baseSheetReportUrl = "{{ url('/sheet-report') }}";
        const selectedEmployeeKey = @json($selectedEmployeeKey);
        const reportViewSelect = document.getElementById('reportViewSelect');
        const employeeSelect = document.getElementById('employeeSelect');

        if (reportViewSelect) {
            reportViewSelect.addEventListener('change', function () {
                const viewValue = this.value;
                let targetUrl = `${baseSheetReportUrl}?type=${viewValue}`;

                if (viewValue === 'custom' && selectedEmployeeKey) {
                    targetUrl += `&employee=${encodeURIComponent(selectedEmployeeKey)}`;
                }

                window.location.href = targetUrl;
            });
        }

        if (employeeSelect) {
            employeeSelect.addEventListener('change', function () {
                const targetUrl = `${baseSheetReportUrl}?type=custom&employee=${encodeURIComponent(this.value)}`;
                window.location.href = targetUrl;
            });
        }
    </script>
</body>
</html>
