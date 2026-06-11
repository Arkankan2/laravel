<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin') — Sistem Pelaporan Fasilitas</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:       #6c63ff;
            --primary-dark:  #4c46b8;
            --primary-light: #a09aff;
            --accent:        #ff6584;
            --success:       #22c55e;
            --warning:       #f59e0b;
            --danger:        #ef4444;
            --info:          #3b82f6;

            --bg-dark:       #0f0f1a;
            --bg-card:       #1a1a2e;
            --bg-sidebar:    #16213e;
            --bg-input:      #0f3460;

            --text-primary:  #e2e8f0;
            --text-muted:    #94a3b8;
            --text-dark:     #64748b;

            --border:        rgba(255,255,255,0.08);
            --radius:        12px;
            --shadow:        0 4px 24px rgba(0,0,0,0.4);
            --sidebar-w:     260px;
            --navbar-h:      64px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-dark);
            color: var(--text-primary);
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ───────────────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 100;
            transition: transform .3s ease;
        }
        .sidebar-brand {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-brand .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .sidebar-brand h2 {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.3;
            letter-spacing: 0.3px;
        }
        .sidebar-brand span {
            font-size: 10px;
            color: var(--primary-light);
            font-weight: 500;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 16px 12px;
        }
        .nav-section-title {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-dark);
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 12px 12px 6px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all .2s ease;
            margin-bottom: 2px;
        }
        .nav-item:hover {
            background: rgba(108,99,255,0.12);
            color: var(--primary-light);
        }
        .nav-item.active {
            background: linear-gradient(135deg, rgba(108,99,255,0.25), rgba(108,99,255,0.1));
            color: var(--primary-light);
            border-left: 3px solid var(--primary);
        }
        .nav-item i { width: 18px; text-align: center; font-size: 15px; }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--border);
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(255,255,255,0.04);
            margin-bottom: 8px;
        }
        .sidebar-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            object-fit: cover;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .sidebar-user-info { min-width: 0; }
        .sidebar-user-name { font-size: 13px; font-weight: 600; color: var(--text-primary); truncate }
        .sidebar-user-role { font-size: 11px; color: var(--primary-light); }

        /* ── MAIN CONTENT ──────────────────────────────────────── */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── TOPBAR ────────────────────────────────────────────── */
        .topbar {
            height: var(--navbar-h);
            background: rgba(26,26,46,0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-title { font-size: 18px; font-weight: 700; }
        .topbar-actions { display: flex; align-items: center; gap: 16px; }
        .topbar-icon-btn {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: rgba(255,255,255,0.06);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted);
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
        }
        .topbar-icon-btn:hover { background: rgba(108,99,255,0.2); color: var(--primary-light); }

        /* ── PAGE CONTENT ──────────────────────────────────────── */
        .page-content {
            padding: 28px;
            flex: 1;
        }

        /* ── ALERTS / FLASH ────────────────────────────────────── */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            animation: slideDown .3s ease;
        }
        @keyframes slideDown { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
        .alert-success { background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.3); color: #86efac; }
        .alert-danger  { background: rgba(239,68,68,0.12);  border: 1px solid rgba(239,68,68,0.3);  color: #fca5a5; }
        .alert-warning { background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.3); color: #fcd34d; }

        /* ── CARDS ─────────────────────────────────────────────── */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
        }
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .card-title { font-size: 16px; font-weight: 700; }
        .card-subtitle { font-size: 13px; color: var(--text-muted); margin-top: 2px; }

        /* ── STAT CARDS ────────────────────────────────────────── */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px,1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            position: relative;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,0.3); }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 80px; height: 80px;
            border-radius: 50%;
            opacity: 0.08;
        }
        .stat-card.purple::before { background: var(--primary); }
        .stat-card.blue::before   { background: var(--info); }
        .stat-card.green::before  { background: var(--success); }
        .stat-card.orange::before { background: var(--warning); }
        .stat-card.red::before    { background: var(--danger); }
        .stat-card.pink::before   { background: var(--accent); }

        .stat-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            margin-bottom: 14px;
        }
        .stat-icon.purple { background: rgba(108,99,255,0.15); color: var(--primary-light); }
        .stat-icon.blue   { background: rgba(59,130,246,0.15); color: #93c5fd; }
        .stat-icon.green  { background: rgba(34,197,94,0.15);  color: #86efac; }
        .stat-icon.orange { background: rgba(245,158,11,0.15); color: #fcd34d; }
        .stat-icon.red    { background: rgba(239,68,68,0.15);  color: #fca5a5; }
        .stat-icon.pink   { background: rgba(255,101,132,0.15);color: #f9a8d4; }

        .stat-value { font-size: 32px; font-weight: 800; line-height: 1; margin-bottom: 6px; }
        .stat-label { font-size: 12px; color: var(--text-muted); font-weight: 500; }

        /* ── TABLE ─────────────────────────────────────────────── */
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        thead th {
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-dark);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
        }
        tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            color: var(--text-primary);
        }
        tbody tr:hover { background: rgba(255,255,255,0.02); }
        tbody tr:last-child td { border-bottom: none; }

        /* ── BADGES ────────────────────────────────────────────── */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-success { background: rgba(34,197,94,0.15);  color: #86efac; }
        .badge-danger  { background: rgba(239,68,68,0.15);  color: #fca5a5; }
        .badge-warning { background: rgba(245,158,11,0.15); color: #fcd34d; }
        .badge-info    { background: rgba(59,130,246,0.15); color: #93c5fd; }
        .badge-purple  { background: rgba(108,99,255,0.15); color: var(--primary-light); }

        /* ── BUTTONS ───────────────────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all .2s;
            white-space: nowrap;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-success { background: var(--success); color: #fff; }
        .btn-danger  { background: var(--danger);  color: #fff; }
        .btn-warning { background: var(--warning); color: #000; }
        .btn-secondary { background: rgba(255,255,255,0.06); color: var(--text-muted); border: 1px solid var(--border); }
        .btn-secondary:hover { background: rgba(255,255,255,0.1); color: var(--text-primary); }
        .btn-info { background: var(--info); color: #fff; }

        /* ── FORM ──────────────────────────────────────────────── */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px; }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(108,99,255,0.15);
        }
        .form-control option { background: var(--bg-card); }
        .form-error { font-size: 12px; color: #fca5a5; margin-top: 5px; }
        textarea.form-control { resize: vertical; min-height: 100px; }

        /* ── PAGINATION ────────────────────────────────────────── */
        .pagination { display: flex; gap: 6px; margin-top: 20px; flex-wrap: wrap; }
        .pagination a, .pagination span {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            background: rgba(255,255,255,0.05);
            color: var(--text-muted);
            text-decoration: none;
            border: 1px solid var(--border);
            transition: all .2s;
        }
        .pagination a:hover { background: rgba(108,99,255,0.2); color: var(--primary-light); }
        .pagination .active { background: var(--primary); color: #fff; border-color: var(--primary); }

        /* ── LOGOUT FORM ───────────────────────────────────────── */
        .logout-form { margin: 0; }
        .logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.2);
            color: #fca5a5;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all .2s;
        }
        .logout-btn:hover { background: rgba(239,68,68,0.2); }

        /* ── RESPONSIVE ────────────────────────────────────────── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="fa-solid fa-shield-halved"></i></div>
        <div>
            <h2>Sistem Pelaporan</h2>
            <span>Super Admin Panel</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-title">Utama</div>
        <a href="{{ route('superadmin.dashboard') }}"
           class="nav-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge-high"></i> Dashboard
        </a>

        <div class="nav-section-title" style="margin-top:8px">Manajemen</div>
        <a href="{{ route('superadmin.akun.index') }}"
           class="nav-item {{ request()->routeIs('superadmin.akun.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users-gear"></i> Manajemen Akun
        </a>

        <div class="nav-section-title" style="margin-top:8px">Konfigurasi</div>
        <a href="{{ route('superadmin.gedung.index') }}"
           class="nav-item {{ request()->routeIs('superadmin.gedung.*') ? 'active' : '' }}">
            <i class="fa-solid fa-building"></i> Gedung
        </a>
        <a href="{{ route('superadmin.lantai.index') }}"
           class="nav-item {{ request()->routeIs('superadmin.lantai.*') ? 'active' : '' }}">
            <i class="fa-solid fa-layer-group"></i> Lantai
        </a>
        <a href="{{ route('superadmin.ruangan.index') }}"
           class="nav-item {{ request()->routeIs('superadmin.ruangan.*') ? 'active' : '' }}">
            <i class="fa-solid fa-door-open"></i> Ruangan
        </a>
        <a href="{{ route('superadmin.kategori.index') }}"
           class="nav-item {{ request()->routeIs('superadmin.kategori.*') ? 'active' : '' }}">
            <i class="fa-solid fa-tags"></i> Kategori Fasilitas
        </a>

        <div class="nav-section-title" style="margin-top:8px">Sistem</div>
        <a href="{{ route('superadmin.audit-log.index') }}"
           class="nav-item {{ request()->routeIs('superadmin.audit-log.*') ? 'active' : '' }}">
            <i class="fa-solid fa-clipboard-list"></i> Log & Audit
        </a>
        <a href="{{ route('superadmin.backup.index') }}"
           class="nav-item {{ request()->routeIs('superadmin.backup.*') ? 'active' : '' }}">
            <i class="fa-solid fa-database"></i> Backup Sistem
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            @if(Auth::user()->foto_profil)
                <img src="{{ asset('storage/profil/' . Auth::user()->foto_profil) }}"
                     class="sidebar-avatar" style="font-size:0" alt="foto">
            @else
                <div class="sidebar-avatar">
                    {{ strtoupper(substr(Auth::user()->nama ?? Auth::user()->username, 0, 1)) }}
                </div>
            @endif
            <div class="sidebar-user-info">
                <div class="sidebar-user-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:140px">
                    {{ Auth::user()->nama ?? Auth::user()->username }}
                </div>
                <div class="sidebar-user-role">Super Admin</div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i> Keluar
            </button>
        </form>
    </div>
</aside>

<!-- MAIN WRAPPER -->
<div class="main-wrapper">

    <!-- TOPBAR -->
    <header class="topbar">
        <div style="display:flex;align-items:center;gap:14px">
            <button onclick="document.getElementById('sidebar').classList.toggle('open')"
                    style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:18px;display:none" id="menuToggle">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div>
                <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                <div style="font-size:12px;color:var(--text-muted)">@yield('page-subtitle', '')</div>
            </div>
        </div>

        <div class="topbar-actions">
            <a href="{{ route('superadmin.profil.edit') }}" class="topbar-icon-btn" title="Profil">
                <i class="fa-solid fa-user"></i>
            </a>
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <main class="page-content">

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-xmark"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div>
                    @foreach($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @yield('content')

    </main>
</div>

@stack('scripts')

<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            el.style.transition = 'opacity .5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 5000);
</script>
</body>
</html>
