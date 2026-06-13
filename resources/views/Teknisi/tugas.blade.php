@extends('layouts.dashboard')

@section('title', 'Tugas Saya — Teknisi Sistem Pelaporan Fasilitas')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/teknisi-dashboard.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
@endpush

{{-- ── SIDEBAR ─────────────────────────────────────────────────── --}}
@section('sidebar-menu')
  <a href="{{ route('teknisi.dashboard') }}">
    <button>🏠 Dashboard</button>
  </a>
  <a href="{{ route('teknisi.tugas') }}">
    <button class="active-menu">🔧 Tugas Saya</button>
  </a>
  <a href="{{ route('teknisi.tugas', ['status' => 'Dalam Pengerjaan']) }}">
    <button>⚙️ Dalam Pengerjaan</button>
  </a>
  <a href="{{ route('teknisi.tugas', ['status' => 'Selesai']) }}">
    <button>✅ Riwayat Selesai</button>
  </a>
@endsection

@section('profile-name') {{ $user->nama ?? $user->username }} @endsection
@section('profile-role') Teknisi Fasilitas @endsection
@section('profile-buttons')
  <a href="{{ route('teknisi.dashboard') }}"><button>🏠 Dashboard</button></a>
@endsection

@section('content')

<div class="tek-wrap">

  {{-- ── SESSION ALERT ─────────────────────────────────── --}}
  @if(session('success'))
    <div class="tek-alert alert-success">✅ {{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="tek-alert alert-danger">❌ {{ session('error') }}</div>
  @endif

  {{-- ══════════════════════════════════════════════════════
       PAGE TITLE & BREADCRUMB
  ══════════════════════════════════════════════════════ --}}
  <nav class="tek-breadcrumb" aria-label="breadcrumb">
    <a href="{{ route('teknisi.dashboard') }}">🏠 Dashboard</a>
    <span class="sep">›</span>
    <span class="current">🔧 Tugas Saya</span>
  </nav>

  <div class="tek-page-title">
    <div>
      <h1>🔧 Tugas Saya</h1>
      <div class="page-subtitle">Daftar semua laporan kerusakan yang perlu ditangani</div>
    </div>
    <div class="page-actions">
      <a href="{{ route('teknisi.tugas', ['tingkat' => 'Parah']) }}" class="btn-danger btn-sm">
        🚨 Darurat
      </a>
      <a href="{{ route('teknisi.dashboard') }}" class="btn-outline btn-sm">
        ← Dashboard
      </a>
    </div>
  </div>

  {{-- ══════════════════════════════════════════════════════
       STAT BADGES FILTER
  ══════════════════════════════════════════════════════ --}}
  <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;">
    <a href="{{ route('teknisi.tugas') }}"
       style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:9999px;font-size:12px;font-weight:700;text-decoration:none;transition:all .2s;
              {{ !request()->filled('status') && !request()->filled('tingkat') ? 'background:var(--tek-primary);color:#fff;box-shadow:0 2px 8px rgba(15,76,129,.25)' : 'background:var(--gray-100);color:var(--gray-600)' }}">
      📋 Semua <span style="background:rgba(255,255,255,.3);padding:1px 7px;border-radius:9999px;font-size:11px;">{{ $statTotal }}</span>
    </a>
    <a href="{{ route('teknisi.tugas', ['status' => 'Sedang Diperbaiki']) }}"
       style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:9999px;font-size:12px;font-weight:700;text-decoration:none;transition:all .2s;
              {{ request('status') === 'Sedang Diperbaiki' ? 'background:var(--tek-warning);color:#fff;box-shadow:0 2px 8px rgba(245,158,11,.25)' : 'background:var(--gray-100);color:var(--gray-600)' }}">
      ⏳ Menunggu <span style="padding:1px 7px;border-radius:9999px;font-size:11px;background:rgba(0,0,0,.1);">{{ $statBaru }}</span>
    </a>
    <a href="{{ route('teknisi.tugas', ['status' => 'Dalam Pengerjaan']) }}"
       style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:9999px;font-size:12px;font-weight:700;text-decoration:none;transition:all .2s;
              {{ request('status') === 'Dalam Pengerjaan' ? 'background:var(--tek-accent);color:#fff;box-shadow:0 2px 8px rgba(255,107,53,.25)' : 'background:var(--gray-100);color:var(--gray-600)' }}">
      ⚙️ Dikerjakan <span style="padding:1px 7px;border-radius:9999px;font-size:11px;background:rgba(0,0,0,.1);">{{ $statProses }}</span>
    </a>
    <a href="{{ route('teknisi.tugas', ['status' => 'Selesai']) }}"
       style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:9999px;font-size:12px;font-weight:700;text-decoration:none;transition:all .2s;
              {{ request('status') === 'Selesai' ? 'background:var(--tek-success);color:#fff;box-shadow:0 2px 8px rgba(16,185,129,.25)' : 'background:var(--gray-100);color:var(--gray-600)' }}">
      ✅ Selesai <span style="padding:1px 7px;border-radius:9999px;font-size:11px;background:rgba(0,0,0,.1);">{{ $statSelesai }}</span>
    </a>
  </div>

  {{-- ══════════════════════════════════════════════════════
       MAIN TABLE PANEL
  ══════════════════════════════════════════════════════ --}}
  <div class="tek-panel">

    {{-- FILTER BAR --}}
    <div class="tek-filter-bar">
      <form method="GET" action="{{ route('teknisi.tugas') }}" style="display:contents;">
        <div class="tek-search-box">
          <span class="search-icon">🔍</span>
          <input type="text" name="search" placeholder="Cari laporan, lokasi, pelapor…"
                 value="{{ request('search') }}">
        </div>
        <select name="tingkat" class="tek-filter-select" onchange="this.form.submit()">
          <option value="">🎯 Semua Prioritas</option>
          <option value="Rendah" {{ request('tingkat') === 'Rendah' ? 'selected' : '' }}>🟢 Rendah</option>
          <option value="Sedang" {{ request('tingkat') === 'Sedang' ? 'selected' : '' }}>🟡 Sedang</option>
          <option value="Parah"  {{ request('tingkat') === 'Parah'  ? 'selected' : '' }}>🔴 Tinggi / Darurat</option>
        </select>
        <button type="submit" class="btn-primary btn-sm">🔍 Cari</button>
        @if(request()->hasAny(['search', 'status', 'tingkat']))
          <a href="{{ route('teknisi.tugas') }}" class="btn-outline btn-sm">✕ Reset</a>
        @endif
      </form>
    </div>

    {{-- TABLE --}}
    <div class="tek-table-wrap">
      @if($laporanList->isEmpty())
        <div class="tek-empty">
          <div class="tek-empty-icon">📭</div>
          <h4>Tidak Ada Laporan Ditemukan</h4>
          <p>Coba ubah filter pencarian atau cek kembali nanti.</p>
        </div>
      @else
        <table class="tek-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Tiket</th>
              <th>Fasilitas</th>
              <th>Lokasi</th>
              <th>Pelapor</th>
              <th>Prioritas</th>
              <th>Status</th>
              <th>Tanggal</th>
              <th style="text-align:center;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($laporanList as $i => $lap)
            @php
              $pr = $lap->Tingkat_Kerusakan ?? 'Rendah';
              $prioClass = match($pr) {
                'Rendah' => 'prio-rendah',
                'Sedang' => 'prio-sedang',
                'Parah'  => 'prio-tinggi',
                default  => 'prio-rendah'
              };
              $prioIcon = match($pr) {
                'Rendah' => '🟢',
                'Sedang' => '🟡',
                'Parah'  => '🔴',
                default  => '⚪'
              };
              $st = $lap->Status_terkini ?? '';
              $stClass = match(true) {
                str_contains($st,'Selesai')         => 'st-selesai',
                str_contains($st,'Pengerjaan')      => 'st-proses',
                $st === 'Sedang Diperbaiki'         => 'st-menunggu',
                default                             => 'st-baru'
              };
              $stLabel = match(true) {
                str_contains($st,'Selesai')         => 'Selesai',
                str_contains($st,'Pengerjaan')      => 'Dalam Pengerjaan',
                $st === 'Sedang Diperbaiki'         => 'Menunggu Penanganan',
                default                             => $st
              };
            @endphp
            <tr>
              <td style="color:var(--gray-400);font-size:12px;">
                {{ $laporanList->firstItem() + $i }}
              </td>
              <td>
                <span class="ticket-badge">RPT-{{ str_pad($lap->id_laporan, 5, '0', STR_PAD_LEFT) }}</span>
              </td>
              <td>
                <div style="font-weight:600;font-size:13px;color:var(--gray-800);max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                  {{ ucfirst($lap->kategori->nama_kategori ?? 'Fasilitas') }}
                </div>
                <div style="font-size:11px;color:var(--gray-400);margin-top:2px;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                  {{ Str::limit($lap->deskripsi ?? '-', 40) }}
                </div>
              </td>
              <td style="font-size:12.5px;color:var(--gray-600);">
                @if($lap->lokasi)
                  <div>{{ $lap->lokasi->nama_ruangan }}</div>
                  <div style="font-size:11px;color:var(--gray-400);">{{ $lap->lokasi->nama_gedung }}</div>
                @else
                  <span style="color:var(--gray-300);">—</span>
                @endif
              </td>
              <td style="font-size:12.5px;color:var(--gray-600);">
                {{ $lap->mahasiswa->Nama_mahasiswa ?? 'Anonim' }}
                @if($lap->mahasiswa?->Nim)
                  <div style="font-size:11px;color:var(--gray-400);">{{ $lap->mahasiswa->Nim }}</div>
                @endif
              </td>
              <td>
                <span class="priority-badge {{ $prioClass }}">{{ $prioIcon }} {{ $pr }}</span>
              </td>
              <td>
                <span class="status-badge {{ $stClass }}">{{ $stLabel }}</span>
              </td>
              <td style="font-size:11.5px;color:var(--gray-400);white-space:nowrap;">
                {{ $lap->created_at->format('d M Y') }}
                <div>{{ $lap->created_at->format('H:i') }} WITA</div>
              </td>
              <td style="text-align:center;">
                <a href="{{ route('teknisi.detail', $lap->id_laporan) }}"
                   class="btn-primary btn-sm" title="Lihat & Kelola Tugas">
                  👁 Detail
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

        {{-- PAGINATION --}}
        @if($laporanList->hasPages())
        <div style="padding:16px 20px;border-top:1px solid var(--gray-100);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
          <div style="font-size:12.5px;color:var(--gray-500);">
            Menampilkan {{ $laporanList->firstItem() }}–{{ $laporanList->lastItem() }} dari {{ $laporanList->total() }} laporan
          </div>
          <div style="display:flex;gap:4px;">
            {{-- Prev --}}
            @if($laporanList->onFirstPage())
              <span style="padding:6px 12px;border-radius:var(--radius-sm);background:var(--gray-100);color:var(--gray-300);font-size:12px;cursor:not-allowed;">‹</span>
            @else
              <a href="{{ $laporanList->previousPageUrl() }}"
                 style="padding:6px 12px;border-radius:var(--radius-sm);background:var(--gray-100);color:var(--gray-600);font-size:12px;text-decoration:none;transition:all .2s;"
                 onmouseover="this.style.background='var(--tek-primary-soft)'"
                 onmouseout="this.style.background='var(--gray-100)'">‹</a>
            @endif

            {{-- Pages --}}
            @foreach($laporanList->getUrlRange(max(1,$laporanList->currentPage()-2), min($laporanList->lastPage(),$laporanList->currentPage()+2)) as $page => $url)
              @if($page === $laporanList->currentPage())
                <span style="padding:6px 12px;border-radius:var(--radius-sm);background:var(--tek-primary);color:#fff;font-size:12px;font-weight:700;">{{ $page }}</span>
              @else
                <a href="{{ $url }}"
                   style="padding:6px 12px;border-radius:var(--radius-sm);background:var(--gray-100);color:var(--gray-600);font-size:12px;text-decoration:none;transition:all .2s;"
                   onmouseover="this.style.background='var(--tek-primary-soft)'"
                   onmouseout="this.style.background='var(--gray-100)'">{{ $page }}</a>
              @endif
            @endforeach

            {{-- Next --}}
            @if($laporanList->hasMorePages())
              <a href="{{ $laporanList->nextPageUrl() }}"
                 style="padding:6px 12px;border-radius:var(--radius-sm);background:var(--gray-100);color:var(--gray-600);font-size:12px;text-decoration:none;transition:all .2s;"
                 onmouseover="this.style.background='var(--tek-primary-soft)'"
                 onmouseout="this.style.background='var(--gray-100)'">›</a>
            @else
              <span style="padding:6px 12px;border-radius:var(--radius-sm);background:var(--gray-100);color:var(--gray-300);font-size:12px;cursor:not-allowed;">›</span>
            @endif
          </div>
        </div>
        @endif

      @endif
    </div>
  </div>

</div>{{-- /tek-wrap --}}

@endsection
