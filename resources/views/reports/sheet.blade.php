@extends('layouts.dashboard')

@section('title', 'Laporan Absensi')
@section('page-title', 'Laporan Absensi')
@section('page-subtitle', 'Pantau kehadiran karyawan berdasarkan rentang tanggal pilihan')

@push('styles')
    <style>
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 5;
            padding: 16px 0;
            background: linear-gradient(180deg, #f5f5f5 0%, rgba(245,245,245,0.85) 100%);
            backdrop-filter: blur(6px);
        }

        .report-title-block {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .report-title {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--blue-primary);
        }

        .report-subtitle {
            margin: 0;
            font-size: 14px;
            color: var(--text-muted);
        }

        .report-period {
            margin: 0;
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .report-print-meta {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .report-print-meta strong {
            color: var(--text-dark);
        }

        .report-export {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-icon {
            width: 16px;
            height: 16px;
            object-fit: contain;
        }

        .filter-card {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(17, 43, 105, 0.08);
            padding: 16px 20px 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .filter-card__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .filter-card__title {
            margin: 0;
            font-weight: 700;
            color: var(--blue-primary);
            font-size: 16px;
        }

        .filter-toggle {
            display: none;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background: #fff;
            cursor: pointer;
            font-weight: 600;
            color: var(--blue-primary);
        }

        .report-filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            align-items: end;
        }

        .filter-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .filter-field--view {
            min-width: 200px;
        }

        .filter-label {
            font-size: 12px;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            font-weight: 600;
        }

        .filter-control {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background-color: #fff;
            min-height: 44px;
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

        .report-search-wrapper {
            position: relative;
            width: 100%;
        }

        .report-search-clear {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(17, 43, 105, 0.08);
            border: 1px solid var(--border-color);
            border-radius: 999px;
            width: 26px;
            height: 26px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--blue-primary);
            cursor: pointer;
        }

        .search-suggestions {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: #FFFFFF;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            box-shadow: 0 18px 48px rgba(17, 43, 105, 0.16);
            padding: 8px 0;
            max-height: 320px;
            overflow-y: auto;
            display: none;
            z-index: 10;
        }

        .search-suggestions.open {
            display: block;
        }

        .suggestion-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
            width: 100%;
            text-align: left;
            padding: 10px 14px;
            background: transparent;
            border: none;
            cursor: pointer;
        }

        .suggestion-item:hover,
        .suggestion-item:focus-visible {
            background: rgba(17, 43, 105, 0.06);
        }

        .suggestion-title {
            font-weight: 600;
            color: var(--blue-primary);
        }

        .suggestion-subtitle {
            font-size: 12px;
            color: var(--text-muted);
        }

        .suggestion-empty {
            padding: 10px 14px;
            color: var(--text-muted);
            font-size: 13px;
        }

        .view-toggle {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: rgba(17, 43, 105, 0.08);
            border-radius: 12px;
            padding: 4px;
        }

        .view-option {
            border: none;
            background: transparent;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 10px;
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
            width: 16px;
            height: 16px;
        }

        .view-option.active {
            background-color: #fff;
            color: var(--blue-primary);
            box-shadow: 0 8px 20px rgba(17, 43, 105, 0.15);
        }

        .view-option:not(.active):hover {
            color: var(--blue-primary);
        }

        .filter-badge {
            display: inline-flex;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(17, 43, 105, 0.08);
            color: var(--blue-primary);
            font-weight: 600;
            font-size: 12px;
        }

        .employee-cell {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .employee-name {
            font-weight: 600;
        }

        .employee-department {
            font-size: 12px;
            color: var(--text-muted);
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
            overflow: auto;
            max-height: calc(100vh - 320px);
        }

        .matrix-vertical {
            max-height: calc(100vh - 260px);
            overflow-y: auto;
            position: relative;
        }

        .matrix-horizontal {
            display: block;
            overflow-x: auto !important;
            overflow-y: hidden !important;
        }

        .matrix-horizontal.table-scroll {
            overflow-x: auto !important;
            overflow-y: hidden !important;
        }

        .matrix-vertical::-webkit-scrollbar,
        .matrix-horizontal::-webkit-scrollbar {
            height: 10px;
            width: 10px;
        }

        .matrix-vertical::-webkit-scrollbar-thumb,
        .matrix-horizontal::-webkit-scrollbar-thumb {
            background: rgba(17, 43, 105, 0.25);
            border-radius: 999px;
        }

        .matrix-vertical::-webkit-scrollbar-track,
        .matrix-horizontal::-webkit-scrollbar-track {
            background: rgba(17, 43, 105, 0.05);
        }

        .drag-scroll {
            cursor: grab;
            user-select: none;
            -webkit-user-select: none;
        }

        .drag-scroll:active {
            cursor: grabbing;
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
            min-width: 1200px;
        }

        .matrix-table .sticky-col {
            position: sticky;
            left: 0;
            z-index: 3;
            background: #fff;
            box-shadow: 2px 0 6px rgba(0, 0, 0, 0.04);
        }

        .matrix-table .sticky-col.name-col {
            left: 70px;
            width: 220px;
            min-width: 220px;
            max-width: 260px;
            z-index: 3;
        }

        .matrix-table tbody .sticky-col {
            z-index: 2;
            background: #fff;
        }

        .matrix-table tbody tr:nth-child(even) .sticky-col {
            background: rgba(17, 43, 105, 0.02);
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
        .matrix-table tbody td:first-child {
            width: 70px;
            min-width: 70px;
            max-width: 80px;
        }

        .matrix-table thead th:first-child,
        .matrix-table thead th:nth-child(2) {
            text-align: left;
        }

        .matrix-table thead th:nth-child(n + 3) {
            min-width: 60px;
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
            min-width: 60px;
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
            border-collapse: separate;
            border-spacing: 0;
        }

        .detail-table thead th {
            background-color: rgba(17, 43, 105, 0.06);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 2;
            padding: 12px;
            font-size: 13px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .detail-table tbody td {
            border-top: 1px solid var(--border-color);
            padding: 12px;
            font-size: 14px;
        }

        .detail-table tr:nth-child(even) td {
            background: rgba(17, 43, 105, 0.02);
        }

        .col-date {
            width: 130px;
            white-space: nowrap;
        }

        .col-time {
            width: 90px;
            white-space: nowrap;
        }

        .status-badge {
            border-radius: 999px;
            padding: 6px 12px;
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

        .report-printable {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

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
            .report-filter-form {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }

            .filter-toggle {
                display: inline-flex;
            }

            .filter-card[data-collapsed="true"] .report-filter-form {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
    <section class="content-wrapper">
        <div class="report-header">
            <div class="report-title-block">
                <h2 class="report-title">Rekapan Absensi</h2>
                <p class="report-subtitle">Pantau kehadiran karyawan berdasarkan rentang tanggal dan filter pilihan.</p>
                <p class="report-period">
                    Periode: {{ $startDate->translatedFormat('d F Y') }} &ndash; {{ $endDate->translatedFormat('d F Y') }}
                </p>
            </div>
            <button type="button" class="btn btn-primary report-export" id="export-pdf-button">
                <img src="{{ asset('images/file-download-icon.png') }}" alt="Download" class="btn-icon">
                Export PDF
            </button>
        </div>

        <div class="filter-card" id="filter-card" data-collapsed="false">
            <div class="filter-card__header">
                <h3 class="filter-card__title">Filter</h3>
                <span class="filter-badge">Filter aktif: {{ $selectedSubjectLabel }}</span>
                <button type="button" class="filter-toggle" id="filter-toggle">Tampilkan/Sembunyikan</button>
            </div>

            <form method="GET" class="report-filter-form" id="report-filter-form" data-report-filter>
                @if (request()->has('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                <input type="hidden" name="view" id="view-mode" value="{{ $viewMode }}">

                <div class="filter-field filter-field--wide">
                    <span class="filter-label">Nama</span>
                    <div class="filter-control report-search-wrapper">
                        <input
                            type="search"
                            name="name"
                            id="employee_name"
                            placeholder="Ketik nama karyawan"
                            value="{{ $searchName }}"
                            autocomplete="off"
                            data-report-search
                        >
                        <button type="button" class="report-search-clear" data-report-clear aria-label="Hapus pencarian">&times;</button>
                        <div class="search-suggestions" data-report-suggestions></div>
                    </div>
                </div>

                <div class="filter-field filter-field--wide">
                    <span class="filter-label">Departemen</span>
                    <div class="filter-control">
                        <select name="department_id" id="department_id">
                            <option value="">Semua</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ ($selectedDepartmentId ?? null) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="filter-field filter-field--wide">
                    <span class="filter-label">Jabatan</span>
                    <div class="filter-control">
                        <select name="position_id" id="position_id">
                            <option value="">Semua</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position->id }}" {{ ($selectedPositionId ?? null) == $position->id ? 'selected' : '' }}>
                                    {{ $position->name }}
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
            </form>
        </div>

        <div data-report-results>
            @include('reports.partials.sheet-content')
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const exportButton = document.getElementById('export-pdf-button');
            const form = document.querySelector('[data-report-filter]');
            const viewInput = document.getElementById('view-mode');
            const viewButtons = document.querySelectorAll('[data-view-option]');
            const searchInput = document.querySelector('[data-report-search]');
            const suggestionsBox = document.querySelector('[data-report-suggestions]');
            const clearButton = document.querySelector('[data-report-clear]');
            const resultsContainer = document.querySelector('[data-report-results]');
            const filterCard = document.getElementById('filter-card');
            const filterToggle = document.getElementById('filter-toggle');

            if (exportButton) {
                exportButton.addEventListener('click', () => window.print());
            }

            if (!form || !resultsContainer || !viewInput || !viewButtons.length) {
                return;
            }

            let controller = null;
            let debounceId = null;
            const defaultHtml = resultsContainer.innerHTML;

            const hideSuggestions = () => suggestionsBox?.classList.remove('open');

            const renderSuggestions = (items, term) => {
                if (!suggestionsBox) return;
                suggestionsBox.innerHTML = '';

                if (!term) {
                    suggestionsBox.dataset.hasContent = 'false';
                    hideSuggestions();
                    return;
                }

                if (!items || items.length === 0) {
                    const empty = document.createElement('div');
                    empty.className = 'suggestion-empty';
                    empty.textContent = `Tidak ada rekomendasi untuk "${term}".`;
                    suggestionsBox.appendChild(empty);
                    suggestionsBox.dataset.hasContent = 'true';
                    suggestionsBox.classList.add('open');
                    return;
                }

                items.forEach((item) => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'suggestion-item';
                    button.dataset.term = item.term || item.label || '';

                    const title = document.createElement('span');
                    title.className = 'suggestion-title';
                    title.textContent = item.label || 'Tanpa Nama';

                    const subtitle = document.createElement('span');
                    subtitle.className = 'suggestion-subtitle';
                    subtitle.textContent = item.department || '';

                    button.appendChild(title);
                    button.appendChild(subtitle);
                    suggestionsBox.appendChild(button);
                });

                suggestionsBox.dataset.hasContent = 'true';
                suggestionsBox.classList.add('open');
            };

            const buildUrl = () => {
                const data = new FormData(form);
                const url = new URL('{{ route('reports.sheet.search') }}', window.location.origin);
                data.forEach((value, key) => url.searchParams.set(key, value.toString()));
                return url;
            };

            const fetchResults = () => {
                const url = buildUrl();
                if (controller) controller.abort();
                controller = new AbortController();

                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    signal: controller.signal,
                })
                    .then((response) => (response.ok ? response.json() : Promise.reject(response)))
                    .then((payload) => {
                        if (typeof payload?.html === 'string') {
                            resultsContainer.innerHTML = payload.html;
                        }
                        renderSuggestions(payload?.suggestions || [], searchInput?.value.trim() || '');
                    })
                    .catch((error) => {
                        if (error.name === 'AbortError') return;
                        console.error('Gagal memuat laporan', error);
                    });
            };

            const handleSearchInput = () => {
                clearTimeout(debounceId);
                debounceId = setTimeout(() => {
                    if (searchInput && searchInput.value.trim() === '') {
                        renderSuggestions([], '');
                        fetchResults();
                        return;
                    }
                    fetchResults();
                }, 220);
            };

            form.addEventListener('submit', (event) => {
                event.preventDefault();
                fetchResults();
                hideSuggestions();
            });

            form.addEventListener('change', (event) => {
                if (event.target?.name && event.target.name !== 'name') {
                    fetchResults();
                }
            });

            viewButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const targetView = button.getAttribute('data-view-option');
                    if (!targetView) return;

                    viewInput.value = targetView;
                    viewButtons.forEach((btn) => btn.classList.toggle('active', btn === button));
                    fetchResults();
                });
            });

            searchInput?.addEventListener('input', handleSearchInput);
            searchInput?.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === 'Search') {
                    event.preventDefault();
                    fetchResults();
                    hideSuggestions();
                }
            });
            searchInput?.addEventListener('focus', () => {
                if (searchInput.value.trim() !== '' && suggestionsBox?.dataset.hasContent === 'true') {
                    suggestionsBox.classList.add('open');
                }
            });
            searchInput?.addEventListener('search', fetchResults);

            suggestionsBox?.addEventListener('click', (event) => {
                const target = event.target.closest('.suggestion-item');
                if (!target || !searchInput) return;
                searchInput.value = target.dataset.term || target.textContent.trim();
                fetchResults();
                hideSuggestions();
            });

            clearButton?.addEventListener('click', () => {
                if (!searchInput) return;
                searchInput.value = '';
                renderSuggestions([], '');
                fetchResults();
                searchInput.focus();
            });

            document.addEventListener('click', (event) => {
                if (suggestionsBox && !suggestionsBox.contains(event.target) && !searchInput?.contains(event.target)) {
                    hideSuggestions();
                }
            });

            if (filterToggle && filterCard) {
                filterToggle.addEventListener('click', () => {
                    const isCollapsed = filterCard.getAttribute('data-collapsed') === 'true';
                    filterCard.setAttribute('data-collapsed', (!isCollapsed).toString());
                });
            }

            const enableDragScroll = (container) => {
                if (!container) return;
                let isDown = false;
                let startX = 0;
                let scrollLeft = 0;

                container.addEventListener('mousedown', (e) => {
                    isDown = true;
                    container.classList.add('dragging');
                    startX = e.pageX - container.offsetLeft;
                    scrollLeft = container.scrollLeft;
                });

                container.addEventListener('mouseleave', () => {
                    isDown = false;
                    container.classList.remove('dragging');
                });

                container.addEventListener('mouseup', () => {
                    isDown = false;
                    container.classList.remove('dragging');
                });

                container.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - container.offsetLeft;
                    const walk = (x - startX) * -1;
                    container.scrollLeft = scrollLeft + walk;
                });
            };

            document.querySelectorAll('[data-drag-scroll]').forEach(enableDragScroll);
        });
    </script>
@endpush
