@extends('superadmin.layouts.app')
@section('title','Edit Gedung')
@section('page-title','Edit Gedung')

@section('content')
<div style="max-width:560px">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Edit Gedung: {{ $gedung->nama_gedung }}</div>
            <a href="{{ route('superadmin.gedung.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
        <form method="POST" action="{{ route('superadmin.gedung.update', $gedung->id) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Gedung <span style="color:var(--danger)">*</span></label>
                <input type="text" name="nama_gedung" class="form-control" value="{{ old('nama_gedung', $gedung->nama_gedung) }}" required>
                @error('nama_gedung')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Kode Gedung</label>
                <input type="text" name="kode_gedung" class="form-control" value="{{ old('kode_gedung', $gedung->kode_gedung) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $gedung->deskripsi) }}</textarea>
            </div>
            <div style="display:flex;gap:12px;justify-content:flex-end">
                <a href="{{ route('superadmin.gedung.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
