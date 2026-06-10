@extends('layouts.dashboard')

@section('title', 'Dashboard Mahasiswa — Sistem Pelaporan Fasilitas')

@section('sidebar-menu')
  <a href="{{ route('mahasiswa.dashboard') }}"><button>Dashboard</button></a>
  <button>Buat Laporan</button>
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

  <h2 class="welcome">Selamat Datang, {{ $mhs->Nama_mahasiswa ?? $user->username }}</h2>

  @if (session('success'))
    <div style="margin: 10px 40px; background:#d4edda; color:#155724; padding:10px 15px; border-radius:8px; font-size:13px;">
      {{ session('success') }}
    </div>
  @endif

  <div class="content">

    <!-- CARDS -->
    <div class="cards">
      <div class="card" style="display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;">
        <span style="font-size:28px;font-weight:700;color:#0b4a6f;">0</span>
        <span style="font-size:13px;color:#777;">Laporan Dibuat</span>
      </div>
      <div class="card" style="display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;">
        <span style="font-size:28px;font-weight:700;color:#e07b00;">0</span>
        <span style="font-size:13px;color:#777;">Dalam Proses</span>
      </div>
      <div class="card" style="display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;">
        <span style="font-size:28px;font-weight:700;color:#198754;">0</span>
        <span style="font-size:13px;color:#777;">Selesai</span>
      </div>
    </div>

  </div>

  <!-- LINE -->
  <div class="line"></div>

  <!-- BUTTONS -->
  <div class="buttons">
    <a href="#"><button>Buat Laporan</button></a>
    <a href="{{ route('laporan.status') }}"><button>Pantau Laporan</button></a>
    <a href="#"><button>Riwayat Laporan</button></a>
  </div>

@endsection
