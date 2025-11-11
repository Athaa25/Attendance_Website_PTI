@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
    @php
        $maxChartValue = max(1, $monthlyChart->max('value') ?? 1);
        $startOfMonthLabel = $now->copy()->startOfMonth()->translatedFormat('d F');
        $endOfMonthLabel = $now->copy()->endOfMonth()->translatedFormat('d F Y');
        $chartMaxHeight = 200;
    @endphp

    <section class="content-wrapper">
        <div class="analysis-section">
            <div>
                <div class="section-header">
                    <div>
                        <h2 class="section-title">Analisis Metrik</h2>
                        <p class="section-subtitle">Kehadiran Bulan Ini</p>
                    </div>
                    <a class="primary-button" href="{{ route('reports.sheet') }}">Laporan Detail</a>
                </div>

                <div class="metrics-grid">
                    <a class="metric-card metric-card--link" href="{{ route('reports.sheet', ['type' => 'bulanan']) }}">
                        <span class="metric-title">Total Absensi Bulan Ini</span>
                        <p class="metric-value">{{ number_format($metrics['total_absence']) }}</p>
                        <span class="metric-description">Periode {{ $startOfMonthLabel }} - {{ $endOfMonthLabel }}</span>
                    </a>
                    <a class="metric-card metric-card--link" href="{{ route('manage-users.index') }}">
                        <span class="metric-title">Staff &amp; Karyawan</span>
                        <p class="metric-value">{{ number_format($metrics['employee_count']) }}</p>
                        <span class="metric-description">Total karyawan yang terdaftar</span>
                    </a>
                    <a class="metric-card metric-card--link" href="{{ route('attendance.index') }}">
                        <span class="metric-title">Presensi Harian</span>
                        <p class="metric-value">{{ number_format($metrics['daily_presence_count']) }}</p>
                        <span class="metric-description">Total pegawai hadir hari ini</span>
                    </a>
                    <a class="metric-card metric-card--link" href="{{ route('reports.sheet') }}">
                        <span class="metric-title">Tingkat Kehadiran</span>
                        <p class="metric-value">{{ number_format($metrics['attendance_rate'], 1) }}%</p>
                        <span class="metric-description">Persentase kehadiran pegawai bulan ini</span>
                    </a>
                </div>
            </div>

            <a class="chart-card chart-card--link" href="{{ route('reports.sheet', ['type' => 'bulanan']) }}">
                <h3 class="chart-title">Kehadiran Bulanan</h3>
                <p class="chart-subtitle">Jumlah kehadiran selama 5 bulan terakhir</p>
                <div class="chart-bars">
                    @forelse ($monthlyChart as $item)
                        @php($barHeight = max(12, ($item['value'] / $maxChartValue) * $chartMaxHeight))
                        <div class="chart-bar-wrapper">
                            <div
                                class="chart-bar"
                                style="height: {{ round($barHeight, 2) }}px;"
                                title="{{ $item['label'] }} - {{ $item['value'] }} absensi"
                            ></div>
                            <span class="chart-bar-value">{{ $item['value'] }}</span>
                            <span class="chart-bar-label">{{ $item['label'] }}</span>
                        </div>
                    @empty
                        <p style="font-size: 13px; color: var(--text-muted);">Belum ada data absensi</p>
                    @endforelse
                </div>
            </a>
        </div>

        <div class="attendance-section">
            <div class="section-header">
                <h2 class="section-title">Riwayat Absensi</h2>
                <a href="{{ route('attendance.index') }}" class="primary-button">Lihat Detail</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Status</th>
                        <th>Check-In</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentAttendances as $record)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $record->employee->full_name }}</td>
                            <td>
                                <span class="status-badge {{ $record->status_badge_class }}">
                                    {{ $record->status_label }}
                                </span>
                            </td>
                            <td>{{ optional($record->check_in_time)->format('H:i') ?? '--:--' }}</td>
                            <td>{{ $record->attendance_date->translatedFormat('d M Y') }}</td>
                            <td>{{ $record->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 24px; color: var(--text-muted);">
                                Belum ada data absensi terbaru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
