@extends('superadmin.layouts.app')
@section('title','Edit Ruangan')
@section('page-title','Edit Ruangan')

@section('content')
<div style="max-width:560px">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Edit Ruangan: {{ $ruangan->nama_ruangan }}</div>
            <a href="{{ route('superadmin.ruangan.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
        <form method="POST" action="{{ route('superadmin.ruangan.update', $ruangan->id) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Lantai <span style="color:var(--danger)">*</span></label>
                <select name="id_lantai" class="form-control" required>
                    @foreach($lantais as $l)
                        <option value="{{ $l->id }}" {{ (old('id_lantai',$ruangan->id_lantai) == $l->id) ? 'selected' : '' }}>
                            {{ $l->gedung?->nama_gedung }} — {{ $l->nama_lantai }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Ruangan <span style="color:var(--danger)">*</span></label>
                <input type="text" name="nama_ruangan" class="form-control" value="{{ old('nama_ruangan', $ruangan->nama_ruangan) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Kode Ruangan</label>
                <input type="text" name="kode_ruangan" class="form-control" value="{{ old('kode_ruangan', $ruangan->kode_ruangan) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $ruangan->deskripsi) }}</textarea>
            </div>
            <div style="display:flex;gap:12px;justify-content:flex-end">
                <a href="{{ route('superadmin.ruangan.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
