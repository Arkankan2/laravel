@extends('superadmin.layouts.app')

@section('title', 'Dashboard Super Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . (Auth::user()->nama ?? Auth::user()->username))

@push('styles')
<style>
    .chart-container {
        position: relative;
        height: 280px;
    }
</style>
@endpush

@section('content')

<!-- STAT CARDS -->
<div class="stat-grid">
    <div class="stat-card purple">
        <div class="stat-icon purple"><i class="fa-solid fa-users"></i></div>
        <div class="stat-value">{{ $totalUser }}</div>
        <div class="stat-label">Total Pengguna</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon blue"><i class="fa-solid fa-user-graduate"></i></div>
        <div class="stat-value">{{ $totalMahasiswa }}</div>
        <div class="stat-label">Mahasiswa</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon orange"><i class="fa-solid fa-user-tie"></i></div>
        <div class="stat-value">{{ $totalAdmin }}</div>
        <div class="stat-label">Admin</div>
    </div>
    <div class="stat-card pink">
        <div class="stat-icon pink"><i class="fa-solid fa-file-lines"></i></div>
        <div class="stat-value">{{ $totalLaporan }}</div>
        <div class="stat-label">Total Laporan</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
        <div class="stat-value">{{ $laporanSelesai }}</div>
        <div class="stat-label">Laporan Selesai</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon red"><i class="fa-solid fa-spinner"></i></div>
        <div class="stat-value">{{ $laporanProses }}</div>
        <div class="stat-label">Sedang Diproses</div>
    </div>
</div>

<!-- CHART & LOG ROW -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px">

    <!-- CHART -->
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Laporan per Bulan</div>
                <div class="card-subtitle">Statistik laporan tahun {{ date('Y') }}</div>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="laporanChart"></canvas>
        </div>
    </div>

    <!-- RECENT LOG -->
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Aktivitas Terbaru</div>
                <div class="card-subtitle">Log sistem terkini</div>
            </div>
            <a href="{{ route('superadmin.audit-log.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px">
            @forelse($recentLogs as $log)
            <div style="display:flex;align-items:flex-start;gap:10px;padding:10px;border-radius:8px;background:rgba(255,255,255,0.03)">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(108,99,255,0.15);display:flex;align-items:center;justify-content:center;font-size:13px;color:var(--primary-light);flex-shrink:0">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <div style="min-width:0">
                    <div style="font-size:13px;font-weight:600;color:var(--text-primary)">{{ $log->aktivitas }}</div>
                    <div style="font-size:11px;color:var(--text-muted)">{{ $log->user?->username ?? 'System' }} · {{ $log->created_at?->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <div style="color:var(--text-muted);font-size:13px;text-align:center;padding:20px">
                Belum ada aktivitas.
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- QUICK ACTIONS -->
<div class="card">
    <div class="card-title" style="margin-bottom:16px">Aksi Cepat</div>
    <div style="display:flex;gap:12px;flex-wrap:wrap">
        <a href="{{ route('superadmin.akun.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-user-plus"></i> Tambah Pengguna
        </a>
        <a href="{{ route('superadmin.gedung.create') }}" class="btn btn-secondary">
            <i class="fa-solid fa-building-circle-arrow-right"></i> Tambah Gedung
        </a>
        <a href="{{ route('superadmin.kategori.create') }}" class="btn btn-secondary">
            <i class="fa-solid fa-plus"></i> Tambah Kategori
        </a>
        <a href="{{ route('superadmin.backup.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-database"></i> Buat Backup
        </a>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('laporanChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($bulanLabels),
            datasets: [{
                label: 'Jumlah Laporan',
                data: @json($chartData),
                backgroundColor: 'rgba(108,99,255,0.5)',
                borderColor: 'rgba(108,99,255,1)',
                borderWidth: 2,
                borderRadius: 6,
                hoverBackgroundColor: 'rgba(108,99,255,0.75)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a1a2e',
                    borderColor: 'rgba(108,99,255,0.5)',
                    borderWidth: 1,
                    titleColor: '#e2e8f0',
                    bodyColor: '#94a3b8',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: '#64748b', stepSize: 1 },
                    grid: { color: 'rgba(255,255,255,0.05)' }
                },
                x: {
                    ticks: { color: '#64748b' },
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endpush
