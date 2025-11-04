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
        }

        .table-card table {
            width: 100%;
            border-collapse: collapse;
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

        <form method="GET" class="filter-bar">
            <div class="filter-group">
                <label for="search">Pencarian</label>
                <div class="filter-input">
                    <input type="search" id="search" name="search" placeholder="Nama atau kode pegawai" value="{{ $filters['search'] ?? '' }}">
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

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Departemen</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td>{{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration }}</td>
                            <td>
                                <a class="table-name-link" href="{{ route('manage-users.show', $employee) }}">
                                    {{ $employee->full_name }}
                                </a>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    {{ $employee->user->email }}
                                </div>
                            </td>
                            <td>{{ $employee->department->name ?? '—' }}</td>
                            <td>{{ $employee->schedule->name ?? '—' }}</td>
                            <td>
                                <span class="status-badge status-{{ $employee->employment_status }}">
                                    {{ $employee->employment_status_label }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a class="action-link detail" href="{{ route('manage-users.show', $employee) }}">Detail</a>
                                    <a class="action-link edit" href="{{ route('manage-users.edit', $employee) }}">Edit</a>
                                    <form method="POST" action="{{ route('manage-users.destroy', $employee) }}" onsubmit="return confirm('Hapus pegawai ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-link delete">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 24px; color: var(--text-muted);">
                                Belum ada data pegawai yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($employees->hasPages())
                <div class="pagination">
                    <span>Halaman {{ $employees->currentPage() }} dari {{ $employees->lastPage() }}</span>
                    <div class="pagination-controls">
                        <a class="pagination-button {{ $employees->onFirstPage() ? 'disabled' : '' }}" href="{{ $employees->previousPageUrl() ?? '#' }}">Sebelumnya</a>
                        <a class="pagination-button {{ $employees->hasMorePages() ? '' : 'disabled' }}" href="{{ $employees->nextPageUrl() ?? '#' }}">Selanjutnya</a>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
