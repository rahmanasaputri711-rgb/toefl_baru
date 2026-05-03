<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background:#f1f5f9; margin:0; padding:20px; color:#1e293b; }
        .wrap { max-width:560px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.08); }
        .header { background:linear-gradient(135deg,#1d4ed8,#3b82f6); padding:32px; text-align:center; }
        .header h1 { color:#fff; font-size:22px; margin:0; }
        .header p { color:#bfdbfe; font-size:13px; margin:8px 0 0; }
        .body { padding:28px 32px; }
        .greeting { font-size:16px; font-weight:600; margin-bottom:14px; }
        .info-box { background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:16px 20px; margin:18px 0; }
        .info-row { display:flex; justify-content:space-between; padding:7px 0; border-bottom:1px solid #e2e8f0; font-size:14px; }
        .info-row:last-child { border-bottom:none; }
        .info-row .lbl { color:#64748b; }
        .info-row .val { font-weight:600; }
        .nomor-box { background:#eff6ff; border:2px solid #3b82f6; border-radius:10px; padding:18px; text-align:center; margin:20px 0; }
        .nomor-box .label { font-size:12px; color:#3b82f6; font-weight:700; text-transform:uppercase; letter-spacing:1px; }
        .nomor-box .nomor { font-size:28px; font-weight:900; color:#1d4ed8; font-family:monospace; margin-top:4px; }
        .alert { background:#fef3c7; border-left:4px solid #f59e0b; padding:12px 16px; border-radius:0 8px 8px 0; font-size:13px; color:#92400e; margin:16px 0; }
        .footer { background:#f8fafc; padding:20px 32px; text-align:center; font-size:12px; color:#94a3b8; border-top:1px solid #e2e8f0; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <h1>🎓 TOEFL ITP Polman</h1>
        <p>UPA Bahasa — Politeknik Manufaktur Bandung</p>
    </div>
    <div class="body">
        <div class="greeting">Halo, {{ $pendaftaran->user->name }}!</div>
        <p style="font-size:14px;line-height:1.7;color:#475569">
            Selamat! Pendaftaran Tes TOEFL ITP Anda telah <strong style="color:#16a34a">dikonfirmasi</strong>
            oleh admin UPA Bahasa. Berikut detail pendaftaran Anda:
        </p>

        <div class="nomor-box">
            <div class="label">Nomor Pendaftaran Anda</div>
            <div class="nomor">{{ $pendaftaran->nomor_pendaftaran }}</div>
            <div style="font-size:12px;color:#3b82f6;margin-top:6px">Simpan nomor ini untuk cek hasil tes</div>
        </div>

        <div class="info-box">
            @foreach([
                ['Nama', $pendaftaran->user->name],
                ['NIM/NIP', $pendaftaran->nim_nip],
                ['Program Studi', $pendaftaran->program_studi],
                ['Sesi Tes', $pendaftaran->sesiTes?->judul ?? '-'],
                ['Tanggal Tes', $pendaftaran->sesiTes ? \Carbon\Carbon::parse($pendaftaran->sesiTes->waktu_mulai)->format('d F Y, H:i') : '-'],
                ['Durasi', ($pendaftaran->sesiTes?->durasi_menit ?? '-').' menit'],
            ] as [$lbl, $val])
            <div class="info-row">
                <span class="lbl">{{ $lbl }}</span>
                <span class="val">{{ $val }}</span>
            </div>
            @endforeach
        </div>

        <div class="alert">
            <strong>⚠️ Penting:</strong> Hadir tepat waktu. Dilarang berpindah tab, screenshot, atau menggunakan 2 layar saat tes berlangsung. Pelanggaran 3x akan membatalkan tes Anda.
        </div>

        <p style="font-size:13px;color:#64748b;line-height:1.6">
            Anda dapat login ke sistem untuk melihat detail jadwal dan mempersiapkan diri.
            Tombol "Mulai Tes" akan aktif saat admin mengaktifkan sesi tes.
        </p>
    </div>
    <div class="footer">
        Email ini dikirim otomatis oleh sistem TOEFL ITP Polman.<br>
        Jangan balas email ini. Hubungi UPA Bahasa untuk pertanyaan.
    </div>
</div>
</body>
</html>
