<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Mahasiswa — Sistem Pelaporan Fasilitas</title>
  <link rel="stylesheet" href="{{ asset('css/hiasan.css') }}">
</head>
<body>

<div class="container">

  <div class="card">

    <div class="card-header">
      <h2>Data Mahasiswa</h2>
      <a href="{{ route('admin.dashboard') }}" class="btn-tambah">← Kembali</a>
    </div>

    @if (session('success'))
      <div style="background:#d4edda;color:#155724;padding:12px 20px;font-size:14px;">
        {{ session('success') }}
      </div>
    @endif

    <div class="table-wrapper">
      <table class="table">
        <thead>
          <tr>
            <th>Nama</th>
            <th>NIM</th>
            <th>Gender</th>
            <th>Prodi</th>
            <th>Jumlah Laporan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($mahasiswas as $mhs)
            <tr>
              <td>{{ $mhs->Nama_mahasiswa }}</td>
              <td>{{ $mhs->Nim }}</td>
              <td>{{ $mhs->jenis_kelamin == 'L' ? 'Laki-laki' : ($mhs->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td>
              <td>{{ $mhs->prodi ?? '-' }}</td>
              <td>0</td>
              <td>
                <a href="{{ route('mahasiswa.biodata', $mhs->id_mahasiswa) }}"
                   class="btn btn-edit">Edit</a>

                <form action="{{ route('admin.mahasiswa.hapus', $mhs->id_mahasiswa) }}"
                      method="POST"
                      style="display:inline"
                      onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-hapus">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6">Tidak ada data mahasiswa</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>

</div>

</body>
</html>
