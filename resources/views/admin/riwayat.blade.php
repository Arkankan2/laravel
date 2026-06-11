@extends('layouts.dashboard')

@section('title', 'Riwayat Laporan — Sistem Pelaporan Fasilitas')

{{-- ── SIDEBAR ── --}}
@section('sidebar-menu')
  <a href="{{ route('admin.dashboard') }}"><button>Dashboard</button></a>
  <a href="#"><button>Verifikasi Laporan</button></a>
  <a href="#"><button>Semua Laporan</button></a>
  <a href="#"><button>Teknisi Tersedia</button></a>
  <a href="{{ route('admin.mahasiswa') }}"><button>Daftar Mahasiswa</button></a>
  <a href="{{ route('admin.riwayat') }}"><button class="active-menu">Riwayat Laporan</button></a>
@endsection

{{-- ── PROFILE ── --}}
@section('profile-name') {{ $admin->nama_admin ?? $user->username }} @endsection
@section('profile-role') Administrator @endsection
@section('profile-buttons')
  <button onclick="toggleProfile()">Edit Profile</button>
@endsection

{{-- ── EXTRA STYLES ── --}}
@push('styles')
  <link rel="stylesheet" href="{{ asset('css/riwayat.css') }}">
@endpush

{{-- ── MAIN CONTENT ── --}}
@section('content')

  {{-- PAGE TITLE --}}
  <div class="page-title-section">
    <div class="page-title-inner">
      <h2>RIWAYAT LAPORAN</h2>
    </div>
  </div>

  {{-- FILTER BAR --}}
  <div class="filter-bar">
    <button class="filter-btn active-filter" onclick="filterStatus('semua', this)">Semua</button>
    <button class="filter-btn filter-green"  onclick="filterStatus('selesai', this)">✔ Selesai</button>
    <button class="filter-btn filter-gray"   onclick="filterStatus('perbaikan', this)">⚙ Dalam Perbaikan</button>
    <button class="filter-btn filter-red"    onclick="filterStatus('ditunda', this)">✕ Ditunda</button>
  </div>

  {{-- REPORT LIST --}}
  <div class="report-box" id="reportBox">

    @forelse ($laporanList as $item)
      @php
        $statusMap = [
          'selesai'   => ['label' => 'SELESAI',          'class' => 'status-selesai'],
          'perbaikan' => ['label' => 'DALAM PERBAIKAN',   'class' => 'status-perbaikan'],
          'ditunda'   => ['label' => 'PERBAIKAN DITUNDA', 'class' => 'status-ditunda'],
        ];
        $s = $statusMap[$item['status_key']] ?? $statusMap['perbaikan'];
      @endphp

      <div class="report-item"
           data-status="{{ $item['status_key'] }}"
           onclick="openModal(
             {{ Js::from($item['foto']) }},
             {{ Js::from($item['deskripsi']) }},
             {{ Js::from($item['lokasi']) }},
             {{ Js::from($item['tanggal']) }},
             {{ Js::from($item['status']) }},
             {{ Js::from($item['status_key']) }}
           )">

        <div class="report-text">
          <span class="report-desc">{{ $item['deskripsi'] }}</span>
          <span class="report-loc">📍 {{ $item['lokasi'] }}</span>
          <span class="report-time">🕐 {{ $item['tanggal'] }}</span>
        </div>

        <div class="report-status-badge {{ $s['class'] }}">
          {{ $s['label'] }}
        </div>
      </div>

    @empty
      <div class="empty-state">
        <div class="empty-icon">📋</div>
        <p>Belum ada laporan yang tercatat</p>
      </div>
    @endforelse

    {{-- Empty state saat filter aktif --}}
    <div class="empty-state" id="emptyState" style="display:none;">
      <div class="empty-icon">🔍</div>
      <p>Tidak ada laporan dengan status ini</p>
    </div>

  </div>

@endsection

{{-- ── MODAL (rendered after body via @push) ── --}}
@push('scripts')

  {{-- MODAL OVERLAY --}}
  <div id="modalOverlay" class="rw-modal-overlay" onclick="handleOverlayClick(event)">
    <div class="rw-modal-card" id="rwModalCard">

      {{-- Modal Header --}}
      <div class="rw-modal-header">
        <div class="rw-modal-logo">
          <img src="{{ asset('images/logo.png') }}" alt="Logo SPF">
          <span>SISTEM PELAPORAN FASILITAS</span>
        </div>
        <button class="rw-modal-close" onclick="closeModal()">✕</button>
      </div>

      {{-- Modal Body --}}
      <div class="rw-modal-body">

        <div class="rw-img-frame">
          <img id="rwModalImg" src="" alt="Foto Kerusakan">
          <div class="rw-img-label">Foto Kerusakan</div>
        </div>

        <div class="rw-info-box">
          <div class="rw-info-label">📝 Deskripsi</div>
          <p id="rwModalDesc">–</p>
        </div>

        <div class="rw-info-box">
          <div class="rw-info-label">📍 Lokasi</div>
          <p id="rwModalLoc">–</p>
        </div>

        <div class="rw-info-box">
          <div class="rw-info-label">🕐 Waktu Laporan</div>
          <p id="rwModalTime">–</p>
        </div>

        <div class="rw-status-section">
          <div class="rw-status-title">STATUS</div>
          <button id="rwModalStatusBtn" class="rw-status-btn">–</button>
        </div>

      </div>
    </div>
  </div>

  <script src="{{ asset('js/riwayat.js') }}"></script>
@endpush
