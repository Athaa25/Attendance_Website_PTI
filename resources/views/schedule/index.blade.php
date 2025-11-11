@extends('layouts.dashboard')

@section('title', 'Jadwal Kerja')
@section('page-title', 'Jadwal Kerja')
@section('page-subtitle', 'Kelola daftar shift dan jam kerja karyawan')

@section('content')
    <section class="content-wrapper">
        @if (session('status'))
            <div class="status-banner">
                <img src="{{ asset('images/schedule-icon.png') }}" alt="Info" width="20" height="20">
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <div class="schedule-card">
            <div class="schedule-header">
                <div>
                    <h2 class="schedule-title">Daftar Shift</h2>
                    <p class="schedule-subtitle">Jam masuk dan keluar setiap shift</p>
                </div>
                <div class="schedule-actions">
                    <a class="btn btn-primary" href="{{ route('schedule.create') }}">
                        Tambah
                    </a>
                </div>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID Shift</th>
                            <th>Nama Shift</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schedules as $schedule)
                            <tr>
                                <td class="shift-id">{{ $schedule->code }}</td>
                                <td>{{ $schedule->name }}</td>
                                <td>{{ optional($schedule->start_time)->format('H.i') }}</td>
                                <td>{{ optional($schedule->end_time)->format('H.i') }}</td>
                                <td>
                                    <div class="actions" style="justify-content: flex-end;">
                                        <a class="icon-button edit" href="{{ route('schedule.edit', $schedule) }}" title="Edit">
                                            <img src="{{ asset('images/edit-icon.png') }}" alt="Edit schedule">
                                        </a>
                                        <form class="inline-form" action="{{ route('schedule.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data shift ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="icon-button delete" type="submit" title="Delete">
                                                <img src="{{ asset('images/delete-icon.png') }}" alt="Delete schedule">
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">Belum ada data shift yang tersedia.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
