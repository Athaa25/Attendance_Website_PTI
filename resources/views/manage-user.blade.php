<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php($page = $page ?? 'list')
    <title>
        @switch($page)
            @case('add')Add User@break
            @case('edit')Edit User@break
            @case('view')Lihat Data Pegawai@break
            @default Manage User
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
            --danger: #BC0000;
            --info: #2B6CB0;
            --accent: #BC0000;
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
            box-shadow: 0 10px 40px rgba(17, 43, 105, 0.06);
        }

        .top-header-title {
            font-size: 28px;
            font-weight: 600;
            margin: 0 0 8px 0;
        }

        .top-header-subtitle {
            margin: 0;
            color: var(--text-muted);
        }

        .profile-info {
            display: flex;
            align-items: center;
            gap: 16px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--highlight);
            display: grid;
            place-items: center;
            font-weight: 600;
            color: var(--blue-primary);
        }

        .content-wrapper {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* Manage user table */
        .manage-user-layout {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .table-card {
            background-color: var(--card-background);
            border-radius: 28px;
            padding: 32px;
            box-shadow: 0 20px 45px rgba(17, 43, 105, 0.08);
        }

        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
        }

        .table-title {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }

        .table-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .search-input {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            background-color: var(--highlight);
            border-radius: 16px;
            border: 1px solid var(--border-color);
        }

        .search-input input {
            border: none;
            background: transparent;
            outline: none;
            font-family: inherit;
        }

        .add-button {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 12px 24px;
            border-radius: 16px;
            background-color: var(--blue-primary);
            color: #FFFFFF;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .add-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 15px 30px rgba(17, 43, 105, 0.12);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-responsive {
            overflow: hidden;
            border-radius: 16px;
        }

        thead {
            background-color: var(--highlight);
        }

        th,
        td {
            padding: 14px 16px;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:nth-child(even) {
            background-color: rgba(17, 43, 105, 0.04);
        }

        tbody tr:hover {
            background-color: rgba(17, 43, 105, 0.08);
        }

        .table-name-link {
            color: var(--blue-primary);
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .table-name-link:hover {
            color: #0b1d49;
        }

        .table-actions-buttons {
            display: flex;
            gap: 12px;
        }

        .icon-button {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
        }

        .icon-button img {
            width: 20px;
            height: 20px;
        }

        .icon-button.info {
            background-color: rgba(43, 108, 176, 0.12);
            color: var(--info);
        }

        .icon-button.danger {
            background-color: rgba(245, 101, 101, 0.12);
            color: var(--danger);
        }

        /* Form styles */
        .form-card {
            background-color: var(--card-background);
            border-radius: 28px;
            padding: 32px;
            box-shadow: 0 20px 45px rgba(17, 43, 105, 0.08);
        }

        .form-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
        }

        .form-title {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            color: var(--blue-primary);
        }

        .form-subtitle {
            margin: 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-weight: 500;
            color: var(--text-dark);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 14px 16px;
            border-radius: 14px;
            border: 1px solid var(--border-color);
            background-color: var(--highlight);
            font-family: inherit;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--blue-primary);
            box-shadow: 0 0 0 3px rgba(17, 43, 105, 0.12);
        }

        .radio-group {
            display: flex;
            align-items: center;
            gap: 24px;
            background-color: var(--highlight);
            border-radius: 14px;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .radio-option input {
            width: 18px;
            height: 18px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 16px;
        }

        .button {
            padding: 14px 28px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .button:focus-visible {
            outline: 2px solid rgba(17, 43, 105, 0.4);
            outline-offset: 2px;
        }

        .button-cancel {
            background-color: var(--danger);
            color: #FFFFFF;
        }

        .button-submit {
            background-color: var(--blue-primary);
            color: #FFFFFF;
        }

        .button:hover {
            transform: translateY(-1px);
            box-shadow: 0 15px 30px rgba(17, 43, 105, 0.12);
        }

        /* Detail styles */
        .detail-card {
            background-color: var(--card-background);
            border-radius: 28px;
            padding: 32px;
            box-shadow: 0 20px 45px rgba(17, 43, 105, 0.08);
        }

        .detail-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .detail-title {
            font-size: 26px;
            font-weight: 600;
            color: var(--blue-primary);
            margin: 0;
        }

        .detail-body {
            display: grid;
            grid-template-columns: 220px 1fr;
            gap: 40px;
            align-items: flex-start;
        }

        .employee-photo {
            width: 220px;
            height: 260px;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(17, 43, 105, 0.12);
        }

        .employee-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(220px, 1fr));
            gap: 16px 32px;
        }

        .detail-item {
            display: flex;
            gap: 12px;
        }

        .detail-label {
            width: 160px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .detail-value {
            flex: 1;
            color: var(--text-dark);
        }

        .detail-footer {
            display: flex;
            justify-content: flex-end;
            gap: 16px;
            margin-top: 32px;
        }

        .btn {
            padding: 12px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            border: none;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn:active {
            transform: scale(0.98);
        }

        .btn-secondary {
            background-color: var(--highlight);
            color: var(--blue-primary);
            box-shadow: 0 10px 20px rgba(17, 43, 105, 0.12);
        }

        .btn-primary {
            background-color: var(--accent);
            color: #fff;
            box-shadow: 0 12px 30px rgba(188, 0, 0, 0.25);
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

            .detail-body {
                grid-template-columns: 1fr;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .employee-photo {
                margin: 0 auto;
            }
        }

        @media (max-width: 1024px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .top-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .table-actions {
                width: 100%;
                justify-content: space-between;
                flex-wrap: wrap;
            }

            .search-input {
                flex: 1;
            }

            .search-input input {
                width: 100%;
            }

            .detail-card {
                padding: 24px;
            }

            .detail-footer {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .btn {
                width: 100%;
                text-align: center;
            }

            .detail-label {
                width: 140px;
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
                    <a href="{{ url('/manage-users') }}" class="sidebar-nav-item {{ $page === 'list' ? 'active' : '' }}">
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
                @if($page === 'list')
                    <div class="manage-user-layout">
                        <div class="table-card">
                            <div class="table-header">
                                <h2 class="table-title">Manage user</h2>
                                <div class="table-actions">
                                    <div class="search-input">
                                        <span>🔍</span>
                                        <input type="search" placeholder="Search">
                                    </div>
                                    <a href="{{ url('/manage-users/add') }}" class="add-button">
                                        <span>+</span>
                                        <span>Add</span>
                                    </a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Departemen</th>
                                            <th>Schedule</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td><a class="table-name-link" href="{{ url('/manage-users/view') }}">Fefe Fifi Fufu Fafa</a></td>
                                            <td>HRD</td>
                                            <td>Shift+1</td>
                                            <td>
                                                <div class="table-actions-buttons">
                                                    <a class="icon-button info" href="{{ url('/manage-users/edit') }}" aria-label="Edit user">
                                                        <img src="{{ asset('images/edit-icon.png') }}" alt="Edit icon" aria-hidden="true">
                                                    </a>
                                                    <button class="icon-button danger" type="button" aria-label="Delete user">
                                                        <img src="{{ asset('images/delete-icon.png') }}" alt="Delete icon" aria-hidden="true">
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td><a class="table-name-link" href="{{ url('/manage-users/view') }}">Ria Hiu</a></td>
                                            <td>Finance</td>
                                            <td>Shift+1</td>
                                            <td>
                                                <div class="table-actions-buttons">
                                                    <a class="icon-button info" href="{{ url('/manage-users/edit') }}" aria-label="Edit user">
                                                        <img src="{{ asset('images/edit-icon.png') }}" alt="Edit icon" aria-hidden="true">
                                                    </a>
                                                    <button class="icon-button danger" type="button" aria-label="Delete user">
                                                        <img src="{{ asset('images/delete-icon.png') }}" alt="Delete icon" aria-hidden="true">
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td><a class="table-name-link" href="{{ url('/manage-users/view') }}">Pepet Siebor</a></td>
                                            <td>IT</td>
                                            <td>Shift+2</td>
                                            <td>
                                                <div class="table-actions-buttons">
                                                    <a class="icon-button info" href="{{ url('/manage-users/edit') }}" aria-label="Edit user">
                                                        <img src="{{ asset('images/edit-icon.png') }}" alt="Edit icon" aria-hidden="true">
                                                    </a>
                                                    <button class="icon-button danger" type="button" aria-label="Delete user">
                                                        <img src="{{ asset('images/delete-icon.png') }}" alt="Delete icon" aria-hidden="true">
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td><a class="table-name-link" href="{{ url('/manage-users/view') }}">Mie Ayam Gedangan</a></td>
                                            <td>IT</td>
                                            <td>Shift+3</td>
                                            <td>
                                                <div class="table-actions-buttons">
                                                    <a class="icon-button info" href="{{ url('/manage-users/edit') }}" aria-label="Edit user">
                                                        <img src="{{ asset('images/edit-icon.png') }}" alt="Edit icon" aria-hidden="true">
                                                    </a>
                                                    <button class="icon-button danger" type="button" aria-label="Delete user">
                                                        <img src="{{ asset('images/delete-icon.png') }}" alt="Delete icon" aria-hidden="true">
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td><a class="table-name-link" href="{{ url('/manage-users/view') }}">Hasan Susanto</a></td>
                                            <td>Gudang</td>
                                            <td>Shift+1</td>
                                            <td>
                                                <div class="table-actions-buttons">
                                                    <a class="icon-button info" href="{{ url('/manage-users/edit') }}" aria-label="Edit user">
                                                        <img src="{{ asset('images/edit-icon.png') }}" alt="Edit icon" aria-hidden="true">
                                                    </a>
                                                    <button class="icon-button danger" type="button" aria-label="Delete user">
                                                        <img src="{{ asset('images/delete-icon.png') }}" alt="Delete icon" aria-hidden="true">
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td><a class="table-name-link" href="{{ url('/manage-users/view') }}">Hasan Susanto</a></td>
                                            <td>Gudang</td>
                                            <td>Shift+2</td>
                                            <td>
                                                <div class="table-actions-buttons">
                                                    <a class="icon-button info" href="{{ url('/manage-users/edit') }}" aria-label="Edit user">
                                                        <img src="{{ asset('images/edit-icon.png') }}" alt="Edit icon" aria-hidden="true">
                                                    </a>
                                                    <button class="icon-button danger" type="button" aria-label="Delete user">
                                                        <img src="{{ asset('images/delete-icon.png') }}" alt="Delete icon" aria-hidden="true">
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td><a class="table-name-link" href="{{ url('/manage-users/view') }}">Hasan Susanto</a></td>
                                            <td>Gudang</td>
                                            <td>Shift+3</td>
                                            <td>
                                                <div class="table-actions-buttons">
                                                    <a class="icon-button info" href="{{ url('/manage-users/edit') }}" aria-label="Edit user">
                                                        <img src="{{ asset('images/edit-icon.png') }}" alt="Edit icon" aria-hidden="true">
                                                    </a>
                                                    <button class="icon-button danger" type="button" aria-label="Delete user">
                                                        <img src="{{ asset('images/delete-icon.png') }}" alt="Delete icon" aria-hidden="true">
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td><a class="table-name-link" href="{{ url('/manage-users/view') }}">Hasan Susanto</a></td>
                                            <td>Gudang</td>
                                            <td>Shift+4</td>
                                            <td>
                                                <div class="table-actions-buttons">
                                                    <a class="icon-button info" href="{{ url('/manage-users/edit') }}" aria-label="Edit user">
                                                        <img src="{{ asset('images/edit-icon.png') }}" alt="Edit icon" aria-hidden="true">
                                                    </a>
                                                    <button class="icon-button danger" type="button" aria-label="Delete user">
                                                        <img src="{{ asset('images/delete-icon.png') }}" alt="Delete icon" aria-hidden="true">
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @elseif($page === 'add')
                    <div class="form-card">
                        <div class="form-header">
                            <h2 class="form-title">Add user/pegawai</h2>
                        </div>
                        <form>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" placeholder="Maria Laksomatri Gacor">
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="tel" id="phone" name="phone" placeholder="+628123456789">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" placeholder="Historia@gmail.com">
                                </div>
                                <div class="form-group">
                                    <label for="nip">NIP</label>
                                    <input type="text" id="nip" name="nip" placeholder="20200962038136">
                                </div>
                                <div class="form-group">
                                    <label for="nik">NIK</label>
                                    <input type="text" id="nik" name="nik" placeholder="35717234562948827">
                                </div>
                                <div class="form-group">
                                    <label for="dob">Date Of Birth</label>
                                    <input type="date" id="dob" name="dob" value="1927-07-21">
                                </div>
                                <div class="form-group">
                                    <label>Jenis Kelamin</label>
                                    <div class="radio-group">
                                        <label class="radio-option" for="gender-male">
                                            <input type="radio" id="gender-male" name="gender" value="male">
                                            Laki-laki
                                        </label>
                                        <label class="radio-option" for="gender-female">
                                            <input type="radio" id="gender-female" name="gender" value="female" checked>
                                            Perempuan
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="department">Departemen</label>
                                    <select id="department" name="department">
                                        <option value="finance" selected>Finance</option>
                                        <option value="hrd">HRD</option>
                                        <option value="marketing">Marketing</option>
                                        <option value="it">IT</option>
                                    </select>
                                </div>
                                <div class="form-group" style="grid-column: span 2;">
                                    <label for="address">Address</label>
                                    <textarea id="address" name="address" placeholder="Jl. Mawar no. 5, Surabaya"></textarea>
                                </div>
                            </div>

                            <div class="form-actions">
                                <a href="{{ url('/manage-users') }}" class="button button-cancel">Cancel</a>
                                <button type="submit" class="button button-submit">Submit</button>
                            </div>
                        </form>
                    </div>
                @elseif($page === 'edit')
                    <div class="form-card">
                        <div class="form-header">
                            <div>
                                <h2 class="form-title">Edit user/pegawai</h2>
                                <p class="form-subtitle">Perbarui data pegawai dengan mudah dan pastikan informasi tetap akurat.</p>
                            </div>
                        </div>
                        <form>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" value="Fefe Maret">
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="tel" id="phone" name="phone" value="+6284569568789">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" value="MaretHibet@gmail.com">
                                </div>
                                <div class="form-group">
                                    <label for="nik">NIK</label>
                                    <input type="text" id="nik" name="nik" value="35717234562948827">
                                </div>
                                <div class="form-group">
                                    <label>Jenis Kelamin</label>
                                    <div class="radio-group">
                                        <label class="radio-option" for="gender-male">
                                            <input type="radio" id="gender-male" name="gender" value="male">
                                            Laki-laki
                                        </label>
                                        <label class="radio-option" for="gender-female">
                                            <input type="radio" id="gender-female" name="gender" value="female" checked>
                                            Perempuan
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dob">Date Of Birth</label>
                                    <input type="date" id="dob" name="dob" value="2004-07-27">
                                </div>
                                <div class="form-group">
                                    <label for="department">Departemen</label>
                                    <select id="department" name="department">
                                        <option value="finance">Finance</option>
                                        <option value="hrd" selected>HRD</option>
                                        <option value="marketing">Marketing</option>
                                        <option value="it">IT</option>
                                    </select>
                                </div>
                                <div class="form-group" style="grid-column: span 2;">
                                    <label for="address">Address</label>
                                    <textarea id="address" name="address">Gedangon, rumah pisang</textarea>
                                </div>
                            </div>

                            <div class="form-actions">
                                <a href="{{ url('/manage-users/view') }}" class="button button-cancel">Cancel</a>
                                <button type="submit" class="button button-submit">Edit</button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="detail-card">
                        <div class="detail-header">
                            <h2 class="detail-title">Lihat Data Pegawai</h2>
                        </div>
                        <div class="detail-body">
                            <div class="employee-photo">
                                <img src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=400&q=80" alt="Foto Pegawai">
                            </div>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <div class="detail-label">Nama Lengkap</div>
                                    <div class="detail-value">Kim Kim Kim</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Level</div>
                                    <div class="detail-value">Staff</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Jenis Kelamin</div>
                                    <div class="detail-value">Laki laki</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Gaji</div>
                                    <div class="detail-value">Rp 5.000.000</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Tempat, Tanggal Lahir</div>
                                    <div class="detail-value">Wonosobo, 1 Juni 2023</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Jabatan</div>
                                    <div class="detail-value">Pengawas</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">No HP</div>
                                    <div class="detail-value">08123456788999</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Alamat</div>
                                    <div class="detail-value">rt 1 rw 1 Wonosobo, Jawa Tengah</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Masa Kerja</div>
                                    <div class="detail-value">1 Tahun</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Masa Tanggung Kerja</div>
                                    <div class="detail-value">24 Januari 2024</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Penempatan</div>
                                    <div class="detail-value">Dept. BLOCKBOARD</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Schedule</div>
                                    <div class="detail-value">Shift-1</div>
                                </div>
                            </div>
                        </div>
                        <div class="detail-footer">
                            <a class="btn btn-secondary" href="/manage-users">Cancel</a>
                            <a class="btn btn-primary" href="{{ url('/manage-users/edit') }}">Edit</a>
                        </div>
                    </div>
                @endif
            </section>
        </main>
    </div>
</body>
</html>
