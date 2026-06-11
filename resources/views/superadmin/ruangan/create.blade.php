@extends('superadmin.layouts.app')
@section('title','Tambah Ruangan')
@section('page-title','Tambah Ruangan')

@section('content')
<div style="max-width:560px">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Form Tambah Ruangan</div>
            <a href="{{ route('superadmin.ruangan.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
        <form method="POST" action="{{ route('superadmin.ruangan.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Lantai <span style="color:var(--danger)">*</span></label>
                <select name="id_lantai" class="form-control" required>
                    <option value="">-- Pilih Lantai --</option>
                    @foreach($lantais as $l)
                        <option value="{{ $l->id }}" {{ old('id_lantai') == $l->id ? 'selected' : '' }}>
                            {{ $l->gedung?->nama_gedung }} — {{ $l->nama_lantai }}
                        </option>
                    @endforeach
                </select>
                @error('id_lantai')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Nama Ruangan <span style="color:var(--danger)">*</span></label>
                <input type="text" name="nama_ruangan" class="form-control" value="{{ old('nama_ruangan') }}" required placeholder="cth: Ruang Kelas 101">
                @error('nama_ruangan')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Kode Ruangan</label>
                <input type="text" name="kode_ruangan" class="form-control" value="{{ old('kode_ruangan') }}" placeholder="cth: RK-101">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" placeholder="Deskripsi ruangan (opsional)">{{ old('deskripsi') }}</textarea>
            </div>
            <div style="display:flex;gap:12px;justify-content:flex-end">
                <a href="{{ route('superadmin.ruangan.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-door-open"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
