@extends('layouts.dashboard')

@section('title', 'Selesaikan Tugas {{ str_pad($laporan->id_laporan, 5, "0", STR_PAD_LEFT) }} — Teknisi')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/teknisi-dashboard.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
@endpush

@section('sidebar-menu')
  <a href="{{ route('teknisi.dashboard') }}"><button>🏠 Dashboard</button></a>
  <a href="{{ route('teknisi.tugas') }}"><button class="active-menu">🔧 Tugas Saya</button></a>
  <a href="{{ route('teknisi.tugas', ['status' => 'Dalam Pengerjaan']) }}"><button>⚙️ Dalam Pengerjaan</button></a>
  <a href="{{ route('teknisi.tugas', ['status' => 'Selesai']) }}"><button>✅ Riwayat Selesai</button></a>
@endsection

@section('profile-name') {{ $user->nama ?? $user->username }} @endsection
@section('profile-role') Teknisi Fasilitas @endsection
@section('profile-buttons')
  <a href="{{ route('teknisi.dashboard') }}"><button>🏠 Dashboard</button></a>
@endsection

@section('content')

@php
  $pr = $laporan->Tingkat_Kerusakan ?? 'Rendah';
  $prioClass = match($pr) {
    'Rendah' => 'prio-rendah',
    'Sedang' => 'prio-sedang',
    'Parah'  => 'prio-tinggi',
    default  => 'prio-rendah'
  };
  $tiket = 'RPT-' . str_pad($laporan->id_laporan, 5, '0', STR_PAD_LEFT);
@endphp

<div class="tek-wrap">

  {{-- ── ALERTS ──────────────────────────────────────────── --}}
  @if($errors->any())
    <div class="tek-alert alert-danger">
      ❌ <strong>Terdapat kesalahan:</strong>
      <ul style="margin:8px 0 0 16px;">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- ── BREADCRUMB ──────────────────────────────────────── --}}
  <nav class="tek-breadcrumb">
    <a href="{{ route('teknisi.dashboard') }}">🏠 Dashboard</a>
    <span class="sep">›</span>
    <a href="{{ route('teknisi.tugas') }}">🔧 Tugas Saya</a>
    <span class="sep">›</span>
    <a href="{{ route('teknisi.detail', $laporan->id_laporan) }}">{{ $tiket }}</a>
    <span class="sep">›</span>
    <span class="current">📸 Selesaikan Tugas</span>
  </nav>

  {{-- ══════════════════════════════════════════════════════
       PROGRESS TIMELINE
  ══════════════════════════════════════════════════════ --}}
  <div class="tek-panel" style="margin-bottom:24px;">
    <div class="tek-panel-header">
      <h3>📊 Progress Penyelesaian Tugas</h3>
      <span class="priority-badge {{ $prioClass }}" style="font-size:11px;">{{ $pr }}</span>
    </div>
    <div class="tek-panel-body">
      <div class="tek-timeline">
        <div class="tek-timeline-step tl-done">
          <div class="tl-circle">📩</div>
          <div><div class="tl-label">Laporan Masuk</div><div class="tl-sub">✓ Diterima</div></div>
        </div>
        <div class="tek-timeline-step tl-done">
          <div class="tl-circle">📋</div>
          <div><div class="tl-label">Disposisi Admin</div><div class="tl-sub">✓ Ditugaskan</div></div>
        </div>
        <div class="tek-timeline-step tl-done">
          <div class="tl-circle">🔧</div>
          <div><div class="tl-label">Dalam Pengerjaan</div><div class="tl-sub">✓ Selesai dikerjakan</div></div>
        </div>
        <div class="tek-timeline-step tl-active">
          <div class="tl-circle">📸</div>
          <div><div class="tl-label">Upload Bukti</div><div class="tl-sub">⚙️ Langkah ini</div></div>
        </div>
        <div class="tek-timeline-step tl-pending">
          <div class="tl-circle">✅</div>
          <div><div class="tl-label">Selesai</div><div class="tl-sub">Setelah submit</div></div>
        </div>
      </div>
    </div>
  </div>

  {{-- ══════════════════════════════════════════════════════
       FORM + RINGKASAN LAPORAN
  ══════════════════════════════════════════════════════ --}}
  <div class="tek-detail-grid">

    {{-- KIRI: FORM UPLOAD --}}
    <div>
      <div class="tek-panel">
        <div class="tek-panel-header">
          <h3>📸 Upload Bukti Perbaikan & Catatan</h3>
        </div>
        <div class="tek-panel-body">

          <form action="{{ route('teknisi.selesai.post', $laporan->id_laporan) }}"
                method="POST"
                enctype="multipart/form-data"
                id="form-selesai">
            @csrf
            @method('POST')

            {{-- FOTO HASIL PERBAIKAN --}}
            <div class="tek-form-group">
              <label for="foto_selesai">
                📷 Foto Hasil Perbaikan
                <span class="required">*</span>
              </label>

              <div class="upload-zone" id="uploadZone" onclick="document.getElementById('foto_selesai').click()">
                <input type="file"
                       name="foto_selesai"
                       id="foto_selesai"
                       accept="image/jpg,image/jpeg,image/png,image/webp"
                       onchange="previewImage(event)">
                <div id="uploadPlaceholder">
                  <div class="upload-icon">📷</div>
                  <div class="upload-title">Klik untuk pilih foto</div>
                  <div class="upload-sub">atau seret & lepas foto ke sini</div>
                  <div class="upload-sub" style="margin-top:6px;">Format: JPG, PNG, WebP · Maks 5 MB</div>
                </div>
              </div>

              <div class="upload-preview" id="uploadPreview">
                <img id="previewImg" src="#" alt="Preview foto perbaikan">
                <div style="margin-top:8px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                  <div style="display:flex;align-items:center;gap:6px;">
                    <span style="font-size:18px;">✅</span>
                    <span id="previewFileName" style="font-size:12px;font-weight:600;color:var(--gray-600);"></span>
                  </div>
                  <button type="button"
                          onclick="removePreview()"
                          class="btn-outline btn-sm"
                          style="color:var(--tek-danger);border-color:var(--tek-danger);">
                    🗑 Hapus
                  </button>
                </div>
              </div>

              @error('foto_selesai')
                <div style="color:var(--tek-danger);font-size:12px;margin-top:6px;">⚠ {{ $message }}</div>
              @enderror
            </div>

            {{-- CATATAN PERBAIKAN --}}
            <div class="tek-form-group">
              <label for="catatan">
                📝 Catatan Hasil Perbaikan
                <span class="required">*</span>
              </label>
              <textarea name="catatan"
                        id="catatan"
                        rows="5"
                        placeholder="Jelaskan tindakan perbaikan yang telah dilakukan, material yang digunakan, hasil akhir perbaikan, dan hal-hal yang perlu diperhatikan…"
                        style="min-height:130px;">{{ old('catatan') }}</textarea>
              <div style="display:flex;align-items:center;justify-content:space-between;margin-top:4px;">
                <span id="charCount" style="font-size:11px;color:var(--gray-400);">0 / 1000 karakter</span>
                @error('catatan')
                  <span style="color:var(--tek-danger);font-size:12px;">⚠ {{ $message }}</span>
                @enderror
              </div>
            </div>

            {{-- KONFIRMASI CHECKLIST --}}
            <div style="background:var(--gray-50);border-radius:var(--radius-md);padding:16px;margin-bottom:24px;">
              <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--gray-600);margin-bottom:12px;">
                ✅ Konfirmasi Sebelum Submit
              </div>
              <div style="display:flex;flex-direction:column;gap:8px;">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                  <input type="checkbox" id="chk1" onchange="checkReady()" style="width:16px;height:16px;accent-color:var(--tek-success);">
                  <span style="font-size:13px;color:var(--gray-700);">Perbaikan sudah dilakukan dengan benar</span>
                </label>
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                  <input type="checkbox" id="chk2" onchange="checkReady()" style="width:16px;height:16px;accent-color:var(--tek-success);">
                  <span style="font-size:13px;color:var(--gray-700);">Foto diambil setelah perbaikan selesai</span>
                </label>
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                  <input type="checkbox" id="chk3" onchange="checkReady()" style="width:16px;height:16px;accent-color:var(--tek-success);">
                  <span style="font-size:13px;color:var(--gray-700);">Catatan menjelaskan tindakan yang dilakukan</span>
                </label>
              </div>
            </div>

            {{-- SUBMIT BUTTONS --}}
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
              <button type="submit"
                      class="btn-success"
                      id="btn-submit-selesai"
                      disabled
                      style="flex:1;justify-content:center;padding:14px;font-size:14px;opacity:.5;cursor:not-allowed;">
                🎉 Selesaikan Tugas & Kirim Notifikasi
              </button>
              <a href="{{ route('teknisi.detail', $laporan->id_laporan) }}"
                 class="btn-outline"
                 style="padding:14px 20px;">
                ← Batal
              </a>
            </div>

            <div style="margin-top:12px;padding:12px;background:var(--tek-warning-soft);border-radius:var(--radius-md);font-size:12px;color:#92400E;display:flex;align-items:flex-start;gap:8px;">
              <span style="font-size:16px;">⚠️</span>
              <span>Setelah tugas diselesaikan, sistem akan otomatis mengirim notifikasi kepada <strong>mahasiswa pelapor</strong> dan <strong>admin</strong> bahwa fasilitas telah berhasil diperbaiki. Pastikan semua data sudah benar.</span>
            </div>

          </form>

        </div>
      </div>
    </div>

    {{-- KANAN: Ringkasan Laporan --}}
    <div>

      {{-- Info Laporan --}}
      <div class="tek-panel" style="margin-bottom:16px;">
        <div class="tek-panel-header">
          <h3>📋 Ringkasan Laporan</h3>
        </div>
        <div class="tek-panel-body">

          <div class="info-group">
            <div class="info-label">Tiket</div>
            <div class="info-value"><span class="ticket-badge" style="font-size:13px;padding:5px 10px;">{{ $tiket }}</span></div>
          </div>

          <div class="info-divider"></div>

          <div class="info-group">
            <div class="info-label">Jenis Fasilitas</div>
            <div class="info-value">{{ ucfirst($laporan->kategori->nama_kategori ?? '-') }}</div>
          </div>

          <div class="info-group">
            <div class="info-label">📍 Lokasi</div>
            <div class="info-value" style="font-size:13px;">
              @if($laporan->lokasi)
                🏢 {{ $laporan->lokasi->nama_gedung }} · 🚪 {{ $laporan->lokasi->nama_ruangan }}
              @else
                <span style="color:var(--gray-400);">Tidak tersedia</span>
              @endif
            </div>
          </div>

          <div class="info-divider"></div>

          <div class="info-group">
            <div class="info-label">Deskripsi Kerusakan</div>
            <div style="background:var(--gray-50);padding:10px 12px;border-radius:var(--radius-md);font-size:12.5px;color:var(--gray-700);line-height:1.6;">
              {{ Str::limit($laporan->deskripsi ?? '-', 150) }}
            </div>
          </div>

          <div class="info-divider"></div>

          <div class="info-group">
            <div class="info-label">Pelapor</div>
            <div class="info-value">
              {{ $laporan->mahasiswa->Nama_mahasiswa ?? 'Anonim' }}
              @if($laporan->mahasiswa?->Nim)
                <div style="font-size:11px;color:var(--gray-400);font-weight:400;">NIM: {{ $laporan->mahasiswa->Nim }}</div>
              @endif
            </div>
          </div>

          <div class="info-group">
            <div class="info-label">Tanggal Laporan</div>
            <div class="info-value">{{ $laporan->created_at->format('d M Y, H:i') }} WITA</div>
          </div>

        </div>
      </div>

      {{-- Foto Kerusakan Awal --}}
      <div class="tek-panel">
        <div class="tek-panel-header">
          <h3>📸 Foto Kerusakan Awal</h3>
        </div>
        <div class="tek-panel-body">
          @if($laporan->foto)
            <div class="img-preview-box">
              @php
                $fotoUrl = str_starts_with($laporan->foto, 'http')
                    ? $laporan->foto
                    : asset('storage/' . $laporan->foto);
              @endphp
              <img src="{{ $fotoUrl }}" alt="Foto kerusakan awal">
              <div class="img-caption">📸 Sebelum perbaikan</div>
            </div>
          @else
            <div class="img-preview-box">
              <div class="img-no-photo">
                <span style="font-size:32px;">📷</span>
                <span>Tidak ada foto dilampirkan</span>
              </div>
            </div>
          @endif
        </div>
      </div>

    </div>

  </div>

</div>{{-- /tek-wrap --}}

@push('scripts')
<script>
  // ── Image Preview ──────────────────────────────────────────────
  function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;

    const maxMB = 5;
    if (file.size > maxMB * 1024 * 1024) {
      alert('❌ Ukuran file terlalu besar. Maksimal ' + maxMB + ' MB.');
      event.target.value = '';
      return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('previewImg').src = e.target.result;
      document.getElementById('previewFileName').textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
      document.getElementById('uploadPreview').classList.add('show');
      document.getElementById('uploadPlaceholder').style.display = 'none';
      document.getElementById('uploadZone').style.borderColor = 'var(--tek-success)';
    };
    reader.readAsDataURL(file);
    checkReady();
  }

  function removePreview() {
    document.getElementById('foto_selesai').value = '';
    document.getElementById('uploadPreview').classList.remove('show');
    document.getElementById('uploadPlaceholder').style.display = '';
    document.getElementById('uploadZone').style.borderColor = '';
    checkReady();
  }

  // ── Drag & Drop ────────────────────────────────────────────────
  const zone = document.getElementById('uploadZone');
  zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
  zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
  zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.classList.remove('drag-over');
    const input = document.getElementById('foto_selesai');
    input.files = e.dataTransfer.files;
    previewImage({ target: input });
  });

  // ── Char counter ───────────────────────────────────────────────
  const catatan = document.getElementById('catatan');
  const charCount = document.getElementById('charCount');
  catatan?.addEventListener('input', function() {
    const len = this.value.length;
    charCount.textContent = len + ' / 1000 karakter';
    charCount.style.color = len > 900 ? 'var(--tek-danger)' : len > 700 ? 'var(--tek-warning)' : 'var(--gray-400)';
    checkReady();
  });

  // ── Submit button enable/disable ───────────────────────────────
  function checkReady() {
    const hasFoto    = document.getElementById('foto_selesai')?.files?.length > 0;
    const hasCatatan = (document.getElementById('catatan')?.value?.trim().length || 0) >= 10;
    const chk1 = document.getElementById('chk1')?.checked;
    const chk2 = document.getElementById('chk2')?.checked;
    const chk3 = document.getElementById('chk3')?.checked;

    const ready = hasFoto && hasCatatan && chk1 && chk2 && chk3;
    const btn = document.getElementById('btn-submit-selesai');
    if (btn) {
      btn.disabled = !ready;
      btn.style.opacity = ready ? '1' : '.5';
      btn.style.cursor  = ready ? 'pointer' : 'not-allowed';
    }
  }

  // ── Confirm before submit ──────────────────────────────────────
  document.getElementById('form-selesai')?.addEventListener('submit', function(e) {
    if (!confirm('⚠️ Konfirmasi:\n\nAnda akan menyelesaikan tugas dan mengirim notifikasi.\nApakah Anda yakin?')) {
      e.preventDefault();
    }
  });
</script>
@endpush

@endsection
