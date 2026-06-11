@extends('layouts.dashboard')

@section('title', 'Dashboard Admin — Sistem Pelaporan Fasilitas')

@section('sidebar-menu')
  <a href="{{ route('admin.dashboard') }}"><button>Dashboard</button></a>
  <a href="#"><button>Verifikasi Laporan</button></a>
  <a href="#"><button>Semua Laporan</button></a>
  <a href="#"><button>Teknisi Tersedia</button></a>
  <a href="{{ route('admin.mahasiswa') }}"><button>Daftar Mahasiswa</button></a>
  <a href="{{ route('admin.riwayat') }}"><button>Riwayat Laporan</button></a>
@endsection

@section('profile-name') {{ $admin->nama_admin ?? $user->username }} @endsection
@section('profile-role') Administrator @endsection
@section('profile-buttons')
  <button onclick="toggleProfile()">Edit Profile</button>
@endsection

@section('content')

  <h2 class="welcome">Halo, Admin {{ $admin->nama_admin ?? $user->username }}</h2>

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
        <span style="font-size:13px;color:#777;">Laporan Masuk</span>
      </div>
      <div class="card" style="display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;">
        <span style="font-size:28px;font-weight:700;color:#e07b00;">0</span>
        <span style="font-size:13px;color:#777;">Menunggu Verifikasi</span>
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
    <a href="{{ route('admin.mahasiswa') }}"><button>Daftar Mahasiswa</button></a>
    <button>Verifikasi Laporan</button>
    <a href="{{ route('admin.riwayat') }}"><button>Riwayat Laporan</button></a>
  </div>

@endsection
