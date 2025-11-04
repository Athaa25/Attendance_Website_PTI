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
])

<section class="content-wrapper">
    @if (session('status'))
        <div class="status-banner">
            {{ session('status') }}
        </div>
    @endif

    @if ($page === 'list')
        <form method="GET" class="filter-bar">
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
                <div class="filter-input">
                    <input
                        type="search"
                        id="search"
                        name="search"
                        placeholder="Nama atau kode pegawai"
                        value="{{ $filters['search'] ?? '' }}"
                    >
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-secondary">Terapkan</button>
            </div>
        </form>

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
                        <td>{{ $record->employee->department->name ?? '—' }}</td>
                        <td>
                            <span class="status-badge status-{{ $record->status }}">
                                {{ $record->status_label }}
                            </span>
                        </td>
                        <td>{{ optional($record->check_in_time)->format('H:i') ?? '--:--' }}</td>
                        <td>{{ $record->notes ?? '—' }}</td>
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

        <form method="POST" action="{{ route('attendance.update', $record) }}">
            @csrf
            @method('PUT')
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
