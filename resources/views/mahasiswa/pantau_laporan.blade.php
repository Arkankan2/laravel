@extends('layouts.dashboard')

@section('title', 'Pantau Laporan — Sistem Pelaporan Fasilitas')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    /* ================================================================
       PANTAU LAPORAN — Monitoring View
       Modern split-panel layout, connected to real database
       ================================================================ */

    :root {
        --primary:        #4f46e5;
        --primary-light:  #eef2ff;
        --primary-dark:   #3730a3;
        --blue:           #2563EB;
        --blue-light:     #EFF6FF;
        --success:        #10b981;
        --success-light:  #ecfdf5;
        --warning:        #f59e0b;
        --warning-light:  #fffbeb;
        --danger:         #ef4444;
        --danger-light:   #fef2f2;
        --gray-50:        #f8fafc;
        --gray-100:       #f1f5f9;
        --gray-200:       #e2e8f0;
        --gray-300:       #cbd5e1;
        --gray-500:       #64748b;
        --gray-700:       #334155;
        --gray-900:       #0f172a;
        --white:          #ffffff;
        --shadow:         0 4px 18px -2px rgba(15,23,42,.07);
        --shadow-lg:      0 8px 30px -4px rgba(15,23,42,.12);
        --radius:         12px;
    }

    /* ── OVERRIDE BODY untuk full-height split layout ──────── */
    body {
        background: var(--gray-100);
        font-family: 'Inter', system-ui, sans-serif;
    }

    /* main-content dari layout sudah flex column */
    .pantau-wrapper {
        display: flex;
        flex: 1;
        height: calc(100vh - 64px); /* 64px = header tinggi */
        overflow: hidden;
        gap: 0;
    }

    /* ──────────────────────────────────────────────────────────
       SIDEBAR KIRI — Daftar Laporan
    ──────────────────────────────────────────────────────────── */
    .panel-list {
        width: 340px;
        min-width: 280px;
        background: var(--white);
        border-right: 1px solid var(--gray-200);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        flex-shrink: 0;
    }

    .panel-list-header {
        padding: 18px 20px 14px;
        border-bottom: 1px solid var(--gray-200);
        background: var(--white);
        position: sticky;
        top: 0;
        z-index: 5;
    }

    .panel-list-header h2 {
        font-size: 13px;
        font-weight: 700;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: .6px;
        margin-bottom: 12px;
    }

    /* Search Box */
    .search-box {
        position: relative;
    }
    .search-box input {
        width: 100%;
        padding: 9px 12px 9px 36px;
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        font-size: 13px;
        font-family: 'Inter', sans-serif;
        background: var(--gray-50);
        color: var(--gray-700);
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .search-box input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        background: var(--white);
    }
    .search-box .search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        pointer-events: none;
    }

    /* Filter Pills */
    .filter-pills {
        display: flex;
        gap: 6px;
        padding: 10px 20px;
        border-bottom: 1px solid var(--gray-200);
        overflow-x: auto;
        flex-shrink: 0;
    }
    .filter-pills::-webkit-scrollbar { display: none; }

    .pill {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        border: 1.5px solid var(--gray-200);
        background: var(--white);
        color: var(--gray-500);
        cursor: pointer;
        white-space: nowrap;
        transition: all .2s;
        font-family: 'Inter', sans-serif;
    }
    .pill:hover { border-color: var(--primary); color: var(--primary); }
    .pill.active { background: var(--primary); border-color: var(--primary); color: var(--white); }

    /* List Items */
    .panel-list-body {
        flex: 1;
        overflow-y: auto;
    }
    .panel-list-body::-webkit-scrollbar { width: 4px; }
    .panel-list-body::-webkit-scrollbar-track { background: var(--gray-100); }
    .panel-list-body::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 2px; }

    .report-item {
        padding: 16px 20px;
        border-bottom: 1px solid var(--gray-100);
        cursor: pointer;
        transition: all .2s;
        position: relative;
    }
    .report-item:hover { background: var(--gray-50); }
    .report-item.active {
        background: var(--primary-light);
        border-left: 4px solid var(--primary);
    }
    .report-item.active .item-ticket { color: var(--primary); }

    .item-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }
    .item-ticket {
        font-size: 10.5px;
        font-weight: 800;
        font-family: 'Courier New', monospace;
        color: var(--gray-500);
        background: var(--gray-100);
        padding: 2px 7px;
        border-radius: 5px;
        letter-spacing: .5px;
    }
    .item-title {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 4px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .item-sub {
        font-size: 11.5px;
        color: var(--gray-500);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Status mini badge */
    .mini-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 700;
    }
    .mini-badge::before {
        content: '';
        width: 5px; height: 5px;
        border-radius: 50%;
        display: inline-block;
    }
    .mb-proses   { background: var(--primary-light); color: var(--primary); }
    .mb-proses::before   { background: var(--primary); }
    .mb-selesai  { background: var(--success-light); color: var(--success); }
    .mb-selesai::before  { background: var(--success); animation: none; }
    .mb-default  { background: var(--gray-100); color: var(--gray-500); }
    .mb-default::before  { background: var(--gray-300); }

    /* Empty list */
    .list-empty {
        padding: 40px 20px;
        text-align: center;
        color: var(--gray-500);
    }
    .list-empty .empty-ico { font-size: 36px; display: block; margin-bottom: 10px; }
    .list-empty p { font-size: 13px; line-height: 1.6; }
    .list-empty a {
        display: inline-block;
        margin-top: 14px;
        background: var(--primary);
        color: var(--white);
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
        text-decoration: none;
        transition: background .2s;
    }
    .list-empty a:hover { background: var(--primary-dark); }

    /* ──────────────────────────────────────────────────────────
       PANEL KANAN — Detail Monitor
    ──────────────────────────────────────────────────────────── */
    .panel-detail {
        flex: 1;
        overflow-y: auto;
        padding: 28px 30px;
        display: flex;
        flex-direction: column;
        gap: 22px;
        background: var(--gray-100);
    }
    .panel-detail::-webkit-scrollbar { width: 5px; }
    .panel-detail::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 3px; }

    /* ── Welcome / No Selection State ─────────────────────── */
    .no-selection {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 14px;
        text-align: center;
        padding: 40px;
        color: var(--gray-500);
    }
    .no-selection .ns-icon {
        font-size: 56px;
        background: var(--gray-100);
        width: 96px; height: 96px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    .no-selection h3 { font-size: 18px; font-weight: 700; color: var(--gray-700); }
    .no-selection p  { font-size: 13.5px; max-width: 300px; line-height: 1.6; }

    /* ── Breadcrumb / Header Detail ───────────────────────── */
    .detail-topbar {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 10px;
    }
    .detail-topbar .ticket-label {
        font-family: 'Courier New', monospace;
        font-size: 12px;
        font-weight: 800;
        color: var(--primary);
        background: var(--primary-light);
        padding: 4px 10px;
        border-radius: 6px;
        letter-spacing: .5px;
    }
    .detail-topbar .back-btn {
        display: none;
        background: var(--gray-100);
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 700;
        color: var(--gray-700);
        cursor: pointer;
        font-family: 'Inter', sans-serif;
    }

    /* ── Stepper / Progress Card ──────────────────────────── */
    .stepper-card {
        background: var(--white);
        border-radius: var(--radius);
        padding: 22px 26px;
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow);
    }

    .stepper-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: var(--gray-500);
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stepper-wrapper {
        display: flex;
        justify-content: space-between;
        position: relative;
    }

    .stepper-line-bg {
        position: absolute;
        top: 20px;
        left: 5%;
        right: 5%;
        height: 4px;
        background: var(--gray-200);
        border-radius: 2px;
        z-index: 1;
    }
    .stepper-line-fill {
        position: absolute;
        top: 20px;
        left: 5%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), #818cf8);
        border-radius: 2px;
        z-index: 2;
        transition: width .5s cubic-bezier(.4,0,.2,1);
        width: 0%;
    }

    .step-node {
        position: relative;
        z-index: 3;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        gap: 8px;
    }
    .node-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: var(--white);
        border: 3px solid var(--gray-300);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        color: var(--gray-500);
        transition: all .3s;
    }
    .node-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--gray-500);
        text-align: center;
    }

    .step-node.completed .node-circle {
        background: var(--success);
        border-color: var(--success);
        color: var(--white);
    }
    .step-node.completed .node-label { color: var(--success); }

    .step-node.active .node-circle {
        background: var(--primary);
        border-color: var(--primary);
        color: var(--white);
        box-shadow: 0 0 0 5px var(--primary-light);
        animation: pulse-node 2s infinite;
    }
    .step-node.active .node-label { color: var(--primary); font-weight: 800; }

    @keyframes pulse-node {
        0%   { box-shadow: 0 0 0 5px rgba(79,70,229,.15); }
        50%  { box-shadow: 0 0 0 10px rgba(79,70,229,.05); }
        100% { box-shadow: 0 0 0 5px rgba(79,70,229,.15); }
    }

    /* ── Details + Log Grid ───────────────────────────────── */
    .details-grid {
        display: grid;
        grid-template-columns: 3fr 2fr;
        gap: 20px;
    }

    .panel-box {
        background: var(--white);
        border-radius: var(--radius);
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow);
        padding: 22px 24px;
    }

    .panel-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--gray-900);
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--gray-100);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }
    .badge-danger   { background: var(--danger-light);  color: var(--danger); }
    .badge-warning  { background: var(--warning-light); color: var(--warning); }
    .badge-success  { background: var(--success-light); color: var(--success); }
    .badge-primary  { background: var(--primary-light); color: var(--primary); }
    .badge-gray     { background: var(--gray-100);      color: var(--gray-500); }

    /* Info matrix */
    .info-matrix {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin: 16px 0;
    }
    .matrix-item {
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        padding: 12px;
        border-radius: 8px;
    }
    .matrix-item .m-label {
        display: block;
        font-size: 10.5px;
        color: var(--gray-500);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 4px;
    }
    .matrix-item .m-value {
        font-size: 13px;
        font-weight: 700;
        color: var(--gray-900);
    }

    .desc-box {
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        padding: 14px;
        font-size: 13.5px;
        line-height: 1.7;
        color: var(--gray-700);
        margin-top: 4px;
        font-style: italic;
    }

    /* ── Foto Laporan ─────────────────────────────────────── */
    .foto-laporan {
        margin-top: 16px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid var(--gray-200);
        max-height: 200px;
    }
    .foto-laporan img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
    }

    /* ── Timeline Log ─────────────────────────────────────── */
    .timeline-box {
        display: flex;
        flex-direction: column;
        gap: 0;
        position: relative;
        padding-left: 20px;
    }
    .timeline-box::before {
        content: '';
        position: absolute;
        left: 7px;
        top: 5px;
        bottom: 5px;
        width: 2px;
        background: var(--gray-200);
    }

    .log-node {
        position: relative;
        padding-bottom: 18px;
    }
    .log-node:last-child { padding-bottom: 0; }

    .log-dot {
        position: absolute;
        left: -20px;
        top: 6px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: var(--gray-300);
        border: 2px solid var(--white);
        z-index: 1;
    }
    .log-node:first-child .log-dot {
        background: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .log-content {
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        padding: 12px 14px;
        border-radius: 8px;
        transition: border-color .2s;
    }
    .log-content:hover { border-color: var(--primary); }

    .log-time {
        font-size: 10.5px;
        color: var(--gray-500);
        display: block;
        margin-bottom: 3px;
        font-style: italic;
    }
    .log-status-title {
        font-weight: 800;
        font-size: 13px;
        color: var(--gray-900);
        margin-bottom: 4px;
    }
    .log-msg {
        font-size: 12.5px;
        color: var(--gray-700);
        line-height: 1.5;
        font-style: italic;
    }

    /* ── RESPONSIVE ───────────────────────────────────────── */
    @media (max-width: 1100px) {
        .details-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 900px) {
        .pantau-wrapper { flex-direction: column; height: auto; overflow: auto; }
        .panel-list { width: 100%; height: auto; max-height: 280px; }
        .panel-detail { padding: 16px; }
        .detail-topbar .back-btn { display: inline-flex; }
    }
    @media (max-width: 600px) {
        .info-matrix { grid-template-columns: 1fr; }
    }

    /* ── hidden class ─────────────────────────────────────── */
    .hidden { display: none !important; }
</style>
@endpush

@section('sidebar-menu')
  <a href="{{ route('mahasiswa.dashboard') }}">
    <button>🏠 Dashboard</button>
  </a>
  <a href="{{ route('laporan.create') }}">
    <button>📋 Buat Laporan</button>
  </a>
  <a href="{{ route('laporan.pantau') }}">
    <button class="active-menu">🔍 Pantau Laporan</button>
  </a>
  <a href="{{ route('laporan.status') }}">
    <button>📂 Riwayat Laporan</button>
  </a>
  <a href="{{ route('mahasiswa.ganti.password') }}">
    <button style="background:rgba(255,243,205,.1);color:#FCD34D;">🔑 Ganti Password</button>
  </a>
@endsection

@section('profile-name') {{ $mhs->Nama_mahasiswa ?? $user->username }} @endsection
@section('profile-role') NIM: {{ $mhs->Nim ?? '' }} @endsection
@section('profile-buttons')
  <a href="{{ route('mahasiswa.biodata', $mhs->id_mahasiswa ?? 0) }}">
    <button>👤 Edit Profil</button>
  </a>
  <a href="{{ route('mahasiswa.ganti.password') }}">
    <button>🔑 Ganti Password</button>
  </a>
@endsection

@section('content')

@php
  $nama = $mhs->Nama_mahasiswa ?? $user->username;

  /**
   * Map Status_terkini → step index (1-4) untuk stepper
   */
  $stepMap = function(string $status): int {
    if (str_contains($status, 'Selesai'))    return 4;
    if (str_contains($status, 'Diperbaiki')) return 3;
    if (str_contains($status, 'Diproses'))   return 2;
    return 1; // Menunggu / default
  };

  /**
   * Map Tingkat_Kerusakan → badge class + label
   */
  $urgencyClass = function(string $t): string {
    return match($t) {
      'Parah'  => 'badge-danger',
      'Sedang' => 'badge-warning',
      'Rendah' => 'badge-success',
      default  => 'badge-gray',
    };
  };

  $statusClass = function(string $s): string {
    if (str_contains($s, 'Selesai'))    return 'badge-success';
    if (str_contains($s, 'Diperbaiki')) return 'badge-primary';
    return 'badge-warning';
  };

  $miniBadgeClass = function(string $s): string {
    if (str_contains($s, 'Selesai'))    return 'mb-selesai';
    if (str_contains($s, 'Diperbaiki')) return 'mb-proses';
    return 'mb-default';
  };
@endphp

{{-- ── SPLIT PANEL WRAPPER ──────────────────────────────── --}}
<div class="pantau-wrapper">

    {{-- ════════════════════════════════════════
         PANEL KIRI — Daftar Laporan
    ════════════════════════════════════════ --}}
    <div class="panel-list">
        <div class="panel-list-header">
            <h2>📋 Laporan Saya</h2>
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" id="search-input"
                       placeholder="Cari nomor tiket atau kerusakan…"
                       oninput="filterList(this.value)">
            </div>
        </div>

        {{-- Filter Pills --}}
        <div class="filter-pills">
            <button class="pill active" onclick="filterPill(this,'all')">Semua</button>
            <button class="pill" onclick="filterPill(this,'proses')">Diproses</button>
            <button class="pill" onclick="filterPill(this,'selesai')">Selesai</button>
            <button class="pill" onclick="filterPill(this,'parah')">Parah</button>
            <button class="pill" onclick="filterPill(this,'sedang')">Sedang</button>
            <button class="pill" onclick="filterPill(this,'rendah')">Rendah</button>
        </div>

        {{-- List Body --}}
        <div class="panel-list-body" id="report-list">
            @if($laporan->isEmpty())
                <div class="list-empty">
                    <span class="empty-ico">📭</span>
                    <p>Belum ada laporan yang pernah dibuat.</p>
                    <a href="{{ route('laporan.create') }}">➕ Buat Laporan</a>
                </div>
            @else
                @foreach($laporan as $idx => $lap)
                @php
                    $isFirst   = $idx === 0;
                    $ticketNum = 'RPT-' . str_pad($lap->id_laporan, 5, '0', STR_PAD_LEFT);
                    $kategori  = ucfirst($lap->kategori->nama_kategori ?? 'Fasilitas');
                    $lokasi    = $lap->lokasi
                        ? $lap->lokasi->nama_ruangan . ', ' . $lap->lokasi->nama_gedung
                        : 'Lokasi tidak diketahui';
                    $status    = $lap->Status_terkini ?? 'Menunggu';
                    $tingkat   = $lap->Tingkat_Kerusakan ?? '-';
                @endphp
                <div class="report-item {{ $isFirst ? 'active' : '' }}"
                     data-id="{{ $lap->id_laporan }}"
                     data-status="{{ strtolower($status) }}"
                     data-tingkat="{{ strtolower($tingkat) }}"
                     data-search="{{ e(strtolower($ticketNum . ' ' . $kategori . ' ' . $lokasi . ' ' . $tingkat)) }}">
                    <div class="item-top">
                        <span class="item-ticket">{{ $ticketNum }}</span>
                        <span class="mini-badge {{ $miniBadgeClass($status) }}">
                            {{ str_contains($status,'Selesai') ? 'Selesai' : (str_contains($status,'Diperbaiki') ? 'Diproses' : 'Menunggu') }}
                        </span>
                    </div>
                    <div class="item-title">{{ $kategori }}</div>
                    <div class="item-sub">
                        <span>📍 {{ $lokasi }}</span>
                        <span>· {{ $lap->created_at->format('d M Y') }}</span>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- ════════════════════════════════════════
         PANEL KANAN — Detail Monitoring
    ════════════════════════════════════════ --}}
    <div class="panel-detail" id="panel-detail">

        {{-- Default: tampilan awal jika ada laporan, langsung load laporan pertama --}}
        @if($laporan->isEmpty())
        <div class="no-selection">
            <div class="ns-icon">📋</div>
            <h3>Belum Ada Laporan</h3>
            <p>Anda belum pernah membuat laporan kerusakan fasilitas. Klik tombol di bawah untuk memulai.</p>
            <a href="{{ route('laporan.create') }}"
               style="margin-top:16px;display:inline-block;background:var(--primary);color:white;padding:11px 22px;border-radius:9px;font-weight:700;text-decoration:none;font-size:14px;">
               ➕ Buat Laporan Baru
            </a>
        </div>
        @else

        {{-- Detail area — diisi oleh JavaScript --}}
        <div id="detail-content">

            {{-- Topbar --}}
            <div class="detail-topbar">
                <div>
                    <span id="det-ticket" class="ticket-label" style="font-family:'Courier New',monospace;font-size:12px;font-weight:800;color:var(--primary);background:var(--primary-light);padding:4px 10px;border-radius:6px;letter-spacing:.5px;"></span>
                    <span id="det-status-badge" class="badge" style="margin-left:8px;"></span>
                    <span id="det-urgency-badge" class="badge" style="margin-left:6px;"></span>
                </div>
                <button class="back-btn" id="back-btn" onclick="showList()">← Kembali</button>
            </div>

            {{-- Stepper Card --}}
            <div class="stepper-card">
                <div class="stepper-label">
                    <span>📊 Progress Penanganan Laporan</span>
                    <span id="det-step-label"
                          style="font-size:12px;color:var(--primary);font-weight:700;"></span>
                </div>
                <div class="stepper-wrapper">
                    <div class="stepper-line-bg"></div>
                    <div class="stepper-line-fill" id="stepper-fill"></div>
                    <div class="step-node" id="step-1">
                        <div class="node-circle">1</div>
                        <div class="node-label">Diterima</div>
                    </div>
                    <div class="step-node" id="step-2">
                        <div class="node-circle">2</div>
                        <div class="node-label">Diverifikasi</div>
                    </div>
                    <div class="step-node" id="step-3">
                        <div class="node-circle">3</div>
                        <div class="node-label">Diperbaiki</div>
                    </div>
                    <div class="step-node" id="step-4">
                        <div class="node-circle">4</div>
                        <div class="node-label">Selesai</div>
                    </div>
                </div>
            </div>

            {{-- Detail + Log Grid --}}
            <div class="details-grid">

                {{-- Kiri: Info Detail --}}
                <div class="panel-box">
                    <div class="panel-title">📝 Informasi Laporan</div>

                    <div style="margin-bottom:12px;">
                        <div style="font-size:20px;font-weight:800;color:var(--gray-900);margin-bottom:6px;" id="det-title"></div>
                        <div style="font-size:13px;color:var(--gray-500);" id="det-lokasi"></div>
                    </div>

                    <div class="info-matrix">
                        <div class="matrix-item">
                            <span class="m-label">Pelapor</span>
                            <span class="m-value" id="det-reporter"></span>
                        </div>
                        <div class="matrix-item">
                            <span class="m-label">Tanggal Laporan</span>
                            <span class="m-value" id="det-date"></span>
                        </div>
                        <div class="matrix-item">
                            <span class="m-label">Kategori Fasilitas</span>
                            <span class="m-value" id="det-kategori"></span>
                        </div>
                        <div class="matrix-item">
                            <span class="m-label">Tingkat Kerusakan</span>
                            <span class="m-value" id="det-tingkat"></span>
                        </div>
                    </div>

                    <div>
                        <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:var(--gray-500);margin-bottom:8px;">
                            📌 Deskripsi Kerusakan
                        </div>
                        <div class="desc-box" id="det-desc"></div>
                    </div>

                    {{-- Foto --}}
                    <div id="foto-wrapper" class="hidden">
                        <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:var(--gray-500);margin-bottom:8px;margin-top:16px;">
                            📷 Foto Kerusakan
                        </div>
                        <div class="foto-laporan">
                            <img id="det-foto" src="" alt="Foto Kerusakan">
                        </div>
                    </div>
                </div>

                {{-- Kanan: Timeline Log --}}
                <div class="panel-box">
                    <div class="panel-title">🕐 Riwayat Status Laporan</div>
                    <div class="timeline-box" id="timeline-container">
                        {{-- diisi JS --}}
                    </div>
                </div>
            </div>

        </div>{{-- /detail-content --}}
        @endif

    </div>{{-- /panel-detail --}}

</div>{{-- /pantau-wrapper --}}

@if(!$laporan->isEmpty())

@php
    /* Bangun array $reportsData dari collection $laporan */
    $_stepLabels = [
        1 => 'Laporan Diterima',
        2 => 'Sedang Diverifikasi Admin',
        3 => 'Dalam Pengerjaan Teknisi',
        4 => 'Perbaikan Selesai',
    ];
    $_stepNotes = [
        1 => 'Laporan berhasil dikirim ke sistem dan masuk antrian verifikasi admin Sarpras.',
        2 => 'Admin Sarpras sedang meninjau dan memverifikasi laporan Anda.',
        3 => 'Teknisi telah ditugaskan dan sedang melakukan perbaikan di lokasi.',
        4 => 'Perbaikan telah selesai dilakukan. Fasilitas sudah kembali normal.',
    ];

    $reportsJson = [];
    foreach ($laporan as $lap) {
        $_s    = $lap->Status_terkini ?? 'Menunggu';
        $_step = $stepMap($_s);
        $_tkt  = 'RPT-' . str_pad($lap->id_laporan, 5, '0', STR_PAD_LEFT);
        $_kat  = ucfirst($lap->kategori->nama_kategori ?? 'Fasilitas');
        $_lok  = $lap->lokasi
            ? $lap->lokasi->nama_ruangan . ', ' . $lap->lokasi->nama_gedung
            : 'Lokasi tidak diketahui';
        $_foto = $lap->foto ? asset('storage/' . $lap->foto) : null;

        $_logs = [];
        for ($_i = $_step; $_i >= 1; $_i--) {
            $_logs[] = [
                'time'   => ($_i === $_step)
                    ? $lap->updated_at->format('d M Y, H:i') . ' WITA'
                    : $lap->created_at->format('d M Y, H:i') . ' WITA',
                'status' => $_stepLabels[$_i] ?? 'Status',
                'note'   => $_stepNotes[$_i] ?? '',
            ];
        }

        $reportsJson[] = [
            'id'       => $lap->id_laporan,
            'ticket'   => $_tkt,
            'title'    => $_kat,
            'lokasi'   => $_lok,
            'kategori' => $_kat,
            'tingkat'  => $lap->Tingkat_Kerusakan ?? '-',
            'status'   => $_s,
            'reporter' => $nama,
            'date'     => $lap->created_at->format('d M Y, H:i') . ' WITA',
            'step'     => $_step,
            'desc'     => $lap->deskripsi ?? 'Tidak ada deskripsi.',
            'foto'     => $_foto,
            'logs'     => $_logs,
        ];
    }
@endphp
<script>
const reportsData = {!! json_encode($reportsJson, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) !!};

/* ── Fungsi utilitas ────────────────────────────────────── */
function getUrgencyClass(tingkat) {
    const map = { 'Parah': 'badge-danger', 'Sedang': 'badge-warning', 'Rendah': 'badge-success' };
    return map[tingkat] || 'badge-gray';
}

function getStatusClass(status) {
    if (status.includes('Selesai'))    return 'badge-success';
    if (status.includes('Diperbaiki')) return 'badge-primary';
    return 'badge-warning';
}

/* ── Render detail panel kanan ──────────────────────────── */
function renderDetail(report) {
    // Ticket + badge
    document.getElementById('det-ticket').textContent   = 'TICKET // ' + report.ticket;
    document.getElementById('det-title').textContent    = report.title;
    document.getElementById('det-lokasi').textContent   = '📍 ' + report.lokasi;
    document.getElementById('det-reporter').textContent = report.reporter;
    document.getElementById('det-date').textContent     = report.date;
    document.getElementById('det-kategori').textContent = report.kategori;
    document.getElementById('det-tingkat').textContent  = report.tingkat;
    document.getElementById('det-desc').textContent     = report.desc;

    // Status badge
    const sb = document.getElementById('det-status-badge');
    sb.className    = 'badge ' + getStatusClass(report.status);
    sb.textContent  = report.status;

    // Urgency badge
    const ub = document.getElementById('det-urgency-badge');
    ub.className    = 'badge ' + getUrgencyClass(report.tingkat);
    ub.textContent  = '⚠ ' + report.tingkat;

    // Foto
    const fotoWrap = document.getElementById('foto-wrapper');
    if (report.foto) {
        document.getElementById('det-foto').src = report.foto;
        fotoWrap.classList.remove('hidden');
    } else {
        fotoWrap.classList.add('hidden');
    }

    // Step label
    const stepLabels = ['','Laporan Diterima','Diverifikasi','Dalam Pengerjaan','Selesai'];
    document.getElementById('det-step-label').textContent =
        'Tahap ' + report.step + ': ' + (stepLabels[report.step] || '');

    /* ── Stepper Bar ─────────────────────────────────────── */
    const fillMap = { 1: '0%', 2: '33.3%', 3: '66.6%', 4: '95%' };
    document.getElementById('stepper-fill').style.width = fillMap[report.step] || '0%';

    for (let i = 1; i <= 4; i++) {
        const node   = document.getElementById('step-' + i);
        const circle = node.querySelector('.node-circle');
        node.className = 'step-node';

        if (i < report.step) {
            node.classList.add('completed');
            circle.innerHTML = '✓';
        } else if (i === report.step) {
            node.classList.add('active');
            circle.innerHTML = i;
        } else {
            circle.innerHTML = i;
        }
    }

    /* ── Timeline Log ────────────────────────────────────── */
    const tlContainer = document.getElementById('timeline-container');
    tlContainer.innerHTML = '';

    if (!report.logs || report.logs.length === 0) {
        tlContainer.innerHTML = '<p style="color:var(--gray-500);font-size:13px;font-style:italic;">Belum ada riwayat status.</p>';
        return;
    }

    report.logs.forEach((log, idx) => {
        const node = document.createElement('div');
        node.className = 'log-node';
        node.innerHTML = `
            <div class="log-dot"></div>
            <div class="log-content">
                <span class="log-time">🕐 ${log.time}</span>
                <div class="log-status-title">${log.status}</div>
                <p class="log-msg">"${log.note}"</p>
            </div>
        `;
        tlContainer.appendChild(node);
    });
}

/* ── Klik item di list ──────────────────────────────────── */
function selectReport(id) {
    const items = document.querySelectorAll('.report-item');
    items.forEach(el => el.classList.remove('active'));

    const clicked = document.querySelector(`.report-item[data-id="${id}"]`);
    if (clicked) clicked.classList.add('active');

    const report = reportsData.find(r => r.id == id);
    if (report) renderDetail(report);

    // Mobile: sembunyikan list, tampilkan detail
    if (window.innerWidth <= 900) {
        document.querySelector('.panel-list').style.display = 'none';
    }
}

/* ── Back button (mobile) ───────────────────────────────── */
function showList() {
    document.querySelector('.panel-list').style.display = 'flex';
}

/* ── Filter pills ────────────────────────────────────────── */
let currentFilter = 'all';

function filterPill(btn, filter) {
    document.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    currentFilter = filter;
    applyFilter();
}

function filterList(query) {
    applyFilter(query);
}

function applyFilter(query = null) {
    const q = query !== null ? query.toLowerCase() : document.getElementById('search-input').value.toLowerCase();
    document.querySelectorAll('.report-item').forEach(el => {
        const search  = el.dataset.search || '';
        const status  = el.dataset.status || '';
        const tingkat = el.dataset.tingkat || '';

        let showByPill = true;
        if (currentFilter === 'proses')   showByPill = status.includes('diperbaiki') || status.includes('menunggu');
        if (currentFilter === 'selesai')  showByPill = status.includes('selesai');
        if (currentFilter === 'parah')    showByPill = tingkat === 'parah';
        if (currentFilter === 'sedang')   showByPill = tingkat === 'sedang';
        if (currentFilter === 'rendah')   showByPill = tingkat === 'rendah';

        const showBySearch = q === '' || search.includes(q);

        el.style.display = (showByPill && showBySearch) ? '' : 'none';
    });
}

/* ── Init event listeners ───────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    // Pasang listener pada setiap item list
    document.querySelectorAll('.report-item').forEach(el => {
        el.addEventListener('click', () => selectReport(el.dataset.id));
    });

    // Auto-load laporan pertama
    if (reportsData.length > 0) {
        renderDetail(reportsData[0]);
    }
});
</script>
@endif

@endsection
