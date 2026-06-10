@extends('layouts.dashboard')

@section('title', 'Ganti Password — Sistem Pelaporan Fasilitas')

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
  <a href="{{ route('mahasiswa.ganti.password') }}"><button>Ganti Password</button></a>
@endsection

@section('content')

@push('styles')
<style>
  .ganti-pw-wrapper {
    max-width: 480px;
    margin: 30px auto;
    padding: 0 20px;
  }
  .ganti-pw-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
  }
  .ganti-pw-header {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    padding: 24px 28px;
    display: flex;
    align-items: center;
    gap: 14px;
  }
  .ganti-pw-header .icon {
    font-size: 32px;
    width: 52px;
    height: 52px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .ganti-pw-header h2 { margin: 0; font-size: 20px; }
  .ganti-pw-header p  { margin: 4px 0 0; font-size: 13px; opacity: 0.85; }
  .ganti-pw-body { padding: 28px; }
  .form-field { margin-bottom: 20px; }
  .form-field label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #555;
    margin-bottom: 6px;
  }
  .pw-input-wrap { position: relative; }
  .pw-input-wrap input {
    width: 100%;
    height: 48px;
    border: 1.5px solid #ddd;
    border-radius: 10px;
    padding: 0 46px 0 16px;
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
  }
  .pw-input-wrap input:focus { border-color: #3b82f6; }
  .pw-input-wrap input.is-error { border-color: #dc3545; }
  .pw-input-wrap input.is-ok    { border-color: #198754; }
  .toggle-eye {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    font-size: 16px;
    color: #999;
    padding: 0;
    line-height: 1;
  }
  .field-hint {
    font-size: 11px;
    margin-top: 5px;
    min-height: 16px;
  }
  .field-hint.error  { color: #dc3545; }
  .field-hint.ok     { color: #198754; }
  .field-hint.warn   { color: #e07b00; }
  .strength-bar {
    height: 4px;
    border-radius: 4px;
    background: #eee;
    margin-top: 6px;
    overflow: hidden;
  }
  .strength-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s, background 0.3s;
    width: 0;
  }
  .alert-msg {
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 13px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .alert-error   { background:#fef2f2; color:#dc3545; border:1px solid #fecaca; }
  .alert-success { background:#f0fdf4; color:#198754; border:1px solid #bbf7d0; }
  .btn-submit {
    width: 100%;
    height: 50px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: opacity 0.2s, transform 0.1s;
    font-family: 'Poppins', sans-serif;
    letter-spacing: 0.5px;
  }
  .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }
  .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
  .divider { border: none; border-top: 1px solid #f0f0f0; margin: 20px 0; }
  .btn-back {
    display: block;
    text-align: center;
    color: #888;
    font-size: 13px;
    text-decoration: none;
    padding: 8px;
  }
  .btn-back:hover { color: #3b82f6; }
  .req-list {
    font-size: 11px;
    color: #888;
    margin-top: 4px;
    padding-left: 14px;
  }
  .req-list li { margin-bottom: 2px; }
  .req-list li.met { color: #198754; }
</style>
@endpush

  <div class="ganti-pw-wrapper">
    <div class="ganti-pw-card">

      {{-- Header --}}
      <div class="ganti-pw-header">
        <div class="icon">🔑</div>
        <div>
          <h2>Ganti Password</h2>
          <p>Pastikan gunakan password yang kuat dan unik</p>
        </div>
      </div>

      {{-- Body --}}
      <div class="ganti-pw-body">

        {{-- Alert Error --}}
        @if ($errors->any())
          <div class="alert-msg alert-error">
            ⚠️ {{ $errors->first() }}
          </div>
        @endif

        <form action="{{ route('mahasiswa.ganti.password.post') }}" method="POST" id="gantiPwForm">
          @csrf

          {{-- Password Lama --}}
          <div class="form-field">
            <label for="password_lama">Password Saat Ini</label>
            <div class="pw-input-wrap">
              <input type="password"
                     name="password_lama"
                     id="password_lama"
                     placeholder="Masukkan password lama"
                     required
                     class="{{ $errors->has('password_lama') ? 'is-error' : '' }}">
              <button type="button" class="toggle-eye" onclick="togglePass('password_lama', this)">👁</button>
            </div>
            @error('password_lama')
              <div class="field-hint error">{{ $message }}</div>
            @enderror
          </div>

          <hr class="divider">

          {{-- Password Baru --}}
          <div class="form-field">
            <label for="password_baru">Password Baru</label>
            <div class="pw-input-wrap">
              <input type="password"
                     name="password_baru"
                     id="password_baru"
                     placeholder="Masukkan password baru"
                     required
                     oninput="checkStrength(this.value); checkMatch()">
              <button type="button" class="toggle-eye" onclick="togglePass('password_baru', this)">👁</button>
            </div>
            <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
            <div class="field-hint" id="strengthHint"></div>
            <ul class="req-list" id="reqList">
              <li id="req-len">Minimal 6 karakter</li>
              <li id="req-upper">Minimal 1 huruf besar</li>
              <li id="req-num">Minimal 1 angka</li>
            </ul>
            @error('password_baru')
              <div class="field-hint error">{{ $message }}</div>
            @enderror
          </div>

          {{-- Konfirmasi --}}
          <div class="form-field">
            <label for="password_baru_confirmation">Konfirmasi Password Baru</label>
            <div class="pw-input-wrap">
              <input type="password"
                     name="password_baru_confirmation"
                     id="password_baru_confirmation"
                     placeholder="Ulangi password baru"
                     required
                     oninput="checkMatch()">
              <button type="button" class="toggle-eye" onclick="togglePass('password_baru_confirmation', this)">👁</button>
            </div>
            <div class="field-hint" id="matchHint"></div>
          </div>

          <button type="submit" class="btn-submit" id="submitBtn" disabled>SIMPAN PASSWORD BARU</button>

        </form>

        <hr class="divider">
        <a href="{{ route('mahasiswa.dashboard') }}" class="btn-back">← Kembali ke Dashboard</a>

      </div>
    </div>
  </div>

@endsection

@push('scripts')
<script>
  function togglePass(id, btn) {
    const input = document.getElementById(id);
    input.type  = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? '👁' : '🙈';
  }

  function checkStrength(val) {
    const fill  = document.getElementById('strengthFill');
    const hint  = document.getElementById('strengthHint');
    const rLen  = document.getElementById('req-len');
    const rUp   = document.getElementById('req-upper');
    const rNum  = document.getElementById('req-num');

    const hasLen   = val.length >= 6;
    const hasUpper = /[A-Z]/.test(val);
    const hasNum   = /[0-9]/.test(val);
    const hasSpec  = /[^A-Za-z0-9]/.test(val);

    rLen.className = hasLen   ? 'met' : '';
    rUp.className  = hasUpper ? 'met' : '';
    rNum.className = hasNum   ? 'met' : '';

    const score = (hasLen ? 1:0) + (hasUpper ? 1:0) + (hasNum ? 1:0) + (hasSpec ? 1:0);

    if (!val) {
      fill.style.width = '0'; fill.style.background = '#eee';
      hint.textContent = ''; hint.className = 'field-hint';
      return;
    }
    if (score <= 1) {
      fill.style.width = '25%'; fill.style.background = '#dc3545';
      hint.textContent = 'Terlalu lemah'; hint.className = 'field-hint error';
    } else if (score === 2) {
      fill.style.width = '50%'; fill.style.background = '#ffc107';
      hint.textContent = 'Sedang'; hint.className = 'field-hint warn';
    } else if (score === 3) {
      fill.style.width = '75%'; fill.style.background = '#17a2b8';
      hint.textContent = 'Kuat'; hint.className = 'field-hint ok';
    } else {
      fill.style.width = '100%'; fill.style.background = '#198754';
      hint.textContent = 'Sangat kuat ✓'; hint.className = 'field-hint ok';
    }

    checkMatch();
  }

  function checkMatch() {
    const p1   = document.getElementById('password_baru').value;
    const p2   = document.getElementById('password_baru_confirmation').value;
    const hint = document.getElementById('matchHint');
    const btn  = document.getElementById('submitBtn');
    const inp1 = document.getElementById('password_baru');
    const inp2 = document.getElementById('password_baru_confirmation');

    if (!p2) { hint.textContent = ''; btn.disabled = true; return; }

    if (p1 === p2 && p1.length >= 6) {
      hint.textContent = 'Password cocok ✓'; hint.className = 'field-hint ok';
      inp1.className = 'is-ok'; inp2.className = 'is-ok';
      btn.disabled = false;
    } else if (p1 !== p2) {
      hint.textContent = 'Password tidak cocok ✗'; hint.className = 'field-hint error';
      inp2.className = 'is-error';
      btn.disabled = true;
    } else {
      hint.textContent = 'Minimal 6 karakter'; hint.className = 'field-hint warn';
      btn.disabled = true;
    }
  }
</script>
@endpush
