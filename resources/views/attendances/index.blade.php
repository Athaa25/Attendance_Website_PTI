@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Attendance List</h2>
    <a href="{{ route('attendances.create') }}" class="btn btn-primary mb-3">Tambah Absensi</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Karyawan</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Status</th>
                <th>Alasan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
            <tr>
                <td>{{ $attendance->id }}</td>
                <td>{{ $attendance->employee->name }}</td>
                <td>{{ $attendance->date }}</td>
                <td>{{ $attendance->time }}</td>
                <td>{{ ucfirst($attendance->status) }}</td>
                <td>{{ $attendance->reason ?? '-' }}</td>
                <td>
                    <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
