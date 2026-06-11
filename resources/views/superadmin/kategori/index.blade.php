@extends('superadmin.layouts.app')
@section('title','Kategori Fasilitas')
@section('page-title','Kategori Fasilitas')
@section('page-subtitle','Master data kategori fasilitas kampus')

@section('content')
<div style="display:grid;grid-template-columns:1fr 380px;gap:20px">

    <!-- TABLE -->
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Daftar Kategori</div>
                <div class="card-subtitle">{{ $kategoris->count() }} kategori terdaftar</div>
            </div>
            <a href="{{ route('superadmin.kategori.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Tambah
            </a>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr><th>#</th><th>Nama Kategori</th><th>Deskripsi</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $k)
                    <tr>
                        <td style="color:var(--text-muted)">{{ $k->id_kategori }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,rgba(108,99,255,0.2),rgba(255,101,132,0.2));display:flex;align-items:center;justify-content:center;font-size:14px">
                                    @php
                                        $icons = ['Listrik'=>'bolt','Air'=>'droplet','AC'=>'wind','Meja'=>'table','Kursi'=>'chair','Proyektor'=>'video','Jaringan Internet'=>'wifi'];
                                        $icon = $icons[$k->nama_kategori] ?? 'tag';
                                    @endphp
                                    <i class="fa-solid fa-{{ $icon }}" style="color:var(--primary-light)"></i>
                                </div>
                                <span style="font-weight:600">{{ $k->nama_kategori }}</span>
                            </div>
                        </td>
                        <td style="color:var(--text-muted);font-size:13px">{{ Str::limit($k->deskripsi ?? '-', 60) }}</td>
                        <td>
                            <div style="display:flex;gap:6px">
                                <a href="{{ route('superadmin.kategori.edit', $k->id_kategori) }}" class="btn btn-sm btn-secondary"><i class="fa-solid fa-pen"></i></a>
                                <form method="POST" action="{{ route('superadmin.kategori.destroy', $k->id_kategori) }}" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus kategori {{ $k->nama_kategori }}?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:40px">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- QUICK INFO -->
    <div>
        <div class="card">
            <div class="card-title" style="margin-bottom:12px">Contoh Kategori</div>
            <div style="display:flex;flex-direction:column;gap:8px">
                @foreach(['Listrik','Air','AC','Meja','Kursi','Proyektor','Jaringan Internet'] as $contoh)
                <div style="padding:8px 12px;border-radius:8px;background:rgba(255,255,255,0.03);font-size:13px;color:var(--text-muted)">
                    <i class="fa-solid fa-circle-dot" style="color:var(--primary-light);margin-right:8px;font-size:8px"></i> {{ $contoh }}
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
