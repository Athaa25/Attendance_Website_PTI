@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Karyawan</h1>

        <form action="{{ route('employees.update', $employee->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" id="name" value="{{ $employee->name }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="position" class="form-label">Posisi</label>
                <input type="text" name="position" id="position" value="{{ $employee->position }}" class="form-control"
                    required>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
