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
            gap: 12px;
        }

        .chart-bar {
            flex: 1;
            background: linear-gradient(180deg, rgba(17, 43, 105, 0.6), rgba(17, 43, 105, 0.2));
            border-radius: 12px 12px 4px 4px;
        }

        .chart-bar:nth-child(1) { height: 40%; }
        .chart-bar:nth-child(2) { height: 70%; }
        .chart-bar:nth-child(3) { height: 55%; }
        .chart-bar:nth-child(4) { height: 80%; }
        .chart-bar:nth-child(5) { height: 65%; }

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
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="sidebar-logo">
                <img src="{{ asset('images/RMDI_logo.png') }}" alt="RMDI Logo" width="120" height="60">
            </div>
            <div class="sidebar-nav">
                <div class="sidebar-nav-group">
                    <p class="sidebar-section-title">Menu</p>
                    <a href="{{ url('/dashboard') }}" class="sidebar-nav-item active">
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
                    <a href="{{ url('/sheet-report') }}" class="sidebar-nav-item">
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
                <div class="analysis-section">
                    <div>
                        <div class="section-header">
                            <div>
                                <h2 class="section-title">Analisis Metrik</h2>
                                <p class="section-subtitle">Kehadiran Bulan Ini</p>
                            </div>
                            <button class="primary-button">Laporan Detail</button>
                        </div>

                        <div class="metrics-grid">
                            <div class="metric-card">
                                <span class="metric-title">Persentase Kehadiran</span>
                                <p class="metric-value">1500</p>
                                <span class="metric-description">Absensi</span>
                            </div>
                            <div class="metric-card">
                                <span class="metric-title">Jumlah Keterlambatan</span>
                                <p class="metric-value">300</p>
                                <span class="metric-description">(45%)</span>
                            </div>
                            <div class="metric-card">
                                <span class="metric-title">Tingkat Kehadiran</span>
                                <p class="metric-value">80%</p>
                                <span class="metric-description">Status Absensi</span>
                            </div>
                        </div>
                    </div>

                    <div class="chart-card">
                        <h3 class="chart-title">Kehadiran Bulanan</h3>
                        <p class="chart-subtitle">Jumlah Kehadiran</p>
                        <div class="chart-bars">
                            <div class="chart-bar"></div>
                            <div class="chart-bar"></div>
                            <div class="chart-bar"></div>
                            <div class="chart-bar"></div>
                            <div class="chart-bar"></div>
                        </div>
                    </div>
                </div>

                <div class="attendance-section">
                    <div class="section-header">
                        <h2 class="section-title">Riwayat Absensi</h2>
                        <a href="{{ url('/daily-attendance') }}" class="primary-button">Lihat Detail</a>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Waktu</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Fefe Fifi Fufu Fafa</td>
                                <td>Hadir</td>
                                <td>09:54</td>
                                <td>Tepat waktu</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Reo Hiu</td>
                                <td>Hadir</td>
                                <td>09:54</td>
                                <td>Tepat waktu</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Pepet Siebor</td>
                                <td>Hadir</td>
                                <td>09:54</td>
                                <td>Tepat waktu</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Mie Ayam Gedangan</td>
                                <td>Hadir</td>
                                <td>09:54</td>
                                <td>Tepat waktu</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Hason Susanto</td>
                                <td>Hadir</td>
                                <td>09:54</td>
                                <td>Tepat waktu</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
