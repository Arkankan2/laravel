@extends('superadmin.layouts.app')
@section('title','Tambah Lantai')
@section('page-title','Tambah Lantai')

@section('content')
<div style="max-width:560px">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Form Tambah Lantai</div>
            <a href="{{ route('superadmin.lantai.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
        <form method="POST" action="{{ route('superadmin.lantai.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Gedung <span style="color:var(--danger)">*</span></label>
                <select name="id_gedung" class="form-control" required>
                    <option value="">-- Pilih Gedung --</option>
                    @foreach($gedungs as $g)
                        <option value="{{ $g->id }}" {{ old('id_gedung') == $g->id ? 'selected' : '' }}>{{ $g->nama_gedung }}</option>
                    @endforeach
                </select>
                @error('id_gedung')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Nama Lantai <span style="color:var(--danger)">*</span></label>
                <input type="text" name="nama_lantai" class="form-control" value="{{ old('nama_lantai') }}" required placeholder="cth: Lantai 1">
                @error('nama_lantai')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Nomor Lantai <span style="color:var(--danger)">*</span></label>
                <input type="number" name="nomor_lantai" class="form-control" value="{{ old('nomor_lantai', 1) }}" min="1" required>
                @error('nomor_lantai')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div style="display:flex;gap:12px;justify-content:flex-end">
                <a href="{{ route('superadmin.lantai.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-layer-group"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
