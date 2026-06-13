@extends('layouts.dashboard')

@section('title', 'Dashboard Mahasiswa — Sistem Pelaporan Fasilitas')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/mahasiswa-dashboard.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@endpush

{{-- ── SIDEBAR ──────────────────────────────────────────────── --}}
@section('sidebar-menu')
  <a href="{{ route('mahasiswa.dashboard') }}">
    <button class="active-menu">🏠 Dashboard</button>
  </a>
  <a href="{{ route('laporan.create') }}">
    <button>📋 Buat Laporan</button>
  </a>
  <a href="{{ route('laporan.pantau') }}">
    <button>🔍 Pantau Laporan</button>
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

{{-- ── MAIN CONTENT ─────────────────────────────────────────── --}}
@section('content')

@php
  $nama    = $mhs->Nama_mahasiswa ?? $user->username;
  $nim     = $mhs->Nim ?? '-';
  $prodi   = $mhs->prodi ?? 'Mahasiswa';
  $now     = \Carbon\Carbon::now('Asia/Makassar');

  // Determine greeting based on time
  $hour = (int)$now->format('H');
  if ($hour >= 5 && $hour < 12)       $greeting = 'Selamat Pagi';
  elseif ($hour >= 12 && $hour < 15)  $greeting = 'Selamat Siang';
  elseif ($hour >= 15 && $hour < 18)  $greeting = 'Selamat Sore';
  else                                 $greeting = 'Selamat Malam';

  // Status badge helper (closure to avoid redeclaration)
  $statusBadge = function(string $status): string {
    if (str_contains($status, 'Selesai'))     return '<span class="status-badge badge-selesai">Selesai</span>';
    if (str_contains($status, 'Diperbaiki'))  return '<span class="status-badge badge-proses">Dalam Pengerjaan</span>';
    if (str_contains($status, 'Verifikasi')) return '<span class="status-badge badge-menunggu">Menunggu Verifikasi</span>';
    return '<span class="status-badge badge-default">' . e($status) . '</span>';
  };

  $severityBadge = function(string $severity): string {
    return match($severity) {
      'Rendah' => '<span class="severity-badge sev-rendah">Rendah</span>',
      'Sedang' => '<span class="severity-badge sev-sedang">Sedang</span>',
      'Parah'  => '<span class="severity-badge sev-parah">Parah</span>',
      default  => '<span class="severity-badge sev-rendah">' . e($severity) . '</span>',
    };
  };

  // Progress tracker steps
  $steps = [
    ['icon' => '📩', 'label' => 'Laporan Dibuat',        'timestamp' => 'Diterima sistem'],
    ['icon' => '🔍', 'label' => 'Menunggu Verifikasi',   'timestamp' => 'Menunggu admin'],
    ['icon' => '🔧', 'label' => 'Dalam Pengerjaan',      'timestamp' => 'Teknisi ditugaskan'],
    ['icon' => '✅', 'label' => 'Selesai',               'timestamp' => 'Perbaikan selesai'],
  ];

  // Determine active step based on laporanAktif status
  $activeStep = 0;
  if ($laporanAktif) {
    $s = $laporanAktif->Status_terkini ?? '';
    if (str_contains($s, 'Verifikasi'))  $activeStep = 1;
    elseif (str_contains($s, 'Diperbaiki')) $activeStep = 2;
    elseif (str_contains($s, 'Selesai'))    $activeStep = 3;
    else $activeStep = 1;
  }

  $stepCount   = count($steps);
  $fillPercent = $stepCount > 1 ? ($activeStep / ($stepCount - 1)) * 100 : 0;
@endphp

<div class="mhs-dashboard">

  {{-- ── SESSION SUCCESS ALERT ─────────────────────────── --}}
  @if(session('success'))
    <div class="alert-success" role="alert">
      ✅ <span>{{ session('success') }}</span>
    </div>
  @endif

  {{-- ══════════════════════════════════════════════════════
       1. WELCOME BANNER
  ══════════════════════════════════════════════════════ --}}
  <div class="welcome-banner">
    <div class="welcome-text">
      <div class="greeting">{{ $greeting }}, 👋</div>
      <h2>{{ $nama }}</h2>
      <div class="sub-info">
        <span class="info-item">🎓 {{ $prodi }}</span>
        <span class="info-item">🪪 NIM: {{ $nim }}</span>
        <span class="info-item">📅 {{ $now->isoFormat('dddd, D MMMM Y') }}</span>
      </div>
    </div>
    <div class="welcome-actions">
      <a href="{{ route('laporan.create') }}" class="btn-primary-white">
        ➕ Buat Laporan Baru
      </a>
    </div>
  </div>

  {{-- ══════════════════════════════════════════════════════
       2. STATISTIK RINGKAS (4 kartu)
  ══════════════════════════════════════════════════════ --}}
  <div class="stats-grid">
    {{-- Total Laporan --}}
    <div class="stat-card total">
      <div class="stat-icon">📋</div>
      <div class="stat-info">
        <div class="stat-number">{{ $totalLaporan }}</div>
        <div class="stat-label">Total Laporan</div>
        <div class="stat-sub">Semua laporan Anda</div>
      </div>
    </div>

    {{-- Menunggu Verifikasi --}}
    <div class="stat-card menunggu">
      <div class="stat-icon">⏳</div>
      <div class="stat-info">
        <div class="stat-number">{{ $menunggu }}</div>
        <div class="stat-label">Sedang Diproses</div>
        <div class="stat-sub">Menunggu tindak lanjut</div>
      </div>
    </div>

    {{-- Dalam Pengerjaan --}}
    <div class="stat-card proses">
      <div class="stat-icon">🔧</div>
      <div class="stat-info">
        <div class="stat-number">{{ $dalamProses }}</div>
        <div class="stat-label">Dalam Pengerjaan</div>
        <div class="stat-sub">Teknisi aktif bekerja</div>
      </div>
    </div>

    {{-- Selesai --}}
    <div class="stat-card selesai">
      <div class="stat-icon">✅</div>
      <div class="stat-info">
        <div class="stat-number">{{ $selesai }}</div>
        <div class="stat-label">Selesai</div>
        <div class="stat-sub">Perbaikan terselesaikan</div>
      </div>
    </div>
  </div>

  {{-- ══════════════════════════════════════════════════════
       3. PROGRESS LAPORAN AKTIF
  ══════════════════════════════════════════════════════ --}}
  <div class="progress-section">
    <div class="section-header">
      <h3><span class="icon">📊</span> Progress Laporan Aktif</h3>
      @if($laporanAktif)
        <a href="{{ route('laporan.pantau') }}">Lihat Detail →</a>
      @endif
    </div>

    @if($laporanAktif)
      {{-- Laporan aktif info --}}
      <div style="background:#EFF6FF;border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#1E3A5F;display:flex;align-items:center;gap:10px;">
        <span style="font-size:18px;">🔧</span>
        <div>
          <strong>{{ $laporanAktif->kategori->nama_kategori ?? 'Fasilitas' }}</strong>
          @if($laporanAktif->lokasi)
            — {{ $laporanAktif->lokasi->nama_ruangan }}, {{ $laporanAktif->lokasi->nama_gedung }}
          @endif
          <div style="font-size:11px;color:#64748B;margin-top:2px;">
            Dilaporkan {{ $laporanAktif->created_at->diffForHumans() }}
          </div>
        </div>
        {!! $statusBadge($laporanAktif->Status_terkini ?? 'Sedang Diperbaiki') !!}
      </div>

      {{-- Steps --}}
      <div class="progress-tracker">
        <div class="progress-connector">
          <div class="progress-connector-fill" style="width:{{ $fillPercent }}%"></div>
        </div>

        @foreach($steps as $i => $step)
          @php
            if ($i < $activeStep)      $stepClass = 'done';
            elseif ($i === $activeStep) $stepClass = 'active';
            else                        $stepClass = 'pending';
          @endphp
          <div class="progress-step {{ $stepClass }}">
            <div class="step-circle">{{ $step['icon'] }}</div>
            <div class="step-label">{{ $step['label'] }}</div>
            <div class="step-timestamp">{{ $step['timestamp'] }}</div>
          </div>
        @endforeach
      </div>
    @elseif($totalLaporan === 0)
      <div class="no-active-label">
        📭 Belum ada laporan aktif. Buat laporan pertama Anda!
      </div>
    @else
      <div class="no-active-label">
        🎉 Semua laporan Anda telah diselesaikan. Tidak ada laporan aktif saat ini.
      </div>
    @endif
  </div>

  {{-- ══════════════════════════════════════════════════════
       4. LAPORAN TERBARU + AKTIVITAS TERBARU (2-kolom)
  ══════════════════════════════════════════════════════ --}}
  <div class="two-col-grid">

    {{-- ── Laporan Terbaru ─────────────────────────── --}}
    <div class="panel">
      <div class="section-header" style="margin-bottom:12px;">
        <h3>📁 Laporan Terbaru Saya</h3>
        <a href="{{ route('laporan.status') }}">Semua Laporan →</a>
      </div>

      @if($laporanTerbaru->isEmpty())
        <div class="empty-state">
          <div class="empty-icon-wrap">📋</div>
          <h4>Belum Ada Laporan</h4>
          <p>Belum ada laporan yang dibuat. Klik tombol di bawah untuk melaporkan kerusakan fasilitas kampus.</p>
          <a href="{{ route('laporan.create') }}" class="btn-create">
            ➕ Buat Laporan Pertama
          </a>
        </div>
      @else
        <table class="laporan-table">
          <thead>
            <tr>
              <th>Tiket</th>
              <th>Kerusakan</th>
              <th>Status</th>
              <th>Tingkat</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody>
            @foreach($laporanTerbaru as $lap)
            <tr>
              <td>
                <span class="ticket-badge">
                  RPT-{{ str_pad($lap->id_laporan, 5, '0', STR_PAD_LEFT) }}
                </span>
              </td>
              <td>
                <div class="laporan-title">
                  {{ ucfirst($lap->kategori->nama_kategori ?? 'Fasilitas') }}
                </div>
                <div class="laporan-lokasi">
                  📍
                  @if($lap->lokasi)
                    {{ $lap->lokasi->nama_ruangan }}, {{ $lap->lokasi->nama_gedung }}
                  @else
                    Lokasi tidak diketahui
                  @endif
                </div>
              </td>
              <td>{!! $statusBadge($lap->Status_terkini ?? '-') !!}</td>
              <td>{!! $severityBadge($lap->Tingkat_Kerusakan ?? '-') !!}</td>
              <td style="font-size:11.5px;color:#64748B;white-space:nowrap;">
                {{ $lap->created_at->format('d M Y') }}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>

    {{-- ── Aktivitas Terbaru (Timeline) ────────────── --}}
    <div class="panel">
      <div class="section-header" style="margin-bottom:12px;">
        <h3>🕐 Aktivitas Terbaru</h3>
      </div>

      @if($laporanTerbaru->isEmpty())
        <div style="padding:24px;text-align:center;color:#94A3B8;font-size:13px;font-style:italic;">
          Belum ada aktivitas yang tercatat.
        </div>
      @else
        <div class="timeline">
          @foreach($laporanTerbaru->take(4) as $lap)
            @php
              $status = $lap->Status_terkini ?? 'Sedang Diperbaiki';
              $isSelesai = str_contains($status, 'Selesai');
              $dotClass  = $isSelesai ? 'dot-green' : 'dot-blue';
              $icon      = $isSelesai ? '✅' : '🔧';
              $kategori  = ucfirst($lap->kategori->nama_kategori ?? 'Fasilitas');
              $lokasi    = $lap->lokasi ? $lap->lokasi->nama_ruangan : 'Lokasi tidak diketahui';
            @endphp
            <div class="timeline-item">
              <div class="timeline-dot {{ $dotClass }}">{{ $icon }}</div>
              <div class="timeline-content">
                <div class="t-title">{{ $kategori }} — {{ $lokasi }}</div>
                <div class="t-desc">
                  Tingkat kerusakan: <strong>{{ $lap->Tingkat_Kerusakan ?? '-' }}</strong>
                </div>
                <div class="t-time">{{ $lap->created_at->diffForHumans() }} · {{ $lap->created_at->format('d M Y, H:i') }} WITA</div>
              </div>
            </div>
          @endforeach

          {{-- Entry: Akun dibuat sebagai base activity --}}
          <div class="timeline-item">
            <div class="timeline-dot dot-purple">🎓</div>
            <div class="timeline-content">
              <div class="t-title">Akun Mahasiswa Aktif</div>
              <div class="t-desc">Sistem siap menerima laporan kerusakan fasilitas.</div>
              <div class="t-time">Selamat datang, {{ $nama }}!</div>
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>

  {{-- ══════════════════════════════════════════════════════
       5. PENGUMUMAN SARPRAS
  ══════════════════════════════════════════════════════ --}}
  <div class="announce-section">
    <div class="announce-header">
      <span class="icon">📣</span>
      <h3>Pengumuman Sarpras</h3>
    </div>
    <div class="announce-list">
      <div class="announce-item">
        <div class="announce-date-badge">
          <div class="day">15</div>
          <div class="month">Jun</div>
        </div>
        <div class="announce-text">
          <div class="a-title">Perbaikan Jaringan Internet Kampus</div>
          <div class="a-desc">Seluruh gedung — Maintenance jaringan oleh tim IT. Layanan mungkin terganggu sementara.</div>
        </div>
      </div>
      <div class="announce-item">
        <div class="announce-date-badge">
          <div class="day">18</div>
          <div class="month">Jun</div>
        </div>
        <div class="announce-text">
          <div class="a-title">Perawatan Berkala AC Gedung B</div>
          <div class="a-desc">Gedung B lantai 1–3 — Servis AC rutin oleh teknisi. Gedung tetap beroperasi normal.</div>
        </div>
      </div>
      <div class="announce-item">
        <div class="announce-date-badge">
          <div class="day">20</div>
          <div class="month">Jun</div>
        </div>
        <div class="announce-text">
          <div class="a-title">Pembersihan & Pemeriksaan Lab Komputer</div>
          <div class="a-desc">Lab A & B — Pemeriksaan unit komputer dan periferal. Lab ditutup sementara mulai pukul 07.00.</div>
        </div>
      </div>
    </div>
  </div>

  {{-- ══════════════════════════════════════════════════════
       6. QUICK ACTIONS
  ══════════════════════════════════════════════════════ --}}
  <div class="quick-actions-section">
    <div class="section-header" style="margin-bottom:16px;">
      <h3 style="font-size:16px;font-weight:700;color:#0F172A;display:flex;align-items:center;gap:8px;">
        ⚡ Aksi Cepat
      </h3>
    </div>
    <div class="quick-actions">
      {{-- Buat Laporan --}}
      <a href="{{ route('laporan.create') }}" class="action-card ac-blue" id="btn-buat-laporan">
        <div class="action-icon">➕</div>
        <div class="action-label">Buat Laporan</div>
        <div class="action-sub">Laporkan kerusakan fasilitas kampus</div>
      </a>

      {{-- Pantau Laporan --}}
      <a href="{{ route('laporan.pantau') }}" class="action-card ac-purple" id="btn-pantau-laporan">
        <div class="action-icon">🔍</div>
        <div class="action-label">Pantau Laporan</div>
        <div class="action-sub">Monitor detail status laporan Anda</div>
      </a>

      {{-- Riwayat Laporan --}}
      <a href="{{ route('laporan.status') }}" class="action-card ac-green" id="btn-riwayat-laporan">
        <div class="action-icon">📂</div>
        <div class="action-label">Riwayat Laporan</div>
        <div class="action-sub">Lihat semua laporan yang pernah dibuat</div>
      </a>

      {{-- Profil Saya --}}
      <a href="{{ route('mahasiswa.biodata', $mhs->id_mahasiswa ?? 0) }}" class="action-card ac-gray" id="btn-profil-saya">
        <div class="action-icon">👤</div>
        <div class="action-label">Profil Saya</div>
        <div class="action-sub">Kelola data diri & foto profil</div>
      </a>
    </div>
  </div>

</div>{{-- /mhs-dashboard --}}

@push('scripts')
<script>
  // Animate stat numbers counting up
  document.addEventListener('DOMContentLoaded', function () {
    const nums = document.querySelectorAll('.stat-number');
    nums.forEach(el => {
      const target = parseInt(el.textContent, 10);
      if (isNaN(target) || target === 0) return;
      let current = 0;
      const step = Math.max(1, Math.floor(target / 30));
      const timer = setInterval(() => {
        current = Math.min(current + step, target);
        el.textContent = current;
        if (current >= target) clearInterval(timer);
      }, 30);
    });
  });
</script>
@endpush

@endsection
