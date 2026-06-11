@extends('layouts.dashboard')

@section('title', 'Buat Laporan — Sistem Pelaporan Fasilitas')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/buat-laporan.css') }}">
@endpush

@section('sidebar-menu')
  <a href="{{ route('mahasiswa.dashboard') }}"><button>Dashboard</button></a>
  <a href="{{ route('laporan.create') }}"><button style="background:#0b4a6f;color:white;">Buat Laporan</button></a>
  <a href="{{ route('laporan.status') }}"><button>Status Laporan</button></a>
  <button>Riwayat Tersedia</button>
  <button>Notifikasi</button>
  <a href="{{ route('mahasiswa.ganti.password') }}"><button style="background:#fff3cd;border-color:#ffc107;">🔑 Ganti Password</button></a>
@endsection

@section('profile-name') {{ $mhs->Nama_mahasiswa ?? $user->username }} @endsection
@section('profile-role') {{ $mhs->Nim ?? '' }} @endsection
@section('profile-buttons')
  <a href="{{ route('mahasiswa.biodata', $mhs->id_mahasiswa ?? 0) }}"><button>Edit Profile</button></a>
  <a href="{{ route('mahasiswa.ganti.password') }}"><button>🔑 Ganti Password</button></a>
@endsection

@section('content')

<div class="laporan-wrapper">

  <div class="laporan-title">
    <h2>📋 BUAT LAPORAN KERUSAKAN</h2>
  </div>

  {{-- Flash messages --}}
  @if (session('success'))
    <div class="alert-success">
      ✅ {{ session('success') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="alert-error">
      <strong>⚠️ Terdapat kesalahan pada form:</strong>
      <ul>
        @foreach ($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="laporan-card">

    <div class="info-badge">
      ℹ️ Laporan Anda akan segera diproses oleh tim teknis kampus.
    </div>

    <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" id="form-laporan">
      @csrf

      {{-- BARIS 1: Kategori & Lokasi --}}
      <div class="form-row">

        <div class="form-group">
          <label for="id_kategori">Kategori Fasilitas <span class="required">*</span></label>
          <select name="id_kategori" id="id_kategori" class="form-control" required>
            <option value="" disabled selected>-- Pilih Kategori --</option>
            @foreach ($kategori as $kat)
              <option value="{{ $kat->id_kategori }}" {{ old('id_kategori') == $kat->id_kategori ? 'selected' : '' }}>
                {{ ucfirst($kat->nama_kategori) }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="id_lokasi">Lokasi / Ruangan <span class="required">*</span></label>
          <select name="id_lokasi" id="id_lokasi" class="form-control" required>
            <option value="" disabled selected>-- Pilih Lokasi --</option>
            @foreach ($lokasi as $lok)
              <option value="{{ $lok->id_lokasi }}" {{ old('id_lokasi') == $lok->id_lokasi ? 'selected' : '' }}>
                {{ $lok->nama_gedung }} — {{ $lok->nama_ruangan }}
              </option>
            @endforeach
          </select>
        </div>

      </div>

      {{-- DESKRIPSI --}}
      <div class="form-group">
        <label for="deskripsi">Deskripsi Kerusakan <span class="required">*</span></label>
        <textarea
          name="deskripsi"
          id="deskripsi"
          class="form-control"
          placeholder="Jelaskan kerusakan secara detail, misalnya: Kursi pada baris ketiga kaki depan kiri patah sehingga tidak bisa digunakan..."
          required
        >{{ old('deskripsi') }}</textarea>
        <small style="color:#9ca3af;font-size:12px;margin-top:4px;display:block;">
          Minimal 10 karakter. Semakin detail, semakin cepat ditangani.
        </small>
      </div>

      {{-- TINGKAT KERUSAKAN --}}
      <div class="form-group">
        <label>Tingkat Kerusakan <span class="required">*</span></label>
        <div class="kerusakan-group">

          <div class="kerusakan-pill rendah">
            <input type="radio" name="Tingkat_Kerusakan" id="tk-rendah" value="Rendah"
              {{ old('Tingkat_Kerusakan') == 'Rendah' ? 'checked' : '' }}>
            <label for="tk-rendah">Rendah</label>
          </div>

          <div class="kerusakan-pill sedang">
            <input type="radio" name="Tingkat_Kerusakan" id="tk-sedang" value="Sedang"
              {{ old('Tingkat_Kerusakan') == 'Sedang' ? 'checked' : '' }}>
            <label for="tk-sedang">Sedang</label>
          </div>

          <div class="kerusakan-pill parah">
            <input type="radio" name="Tingkat_Kerusakan" id="tk-parah" value="Parah"
              {{ old('Tingkat_Kerusakan') == 'Parah' ? 'checked' : '' }}>
            <label for="tk-parah">Parah</label>
          </div>

        </div>
      </div>

      {{-- UPLOAD FOTO --}}
      <div class="form-group">
        <label>Foto Kerusakan <span style="color:#9ca3af;font-weight:400;">(Opsional)</span></label>

        <div class="upload-area" id="upload-area">
          <input type="file" name="foto" id="foto-input" accept="image/*">
          <div class="upload-icon">📷</div>
          <p><strong>Klik untuk upload</strong> atau drag & drop foto di sini</p>
          <small>Format: JPG, PNG, WEBP — Maks. 3 MB</small>
        </div>

        <div id="preview-container">
          <img id="preview-img" src="" alt="Preview foto">
          <button type="button" id="remove-photo" title="Hapus foto">✕</button>
        </div>
      </div>

      <div class="form-divider"></div>

      {{-- TOMBOL SUBMIT --}}
      <button type="submit" class="btn-submit" id="btn-submit">
        <span class="btn-icon">📤</span> Kirim Laporan
      </button>

    </form>

    <a href="{{ route('mahasiswa.dashboard') }}" class="cancel-link">
      ← Kembali ke Dashboard
    </a>

  </div>
</div>

@endsection

@push('scripts')
<script>
  // ── Preview & Upload Photo ──
  const fotoInput  = document.getElementById('foto-input');
  const previewCon = document.getElementById('preview-container');
  const previewImg = document.getElementById('preview-img');
  const removeBtn  = document.getElementById('remove-photo');
  const uploadArea = document.getElementById('upload-area');

  fotoInput.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
      previewImg.src = URL.createObjectURL(file);
      previewCon.style.display = 'block';
      uploadArea.style.display = 'none';
    }
  });

  removeBtn.addEventListener('click', function () {
    fotoInput.value = '';
    previewImg.src = '';
    previewCon.style.display = 'none';
    uploadArea.style.display = 'block';
  });

  // Drag & Drop
  uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('drag-over');
  });

  uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('drag-over');
  });

  uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
      fotoInput.files = e.dataTransfer.files;
      previewImg.src = URL.createObjectURL(file);
      previewCon.style.display = 'block';
      uploadArea.style.display = 'none';
    }
  });

  // ── Submit loading state ──
  document.getElementById('form-laporan').addEventListener('submit', function () {
    const btn = document.getElementById('btn-submit');
    btn.disabled = true;
    btn.innerHTML = '<span class="btn-icon">⏳</span> Mengirim...';
  });
</script>
@endpush
