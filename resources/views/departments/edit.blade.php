@extends('layouts.dashboard')

@section('title', 'Edit Departemen & Jabatan')
@section('page-title', 'Edit Departemen & Jabatan')
@section('page-subtitle', 'Perbarui informasi departemen dan jabatan')

@section('content')
    <section class="content-wrapper">
        <div class="form-header">
            <h2 class="form-title">Perbarui Departemen & Jabatan</h2>
            <p class="form-subtitle">Sesuaikan nama departemen dan jabatan sesuai kebutuhan organisasi.</p>
        </div>

        <form action="{{ route('departments.update', $position) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label for="department_name">Nama Departemen</label>
                    <input type="text" id="department_name" name="department_name" class="form-control" placeholder="Masukkan nama departemen" value="{{ old('department_name', optional($position->department)->name) }}" required>
                    @error('department_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="position_name">Nama Jabatan</label>
                    <input type="text" id="position_name" name="position_name" class="form-control" placeholder="Masukkan nama jabatan" value="{{ old('position_name', $position->name) }}" required>
                    @error('position_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('departments.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </section>
@endsection
