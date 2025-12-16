@php
    $computedSubjectLabel = $searchName !== ''
        ? 'Nama mengandung "' . $searchName . '"'
        : (($selectedDepartmentId ?? null) ? optional($departments->firstWhere('id', $selectedDepartmentId))->name : null);
    $computedSubjectLabel = $computedSubjectLabel
        ?? (($selectedPositionId ?? null) ? optional($positions->firstWhere('id', $selectedPositionId))->name : null)
        ?? 'Semua Karyawan';
    $selectedSubjectLabel = $selectedSubjectLabel ?? $computedSubjectLabel;
@endphp

@if ($viewMode === 'summary')
    <div class="report-printable">
        <div class="report-print-meta">
            <span><strong>Periode:</strong> {{ $startDate->translatedFormat('d F Y') }} &ndash; {{ $endDate->translatedFormat('d F Y') }}</span>
            <span><strong>Filter:</strong> {{ $selectedSubjectLabel }}</span>
        </div>
        <div class="summary-wrapper">
            <div class="legend">
                <span><span class="legend-badge present">H</span> Hadir</span>
                <span><span class="legend-badge late">T</span> Terlambat</span>
                <span><span class="legend-badge leave">I</span> Izin</span>
                <span><span class="legend-badge sick">S</span> Sakit</span>
                <span><span class="legend-badge absent">A</span> Alpa</span>
            </div>

            <div class="matrix-vertical">
                <div class="table-scroll matrix-horizontal drag-scroll" data-drag-scroll>
                    <table class="matrix-table">
                        <thead>
                            <tr>
                                <th class="sticky-col" style="width: 70px;">No</th>
                                <th class="sticky-col name-col">Nama</th>
                                @foreach ($dateRange as $date)
                                    <th>{{ $date->format('j') }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($summaryMatrix as $row)
                                <tr>
                                    <td class="sticky-col">{{ $loop->iteration }}</td>
                                    <td class="sticky-col name-col">
                                        <div class="employee-cell">
                                            <span class="employee-name">{{ $row['employee']->full_name }}</span>
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
    </div>
@else
    <div class="report-printable">
        <div class="report-print-meta">
            <span><strong>Periode:</strong> {{ $startDate->translatedFormat('d F Y') }} &ndash; {{ $endDate->translatedFormat('d F Y') }}</span>
            <span><strong>Filter:</strong> {{ $selectedSubjectLabel }}</span>
        </div>
        <div class="matrix-vertical">
            <div class="table-scroll matrix-horizontal drag-scroll" data-drag-scroll>
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th class="sticky-col" style="width: 70px;">No</th>
                            <th class="sticky-col name-col">Nama</th>
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
                                <td class="sticky-col">{{ $loop->iteration }}</td>
                                <td class="sticky-col name-col">{{ $record->employee->full_name }}</td>
                                <td>{{ $record->employee->department->name ?? '-' }}</td>
                                <td>{{ $record->attendance_date->translatedFormat('d F Y') }}</td>
                                <td>{{ optional($record->check_in_time)->format('H:i') ?? '--:--' }}</td>
                                <td>{{ optional($record->check_out_time)->format('H:i') ?? '--:--' }}</td>
                                <td>{{ $record->notes ?? '-' }}</td>
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
    </div>
@endif
