@extends('layouts.dashboard')

@section('title', 'Edit Pegawai')
@section('page-title', 'Edit Pegawai')
@section('page-subtitle', 'Perbarui informasi pegawai agar tetap akurat dan up-to-date')

@section('content')
    <section class="content-wrapper">
        <div class="form-header">
            <div>
                <h2 class="form-title">Perbarui Data Pegawai</h2>
                <p class="form-subtitle">Sesuaikan informasi pegawai agar tetap akurat dan relevan.</p>
            </div>
            <a href="{{ route('manage-users.show', $employee) }}" class="btn btn-secondary">Lihat Detail</a>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                <strong>Data belum valid.</strong>
                <ul class="alert-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('manage-users.update', $employee) }}">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label for="full_name">Nama Lengkap</label>
                    <input id="full_name" name="full_name" type="text" class="form-control" value="{{ old('full_name', $employee->full_name) }}" required>
                </div>
                <div class="form-group">
                    <label for="employee_code">Kode Pegawai</label>
                    <input id="employee_code" name="employee_code" type="text" class="form-control" value="{{ old('employee_code', $employee->employee_code) }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $employee->user->email) }}" required>
                </div>
                <div class="form-group">
                    <label for="work_email">Email Kantor (opsional)</label>
                    <input id="work_email" name="work_email" type="email" class="form-control" value="{{ old('work_email', $employee->work_email) }}">
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" class="form-control" value="{{ old('username', $employee->user->username) }}" required>
                </div>
                <div class="form-group">
                    <label for="role">Role Sistem</label>
                    <select id="role" name="role" class="form-control" required>
                        @foreach ($roleOptions as $value => $label)
                            <option value="{{ $value }}" {{ old('role', $employee->user->role) === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Kata Sandi Baru</label>
                    <input id="password" name="password" type="password" class="form-control">
                    <p class="helper-text">Kosongkan jika tidak ingin mengubah kata sandi.</p>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="phone">Nomor Telepon</label>
                    <input id="phone" name="phone" type="text" class="form-control" value="{{ old('phone', $employee->phone) }}">
                </div>
                <div class="form-group">
                    <label for="gender">Jenis Kelamin</label>
                    <select id="gender" name="gender" class="form-control">
                        <option value="">Pilih</option>
                        <option value="male" {{ old('gender', $employee->gender) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ old('gender', $employee->gender) === 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="national_id">NIK</label>
                    <input id="national_id" name="national_id" type="text" class="form-control" value="{{ old('national_id', $employee->national_id) }}">
                </div>
                <div class="form-group">
                    <label for="place_of_birth">Tempat Lahir</label>
                    <input id="place_of_birth" name="place_of_birth" type="text" class="form-control" value="{{ old('place_of_birth', $employee->place_of_birth) }}">
                </div>
                <div class="form-group">
                    <label for="date_of_birth">Tanggal Lahir</label>
                    <input id="date_of_birth" name="date_of_birth" type="date" class="form-control" value="{{ old('date_of_birth', optional($employee->date_of_birth)->format('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label for="hire_date">Tanggal Masuk</label>
                    <input id="hire_date" name="hire_date" type="date" class="form-control" value="{{ old('hire_date', optional($employee->hire_date)->format('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label for="employment_status">Status Kepegawaian</label>
                    <select id="employment_status" name="employment_status" class="form-control" required>
                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ old('employment_status', $employee->employment_status) === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="salary">Gaji Pokok</label>
                    <input id="salary" name="salary" type="number" step="0.01" class="form-control" value="{{ old('salary', $employee->salary) }}">
                </div>
                <div class="form-group">
                    <label for="department_id">Departemen</label>
                    <select id="department_id" name="department_id" class="form-control" required>
                        <option value="">Pilih Departemen</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
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
                            <option value="{{ $position->id }}" {{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>
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
                            <option value="{{ $schedule->id }}" {{ old('schedule_id', $employee->schedule_id) == $schedule->id ? 'selected' : '' }}>
                                {{ $schedule->name }} ({{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group form-row-span">
                    <label for="address">Alamat</label>
                    <textarea id="address" name="address" class="form-control">{{ old('address', $employee->address) }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('manage-users.show', $employee) }}" class="btn btn-secondary">Batalkan Perubahan</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </section>
@endsection
