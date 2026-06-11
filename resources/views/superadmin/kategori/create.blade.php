@extends('superadmin.layouts.app')
@section('title','Tambah Kategori')
@section('page-title','Tambah Kategori Fasilitas')

@section('content')
<div style="max-width:560px">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Form Tambah Kategori</div>
            <a href="{{ route('superadmin.kategori.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
        <form method="POST" action="{{ route('superadmin.kategori.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Kategori <span style="color:var(--danger)">*</span></label>
                <input type="text" name="nama_kategori" class="form-control" value="{{ old('nama_kategori') }}" required placeholder="cth: Listrik, Air, AC...">
                @error('nama_kategori')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" placeholder="Deskripsi kategori (opsional)">{{ old('deskripsi') }}</textarea>
            </div>
            <div style="display:flex;gap:12px;justify-content:flex-end">
                <a href="{{ route('superadmin.kategori.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-tag"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
