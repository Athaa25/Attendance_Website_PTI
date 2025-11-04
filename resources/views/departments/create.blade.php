@extends('layouts.dashboard')

@section('title', 'Tambah Departemen & Jabatan')
@section('page-title', 'Tambah Departemen & Jabatan')
@section('page-subtitle', 'Tambahkan departemen dan jabatan baru untuk struktur organisasi Anda')

@section('content')
    <section class="content-wrapper">
        <div class="form-header">
            <h2 class="form-title">Formulir Departemen &amp; Jabatan</h2>
            <p class="form-subtitle">Masukkan informasi departemen dan jabatan baru pada perusahaan Anda.</p>
        </div>

        <form action="{{ route('departments.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="department_name">Nama Departemen</label>
                    <input type="text" id="department_name" name="department_name" class="form-control" placeholder="Masukkan nama departemen" value="{{ old('department_name') }}" required>
                    @error('department_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="position_name">Nama Jabatan</label>
                    <input type="text" id="position_name" name="position_name" class="form-control" placeholder="Masukkan nama jabatan" value="{{ old('position_name') }}" required>
                    @error('position_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('departments.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </section>
@endsection
