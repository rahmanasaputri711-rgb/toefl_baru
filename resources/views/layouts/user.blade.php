<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Dashboard') — TOEFL ITP Polman</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/audio-player.css') }}">
    <style>
        :root {
            --blue:      #1a56db;
            --blue-dark: #1e3a8a;
            --blue-mid:  #2563eb;
            --navy:      #0f2456;
            --navy-mid:  #162244;
            --blue-light:#eff6ff;
            --blue-pale: #dbeafe;
            --sidebar-bg:#0f2456;
            --sidebar-w: 250px;
            --text:      #1e293b;
            --muted:     #64748b;
            --border:    #e2e8f0;
            --bg:        #f1f5f9;
            --white:     #ffffff;
            --green:     #16a34a;
            --red:       #dc2626;
            --gold:      #d97706;
            --orange:    #ea580c;
            --purple:    #7c3aed;
        }
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text);
            min-height:100vh;display:flex}

        /* ── SIDEBAR ── */
        .sidebar{
            width:var(--sidebar-w);background:var(--sidebar-bg);
            display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;
            z-index:100;overflow-y:auto;
        }
        .sb-brand{
            padding:20px 18px 18px;
            border-bottom:1px solid rgba(255,255,255,.1);
        }
        .sb-logo{display:flex;align-items:center;gap:10px;margin-bottom:12px}
        .sb-logo-icon{
            width:38px;height:38px;border-radius:10px;
            background:rgba(255,255,255,.15);
            display:flex;align-items:center;justify-content:center;
            color:#fff;font-size:18px;font-weight:900;
        }
        .sb-logo-text{font-size:15px;font-weight:800;color:#fff}
        .sb-logo-sub{font-size:10.5px;color:rgba(255,255,255,.5);margin-top:1px}
        .sb-user{
            background:rgba(255,255,255,.08);border-radius:10px;padding:10px 12px;
            display:flex;align-items:center;gap:10px;
        }
        .sb-avatar{
            width:34px;height:34px;border-radius:50%;
            background:linear-gradient(135deg,#60a5fa,#3b82f6);
            display:flex;align-items:center;justify-content:center;
            font-weight:700;font-size:14px;color:#fff;flex-shrink:0;
        }
        .sb-uname{font-size:13px;font-weight:600;color:#fff;
            white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .sb-uemail{font-size:11px;color:rgba(255,255,255,.5);
            white-space:nowrap;overflow:hidden;text-overflow:ellipsis}

        .sb-nav{padding:14px 12px;flex:1}
        .sb-section{
            font-size:10px;font-weight:700;letter-spacing:1.5px;
            color:rgba(255,255,255,.35);text-transform:uppercase;
            padding:10px 8px 5px;
        }
        .nav-item{
            display:flex;align-items:center;justify-content:space-between;
            padding:9px 12px;border-radius:9px;color:rgba(255,255,255,.65);
            text-decoration:none;font-size:13.5px;font-weight:500;
            transition:all .15s;margin-bottom:2px;
        }
        .nav-item-left{display:flex;align-items:center;gap:10px}
        .nav-item i{width:17px;text-align:center;font-size:13px;flex-shrink:0}
        .nav-item:hover{background:rgba(255,255,255,.1);color:#fff}
        .nav-item.active{background:rgba(255,255,255,.15);color:#fff;font-weight:600}
        .nav-item.active i{color:#93c5fd}
        .nav-badge{
            background:#dc2626;color:#fff;
            font-size:10px;font-weight:700;padding:2px 6px;border-radius:10px;
        }
        .nav-chevron{font-size:10px;color:rgba(255,255,255,.35)}

        .sb-footer{padding:14px;border-top:1px solid rgba(255,255,255,.1)}
        .btn-logout{
            display:flex;align-items:center;gap:9px;width:100%;
            padding:10px 12px;background:rgba(255,255,255,.06);
            border:1px solid rgba(255,255,255,.1);border-radius:9px;
            color:rgba(255,255,255,.6);cursor:pointer;font-size:13px;
            font-family:inherit;transition:all .15s;
        }
        .btn-logout:hover{background:rgba(220,38,38,.15);border-color:rgba(220,38,38,.3);color:#fca5a5}

        /* ── MAIN ── */
        .main-wrap{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}

        /* ── TOPBAR ── */
        .topbar{
            background:#fff;border-bottom:1px solid var(--border);
            padding:0 24px;height:62px;display:flex;align-items:center;
            justify-content:space-between;position:sticky;top:0;z-index:50;
            box-shadow:0 1px 3px rgba(0,0,0,.04);
        }
        .topbar-title{font-size:17px;font-weight:700;color:var(--navy)}
        .topbar-bread{font-size:12px;color:var(--muted);margin-top:1px}
        .topbar-right{display:flex;align-items:center;gap:12px}
        .notif-btn{
            width:38px;height:38px;border-radius:9px;background:var(--bg);
            border:1px solid var(--border);display:flex;align-items:center;justify-content:center;
            color:var(--muted);text-decoration:none;transition:all .15s;position:relative;
        }
        .notif-btn:hover{border-color:var(--blue);color:var(--blue)}
        .notif-dot{
            width:8px;height:8px;background:var(--red);border-radius:50%;
            position:absolute;top:6px;right:6px;border:2px solid #fff;
        }

        /* ── CONTENT ── */
        .content{padding:24px;flex:1}

        /* ── CARDS ── */
        .card{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden}
        .card-header{
            padding:16px 22px;border-bottom:1px solid var(--border);
            display:flex;align-items:center;justify-content:space-between;
        }
        .card-header h3{font-size:15px;font-weight:700;color:var(--navy)}
        .card-body{padding:22px}

        /* ── STAT CARDS ── */
        .stat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(195px,1fr));gap:14px;margin-bottom:22px}
        .stat-card{
            background:#fff;border:1px solid var(--border);border-radius:14px;
            padding:18px;display:flex;align-items:center;gap:14px;
        }
        .stat-icon{
            width:48px;height:48px;border-radius:12px;
            display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;
        }
        .si-blue  {background:var(--blue-light);color:var(--blue)}
        .si-green {background:#f0fdf4;color:var(--green)}
        .si-gold  {background:#fffbeb;color:var(--gold)}
        .si-red   {background:#fff1f2;color:var(--red)}
        .si-purple{background:#f5f3ff;color:var(--purple)}
        .si-orange{background:#fff7ed;color:var(--orange)}
        .stat-val  {font-size:26px;font-weight:800;line-height:1;color:var(--navy)}
        .stat-label{font-size:12px;color:var(--muted);margin-top:3px}

        /* ── TABLE ── */
        .tbl{width:100%;border-collapse:collapse;font-size:13.5px}
        .tbl thead tr{background:#f8fafc}
        .tbl th{
            padding:10px 16px;text-align:left;font-size:11px;font-weight:700;
            letter-spacing:.7px;text-transform:uppercase;color:var(--muted);
            border-bottom:1px solid var(--border);
        }
        .tbl td{padding:13px 16px;border-bottom:1px solid var(--border);vertical-align:middle}
        .tbl tbody tr:hover{background:#fafbfc}
        .tbl tbody tr:last-child td{border-bottom:none}

        /* ── BADGE ── */
        .badge{display:inline-flex;align-items:center;gap:4px;
            font-size:11.5px;font-weight:600;padding:3px 10px;border-radius:20px}
        .badge-green {background:#dcfce7;color:var(--green)}
        .badge-red   {background:#fee2e2;color:var(--red)}
        .badge-gold  {background:#fef3c7;color:var(--gold)}
        .badge-blue  {background:var(--blue-pale);color:var(--blue)}
        .badge-gray  {background:#f1f5f9;color:var(--muted)}
        .badge-orange{background:#fff7ed;color:var(--orange)}
        .badge-purple{background:#f5f3ff;color:var(--purple)}

        /* ── BUTTONS ── */
        .btn{
            display:inline-flex;align-items:center;gap:7px;padding:9px 18px;
            border-radius:9px;font-size:13.5px;font-weight:600;text-decoration:none;
            cursor:pointer;border:none;transition:all .15s;white-space:nowrap;font-family:inherit;
        }
        .btn-primary{background:var(--blue);color:#fff}
        .btn-primary:hover{background:var(--blue-dark)}
        .btn-success{background:#16a34a;color:#fff}
        .btn-success:hover{opacity:.88}
        .btn-danger {background:var(--red);color:#fff}
        .btn-danger:hover{opacity:.88}
        .btn-warning{background:#f59e0b;color:#000}
        .btn-outline{background:transparent;border:1.5px solid var(--border);color:var(--muted)}
        .btn-outline:hover{border-color:var(--blue);color:var(--blue)}
        .btn-sm{padding:6px 13px;font-size:12px}
        .btn-block{width:100%;justify-content:center}

        /* ── FORMS ── */
        .form-group{margin-bottom:16px}
        .form-label{display:block;font-size:12px;font-weight:700;color:var(--muted);
            margin-bottom:7px;text-transform:uppercase;letter-spacing:.5px}
        .form-control{
            width:100%;background:#f9fafb;border:1.5px solid var(--border);
            border-radius:9px;padding:10px 14px;color:var(--text);font-size:14px;
            font-family:inherit;outline:none;transition:border-color .15s;
        }
        .form-control:focus{border-color:var(--blue);background:#fff}
        .form-control::placeholder{color:#9ca3af}
        select.form-control option{background:#fff}
        textarea.form-control{resize:vertical;min-height:90px}

        /* ── GRID ── */
        .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:16px}
        .grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px}

        /* ── ALERTS ── */
        .alert{
            padding:12px 16px;border-radius:10px;font-size:13.5px;margin-bottom:18px;
            display:flex;align-items:flex-start;gap:10px;
        }
        .alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d}
        .alert-danger {background:#fff1f2;border:1px solid #fecdd3;color:#be123c}
        .alert-info   {background:var(--blue-light);border:1px solid var(--blue-pale);color:var(--blue)}
        .alert-warning{background:#fffbeb;border:1px solid #fde68a;color:#92400e}

        /* ── EMPTY STATE ── */
        .empty-state{text-align:center;padding:50px 20px;color:var(--muted)}
        .empty-state i{font-size:36px;margin-bottom:12px;opacity:.3;display:block}
        .empty-state p{font-size:14px}

        /* ── PAGINATION ── */
        .pg-wrap{
            padding:14px 20px;border-top:1px solid var(--border);
            display:flex;align-items:center;justify-content:space-between;font-size:13px;
        }
        .pg-info{color:var(--muted)}

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar{width:4px;height:4px}
        ::-webkit-scrollbar-track{background:transparent}
        ::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:10px}
    </style>
    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar">
    <div class="sb-brand">
        <div class="sb-logo">
            <div class="sb-logo-icon">T</div>
            <div>
                <div class="sb-logo-text">TOEFL Prep</div>
                <div class="sb-logo-sub">UPA Bahasa Polman</div>
            </div>
        </div>
        <div class="sb-user">
            <div class="sb-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
            <div style="overflow:hidden;flex:1">
                <div class="sb-uname">{{ auth()->user()->name ?? 'User' }}</div>
                <div class="sb-uemail">{{ auth()->user()->email ?? '' }}</div>
            </div>
        </div>
    </div>

    <nav class="sb-nav">
        <div class="sb-section">Menu Utama</div>
        <a href="{{ route('user.dashboard') }}" class="nav-item {{ request()->routeIs('user.dashboard') ? 'active':'' }}">
            <div class="nav-item-left"><i class="fas fa-home"></i> Dashboard</div>
        </a>

        <div class="sb-section">Belajar</div>
        <a href="{{ route('user.materi.index') }}" class="nav-item {{ request()->routeIs('user.materi.*') ? 'active':'' }}">
            <div class="nav-item-left"><i class="fas fa-book-open"></i> Materi</div>
            <i class="fas fa-chevron-right nav-chevron"></i>
        </a>
        <a href="{{ route('user.latihan.index') }}" class="nav-item {{ request()->routeIs('user.latihan.*') ? 'active':'' }}">
            <div class="nav-item-left"><i class="fas fa-pen-to-square"></i> Praktik</div>
            <i class="fas fa-chevron-right nav-chevron"></i>
        </a>

        <div class="sb-section">Tes</div>
        <a href="{{ route('user.tes.mini') }}" class="nav-item {{ request()->routeIs('user.tes.mini*') ? 'active':'' }}">
            <div class="nav-item-left"><i class="fas fa-bolt"></i> Mini Test</div>
        </a>
        <a href="{{ route('user.tes.simulasi') }}" class="nav-item {{ request()->routeIs('user.tes.simulasi*') ? 'active':'' }}">
            <div class="nav-item-left"><i class="fas fa-flask"></i> Simulasi</div>
        </a>
        <a href="{{ route('user.tes.full') }}" class="nav-item {{ request()->routeIs('user.tes.full*') ? 'active':'' }}">
            <div class="nav-item-left"><i class="fas fa-graduation-cap"></i> Full Test</div>
            @php
                $pendingDaftar = auth()->check()
                    ? \App\Models\PendaftaranTes::where('user_id',auth()->id())
                        ->where('status_pendaftaran','menunggu')->exists()
                    : false;
            @endphp
            @if($pendingDaftar)<span class="nav-badge">!</span>@endif
        </a>

        <div class="sb-section">Hasil</div>
        <a href="{{ route('user.hasil.index') }}" class="nav-item {{ request()->routeIs('user.hasil.*') ? 'active':'' }}">
            <div class="nav-item-left"><i class="fas fa-trophy"></i> Hasil</div>
        </a>
        <a href="{{ route('user.hasil.index') }}" class="nav-item {{ false ? 'active':'' }}">
            <div class="nav-item-left"><i class="fas fa-clock"></i> Riwayat</div>
        </a>
        <a href="{{ route('user.hasil.index') }}" class="nav-item {{ false ? 'active':'' }}">
            <div class="nav-item-left"><i class="fas fa-chart-line"></i> Grafik</div>
        </a>

        <div class="sb-section">Akun</div>
        <a href="{{ route('user.profil') }}" class="nav-item {{ request()->routeIs('user.profil*') ? 'active':'' }}">
            <div class="nav-item-left"><i class="fas fa-user-circle"></i> Lengkapi Data</div>
            <i class="fas fa-chevron-right nav-chevron"></i>
        </a>
        @php
            $unread = auth()->check()
                ? \App\Models\Notifikasi::where('user_id',auth()->id())->where('is_read',0)->count()
                : 0;
        @endphp
        <a href="{{ route('user.notifikasi') }}" class="nav-item {{ request()->routeIs('user.notifikasi*') ? 'active':'' }}">
            <div class="nav-item-left"><i class="fas fa-bell"></i> Jadwal</div>
            @if($unread > 0)<span class="nav-badge">{{ $unread }}</span>@endif
        </a>
    </nav>

    <div class="sb-footer">
        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-power-off"></i> Logout
            </button>
        </form>
    </div>
</aside>

{{-- MAIN --}}
<div class="main-wrap">
    <header class="topbar">
        <div>
            <div class="topbar-title">@yield('page-title','Dashboard')</div>
            <div class="topbar-bread">@yield('breadcrumb','Home / Dashboard')</div>
        </div>
        <div class="topbar-right">
            @php $__unreadCount = auth()->check() ? auth()->user()->notifikasiUnread()->count() : 0; @endphp
            <a href="{{ route('user.notifikasi') }}" class="notif-btn" style="position:relative" title="Notifikasi">
                <i class="fas fa-bell" style="font-size:14px"></i>
                @if($__unreadCount > 0)
                <span style="position:absolute;top:-4px;right:-4px;
                    background:#dc2626;color:#fff;border-radius:50%;
                    min-width:16px;height:16px;font-size:9px;font-weight:700;
                    display:flex;align-items:center;justify-content:center;
                    padding:0 3px;border:1.5px solid #fff;line-height:1">
                    {{ $__unreadCount > 9 ? '9+' : $__unreadCount }}
                </span>
                @endif
            </a>
            <a href="{{ route('user.profil') }}" style="width:38px;height:38px;border-radius:50%;
                background:linear-gradient(135deg,var(--blue),var(--blue-dark));
                display:flex;align-items:center;justify-content:center;
                color:#fff;font-weight:700;font-size:14px;text-decoration:none">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
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
            <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        </div>
        @endif
        @yield('content')
    </main>
</div>

<script src="{{ asset('js/audio-player.js') }}"></script>
@stack('scripts')
@include('partials.firebase-push')
</body>
</html>
