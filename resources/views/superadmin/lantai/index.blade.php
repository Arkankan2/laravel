@extends('superadmin.layouts.app')
@section('title','Konfigurasi Lantai')
@section('page-title','Lantai')
@section('page-subtitle','Manajemen lantai per gedung')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Daftar Lantai</div>
            <div class="card-subtitle">{{ $lantais->count() }} lantai terdaftar</div>
        </div>
        <a href="{{ route('superadmin.lantai.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Lantai
        </a>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>#</th><th>Gedung</th><th>Nama Lantai</th><th>Nomor Lantai</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($lantais as $l)
                <tr>
                    <td style="color:var(--text-muted)">{{ $l->id }}</td>
                    <td><span class="badge badge-purple">{{ $l->gedung?->nama_gedung ?? '-' }}</span></td>
                    <td style="font-weight:600">{{ $l->nama_lantai }}</td>
                    <td><span class="badge badge-info">Lantai {{ $l->nomor_lantai }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('superadmin.lantai.edit', $l->id) }}" class="btn btn-sm btn-secondary"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" action="{{ route('superadmin.lantai.destroy', $l->id) }}" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus lantai ini? Semua ruangan di dalamnya juga terhapus!')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:40px">Belum ada data lantai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
