<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — TOEFL Prep</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
    /* ──────────────────────────────────────────
       TOKENS  — calm, premium, SaaS-grade
    ────────────────────────────────────────── */
    :root {
        /* Primary */
        --blue:      #2563EB;
        --blue-h:    #1D4ED8;
        --blue-lt:   #EFF6FF;
        --blue-pale: #DBEAFE;
        --navy:      #0F172A;

        /* Accent */
        --purple:    #8B5CF6;
        --green:     #22C55E;
        --green-lt:  #DCFCE7;
        --amber:     #F59E0B;
        --amber-lt:  #FEF9C3;
        --red:       #EF4444;
        --red-lt:    #FEE2E2;
        --gold:      #F59E0B;
        --gold-lt:   #FEF9C3;
        --orange:    #F97316;

        /* Legacy aliases (views use these) */
        --accent:    #2563EB;
        --accent2:   #1D4ED8;
        --green-dk:  #16A34A;

        /* Surface */
        --bg:       #F8FAFC;   /* slightly warmer than pure gray */
        --white:    #FFFFFF;
        --surface2: #F1F5F9;
        --text:     #1E293B;
        --muted:    #64748B;
        --muted-lt: #94A3B8;
        --border:   #E2E8F0;

        /* Shadow — ultra-soft only */
        --shadow-xs: 0 1px 2px rgba(15,23,42,.04);
        --shadow-sm: 0 1px 4px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
        --shadow-md: 0 4px 16px rgba(15,23,42,.08), 0 1px 4px rgba(15,23,42,.04);
        --shadow-lg: 0 8px 32px rgba(15,23,42,.1);

        /* Layout */
        --sidebar-w: 232px;
        --topbar-h:  58px;
        --radius:    14px;
        --radius-sm: 9px;
    }

    *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
    html { scroll-behavior:smooth; font-size:14px; }
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text);
        background: var(--bg);
        display: flex;
        min-height: 100vh;
        -webkit-font-smoothing: antialiased;
    }

    /* ──────────────────────────────────────────
       SIDEBAR — darker, calmer, glassmorphism
    ────────────────────────────────────────── */
    .sidebar {
        width: var(--sidebar-w);
        min-height: 100vh;
        /* Calm deep blue — NOT electric, NOT neon */
        background: linear-gradient(170deg, #1E3A8A 0%, #1E40AF 55%, #2563EB 100%);
        position: fixed; top:0; left:0; bottom:0;
        display: flex; flex-direction: column;
        z-index: 300;
        box-shadow: 2px 0 20px rgba(15,23,42,.12);
    }
    /* Subtle top highlight for depth */
    .sidebar::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 160px;
        background: linear-gradient(180deg, rgba(255,255,255,.06) 0%, transparent 100%);
        pointer-events: none;
    }

    /* Brand */
    .sb-brand {
        height: var(--topbar-h);
        display: flex; align-items: center; gap: 11px;
        padding: 0 18px;
        border-bottom: 1px solid rgba(255,255,255,.07);
        flex-shrink: 0; position: relative;
    }
    .sb-logo {
        width: 32px; height: 32px; border-radius: 8px;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; color: #fff; flex-shrink: 0;
    }
    .sb-name  { font-size: 14.5px; font-weight: 800; color: #fff; line-height: 1.2; }
    .sb-sub   { font-size: 10.5px; color: rgba(255,255,255,.45); font-weight: 400; }

    /* Nav */
    .sb-nav   { flex: 1; overflow-y: auto; padding: 12px 10px 8px; position: relative; }
    .sb-nav::-webkit-scrollbar { width: 0; }

    .sb-item {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 12px; border-radius: 9px;
        color: rgba(255,255,255,.65); font-size: 13.5px; font-weight: 500;
        text-decoration: none; transition: all .15s;
        margin-bottom: 1px;
        background: none; border: none; cursor: pointer;
        width: 100%; font-family: inherit;
        position: relative;
    }
    .sb-item i {
        width: 18px; text-align:center; font-size: 14px;
        flex-shrink: 0; opacity: .8;
        transition: opacity .15s;
    }
    .sb-item:hover {
        background: rgba(255,255,255,.1);
        color: rgba(255,255,255,.95);
    }
    .sb-item:hover i { opacity: 1; }
    .sb-item.active {
        /* Glassmorphism active state */
        background: rgba(255,255,255,.15);
        backdrop-filter: blur(8px);
        color: #fff; font-weight: 600;
        box-shadow: inset 0 0 0 1px rgba(255,255,255,.15);
    }
    .sb-item.active i { opacity: 1; }
    .sb-item.active::before {
        content: '';
        position: absolute; left: 0; top: 20%; bottom: 20%;
        width: 3px; border-radius: 0 3px 3px 0;
        background: rgba(255,255,255,.7);
    }
    .sb-badge {
        margin-left: auto; background: #EF4444;
        color: #fff; font-size: 10px; font-weight: 700;
        padding: 2px 6px; border-radius: 20px; min-width: 18px; text-align:center;
    }
    .sb-sep {
        height: 1px; background: rgba(255,255,255,.07);
        margin: 7px 10px;
    }

  /* ──────────────────────────────────────────
   PROMO CARD — clean floating illustration
────────────────────────────────────────── */
.sb-promo {
    margin: 8px 10px;

    padding: 18px 14px 14px;

   

    /* background soft tanpa kotak aneh */


    backdrop-filter: blur(10px);

    text-align: center;

    position: relative;

    overflow: hidden;
}

/* PASTIKAN TIDAK ADA BULAT / KOTAK TRANSPARAN */
.sb-promo::before,
.sb-promo::after {
    display: none !important;
    content: none !important;
}

/* IMAGE */
.sb-promo-img {
    width: 90px;

    display: block;

    margin: -8px auto 10px;

    position: relative;
    z-index: 2;

    background: transparent !important;

    mix-blend-mode: normal;

    /* efek floating */
    filter: drop-shadow(0 10px 18px rgba(0,0,0,0.16));

}

/* floating animation */
@keyframes floating {
    0% {
        transform: translateY(0px);
    }

    50% {
        transform: translateY(-7px);
    }

    100% {
        transform: translateY(0px);
    }
}

/* TEXT */
.sb-promo-text {
    font-size: 10px;

    line-height: 1.6;

    color: rgba(255,255,255,0.88);

    font-weight: 600;

    margin-bottom: 14px;
}

/* BUTTON */
.sb-promo-btn {
    display: flex;

    align-items: center;
    justify-content: center;

    width: 100%;

    padding: 10px 14px;

    border-radius: 12px;

    background: #FFFFFF;

    color: #1E40AF;

    font-size: 12.5px;

    font-weight: 700;

    text-decoration: none;

    transition: all 0.2s ease;

    box-shadow: 0 4px 12px rgba(0,0,0,0.10);
}

.sb-promo-btn:hover {
    transform: translateY(-2px);

    background: #F8FAFC;

    box-shadow: 0 8px 18px rgba(0,0,0,0.12);
}
    /* User footer */
    .sb-foot {
        padding: 8px 10px 10px;
        border-top: 1px solid rgba(255,255,255,.07);
        flex-shrink: 0;
    }
    .sb-user {
        display: flex; align-items: center; gap: 9px;
        padding: 8px 10px; border-radius: 9px;
        transition: background .15s;
    }
    .sb-user:hover { background: rgba(255,255,255,.08); }
    .sb-av {
        width: 32px; height: 32px; border-radius: 50%;
        background: rgba(255,255,255,.18);
        border: 1.5px solid rgba(255,255,255,.25);
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 700; color: #fff; flex-shrink: 0;
    }
    .sb-uname { font-size: 12.5px; font-weight: 600; color: #fff; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:130px; }
    .sb-urole { font-size: 10px; color: rgba(255,255,255,.45); margin-top: 1px; }

    /* ──────────────────────────────────────────
       MAIN WRAPPER
    ────────────────────────────────────────── */
    .main-wrap {
        margin-left: var(--sidebar-w);
        flex: 1; display: flex; flex-direction: column;
        min-height: 100vh;
    }

    /* ──────────────────────────────────────────
       TOPBAR
    ────────────────────────────────────────── */
    .topbar {
        height: var(--topbar-h);
        background: var(--white);
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center;
        padding: 0 24px; gap: 12px;
        position: sticky; top: 0; z-index: 200;
        box-shadow: var(--shadow-xs);
    }
    .topbar-left { flex: 1; display:flex; align-items:center; gap:10px; }
    .tb-title { font-size: 15px; font-weight: 700; color: var(--navy); }
    .tb-sub   { font-size: 12px; color: var(--muted); margin-top: 1px; }
    .topbar-right { display:flex; align-items:center; gap:8px; }

    .tb-btn {
        width: 34px; height: 34px; border-radius: 8px;
        background: var(--bg); border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        color: var(--muted); font-size: 13px;
        text-decoration: none; cursor: pointer;
        transition: all .15s; position: relative;
    }
    .tb-btn:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-lt); }
    .tb-dot {
        position: absolute; top: 6px; right: 6px;
        width: 6px; height: 6px; border-radius: 50%;
        background: var(--red); border: 1.5px solid var(--white);
    }
    .tb-user {
        display: flex; align-items: center; gap: 8px;
        padding: 4px 10px 4px 5px;
        background: var(--bg); border: 1px solid var(--border);
        border-radius: 9px; cursor: pointer; transition: all .15s;
        text-decoration: none;
    }
    .tb-user:hover { border-color: var(--blue); background: var(--blue-lt); }
    .tb-user-av {
        width: 26px; height: 26px; border-radius: 7px;
        background: linear-gradient(135deg, var(--blue), var(--blue-h));
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 700; color: #fff;
    }
    .tb-user-name {
        font-size: 13px; font-weight: 600; color: var(--text);
        max-width: 88px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
    }

    /* Mobile */
    .mob-toggle {
        display: none; align-items:center; justify-content:center;
        width: 34px; height: 34px; border-radius: 8px;
        background: var(--bg); border: 1px solid var(--border);
        color: var(--text); cursor: pointer;
    }
    .sb-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.35); z-index:250; }

    /* ──────────────────────────────────────────
       CONTENT AREA
    ────────────────────────────────────────── */
    .content-area { padding: 20px 24px 48px; flex: 1; }

    /* ──────────────────────────────────────────
       ALERTS
    ────────────────────────────────────────── */
    .alert {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 12px 16px; border-radius: 10px;
        font-size: 13.5px; margin-bottom: 16px;
        border: 1px solid transparent;
    }
    .alert i { margin-top: 2px; flex-shrink: 0; }
    .alert-warning { background: #FFFBEB; border-color: #FDE68A; color: #78350F; }
    .alert-warning i { color: var(--amber); }
    .alert-success  { background: #F0FDF4; border-color: #BBF7D0; color: #14532D; }
    .alert-success i { color: #16A34A; }
    .alert-danger   { background: #FEF2F2; border-color: #FECACA; color: #7F1D1D; }
    .alert-danger i  { color: var(--red); }
    .alert-info     { background: var(--blue-lt); border-color: var(--blue-pale); color: #1E3A8A; }

    /* ──────────────────────────────────────────
       STAT GRID
    ────────────────────────────────────────── */
    .stat-grid {
        display: grid; grid-template-columns: repeat(4,1fr);
        gap: 14px; margin-bottom: 20px;
    }
    .stat-card {
        background: var(--white); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 16px 18px;
        display: flex; align-items: center; gap: 14px;
        box-shadow: var(--shadow-xs); transition: all .2s;
    }
    .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
    .stat-icon {
        width: 44px; height: 44px; border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        font-size: 17px; flex-shrink: 0;
    }
    .si-blue   { background: #EFF6FF; color: #2563EB; }
    .si-gold   { background: #FFFBEB; color: #D97706; }
    .si-green  { background: #F0FDF4; color: #16A34A; }
    .si-purple { background: #F3E8FF; color: #7C3AED; }
    .si-red    { background: #FEF2F2; color: #DC2626; }
    .si-indigo { background: #EEF2FF; color: #4F46E5; }
    .si-cyan   { background: #ECFEFF; color: #0891B2; }
    .si-orange { background: #FFF7ED; color: #EA580C; }
    .stat-val   { font-size: 23px; font-weight: 800; color: var(--navy); line-height: 1.1; }
    .stat-label { font-size: 12px; color: var(--muted); margin-top: 3px; }

    /* ──────────────────────────────────────────
       CARDS
    ────────────────────────────────────────── */
    .card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-xs);
        margin-bottom: 20px; overflow: hidden;
        transition: box-shadow .2s;
    }
    .card:hover { box-shadow: var(--shadow-sm); }
    .card-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 15px 20px;
        border-bottom: 1px solid var(--border);
        background: var(--white);
    }
    .card-header h3 {
        font-size: 14px; font-weight: 700; color: var(--navy);
        display: flex; align-items: center; margin: 0;
    }
    .card-body { padding: 18px 20px; }

    /* ──────────────────────────────────────────
       GRID HELPERS
    ────────────────────────────────────────── */
    .grid-2 { display:grid; grid-template-columns:repeat(2,1fr); gap:18px; margin-bottom:18px; }
    .grid-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; }
    .grid-4 { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; }

    /* ──────────────────────────────────────────
       BUTTONS
    ────────────────────────────────────────── */
    .btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 8px 17px; border-radius: var(--radius-sm);
        font-size: 13.5px; font-weight: 600;
        text-decoration: none; cursor: pointer;
        border: 1.5px solid transparent;
        font-family: inherit; transition: all .15s; white-space: nowrap;
    }
    .btn-primary {
        background: var(--blue); color: #fff; border-color: var(--blue);
    }
    .btn-primary:hover {
        background: var(--blue-h); border-color: var(--blue-h);
        transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,.25);
    }
    .btn-outline {
        background: transparent; border-color: var(--border); color: var(--muted);
    }
    .btn-outline:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-lt); }
    .btn-danger  { background: var(--red); color: #fff; border-color: var(--red); }
    .btn-danger:hover { background: #DC2626; }
    .btn-block   { display: flex; width: 100%; justify-content: center; }
    .btn-sm      { padding: 5px 12px; font-size: 12.5px; border-radius: 7px; }
    .btn-lg      { padding: 11px 26px; font-size: 14.5px; }

    /* ──────────────────────────────────────────
       BADGES
    ────────────────────────────────────────── */
    .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11.5px; font-weight:600; }
    .badge-green  { background:#DCFCE7; color:#15803D; }
    .badge-gold   { background:#FEF9C3; color:#B45309; }
    .badge-red    { background:#FEE2E2; color:#B91C1C; }
    .badge-blue   { background:#DBEAFE; color:#1D4ED8; }
    .badge-gray   { background:#F1F5F9; color:#475569; }
    .badge-purple { background:#EDE9FE; color:#6D28D9; }

    /* ──────────────────────────────────────────
       TABLE
    ────────────────────────────────────────── */
    .tbl { width:100%; border-collapse:collapse; }
    .tbl thead tr { background: var(--bg); }
    .tbl th {
        padding: 10px 16px; text-align: left;
        font-size: 11px; font-weight: 700; color: var(--muted);
        text-transform: uppercase; letter-spacing: .6px;
        border-bottom: 1px solid var(--border);
    }
    .tbl td {
        padding: 11px 16px; font-size: 13.5px;
        color: var(--text); border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    .tbl tbody tr:hover { background: var(--bg); }
    .tbl tbody tr:last-child td { border-bottom: none; }

    /* ──────────────────────────────────────────
       FORMS
    ────────────────────────────────────────── */
    .form-group { margin-bottom: 16px; }
    .form-label { display:block; font-size:13px; font-weight:600; color:var(--navy); margin-bottom:6px; }
    .form-control {
        width: 100%; padding: 9px 13px;
        border: 1.5px solid var(--border); border-radius: 8px;
        font-size: 13.5px; font-family: inherit; color: var(--text);
        background: var(--white); outline: none;
        transition: border-color .15s, box-shadow .15s;
    }
    .form-control:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,.08); }
    .form-control::placeholder { color: var(--muted-lt); }
    .form-control:disabled { background: var(--bg); opacity: .7; }
    select.form-control { cursor: pointer; }
    textarea.form-control { resize: vertical; min-height: 100px; }

    /* ──────────────────────────────────────────
       EMPTY STATE
    ────────────────────────────────────────── */
    .empty-state { text-align:center; padding:44px 24px; color:var(--muted); }
    .empty-state i { font-size:32px; color:#CBD5E1; display:block; margin-bottom:12px; }
    .empty-state p { font-size:14px; margin-bottom:4px; color:var(--text); }
    .empty-state small { font-size:12.5px; color:var(--muted); }

    /* ──────────────────────────────────────────
       RESPONSIVE
    ────────────────────────────────────────── */
    @media (max-width:1024px) { .stat-grid { grid-template-columns:repeat(2,1fr); } }
    @media (max-width:768px) {
        .sidebar { transform:translateX(-100%); transition:transform .25s; }
        .sidebar.open { transform:translateX(0); }
        .sb-overlay.open { display:block; }
        .main-wrap { margin-left:0; }
        .mob-toggle { display:flex; }
        .grid-2, .grid-3 { grid-template-columns:1fr; }
        .stat-grid { grid-template-columns:repeat(2,1fr); }
        .content-area { padding:14px 14px 32px; }
    }
    @media (max-width:480px) { .stat-grid { grid-template-columns:1fr; } }
    </style>
    @stack('styles')
</head>
<body>

<div class="sb-overlay" id="sbOverlay" onclick="closeSidebar()"></div>

<!-- ════════════════ SIDEBAR ════════════════ -->
<aside class="sidebar" id="sidebar">

    <div class="sb-brand">
        <div class="sb-logo"><i class="fas fa-graduation-cap"></i></div>
        <div>
            <div class="sb-name">TOEFL Prep</div>
            <div class="sb-sub">UPA Bahasa Polman</div>
        </div>
    </div>

    <nav class="sb-nav">
        <a href="{{ route('user.dashboard') }}"
           class="sb-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="{{ route('user.materi.index') }}"
           class="sb-item {{ request()->routeIs('user.materi.*') ? 'active' : '' }}">
            <i class="fas fa-book-open"></i> Materi
        </a>
        <a href="{{ route('user.latihan.index') }}"
           class="sb-item {{ request()->routeIs('user.latihan.*') ? 'active' : '' }}">
            <i class="fas fa-pen-nib"></i> Latihan Soal
        </a>

        <div class="sb-sep"></div>

        <a href="{{ route('user.tes.mini') }}"
           class="sb-item {{ request()->routeIs('user.tes.mini*') ? 'active' : '' }}">
            <i class="fas fa-bolt"></i> Mini Test
        </a>
        <a href="{{ route('user.tes.simulasi') }}"
           class="sb-item {{ request()->routeIs('user.tes.simulasi*') ? 'active' : '' }}">
            <i class="fas fa-flask"></i> Tes Simulasi
        </a>
        <a href="{{ route('user.tes.full') }}"
           class="sb-item {{ request()->routeIs('user.tes.full*') ? 'active' : '' }}">
            <i class="fas fa-graduation-cap"></i> Full Test
        </a>
        <a href="{{ route('user.hasil.index') }}"
           class="sb-item {{ request()->routeIs('user.hasil.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> Riwayat Tes
        </a>

        <div class="sb-sep"></div>

        @php $unreadCount = auth()->user()?->notifikasi()->where('is_read',0)->count() ?? 0; @endphp
        <a href="{{ route('user.notifikasi') }}"
           class="sb-item {{ request()->routeIs('user.notifikasi') ? 'active' : '' }}">
            <i class="fas fa-bell"></i> Notifikasi
            @if($unreadCount > 0)
            <span class="sb-badge">{{ $unreadCount }}</span>
            @endif
        </a>
        <a href="{{ route('user.profil') }}"
           class="sb-item {{ request()->routeIs('user.profil') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i> Profil
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sb-item" style="color:rgba(255,255,255,.45)">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </nav>

    <!-- Promo card -->
    <div class="sb-promo">
        <img src="{{ asset('images/hero-vector_2.png') }}"
             alt="Ilustrasi" class="sb-promo-img">
        <p class="sb-promo-text">Ayo tingkatkan<br>skor TOEFL kamu!</p>
        <a href="{{ route('user.tes.simulasi') }}" class="sb-promo-btn">
           Ayo Daftar Tes →
        </a>
    </div>

    <div class="sb-foot">
        <div class="sb-user">
            <div class="sb-av">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
            <div style="flex:1;min-width:0">
                <div class="sb-uname">{{ auth()->user()->name ?? 'User' }}</div>
                <div class="sb-urole">{{ auth()->user()->is_active ? '✓ Terverifikasi' : 'Belum Aktif' }}</div>
            </div>
        </div>
    </div>
</aside>

<!-- ════════════════ MAIN ════════════════ -->
<div class="main-wrap">

    <!-- TOPBAR -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="mob-toggle" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
            <div>
                <div class="tb-title">@yield('page-title', 'Dashboard')</div>
                <div class="tb-sub">@yield('breadcrumb', 'Home / Dashboard')</div>
            </div>
        </div>
        <div class="topbar-right">
            <a href="{{ route('user.notifikasi') }}" class="tb-btn">
                <i class="fas fa-bell"></i>
                @if(($unreadCount ?? 0) > 0)<span class="tb-dot"></span>@endif
            </a>
            <a href="{{ route('user.profil') }}" class="tb-user">
                <div class="tb-user-av">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
                <span class="tb-user-name">{{ explode(' ', auth()->user()->name ?? 'User')[0] }}</span>
                <i class="fas fa-chevron-down" style="font-size:9px;color:var(--muted-lt);margin-left:2px"></i>
            </a>
        </div>
    </header>

    <!-- CONTENT -->
    <main class="content-area">
        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <div>{{ session('success') }}</div>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-times-circle"></i>
            <div>{{ session('error') }}</div>
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Ada kesalahan input:</strong>
                <ul style="margin-top:4px;padding-left:16px">
                    @foreach($errors->all() as $e)
                    <li style="font-size:13px">{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        @yield('content')
    </main>
</div>

<script>
function openSidebar()  {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sbOverlay').classList.add('open');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sbOverlay').classList.remove('open');
}
</script>
@stack('scripts')
</body>
</html>
