@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Absensi</h2>

    <form action="{{ route('attendances.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="employee_id" class="form-label">Karyawan</label>
            <select name="employee_id" id="employee_id" class="form-control" required>
                <option value="">-- Pilih Karyawan --</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Tanggal</label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="time" class="form-label">Jam</label>
            <input type="time" name="time" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required onchange="toggleReason()">
                <option value="ontime">Ontime</option>
                <option value="late">Late</option>
                <option value="absent">Absent</option>
            </select>
        </div>

        <div class="mb-3" id="reason_div" style="display: none;">
            <label for="reason" class="form-label">Alasan</label>
            <select name="reason" id="reason" class="form-control">
                <option value="">-- Pilih Alasan --</option>
                <option value="sakit">Sakit</option>
                <option value="ijin">Ijin</option>
                <option value="alfa">Alfa</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('attendances.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script>
function toggleReason() {
    var status = document.getElementById('status').value;
    var reasonDiv = document.getElementById('reason_div');
    if (status === 'absent') {
        reasonDiv.style.display = 'block';
    } else {
        reasonDiv.style.display = 'none';
    }
}
</script>
@endsection
