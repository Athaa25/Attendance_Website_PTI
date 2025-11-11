@extends('layouts.dashboard')

@section('title', 'Laporan Absensi')
@section('page-title', 'Laporan Absensi')
@section('page-subtitle', 'Pantau kehadiran karyawan berdasarkan rentang tanggal pilihan')

@push('styles')
    <style>
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .report-title-block {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .report-title {
            margin: 0;
            font-size: 26px;
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
            color: var(--text-dark);
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
            gap: 10px;
        }

        .btn-icon {
            width: 18px;
            height: 18px;
            object-fit: contain;
        }

        .report-filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: flex-end;
            padding: 24px;
            background-color: var(--highlight);
            border: 1px solid var(--border-color);
            border-radius: 24px;
        }

        .filter-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 180px;
        }

        .filter-field--wide {
            min-width: 220px;
        }

        .filter-field--view {
            min-width: 200px;
        }

        .filter-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            font-weight: 600;
        }

        .filter-control {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            background-color: #fff;
            min-height: 48px;
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

        .view-toggle {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: rgba(17, 43, 105, 0.08);
            border-radius: 14px;
            padding: 4px;
        }

        .view-option {
            border: none;
            background: transparent;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 12px;
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
            width: 18px;
            height: 18px;
        }

        .view-option.active {
            background-color: #fff;
            color: var(--blue-primary);
            box-shadow: 0 8px 20px rgba(17, 43, 105, 0.15);
        }

        .view-option:not(.active):hover {
            color: var(--blue-primary);
        }

        .report-filter-actions {
            margin-left: auto;
            display: flex;
            align-items: center;
        }

        .btn-filter {
            padding: 12px 28px;
        }

        .employee-cell {
            display: flex;
            flex-direction: column;
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
            overflow-x: auto;
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
        .matrix-table thead th:nth-child(2) {
            text-align: left;
        }

        .matrix-table thead th:nth-child(n + 3) {
            min-width: 44px;
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
            min-width: 44px;
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
        }

        .detail-table thead th {
            background-color: rgba(17, 43, 105, 0.05);
            border-bottom: 1px solid var(--border-color);
        }

        .detail-table tbody td {
            border-top: 1px solid var(--border-color);
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
                flex-direction: column;
                align-items: stretch;
            }

            .report-filter-actions {
                margin-left: 0;
                width: 100%;
                display: flex;
                justify-content: flex-end;
            }
        }
    </style>
@endpush

@section('content')
    <section class="content-wrapper">
        <div class="report-header">
            <div class="report-title-block">
                <h2 class="report-title">Rekapan</h2>
                <p class="report-subtitle">Pantau kehadiran karyawan berdasarkan rentang tanggal dan nama yang dipilih.</p>
                <p class="report-period">
                    Periode: {{ $startDate->translatedFormat('d F Y') }} &ndash; {{ $endDate->translatedFormat('d F Y') }}
                </p>
            </div>
            <button type="button" class="btn btn-primary report-export" id="export-pdf-button">
                <img src="{{ asset('images/file-download-icon.png') }}" alt="Download" class="btn-icon">
                Export PDF
            </button>
        </div>

        <form method="GET" class="report-filter-form" id="report-filter-form">
            @if (request()->has('type'))
                <input type="hidden" name="type" value="{{ request('type') }}">
            @endif
            <input type="hidden" name="view" id="view-mode" value="{{ $viewMode }}">

            <div class="filter-field filter-field--wide">
                <span class="filter-label">Nama</span>
                <div class="filter-control">
                    <select name="employee_id" id="employee_id">
                        <option value="all" {{ $selectedEmployeeId === 'all' ? 'selected' : '' }}>Semua</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" {{ (int) $selectedEmployeeId === $employee->id ? 'selected' : '' }}>
                                {{ $employee->full_name }}
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

            <div class="report-filter-actions">
                <button type="submit" class="btn btn-primary btn-filter">Filter</button>
            </div>
        </form>

        @if ($viewMode === 'summary')
            <div class="report-printable">
                <div class="report-print-meta">
                    <span><strong>Periode:</strong> {{ $startDate->translatedFormat('d F Y') }} &ndash; {{ $endDate->translatedFormat('d F Y') }}</span>
                    <span><strong>Nama:</strong> {{ $selectedEmployee?->full_name ?? 'Semua Karyawan' }}</span>
                </div>
                <div class="summary-wrapper">
                    <div class="legend">
                        <span><span class="legend-badge present">H</span> Hadir</span>
                        <span><span class="legend-badge late">T</span> Terlambat</span>
                        <span><span class="legend-badge leave">I</span> Izin</span>
                        <span><span class="legend-badge sick">S</span> Sakit</span>
                        <span><span class="legend-badge absent">A</span> Alpa</span>
                    </div>

                    <div class="table-scroll">
                        <table class="matrix-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    @foreach ($dateRange as $date)
                                        <th>{{ $date->format('j') }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($summaryMatrix as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="employee-cell">
                                                <span class="employee-name">{{ $row['employee']->full_name }}</span>
                                                <span class="employee-department">{{ $row['employee']->department->name ?? '—' }}</span>
                                            </div>
                                        </td>
                                        @foreach ($dateRange as $date)
                                            @php($key = $date->format('Y-m-d'))
                                            @php($symbol = $row['days'][$key] ?? '')
                                            <td class="matrix-cell">{{ $symbol }}</td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($dateRange) + 2 }}" class="empty-state">
                                            Tidak ada data absensi pada rentang tanggal ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="report-printable">
                <div class="report-print-meta">
                    <span><strong>Periode:</strong> {{ $startDate->translatedFormat('d F Y') }} &ndash; {{ $endDate->translatedFormat('d F Y') }}</span>
                    <span><strong>Nama:</strong> {{ $selectedEmployee?->full_name ?? 'Semua Karyawan' }}</span>
                </div>
                <div class="table-scroll">
                    <table class="detail-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Departemen</th>
                                <th>Tanggal</th>
                                <th>Check-In</th>
                                <th>Check-Out</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($records as $record)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $record->employee->full_name }}</td>
                                    <td>{{ $record->employee->department->name ?? '—' }}</td>
                                    <td>{{ $record->attendance_date->translatedFormat('d F Y') }}</td>
                                    <td>{{ optional($record->check_in_time)->format('H:i') ?? '--:--' }}</td>
                                    <td>{{ optional($record->check_out_time)->format('H:i') ?? '--:--' }}</td>
                                    <td>{{ $record->notes ?? '—' }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $record->status }}">{{ $statusLabels[$record->status] ?? $record->status_label }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="empty-state">
                                        Tidak ada data absensi pada rentang tanggal ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const exportButton = document.getElementById('export-pdf-button');

            if (exportButton) {
                exportButton.addEventListener('click', function () {
                    window.print();
                });
            }

            const form = document.getElementById('report-filter-form');
            const viewInput = document.getElementById('view-mode');
            const viewButtons = document.querySelectorAll('[data-view-option]');

            if (!form || !viewInput || !viewButtons.length) {
                return;
            }

            viewButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const targetView = button.getAttribute('data-view-option');

                    if (!targetView) {
                        return;
                    }

                    viewInput.value = targetView;

                    viewButtons.forEach(function (btn) {
                        btn.classList.toggle('active', btn === button);
                    });

                    form.submit();
                });
            });
        });
    </script>
@endpush
