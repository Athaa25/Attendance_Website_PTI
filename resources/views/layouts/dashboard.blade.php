<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin')</title>
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
            transition: width 0.25s ease, padding 0.25s ease, margin 0.25s ease;
            max-height: calc(100vh - 64px);
            overflow-y: auto;
            overscroll-behavior: contain;
        }

        .sidebar-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
        }

        .sidebar-logo-img {
            max-width: 160px;
            width: 100%;
            height: auto;
            object-fit: contain;
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

        .sidebar-icon {
            width: 20px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            background-color: rgba(17, 43, 105, 0.08);
            color: var(--blue-primary);
            font-size: 12px;
            font-weight: 700;
        }

        .sidebar::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(17, 43, 105, 0.18);
            border-radius: 999px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(17, 43, 105, 0.28);
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

        .top-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .sidebar-toggle {
            display: inline-flex;
            flex-direction: column;
            gap: 5px;
            padding: 10px;
            border-radius: 14px;
            border: 1px solid var(--border-color);
            background-color: #fff;
            cursor: pointer;
            transition: background-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .sidebar-toggle span {
            display: block;
            width: 20px;
            height: 2px;
            background-color: var(--blue-primary);
            border-radius: 999px;
        }

        .sidebar-toggle:hover,
        .sidebar-toggle:focus-visible {
            background-color: rgba(17, 43, 105, 0.06);
            box-shadow: 0 6px 16px rgba(17, 43, 105, 0.12);
            transform: translateY(-1px);
        }

        .topbar-logo {
            display: none;
            height: 36px;
            width: auto;
            object-fit: contain;
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

        .status-banner {
            padding: 14px 20px;
            border-radius: 14px;
            background-color: rgba(17, 43, 105, 0.08);
            color: var(--blue-primary);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .filter-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-group label {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .filter-input {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background-color: var(--highlight);
        }

        .filter-input input,
        .filter-input select {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            background-color: var(--highlight);
            font-family: inherit;
            font-size: 14px;
            color: var(--text-dark);
        }

        .filter-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease, color 0.2s ease;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--blue-primary);
            color: #FFFFFF;
            box-shadow: 0 8px 20px rgba(17, 43, 105, 0.18);
        }

        .btn-primary:hover,
        .btn-primary:focus-visible {
            background-color: #0d2254;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: rgba(17, 43, 105, 0.08);
            color: var(--blue-primary);
        }

        .btn-secondary:hover,
        .btn-secondary:focus-visible {
            background-color: rgba(17, 43, 105, 0.12);
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 16px;
        }

        .summary-card {
            background-color: var(--highlight);
            border-radius: 20px;
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .summary-label {
            font-size: 13px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .summary-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
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

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 18px;
        }

        .form-inline-group {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 18px;
        }

        .form-row-span {
            grid-column: 1 / -1;
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

        .helper-text {
            font-size: 12px;
            color: var(--text-muted);
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-top: 16px;
        }

        .alert-error {
            background-color: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #991b1b;
            border-radius: 16px;
            padding: 18px 20px;
        }

        .alert-list {
            margin: 12px 0 0;
            padding-left: 18px;
        }

        .error-message {
            color: var(--danger);
            font-size: 13px;
            font-weight: 500;
        }

        .inline-form {
            display: inline-flex;
            margin: 0;
        }

        .required-indicator {
            color: var(--danger);
        }

        .schedule-header,
        .table-header,
        .detail-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .schedule-title,
        .detail-title {
            margin: 0;
            font-size: 22px;
            color: var(--blue-primary);
        }

        .schedule-subtitle {
            margin-top: 6px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .schedule-actions,
        .detail-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .table-wrapper {
            overflow: auto;
            max-height: calc(100vh - 320px);
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .icon-button {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            background-color: rgba(17, 43, 105, 0.08);
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .icon-button img {
            width: 18px;
            height: 18px;
        }

        .icon-button.edit {
            background-color: rgba(59, 130, 246, 0.12);
        }

        .icon-button.delete {
            background-color: rgba(239, 68, 68, 0.12);
        }

        .icon-button:hover,
        .icon-button:focus-visible {
            transform: translateY(-1px);
        }

        .empty-state {
            padding: 28px;
            text-align: center;
            color: var(--text-muted);
            font-size: 14px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
        }

        .detail-card {
            background-color: var(--highlight);
            border-radius: 20px;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .detail-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .detail-value {
            font-size: 15px;
            color: var(--text-dark);
        }

        .detail-actions {
            margin-top: 24px;
            justify-content: flex-end;
        }

        .status-present {
            background-color: rgba(34, 197, 94, 0.18);
            color: #15803d;
        }

        .status-late {
            background-color: rgba(234, 179, 8, 0.18);
            color: #b45309;
        }

        .status-leave,
        .status-sick {
            background-color: rgba(59, 130, 246, 0.18);
            color: #1d4ed8;
        }

        .status-absent {
            background-color: rgba(239, 68, 68, 0.18);
            color: #b91c1c;
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

        body.sidebar-collapsed .sidebar {
            width: 0;
            padding: 0;
            margin: 0;
            border: none;
            box-shadow: none;
            overflow: hidden;
            min-height: 0;
        }

        body.sidebar-collapsed .sidebar-logo,
        body.sidebar-collapsed .sidebar-nav,
        body.sidebar-collapsed .sidebar-footer {
            display: none;
        }

        body.sidebar-collapsed .dashboard-layout {
            gap: 16px;
        }

        body.sidebar-collapsed .topbar-logo {
            display: block;
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

            .filter-actions,
            .form-actions,
            .detail-actions {
                flex-direction: column;
                align-items: stretch;
            }
        }

        @media (max-width: 992px) {
            .dashboard-layout {
                padding: 20px;
                gap: 16px;
            }

            .sidebar {
                padding: 16px;
                gap: 16px;
                flex-wrap: wrap;
                border-radius: 24px;
            }

            .sidebar-logo {
                width: 100%;
                margin-bottom: 4px;
            }

            .sidebar-logo-img {
                max-width: 120px;
            }

            .sidebar-nav {
                width: 100%;
                gap: 10px;
                row-gap: 12px;
            }

            .sidebar-nav-group {
                margin-bottom: 0;
                display: contents;
            }

            .sidebar-section-title {
                display: none;
            }

            .sidebar-nav-item {
                flex: 1 1 calc(50% - 10px);
                min-width: 140px;
                padding: 10px 12px;
                gap: 10px;
            }

            .sidebar-footer {
                width: 100%;
                border-left: 0;
                border-top: 1px solid var(--border-color);
                padding-left: 0;
                margin-top: 8px;
            }
        }

        @media (max-width: 640px) {
            .dashboard-layout {
                padding: 16px;
            }

            .sidebar {
                border-radius: 20px;
                align-items: flex-start;
            }

            .sidebar-nav-item {
                flex: 1 1 calc(50% - 8px);
                padding: 8px 10px;
                gap: 8px;
                font-size: 13px;
            }

            .sidebar-nav-item img {
                width: 18px;
                height: 18px;
            }
        }
    </style>
    @stack('styles')
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

        $pageTitle = trim($__env->yieldContent('page-title'));
        $pageTitle = $pageTitle !== '' ? $pageTitle : null;
        $pageSubtitle = trim($__env->yieldContent('page-subtitle'));
        $pageSubtitle = $pageSubtitle !== '' ? $pageSubtitle : null;
    @endphp

    <div class="dashboard-layout">
        @include('partials.dashboard.sidebar')

        <main class="main-content">
            @include('partials.dashboard.topbar', [
                'user' => $user,
                'userInitials' => $userInitials,
                'now' => $now ?? now(),
                'title' => $pageTitle,
                'subtitle' => $pageSubtitle,
            ])

            @yield('content')
        </main>
    </div>

    <script>
        (() => {
            const toggleButton = document.querySelector('[data-sidebar-toggle]');
            const body = document.body;
            const storageKey = 'dashboardSidebarCollapsed';

            if (!toggleButton) return;

            const applyState = (collapsed) => {
                body.classList.toggle('sidebar-collapsed', collapsed);
                toggleButton.setAttribute('aria-expanded', (!collapsed).toString());
            };

            const saved = localStorage.getItem(storageKey) === 'true';
            applyState(saved);

            toggleButton.addEventListener('click', () => {
                const next = !body.classList.contains('sidebar-collapsed');
                applyState(next);
                localStorage.setItem(storageKey, next);
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
