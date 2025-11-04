@extends('layouts.dashboard')

@section('title', 'Detail Pegawai')
@section('page-title', 'Detail Pegawai')
@section('page-subtitle', 'Informasi lengkap pegawai dan status kepegawaiannya')

@section('content')
    <section class="content-wrapper">
        <div class="detail-header">
            <div>
                <h2 class="detail-title">{{ $employee->full_name }}</h2>
                <div style="color: var(--text-muted); font-size: 14px;">
                    {{ $employee->position->name ?? 'Jabatan belum diatur' }} &mdash; {{ $employee->department->name ?? 'Departemen belum diatur' }}
                </div>
            </div>
            <span class="status-badge status-{{ $employee->employment_status }}">{{ $employee->employment_status_label }}</span>
        </div>

        <div class="detail-grid">
            <div class="detail-card">
                <div class="detail-label">Email</div>
                <div class="detail-value">{{ $employee->user->email }}</div>

                <div class="detail-label">Email Kantor</div>
                <div class="detail-value">{{ $employee->work_email ?? '—' }}</div>

                <div class="detail-label">Nomor Telepon</div>
                <div class="detail-value">{{ $employee->phone ?? '—' }}</div>

                <div class="detail-label">Username</div>
                <div class="detail-value">{{ $employee->user->username }}</div>

                <div class="detail-label">Role Sistem</div>
                <div class="detail-value">{{ ucfirst($employee->user->role) }}</div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Kode Pegawai</div>
                <div class="detail-value">{{ $employee->employee_code }}</div>

                <div class="detail-label">Jadwal Kerja</div>
                <div class="detail-value">
                    @if ($employee->schedule)
                        {{ $employee->schedule->name }} ({{ $employee->schedule->start_time->format('H:i') }} - {{ $employee->schedule->end_time->format('H:i') }})
                    @else
                        —
                    @endif
                </div>

                <div class="detail-label">Tanggal Masuk</div>
                <div class="detail-value">{{ optional($employee->hire_date)->translatedFormat('d F Y') ?? '—' }}</div>

                <div class="detail-label">Gaji Pokok</div>
                <div class="detail-value">
                    {{ $employee->salary ? 'Rp ' . number_format($employee->salary, 0, ',', '.') : '—' }}
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">NIK</div>
                <div class="detail-value">{{ $employee->national_id ?? '—' }}</div>

                <div class="detail-label">Tempat, Tanggal Lahir</div>
                <div class="detail-value">
                    @if ($employee->place_of_birth || $employee->date_of_birth)
                        {{ $employee->place_of_birth ?? '' }}{{ $employee->place_of_birth && $employee->date_of_birth ? ', ' : '' }}{{ optional($employee->date_of_birth)->translatedFormat('d F Y') }}
                    @else
                        —
                    @endif
                </div>

                <div class="detail-label">Jenis Kelamin</div>
                <div class="detail-value">
                    @if ($employee->gender === 'male')
                        Laki-laki
                    @elseif ($employee->gender === 'female')
                        Perempuan
                    @else
                        —
                    @endif
                </div>

                <div class="detail-label">Alamat</div>
                <div class="detail-value">{{ $employee->address ?? '—' }}</div>
            </div>
        </div>

        <div class="detail-actions">
            <a href="{{ route('manage-users.index') }}" class="btn btn-secondary">Kembali ke daftar</a>
            <a href="{{ route('manage-users.edit', $employee) }}" class="btn btn-primary">Edit Data Pegawai</a>
        </div>
    </section>
@endsection
