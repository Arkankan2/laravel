@extends('layouts.dashboard')

@section('title', 'Riwayat Laporan — Sistem Pelaporan Fasilitas')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
/* ================================================================
   RIWAYAT LAPORAN — laporan/status.blade.php
   Daftar lengkap semua laporan milik mahasiswa yang login
   Terhubung database, modern UI, layout dashboard
   ================================================================ */

:root {
    --blue:       #2563EB;
    --blue-light: #EFF6FF;
    --blue-dark:  #1D4ED8;
    --navy:       #1E3A5F;
    --green:      #16A34A;
    --green-light:#DCFCE7;
    --yellow:     #D97706;
    --yellow-light:#FEF3C7;
    --red:        #DC2626;
    --red-light:  #FEE2E2;
    --purple:     #7C3AED;
    --purple-light:#EDE9FE;
    --gray-50:    #F8FAFC;
    --gray-100:   #F1F5F9;
    --gray-200:   #E2E8F0;
    --gray-300:   #CBD5E1;
    --gray-500:   #64748B;
    --gray-700:   #334155;
    --gray-900:   #0F172A;
    --white:      #FFFFFF;
    --shadow:     0 1px 4px rgba(0,0,0,.06);
    --shadow-md:  0 4px 16px rgba(0,0,0,.08);
    --radius:     12px;
}

/* ── WRAPPER ──────────────────────────────────────────── */
.riwayat-wrapper {
    padding: 28px 32px 48px;
    max-width: 1100px;
    margin: 0 auto;
    animation: fadeIn .35s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── PAGE HEADER ──────────────────────────────────────── */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}

.page-header-text h2 {
    font-size: 22px;
    font-weight: 800;
    color: var(--gray-900);
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.page-header-text p {
    font-size: 13.5px;
    color: var(--gray-500);
}

.btn-new-laporan {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--blue);
    color: var(--white);
    padding: 11px 22px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 14px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all .25s;
    font-family: 'Inter', sans-serif;
    box-shadow: 0 4px 14px rgba(37,99,235,.3);
    white-space: nowrap;
}
.btn-new-laporan:hover {
    background: var(--blue-dark);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(37,99,235,.4);
}

/* ── ALERT ──────────────────────────────────────────────── */
.alert-success {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--green-light);
    color: #14532D;
    border: 1px solid #86EFAC;
    border-radius: 10px;
    padding: 14px 18px;
    font-size: 13.5px;
    font-weight: 500;
    margin-bottom: 22px;
}

/* ── STATS ROW ──────────────────────────────────────────── */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 24px;
}

.stat-mini {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius);
    padding: 16px 18px;
    box-shadow: var(--shadow);
    display: flex;
    align-items: center;
    gap: 12px;
    transition: transform .2s, box-shadow .2s;
}
.stat-mini:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }

.stat-mini-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.stat-mini-info .num {
    font-size: 24px;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 3px;
}
.stat-mini-info .lbl {
    font-size: 12px;
    color: var(--gray-500);
    font-weight: 500;
}

.sm-total   { border-top: 3px solid var(--blue); }
.sm-total   .stat-mini-icon { background: var(--blue-light); }
.sm-total   .num { color: var(--blue); }

.sm-proses  { border-top: 3px solid var(--purple); }
.sm-proses  .stat-mini-icon { background: var(--purple-light); }
.sm-proses  .num { color: var(--purple); }

.sm-selesai { border-top: 3px solid var(--green); }
.sm-selesai .stat-mini-icon { background: var(--green-light); }
.sm-selesai .num { color: var(--green); }

.sm-parah   { border-top: 3px solid var(--red); }
.sm-parah   .stat-mini-icon { background: var(--red-light); }
.sm-parah   .num { color: var(--red); }

/* ── FILTER & SEARCH BAR ───────────────────────────────── */
.filter-bar {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius);
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
    flex-wrap: wrap;
    box-shadow: var(--shadow);
}

.filter-bar .search-wrap {
    position: relative;
    flex: 1;
    min-width: 180px;
}
.filter-bar .search-wrap input {
    width: 100%;
    padding: 9px 12px 9px 36px;
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
    background: var(--gray-50);
    color: var(--gray-700);
    outline: none;
    transition: border-color .2s;
}
.filter-bar .search-wrap input:focus {
    border-color: var(--blue);
    background: var(--white);
}
.filter-bar .search-wrap .si {
    position: absolute;
    left: 10px; top: 50%;
    transform: translateY(-50%);
    font-size: 14px;
}

.filter-tabs {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}
.ftab {
    padding: 7px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    border: 1.5px solid var(--gray-200);
    background: var(--white);
    color: var(--gray-500);
    cursor: pointer;
    font-family: 'Inter', sans-serif;
    transition: all .2s;
    white-space: nowrap;
}
.ftab:hover { border-color: var(--blue); color: var(--blue); }
.ftab.on    { background: var(--blue); border-color: var(--blue); color: var(--white); }

/* ── TABLE ──────────────────────────────────────────────── */
.table-card {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
}

.riwayat-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.riwayat-table th {
    background: var(--gray-50);
    padding: 12px 14px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: var(--gray-500);
    border-bottom: 2px solid var(--gray-200);
    white-space: nowrap;
}

.riwayat-table td {
    padding: 14px 14px;
    border-bottom: 1px solid var(--gray-100);
    vertical-align: middle;
}

.riwayat-table tr:last-child td { border-bottom: none; }
.riwayat-table tr:hover td { background: var(--gray-50); transition: background .12s; }

/* Ticket number */
.ticket-no {
    font-family: 'Courier New', monospace;
    font-size: 11px;
    font-weight: 800;
    color: var(--blue);
    background: var(--blue-light);
    padding: 3px 8px;
    border-radius: 6px;
    white-space: nowrap;
    letter-spacing: .3px;
}

/* Kategori & lokasi */
.cell-kategori { font-weight: 700; color: var(--gray-900); margin-bottom: 3px; }
.cell-lokasi   { font-size: 11.5px; color: var(--gray-500); }

/* Status badges */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 11px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}
.badge::before {
    content: '';
    width: 6px; height: 6px;
    border-radius: 50%;
    display: inline-block;
    flex-shrink: 0;
}
.b-proses   { background: var(--purple-light); color: var(--purple); }
.b-proses::before  { background: var(--purple); animation: blink 1.5s infinite; }
.b-selesai  { background: var(--green-light);  color: var(--green); }
.b-selesai::before { background: var(--green); }
.b-menunggu { background: var(--yellow-light); color: var(--yellow); }
.b-menunggu::before{ background: var(--yellow); }
.b-default  { background: var(--gray-100);     color: var(--gray-500); }
.b-default::before { background: var(--gray-300); }

@keyframes blink { 0%,100%{opacity:1;} 50%{opacity:.3;} }

/* Severity badges */
.sev {
    font-size: 10px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: .3px;
}
.sev-r { background: var(--green-light);  color: var(--green); }
.sev-s { background: var(--yellow-light); color: var(--yellow); }
.sev-p { background: var(--red-light);    color: var(--red); }

/* Action button */
.btn-detail {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: var(--blue-light);
    color: var(--blue);
    border: 1px solid #BFDBFE;
    border-radius: 7px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 700;
    font-family: 'Inter', sans-serif;
    text-decoration: none;
    cursor: pointer;
    transition: all .2s;
    white-space: nowrap;
}
.btn-detail:hover {
    background: var(--blue);
    color: var(--white);
    border-color: var(--blue);
}

/* ── EMPTY STATE ─────────────────────────────────────────── */
.empty-state {
    text-align: center;
    padding: 64px 24px 48px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
}
.empty-icon-circle {
    width: 96px; height: 96px;
    background: var(--blue-light);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 44px;
    margin-bottom: 8px;
}
.empty-state h3 { font-size: 18px; font-weight: 700; color: var(--gray-700); }
.empty-state p  { font-size: 13.5px; color: var(--gray-500); max-width: 320px; line-height: 1.6; }
.btn-create-empty {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--blue); color: var(--white);
    padding: 12px 26px; border-radius: 10px;
    font-weight: 700; font-size: 14px;
    text-decoration: none; margin-top: 8px;
    box-shadow: 0 4px 14px rgba(37,99,235,.3);
    transition: all .25s;
}
.btn-create-empty:hover {
    background: var(--blue-dark);
    transform: translateY(-2px);
}

/* ── RESPONSIVE ──────────────────────────────────────────── */
@media (max-width: 900px) {
    .riwayat-wrapper { padding: 16px 16px 40px; }
    .stats-row { grid-template-columns: repeat(2, 1fr); }
    .riwayat-table th:nth-child(5),
    .riwayat-table td:nth-child(5) { display: none; }
}
@media (max-width: 600px) {
    .stats-row { grid-template-columns: 1fr 1fr; }
    .riwayat-table th:nth-child(4),
    .riwayat-table td:nth-child(4) { display: none; }
}
</style>
@endpush

{{-- ── SIDEBAR ──────────────────────────────────────────── --}}
@section('sidebar-menu')
  <a href="{{ route('mahasiswa.dashboard') }}">
    <button>🏠 Dashboard</button>
  </a>
  <a href="{{ route('laporan.create') }}">
    <button>📋 Buat Laporan</button>
  </a>
  <a href="{{ route('laporan.pantau') }}">
    <button>🔍 Pantau Laporan</button>
  </a>
  <a href="{{ route('laporan.status') }}">
    <button class="active-menu">📂 Riwayat Laporan</button>
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

{{-- ── MAIN CONTENT ─────────────────────────────────────── --}}
@section('content')

@php
  $nama    = $mhs->Nama_mahasiswa ?? $user->username;
  $total   = $laporan->count();
  $proses  = $laporan->filter(fn($l) => str_contains($l->Status_terkini ?? '', 'Diperbaiki'))->count();
  $selesai = $laporan->filter(fn($l) => str_contains($l->Status_terkini ?? '', 'Selesai'))->count();
  $parah   = $laporan->filter(fn($l) => ($l->Tingkat_Kerusakan ?? '') === 'Parah')->count();

  $statusBadge = function(string $s): string {
    if (str_contains($s, 'Selesai'))    return 'badge b-selesai';
    if (str_contains($s, 'Diperbaiki')) return 'badge b-proses';
    if (str_contains($s, 'Menunggu'))   return 'badge b-menunggu';
    return 'badge b-default';
  };

  $statusLabel = function(string $s): string {
    if (str_contains($s, 'Selesai'))    return 'Selesai';
    if (str_contains($s, 'Diperbaiki')) return 'Dalam Pengerjaan';
    return 'Menunggu';
  };

  $sevClass = function(string $t): string {
    return match($t) { 'Parah' => 'sev sev-p', 'Sedang' => 'sev sev-s', 'Rendah' => 'sev sev-r', default => 'sev sev-r' };
  };
@endphp

<div class="riwayat-wrapper">

    {{-- ── SUCCESS ALERT ──────────────────────────────── --}}
    @if(session('success'))
    <div class="alert-success">
        ✅ <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- ── PAGE HEADER ─────────────────────────────────── --}}
    <div class="page-header">
        <div class="page-header-text">
            <h2>📂 Riwayat Laporan</h2>
            <p>Semua laporan kerusakan yang pernah Anda buat, {{ $nama }}</p>
        </div>
        <a href="{{ route('laporan.create') }}" class="btn-new-laporan">
            ➕ Buat Laporan Baru
        </a>
    </div>

    {{-- ── STATS ROW ────────────────────────────────────── --}}
    <div class="stats-row">
        <div class="stat-mini sm-total">
            <div class="stat-mini-icon">📋</div>
            <div class="stat-mini-info">
                <div class="num">{{ $total }}</div>
                <div class="lbl">Total Laporan</div>
            </div>
        </div>
        <div class="stat-mini sm-proses">
            <div class="stat-mini-icon">🔧</div>
            <div class="stat-mini-info">
                <div class="num">{{ $proses }}</div>
                <div class="lbl">Dalam Pengerjaan</div>
            </div>
        </div>
        <div class="stat-mini sm-selesai">
            <div class="stat-mini-icon">✅</div>
            <div class="stat-mini-info">
                <div class="num">{{ $selesai }}</div>
                <div class="lbl">Selesai</div>
            </div>
        </div>
        <div class="stat-mini sm-parah">
            <div class="stat-mini-icon">🚨</div>
            <div class="stat-mini-info">
                <div class="num">{{ $parah }}</div>
                <div class="lbl">Tingkat Parah</div>
            </div>
        </div>
    </div>

    @if($laporan->isEmpty())
    {{-- ── EMPTY STATE ─────────────────────────────────── --}}
    <div class="table-card">
        <div class="empty-state">
            <div class="empty-icon-circle">📭</div>
            <h3>Belum Ada Laporan</h3>
            <p>Anda belum pernah membuat laporan kerusakan fasilitas kampus. Mulai buat laporan pertama Anda sekarang.</p>
            <a href="{{ route('laporan.create') }}" class="btn-create-empty">
                ➕ Buat Laporan Pertama
            </a>
        </div>
    </div>
    @else

    {{-- ── FILTER & SEARCH ─────────────────────────────── --}}
    <div class="filter-bar">
        <div class="search-wrap">
            <span class="si">🔍</span>
            <input type="text" id="search-input"
                   placeholder="Cari nomor tiket, kategori, atau lokasi…"
                   oninput="applyFilter()">
        </div>
        <div class="filter-tabs">
            <button class="ftab on"  onclick="setFilter(this,'all')">Semua</button>
            <button class="ftab"     onclick="setFilter(this,'proses')">Diproses</button>
            <button class="ftab"     onclick="setFilter(this,'selesai')">Selesai</button>
            <button class="ftab"     onclick="setFilter(this,'parah')">Parah</button>
            <button class="ftab"     onclick="setFilter(this,'sedang')">Sedang</button>
            <button class="ftab"     onclick="setFilter(this,'rendah')">Rendah</button>
        </div>
    </div>

    {{-- ── TABLE ───────────────────────────────────────── --}}
    <div class="table-card">
        <table class="riwayat-table" id="riwayat-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nomor Tiket</th>
                    <th>Kerusakan & Lokasi</th>
                    <th>Tingkat</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="riwayat-body">
            @foreach($laporan as $idx => $lap)
            @php
                $ticket   = 'RPT-' . str_pad($lap->id_laporan, 5, '0', STR_PAD_LEFT);
                $kategori = ucfirst($lap->kategori->nama_kategori ?? 'Fasilitas');
                $lokasi   = $lap->lokasi
                    ? $lap->lokasi->nama_ruangan . ', ' . $lap->lokasi->nama_gedung
                    : 'Lokasi tidak diketahui';
                $status   = $lap->Status_terkini ?? 'Menunggu';
                $tingkat  = $lap->Tingkat_Kerusakan ?? '-';
            @endphp
            <tr class="riwayat-row"
                data-search="{{ strtolower($ticket . ' ' . $kategori . ' ' . $lokasi . ' ' . $tingkat) }}"
                data-status="{{ strtolower($status) }}"
                data-tingkat="{{ strtolower($tingkat) }}">
                <td style="color:var(--gray-500);font-size:12px;font-weight:600;">{{ $idx + 1 }}</td>
                <td>
                    <span class="ticket-no">{{ $ticket }}</span>
                </td>
                <td>
                    <div class="cell-kategori">{{ $kategori }}</div>
                    <div class="cell-lokasi">📍 {{ $lokasi }}</div>
                </td>
                <td>
                    <span class="{{ $sevClass($tingkat) }}">{{ $tingkat }}</span>
                </td>
                <td>
                    <span class="{{ $statusBadge($status) }}">{{ $statusLabel($status) }}</span>
                </td>
                <td style="font-size:12px;color:var(--gray-500);white-space:nowrap;">
                    {{ $lap->created_at->format('d M Y') }}<br>
                    <span style="font-size:11px;color:var(--gray-300);">
                        {{ $lap->created_at->format('H:i') }} WITA
                    </span>
                </td>
                <td>
                    <a href="{{ route('laporan.pantau') }}" class="btn-detail">
                        🔍 Detail
                    </a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>

        {{-- Tidak ada hasil filter --}}
        <div id="no-result" style="display:none;padding:36px;text-align:center;color:var(--gray-500);">
            <div style="font-size:32px;margin-bottom:10px;">🔍</div>
            <div style="font-weight:700;margin-bottom:4px;">Tidak ada laporan ditemukan</div>
            <div style="font-size:13px;">Coba ubah kata kunci atau filter pencarian.</div>
        </div>
    </div>

    @endif

</div>{{-- /riwayat-wrapper --}}

@push('scripts')
<script>
let currentFilter = 'all';

function setFilter(btn, filter) {
    document.querySelectorAll('.ftab').forEach(b => b.classList.remove('on'));
    btn.classList.add('on');
    currentFilter = filter;
    applyFilter();
}

function applyFilter() {
    const q      = document.getElementById('search-input').value.toLowerCase();
    const rows   = document.querySelectorAll('.riwayat-row');
    let visible  = 0;

    rows.forEach(row => {
        const search  = row.dataset.search || '';
        const status  = row.dataset.status || '';
        const tingkat = row.dataset.tingkat || '';

        let showByFilter = true;
        if (currentFilter === 'proses')   showByFilter = status.includes('diperbaiki') || status.includes('menunggu');
        if (currentFilter === 'selesai')  showByFilter = status.includes('selesai');
        if (currentFilter === 'parah')    showByFilter = tingkat === 'parah';
        if (currentFilter === 'sedang')   showByFilter = tingkat === 'sedang';
        if (currentFilter === 'rendah')   showByFilter = tingkat === 'rendah';

        const showBySearch = q === '' || search.includes(q);
        const show = showByFilter && showBySearch;

        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    const noResult = document.getElementById('no-result');
    if (noResult) noResult.style.display = visible === 0 ? 'block' : 'none';
}
</script>
@endpush

@endsection
