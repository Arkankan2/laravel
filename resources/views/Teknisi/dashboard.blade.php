@extends('layouts.dashboard')

@section('title', 'Dashboard Teknisi — Sistem Pelaporan Fasilitas Kampus')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/teknisi-dashboard.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
@endpush

{{-- ── SIDEBAR ─────────────────────────────────────────────────── --}}
@section('sidebar-menu')
  <a href="{{ route('teknisi.dashboard') }}">
    <button class="active-menu">🏠 Dashboard</button>
  </a>
  <a href="{{ route('teknisi.tugas') }}">
    <button>🔧 Tugas Saya</button>
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
  <a href="{{ route('teknisi.tugas') }}"><button>🔧 Tugas Saya</button></a>
@endsection

{{-- ── MAIN CONTENT ───────────────────────────────────────────── --}}
@section('content')

@php
  $now  = \Carbon\Carbon::now('Asia/Makassar');
  $hour = (int)$now->format('H');
  if      ($hour >= 5  && $hour < 12) $greeting = 'Selamat Pagi';
  elseif  ($hour >= 12 && $hour < 15) $greeting = 'Selamat Siang';
  elseif  ($hour >= 15 && $hour < 18) $greeting = 'Selamat Sore';
  else                                 $greeting = 'Selamat Malam';
@endphp

<div class="tek-wrap">

  {{-- ── SESSION ALERT ─────────────────────────────────── --}}
  @if(session('success'))
    <div class="tek-alert alert-success" role="alert">
      <span>{{ session('success') }}</span>
    </div>
  @endif

  {{-- ══════════════════════════════════════════════════════
       1. WELCOME BANNER
  ══════════════════════════════════════════════════════ --}}
  <div class="tek-welcome">
    <div class="tek-welcome-text">
      <div class="greeting">{{ $greeting }}, 👷</div>
      <h2>{{ $user->nama ?? $user->username }}</h2>
      <div class="sub-info">
        <span class="info-chip">🪪 Teknisi Fasilitas</span>
        <span class="info-chip">📅 {{ $now->isoFormat('dddd, D MMMM Y') }}</span>
        <span class="info-chip">🕐 {{ $now->format('H:i') }} WITA</span>
      </div>
    </div>
    <div class="tek-welcome-actions">
      <a href="{{ route('teknisi.tugas') }}" class="btn-white">
        🔧 Lihat Tugas Saya
      </a>
      <a href="{{ route('teknisi.tugas', ['tingkat' => 'Parah']) }}" class="btn-ghost-white">
        🚨 Darurat ({{ $darurat }})
      </a>
    </div>
  </div>

  {{-- ══════════════════════════════════════════════════════
       2. STATISTIK KARTU (4 kolom)
  ══════════════════════════════════════════════════════ --}}
  <div class="tek-stats-grid">

    <div class="tek-stat-card sc-total">
      <div class="tek-stat-icon">📋</div>
      <div class="tek-stat-info">
        <div class="tek-stat-number stat-animate">{{ $totalTugas }}</div>
        <div class="tek-stat-label">Total Tugas</div>
        <div class="tek-stat-sub">Semua laporan</div>
      </div>
    </div>

    <div class="tek-stat-card sc-proses">
      <div class="tek-stat-icon">⏳</div>
      <div class="tek-stat-info">
        <div class="tek-stat-number stat-animate">{{ $tugasBaru }}</div>
        <div class="tek-stat-label">Menunggu</div>
        <div class="tek-stat-sub">Perlu ditangani</div>
      </div>
    </div>

    <div class="tek-stat-card sc-done">
      <div class="tek-stat-icon">⚙️</div>
      <div class="tek-stat-info">
        <div class="tek-stat-number stat-animate">{{ $dalamPengerjaan }}</div>
        <div class="tek-stat-label">Dalam Pengerjaan</div>
        <div class="tek-stat-sub">Sedang dikerjakan</div>
      </div>
    </div>

    <div class="tek-stat-card sc-darurat">
      <div class="tek-stat-icon">🚨</div>
      <div class="tek-stat-info">
        <div class="tek-stat-number stat-animate">{{ $darurat }}</div>
        <div class="tek-stat-label">Prioritas Darurat</div>
        <div class="tek-stat-sub">Perlu segera</div>
      </div>
    </div>

  </div>

  {{-- ══════════════════════════════════════════════════════
       3. PROGRESS TIMELINE (Alur Status)
  ══════════════════════════════════════════════════════ --}}
  <div class="tek-panel" style="margin-bottom:24px;">
    <div class="tek-panel-header">
      <h3>📊 Alur Penanganan Laporan</h3>
      <span style="font-size:12px;color:var(--gray-400);">Status progress per laporan</span>
    </div>
    <div class="tek-panel-body">
      <div class="tek-timeline" id="flow-timeline">

        {{-- Step 1: Laporan Masuk --}}
        <div class="tek-timeline-step tl-done">
          <div class="tl-circle">📩</div>
          <div>
            <div class="tl-label">Laporan Masuk</div>
            <div class="tl-sub">✓ Diterima sistem</div>
          </div>
        </div>

        {{-- Step 2: Disposisi Admin --}}
        <div class="tek-timeline-step tl-done">
          <div class="tl-circle">📋</div>
          <div>
            <div class="tl-label">Disposisi Admin</div>
            <div class="tl-sub">✓ Ditugaskan ke teknisi</div>
          </div>
        </div>

        {{-- Step 3: Mulai Perbaikan --}}
        <div class="tek-timeline-step tl-active">
          <div class="tl-circle">🔧</div>
          <div>
            <div class="tl-label">Dalam Pengerjaan</div>
            <div class="tl-sub">⚙️ Proses perbaikan</div>
          </div>
        </div>

        {{-- Step 4: Upload Bukti --}}
        <div class="tek-timeline-step tl-pending">
          <div class="tl-circle">📸</div>
          <div>
            <div class="tl-label">Upload Bukti</div>
            <div class="tl-sub">Foto hasil perbaikan</div>
          </div>
        </div>

        {{-- Step 5: Selesai --}}
        <div class="tek-timeline-step tl-pending">
          <div class="tl-circle">✅</div>
          <div>
            <div class="tl-label">Selesai</div>
            <div class="tl-sub">Notifikasi terkirim</div>
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- ══════════════════════════════════════════════════════
       4. TUGAS TERBARU + QUICK ACTIONS
  ══════════════════════════════════════════════════════ --}}
  <div class="tek-two-col">

    {{-- Tabel Tugas Terbaru --}}
    <div class="tek-panel">
      <div class="tek-panel-header">
        <h3>🔧 Tugas Aktif Terbaru</h3>
        <a href="{{ route('teknisi.tugas') }}" class="panel-link">Lihat Semua →</a>
      </div>
      <div class="tek-table-wrap">
        @if($tugasTerbaru->isEmpty())
          <div class="tek-empty">
            <div class="tek-empty-icon">🎉</div>
            <h4>Tidak Ada Tugas Aktif</h4>
            <p>Semua laporan sudah diselesaikan. Kerja bagus!</p>
          </div>
        @else
          <table class="tek-table">
            <thead>
              <tr>
                <th>Tiket</th>
                <th>Fasilitas & Lokasi</th>
                <th>Prioritas</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($tugasTerbaru as $lap)
              @php
                $pr = $lap->Tingkat_Kerusakan ?? 'Rendah';
                $prioClass = match($pr) {
                  'Rendah' => 'prio-rendah',
                  'Sedang' => 'prio-sedang',
                  'Parah'  => 'prio-tinggi',
                  default  => 'prio-rendah'
                };
                $st = $lap->Status_terkini ?? '';
                $stClass = match(true) {
                  str_contains($st,'Selesai')      => 'st-selesai',
                  str_contains($st,'Pengerjaan')   => 'st-proses',
                  str_contains($st,'Verifikasi')   => 'st-menunggu',
                  default                          => 'st-baru'
                };
              @endphp
              <tr>
                <td>
                  <span class="ticket-badge">RPT-{{ str_pad($lap->id_laporan, 5, '0', STR_PAD_LEFT) }}</span>
                </td>
                <td>
                  <div style="font-weight:600;font-size:13px;color:var(--gray-800);">
                    {{ ucfirst($lap->kategori->nama_kategori ?? 'Fasilitas') }}
                  </div>
                  <div style="font-size:11.5px;color:var(--gray-400);">
                    📍 @if($lap->lokasi) {{ $lap->lokasi->nama_ruangan }}, {{ $lap->lokasi->nama_gedung }} @else Lokasi tidak tersedia @endif
                  </div>
                </td>
                <td><span class="priority-badge {{ $prioClass }}">{{ $pr }}</span></td>
                <td><span class="status-badge {{ $stClass }}">{{ $st }}</span></td>
                <td style="font-size:11.5px;color:var(--gray-400);white-space:nowrap;">
                  {{ $lap->created_at->format('d M Y') }}
                </td>
                <td>
                  <a href="{{ route('teknisi.detail', $lap->id_laporan) }}" class="btn-icon view" title="Lihat Detail">👁</a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        @endif
      </div>
    </div>

    {{-- Quick Actions + Aktivitas --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

      {{-- Quick Actions --}}
      <div class="tek-panel">
        <div class="tek-panel-header">
          <h3>⚡ Aksi Cepat</h3>
        </div>
        <div class="tek-panel-body">
          <div class="tek-quick-actions">
            <a href="{{ route('teknisi.tugas') }}" class="tek-action-item qa-blue">
              <div class="tek-action-icon">🔧</div>
              <div>
                <div class="ai-label">Semua Tugas</div>
                <div class="ai-sub">Kelola semua laporan</div>
              </div>
            </a>
            <a href="{{ route('teknisi.tugas', ['status' => 'Dalam Pengerjaan']) }}" class="tek-action-item qa-orange">
              <div class="tek-action-icon">⚙️</div>
              <div>
                <div class="ai-label">Sedang Dikerjakan</div>
                <div class="ai-sub">{{ $dalamPengerjaan }} laporan aktif</div>
              </div>
            </a>
            <a href="{{ route('teknisi.tugas', ['tingkat' => 'Parah']) }}" class="tek-action-item qa-purple" style="background:var(--tek-danger-soft);">
              <div class="tek-action-icon">🚨</div>
              <div>
                <div class="ai-label">Prioritas Darurat</div>
                <div class="ai-sub">{{ $darurat }} laporan kritis</div>
              </div>
            </a>
            <a href="{{ route('teknisi.tugas', ['status' => 'Selesai']) }}" class="tek-action-item qa-green">
              <div class="tek-action-icon">✅</div>
              <div>
                <div class="ai-label">Riwayat Selesai</div>
                <div class="ai-sub">{{ $selesai }} diselesaikan</div>
              </div>
            </a>
          </div>
        </div>
      </div>

      {{-- Aktivitas Terbaru --}}
      <div class="tek-panel">
        <div class="tek-panel-header">
          <h3>🕐 Aktivitas Terbaru</h3>
        </div>
        <div class="tek-panel-body" style="padding:0 22px 16px;">
          @if($aktivitasTerbaru->isEmpty())
            <div style="padding:24px;text-align:center;color:var(--gray-400);font-size:13px;">
              Belum ada aktivitas tercatat.
            </div>
          @else
            <div class="tek-activity-timeline">
              @foreach($aktivitasTerbaru->take(5) as $lap)
              @php
                $st = $lap->Status_terkini ?? '';
                $isSelesai = str_contains($st, 'Selesai');
                $isProses  = str_contains($st, 'Pengerjaan');
                $dotClass  = $isSelesai ? 'd-green' : ($isProses ? 'd-orange' : 'd-blue');
                $icon      = $isSelesai ? '✅' : ($isProses ? '⚙️' : '📋');
              @endphp
              <div class="tak-item">
                <div class="tak-dot {{ $dotClass }}">{{ $icon }}</div>
                <div class="tak-content">
                  <div class="tak-title">{{ ucfirst($lap->kategori->nama_kategori ?? 'Fasilitas') }}</div>
                  <div class="tak-sub">
                    📍 @if($lap->lokasi) {{ $lap->lokasi->nama_ruangan }} @else Lokasi N/A @endif
                  </div>
                  <div class="tak-time">🕐 {{ $lap->updated_at->diffForHumans() }}</div>
                </div>
              </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>

    </div>
  </div>

</div>{{-- /tek-wrap --}}

@push('scripts')
<script>
  // Animate stat counters
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.stat-animate').forEach(el => {
      const target = parseInt(el.textContent, 10);
      if (isNaN(target) || target === 0) return;
      let cur = 0;
      const step = Math.max(1, Math.floor(target / 30));
      const t = setInterval(() => {
        cur = Math.min(cur + step, target);
        el.textContent = cur;
        if (cur >= target) clearInterval(t);
      }, 30);
    });
  });
</script>
@endpush

@endsection
