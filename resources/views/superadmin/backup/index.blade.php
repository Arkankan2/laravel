@extends('superadmin.layouts.app')
@section('title','Backup Sistem')
@section('page-title','Backup Sistem')
@section('page-subtitle','Kelola backup database')

@section('content')
<div style="display:grid;grid-template-columns:1fr 340px;gap:20px">

    <!-- BACKUP LIST -->
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Riwayat Backup</div>
                <div class="card-subtitle">{{ $backups->count() }} backup tersimpan</div>
            </div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr><th>#</th><th>Nama File</th><th>Ukuran</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($backups as $b)
                    <tr>
                        <td style="color:var(--text-muted)">{{ $b->id }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div style="width:32px;height:32px;border-radius:8px;background:rgba(34,197,94,0.12);display:flex;align-items:center;justify-content:center">
                                    <i class="fa-solid fa-file-code" style="color:#86efac"></i>
                                </div>
                                <span style="font-family:monospace;font-size:13px">{{ $b->nama_file }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $b->ukuran_format }}</span>
                        </td>
                        <td style="font-size:12px;color:var(--text-muted)">{{ $b->created_at?->format('d/m/Y H:i') }}</td>
                        <td>
                            <div style="display:flex;gap:6px">
                                <a href="{{ route('superadmin.backup.download', $b->id) }}" class="btn btn-sm btn-success" title="Download">
                                    <i class="fa-solid fa-download"></i>
                                </a>
                                <form method="POST" action="{{ route('superadmin.backup.destroy', $b->id) }}" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus file backup ini?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:40px">
                        <i class="fa-solid fa-database" style="font-size:32px;margin-bottom:10px;display:block;color:var(--text-dark)"></i>
                        Belum ada backup tersimpan.
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ACTION PANEL -->
    <div>
        <div class="card" style="margin-bottom:16px">
            <div class="card-title" style="margin-bottom:8px">
                <i class="fa-solid fa-database" style="color:var(--primary-light);margin-right:8px"></i>Buat Backup Baru
            </div>
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px;line-height:1.6">
                Backup akan menyimpan seluruh data database MySQL ke dalam file <code style="background:rgba(255,255,255,0.08);padding:2px 6px;border-radius:4px">.sql</code> di server.
            </p>
            <form method="POST" action="{{ route('superadmin.backup.store') }}">
                @csrf
                <button type="submit" class="btn btn-primary" style="width:100%"
                        onclick="return confirm('Buat backup database sekarang?')">
                    <i class="fa-solid fa-play"></i> Mulai Backup
                </button>
            </form>
        </div>

        <div class="card">
            <div class="card-title" style="margin-bottom:12px;font-size:14px">
                <i class="fa-solid fa-circle-info" style="color:var(--info);margin-right:8px"></i>Informasi
            </div>
            <div style="display:flex;flex-direction:column;gap:10px;font-size:13px;color:var(--text-muted)">
                <div><i class="fa-solid fa-folder-open" style="margin-right:8px"></i>Disimpan di <code style="background:rgba(255,255,255,0.06);padding:2px 5px;border-radius:4px">storage/app/backups/</code></div>
                <div><i class="fa-solid fa-clock" style="margin-right:8px"></i>Nama file berdasarkan tanggal & waktu backup</div>
                <div><i class="fa-solid fa-triangle-exclamation" style="color:var(--warning);margin-right:8px"></i>File lama tidak otomatis terhapus</div>
            </div>
        </div>
    </div>
</div>
@endsection
