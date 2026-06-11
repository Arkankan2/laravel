@extends('superadmin.layouts.app')
@section('title','Log Sistem & Audit')
@section('page-title','Log Sistem & Audit')
@section('page-subtitle','Rekam jejak seluruh aktivitas pengguna')

@section('content')

<!-- FILTER -->
<div class="card" style="margin-bottom:20px">
    <form method="GET" action="{{ route('superadmin.audit-log.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
        <div style="flex:1;min-width:200px">
            <label class="form-label">Cari Aktivitas</label>
            <input type="text" name="aktivitas" class="form-control" placeholder="Login, Hapus Pengguna..." value="{{ request('aktivitas') }}">
        </div>
        <div style="min-width:160px">
            <label class="form-label">Tanggal Dari</label>
            <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
        </div>
        <div style="min-width:160px">
            <label class="form-label">Tanggal Sampai</label>
            <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
        </div>
        <div style="display:flex;gap:8px">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-search"></i> Filter</button>
            <a href="{{ route('superadmin.audit-log.index') }}" class="btn btn-secondary">Reset</a>
            <a href="{{ route('superadmin.audit-log.export') }}" class="btn btn-success">
                <i class="fa-solid fa-download"></i> Export CSV
            </a>
        </div>
    </form>
</div>

<!-- LOG TABLE -->
<div class="card">
    <div class="card-header">
        <div class="card-title">Riwayat Aktivitas</div>
        <span style="color:var(--text-muted);font-size:13px">{{ $logs->total() }} entri</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>#</th><th>Pengguna</th><th>Aktivitas</th><th>Keterangan</th><th>IP Address</th><th>Waktu</th></tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="color:var(--text-muted)">{{ $log->id }}</td>
                    <td>
                        <div style="font-weight:600;font-size:13px">{{ $log->user?->username ?? 'System' }}</div>
                        <div style="font-size:11px;color:var(--text-muted)">{{ ucfirst($log->user?->role ?? '-') }}</div>
                    </td>
                    <td>
                        @php
                            $actColors = ['Login'=>'badge-success','Logout'=>'badge-warning','Hapus'=>'badge-danger','Reset'=>'badge-warning'];
                            $badgeClass = 'badge-info';
                            foreach($actColors as $k=>$v) {
                                if(str_contains($log->aktivitas, $k)) { $badgeClass = $v; break; }
                            }
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $log->aktivitas }}</span>
                    </td>
                    <td style="color:var(--text-muted);font-size:13px;max-width:220px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $log->keterangan ?? '-' }}
                    </td>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted)">{{ $log->ip_address ?? '-' }}</td>
                    <td style="font-size:12px;color:var(--text-muted)">{{ $log->created_at?->format('d/m/Y H:i:s') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:40px">
                    <i class="fa-solid fa-clipboard" style="font-size:32px;margin-bottom:10px;display:block"></i>
                    Belum ada log aktivitas.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">
        {{ $logs->links('pagination::simple-bootstrap-4') }}
    </div>
</div>
@endsection
