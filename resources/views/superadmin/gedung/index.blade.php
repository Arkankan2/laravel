@extends('superadmin.layouts.app')
@section('title','Konfigurasi Gedung')
@section('page-title','Gedung')
@section('page-subtitle','Manajemen data gedung kampus')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Daftar Gedung</div>
            <div class="card-subtitle">{{ $gedungs->count() }} gedung terdaftar</div>
        </div>
        <a href="{{ route('superadmin.gedung.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Gedung
        </a>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>#</th><th>Nama Gedung</th><th>Kode</th><th>Deskripsi</th><th>Jumlah Lantai</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($gedungs as $g)
                <tr>
                    <td style="color:var(--text-muted)">{{ $g->id }}</td>
                    <td style="font-weight:600">{{ $g->nama_gedung }}</td>
                    <td><span class="badge badge-purple">{{ $g->kode_gedung ?? '-' }}</span></td>
                    <td style="color:var(--text-muted);font-size:13px;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $g->deskripsi ?? '-' }}</td>
                    <td><span class="badge badge-info">{{ $g->lantais_count }} lantai</span></td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('superadmin.gedung.edit', $g->id) }}" class="btn btn-sm btn-secondary">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('superadmin.gedung.destroy', $g->id) }}" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus gedung {{ $g->nama_gedung }}? Semua lantai dan ruangan di dalamnya juga akan terhapus!')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:40px">Belum ada data gedung.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
