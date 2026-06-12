@extends('layouts.dashboard')

@section('title', 'Dashboard Admin — Sistem Pelaporan Fasilitas')

{{-- ── SIDEBAR ────────────────────────────────────────────── --}}
@section('sidebar-menu')
  <a href="{{ route('admin.dashboard') }}"><button class="active-menu">Dashboard</button></a>
  <a href="#"><button>Verifikasi Laporan</button></a>
  <a href="#"><button>Semua Laporan</button></a>
  <a href="#"><button>Teknisi Tersedia</button></a>
  <a href="{{ route('admin.mahasiswa') }}"><button>Daftar Mahasiswa</button></a>
  <a href="{{ route('admin.riwayat') }}"><button>Riwayat Laporan</button></a>
@endsection

@section('profile-name') {{ $admin->nama_admin ?? $user->username }} @endsection
@section('profile-role') Administrator Sarpras @endsection
@section('profile-buttons')
  <button onclick="toggleProfile()">Edit Profile</button>
@endsection

{{-- ── STYLES ──────────────────────────────────────────────── --}}
@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  /* ═══════════════════════════════════════════════════════════
     ADMIN DASHBOARD — PROFESSIONAL REDESIGN
     Theme: Clean White · Blue #2563EB · Professional
  ═══════════════════════════════════════════════════════════ */

  * { box-sizing: border-box; }

  .dash-wrap {
    font-family: 'Inter', sans-serif;
    background: #F1F5F9;
    min-height: calc(100vh - 70px);
    padding: 24px 28px 40px;
  }

  /* ── Welcome Bar ── */
  .dash-welcome {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
  }
  .dash-welcome-left h1 {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    margin: 0 0 4px;
  }
  .dash-welcome-left p {
    font-size: 13px;
    color: #64748B;
    margin: 0;
  }
  .dash-date-badge {
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 8px 16px;
    font-size: 13px;
    color: #475569;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
  }
  .dash-date-badge i { color: #2563EB; }

  /* ── Flash Alert ── */
  .dash-alert {
    background: #DCFCE7;
    border: 1px solid #86EFAC;
    color: #166534;
    padding: 12px 18px;
    border-radius: 10px;
    font-size: 13px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  /* ── STAT CARDS ──────────────────────────────────────────── */
  .stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 24px;
  }
  .stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 22px 20px;
    border: 1px solid #E2E8F0;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: transform .2s, box-shadow .2s;
    position: relative;
    overflow: hidden;
  }
  .stat-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 0 0 14px 14px;
  }
  .stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.10);
  }
  .stat-card.blue::after   { background: #2563EB; }
  .stat-card.amber::after  { background: #F59E0B; }
  .stat-card.violet::after { background: #7C3AED; }
  .stat-card.green::after  { background: #16A34A; }

  .stat-icon {
    width: 52px; height: 52px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
  }
  .stat-icon.blue   { background: #EFF6FF; color: #2563EB; }
  .stat-icon.amber  { background: #FFFBEB; color: #D97706; }
  .stat-icon.violet { background: #F5F3FF; color: #7C3AED; }
  .stat-icon.green  { background: #F0FDF4; color: #16A34A; }

  .stat-body { flex: 1; min-width: 0; }
  .stat-value {
    font-size: 30px;
    font-weight: 800;
    color: #0F172A;
    line-height: 1;
    margin-bottom: 4px;
  }
  .stat-label { font-size: 12px; color: #64748B; font-weight: 500; }
  .stat-trend {
    font-size: 11px;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
    font-weight: 600;
  }
  .stat-trend.up   { color: #16A34A; }
  .stat-trend.warn { color: #D97706; }

  /* ── SECTION TITLE ── */
  .section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
  }
  .section-title {
    font-size: 15px;
    font-weight: 700;
    color: #0F172A;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .section-title i { color: #2563EB; }
  .section-link {
    font-size: 12px;
    color: #2563EB;
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
  }
  .section-link:hover { text-decoration: underline; }

  /* ── MAIN GRID ROW ── */
  .dash-row {
    display: grid;
    gap: 20px;
    margin-bottom: 20px;
  }
  .dash-row-3-1  { grid-template-columns: 3fr 1fr; }
  .dash-row-2-2  { grid-template-columns: 1fr 1fr; }
  .dash-row-full { grid-template-columns: 1fr; }

  /* ── CARD BOX ── */
  .dash-card {
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
  }

  /* ── TABLE ── */
  .dash-table { width: 100%; border-collapse: collapse; font-size: 13px; }
  .dash-table thead th {
    padding: 10px 14px;
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    color: #94A3B8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #F1F5F9;
  }
  .dash-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #F8FAFC;
    color: #334155;
    font-size: 13px;
  }
  .dash-table tbody tr:last-child td { border-bottom: none; }
  .dash-table tbody tr:hover { background: #F8FAFC; }

  /* ── BADGES ── */
  .badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
  }
  .badge-blue   { background: #EFF6FF; color: #2563EB; }
  .badge-amber  { background: #FFFBEB; color: #B45309; }
  .badge-green  { background: #F0FDF4; color: #16A34A; }
  .badge-red    { background: #FEF2F2; color: #DC2626; }
  .badge-violet { background: #F5F3FF; color: #7C3AED; }
  .badge-gray   { background: #F1F5F9; color: #64748B; }

  /* ── NOTIFIKASI ── */
  .notif-list { display: flex; flex-direction: column; gap: 10px; }
  .notif-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px;
    border-radius: 10px;
    transition: background .15s;
    cursor: default;
  }
  .notif-item:hover { background: #F8FAFC; }
  .notif-icon-wrap {
    width: 36px; height: 36px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
  }
  .notif-icon-wrap.info    { background: #EFF6FF; color: #2563EB; }
  .notif-icon-wrap.danger  { background: #FEF2F2; color: #DC2626; }
  .notif-icon-wrap.warning { background: #FFFBEB; color: #D97706; }
  .notif-icon-wrap.success { background: #F0FDF4; color: #16A34A; }
  .notif-body { flex: 1; min-width: 0; }
  .notif-text  { font-size: 13px; color: #334155; font-weight: 500; line-height: 1.4; }
  .notif-time  { font-size: 11px; color: #94A3B8; margin-top: 2px; }

  /* ── PRIORITAS ── */
  .prioritas-list { display: flex; flex-direction: column; gap: 10px; }
  .prioritas-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 10px;
    background: #FEF2F2;
    border: 1px solid #FECACA;
    transition: transform .15s;
  }
  .prioritas-item:hover { transform: translateX(3px); }
  .prioritas-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #DC2626;
    flex-shrink: 0;
    animation: pulse-dot 1.5s infinite;
  }
  @keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: .6; transform: scale(1.3); }
  }
  .prioritas-body { flex: 1; min-width: 0; }
  .prioritas-desc {
    font-size: 13px;
    font-weight: 600;
    color: #991B1B;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .prioritas-loc  { font-size: 11px; color: #B91C1C; margin-top: 2px; }
  .prioritas-badge {
    font-size: 10px;
    font-weight: 700;
    background: #DC2626;
    color: #fff;
    padding: 2px 8px;
    border-radius: 999px;
    flex-shrink: 0;
  }

  /* ── QUICK ACTION ── */
  .qa-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
  }
  .qa-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 18px 12px;
    border-radius: 12px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    cursor: pointer;
    text-decoration: none;
    transition: all .2s;
    text-align: center;
  }
  .qa-item:hover {
    background: #EFF6FF;
    border-color: #BFDBFE;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37,99,235,0.10);
  }
  .qa-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    background: #EFF6FF;
    color: #2563EB;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
  }
  .qa-label { font-size: 12px; font-weight: 600; color: #374151; }

  /* ── CHART CONTAINER ── */
  .chart-wrap { position: relative; height: 220px; }

  /* ── EMPTY STATE ── */
  .empty-box {
    text-align: center;
    padding: 32px 20px;
    color: #94A3B8;
    font-size: 13px;
  }
  .empty-box i { font-size: 32px; margin-bottom: 8px; display: block; color: #CBD5E1; }

  /* ── RESPONSIVE ── */
  @media (max-width: 1100px) {
    .stat-grid { grid-template-columns: repeat(2, 1fr); }
    .dash-row-3-1 { grid-template-columns: 1fr; }
  }
  @media (max-width: 700px) {
    .dash-wrap { padding: 16px; }
    .stat-grid { grid-template-columns: 1fr 1fr; }
    .dash-row-2-2 { grid-template-columns: 1fr; }
    .qa-grid { grid-template-columns: repeat(2, 1fr); }
  }
  @media (max-width: 480px) {
    .stat-grid { grid-template-columns: 1fr; }
  }
</style>
@endpush

{{-- ── CONTENT ─────────────────────────────────────────────── --}}
@section('content')
<div class="dash-wrap">

  {{-- ── WELCOME BAR ── --}}
  <div class="dash-welcome">
    <div class="dash-welcome-left">
      <h1>Selamat Datang, {{ $admin->nama_admin ?? $user->username }} 👋</h1>
      <p>Administrator Sarana & Prasarana — Sistem Pelaporan Fasilitas Kampus</p>
    </div>
    <div class="dash-date-badge">
      <i class="fa-solid fa-calendar-days"></i>
      <span id="dashDate"></span>
    </div>
  </div>

  {{-- ── FLASH ALERT ── --}}
  @if(session('success'))
    <div class="dash-alert">
      <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
  @endif

  {{-- ══════════════════════════════════════════════════
       STAT CARDS
  ══════════════════════════════════════════════════ --}}
  <div class="stat-grid">

    <div class="stat-card blue">
      <div class="stat-icon blue"><i class="fa-solid fa-file-lines"></i></div>
      <div class="stat-body">
        <div class="stat-value">{{ $totalLaporan }}</div>
        <div class="stat-label">Total Laporan</div>
        <div class="stat-trend up"><i class="fa-solid fa-arrow-trend-up"></i> Semua periode</div>
      </div>
    </div>

    <div class="stat-card amber">
      <div class="stat-icon amber"><i class="fa-solid fa-hourglass-half"></i></div>
      <div class="stat-body">
        <div class="stat-value">{{ $menunggu }}</div>
        <div class="stat-label">Menunggu Verifikasi</div>
        <div class="stat-trend warn"><i class="fa-solid fa-circle-dot"></i> Perlu tindakan</div>
      </div>
    </div>

    <div class="stat-card violet">
      <div class="stat-icon violet"><i class="fa-solid fa-screwdriver-wrench"></i></div>
      <div class="stat-body">
        <div class="stat-value">{{ $dalamPengerjaan }}</div>
        <div class="stat-label">Dalam Pengerjaan</div>
        <div class="stat-trend warn"><i class="fa-solid fa-spinner"></i> Sedang berjalan</div>
      </div>
    </div>

    <div class="stat-card green">
      <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
      <div class="stat-body">
        <div class="stat-value">{{ $selesai }}</div>
        <div class="stat-label">Laporan Selesai</div>
        <div class="stat-trend up"><i class="fa-solid fa-check"></i> Berhasil ditangani</div>
      </div>
    </div>

  </div>

  {{-- ══════════════════════════════════════════════════
       ROW 1: LAPORAN TERBARU + NOTIFIKASI
  ══════════════════════════════════════════════════ --}}
  <div class="dash-row dash-row-3-1">

    {{-- Laporan Terbaru --}}
    <div class="dash-card">
      <div class="section-header">
        <div class="section-title">
          <i class="fa-solid fa-table-list"></i> Laporan Terbaru
        </div>
        <a href="{{ route('admin.riwayat') }}" class="section-link">
          Lihat semua <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>

      @if($laporanTerbaru->isEmpty())
        <div class="empty-box">
          <i class="fa-solid fa-inbox"></i>
          Belum ada laporan yang masuk.
        </div>
      @else
        <div style="overflow-x:auto">
          <table class="dash-table">
            <thead>
              <tr>
                <th>#ID</th>
                <th>Pelapor</th>
                <th>Lokasi</th>
                <th>Kategori</th>
                <th>Kerusakan</th>
                <th>Status</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($laporanTerbaru as $lap)
              <tr>
                <td>
                  <span style="font-weight:700;color:#2563EB">#{{ $lap->id_laporan }}</span>
                </td>
                <td>
                  <div style="display:flex;align-items:center;gap:8px">
                    <div style="width:28px;height:28px;border-radius:50%;background:#EFF6FF;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#2563EB;flex-shrink:0">
                      {{ strtoupper(substr($lap->mahasiswa?->Nama_mahasiswa ?? 'U', 0, 1)) }}
                    </div>
                    <span style="font-weight:500;font-size:13px">{{ $lap->mahasiswa?->Nama_mahasiswa ?? 'Anonim' }}</span>
                  </div>
                </td>
                <td style="max-width:140px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                  {{ $lap->lokasi?->nama_ruangan ?? '-' }}
                </td>
                <td>
                  <span class="badge badge-blue">{{ $lap->kategori?->nama_kategori ?? '-' }}</span>
                </td>
                <td>
                  @php
                    $tk = $lap->Tingkat_Kerusakan;
                    $tkClass = match($tk) { 'Parah' => 'badge-red', 'Sedang' => 'badge-amber', default => 'badge-green' };
                  @endphp
                  <span class="badge {{ $tkClass }}">{{ $tk ?? '-' }}</span>
                </td>
                <td>
                  @php
                    $st = $lap->Status_terkini;
                    $stClass = $st === 'Selesai' ? 'badge-green' : 'badge-violet';
                  @endphp
                  <span class="badge {{ $stClass }}">{{ $st ?? '-' }}</span>
                </td>
                <td style="font-size:12px;color:#94A3B8">
                  {{ $lap->created_at?->format('d/m/Y') }}
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    {{-- Panel Notifikasi --}}
    <div class="dash-card">
      <div class="section-header">
        <div class="section-title">
          <i class="fa-solid fa-bell"></i> Notifikasi
        </div>
        @if(count($notifikasi) > 0)
          <span class="badge badge-red">{{ count($notifikasi) }}</span>
        @endif
      </div>

      <div class="notif-list">
        @foreach($notifikasi as $n)
        <div class="notif-item">
          <div class="notif-icon-wrap {{ $n['type'] }}">
            <i class="fa-solid {{ $n['icon'] }}"></i>
          </div>
          <div class="notif-body">
            <div class="notif-text">{{ $n['pesan'] }}</div>
            <div class="notif-time"><i class="fa-regular fa-clock"></i> {{ $n['waktu'] }}</div>
          </div>
        </div>
        @endforeach
      </div>
    </div>

  </div>

  {{-- ══════════════════════════════════════════════════
       ROW 2: GRAFIK BULANAN + GRAFIK KATEGORI
  ══════════════════════════════════════════════════ --}}
  <div class="dash-row dash-row-2-2">

    {{-- Grafik Laporan per Bulan --}}
    <div class="dash-card">
      <div class="section-header">
        <div class="section-title">
          <i class="fa-solid fa-chart-column"></i> Laporan per Bulan
        </div>
        <span style="font-size:12px;color:#94A3B8">Tahun {{ date('Y') }}</span>
      </div>
      <div class="chart-wrap">
        <canvas id="chartBulanan"></canvas>
      </div>
    </div>

    {{-- Grafik Kategori Kerusakan --}}
    <div class="dash-card">
      <div class="section-header">
        <div class="section-title">
          <i class="fa-solid fa-chart-pie"></i> Kategori Kerusakan
        </div>
        <span style="font-size:12px;color:#94A3B8">Terbanyak</span>
      </div>
      @if(count($chartKategoriLabels) > 0)
        <div class="chart-wrap">
          <canvas id="chartKategori"></canvas>
        </div>
      @else
        <div class="empty-box">
          <i class="fa-solid fa-chart-pie"></i>
          Belum ada data kategori.
        </div>
      @endif
    </div>

  </div>

  {{-- ══════════════════════════════════════════════════
       ROW 3: PRIORITAS TINGGI + QUICK ACTION
  ══════════════════════════════════════════════════ --}}
  <div class="dash-row dash-row-2-2">

    {{-- Laporan Prioritas Tinggi --}}
    <div class="dash-card">
      <div class="section-header">
        <div class="section-title" style="color:#DC2626">
          <i class="fa-solid fa-circle-exclamation" style="color:#DC2626"></i>
          🚨 Prioritas Tinggi
        </div>
        <span class="badge badge-red">{{ $laporanPrioritas->count() }} laporan</span>
      </div>

      @if($laporanPrioritas->isEmpty())
        <div class="empty-box">
          <i class="fa-solid fa-shield-halved" style="color:#86EFAC"></i>
          <span style="color:#16A34A;font-weight:600">Tidak ada laporan prioritas tinggi</span>
        </div>
      @else
        <div class="prioritas-list">
          @foreach($laporanPrioritas as $lap)
          <div class="prioritas-item">
            <div class="prioritas-dot"></div>
            <div class="prioritas-body">
              <div class="prioritas-desc">
                {{ $lap->kategori?->nama_kategori ?? 'Kerusakan' }} —
                {{ Str::limit($lap->deskripsi ?? 'Tidak ada deskripsi', 45) }}
              </div>
              <div class="prioritas-loc">
                <i class="fa-solid fa-location-dot"></i>
                {{ $lap->lokasi?->nama_ruangan ?? 'Lokasi tidak diketahui' }}
                · {{ $lap->created_at?->diffForHumans() }}
              </div>
            </div>
            <span class="prioritas-badge">PARAH</span>
          </div>
          @endforeach
        </div>
      @endif
    </div>

    {{-- Quick Action --}}
    <div class="dash-card">
      <div class="section-header">
        <div class="section-title">
          <i class="fa-solid fa-bolt"></i> Aksi Cepat
        </div>
      </div>
      <div class="qa-grid">
        <a href="#" class="qa-item">
          <div class="qa-icon"><i class="fa-solid fa-clipboard-check"></i></div>
          <span class="qa-label">Verifikasi Laporan</span>
        </a>
        <a href="#" class="qa-item">
          <div class="qa-icon"><i class="fa-solid fa-list-ul"></i></div>
          <span class="qa-label">Semua Laporan</span>
        </a>
        <a href="{{ route('admin.mahasiswa') }}" class="qa-item">
          <div class="qa-icon"><i class="fa-solid fa-users"></i></div>
          <span class="qa-label">Daftar Mahasiswa</span>
        </a>
        <a href="{{ route('admin.riwayat') }}" class="qa-item">
          <div class="qa-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
          <span class="qa-label">Riwayat Laporan</span>
        </a>
        <a href="#" class="qa-item">
          <div class="qa-icon"><i class="fa-solid fa-helmet-safety"></i></div>
          <span class="qa-label">Kelola Teknisi</span>
        </a>
        <a href="#" class="qa-item">
          <div class="qa-icon"><i class="fa-solid fa-chart-bar"></i></div>
          <span class="qa-label">Statistik</span>
        </a>
      </div>
    </div>

  </div>

</div>
@endsection

{{-- ── SCRIPTS ─────────────────────────────────────────────── --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
  // ── Tanggal dinamis ──────────────────────────────────────
  const days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
  const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
  const now    = new Date();
  document.getElementById('dashDate').textContent =
    days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();

  // ── Shared Chart Defaults ────────────────────────────────
  Chart.defaults.font.family = "'Inter', sans-serif";
  Chart.defaults.color = '#94A3B8';

  // ── Grafik Laporan per Bulan ─────────────────────────────
  const ctxBulan = document.getElementById('chartBulanan').getContext('2d');
  new Chart(ctxBulan, {
    type: 'bar',
    data: {
      labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
      datasets: [{
        label: 'Jumlah Laporan',
        data: @json($chartBulan),
        backgroundColor: (ctx) => {
          const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 220);
          g.addColorStop(0, 'rgba(37,99,235,0.8)');
          g.addColorStop(1, 'rgba(37,99,235,0.2)');
          return g;
        },
        borderColor: '#2563EB',
        borderWidth: 2,
        borderRadius: 6,
        borderSkipped: false,
        hoverBackgroundColor: '#1D4ED8',
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#0F172A',
          titleColor: '#F8FAFC',
          bodyColor: '#CBD5E1',
          padding: 12,
          cornerRadius: 8,
          callbacks: {
            label: ctx => '  ' + ctx.parsed.y + ' laporan'
          }
        }
      },
      scales: {
        x: { grid: { display: false }, ticks: { font: { size: 11 } } },
        y: {
          beginAtZero: true,
          grid: { color: '#F1F5F9', lineWidth: 1 },
          ticks: { precision: 0, font: { size: 11 } }
        }
      }
    }
  });

  // ── Grafik Kategori ─────────────────────────────────────
  @if(count($chartKategoriLabels) > 0)
  const ctxKat = document.getElementById('chartKategori').getContext('2d');
  new Chart(ctxKat, {
    type: 'doughnut',
    data: {
      labels: @json($chartKategoriLabels),
      datasets: [{
        data: @json($chartKategoriData),
        backgroundColor: [
          '#2563EB', '#7C3AED', '#DB2777', '#D97706',
          '#16A34A', '#0891B2'
        ],
        borderColor: '#fff',
        borderWidth: 3,
        hoverOffset: 6,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '65%',
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            padding: 14,
            usePointStyle: true,
            pointStyleWidth: 10,
            font: { size: 11 }
          }
        },
        tooltip: {
          backgroundColor: '#0F172A',
          titleColor: '#F8FAFC',
          bodyColor: '#CBD5E1',
          padding: 12,
          cornerRadius: 8,
          callbacks: {
            label: ctx => '  ' + ctx.label + ': ' + ctx.parsed + ' laporan'
          }
        }
      }
    }
  });
  @endif
</script>
@endpush
