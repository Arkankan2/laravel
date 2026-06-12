@extends('layouts.dashboard')

@section('title', 'Riwayat Laporan — Sistem Pelaporan Fasilitas')

{{-- ── SIDEBAR ──────────────────────────────────────────────────── --}}
@section('sidebar-menu')
  <a href="{{ route('admin.dashboard') }}"><button>Dashboard</button></a>
  <a href="#"><button>Verifikasi Laporan</button></a>
  <a href="#"><button>Semua Laporan</button></a>
  <a href="#"><button>Teknisi Tersedia</button></a>
  <a href="{{ route('admin.mahasiswa') }}"><button>Daftar Mahasiswa</button></a>
  <a href="{{ route('admin.riwayat') }}"><button class="active-menu">Riwayat Laporan</button></a>
@endsection

@section('profile-name') {{ $admin->nama_admin ?? $user->username }} @endsection
@section('profile-role') Administrator Sarpras @endsection
@section('profile-buttons')
  <button onclick="toggleProfile()">Edit Profile</button>
@endsection

{{-- ── STYLES ──────────────────────────────────────────────────────── --}}
@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* ════════════════════════════════════════════════════════════════
   RIWAYAT LAPORAN — DATABASE-INTEGRATED PROFESSIONAL REDESIGN
   Theme: Clean White · Blue #2563EB
════════════════════════════════════════════════════════════════ */

.rw-wrap {
  font-family: 'Inter', sans-serif;
  background: #F1F5F9;
  min-height: calc(100vh - 64px);
  padding: 0;
}

/* ── PAGE HERO ── */
.rw-hero {
  background: linear-gradient(135deg, #1E3A5F 0%, #2563EB 100%);
  padding: 28px 32px 24px;
  color: #fff;
}
.rw-hero-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 16px;
}
.rw-hero h1 {
  font-size: 20px;
  font-weight: 800;
  margin: 0 0 4px;
  letter-spacing: 0.3px;
}
.rw-hero p { font-size: 13px; opacity: 0.75; margin: 0; }

/* Stat pills di hero */
.rw-hero-stats {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}
.rw-hero-pill {
  background: rgba(255,255,255,0.15);
  border: 1px solid rgba(255,255,255,0.25);
  border-radius: 999px;
  padding: 6px 16px;
  font-size: 12px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 6px;
  backdrop-filter: blur(6px);
}
.rw-hero-pill i { font-size: 11px; }

/* ── MAIN CONTENT BODY ── */
.rw-body { padding: 24px 32px 40px; }

/* ── FILTER & SEARCH CARD ── */
.rw-filter-card {
  background: #fff;
  border: 1px solid #E2E8F0;
  border-radius: 14px;
  padding: 18px 20px;
  margin-bottom: 20px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}
.rw-filter-form {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  align-items: flex-end;
}
.rw-filter-group { display: flex; flex-direction: column; gap: 5px; }
.rw-filter-group label {
  font-size: 11px;
  font-weight: 600;
  color: #64748B;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.rw-input {
  padding: 9px 13px;
  border: 1.5px solid #E2E8F0;
  border-radius: 9px;
  font-size: 13px;
  color: #334155;
  background: #F8FAFC;
  font-family: 'Inter', sans-serif;
  outline: none;
  transition: border-color .2s, box-shadow .2s;
  min-width: 0;
}
.rw-input:focus {
  border-color: #2563EB;
  box-shadow: 0 0 0 3px rgba(37,99,235,0.10);
  background: #fff;
}
.rw-input.search-input { min-width: 240px; }
.rw-filter-actions { display: flex; gap: 8px; align-items: flex-end; }

.rw-btn {
  padding: 9px 18px;
  border-radius: 9px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  border: none;
  font-family: 'Inter', sans-serif;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: all .2s;
  text-decoration: none;
  white-space: nowrap;
}
.rw-btn-primary { background: #2563EB; color: #fff; }
.rw-btn-primary:hover { background: #1D4ED8; box-shadow: 0 4px 12px rgba(37,99,235,0.3); }
.rw-btn-ghost { background: #F1F5F9; color: #475569; border: 1.5px solid #E2E8F0; }
.rw-btn-ghost:hover { background: #E2E8F0; }

/* Active filter tabs */
.rw-tab-bar {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  margin-bottom: 16px;
}
.rw-tab {
  padding: 7px 16px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  border: 1.5px solid #E2E8F0;
  background: #fff;
  color: #64748B;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: all .2s;
}
.rw-tab:hover { border-color: #2563EB; color: #2563EB; }
.rw-tab.active-tab { background: #2563EB; border-color: #2563EB; color: #fff; }
.rw-tab.tab-proses { }
.rw-tab.tab-proses.active-tab { background: #7C3AED; border-color: #7C3AED; }
.rw-tab.tab-selesai.active-tab { background: #16A34A; border-color: #16A34A; }
.rw-tab-count {
  background: rgba(255,255,255,0.25);
  border-radius: 999px;
  padding: 1px 7px;
  font-size: 10px;
}
.rw-tab:not(.active-tab) .rw-tab-count {
  background: #F1F5F9;
  color: #64748B;
}

/* ── TABLE CARD ── */
.rw-table-card {
  background: #fff;
  border: 1px solid #E2E8F0;
  border-radius: 14px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.05);
  overflow: hidden;
}
.rw-table-head {
  padding: 16px 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid #F1F5F9;
}
.rw-table-title {
  font-size: 14px;
  font-weight: 700;
  color: #0F172A;
  display: flex;
  align-items: center;
  gap: 8px;
}
.rw-table-title i { color: #2563EB; }
.rw-result-count { font-size: 12px; color: #94A3B8; }

.rw-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.rw-table thead th {
  padding: 11px 16px;
  text-align: left;
  font-size: 10.5px;
  font-weight: 700;
  color: #94A3B8;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  background: #FAFAFA;
  border-bottom: 1px solid #F1F5F9;
}
.rw-table tbody td {
  padding: 14px 16px;
  border-bottom: 1px solid #F8FAFC;
  color: #334155;
  vertical-align: middle;
}
.rw-table tbody tr:last-child td { border-bottom: none; }
.rw-table tbody tr { transition: background .12s; cursor: pointer; }
.rw-table tbody tr:hover { background: #F8FAFC; }

/* Badges */
.rw-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 11px;
  font-weight: 600;
  white-space: nowrap;
}
.rw-badge-blue    { background: #EFF6FF; color: #2563EB; }
.rw-badge-violet  { background: #F5F3FF; color: #7C3AED; }
.rw-badge-green   { background: #F0FDF4; color: #16A34A; }
.rw-badge-amber   { background: #FFFBEB; color: #B45309; }
.rw-badge-red     { background: #FEF2F2; color: #DC2626; }
.rw-badge-gray    { background: #F1F5F9; color: #475569; }

/* Avatar inline */
.rw-avatar {
  display: flex;
  align-items: center;
  gap: 9px;
}
.rw-avatar-circle {
  width: 30px; height: 30px;
  border-radius: 50%;
  background: linear-gradient(135deg, #2563EB, #7C3AED);
  display: flex; align-items: center; justify-content: center;
  font-size: 11px; font-weight: 800; color: #fff;
  flex-shrink: 0;
}
.rw-avatar-name { font-weight: 600; font-size: 13px; }

/* Foto thumbnail */
.rw-thumb {
  width: 40px; height: 40px;
  border-radius: 8px;
  object-fit: cover;
  border: 2px solid #E2E8F0;
  background: #F1F5F9;
}
.rw-thumb-placeholder {
  width: 40px; height: 40px;
  border-radius: 8px;
  background: #F1F5F9;
  display: flex; align-items: center; justify-content: center;
  color: #CBD5E1; font-size: 16px;
  border: 2px solid #E2E8F0;
}

/* ID badge */
.rw-id { font-weight: 700; color: #2563EB; font-size: 13px; }

/* Desc truncate */
.rw-desc {
  max-width: 220px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  font-weight: 500;
  color: #1E293B;
}

/* Action button */
.rw-action-btn {
  width: 32px; height: 32px;
  border-radius: 8px;
  border: 1.5px solid #E2E8F0;
  background: #F8FAFC;
  color: #64748B;
  cursor: pointer;
  display: inline-flex; align-items: center; justify-content: center;
  font-size: 13px;
  transition: all .2s;
}
.rw-action-btn:hover { background: #EFF6FF; border-color: #BFDBFE; color: #2563EB; }

/* ── EMPTY STATE ── */
.rw-empty {
  padding: 60px 20px;
  text-align: center;
  color: #94A3B8;
}
.rw-empty i { font-size: 48px; display: block; margin-bottom: 12px; color: #CBD5E1; }
.rw-empty p { font-size: 14px; font-weight: 500; margin-bottom: 16px; }

/* ── PAGINATION ── */
.rw-pagination {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 20px;
  border-top: 1px solid #F1F5F9;
  flex-wrap: wrap;
  gap: 10px;
}
.rw-pagination .pagination { margin: 0; display: flex; gap: 4px; list-style: none; padding: 0; }
.rw-pagination .page-item .page-link {
  padding: 6px 12px;
  border-radius: 7px;
  border: 1.5px solid #E2E8F0;
  color: #475569;
  font-size: 12px;
  font-weight: 600;
  text-decoration: none;
  background: #fff;
  transition: all .15s;
  display: block;
}
.rw-pagination .page-item .page-link:hover { background: #EFF6FF; border-color: #BFDBFE; color: #2563EB; }
.rw-pagination .page-item.active .page-link { background: #2563EB; border-color: #2563EB; color: #fff; }
.rw-pagination .page-item.disabled .page-link { opacity: 0.4; cursor: default; }
.rw-pag-info { font-size: 12px; color: #94A3B8; }

/* ═══════════════════════════════════════════════════════════════
   MODAL DETAIL LAPORAN
═══════════════════════════════════════════════════════════════ */
.rw-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(15,23,42,0.6);
  z-index: 9000;
  align-items: center;
  justify-content: center;
  padding: 20px;
  backdrop-filter: blur(4px);
}
.rw-overlay.open { display: flex; }

.rw-modal {
  background: #fff;
  width: 100%;
  max-width: 560px;
  border-radius: 18px;
  overflow: hidden;
  box-shadow: 0 25px 60px rgba(0,0,0,0.25);
  animation: modalIn .25s cubic-bezier(.34,1.56,.64,1);
}
@keyframes modalIn {
  from { transform: scale(0.85); opacity: 0; }
  to   { transform: scale(1); opacity: 1; }
}

.rw-modal-hdr {
  background: linear-gradient(135deg, #1E3A5F, #2563EB);
  padding: 16px 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  color: #fff;
}
.rw-modal-hdr-left { display: flex; align-items: center; gap: 10px; }
.rw-modal-hdr img { width: 26px; height: 26px; object-fit: contain; }
.rw-modal-hdr-title { font-size: 13px; font-weight: 700; letter-spacing: 0.3px; }
.rw-modal-hdr-id { font-size: 11px; opacity: 0.7; margin-top: 1px; }
.rw-modal-close-btn {
  width: 30px; height: 30px;
  background: rgba(255,255,255,0.2);
  border: none; border-radius: 8px;
  color: #fff; font-size: 14px;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: background .2s;
}
.rw-modal-close-btn:hover { background: rgba(255,255,255,0.35); }

.rw-modal-body { padding: 20px; display: flex; flex-direction: column; gap: 14px; }

.rw-modal-photo {
  width: 100%; height: 200px;
  border-radius: 12px;
  object-fit: cover;
  border: 2px solid #E2E8F0;
  background: #F1F5F9;
}
.rw-modal-photo-placeholder {
  width: 100%; height: 200px;
  border-radius: 12px;
  background: #F1F5F9;
  display: flex; align-items: center; justify-content: center;
  color: #CBD5E1; font-size: 48px;
  border: 2px dashed #E2E8F0;
}

.rw-modal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

.rw-modal-field {
  background: #F8FAFC;
  border: 1px solid #E2E8F0;
  border-radius: 10px;
  padding: 12px 14px;
}
.rw-modal-field.full { grid-column: 1 / -1; }
.rw-modal-field-label {
  font-size: 10px;
  font-weight: 700;
  color: #94A3B8;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  margin-bottom: 4px;
  display: flex;
  align-items: center;
  gap: 5px;
}
.rw-modal-field-value {
  font-size: 13px;
  font-weight: 600;
  color: #0F172A;
  line-height: 1.5;
}

/* ── RESPONSIVE ── */
@media (max-width: 900px) {
  .rw-body { padding: 16px; }
  .rw-hero { padding: 20px 16px; }
  .rw-modal-grid { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
  .rw-filter-form { flex-direction: column; }
  .rw-input.search-input { min-width: 100%; }
  .rw-table thead { display: none; }
  .rw-table tbody td { display: block; padding: 6px 16px; }
  .rw-table tbody td::before {
    content: attr(data-label) ': ';
    font-weight: 700;
    font-size: 10px;
    color: #94A3B8;
    text-transform: uppercase;
  }
}
</style>
@endpush

{{-- ── CONTENT ─────────────────────────────────────────────────────── --}}
@section('content')
<div class="rw-wrap">

  {{-- ── HERO ── --}}
  <div class="rw-hero">
    <div class="rw-hero-inner">
      <div>
        <h1><i class="fa-solid fa-clock-rotate-left" style="margin-right:8px;opacity:.8"></i>Riwayat Laporan</h1>
        <p>Rekap seluruh laporan kerusakan fasilitas kampus</p>
      </div>
      <div class="rw-hero-stats">
        <div class="rw-hero-pill">
          <i class="fa-solid fa-file-lines"></i>
          {{ $statTotal }} Total
        </div>
        <div class="rw-hero-pill">
          <i class="fa-solid fa-screwdriver-wrench"></i>
          {{ $statProses }} Diproses
        </div>
        <div class="rw-hero-pill">
          <i class="fa-solid fa-circle-check"></i>
          {{ $statSelesai }} Selesai
        </div>
      </div>
    </div>
  </div>

  <div class="rw-body">

    {{-- ── SEARCH & FILTER ── --}}
    <div class="rw-filter-card">
      <form method="GET" action="{{ route('admin.riwayat') }}" class="rw-filter-form">

        <div class="rw-filter-group" style="flex:1">
          <label><i class="fa-solid fa-search"></i> Cari Laporan</label>
          <input type="text" name="search" class="rw-input search-input"
                 placeholder="Deskripsi, nama pelapor, lokasi..."
                 value="{{ request('search') }}">
        </div>

        <div class="rw-filter-group">
          <label><i class="fa-solid fa-circle-dot"></i> Status</label>
          <select name="status" class="rw-input">
            <option value="">Semua Status</option>
            <option value="Sedang Diperbaiki" {{ request('status') === 'Sedang Diperbaiki' ? 'selected' : '' }}>Sedang Diperbaiki</option>
            <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
          </select>
        </div>

        <div class="rw-filter-group">
          <label><i class="fa-solid fa-gauge-high"></i> Tingkat Kerusakan</label>
          <select name="tingkat" class="rw-input">
            <option value="">Semua Tingkat</option>
            <option value="Rendah"  {{ request('tingkat') === 'Rendah'  ? 'selected' : '' }}>Rendah</option>
            <option value="Sedang"  {{ request('tingkat') === 'Sedang'  ? 'selected' : '' }}>Sedang</option>
            <option value="Parah"   {{ request('tingkat') === 'Parah'   ? 'selected' : '' }}>Parah</option>
          </select>
        </div>

        <div class="rw-filter-actions">
          <button type="submit" class="rw-btn rw-btn-primary">
            <i class="fa-solid fa-search"></i> Cari
          </button>
          @if(request()->hasAny(['search','status','tingkat']))
          <a href="{{ route('admin.riwayat') }}" class="rw-btn rw-btn-ghost">
            <i class="fa-solid fa-xmark"></i> Reset
          </a>
          @endif
        </div>

      </form>
    </div>

    {{-- ── QUICK FILTER TABS ── --}}
    <div class="rw-tab-bar">
      <a href="{{ route('admin.riwayat') }}"
         class="rw-tab {{ !request('status') ? 'active-tab' : '' }}">
        <i class="fa-solid fa-border-all"></i> Semua
        <span class="rw-tab-count">{{ $statTotal }}</span>
      </a>
      <a href="{{ route('admin.riwayat', ['status' => 'Sedang Diperbaiki']) }}"
         class="rw-tab tab-proses {{ request('status') === 'Sedang Diperbaiki' ? 'active-tab' : '' }}">
        <i class="fa-solid fa-screwdriver-wrench"></i> Sedang Diperbaiki
        <span class="rw-tab-count">{{ $statProses }}</span>
      </a>
      <a href="{{ route('admin.riwayat', ['status' => 'Selesai']) }}"
         class="rw-tab tab-selesai {{ request('status') === 'Selesai' ? 'active-tab' : '' }}">
        <i class="fa-solid fa-circle-check"></i> Selesai
        <span class="rw-tab-count">{{ $statSelesai }}</span>
      </a>
    </div>

    {{-- ── TABLE ── --}}
    <div class="rw-table-card">
      <div class="rw-table-head">
        <div class="rw-table-title">
          <i class="fa-solid fa-table-list"></i>
          Daftar Laporan
        </div>
        <span class="rw-result-count">
          Menampilkan {{ $laporanList->firstItem() ?? 0 }}–{{ $laporanList->lastItem() ?? 0 }}
          dari {{ $laporanList->total() }} laporan
        </span>
      </div>

      @if($laporanList->isEmpty())
        <div class="rw-empty">
          <i class="fa-solid fa-inbox"></i>
          <p>Tidak ada laporan ditemukan</p>
          @if(request()->hasAny(['search','status','tingkat']))
            <a href="{{ route('admin.riwayat') }}" class="rw-btn rw-btn-ghost">
              <i class="fa-solid fa-arrow-left"></i> Hapus Filter
            </a>
          @endif
        </div>
      @else
        <div style="overflow-x:auto">
          <table class="rw-table">
            <thead>
              <tr>
                <th>Foto</th>
                <th>#ID</th>
                <th>Pelapor</th>
                <th>Deskripsi</th>
                <th>Lokasi</th>
                <th>Kategori</th>
                <th>Kerusakan</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($laporanList as $lap)
              @php
                $fotoUrl = null;
                if ($lap->foto) {
                    $fotoUrl = Str::startsWith($lap->foto, ['http','//'])
                        ? $lap->foto
                        : asset('storage/' . $lap->foto);
                }

                // Status mapping
                $statusClass = $lap->Status_terkini === 'Selesai'
                    ? 'rw-badge-green' : 'rw-badge-violet';
                $statusIcon  = $lap->Status_terkini === 'Selesai'
                    ? 'fa-circle-check' : 'fa-screwdriver-wrench';

                // Kerusakan mapping
                $tkClass = match($lap->Tingkat_Kerusakan) {
                    'Parah'  => 'rw-badge-red',
                    'Sedang' => 'rw-badge-amber',
                    default  => 'rw-badge-green',
                };

                // Lokasi
                $lokasi = $lap->lokasi;
                $lokasiStr = collect([
                    $lokasi?->nama_gedung,
                    $lokasi?->nama_ruangan,
                ])->filter()->implode(', ') ?: '–';
              @endphp
              <tr onclick="openDetailModal({{ $lap->id_laporan }})" title="Klik untuk lihat detail">

                <td data-label="Foto">
                  @if($fotoUrl)
                    <img src="{{ $fotoUrl }}" class="rw-thumb" alt="Foto" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div class="rw-thumb-placeholder" style="display:none"><i class="fa-solid fa-image"></i></div>
                  @else
                    <div class="rw-thumb-placeholder"><i class="fa-solid fa-image"></i></div>
                  @endif
                </td>

                <td data-label="ID">
                  <span class="rw-id">#{{ $lap->id_laporan }}</span>
                </td>

                <td data-label="Pelapor">
                  <div class="rw-avatar">
                    <div class="rw-avatar-circle">
                      {{ strtoupper(substr($lap->mahasiswa?->Nama_mahasiswa ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                      <div class="rw-avatar-name">{{ $lap->mahasiswa?->Nama_mahasiswa ?? 'Anonim' }}</div>
                      <div style="font-size:11px;color:#94A3B8">{{ $lap->mahasiswa?->Nim ?? '' }}</div>
                    </div>
                  </div>
                </td>

                <td data-label="Deskripsi">
                  <span class="rw-desc" title="{{ $lap->deskripsi }}">
                    {{ Str::limit($lap->deskripsi, 50) }}
                  </span>
                </td>

                <td data-label="Lokasi" style="font-size:12px;color:#64748B;max-width:140px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                  <i class="fa-solid fa-location-dot" style="color:#2563EB;margin-right:4px"></i>
                  {{ $lokasiStr }}
                </td>

                <td data-label="Kategori">
                  <span class="rw-badge rw-badge-blue">
                    {{ $lap->kategori?->nama_kategori ?? '–' }}
                  </span>
                </td>

                <td data-label="Kerusakan">
                  <span class="rw-badge {{ $tkClass }}">
                    {{ $lap->Tingkat_Kerusakan }}
                  </span>
                </td>

                <td data-label="Status">
                  <span class="rw-badge {{ $statusClass }}">
                    <i class="fa-solid {{ $statusIcon }}" style="font-size:9px"></i>
                    {{ $lap->Status_terkini }}
                  </span>
                </td>

                <td data-label="Tanggal" style="font-size:12px;color:#94A3B8;white-space:nowrap">
                  {{ $lap->created_at?->format('d M Y') }}<br>
                  <span style="font-size:11px">{{ $lap->created_at?->format('H:i') }}</span>
                </td>

                <td data-label="Aksi" onclick="event.stopPropagation()">
                  <button class="rw-action-btn" onclick="openDetailModal({{ $lap->id_laporan }})" title="Lihat Detail">
                    <i class="fa-solid fa-eye"></i>
                  </button>
                </td>

              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{-- Pagination --}}
        <div class="rw-pagination">
          <span class="rw-pag-info">
            Halaman {{ $laporanList->currentPage() }} dari {{ $laporanList->lastPage() }}
          </span>
          {{ $laporanList->links('pagination::bootstrap-4') }}
        </div>
      @endif
    </div>

  </div>
</div>

{{-- ════════════════════════════════════════════════════════════════
     MODAL DETAIL
════════════════════════════════════════════════════════════════ --}}
<div class="rw-overlay" id="rwOverlay" onclick="closeModalIfOverlay(event)">
  <div class="rw-modal" id="rwModal">

    {{-- Modal Header --}}
    <div class="rw-modal-hdr">
      <div class="rw-modal-hdr-left">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" onerror="this.style.display='none'">
        <div>
          <div class="rw-modal-hdr-title">SISTEM PELAPORAN FASILITAS</div>
          <div class="rw-modal-hdr-id" id="mdlId">Detail Laporan</div>
        </div>
      </div>
      <button class="rw-modal-close-btn" onclick="closeModal()">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    {{-- Modal Body --}}
    <div class="rw-modal-body" id="mdlBody">
      <div style="text-align:center;padding:20px;color:#94A3B8">
        <i class="fa-solid fa-spinner fa-spin" style="font-size:24px"></i>
        <p style="margin-top:10px;font-size:13px">Memuat data...</p>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
// Data laporan untuk modal detail (disiapkan dari controller)
const laporanData = @json($laporanJson);

function openDetailModal(id) {
  const d = laporanData.find(l => l.id === id);
  if (!d) return;

  document.getElementById('mdlId').textContent = 'Laporan #' + d.id;

  const statusClass = d.status === 'Selesai'
    ? 'background:#DCFCE7;color:#166534;border:1px solid #86EFAC'
    : 'background:#EDE9FE;color:#5B21B6;border:1px solid #C4B5FD';

  const tkStyle = {
    'Parah':  'background:#FEE2E2;color:#991B1B;border:1px solid #FECACA',
    'Sedang': 'background:#FEF3C7;color:#92400E;border:1px solid #FDE68A',
    'Rendah': 'background:#DCFCE7;color:#166534;border:1px solid #86EFAC',
  }[d.tingkat] ?? '';

  const fotoHtml = d.foto
    ? `<img src="${d.foto}" class="rw-modal-photo" alt="Foto Kerusakan" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">`
      + `<div class="rw-modal-photo-placeholder" style="display:none"><i class="fa-solid fa-image"></i></div>`
    : `<div class="rw-modal-photo-placeholder"><i class="fa-solid fa-image"></i><span style="font-size:13px;margin-top:8px;color:#94A3B8">Foto tidak tersedia</span></div>`;

  document.getElementById('mdlBody').innerHTML = `
    ${fotoHtml}
    <div class="rw-modal-grid">
      <div class="rw-modal-field">
        <div class="rw-modal-field-label"><i class="fa-solid fa-user" style="color:#2563EB"></i> Pelapor</div>
        <div class="rw-modal-field-value">${d.pelapor}</div>
        <div style="font-size:11px;color:#94A3B8;margin-top:2px">NIM: ${d.nim}</div>
      </div>
      <div class="rw-modal-field">
        <div class="rw-modal-field-label"><i class="fa-solid fa-calendar" style="color:#2563EB"></i> Tanggal Lapor</div>
        <div class="rw-modal-field-value">${d.tanggal}</div>
      </div>
      <div class="rw-modal-field full">
        <div class="rw-modal-field-label"><i class="fa-solid fa-file-lines" style="color:#2563EB"></i> Deskripsi Kerusakan</div>
        <div class="rw-modal-field-value" style="white-space:pre-line">${d.deskripsi}</div>
      </div>
      <div class="rw-modal-field">
        <div class="rw-modal-field-label"><i class="fa-solid fa-location-dot" style="color:#2563EB"></i> Lokasi</div>
        <div class="rw-modal-field-value">${d.lokasi}</div>
      </div>
      <div class="rw-modal-field">
        <div class="rw-modal-field-label"><i class="fa-solid fa-tag" style="color:#2563EB"></i> Kategori</div>
        <div class="rw-modal-field-value">${d.kategori}</div>
      </div>
      <div class="rw-modal-field">
        <div class="rw-modal-field-label"><i class="fa-solid fa-gauge-high" style="color:#2563EB"></i> Tingkat Kerusakan</div>
        <div class="rw-modal-field-value">
          <span style="padding:3px 10px;border-radius:999px;font-size:12px;font-weight:700;${tkStyle}">${d.tingkat}</span>
        </div>
      </div>
      <div class="rw-modal-field">
        <div class="rw-modal-field-label"><i class="fa-solid fa-circle-dot" style="color:#2563EB"></i> Status Terkini</div>
        <div class="rw-modal-field-value">
          <span style="padding:3px 10px;border-radius:999px;font-size:12px;font-weight:700;${statusClass}">${d.status}</span>
        </div>
      </div>
    </div>
  `;

  document.getElementById('rwOverlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeModal() {
  document.getElementById('rwOverlay').classList.remove('open');
  document.body.style.overflow = '';
}

function closeModalIfOverlay(e) {
  if (e.target === document.getElementById('rwOverlay')) closeModal();
}

// ESC key closes modal
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
@endpush
