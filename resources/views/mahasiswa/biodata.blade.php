<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Biodata — {{ $mhs->Nama_mahasiswa }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5 mb-5">
  <div class="card shadow">

    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h3 class="mb-0">Edit Data Mahasiswa</h3>
      <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-light btn-sm">← Kembali</a>
    </div>

    <div class="card-body">

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('mahasiswa.biodata.update', $mhs->id_mahasiswa) }}"
            method="POST"
            enctype="multipart/form-data">
        @csrf

        <!-- Nama -->
        <div class="mb-3">
          <label class="form-label">Nama Mahasiswa</label>
          <input type="text"
                 class="form-control"
                 name="Nama_mahasiswa"
                 required
                 value="{{ old('Nama_mahasiswa', $mhs->Nama_mahasiswa) }}">
        </div>

        <!-- NIM -->
        <div class="mb-3">
          <label class="form-label">NIM</label>
          <input type="number"
                 class="form-control"
                 name="Nim"
                 required
                 value="{{ old('Nim', $mhs->Nim) }}">
        </div>

        <!-- Gender -->
        <div class="mb-3">
          <label class="form-label d-block">Jenis Kelamin</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="jenis_Kelamin" value="L"
              {{ old('jenis_Kelamin', $mhs->jenis_kelamin) == 'L' ? 'checked' : '' }}>
            <label class="form-check-label">Laki-laki</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="jenis_Kelamin" value="P"
              {{ old('jenis_Kelamin', $mhs->jenis_kelamin) == 'P' ? 'checked' : '' }}>
            <label class="form-check-label">Perempuan</label>
          </div>
        </div>

        <!-- Tanggal Lahir -->
        <div class="mb-3">
          <label class="form-label">Tanggal Lahir</label>
          <input type="date"
                 class="form-control"
                 name="tanggal_lahir"
                 value="{{ old('tanggal_lahir', $mhs->tanggal_lahir) }}">
        </div>

        <!-- Agama -->
        <div class="mb-3">
          <label class="form-label">Agama</label>
          <select class="form-select" name="agama">
            @foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha'] as $ag)
              <option value="{{ $ag }}"
                {{ old('agama', $mhs->agama) == $ag ? 'selected' : '' }}>
                {{ $ag }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Prodi -->
        <div class="mb-3">
          <label class="form-label">Program Studi</label>
          <select class="form-select" name="prodi">
            @foreach (['Ilmu Komputer', 'Sistem Informasi', 'Matematika', 'Sains Data'] as $p)
              <option value="{{ $p }}"
                {{ old('prodi', $mhs->prodi) == $p ? 'selected' : '' }}>
                {{ $p }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- UKM -->
        <div class="mb-3">
          <label class="form-label d-block">UKM</label>
          @foreach (['Seni', 'olahraga', 'Robotika'] as $u)
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="ukm[]" value="{{ $u }}"
                {{ in_array($u, $ukm_array) ? 'checked' : '' }}>
              <label class="form-check-label">{{ ucfirst($u) }}</label>
            </div>
          @endforeach
        </div>

        <!-- Kontak -->
        <div class="mb-3">
          <label class="form-label">Kontak</label>
          <input type="number"
                 class="form-control"
                 name="Kontak"
                 value="{{ old('Kontak', $mhs->Kontak) }}">
        </div>

        <!-- Foto Profil -->
        <div class="mb-3">
          <label class="form-label">Foto Profil</label>
          <input type="file" class="form-control" name="foto_profil" accept="image/*">
          @if ($mhs->foto_profil)
            <small class="text-muted">
              File saat ini:
              <img src="{{ asset('storage/' . $mhs->foto_profil) }}"
                   style="width:50px;height:50px;border-radius:50%;object-fit:cover;margin-left:8px;"
                   alt="Foto profil">
            </small>
          @endif
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <button type="reset" class="btn btn-secondary">Reset</button>

      </form>

    </div>
  </div>
</div>

</body>
</html>
