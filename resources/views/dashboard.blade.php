@extends('layouts.app')

@section('title', 'Dashboard Rekap')

@section('head')
    <!-- CSS khusus dashboard -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        /* tambahan styling dashboard */
        .dashboard-header h1 {
            font-size: 2rem;
            font-weight: 600;
        }
        .summary-card p {
            font-size: 1.5rem;
            margin: 0;
            font-weight: bold;
        }
        .navigation-cards {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 30px;
        }
        .nav-card {
            flex: 1;
            text-align: center;
            padding: 20px;
            border-radius: 12px;
            background: #fff;
            color: #2c3e50;
            text-decoration: none;
            font-weight: 500;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }
        .nav-card:hover {
            transform: translateY(-3px);
        }
    </style>
@endsection

@section('content')
<div class="container py-4">

    <!-- Header -->
    <header class="dashboard-header mb-4 text-center">
        <h1>Dashboard Rekap Kehadiran</h1>
        <p>{{ \Carbon\Carbon::now()->format('d F Y') }}</p>
    </header>

    <!-- Summary boxes -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-center summary-card p-3">
                <h5>Total Karyawan</h5>
                <p>{{ $employees->count() }}</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center summary-card p-3">
                <h5>Hadir Hari Ini</h5>
                <p>{{ $employees->where('attendances.0','!=',null)->count() }}</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center summary-card p-3">
                <h5>Belum Hadir</h5>
                <p>{{ $employees->where('attendances.0',null)->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Employee Table -->
    <div class="employee-table-container mb-4">
        <table class="table table-striped table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Nama Karyawan</th>
                    <th>Status Presensi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                <tr>
                    <td>{{ $employee->name }}</td>
                    <td>
                        @if($employee->attendances->count() > 0)
                            <span class="badge bg-success">Hadir</span>
                        @else
                            <span class="badge bg-danger">Belum Hadir</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Navigation Cards -->
    <div class="navigation-cards">
        <a href="{{ route('employees.index') }}" class="nav-card">Employee</a>
        <a href="{{ route('attendances.index') }}" class="nav-card">Attendances</a>
    </div>

</div>
@endsection
