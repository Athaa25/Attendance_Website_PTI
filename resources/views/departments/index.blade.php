@extends('layouts.dashboard')

@section('title', 'Departemen & Jabatan')
@section('page-title', 'Departemen & Jabatan')
@section('page-subtitle', 'Kelola struktur organisasi perusahaan Anda')

@push('styles')
    <style>
        .department-card {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .department-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .department-title {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--blue-primary);
        }

        .department-subtitle {
            margin: 6px 0 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .department-actions {
            display: flex;
            gap: 16px;
        }

        .department-search {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 16px 20px;
            background: rgba(17, 43, 105, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 20px;
        }

        .department-search-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-muted);
        }

        .search-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .department-search-input {
            width: 100%;
            padding: 12px 44px 12px 44px;
            border-radius: 14px;
            border: 1px solid var(--border-color);
            background: #FFFFFF;
            font-family: inherit;
            font-size: 14px;
            color: var(--text-dark);
            box-shadow: 0 12px 36px rgba(17, 43, 105, 0.08);
        }

        .department-search-input:focus {
            outline: 2px solid rgba(17, 43, 105, 0.2);
        }

        .search-icon {
            position: absolute;
            left: 14px;
            width: 18px;
            height: 18px;
            color: var(--text-muted);
            opacity: 0.8;
        }

        .search-clear {
            position: absolute;
            right: 10px;
            background: rgba(17, 43, 105, 0.08);
            border: 1px solid var(--border-color);
            border-radius: 999px;
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--blue-primary);
            cursor: pointer;
        }

        .search-hint {
            color: var(--text-muted);
            font-size: 12px;
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

        .table-wrapper {
            overflow-x: auto;
            border-radius: 24px;
            border: 1px solid var(--border-color);
            max-height: calc(100vh - 360px);
            overflow-y: auto;
        }

        .department-name {
            color: var(--blue-primary);
            font-weight: 600;
        }

        .department-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
        }

        .department-table thead {
            background-color: rgba(17, 43, 105, 0.04);
        }

        .department-table th {
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            color: var(--text-muted);
            padding: 16px 24px;
            letter-spacing: 0.01em;
        }

        .department-table td {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-color);
            font-weight: 500;
        }

        .department-table tbody tr:last-child td {
            border-bottom: none;
        }

        .department-table tbody tr:hover {
            background-color: rgba(17, 43, 105, 0.04);
        }
    </style>
@endpush

@section('content')
    <section class="content-wrapper">
        <div class="department-card">
            <div class="department-header">
                <div>
                    <h2 class="department-title">Departemen & Jabatan</h2>
                    <p class="department-subtitle">Kelola struktur organisasi perusahaan Anda</p>
                </div>
                <div class="department-actions">
                    <a class="btn btn-primary" href="{{ route('departments.create') }}">
                        Tambah
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="status-banner">
                    {{ session('success') }}
                </div>
            @endif

            <div class="department-search" data-department-search-form role="search" aria-label="Pencarian departemen dan jabatan">
                <span class="department-search-label">Cari Departemen / Jabatan</span>
                <div class="search-input-wrapper">
                    <svg class="search-icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor" d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 1 0-.92.92l.27.28v.79l4.25 4.25a1 1 0 0 0 1.42-1.42L15.5 14Zm-6 0A4.5 4.5 0 1 1 14 9.5 4.5 4.5 0 0 1 9.5 14Z"/>
                    </svg>
                    <input
                        type="search"
                        id="department-search"
                        class="department-search-input"
                        placeholder="Ketik nama departemen atau jabatan"
                        autocomplete="off"
                        data-department-search
                    >
                    <button type="button" class="search-clear" data-clear-search aria-label="Hapus pencarian">&times;</button>
                    <div class="search-suggestions" data-department-suggestions></div>
                </div>
                <div class="search-hint">Ketik untuk melihat rekomendasi. Klik rekomendasi untuk memfilter tanpa memuat ulang halaman.</div>
            </div>

            <div class="table-wrapper">
                <table class="department-table">
                    <thead>
                        <tr>
                            <th>Departemen</th>
                            <th>Jabatan</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="department-table-body" data-department-rows>
                        @include('departments.partials.positions', ['positions' => $positions])
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.querySelector('[data-department-search]');
            const suggestionsBox = document.querySelector('[data-department-suggestions]');
            const tableBody = document.querySelector('[data-department-rows]');
            const clearButton = document.querySelector('[data-clear-search]');

            if (!searchInput || !suggestionsBox || !tableBody) return;

            const defaultRows = tableBody.innerHTML;
            let debounceId = null;
            let controller = null;

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
                    title.textContent = item.position || item.label || 'Tanpa Nama';

                    const subtitle = document.createElement('span');
                    subtitle.className = 'suggestion-subtitle';
                    subtitle.textContent = item.department || 'Tanpa Departemen';

                    button.appendChild(title);
                    button.appendChild(subtitle);
                    suggestionsBox.appendChild(button);
                });

                suggestionsBox.dataset.hasContent = 'true';
                suggestionsBox.classList.add('open');
            };

            const applyResults = (payload, term) => {
                if (payload && typeof payload.html === 'string') {
                    tableBody.innerHTML = payload.html;
                }

                renderSuggestions(payload?.suggestions || [], term);
            };

            const fetchResults = (term) => {
                if (controller) {
                    controller.abort();
                }

                controller = new AbortController();

                const url = new URL('{{ route('departments.search') }}', window.location.origin);
                url.searchParams.set('q', term);

                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    signal: controller.signal,
                })
                    .then((response) => (response.ok ? response.json() : Promise.reject(response)))
                    .then((payload) => applyResults(payload, term))
                    .catch((error) => {
                        if (error.name === 'AbortError') return;
                        console.error('Gagal memuat hasil pencarian departemen', error);
                    });
            };

            const handleInput = () => {
                const term = searchInput.value.trim();
                clearTimeout(debounceId);

                debounceId = setTimeout(() => {
                    if (term === '') {
                        tableBody.innerHTML = defaultRows;
                        renderSuggestions([], '');
                        return;
                    }

                    fetchResults(term);
                }, 220);
            };

            searchInput.addEventListener('input', handleInput);

            // Blok aksi Enter agar tidak memicu submit form bawaan halaman
            searchInput.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === 'Search') {
                    event.preventDefault();
                    handleInput();
                    hideSuggestions();
                }
            });

            searchInput.addEventListener('search', () => {
                handleInput();
            });

            searchInput.addEventListener('focus', () => {
                if (searchInput.value.trim() !== '' && suggestionsBox.dataset.hasContent === 'true') {
                    suggestionsBox.classList.add('open');
                }
            });

            suggestionsBox.addEventListener('click', (event) => {
                const target = event.target.closest('.suggestion-item');
                if (!target) return;

                const term = target.dataset.term || target.textContent.trim();
                searchInput.value = term;
                handleInput();
                hideSuggestions();
            });

            clearButton?.addEventListener('click', () => {
                searchInput.value = '';
                tableBody.innerHTML = defaultRows;
                renderSuggestions([], '');
                searchInput.focus();
            });

            document.addEventListener('click', (event) => {
                if (!suggestionsBox.contains(event.target) && !searchInput.contains(event.target)) {
                    hideSuggestions();
                }
            });
        });
    </script>
@endpush
