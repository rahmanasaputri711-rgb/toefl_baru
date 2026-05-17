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
        /* ══════════════════════════════════════
           TOKENS — layered dark, clear contrast
        ══════════════════════════════════════ */
        :root {
            --bg:         #0F172A;   /* page — lifted from #0B1120, less black */
            --surface:    #131F35;   /* card — lifted slightly */
            --surface2:   #1A2844;   /* hover / secondary */
            --surface3:   #1E3358;   /* table header, sidebar active */
            --border:     rgba(255,255,255,.08);
            --border-md:  rgba(255,255,255,.12);

            --accent:     #3B82F6;
            --accent-h:   #2563EB;
            --accent-lt:  rgba(59,130,246,.13);
            --accent-glow:rgba(59,130,246,.22);

            --gold:       #F59E0B;
            --gold-lt:    rgba(245,158,11,.13);
            --green:      #22C55E;
            --green-lt:   rgba(34,197,94,.13);
            --red:        #EF4444;
            --red-lt:     rgba(239,68,68,.13);
            --orange:     #F97316;
            --orange-lt:  rgba(249,115,22,.13);
            --purple:     #A855F7;
            --purple-lt:  rgba(168,85,247,.13);

            /* Fix 5: secondary text more readable on dark bg */
            --text:       #E2E8F0;
            --text-muted: #7A94B0;   /* raised from #64748B */
            --text-dim:   #A8BCCF;   /* raised from #94A3B8 */

            --shadow-sm:  0 1px 4px rgba(0,0,0,.4);
            --shadow-md:  0 4px 20px rgba(0,0,0,.5);
            --shadow-glow:0 4px 24px rgba(59,130,246,.18);

            --sidebar-w:  252px;
            --topbar-h:   60px;
        }

        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior:smooth; }
        body {
            font-family:'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
        }

        /* ══════════════════════════════════════
           SIDEBAR
        ══════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            position: fixed; top:0; left:0; bottom:0;
            z-index: 100; overflow-y: auto;
        }
        /* Accent line kanan sidebar */
        .sidebar::after {
            content: '';
            position: absolute; top:0; right:-1px;
            width: 1px; height: 100%;
            background: linear-gradient(to bottom,
                transparent 0%, rgba(59,130,246,.35) 30%,
                rgba(59,130,246,.15) 70%, transparent 100%);
        }

        .sidebar-brand {
            padding: 20px 18px 18px;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }
        .brand-badge {
            display: inline-flex; align-items: center; gap: 5px;
            background: var(--accent-lt); color: var(--accent);
            font-size: 10px; font-weight: 700; letter-spacing: 1.2px;
            padding: 3px 9px; border-radius: 5px;
            border: 1px solid rgba(59,130,246,.2);
            text-transform: uppercase; margin-bottom: 9px;
        }
        .sidebar-brand h1 {
            font-size: 15.5px; font-weight: 800; color: #fff; line-height: 1.3;
        }
        .sidebar-brand span { font-size: 11px; color: var(--text-muted); margin-top: 2px; display: block; }

        .sidebar-nav { padding: 12px 10px; flex: 1; }

        .nav-section-label {
            font-size: 9.5px; font-weight: 700; letter-spacing: 1.4px;
            color: var(--text-muted); text-transform: uppercase;
            padding: 10px 10px 5px; opacity: .55;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 8px;
            color: var(--text-dim); text-decoration: none;
            font-size: 13px; font-weight: 500;
            transition: all .15s; margin-bottom: 1px;
            background: none; border: none; cursor: pointer;
            width: 100%; font-family: inherit;
        }
        .nav-item i { width: 16px; text-align:center; font-size: 13px; flex-shrink:0; opacity:.65; }
        .nav-item:hover { background: var(--surface2); color: var(--text); }
        .nav-item:hover i { opacity: 1; }
        .nav-item.active {
            background: var(--accent-lt);
            color: #93C5FD; font-weight: 600;
            border-left: 2px solid var(--accent);
            padding-left: 10px;
            box-shadow: inset 0 0 0 1px rgba(59,130,246,.1),
                        0 0 12px rgba(59,130,246,.08);
        }
        .nav-item.active i { opacity: 1; color: var(--accent); }
        .nav-item .badge {
            margin-left: auto; background: var(--red);
            color: #fff; font-size: 10px; font-weight: 700;
            padding: 2px 7px; border-radius: 10px;
        }

        .sidebar-footer {
            padding: 12px; border-top: 1px solid var(--border); flex-shrink: 0;
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 10px; border-radius: 8px;
            transition: background .15s;
        }
        .sidebar-user:hover { background: var(--surface2); }
        .sidebar-user .avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-h));
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px; color: #fff; flex-shrink: 0;
        }
        .sidebar-user .user-info { flex:1; overflow:hidden; min-width:0; }
        .sidebar-user .user-name {
            font-size: 12.5px; font-weight: 600;
            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
        }
        .sidebar-user .user-role { font-size: 11px; color: var(--text-muted); }
        .btn-logout {
            background: none; border: none; cursor: pointer;
            color: var(--text-muted); font-size: 14px;
            padding: 4px; border-radius: 5px; transition: color .15s;
        }
        .btn-logout:hover { color: var(--red); }

        /* ══════════════════════════════════════
           MAIN WRAPPER
        ══════════════════════════════════════ */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1; display: flex; flex-direction: column;
            min-height: 100vh;
        }

        /* ══════════════════════════════════════
           TOPBAR — FIX 3: lebih berisi
        ══════════════════════════════════════ */
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            height: var(--topbar-h);
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top:0; z-index:50;
            box-shadow: 0 2px 8px rgba(0,0,0,.3);
        }
        .topbar-left { display: flex; align-items: center; gap: 14px; }
        .topbar-left h2 { font-size: 16px; font-weight: 700; color: #fff; }
        .topbar-left .breadcrumb { font-size: 12px; color: var(--text-muted); margin-top: 1px; }

        /* Topbar search */
        .topbar-search {
            display: flex; align-items: center; gap: 8px;
            background: var(--surface2); border: 1px solid var(--border);
            border-radius: 8px; padding: 6px 13px;
            width: 200px; transition: all .15s;
        }
        .topbar-search:focus-within {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-lt);
        }
        .topbar-search i { color: var(--text-muted); font-size: 12px; }
        .topbar-search input {
            background: none; border: none; outline: none;
            font-size: 13px; color: var(--text);
            font-family: inherit; width: 100%;
        }
        .topbar-search input::placeholder { color: var(--text-muted); }

        .topbar-right { display: flex; align-items: center; gap: 8px; }

        /* Date pill */
        .topbar-date {
            display: flex; align-items: center; gap: 6px;
            font-size: 12px; color: var(--text-dim);
            background: var(--surface2);
            border: 1px solid var(--border);
            padding: 5px 12px; border-radius: 7px;
        }
        .topbar-date i { font-size: 11px; }

        /* Clock */
        .topbar-clock {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px; color: var(--text-muted);
            background: var(--surface2);
            border: 1px solid var(--border);
            padding: 5px 12px; border-radius: 7px;
        }

        /* Admin role badge */
        .topbar-role {
            display: flex; align-items: center; gap: 6px;
            background: var(--accent-lt); border: 1px solid rgba(59,130,246,.2);
            color: #93C5FD; font-size: 11.5px; font-weight: 700;
            padding: 4px 12px; border-radius: 7px;
        }
        .topbar-role i { font-size: 10px; }

        .notif-btn {
            width: 34px; height: 34px; border-radius: 8px;
            background: var(--surface2); border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted); cursor: pointer; font-size: 13px;
            text-decoration: none; transition: all .15s; position: relative;
        }
        .notif-btn:hover { color: var(--text); border-color: var(--accent); }
        .notif-dot {
            width: 7px; height: 7px; background: var(--red); border-radius: 50%;
            position: absolute; top: 6px; right: 6px;
            border: 1.5px solid var(--surface);
        }

        /* ══════════════════════════════════════
           CONTENT
        ══════════════════════════════════════ */
        .content { padding: 24px 28px 48px; flex: 1; }

        /* ══════════════════════════════════════
           CARDS — FIX 1: more contrast
        ══════════════════════════════════════ */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px; overflow: hidden;
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
            transition: box-shadow .2s;
        }
        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            background: var(--surface);
        }
        .card-header h3 { font-size: 14px; font-weight: 700; color: #fff; display:flex; align-items:center; }
        .card-body { padding: 20px; }

        /* ══════════════════════════════════════
           STAT CARDS — FIX 4: hover + glow
        ══════════════════════════════════════ */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 14px; margin-bottom: 22px;
        }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 15px 18px;     /* Fix 4: reduced from 18px 20px */
            display: flex; align-items: center; gap: 14px;
            box-shadow: var(--shadow-sm);
            transition: all .25s ease;
            cursor: default;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-glow);
            border-color: rgba(59,130,246,.2);
        }
        .stat-icon {
            width: 42px; height: 42px; border-radius: 10px;  /* Fix 4: 46→42 */
            display: flex; align-items: center; justify-content: center;
            font-size: 17px; flex-shrink: 0;                  /* Fix 4: 19→17 */
        }
        .stat-val   { font-size: 24px; font-weight: 800; line-height: 1; color: #fff; } /* Fix 4: 26→24 */
        .stat-label { font-size: 12px; color: var(--text-muted); margin-top: 3px; }

        /* Icon variants */
        .si-blue   { background: rgba(59,130,246,.15);  color: var(--accent); box-shadow:0 0 10px rgba(59,130,246,.12); }
        .si-green  { background: rgba(34,197,94,.15);   color: var(--green);  box-shadow:0 0 10px rgba(34,197,94,.12); }
        .si-gold   { background: rgba(245,158,11,.15);  color: var(--gold);   box-shadow:0 0 10px rgba(245,158,11,.12); }
        .si-red    { background: rgba(239,68,68,.15);   color: var(--red);    box-shadow:0 0 10px rgba(239,68,68,.12); }
        .si-orange { background: rgba(249,115,22,.15);  color: var(--orange); box-shadow:0 0 10px rgba(249,115,22,.12); }
        .si-purple { background: rgba(168,85,247,.15);  color: var(--purple); box-shadow:0 0 10px rgba(168,85,247,.12); }
        .si-cyan   { background: rgba(6,182,212,.15);   color: var(--cyan, #06B6D4); box-shadow:0 0 10px rgba(6,182,212,.12); }

        /* ══════════════════════════════════════
           TABLE — Fix 2: better separation
        ══════════════════════════════════════ */
        .tbl { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        .tbl thead tr {
            background: rgba(255,255,255,.03);  /* Fix 2: subtle, not too dark */
        }
        .tbl th {
            padding: 11px 16px; text-align: left;
            font-size: 10.5px; font-weight: 700; letter-spacing: .8px;
            text-transform: uppercase;
            color: var(--text-dim);             /* Fix 5: uses raised --text-dim */
            white-space: nowrap;
            border-bottom: 1px solid rgba(255,255,255,.07); /* Fix 2: visible separator */
        }
        .tbl td {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(255,255,255,.04);
            vertical-align: middle;
        }
        .tbl tbody tr { transition: background .12s; }
        .tbl tbody tr:hover { background: rgba(255,255,255,.03); }  /* Fix 2: slightly more visible */
        .tbl tbody tr:last-child td { border-bottom: none; }

        /* ══════════════════════════════════════
           BADGES
        ══════════════════════════════════════ */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 11px; font-weight: 600;
            padding: 3px 9px; border-radius: 20px;
        }
        .badge-green  { background: rgba(34,197,94,.13);   color: #4ADE80; }
        .badge-red    { background: rgba(239,68,68,.13);   color: #FCA5A5; }
        .badge-gold   { background: rgba(245,158,11,.13);  color: #FCD34D; }
        .badge-blue   { background: rgba(59,130,246,.13);  color: #93C5FD; }
        .badge-gray   { background: rgba(148,163,184,.1);  color: var(--text-muted); }
        .badge-orange { background: rgba(249,115,22,.13);  color: #FDB97D; }
        .badge-purple { background: rgba(168,85,247,.13);  color: #D8B4FE; }

        /* ══════════════════════════════════════
           BUTTONS
        ══════════════════════════════════════ */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 16px; border-radius: 8px;
            font-size: 13px; font-weight: 600;
            text-decoration: none; cursor: pointer;
            border: 1px solid transparent;
            transition: all .15s; white-space: nowrap;
            font-family: inherit;
        }
        .btn-primary { background: var(--accent); color: #fff; border-color: var(--accent); }
        .btn-primary:hover { background: var(--accent-h); box-shadow: var(--shadow-glow); transform: translateY(-1px); }
        .btn-success { background: var(--green); color: #fff; }
        .btn-success:hover { opacity: .85; }
        .btn-danger  { background: var(--red); color: #fff; }
        .btn-danger:hover  { opacity: .85; }
        .btn-warning { background: var(--gold); color: #000; }
        .btn-warning:hover { opacity: .85; }
        .btn-outline {
            background: transparent;
            border-color: var(--border-md); color: var(--text-muted);
        }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-lt); }
        .btn-sm { padding: 5px 11px; font-size: 12px; border-radius: 7px; }
        .btn-block { display: flex; width: 100%; justify-content: center; }

        /* ══════════════════════════════════════
           FORMS
        ══════════════════════════════════════ */
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block; font-size: 12px; font-weight: 600;
            color: var(--text-dim); margin-bottom: 7px;
            text-transform: uppercase; letter-spacing: .5px;
        }
        .form-control {
            width: 100%; background: var(--surface2);
            border: 1px solid var(--border); border-radius: 8px;
            padding: 10px 14px; color: var(--text);
            font-size: 13.5px; font-family: inherit;
            outline: none; transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-lt); }
        .form-control::placeholder { color: var(--text-muted); }
        select.form-control option { background: var(--surface2); }
        textarea.form-control { resize: vertical; min-height: 100px; }

        /* ══════════════════════════════════════
           GRIDS
        ══════════════════════════════════════ */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; }

        /* ══════════════════════════════════════
           ALERTS
        ══════════════════════════════════════ */
        .alert {
            padding: 12px 16px; border-radius: 9px;
            font-size: 13.5px; margin-bottom: 18px;
            display: flex; align-items: center; gap: 10px;
        }
        .alert-success { background: rgba(34,197,94,.1);   border: 1px solid rgba(34,197,94,.25);   color: #86EFAC; }
        .alert-danger  { background: rgba(239,68,68,.1);   border: 1px solid rgba(239,68,68,.25);   color: #FCA5A5; }
        .alert-info    { background: rgba(59,130,246,.1);  border: 1px solid rgba(59,130,246,.25);  color: #93C5FD; }
        .alert-warning { background: rgba(245,158,11,.1);  border: 1px solid rgba(245,158,11,.25);  color: #FCD34D; }

        /* ══════════════════════════════════════
           EMPTY STATE — FIX 5
        ══════════════════════════════════════ */
        .empty-state {
            text-align: center; padding: 48px 24px;
            color: var(--text-muted);
        }
        .empty-icon {
            width: 56px; height: 56px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; margin: 0 auto 14px;
            background: var(--surface2);
        }
        .empty-state h4 { font-size: 14px; font-weight: 600; color: var(--text-dim); margin-bottom: 5px; }
        .empty-state p  { font-size: 12.5px; color: var(--text-muted); }

        /* ══════════════════════════════════════
           PAGINATION
        ══════════════════════════════════════ */
        .pagination-wrap {
            padding: 13px 20px;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            font-size: 13px;
        }
        .pagination-wrap .pg-info { color: var(--text-muted); }
        .pagination { display: flex; gap: 4px; }
        .pg-btn {
            width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 7px; font-size: 12.5px;
            text-decoration: none; color: var(--text-muted);
            border: 1px solid var(--border);
            transition: all .15s;
        }
        .pg-btn:hover, .pg-btn.active {
            background: var(--accent); border-color: var(--accent); color: #fff;
        }

        /* ══════════════════════════════════════
           SEARCH BAR
        ══════════════════════════════════════ */
        .search-bar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .search-input-wrap { position: relative; flex: 1; min-width: 200px; }
        .search-input-wrap i {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted); font-size: 13px;
        }
        .search-input-wrap input { padding-left: 36px; }

        /* ══════════════════════════════════════
           MODAL
        ══════════════════════════════════════ */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.65); z-index: 200;
            align-items: center; justify-content: center;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            background: var(--surface);
            border: 1px solid var(--border-md);
            border-radius: 14px;
            width: 100%; max-width: 520px;
            max-height: 90vh; overflow-y: auto;
            padding: 26px; box-shadow: var(--shadow-md);
        }
        .modal h3 { font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 20px; }
        .modal-footer {
            display: flex; gap: 10px; justify-content: flex-end; margin-top: 22px;
        }

        /* ══════════════════════════════════════
           SCROLLBAR
        ══════════════════════════════════════ */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,.08); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,.14); }

        /* ══════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════ */
        @media (max-width: 1024px) {
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform .25s; }
            .sidebar.open { transform: translateX(0); }
            .main-wrap { margin-left: 0; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .content { padding: 16px 14px 40px; }
            .topbar-search { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar" id="adminSidebar">
    <div class="sidebar-brand">
        <span class="brand-badge"><i class="fas fa-shield-alt" style="font-size:9px"></i> Admin Panel</span>
        <h1>TOEFL ITP<br>Polman</h1>
        <span>UPA Bahasa Polman</span>
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
            @if($pending > 0)<span class="badge">{{ $pending }}</span>@endif
        </a>

        <div class="nav-section-label">Pengguna</div>
        <a href="{{ route('admin.user.index') }}" class="nav-item {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Data User
            @php $newUsers = \App\Models\User::where('is_active',0)->where('role','user')->count(); @endphp
            @if($newUsers > 0)<span class="badge">{{ $newUsers }}</span>@endif
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
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- MAIN --}}
<div class="main-wrap">

    {{-- TOPBAR --}}
    <header class="topbar">
        <div class="topbar-left">
            <div>
                <h2>@yield('page-title', 'Dashboard')</h2>
                <div class="breadcrumb">@yield('breadcrumb', 'Admin / Dashboard')</div>
            </div>
        </div>

        {{-- Search --}}
        <div class="topbar-search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Cari data...">
        </div>

        <div class="topbar-right">
            {{-- Date --}}
            <div class="topbar-date">
                <i class="fas fa-calendar-alt"></i>
                <span id="topbar-date">--</span>
            </div>

            {{-- Clock --}}
            <div class="topbar-clock" id="topbar-clock">--:--</div>

            {{-- Role badge --}}
            <div class="topbar-role">
                <i class="fas fa-shield-alt"></i>
                Admin
            </div>

            {{-- Notif --}}
            <a href="{{ route('admin.pengumuman.index') }}" class="notif-btn">
                <i class="fas fa-bell"></i>
                <span class="notif-dot"></span>
            </a>
        </div>
    </header>

    {{-- CONTENT --}}
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
                <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script>
    // Clock + Date
    function tick() {
        const now = new Date();
        const days = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
        const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        document.getElementById('topbar-clock').textContent =
            now.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit', second:'2-digit'});
        document.getElementById('topbar-date').textContent =
            `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
    }
    tick(); setInterval(tick, 1000);
</script>
<script src="{{ asset('js/audio-player.js') }}"></script>
@stack('scripts')
</body>
</html>
