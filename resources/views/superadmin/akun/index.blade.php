@extends('superadmin.layouts.app')

@section('title', 'Manajemen Akun')
@section('page-title', 'Manajemen Akun')
@section('page-subtitle', 'Kelola semua pengguna sistem')

@section('content')

<!-- FILTER BAR -->
<div class="card" style="margin-bottom:20px">
    <form method="GET" action="{{ route('superadmin.akun.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
        <div style="flex:1;min-width:200px">
            <label class="form-label">Cari Pengguna</label>
            <input type="text" name="search" class="form-control" placeholder="Username, nama, email..." value="{{ request('search') }}">
        </div>
        <div style="min-width:150px">
            <label class="form-label">Role</label>
            <select name="role" class="form-control">
                <option value="">Semua Role</option>
                @foreach(['super_admin','admin','teknisi','mahasiswa','dosen'] as $r)
                    <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$r)) }}</option>
                @endforeach
            </select>
        </div>
        <div style="min-width:150px">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="">Semua Status</option>
                <option value="aktif"     {{ request('status') === 'aktif'     ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif"  {{ request('status') === 'nonaktif'  ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div style="display:flex;gap:8px">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-search"></i> Cari</button>
            <a href="{{ route('superadmin.akun.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- TABLE CARD -->
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Daftar Pengguna</div>
            <div class="card-subtitle">Total: {{ $users->total() }} pengguna</div>
        </div>
        <a href="{{ route('superadmin.akun.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-user-plus"></i> Tambah Pengguna
        </a>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pengguna</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td style="color:var(--text-muted)">{{ $u->id_user }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#6c63ff,#ff6584);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0">
                                {{ strtoupper(substr($u->nama ?? $u->username, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:14px">{{ $u->nama ?? '-' }}</div>
                                <div style="font-size:12px;color:var(--text-muted)">{{ $u->username }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--text-muted);font-size:13px">{{ $u->email ?? '-' }}</td>
                    <td>
                        @php
                            $roleColors = ['super_admin'=>'purple','admin'=>'blue','teknisi'=>'warning','mahasiswa'=>'info','dosen'=>'success'];
                            $rc = $roleColors[$u->role] ?? 'info';
                        @endphp
                        <span class="badge badge-{{ $rc }}">{{ ucfirst(str_replace('_',' ',$u->role)) }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $u->status === 'aktif' ? 'badge-success' : 'badge-danger' }}">
                            <i class="fa-solid fa-circle" style="font-size:7px;margin-right:4px"></i>
                            {{ ucfirst($u->status ?? 'aktif') }}
                        </span>
                    </td>
                    <td style="color:var(--text-muted);font-size:12px">{{ $u->created_at?->format('d/m/Y') }}</td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <a href="{{ route('superadmin.akun.edit', $u->id_user) }}" class="btn btn-sm btn-secondary" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>

                            <!-- Toggle Status -->
                            <form method="POST" action="{{ route('superadmin.akun.toggle-status', $u->id_user) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $u->status === 'aktif' ? 'btn-warning' : 'btn-success' }}"
                                        title="{{ $u->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}"
                                        onclick="return confirm('Ubah status akun ini?')">
                                    <i class="fa-solid {{ $u->status === 'aktif' ? 'fa-ban' : 'fa-check' }}"></i>
                                </button>
                            </form>

                            <!-- Hapus -->
                            @if($u->id_user !== Auth::user()->id_user)
                            <form method="POST" action="{{ route('superadmin.akun.destroy', $u->id_user) }}" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                        onclick="return confirm('Hapus pengguna {{ $u->username }}? Aksi ini tidak dapat dibatalkan.')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--text-muted);padding:40px">
                        <i class="fa-solid fa-users-slash" style="font-size:32px;margin-bottom:10px;display:block"></i>
                        Tidak ada pengguna ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        {{ $users->links('pagination::simple-bootstrap-4') }}
    </div>
</div>
@endsection
