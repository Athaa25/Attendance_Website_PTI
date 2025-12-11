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

            <div class="table-wrapper">
                <table class="department-table">
                    <thead>
                        <tr>
                            <th>Departemen</th>
                            <th>Jabatan</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($positions ?? [] as $item)
                            <tr>
                                <td class="department-name">{{ optional($item->department)->name ?? 'Tanpa Departemen' }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <div class="actions" style="justify-content: flex-end;">
                                        <a class="icon-button edit" href="{{ route('departments.edit', $item) }}" title="Edit">
                                            <img src="{{ asset('images/edit-icon.png') }}" alt="Edit department">
                                        </a>
                                        <form class="inline-form" action="{{ route('departments.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="icon-button delete" type="submit" title="Delete">
                                                <img src="{{ asset('images/delete-icon.png') }}" alt="Delete department">
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 24px; color: var(--text-muted);">
                                    Belum ada data departemen dan jabatan yang tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
