@extends('superadmin.layouts.app')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')
@section('page-subtitle', 'Ubah data pengguna: ' . $pengguna->username)

@section('content')
<div style="max-width:640px">

    <!-- EDIT PROFIL -->
    <div class="card" style="margin-bottom:20px">
        <div class="card-header">
            <div class="card-title">Edit Data Pengguna</div>
            <a href="{{ route('superadmin.akun.index') }}" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('superadmin.akun.update', $pengguna->id_user) }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Username <span style="color:var(--danger)">*</span></label>
                <input type="text" name="username" class="form-control" value="{{ old('username', $pengguna->username) }}" required>
                @error('username')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Nama Lengkap <span style="color:var(--danger)">*</span></label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $pengguna->nama) }}" required>
                @error('nama')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $pengguna->email) }}">
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="form-group">
                    <label class="form-label">Role <span style="color:var(--danger)">*</span></label>
                    <select name="role" class="form-control" required>
                        @foreach(['super_admin','admin','teknisi','mahasiswa','dosen'] as $r)
                            <option value="{{ $r }}" {{ (old('role',$pengguna->role) === $r) ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_',' ',$r)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span style="color:var(--danger)">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="aktif"    {{ (old('status',$pengguna->status) === 'aktif')    ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ (old('status',$pengguna->status) === 'nonaktif') ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- RESET PASSWORD -->
    <div class="card">
        <div class="card-title" style="margin-bottom:16px">
            <i class="fa-solid fa-key" style="color:var(--warning);margin-right:8px"></i>Reset Password
        </div>

        <form method="POST" action="{{ route('superadmin.akun.reset-password', $pengguna->id_user) }}">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password_baru" class="form-control" required>
                    @error('password_baru')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_baru_confirmation" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-warning" onclick="return confirm('Reset password pengguna {{ $pengguna->username }}?')">
                <i class="fa-solid fa-rotate"></i> Reset Password
            </button>
        </form>
    </div>
</div>
@endsection
