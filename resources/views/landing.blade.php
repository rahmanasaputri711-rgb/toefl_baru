<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOEFL ITP — Politeknik Manufaktur Bandung</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --blue:      #1a56db;
            --blue-dark: #1e3a8a;
            --blue-mid:  #2563eb;
            --blue-light:#eff6ff;
            --blue-pale: #dbeafe;
            --navy:      #0f2456;
            --text:      #1e293b;
            --muted:     #64748b;
            --border:    #e2e8f0;
            --white:     #ffffff;
            --bg:        #f8fafc;
            --orange:    #f97316;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior: smooth; }
        body { font-family:'Plus Jakarta Sans',sans-serif; color:var(--text); background:#fff; }

        /* ── NAVBAR ── */
        .navbar {
            background:#fff;
            border-bottom: 1px solid var(--border);
            padding: 0 5%;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top:0; z-index:100;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }
        .nav-brand { display:flex; align-items:center; gap:10px; text-decoration:none; }
        .nav-logo {
            width:38px; height:38px; border-radius:10px;
            background:linear-gradient(135deg,var(--blue),var(--blue-dark));
            display:flex; align-items:center; justify-content:center;
            color:#fff; font-size:18px; font-weight:900;
        }
        .nav-brand-text { font-size:17px; font-weight:800; color:var(--navy); }
        .nav-brand-text span { color:var(--blue); }
        .nav-links { display:flex; align-items:center; gap:32px; }
        .nav-links a {
            font-size:14px; font-weight:500; color:var(--muted);
            text-decoration:none; transition:color .15s;
        }
        .nav-links a:hover { color:var(--blue); }
        .nav-actions { display:flex; align-items:center; gap:10px; }
        .btn-login {
            padding:8px 20px; border-radius:8px; font-size:14px; font-weight:600;
            color:var(--blue); border:1.5px solid var(--blue); background:#fff;
            text-decoration:none; transition:all .15s;
        }
        .btn-login:hover { background:var(--blue-light); }
        .btn-register {
            padding:8px 20px; border-radius:8px; font-size:14px; font-weight:600;
            color:#fff; background:var(--blue); text-decoration:none; transition:all .15s;
        }
        .btn-register:hover { background:var(--blue-dark); }

        /* ── HERO ── */
        .hero {
            background: linear-gradient(135deg, #0f2456 0%, #1a56db 60%, #3b82f6 100%);
            padding: 80px 5% 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            min-height: 480px;
            overflow: hidden;
            position: relative;
        }
        .hero::before {
            content:'';
            position:absolute; inset:0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .hero-content { max-width:520px; position:relative; z-index:1; }
        .hero-badge {
            display:inline-flex; align-items:center; gap:6px;
            background:rgba(255,255,255,.15); color:#fff;
            padding:5px 14px; border-radius:20px; font-size:12.5px; font-weight:600;
            border:1px solid rgba(255,255,255,.25); margin-bottom:20px;
        }
        .hero-title { font-size:40px; font-weight:800; color:#fff; line-height:1.2; margin-bottom:14px; }
        .hero-title span { color:#93c5fd; }
        .hero-desc { font-size:15px; color:rgba(255,255,255,.85); line-height:1.7; margin-bottom:28px; }
        .hero-actions { display:flex; gap:12px; }
        .btn-hero-primary {
            padding:12px 28px; border-radius:10px; font-size:15px; font-weight:700;
            background:#fff; color:var(--blue); text-decoration:none; transition:all .15s;
            box-shadow:0 4px 12px rgba(0,0,0,.15);
        }
        .btn-hero-primary:hover { background:#f0f7ff; transform:translateY(-1px); }
        .btn-hero-outline {
            padding:12px 28px; border-radius:10px; font-size:15px; font-weight:600;
            background:rgba(255,255,255,.12); color:#fff; text-decoration:none;
            border:1.5px solid rgba(255,255,255,.4); transition:all .15s;
        }
        .btn-hero-outline:hover { background:rgba(255,255,255,.2); }
        .hero-stats {
            display:flex; gap:28px; margin-top:36px; padding-top:28px;
            border-top:1px solid rgba(255,255,255,.2);
        }
        .hero-stat-val { font-size:26px; font-weight:800; color:#fff; }
        .hero-stat-lbl { font-size:12px; color:rgba(255,255,255,.7); margin-top:2px; }
        .hero-visual {
            flex-shrink:0; width:360px; position:relative; z-index:1;
            display:flex; align-items:center; justify-content:center;
        }
        .hero-illustration {
            width:320px; height:280px;
            background:rgba(255,255,255,.1);
            border-radius:20px; border:1px solid rgba(255,255,255,.2);
            display:flex; flex-direction:column; align-items:center; justify-content:center;
            gap:16px; padding:28px;
        }
        .hi-icon { font-size:56px; }
        .hi-text { font-size:20px; font-weight:800; color:#fff; text-align:center; }
        .hi-sub { font-size:13px; color:rgba(255,255,255,.75); text-align:center; }

        /* ── SECTION BASE ── */
        section { padding: 64px 5%; }
        .section-label {
            font-size:12px; font-weight:700; color:var(--blue); text-transform:uppercase;
            letter-spacing:1.5px; margin-bottom:10px;
        }
        .section-title { font-size:28px; font-weight:800; color:var(--navy); margin-bottom:10px; }
        .section-desc { font-size:15px; color:var(--muted); max-width:560px; line-height:1.7; }
        .section-header { margin-bottom:36px; }

        /* ── FITUR UNGGULAN ── */
        .features-bg { background:var(--bg); }
        .features-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
        .feature-card {
            background:#fff; border-radius:14px; padding:26px;
            border:1px solid var(--border);
            transition:all .2s;
        }
        .feature-card:hover { border-color:var(--blue); box-shadow:0 4px 20px rgba(26,86,219,.1); transform:translateY(-2px); }
        .feature-icon {
            width:52px; height:52px; border-radius:12px; margin-bottom:16px;
            display:flex; align-items:center; justify-content:center; font-size:22px;
        }
        .fi-blue   { background:var(--blue-light); color:var(--blue); }
        .fi-orange { background:#fff7ed; color:var(--orange); }
        .fi-green  { background:#f0fdf4; color:#16a34a; }
        .fi-purple { background:#f5f3ff; color:#7c3aed; }
        .fi-red    { background:#fff1f2; color:#e11d48; }
        .fi-teal   { background:#f0fdfa; color:#0d9488; }
        .feature-title { font-size:16px; font-weight:700; margin-bottom:8px; color:var(--navy); }
        .feature-desc  { font-size:13.5px; color:var(--muted); line-height:1.65; }

        /* ── MATERI ── */
        .materi-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:28px; }
        .materi-card {
            border-radius:14px; padding:24px; text-decoration:none; transition:all .2s;
            border:1px solid var(--border); background:#fff;
            display:flex; align-items:flex-start; gap:16px;
        }
        .materi-card:hover { border-color:var(--blue); box-shadow:0 4px 16px rgba(26,86,219,.1); transform:translateY(-2px); }
        .mc-icon {
            width:48px; height:48px; border-radius:12px; flex-shrink:0;
            display:flex; align-items:center; justify-content:center; font-size:20px;
        }
        .mc-title { font-size:16px; font-weight:700; color:var(--navy); margin-bottom:4px; }
        .mc-desc  { font-size:13px; color:var(--muted); line-height:1.6; }
        .btn-center { text-align:center; }
        .btn-outlined-blue {
            display:inline-flex; align-items:center; gap:8px;
            padding:11px 28px; border-radius:10px; font-size:14px; font-weight:600;
            color:var(--blue); border:1.5px solid var(--blue); text-decoration:none; transition:all .15s;
        }
        .btn-outlined-blue:hover { background:var(--blue-light); }

        /* ── TES ── */
        .tes-bg { background:var(--bg); }
        .tes-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px; }
        .tes-card {
            background:#fff; border-radius:14px; padding:24px;
            border:1px solid var(--border); transition:all .2s;
            display:flex; align-items:center; gap:16px;
        }
        .tes-card:hover { border-color:var(--blue); box-shadow:0 4px 16px rgba(26,86,219,.1); }
        .tc-icon {
            width:50px; height:50px; border-radius:12px; flex-shrink:0;
            display:flex; align-items:center; justify-content:center; font-size:22px;
        }
        .tc-title { font-size:15px; font-weight:700; color:var(--navy); }
        .tc-desc  { font-size:12.5px; color:var(--muted); margin-top:3px; }
        .tes-note {
            text-align:center; font-size:13.5px; color:var(--muted);
            display:flex; align-items:center; justify-content:center; gap:10px;
        }
        .tes-note a {
            color:var(--blue); font-weight:600; text-decoration:none;
            display:inline-flex; align-items:center; gap:5px;
        }

        /* ── JADWAL ── */
        .jadwal-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
        .jadwal-card {
            background:#fff; border-radius:14px; padding:22px;
            border:1px solid var(--border); transition:all .2s;
        }
        .jadwal-card:hover { border-color:var(--blue); }
        .jc-date {
            font-size:11px; font-weight:700; color:var(--blue); text-transform:uppercase;
            letter-spacing:.8px; margin-bottom:8px;
        }
        .jc-title { font-size:15px; font-weight:700; color:var(--navy); margin-bottom:6px; }
        .jc-meta  { font-size:12.5px; color:var(--muted); margin-bottom:14px; }
        .jc-badge {
            display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600;
        }
        .badge-blue { background:var(--blue-pale); color:var(--blue); }
        .badge-green{ background:#dcfce7; color:#16a34a; }
        .badge-red  { background:#fee2e2; color:#dc2626; }

        /* ── CEK SKOR ── */
        .cek-skor-bg {
            background: linear-gradient(135deg, var(--navy), var(--blue));
            text-align:center; color:#fff;
        }
        .cek-skor-bg .section-title { color:#fff; }
        .cek-skor-bg .section-desc  { color:rgba(255,255,255,.8); margin:0 auto 28px; }
        .cek-form {
            display:flex; gap:10px; max-width:480px; margin:0 auto;
        }
        .cek-input {
            flex:1; padding:13px 18px; border-radius:10px;
            border:none; font-size:14px; font-family:inherit; outline:none;
            color:var(--text);
        }
        .btn-cek {
            padding:13px 28px; border-radius:10px; background:#fff; color:var(--blue);
            font-size:14px; font-weight:700; border:none; cursor:pointer; white-space:nowrap;
            font-family:inherit; transition:all .15s;
        }
        .btn-cek:hover { background:#f0f7ff; }

        /* ── PENGUMUMAN ── */
        .pengumuman-grid { display:flex; flex-direction:column; gap:12px; }
        .pengumuman-item {
            background:#fff; border-radius:12px; padding:18px 22px;
            border:1px solid var(--border); display:flex; align-items:flex-start; gap:16px;
        }
        .pi-dot {
            width:10px; height:10px; border-radius:50%; background:var(--blue);
            flex-shrink:0; margin-top:5px;
        }
        .pi-title { font-size:14px; font-weight:600; color:var(--navy); margin-bottom:4px; }
        .pi-desc  { font-size:13px; color:var(--muted); }
        .pi-time  { font-size:11px; color:var(--muted); margin-top:5px; }

        /* ── FOOTER ── */
        footer {
            background:var(--navy);
            color:rgba(255,255,255,.75);
            padding:40px 5% 24px;
        }
        .footer-top {
            display:flex; justify-content:space-between; align-items:flex-start;
            gap:40px; margin-bottom:32px; padding-bottom:28px;
            border-bottom:1px solid rgba(255,255,255,.1);
        }
        .footer-brand { max-width:280px; }
        .footer-logo { display:flex; align-items:center; gap:10px; margin-bottom:12px; }
        .footer-logo-icon {
            width:36px; height:36px; border-radius:9px;
            background:linear-gradient(135deg, #60a5fa, var(--blue));
            display:flex; align-items:center; justify-content:center;
            color:#fff; font-size:16px; font-weight:900;
        }
        .footer-logo-text { font-size:16px; font-weight:800; color:#fff; }
        .footer-desc { font-size:13px; line-height:1.7; }
        .footer-links h4 { font-size:13px; font-weight:700; color:#fff; text-transform:uppercase; letter-spacing:1px; margin-bottom:14px; }
        .footer-links ul { list-style:none; display:flex; flex-direction:column; gap:8px; }
        .footer-links a { font-size:13.5px; color:rgba(255,255,255,.65); text-decoration:none; transition:color .15s; }
        .footer-links a:hover { color:#fff; }
        .footer-bottom {
            display:flex; justify-content:space-between; align-items:center;
            font-size:12.5px; color:rgba(255,255,255,.4);
        }

        /* ── RESPONSIVE ── */
        @media (max-width:768px) {
            .features-grid, .materi-grid, .tes-grid, .jadwal-grid { grid-template-columns:1fr; }
            .hero { flex-direction:column; text-align:center; }
            .hero-visual { width:100%; }
            .hero-actions { justify-content:center; }
            .hero-stats  { justify-content:center; }
            .nav-links   { display:none; }
            .cek-form    { flex-direction:column; }
        }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar">
    <a href="/" class="nav-brand">
        <div class="nav-logo">T</div>
        <span class="nav-brand-text">TOEFL <span>Prep</span></span>
    </a>
    <div class="nav-links">
        <a href="#materi">Materi</a>
        <a href="#latihan">Praktik</a>
        <a href="#tes">Tes</a>
        <a href="#jadwal">Jadwal</a>
    </div>
    <div class="nav-actions">
        <a href="/login" class="btn-login">Login</a>
        <a href="/register" class="btn-register">Register</a>
    </div>
</nav>

{{-- HERO --}}
<section class="hero">
    <div class="hero-content">
        <div class="hero-badge">
            <i class="fas fa-university" style="font-size:11px"></i>
            Politeknik Manufaktur Bandung — UPA Bahasa
        </div>
        <h1 class="hero-title">
            Practice & Test<br>
            <span>TOEFL</span> Online
        </h1>
        <p class="hero-desc">
            Platform tes TOEFL ITP internal kampus dengan sistem modern.
            Belajar, berlatih, dan uji kemampuan bahasa Inggris Anda dengan terstruktur.
        </p>
        <div class="hero-actions">
            <a href="/register" class="btn-hero-primary">
                <i class="fas fa-play-circle"></i> Mulai Sekarang
            </a>
            <a href="#jadwal" class="btn-hero-outline">
                <i class="fas fa-calendar-alt"></i> Lihat Jadwal
            </a>
        </div>
        <div class="hero-stats">
            <div>
                <div class="hero-stat-val">3</div>
                <div class="hero-stat-lbl">Tipe Tes</div>
            </div>
            <div>
                <div class="hero-stat-val">{{ $totalMateri }}</div>
                <div class="hero-stat-lbl">Modul Materi</div>
            </div>
            <div>
                <div class="hero-stat-val">ITP</div>
                <div class="hero-stat-lbl">Standar TOEFL</div>
            </div>
        </div>
    </div>
    <div class="hero-visual">
        <div class="hero-illustration">
            <div class="hi-icon">🎓</div>
            <div class="hi-text">TOEFL ITP<br>Internal Polman</div>
            <div class="hi-sub">Sistem resmi UPA Bahasa<br>Politeknik Manufaktur Bandung</div>
        </div>
    </div>
</section>

{{-- FITUR UNGGULAN --}}
<section class="features-bg">
    <div class="section-header" style="text-align:center">
        <div class="section-label">Mengapa Kami</div>
        <div class="section-title">Tentang Platform TOEFL</div>
        <div class="section-desc" style="margin:0 auto">
            TOEFL adalah tes kemampuan bahasa Inggris yang diakui secara internasional.
            Dengan platform kami, Anda bisa belajar dan berlatih TOEFL dengan mudah.
        </div>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon fi-blue"><i class="fas fa-headphones-alt"></i></div>
            <div class="feature-title">Mudah Digunakan</div>
            <div class="feature-desc">Antarmuka sederhana dan ramah pengguna. Fokus pada belajar, bukan pada cara menggunakan sistem.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon fi-orange"><i class="fas fa-random"></i></div>
            <div class="feature-title">Soal Acak Fisher-Yates</div>
            <div class="feature-desc">Soal diacak secara adil untuk setiap peserta Tes Full menggunakan algoritma Fisher-Yates Shuffle.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon fi-green"><i class="fas fa-layer-group"></i></div>
            <div class="feature-title">Sistem Terstruktur</div>
            <div class="feature-desc">Materi dan tes terstruktur mengikuti standar TOEFL ITP resmi: Listening, Structure, Reading.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon fi-purple"><i class="fas fa-shield-alt"></i></div>
            <div class="feature-title">Anti-Kecurangan</div>
            <div class="feature-desc">Sistem deteksi perpindahan tab, screenshot, dan penggunaan 2 layar selama tes berlangsung.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon fi-teal"><i class="fas fa-chart-line"></i></div>
            <div class="feature-title">Grafik Progress</div>
            <div class="feature-desc">Pantau perkembangan skor dari waktu ke waktu. Lihat area yang perlu ditingkatkan.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon fi-red"><i class="fas fa-certificate"></i></div>
            <div class="feature-title">Sertifikat Resmi</div>
            <div class="feature-desc">Hasil tes dapat dicetak sebagai dokumen resmi yang dapat diverifikasi dengan nomor pendaftaran.</div>
        </div>
    </div>
</section>

{{-- MATERI --}}
<section id="materi">
    <div class="section-header">
        <div class="section-label">Belajar</div>
        <div class="section-title">Materi Pembelajaran</div>
        <div class="section-desc">Pelajari tiga komponen utama TOEFL ITP dengan materi yang disusun oleh tim UPA Bahasa.</div>
    </div>
    <div class="materi-grid">
        <a href="/login" class="materi-card">
            <div class="mc-icon fi-orange"><i class="fas fa-headphones-alt"></i></div>
            <div>
                <div class="mc-title">Listening</div>
                <div class="mc-desc">Tingkatkan kemampuan memahami percakapan dan kuliah dalam bahasa Inggris. Dilengkapi audio interaktif.</div>
            </div>
        </a>
        <a href="/login" class="materi-card">
            <div class="mc-icon fi-blue"><i class="fas fa-book-open"></i></div>
            <div>
                <div class="mc-title">Reading</div>
                <div class="mc-desc">Pahami teks akademik berbahasa Inggris. Latihan membaca cepat dan memahami konteks bacaan.</div>
            </div>
        </a>
        <a href="/login" class="materi-card">
            <div class="mc-icon fi-purple"><i class="fas fa-pen-nib"></i></div>
            <div>
                <div class="mc-title">Structure</div>
                <div class="mc-desc">Kuasai tata bahasa dan ekspresi tertulis dalam bahasa Inggris sesuai standar TOEFL ITP.</div>
            </div>
        </a>
    </div>
    <div class="btn-center">
        <a href="/login" class="btn-outlined-blue">
            <i class="fas fa-book-open"></i> Lihat Semua Materi
        </a>
    </div>
</section>

{{-- TES --}}
<section id="tes" class="tes-bg" id="latihan">
    <div class="section-header">
        <div class="section-label">Evaluasi</div>
        <div class="section-title">Latihan &amp; Tes TOEFL</div>
        <div class="section-desc">Uji kemampuan Anda dengan tiga pilihan tes sesuai kebutuhan dan tingkat persiapan.</div>
    </div>
    <div class="tes-grid">
        <div class="tes-card">
            <div class="tc-icon fi-blue"><i class="fas fa-bolt"></i></div>
            <div>
                <div class="tc-title">Mini Test</div>
                <div class="tc-desc">Tes singkat 30 soal, langsung mulai tanpa daftar. Cocok untuk latihan harian.</div>
            </div>
        </div>
        <div class="tes-card">
            <div class="tc-icon fi-orange"><i class="fas fa-flask"></i></div>
            <div>
                <div class="tc-title">Simulasi</div>
                <div class="tc-desc">3 section dengan timer seperti tes asli. Hasil berupa estimasi skor TOEFL ITP.</div>
            </div>
        </div>
        <div class="tes-card">
            <div class="tc-icon fi-purple"><i class="fas fa-graduation-cap"></i></div>
            <div>
                <div class="tc-title">Full Test</div>
                <div class="tc-desc">Tes resmi TOEFL ITP dengan pengawasan. Wajib daftar dan verifikasi warga Polman.</div>
            </div>
        </div>
    </div>
    <p class="tes-note">
        <i class="fas fa-info-circle" style="color:var(--blue)"></i>
        Tes hanya untuk pengguna terverifikasi.
        <a href="#jadwal">Lihat Jadwal <i class="fas fa-chevron-right" style="font-size:10px"></i></a>
    </p>
</section>

{{-- JADWAL --}}
<section id="jadwal">
    <div class="section-header">
        <div class="section-label">Agenda</div>
        <div class="section-title">Jadwal Tes Full Mendatang</div>
        <div class="section-desc">Daftar sesi Tes Full TOEFL ITP yang dibuka untuk umum warga Polman.</div>
    </div>
    @if($sesiMendatang->count() > 0)
    <div class="jadwal-grid">
        @foreach($sesiMendatang as $s)
        <div class="jadwal-card">
            <div class="jc-date">
                <i class="fas fa-calendar"></i>
                {{ \Carbon\Carbon::parse($s->waktu_mulai)->format('d F Y') }}
            </div>
            <div class="jc-title">{{ $s->judul }}</div>
            <div class="jc-meta">
                <i class="fas fa-clock" style="color:var(--muted)"></i>
                {{ \Carbon\Carbon::parse($s->waktu_mulai)->format('H:i') }} –
                {{ \Carbon\Carbon::parse($s->waktu_selesai)->format('H:i') }} WIB
                &nbsp;·&nbsp;
                <i class="fas fa-users"></i>
                Kuota: {{ $s->peserta_terdaftar }}/{{ $s->kuota_peserta }}
            </div>
            @if($s->peserta_terdaftar >= $s->kuota_peserta)
                <span class="jc-badge badge-red">Penuh</span>
            @else
                <span class="jc-badge badge-green">Tersedia</span>
            @endif
            <div style="margin-top:14px">
                <a href="/login" style="display:inline-flex;align-items:center;gap:6px;
                    font-size:13px;font-weight:600;color:var(--blue);text-decoration:none">
                    Daftar Sekarang <i class="fas fa-arrow-right" style="font-size:11px"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div style="text-align:center;padding:48px;background:var(--bg);border-radius:14px;border:1px solid var(--border)">
        <i class="fas fa-calendar-times" style="font-size:36px;color:var(--muted);margin-bottom:12px;display:block"></i>
        <p style="color:var(--muted);font-size:14px">Belum ada jadwal tes yang dibuka. Pantau pengumuman terbaru.</p>
    </div>
    @endif
</section>

{{-- PENGUMUMAN --}}
@if($pengumuman->count() > 0)
<section style="background:var(--bg)">
    <div class="section-header">
        <div class="section-label">Informasi</div>
        <div class="section-title">Pengumuman Terbaru</div>
    </div>
    <div class="pengumuman-grid">
        @foreach($pengumuman as $p)
        <div class="pengumuman-item">
            <div class="pi-dot" style="{{ $p->is_pinned ? 'background:var(--orange)':'' }}"></div>
            <div>
                <div class="pi-title">
                    @if($p->is_pinned) 📌 @endif
                    {{ $p->judul }}
                </div>
                <div class="pi-desc">{{ Str::limit($p->konten, 120) }}</div>
                <div class="pi-time">{{ $p->published_at?->diffForHumans() }}</div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- CEK SKOR --}}
<section class="cek-skor-bg">
    <div class="section-label" style="color:#93c5fd">Verifikasi</div>
    <div class="section-title">Cek Skor TOEFL Anda</div>
    <div class="section-desc">
        Masukkan nomor pendaftaran untuk melihat hasil tes TOEFL ITP Anda.
    </div>
    <form class="cek-form" action="/cari-skor" method="POST">
        @csrf
        <input type="text" name="nomor_pendaftaran" class="cek-input"
            placeholder="Masukkan Nomor Pendaftaran (cth: TF-2026-0001)" required>
        <button type="submit" class="btn-cek">
            <i class="fas fa-search"></i> Cari Skor
        </button>
    </form>
    <p style="margin-top:14px;font-size:12.5px;color:rgba(255,255,255,.6)">
        Nomor pendaftaran dikirim via email saat pendaftaran dikonfirmasi admin.
    </p>
</section>

{{-- FOOTER --}}
<footer>
    <div class="footer-top">
        <div class="footer-brand">
            <div class="footer-logo">
                <div class="footer-logo-icon">T</div>
                <span class="footer-logo-text">TOEFL Prep Polman</span>
            </div>
            <p class="footer-desc">
                Sistem pembelajaran dan tes TOEFL ITP internal
                Politeknik Manufaktur Bandung. Dikelola oleh UPA Bahasa.
            </p>
        </div>
        <div class="footer-links">
            <h4>Navigasi</h4>
            <ul>
                <li><a href="#materi">Materi Pembelajaran</a></li>
                <li><a href="#tes">Latihan & Tes</a></li>
                <li><a href="#jadwal">Jadwal Tes Full</a></li>
                <li><a href="/cari-skor">Cek Skor</a></li>
            </ul>
        </div>
        <div class="footer-links">
            <h4>Akun</h4>
            <ul>
                <li><a href="/login">Login</a></li>
                <li><a href="/register">Daftar Akun</a></li>
                <li><a href="/dashboard">Dashboard</a></li>
            </ul>
        </div>
        <div class="footer-links">
            <h4>Kontak</h4>
            <ul>
                <li><a href="#">UPA Bahasa Polman</a></li>
                <li><a href="#">Politeknik Manufaktur Bandung</a></li>
                <li><a href="#">Bandung, Jawa Barat</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© {{ date('Y') }} TOEFL ITP Polman — UPA Bahasa. All rights reserved.</span>
        <span>Sistem Pembelajaran & Tes Internal</span>
    </div>
</footer>

</body>
</html>
