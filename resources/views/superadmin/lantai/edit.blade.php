@extends('superadmin.layouts.app')
@section('title','Edit Lantai')
@section('page-title','Edit Lantai')

@section('content')
<div style="max-width:560px">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Edit Lantai: {{ $lantai->nama_lantai }}</div>
            <a href="{{ route('superadmin.lantai.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
        <form method="POST" action="{{ route('superadmin.lantai.update', $lantai->id) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Gedung <span style="color:var(--danger)">*</span></label>
                <select name="id_gedung" class="form-control" required>
                    @foreach($gedungs as $g)
                        <option value="{{ $g->id }}" {{ (old('id_gedung',$lantai->id_gedung) == $g->id) ? 'selected' : '' }}>{{ $g->nama_gedung }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Lantai <span style="color:var(--danger)">*</span></label>
                <input type="text" name="nama_lantai" class="form-control" value="{{ old('nama_lantai', $lantai->nama_lantai) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nomor Lantai <span style="color:var(--danger)">*</span></label>
                <input type="number" name="nomor_lantai" class="form-control" value="{{ old('nomor_lantai', $lantai->nomor_lantai) }}" min="1" required>
            </div>
            <div style="display:flex;gap:12px;justify-content:flex-end">
                <a href="{{ route('superadmin.lantai.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
