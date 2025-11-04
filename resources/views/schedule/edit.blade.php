@extends('layouts.dashboard')

@section('title', 'Edit Shift Pegawai')
@section('page-title', 'Edit Shift Pegawai')
@section('page-subtitle', 'Perbarui jadwal shift untuk menyesuaikan operasional')

@section('content')
    <section class="content-wrapper">
        <div class="form-header">
            <div>
                <h2 class="form-title">Perbarui Shift</h2>
                <p class="form-subtitle">Ubah detail shift agar sesuai dengan kebutuhan tim.</p>
            </div>
        </div>

        <form action="{{ route('schedule.update', $schedule) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label for="code">ID Shift <span class="required-indicator">*</span></label>
                    <input type="text" id="code" name="code" class="form-control" value="{{ old('code', $schedule->code) }}" required>
                    @error('code')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="name">Nama Shift <span class="required-indicator">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $schedule->name) }}" required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="start_time">Check In <span class="required-indicator">*</span></label>
                    <input type="time" id="start_time" name="start_time" class="form-control" value="{{ old('start_time', optional($schedule->start_time)->format('H:i')) }}" required>
                    @error('start_time')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="end_time">Check Out <span class="required-indicator">*</span></label>
                    <input type="time" id="end_time" name="end_time" class="form-control" value="{{ old('end_time', optional($schedule->end_time)->format('H:i')) }}" required>
                    @error('end_time')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group form-row-span">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" class="form-control" placeholder="Tambahkan catatan shift jika diperlukan">{{ old('description', $schedule->description) }}</textarea>
                    @error('description')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('schedule.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </section>
@endsection
