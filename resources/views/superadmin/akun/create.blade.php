@extends('superadmin.layouts.app')

@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna')
@section('page-subtitle', 'Buat akun pengguna baru')

@section('content')
<div style="max-width:640px">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Form Tambah Pengguna</div>
            <a href="{{ route('superadmin.akun.index') }}" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('superadmin.akun.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Username <span style="color:var(--danger)">*</span></label>
                <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                @error('username')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Nama Lengkap <span style="color:var(--danger)">*</span></label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                @error('nama')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="form-group">
                    <label class="form-label">Role <span style="color:var(--danger)">*</span></label>
                    <select name="role" class="form-control" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach(['super_admin','admin','teknisi','mahasiswa','dosen'] as $r)
                            <option value="{{ $r }}" {{ old('role') === $r ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_',' ',$r)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Status <span style="color:var(--danger)">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="aktif"    {{ old('status','aktif') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="form-group">
                    <label class="form-label">Password <span style="color:var(--danger)">*</span></label>
                    <input type="password" name="password" class="form-control" required>
                    @error('password')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password <span style="color:var(--danger)">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:8px">
                <a href="{{ route('superadmin.akun.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-user-plus"></i> Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
