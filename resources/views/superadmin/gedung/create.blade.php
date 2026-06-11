@extends('superadmin.layouts.app')
@section('title','Tambah Gedung')
@section('page-title','Tambah Gedung')

@section('content')
<div style="max-width:560px">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Form Tambah Gedung</div>
            <a href="{{ route('superadmin.gedung.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
        <form method="POST" action="{{ route('superadmin.gedung.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Gedung <span style="color:var(--danger)">*</span></label>
                <input type="text" name="nama_gedung" class="form-control" value="{{ old('nama_gedung') }}" required placeholder="cth: Gedung A">
                @error('nama_gedung')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Kode Gedung</label>
                <input type="text" name="kode_gedung" class="form-control" value="{{ old('kode_gedung') }}" placeholder="cth: GDA">
                @error('kode_gedung')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" placeholder="Deskripsi gedung (opsional)">{{ old('deskripsi') }}</textarea>
            </div>
            <div style="display:flex;gap:12px;justify-content:flex-end">
                <a href="{{ route('superadmin.gedung.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-building"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
