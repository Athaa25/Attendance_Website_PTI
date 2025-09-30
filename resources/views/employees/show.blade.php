@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detail Karyawan</h1>

    <p><strong>ID:</strong> {{ $employee->id }}</p>
    <p><strong>Nama:</strong> {{ $employee->name }}</p>
    <p><strong>Posisi:</strong> {{ $employee->position }}</p>

    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
