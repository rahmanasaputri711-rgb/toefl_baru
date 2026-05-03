<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Skor TOEFL ITP — Polman</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family:'Plus Jakarta Sans',sans-serif; background:#0f1b2d; color:#e2e8f0; display:flex; align-items:center; justify-content:center; min-height:100vh; padding:20px; }
        .box { background:#162236; border:1px solid #243450; border-radius:16px; padding:40px; width:100%; max-width:480px; }
        h1 { font-size:22px; font-weight:800; margin-bottom:4px; }
        p { font-size:13px; color:#7f96b2; margin-bottom:28px; }
        label { display:block; font-size:11px; font-weight:700; color:#7f96b2; text-transform:uppercase; letter-spacing:.5px; margin-bottom:7px; }
        input { width:100%; background:#1e3048; border:1px solid #243450; border-radius:8px; padding:12px 14px; color:#e2e8f0; font-size:15px; font-family:inherit; outline:none; box-sizing:border-box; }
        input:focus { border-color:#3b82f6; }
        button { width:100%; background:#3b82f6; color:#fff; border:none; border-radius:8px; padding:12px; font-size:15px; font-weight:700; cursor:pointer; margin-top:14px; font-family:inherit; }
        button:hover { background:#1d4ed8; }
        .alert { padding:12px 16px; border-radius:8px; font-size:13px; margin-bottom:16px; }
        .alert-error { background:rgba(239,68,68,.12); border:1px solid rgba(239,68,68,.3); color:#fca5a5; }
        .result { background:#1e3048; border-radius:12px; padding:24px; margin-top:24px; text-align:center; }
        .skor-big { font-size:52px; font-weight:900; line-height:1; }
        .skor-row { display:flex; justify-content:space-around; margin:20px 0; }
        .skor-item .val { font-size:24px; font-weight:800; color:#3b82f6; }
        .skor-item .lbl { font-size:11px; color:#7f96b2; margin-top:3px; }
        a { color:#3b82f6; font-size:13px; }
    </style>
</head>
<body>
<div class="box">
    <h1>🎓 Cek Skor TOEFL ITP</h1>
    <p>Masukkan nomor pendaftaran Anda untuk melihat hasil tes.</p>

    @if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('public.cari-skor.post') }}">
        @csrf
        <label>Nomor Pendaftaran</label>
        <input type="text" name="nomor_pendaftaran" placeholder="cth: TF-2026-0042" required autofocus>
        <button type="submit">Cari Skor</button>
    </form>

    @isset($p)
    <div class="result">
        <div style="font-size:13px;color:#7f96b2;margin-bottom:4px">Hasil Tes untuk</div>
        <div style="font-size:17px;font-weight:700">{{ $p->user->name ?? '-' }}</div>
        <div style="font-size:12px;color:#7f96b2;margin-top:2px">{{ $p->nim_nip }} · {{ $p->program_studi }}</div>

        @if($percobaan)
        <div class="skor-row">
            <div class="skor-item"><div class="val">{{ $percobaan->skor_listening }}</div><div class="lbl">Listening</div></div>
            <div class="skor-item"><div class="val">{{ $percobaan->skor_structure }}</div><div class="lbl">Structure</div></div>
            <div class="skor-item"><div class="val">{{ $percobaan->skor_reading }}</div><div class="lbl">Reading</div></div>
        </div>
        <div class="skor-big" style="color:{{ $percobaan->skor_total>=500 ? '#10b981':($percobaan->skor_total>=400?'#f59e0b':'#ef4444') }}">
            {{ $percobaan->skor_total }}
        </div>
        <div style="font-size:12px;color:#7f96b2;margin-top:4px">Skor Total TOEFL ITP</div>
        @else
        <div style="padding:20px;color:#7f96b2;font-size:13px">Tes belum selesai atau belum tersedia.</div>
        @endif
    </div>
    @endisset

    <div style="text-align:center;margin-top:20px">
        <a href="/login">← Kembali ke Login</a>
    </div>
</div>
</body>
</html>
