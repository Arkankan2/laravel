@extends('superadmin.layouts.app')
@section('title','Konfigurasi Ruangan')
@section('page-title','Ruangan')
@section('page-subtitle','Manajemen ruangan per lantai')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Daftar Ruangan</div>
            <div class="card-subtitle">{{ $ruangans->count() }} ruangan terdaftar</div>
        </div>
        <a href="{{ route('superadmin.ruangan.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Ruangan
        </a>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>#</th><th>Gedung</th><th>Lantai</th><th>Nama Ruangan</th><th>Kode</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($ruangans as $r)
                <tr>
                    <td style="color:var(--text-muted)">{{ $r->id }}</td>
                    <td><span class="badge badge-purple">{{ $r->lantai?->gedung?->nama_gedung ?? '-' }}</span></td>
                    <td><span class="badge badge-info">{{ $r->lantai?->nama_lantai ?? '-' }}</span></td>
                    <td style="font-weight:600">{{ $r->nama_ruangan }}</td>
                    <td style="color:var(--text-muted)">{{ $r->kode_ruangan ?? '-' }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('superadmin.ruangan.edit', $r->id) }}" class="btn btn-sm btn-secondary"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" action="{{ route('superadmin.ruangan.destroy', $r->id) }}" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus ruangan {{ $r->nama_ruangan }}?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:40px">Belum ada data ruangan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
