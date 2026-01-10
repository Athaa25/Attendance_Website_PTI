@extends('layouts.dashboard')

@section('title', 'Detail Pegawai')
@section('page-title', 'Detail Pegawai')
@section('page-subtitle', 'Informasi lengkap pegawai dan status kepegawaiannya')

@push('styles')
    <style>
        .face-photo-card {
            grid-column: 1 / -1;
        }

        .face-photo-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 8px;
        }

        .face-photo-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .face-photo-item {
            position: relative;
        }

        .face-photo-select {
            position: absolute;
            top: 8px;
            left: 8px;
            z-index: 2;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 4px 6px;
            display: inline-flex;
            align-items: center;
        }

        .face-photo-select input {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .face-photo-link {
            width: 84px;
            height: 84px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .face-photo-link img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .face-photo-empty {
            font-size: 13px;
            color: var(--text-muted);
        }

        .face-preview-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.72);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            z-index: 1200;
        }

        .face-preview-overlay.open {
            display: flex;
        }

        .face-preview-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 16px;
            max-width: 720px;
            width: 100%;
            box-shadow: 0 18px 42px rgba(15, 23, 42, 0.35);
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .face-preview-image {
            width: 100%;
            border-radius: 14px;
            max-height: 70vh;
            object-fit: contain;
            background: #f8fafc;
        }

        .face-preview-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .face-preview-actions .btn {
            min-width: 120px;
        }
    </style>
@endpush

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

        @if (session('status'))
            <div class="status-banner">
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <strong>Terjadi kesalahan.</strong>
                <ul class="alert-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php($facePhotos = $employee->face_photos ?? [])

        <div class="detail-grid">
            <div class="detail-card">
                <div class="detail-label">Email</div>
                <div class="detail-value">{{ $employee->user->email }}</div>

                <div class="detail-label">Email Kantor</div>
                <div class="detail-value">{{ $employee->work_email ?? '-' }}</div>

                <div class="detail-label">Nomor Telepon</div>
                <div class="detail-value">{{ $employee->telepon ?? $employee->phone ?? '-' }}</div>

                <div class="detail-label">Username</div>
                <div class="detail-value">{{ $employee->user->username }}</div>

                <div class="detail-label">Role Sistem</div>
                <div class="detail-value">{{ $employee->user->role?->name ?? 'Tidak ditetapkan' }}</div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Kode Pegawai</div>
                <div class="detail-value">{{ $employee->employee_code }}</div>

                <div class="detail-label">Jadwal Kerja</div>
                <div class="detail-value">
                    @if ($employee->schedule)
                        {{ $employee->schedule->name }} ({{ $employee->schedule->start_time->format('H:i') }} - {{ $employee->schedule->end_time->format('H:i') }})
                    @else
                        Tidak diatur
                    @endif
                </div>

                <div class="detail-label">Tanggal Mulai</div>
                <div class="detail-value">{{ optional($employee->tanggal_mulai ?? $employee->hire_date)->translatedFormat('d F Y') ?? '-' }}</div>

                <div class="detail-label">Order Date / TMT</div>
                <div class="detail-value">{{ optional($employee->order_date ?? $employee->hire_date)->translatedFormat('d F Y') ?? '-' }}</div>

                <div class="detail-label">Gaji Pokok</div>
                <div class="detail-value">
                    {{ $employee->salary ? 'Rp ' . number_format($employee->salary, 0, ',', '.') : '—' }}
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">NIK</div>
                <div class="detail-value">{{ $employee->nik ?? $employee->national_id ?? '-' }}</div>

                <div class="detail-label">NIP</div>
                <div class="detail-value">{{ $employee->nip ?? '-' }}</div>

                <div class="detail-label">Tempat, Tanggal Lahir</div>
                <div class="detail-value">
                    @if ($employee->place_of_birth || $employee->tanggal_lahir || $employee->date_of_birth)
                        {{ $employee->place_of_birth ?? '' }}{{ $employee->place_of_birth && ($employee->tanggal_lahir || $employee->date_of_birth) ? ', ' : '' }}{{ optional($employee->tanggal_lahir ?? $employee->date_of_birth)->translatedFormat('d F Y') }}
                    @else
                        -
                    @endif
                </div>

                <div class="detail-label">Jenis Kelamin</div>
                <div class="detail-value">{{ $employee->jenis_kelamin_label ?? ($employee->gender === 'male' ? 'Laki-laki' : ($employee->gender === 'female' ? 'Perempuan' : '-')) }}</div>

                <div class="detail-label">Alamat</div>
                <div class="detail-value">{{ $employee->alamat ?? $employee->address ?? '-' }}</div>
            </div>

            <div class="detail-card face-photo-card">
                <div class="detail-label">Foto Wajah</div>
                @if (count($facePhotos))
                    <form
                        method="POST"
                        action="{{ route('manage-users.face-photos.destroy', $employee) }}"
                        onsubmit="return confirm('Hapus foto yang dipilih?');"
                    >
                        @csrf
                        @method('DELETE')
                        <div class="face-photo-toolbar">
                            <span class="helper-text">Pilih foto yang ingin dihapus.</span>
                            <button type="submit" class="btn btn-secondary">Hapus Foto</button>
                        </div>
                        <div class="face-photo-grid">
                            @foreach ($facePhotos as $photo)
                                <div class="face-photo-item">
                                    <label class="face-photo-select" title="Pilih {{ $photo['name'] }}">
                                        <input
                                            type="checkbox"
                                            name="photos[]"
                                            value="{{ $photo['name'] }}"
                                            aria-label="Pilih {{ $photo['name'] }}"
                                        >
                                    </label>
                                    <button
                                        class="face-photo-link"
                                        type="button"
                                        data-face-preview
                                        data-url="{{ $photo['url'] }}"
                                        data-name="{{ $photo['name'] }}"
                                        title="Lihat {{ $photo['name'] }}"
                                    >
                                        <img src="{{ $photo['url'] }}" alt="Face {{ $employee->full_name }}">
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </form>
                @else
                    <div class="face-photo-empty">Belum ada foto wajah tersimpan.</div>
                @endif
            </div>
        </div>

        <div class="face-preview-overlay" data-face-overlay>
            <div class="face-preview-card">
                <img class="face-preview-image" src="" alt="Preview wajah" data-face-image>
                <div class="face-preview-actions">
                    <button type="button" class="btn btn-secondary" data-face-close>Tutup</button>
                    <a class="btn btn-primary" href="#" download data-face-download>Download</a>
                </div>
            </div>
        </div>

        <div class="detail-actions">
            <a href="{{ route('manage-users.index') }}" class="btn btn-secondary">Kembali ke daftar</a>
            <a href="{{ route('manage-users.edit', $employee) }}" class="btn btn-primary">Edit Data Pegawai</a>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (() => {
            const overlay = document.querySelector('[data-face-overlay]');
            const image = document.querySelector('[data-face-image]');
            const download = document.querySelector('[data-face-download]');
            const closeBtn = document.querySelector('[data-face-close]');

            if (!overlay || !image || !download || !closeBtn) return;

            const openPreview = (url, name) => {
                image.src = url;
                image.alt = `Preview ${name || 'wajah'}`;
                download.href = url;
                if (name) {
                    download.setAttribute('download', name);
                } else {
                    download.removeAttribute('download');
                }
                overlay.classList.add('open');
            };

            const closePreview = () => {
                overlay.classList.remove('open');
                image.src = '';
            };

            document.addEventListener('click', (event) => {
                const button = event.target.closest('[data-face-preview]');
                if (!button) return;
                openPreview(button.dataset.url, button.dataset.name);
            });

            closeBtn.addEventListener('click', closePreview);
            overlay.addEventListener('click', (event) => {
                if (event.target === overlay) {
                    closePreview();
                }
            });
        })();
    </script>
@endpush
