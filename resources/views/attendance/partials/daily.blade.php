@php($page = $page ?? 'list')
@php($attendanceDate = $attendanceDate ?? now())
@php($records = $records ?? collect())
@php($filters = $filters ?? [])
@php($statusOptions = $statusOptions ?? \App\Models\AttendanceRecord::statusLabels())
@php($summary = $summary ?? [
    'total_employees' => $records->count(),
    'present' => 0,
    'late' => 0,
    'leave' => 0,
    'sick' => 0,
    'absent' => 0,
    'other' => 0,
])

@push('styles')
    <style>
        .attendance-search-wrapper {
            position: relative;
            width: 100%;
        }

        .attendance-search-clear {
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
    </style>
@endpush

<section class="content-wrapper">
    @if (session('status'))
        <div class="status-banner">
            {{ session('status') }}
        </div>
    @endif

    @if ($page === 'list')
        <form method="GET" class="filter-bar" id="attendance-filter-form" data-attendance-filter>
            <div class="filter-group">
                <label for="date">Tanggal</label>
                <div class="filter-input">
                    <input type="date" id="date" name="date" value="{{ $attendanceDate->format('Y-m-d') }}">
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
            <div class="filter-group">
                <label for="search">Pencarian</label>
                <div class="filter-input attendance-search-wrapper">
                    <input
                        type="search"
                        id="search"
                        name="search"
                        placeholder="Nama atau kode pegawai"
                        value="{{ $filters['search'] ?? '' }}"
                        autocomplete="off"
                        data-attendance-search
                    >
                    <button type="button" class="attendance-search-clear" data-attendance-clear aria-label="Hapus pencarian">&times;</button>
                    <div class="search-suggestions" data-attendance-suggestions></div>
                </div>
            </div>
        </form>

        <div data-attendance-results>
            @include('attendance.partials.list', [
                'records' => $records,
                'summary' => $summary,
            ])
        </div>
    @else
        <div class="form-header">
            <div>
                <h2 class="form-title">Edit Absensi Pegawai</h2>
                <p class="form-subtitle">
                    {{ $record->employee->full_name }} &middot;
                    {{ $attendanceDate->translatedFormat('d F Y') }}
                </p>
            </div>
            <a href="{{ route('attendance.index', ['date' => $attendanceDate->format('Y-m-d')]) }}" class="btn btn-secondary">Kembali</a>
        </div>

        <form method="POST" action="{{ route('attendance.update', $record) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="employee_id" value="{{ $record->employee_id }}">
            <input type="hidden" name="attendance_date" value="{{ $record->attendance_date->format('Y-m-d') }}">
            <div class="form-grid">
                <div class="form-group">
                    <label for="status">Status Kehadiran</label>
                    <select id="status" name="status" class="form-control" required>
                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $record->status) === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="check_in_time">Jam Masuk</label>
                    <input
                        id="check_in_time"
                        name="check_in_time"
                        type="time"
                        class="form-control"
                        value="{{ old('check_in_time', optional($record->check_in_time)->format('H:i')) }}"
                    >
                    <p class="helper-text">
                        Jadwal: {{ optional($record->employee->schedule)->start_time?->format('H:i') ?? '08:00' }}
                    </p>
                </div>
                <div class="form-group">
                    <label for="check_out_time">Jam Pulang</label>
                    <input
                        id="check_out_time"
                        name="check_out_time"
                        type="time"
                        class="form-control"
                        value="{{ old('check_out_time', optional($record->check_out_time)->format('H:i')) }}"
                    >
                </div>
                @if ($viewMode === 'edit')
                    <div class="form-inline-group">
                        <div class="form-group js-leave-reason-field">
                            <label for="leave_reason">Alasan</label>
                            <select id="leave_reason" name="leave_reason" class="form-control">
                                <option value="">Pilih alasan</option>
                                @foreach ($leaveReasonOptions as $value => $label)
                                    <option value="{{ $value }}" {{ old('leave_reason', $record->reasonDefinition->code ?? null) === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group js-supporting-document-field">
                            <label for="supporting_document">Dokumen Pendukung</label>
                            <input id="supporting_document" name="supporting_document" type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            @if ($record->supporting_document_url)
                                <p class="helper-text">
                                    <a href="{{ $record->supporting_document_url }}" target="_blank" rel="noopener">Lihat dokumen saat ini</a>
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
                <div class="form-group form-row-span">
                    <label for="notes">Keterangan</label>
                    <textarea id="notes" name="notes" class="form-control">{{ old('notes', $record->notes) }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('attendance.index', ['date' => $attendanceDate->format('Y-m-d')]) }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    @endif
</section>

@if ($page !== 'list')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const statusSelect = document.getElementById('status');
            const reasonGroup = document.querySelector('.js-leave-reason-field');
            const docGroup = document.querySelector('.js-supporting-document-field');
            const reasonSelect = document.getElementById('leave_reason');
            const docInput = document.getElementById('supporting_document');
            const PRESENT_STATUS = '{{ \App\Models\AttendanceRecord::STATUS_PRESENT }}';

            const toggleAdditionalFields = () => {
                if (!statusSelect || !reasonGroup || !docGroup) {
                    return;
                }

                const isPresent = statusSelect.value === PRESENT_STATUS;
                reasonGroup.style.display = isPresent ? 'none' : '';
                docGroup.style.display = isPresent ? 'none' : '';

                if (reasonSelect) {
                    if (isPresent) {
                        reasonSelect.removeAttribute('required');
                    } else {
                        reasonSelect.setAttribute('required', 'required');
                    }
                }

                if (docInput) {
                    if (isPresent) {
                        docInput.removeAttribute('required');
                    } else {
                        docInput.setAttribute('required', 'required');
                    }
                }
            };

            toggleAdditionalFields();
            statusSelect?.addEventListener('change', toggleAdditionalFields);
        });
    </script>
@else
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('[data-attendance-filter]');
            const searchInput = document.querySelector('[data-attendance-search]');
            const suggestionsBox = document.querySelector('[data-attendance-suggestions]');
            const clearButton = document.querySelector('[data-attendance-clear]');
            const resultsContainer = document.querySelector('[data-attendance-results]');

            if (!form || !resultsContainer) return;

            let debounceId = null;
            let controller = null;
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
                const url = new URL('{{ route('attendance.search') }}', window.location.origin);
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
                        console.error('Gagal memuat data absensi', error);
                    });
            };

            const handleInput = () => {
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
                if (event.target?.name && event.target.name !== 'search') {
                    fetchResults();
                }
            });

            searchInput?.addEventListener('input', handleInput);
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
        });
    </script>
@endif
