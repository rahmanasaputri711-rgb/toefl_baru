<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — TOEFL ITP Polman</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/audio-player.css') }}">
    <style>
        :root {
            --navy:       #0f1b2d;
            --navy-mid:   #162236;
            --navy-light: #1e3048;
            --border:     #243450;
            --accent:     #3b82f6;
            --accent-dim: #1d4ed8;
            --gold:       #f59e0b;
            --green:      #10b981;
            --red:        #ef4444;
            --orange:     #f97316;
            --text:       #e2e8f0;
            --text-muted: #7f96b2;
            --sidebar-w:  260px;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--navy);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--navy-mid);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-brand .brand-badge {
            display: inline-block;
            background: var(--accent);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            padding: 3px 8px;
            border-radius: 4px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .sidebar-brand h1 {
            font-size: 17px;
            font-weight: 800;
            color: #fff;
            line-height: 1.3;
        }
        .sidebar-brand span {
            font-size: 12px;
            color: var(--text-muted);
            display: block;
            margin-top: 2px;
        }
        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
        }
        .nav-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            text-transform: uppercase;
            padding: 8px 8px 6px;
            margin-top: 8px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all .15s;
            margin-bottom: 2px;
        }
        .nav-item i { width: 18px; text-align: center; font-size: 14px; }
        .nav-item:hover { background: var(--navy-light); color: var(--text); }
        .nav-item.active {
            background: rgba(59,130,246,.18);
            color: #60a5fa;
            font-weight: 600;
        }
        .nav-item .badge {
            margin-left: auto;
            background: var(--red);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 10px;
        }
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-user .avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-dim));
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px; color: #fff;
            flex-shrink: 0;
        }
        .sidebar-user .user-info { flex: 1; overflow: hidden; }
        .sidebar-user .user-name {
            font-size: 13px; font-weight: 600;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .sidebar-user .user-role {
            font-size: 11px; color: var(--text-muted);
        }
        .btn-logout {
            background: none; border: none; cursor: pointer;
            color: var(--text-muted); font-size: 14px;
            padding: 4px; border-radius: 4px;
            transition: color .15s;
        }
        .btn-logout:hover { color: var(--red); }

        /* ── MAIN ── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── TOPBAR ── */
        .topbar {
            background: var(--navy-mid);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            height: 62px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-left h2 {
            font-size: 17px;
            font-weight: 700;
            color: #fff;
        }
        .topbar-left .breadcrumb {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 1px;
        }
        .topbar-right { display: flex; align-items: center; gap: 14px; }
        .topbar-time {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--text-muted);
            background: var(--navy-light);
            padding: 5px 12px;
            border-radius: 6px;
        }
        .notif-btn {
            width: 36px; height: 36px;
            border-radius: 8px;
            background: var(--navy-light);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted); cursor: pointer;
            font-size: 14px; text-decoration: none;
            transition: all .15s; position: relative;
        }
        .notif-btn:hover { color: var(--text); border-color: var(--accent); }
        .notif-dot {
            width: 8px; height: 8px;
            background: var(--red);
            border-radius: 50%;
            position: absolute; top: 6px; right: 6px;
        }

        /* ── CONTENT ── */
        .content {
            padding: 28px;
            flex: 1;
        }

        /* ── CARDS ── */
        .card {
            background: var(--navy-mid);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }
        .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-header h3 {
            font-size: 15px; font-weight: 700;
        }
        .card-body { padding: 22px; }

        /* ── STAT CARDS ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: var(--navy-mid);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            display: flex; align-items: center; gap: 16px;
        }
        .stat-card .stat-icon {
            width: 48px; height: 48px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }
        .stat-card .stat-val {
            font-size: 26px; font-weight: 800; line-height: 1;
        }
        .stat-card .stat-label {
            font-size: 12px; color: var(--text-muted); margin-top: 3px;
        }
        .si-blue { background: rgba(59,130,246,.15); color: var(--accent); }
        .si-green { background: rgba(16,185,129,.15); color: var(--green); }
        .si-gold { background: rgba(245,158,11,.15); color: var(--gold); }
        .si-red { background: rgba(239,68,68,.15); color: var(--red); }
        .si-orange { background: rgba(249,115,22,.15); color: var(--orange); }

        /* ── TABLE ── */
        .tbl { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        .tbl thead tr { background: var(--navy-light); }
        .tbl th {
            padding: 11px 14px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            color: var(--text-muted);
            white-space: nowrap;
        }
        .tbl td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        .tbl tbody tr:hover { background: rgba(255,255,255,.02); }
        .tbl tbody tr:last-child td { border-bottom: none; }

        /* ── BADGE STATUS ── */
        .badge { display: inline-flex; align-items: center; gap: 5px;
            font-size: 11px; font-weight: 600; padding: 3px 9px; border-radius: 20px; }
        .badge-green  { background: rgba(16,185,129,.15); color: var(--green); }
        .badge-red    { background: rgba(239,68,68,.15);  color: var(--red); }
        .badge-gold   { background: rgba(245,158,11,.15); color: var(--gold); }
        .badge-blue   { background: rgba(59,130,246,.15); color: var(--accent); }
        .badge-gray   { background: rgba(127,150,178,.12); color: var(--text-muted); }
        .badge-orange { background: rgba(249,115,22,.15); color: var(--orange); }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 16px; border-radius: 8px;
            font-size: 13px; font-weight: 600;
            text-decoration: none; cursor: pointer;
            border: none; transition: all .15s; white-space: nowrap;
        }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-dim); }
        .btn-success { background: var(--green); color: #fff; }
        .btn-success:hover { opacity: .85; }
        .btn-danger  { background: var(--red); color: #fff; }
        .btn-danger:hover  { opacity: .85; }
        .btn-warning { background: var(--gold); color: #000; }
        .btn-warning:hover { opacity: .85; }
        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-muted);
        }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
        .btn-sm { padding: 5px 11px; font-size: 12px; }

        /* ── FORMS ── */
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block; font-size: 12.5px; font-weight: 600;
            color: var(--text-muted); margin-bottom: 7px;
            text-transform: uppercase; letter-spacing: .5px;
        }
        .form-control {
            width: 100%;
            background: var(--navy-light);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 14px;
            color: var(--text);
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: border-color .15s;
        }
        .form-control:focus { border-color: var(--accent); }
        .form-control::placeholder { color: var(--text-muted); }
        select.form-control option { background: var(--navy-light); }
        textarea.form-control { resize: vertical; min-height: 100px; }

        /* ── GRID LAYOUT ── */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }

        /* ── ALERTS ── */
        .alert {
            padding: 12px 16px; border-radius: 8px;
            font-size: 13.5px; margin-bottom: 18px;
            display: flex; align-items: center; gap: 10px;
        }
        .alert-success { background: rgba(16,185,129,.12); border: 1px solid rgba(16,185,129,.3); color: #6ee7b7; }
        .alert-danger  { background: rgba(239,68,68,.12);  border: 1px solid rgba(239,68,68,.3); color: #fca5a5; }
        .alert-info    { background: rgba(59,130,246,.12); border: 1px solid rgba(59,130,246,.3); color: #93c5fd; }
        .alert-warning { background: rgba(245,158,11,.12); border: 1px solid rgba(245,158,11,.3); color: #fcd34d; }

        /* ── PAGINATION ── */
        .pagination-wrap {
            padding: 14px 22px;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            font-size: 13px;
        }
        .pagination-wrap .pg-info { color: var(--text-muted); }
        .pagination { display: flex; gap: 4px; }
        .pg-btn {
            width: 34px; height: 34px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 7px; font-size: 13px;
            text-decoration: none; color: var(--text-muted);
            border: 1px solid var(--border);
            transition: all .15s;
        }
        .pg-btn:hover, .pg-btn.active {
            background: var(--accent); border-color: var(--accent); color: #fff;
        }

        /* ── SEARCH ── */
        .search-bar {
            display: flex; align-items: center;
            gap: 12px; flex-wrap: wrap;
        }
        .search-input-wrap {
            position: relative; flex: 1; min-width: 200px;
        }
        .search-input-wrap i {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted); font-size: 13px;
        }
        .search-input-wrap input {
            padding-left: 36px;
        }

        /* ── EMPTY STATE ── */
        .empty-state {
            text-align: center; padding: 60px 20px;
            color: var(--text-muted);
        }
        .empty-state i { font-size: 40px; margin-bottom: 12px; opacity: .4; }
        .empty-state p { font-size: 14px; }

        /* ── MODAL ── */
        .modal-overlay {
            display: none; position: fixed;
            inset: 0; background: rgba(0,0,0,.6);
            z-index: 200; align-items: center; justify-content: center;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            background: var(--navy-mid);
            border: 1px solid var(--border);
            border-radius: 14px;
            width: 100%; max-width: 520px;
            max-height: 90vh; overflow-y: auto;
            padding: 28px;
        }
        .modal h3 { font-size: 17px; font-weight: 700; margin-bottom: 20px; }
        .modal-footer {
            display: flex; gap: 10px; justify-content: flex-end;
            margin-top: 24px;
        }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }
    </style>
    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <span class="brand-badge">Admin Panel</span>
        <h1>TOEFL ITP<br>Polman</h1>
        <span>UPA Bahasa</span>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>

        <div class="nav-section-label">Konten</div>
        <a href="{{ route('admin.soal.index') }}" class="nav-item {{ request()->routeIs('admin.soal.*') ? 'active' : '' }}">
            <i class="fas fa-question-circle"></i> Bank Soal
        </a>
        <a href="{{ route('admin.materi.index') }}" class="nav-item {{ request()->routeIs('admin.materi.*') ? 'active' : '' }}">
            <i class="fas fa-book-open"></i> Materi
        </a>

        <div class="nav-section-label">Tes & Jadwal</div>
        <a href="{{ route('admin.sesi.index') }}" class="nav-item {{ request()->routeIs('admin.sesi.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i> Sesi Tes
        </a>
        <a href="{{ route('admin.pendaftaran.index') }}" class="nav-item {{ request()->routeIs('admin.pendaftaran.*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list"></i> Pendaftaran
            @php $pending = \App\Models\PendaftaranTes::where('status_pendaftaran','menunggu')->count(); @endphp
            @if($pending > 0)
                <span class="badge">{{ $pending }}</span>
            @endif
        </a>

        <div class="nav-section-label">Pengguna</div>
        <a href="{{ route('admin.user.index') }}" class="nav-item {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Data User
            @php $newUsers = \App\Models\User::where('is_active',0)->where('role','user')->count(); @endphp
            @if($newUsers > 0)
                <span class="badge">{{ $newUsers }}</span>
            @endif
        </a>

        <div class="nav-section-label">Monitoring</div>
        <a href="{{ route('admin.monitoring.index') }}" class="nav-item {{ request()->routeIs('admin.monitoring.*') ? 'active' : '' }}">
            <i class="fas fa-desktop"></i> Monitor Tes
        </a>
        <a href="{{ route('admin.laporan.index') }}" class="nav-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> Laporan
        </a>
        <a href="{{ route('admin.evaluasi.index') }}" class="nav-item {{ request()->routeIs('admin.evaluasi.*') ? 'active' : '' }}">
            <i class="fas fa-star"></i> Evaluasi
        </a>

        <div class="nav-section-label">Lainnya</div>
        <a href="{{ route('admin.pengumuman.index') }}" class="nav-item {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}">
            <i class="fas fa-bullhorn"></i> Pengumuman
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="user-role">UPA Bahasa</div>
            </div>
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="btn-logout" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- MAIN CONTENT --}}
<div class="main-wrap">
    <header class="topbar">
        <div class="topbar-left">
            <h2>@yield('page-title', 'Dashboard')</h2>
            <div class="breadcrumb">@yield('breadcrumb', 'Admin / Dashboard')</div>
        </div>
        <div class="topbar-right">
            <div class="topbar-time" id="topbar-clock">--:--:--</div>
            <a href="{{ route('admin.pengumuman.index') }}" class="notif-btn">
                <i class="fas fa-bell"></i>
                <span class="notif-dot"></span>
            </a>
        </div>
    </header>

    <main class="content">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    @foreach($errors->all() as $e)
                        <div>{{ $e }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script>
    // Live clock
    function tick() {
        const now = new Date();
        document.getElementById('topbar-clock').textContent =
            now.toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit',second:'2-digit'});
    }
    tick(); setInterval(tick, 1000);
</script>
<script src="{{ asset('js/audio-player.js') }}"></script>
@stack('scripts')
</body>
</html>
