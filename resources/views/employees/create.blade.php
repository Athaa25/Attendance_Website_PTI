@extends('layouts.dashboard')

@section('title', 'Tambah Pegawai')
@section('page-title', 'Tambah Pegawai')
@section('page-subtitle', 'Lengkapi formulir untuk menambahkan pegawai baru ke dalam sistem')

@section('content')
    <section class="content-wrapper">
        <div class="form-header">
            <div>
                <h2 class="form-title">Formulir Pegawai Baru</h2>
                <p class="form-subtitle">Lengkapi data berikut untuk menambahkan pegawai ke dalam sistem.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                <strong>Gagal menyimpan data.</strong>
                <ul class="alert-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('manage-users.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="full_name">Nama Lengkap</label>
                    <input id="full_name" name="full_name" type="text" class="form-control" value="{{ old('full_name') }}" required>
                </div>
                <div class="form-group">
                    <label for="employee_code">Kode Pegawai</label>
                    <input id="employee_code" name="employee_code" type="text" class="form-control" value="{{ old('employee_code') }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label for="work_email">Email Kantor (opsional)</label>
                    <input id="work_email" name="work_email" type="email" class="form-control" value="{{ old('work_email') }}">
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" class="form-control" value="{{ old('username') }}" required>
                </div>
                <div class="form-group">
                    <label for="role">Role Sistem</label>
                    <select id="role" name="role" class="form-control" required>
                        @foreach ($roleOptions as $value => $label)
                            <option value="{{ $value }}" {{ old('role') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input id="password" name="password" type="password" class="form-control" required>
                    <p class="helper-text">Minimal 8 karakter.</p>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="telepon">Nomor Telepon</label>
                    <input id="telepon" name="telepon" type="text" class="form-control" value="{{ old('telepon') }}">
                </div>
                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    @php($selectedJenisKelamin = old('jenis_kelamin', old('gender') === 'male' ? 1 : (old('gender') === 'female' ? 0 : null)))
                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-control">
                        <option value="">Pilih</option>
                        <option value="1" {{ (string) $selectedJenisKelamin === '1' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="0" {{ (string) $selectedJenisKelamin === '0' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nik">NIK</label>
                    <input id="nik" name="nik" type="text" class="form-control" value="{{ old('nik') }}">
                </div>
                <div class="form-group">
                    <label for="nip">NIP</label>
                    <input id="nip" name="nip" type="text" class="form-control" value="{{ old('nip') }}">
                </div>
                <div class="form-group">
                    <label for="place_of_birth">Tempat Lahir</label>
                    <input id="place_of_birth" name="place_of_birth" type="text" class="form-control" value="{{ old('place_of_birth') }}">
                </div>
                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input id="tanggal_lahir" name="tanggal_lahir" type="date" class="form-control" value="{{ old('tanggal_lahir', old('date_of_birth')) }}">
                </div>
                <div class="form-group">
                    <label for="tanggal_mulai">Tanggal Mulai Kerja</label>
                    <input id="tanggal_mulai" name="tanggal_mulai" type="date" class="form-control" value="{{ old('tanggal_mulai', old('hire_date')) }}">
                </div>
                <div class="form-group">
                    <label for="order_date">Order Date / TMT</label>
                    <input id="order_date" name="order_date" type="date" class="form-control" value="{{ old('order_date', old('hire_date')) }}">
                </div>
                <div class="form-group">
                    <label for="employment_status">Status Kepegawaian</label>
                    <select id="employment_status" name="employment_status" class="form-control" required>
                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ old('employment_status') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="salary">Gaji Pokok</label>
                    <input id="salary" name="salary" type="number" step="0.01" class="form-control" value="{{ old('salary') }}">
                </div>
                <div class="form-group">
                    <label for="department_id">Departemen</label>
                    <select id="department_id" name="department_id" class="form-control" required>
                        <option value="">Pilih Departemen</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="position_id">Jabatan</label>
                    <select id="position_id" name="position_id" class="form-control" required>
                        <option value="">Pilih Jabatan</option>
                        @foreach ($positions as $position)
                            <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                                {{ $position->name }} ({{ $position->department->name ?? 'Tanpa Departemen' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="schedule_id">Jadwal Kerja</label>
                    <select id="schedule_id" name="schedule_id" class="form-control" required>
                        <option value="">Pilih Jadwal</option>
                        @foreach ($schedules as $schedule)
                            <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                {{ $schedule->name }} ({{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group form-row-span">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" class="form-control">{{ old('alamat', old('address')) }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('manage-users.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Pegawai</button>
            </div>
        </form>
    </section>
@endsection
