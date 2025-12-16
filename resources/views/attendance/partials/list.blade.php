<div class="summary-grid">
    <div class="summary-card">
        <span class="summary-label">Total Pegawai</span>
        <span class="summary-value">{{ $summary['total_employees'] }}</span>
    </div>
    <div class="summary-card">
        <span class="summary-label">Hadir</span>
        <span class="summary-value">{{ $summary['present'] }}</span>
    </div>
    <div class="summary-card">
        <span class="summary-label">Terlambat</span>
        <span class="summary-value">{{ $summary['late'] }}</span>
    </div>
    <div class="summary-card">
        <span class="summary-label">Izin</span>
        <span class="summary-value">{{ $summary['leave'] }}</span>
    </div>
    <div class="summary-card">
        <span class="summary-label">Sakit</span>
        <span class="summary-value">{{ $summary['sick'] }}</span>
    </div>
    <div class="summary-card">
        <span class="summary-label">Alpa</span>
        <span class="summary-value">{{ $summary['absent'] }}</span>
    </div>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Departemen</th>
                <th>Status</th>
                <th>Check-In</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $record)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        {{ $record->employee->full_name }}
                        <div style="font-size: 12px; color: var(--text-muted);">
                            {{ $record->employee->employee_code }}
                        </div>
                    </td>
                    <td>{{ $record->employee->department->name ?? '-' }}</td>
                    <td>
                        <span class="status-badge status-{{ $record->statusDefinition?->code ?? $record->status }}">
                            {{ $record->status_label }}
                        </span>
                    </td>
                    <td>{{ optional($record->check_in_time)->format('H:i') ?? '--:--' }}</td>
                    <td>{{ $record->notes ?? $record->leave_reason_label ?? '-' }}</td>
                    <td>
                        <a class="btn btn-secondary" href="{{ route('attendance.edit', $record) }}">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 24px; color: var(--text-muted);">
                        Belum ada data absensi pada tanggal ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
