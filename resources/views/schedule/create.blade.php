@extends('layouts.dashboard')

@section('title', 'Tambah Shift Pegawai')
@section('page-title', 'Tambah Shift Pegawai')
@section('page-subtitle', 'Tambahkan informasi shift baru sesuai kebutuhan operasional')

@section('content')
    <section class="content-wrapper">
        <div class="form-header">
            <div>
                <h2 class="form-title">Detail Shift</h2>
                <p class="form-subtitle">Lengkapi identitas shift untuk memudahkan penjadwalan karyawan.</p>
            </div>
        </div>

        <form action="{{ route('schedule.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="code">ID Shift <span class="required-indicator">*</span></label>
                    <input type="text" id="code" name="code" class="form-control" placeholder="SHIFT-1" value="{{ old('code') }}">
                    @error('code')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="name">Nama Shift <span class="required-indicator">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Shift Pagi" value="{{ old('name') }}">
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="start_time">Check In <span class="required-indicator">*</span></label>
                    <input type="time" id="start_time" name="start_time" class="form-control" value="{{ old('start_time') }}">
                    @error('start_time')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="end_time">Check Out <span class="required-indicator">*</span></label>
                    <input type="time" id="end_time" name="end_time" class="form-control" value="{{ old('end_time') }}">
                    @error('end_time')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group form-row-span">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" class="form-control" placeholder="Tambahkan catatan shift jika diperlukan">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('schedule.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Shift</button>
            </div>
        </form>
    </section>
@endsection
