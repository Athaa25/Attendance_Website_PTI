<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php($page = $page ?? 'list')
    <title>
        @switch($page)
            @case('edit')Edit Absensi@break
            @default Daily Attendance
        @endswitch
    </title>
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
            --success: #16A34A;
            --warning: #F59E0B;
            --danger: #EF4444;
            --maroon: #590815;
            --navy: #0C1C47;
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
        }

        .content-title {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            color: var(--blue-primary);
        }

        .edit-button {
            background-color: var(--blue-primary);
            color: #FFFFFF;
            border: none;
            border-radius: 12px;
            padding: 10px 24px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .edit-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(17, 43, 105, 0.2);
        }

        .attendance-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background-color: var(--card-background);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(17, 43, 105, 0.06);
        }

        .attendance-table thead {
            background-color: var(--highlight);
        }

        .attendance-table th {
            text-align: left;
            padding: 18px 24px;
            font-weight: 600;
            font-size: 14px;
            color: var(--text-muted);
        }

        .attendance-table tbody tr {
            border-bottom: 1px solid var(--border-color);
        }

        .attendance-table tbody tr:last-child {
            border-bottom: none;
        }

        .attendance-table td {
            padding: 18px 24px;
            font-size: 14px;
            color: var(--text-dark);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 13px;
        }

        .status-present {
            background-color: rgba(22, 163, 74, 0.12);
            color: var(--success);
        }

        .status-late {
            background-color: rgba(245, 158, 11, 0.12);
            color: var(--warning);
        }

        .status-absent {
            background-color: rgba(239, 68, 68, 0.12);
            color: var(--danger);
        }

        .empty-time {
            color: var(--text-muted);
            font-style: italic;
        }

        .attendance-edit {
            scroll-margin-top: 120px;
        }

        .attendance-edit form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .attendance-edit .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
        }

        .attendance-edit .form-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .attendance-edit label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 14px;
        }

        .attendance-edit select,
        .attendance-edit input[type="text"],
        .attendance-edit input[type="time"],
        .attendance-edit textarea {
            width: 100%;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 14px 16px;
            font-size: 14px;
            font-family: inherit;
            background-color: var(--highlight);
            color: var(--text-dark);
        }

        .attendance-edit select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none' stroke='%236F6F6F'%3E%3Cpath d='M6 8l4 4 4-4' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 18px;
            padding-right: 48px;
        }

        .attendance-edit input[type="text"],
        .attendance-edit input[type="time"] {
            appearance: none;
        }

        .attendance-edit textarea {
            min-height: 140px;
            resize: vertical;
        }

        .attendance-edit .status-toggle {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .attendance-edit .status-option {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .attendance-edit .status-option input {
            display: none;
        }

        .attendance-edit .status-indicator {
            width: 42px;
            height: 24px;
            border-radius: 999px;
            background-color: #CBD5F5;
            position: relative;
            transition: background-color 0.2s ease;
        }

        .attendance-edit .status-indicator::after {
            content: "";
            position: absolute;
            top: 4px;
            left: 4px;
            width: 16px;
            height: 16px;
            background-color: var(--blue-primary);
            border-radius: 50%;
            transition: transform 0.2s ease;
        }

        .attendance-edit .status-option input:checked + .status-indicator::after {
            transform: translateX(18px);
        }

        .attendance-edit .status-option input[value="hadir"]:checked + .status-indicator {
            background-color: rgba(17, 43, 105, 0.2);
        }

        .attendance-edit .status-option input[value="izin"]:checked + .status-indicator {
            background-color: rgba(189, 28, 37, 0.2);
        }

        .attendance-edit .status-option input[value="izin"]:checked + .status-indicator::after {
            background-color: var(--danger);
        }

        .attendance-edit .status-label {
            font-weight: 600;
            color: var(--text-dark);
        }

        .attendance-edit .button-row {
            display: flex;
            justify-content: flex-end;
            gap: 16px;
        }

        .attendance-edit .btn {
            border: none;
            border-radius: 14px;
            padding: 14px 32px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .attendance-edit .btn:focus {
            outline: none;
        }

        .attendance-edit .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(17, 43, 105, 0.12);
        }

        .attendance-edit .btn-cancel {
            background: linear-gradient(90deg, var(--maroon), var(--danger));
            color: #FFFFFF;
        }

        .attendance-edit .btn-submit {
            background: linear-gradient(90deg, var(--blue-primary), var(--navy));
            color: #FFFFFF;
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

            .attendance-edit .form-grid {
                grid-template-columns: 1fr;
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

            .content-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .attendance-table {
                display: block;
                overflow-x: auto;
            }

            .attendance-edit .button-row {
                flex-direction: column;
                align-items: stretch;
            }

            .attendance-edit .btn {
                width: 100%;
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
                    <a href="{{ url('/daily-attendance') }}" class="sidebar-nav-item {{ in_array($page, ['list', 'edit']) ? 'active' : '' }}">
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

            <section class="content-wrapper {{ $page === 'edit' ? 'attendance-edit' : '' }}">
                @if($page === 'edit')
                    <div class="content-header">
                        <h2 class="content-title">Edit Absensi</h2>
                        <a href="{{ url('/daily-attendance') }}" class="edit-button">Kembali</a>
                    </div>

                    <form>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="employee-name">Name</label>
                                <select id="employee-name" name="employee-name">
                                    <option selected>Fefe Fifi Fufu Fafa</option>
                                    <option>Rio Hu</option>
                                    <option>Pepet Siebor</option>
                                    <option>Mie Ayam Gedang</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="attendance-date">Tanggal</label>
                                <input type="text" id="attendance-date" value="30 September 2025">
                            </div>
                            <div class="form-group">
                                <label for="clock-in">Clock in</label>
                                <input type="time" id="clock-in" value="09:46">
                            </div>
                            <div class="form-group">
                                <label for="clock-out">Clock out</label>
                                <input type="time" id="clock-out" value="16:19">
                            </div>
                            <div class="form-group">
                                <label for="shift">Shift</label>
                                <select id="shift" name="shift">
                                    <option selected>Shift-1</option>
                                    <option>Shift-2</option>
                                    <option>Shift-3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <div class="status-toggle">
                                    <label class="status-option">
                                        <input type="radio" name="status" value="hadir" checked>
                                        <span class="status-indicator"></span>
                                        <span class="status-label">Hadir</span>
                                    </label>
                                    <label class="status-option">
                                        <input type="radio" name="status" value="izin">
                                        <span class="status-indicator"></span>
                                        <span class="status-label">Izin</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea id="description" placeholder="Deskripsi izin..."></textarea>
                        </div>

                        <div class="button-row">
                            <a href="{{ url('/daily-attendance') }}" class="btn btn-cancel">Batal</a>
                            <button type="submit" class="btn btn-submit">Simpan Perubahan</button>
                        </div>
                    </form>
                @else
                    <div class="content-header">
                        <h2 class="content-title">Kehadiran</h2>
                        <a href="{{ url('/daily-attendance/edit') }}" class="edit-button">Edit</a>
                    </div>

                    <table class="attendance-table">
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
                            <tr>
                                <td>1</td>
                                <td>Fefe Fifi Fufu Fafa</td>
                                <td><span class="status-badge status-present">Hadir</span></td>
                                <td>09:54</td>
                                <td>30 September 2025</td>
                                <td>Tepat waktu</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Rio Hu</td>
                                <td><span class="status-badge status-present">Hadir</span></td>
                                <td>09:54</td>
                                <td>30 September 2025</td>
                                <td>Tepat waktu</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Pepet Siebor</td>
                                <td><span class="status-badge status-late">Telat</span></td>
                                <td>09:54</td>
                                <td>30 September 2025</td>
                                <td>Telat</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Mie Ayam Gedang</td>
                                <td><span class="status-badge status-present">Hadir</span></td>
                                <td>09:54</td>
                                <td>30 September 2025</td>
                                <td>Tepat waktu</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Fefe Fifi Fufu Fafa</td>
                                <td><span class="status-badge status-present">Hadir</span></td>
                                <td>09:54</td>
                                <td>30 September 2025</td>
                                <td>Tepat waktu</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Pepet Siebor</td>
                                <td><span class="status-badge status-late">Telat</span></td>
                                <td>09:54</td>
                                <td>30 September 2025</td>
                                <td>Telat</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Mie Ayam Gedangan</td>
                                <td><span class="status-badge status-present">Hadir</span></td>
                                <td>09:54</td>
                                <td>30 September 2025</td>
                                <td>Tepat waktu</td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Fefe Fifi Fufu Fafa</td>
                                <td><span class="status-badge status-present">Hadir</span></td>
                                <td>09:54</td>
                                <td>30 September 2025</td>
                                <td>Tepat waktu</td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Rio Hu</td>
                                <td><span class="status-badge status-absent">Izin</span></td>
                                <td class="empty-time">--:--</td>
                                <td>30 September 2025</td>
                                <td>Tidak hadir</td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Hasan Susanto</td>
                                <td><span class="status-badge status-absent">Izin</span></td>
                                <td class="empty-time">--:--</td>
                                <td>30 September 2025</td>
                                <td>Tidak hadir</td>
                            </tr>
                        </tbody>
                    </table>
                @endif
            </section>
        </main>
    </div>
</body>
</html>
