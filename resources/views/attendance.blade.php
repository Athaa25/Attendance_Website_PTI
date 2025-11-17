@extends('layouts.dashboard')

@section('title', 'Absensi Harian')
@section('page-title', 'Absensi Harian')
@section('page-subtitle', 'Ringkasan kehadiran pegawai setiap hari')

@section('content')
    @include('attendance.partials.daily')
@endsection
