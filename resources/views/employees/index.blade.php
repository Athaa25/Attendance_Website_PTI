@extends('layouts.dashboard')

@section('title', 'Kelola Pegawai')
@section('page-title', 'Kelola Pegawai')
@section('page-subtitle', 'Kelola data pegawai dan akses sistem perusahaan')

@push('styles')
    <style>
        .table-card {
            border-radius: 24px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            background-color: var(--card-background);
            max-height: calc(100vh - 360px);
            overflow-y: auto;
        }

        .table-card table {
            width: 100%;
            border-collapse: collapse;
            min-width: 760px;
        }

        .table-card thead {
            background-color: rgba(17, 43, 105, 0.05);
        }

        .table-card th {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            padding: 16px;
            text-align: left;
        }

        .table-card td {
            padding: 16px;
            border-top: 1px solid var(--border-color);
            font-size: 14px;
        }

        .table-card tr:nth-child(even) td {
            background-color: rgba(17, 43, 105, 0.02);
        }

        .table-name-link {
            color: var(--blue-primary);
            font-weight: 600;
        }

        .table-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .table-actions form {
            margin: 0;
        }

        .action-link {
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 10px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }

        .action-link.detail {
            background-color: rgba(17, 43, 105, 0.08);
            color: var(--blue-primary);
        }

        .action-link.edit {
            background-color: rgba(59, 130, 246, 0.12);
            color: #1d4ed8;
        }

        .action-link.delete {
            background-color: rgba(239, 68, 68, 0.12);
            color: #b91c1c;
        }

        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            border-top: 1px solid var(--border-color);
            font-size: 14px;
            gap: 12px;
            flex-wrap: wrap;
        }

        .pagination-controls {
            display: flex;
            gap: 12px;
        }

        .pagination-button {
            padding: 8px 14px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background-color: var(--card-background);
            color: var(--text-dark);
            font-weight: 500;
        }

        .pagination-button.disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        .employee-search-wrapper {
            position: relative;
            width: 100%;
        }

        .employee-search-input {
            width: 100%;
        }

        .employee-search-clear {
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
    </style>
@endpush

@section('content')
    <section class="content-wrapper">
        @if (session('status'))
            <div class="status-banner">
                {{ session('status') }}
            </div>
        @endif

        <form method="GET" class="filter-bar" data-employee-filter>
            <div class="filter-group">
                <label for="search">Pencarian</label>
                <div class="filter-input employee-search-wrapper">
                    <input
                        type="search"
                        id="search"
                        name="search"
                        class="employee-search-input"
                        placeholder="Nama atau kode pegawai"
                        autocomplete="off"
                        value="{{ $filters['search'] ?? '' }}"
                        data-employee-search
                    >
                    <button type="button" class="employee-search-clear" data-employee-clear aria-label="Hapus pencarian">&times;</button>
                    <div class="search-suggestions" data-employee-suggestions></div>
                </div>
            </div>
            <div class="filter-group">
                <label for="department_id">Departemen</label>
                <div class="filter-input">
                    <select id="department_id" name="department_id">
                        <option value="">Semua</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{ ($filters['department_id'] ?? null) == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="filter-group">
                <label for="status">Status</label>
                <div class="filter-input">
                    <select id="status" name="status">
                        <option value="">Semua</option>
                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ ($filters['status'] ?? '') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-secondary">Terapkan</button>
                <a href="{{ route('manage-users.create') }}" class="btn btn-primary">
                    <span>+</span> Tambah Pegawai
                </a>
            </div>
        
</form>

        <div data-employee-table>
            @include('employees.partials.table', ['employees' => $employees])
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('[data-employee-filter]');
            const searchInput = document.querySelector('[data-employee-search]');
            const suggestionsBox = document.querySelector('[data-employee-suggestions]');
            const clearButton = document.querySelector('[data-employee-clear]');
            const tableContainer = document.querySelector('[data-employee-table]');

            if (!form || !searchInput || !suggestionsBox || !tableContainer) return;

            let debounceId = null;
            let controller = null;
            const defaultTableHtml = tableContainer.innerHTML;

            const hideSuggestions = () => {
                suggestionsBox.classList.remove('open');
            };

            const renderSuggestions = (items, term) => {
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
                    subtitle.textContent = item.department || item.position || 'Tanpa Departemen';

                    button.appendChild(title);
                    button.appendChild(subtitle);
                    suggestionsBox.appendChild(button);
                });

                suggestionsBox.dataset.hasContent = 'true';
                suggestionsBox.classList.add('open');
            };

            const buildSearchParams = (overrideUrl) => {
                const formData = new FormData(form);
                const url = new URL(overrideUrl || '{{ route('manage-users.search') }}', window.location.origin);
                for (const [key, value] of formData.entries()) {
                    url.searchParams.set(key, value.toString());
                }
                return url;
            };

            const fetchResults = (url) => {
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
                            tableContainer.innerHTML = payload.html;
                        }
                        renderSuggestions(payload?.suggestions || [], searchInput.value.trim());
                    })
                    .catch((error) => {
                        if (error.name === 'AbortError') return;
                        console.error('Gagal memuat data pegawai', error);
                    });
            };

            const handleSearchInput = () => {
                clearTimeout(debounceId);
                const term = searchInput.value.trim();

                debounceId = setTimeout(() => {
                    if (term === '') {
                        renderSuggestions([], '');
                        fetchResults(buildSearchParams());
                        return;
                    }

                    fetchResults(buildSearchParams());
                }, 220);
            };

            form.addEventListener('submit', (event) => {
                event.preventDefault();
                const url = buildSearchParams();
                fetchResults(url);
                hideSuggestions();
            });

            ['change'].forEach((evt) => {
                form.addEventListener(evt, (event) => {
                    const target = event.target;
                    if (target?.name && target.name !== 'search') {
                        const url = buildSearchParams();
                        fetchResults(url);
                    }
                });
            });

            searchInput.addEventListener('input', handleSearchInput);

            searchInput.addEventListener('focus', () => {
                if (searchInput.value.trim() !== '' && suggestionsBox.dataset.hasContent === 'true') {
                    suggestionsBox.classList.add('open');
                }
            });

            searchInput.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === 'Search') {
                    event.preventDefault();
                    const url = buildSearchParams();
                    fetchResults(url);
                    hideSuggestions();
                }
            });

            searchInput.addEventListener('search', () => {
                const url = buildSearchParams();
                fetchResults(url);
            });

            suggestionsBox.addEventListener('click', (event) => {
                const target = event.target.closest('.suggestion-item');
                if (!target) return;

                searchInput.value = target.dataset.term || target.textContent.trim();
                const url = buildSearchParams();
                fetchResults(url);
                hideSuggestions();
            });

            clearButton?.addEventListener('click', () => {
                searchInput.value = '';
                renderSuggestions([], '');
                fetchResults(buildSearchParams());
                searchInput.focus();
            });

            document.addEventListener('click', (event) => {
                if (!suggestionsBox.contains(event.target) && !searchInput.contains(event.target)) {
                    hideSuggestions();
                }
            });

            tableContainer.addEventListener('click', (event) => {
                const link = event.target.closest('[data-pagination-link]');
                if (!link) return;

                event.preventDefault();
                const href = link.getAttribute('href');
                if (!href || href === '#') return;
                const url = new URL(href, window.location.origin);
                const formData = new FormData(form);
                formData.forEach((value, key) => url.searchParams.set(key, value.toString()));
                fetchResults(url);
            });
        });
    </script>
@endpush
