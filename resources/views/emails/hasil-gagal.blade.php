<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
@include('emails.partials.style')
</head>
<body>
<div class="wrap">

  {{-- Header --}}
  @php
    $sisaCoba = 3 - $percobaan->tes_ke;
    $headerBg = $sisaCoba > 0
      ? 'background:linear-gradient(135deg,#d97706,#f59e0b)'
      : 'background:linear-gradient(135deg,#dc2626,#ef4444)';
  @endphp
  <div class="header" style="{{ $headerBg }}">
    <div class="logo" style="color:rgba(255,255,255,.7)">TOEFL ITP — UPA Bahasa Polman</div>
    <h1 style="color:#fff">
      {{ $sisaCoba > 0 ? '💪 Jangan Menyerah!' : '📋 Hasil Tes TOEFL ITP' }}
    </h1>
    <p style="color:rgba(255,255,255,.75)">Hasil Tes — Percobaan ke-{{ $percobaan->tes_ke }}</p>
  </div>

  <div class="body">
    <div class="greeting">Halo, {{ $percobaan->user->name }},</div>
    <p class="text">
      Kamu telah menyelesaikan Tes TOEFL ITP percobaan ke-<strong>{{ $percobaan->tes_ke }}</strong>.
      Berikut hasil tesmu:
    </p>

    {{-- Skor Total --}}
    <div class="nomor-box" style="background:#fef2f2;border:2px solid #fca5a5">
      <div class="lbl" style="color:#dc2626">Skor TOEFL ITP Kamu</div>
      <div class="val" style="color:#dc2626;font-size:52px">
        {{ $percobaan->skor_total }}
      </div>
      <div style="margin-top:8px">
        <span style="background:#fee2e2;color:#dc2626;padding:4px 14px;
          border-radius:20px;font-size:13px;font-weight:700">
          ✗ Belum Lulus (Syarat ≥ 500)
        </span>
      </div>
      <div style="font-size:12px;color:#dc2626;margin-top:6px;opacity:.8">
        Kurang {{ 500 - $percobaan->skor_total }} poin dari syarat lulus
      </div>
    </div>

    {{-- Skor per section --}}
    <div style="margin-bottom:6px;font-size:13px;font-weight:700;color:#475569">
      Detail Skor Per Section:
    </div>
    <div class="skor-grid">
      @foreach([
        ['Listening', $percobaan->skor_listening, '#fff7ed','#ea580c','#fdba74'],
        ['Structure', $percobaan->skor_structure, '#fffbeb','#d97706','#fde68a'],
        ['Reading',   $percobaan->skor_reading,   '#eff6ff','#2563eb','#93c5fd'],
      ] as [$lbl,$skor,$bg,$dark,$light])
      <div class="skor-cell" style="background:{{ $bg }};border:1px solid {{ $light }}">
        <div class="s-lbl" style="color:{{ $dark }}">{{ $lbl }}</div>
        <div class="s-val" style="color:{{ $dark }}">{{ $skor }}</div>
      </div>
      @endforeach
    </div>

    <div class="divider"></div>

    @if($sisaCoba > 0)
    {{-- Masih ada kesempatan --}}
    <div style="text-align:center;margin:16px 0">
      <div style="font-size:28px;font-weight:900;color:#d97706">
        {{ $sisaCoba }}×
      </div>
      <div style="font-size:14px;color:#92400e;font-weight:600;margin-top:4px">
        Kesempatan Tes Tersisa
      </div>
      <div style="font-size:13px;color:#78716c;margin-top:4px">
        Kamu masih bisa mendaftar dan mengulang tes
      </div>
    </div>

    <div class="tips-list">
      <strong style="color:#166534;display:block;margin-bottom:8px">
        💡 Tips untuk Percobaan Berikutnya:
      </strong>
      <ul style="list-style:none;padding:0">
        @if($percobaan->skor_listening < 50)
        <li>🎧 Tingkatkan <strong>Listening</strong> — coba latihan audio setiap hari</li>
        @endif
        @if($percobaan->skor_structure < 50)
        <li>✏️ Perkuat <strong>Structure</strong> — pelajari grammar TOEFL</li>
        @endif
        @if($percobaan->skor_reading < 50)
        <li>📖 Latih <strong>Reading</strong> — biasakan baca teks bahasa Inggris</li>
        @endif
        <li>🧪 Gunakan fitur <strong>Simulasi</strong> di sistem untuk latihan</li>
        <li>⚡ Gunakan <strong>Mini Test</strong> untuk latihan cepat setiap hari</li>
      </ul>
    </div>

    <div class="btn-wrap">
      <a href="{{ url('/hasil/'.$percobaan->id) }}" class="btn"
         style="background:#64748b;color:#fff;margin-right:8px;font-size:13px">
        📊 Lihat Detail Hasil
      </a>
      <a href="{{ url('/tes/full') }}" class="btn"
         style="background:#d97706;color:#fff;font-size:13px">
        📅 Daftar Tes Berikutnya
      </a>
    </div>

    <div class="alert-box" style="background:#fef3c7;border-left:4px solid #f59e0b;color:#92400e">
      <strong>📌 Cara Daftar Tes Lagi:</strong><br>
      Login → Full Test → Pilih Jadwal → Daftar → Tunggu ACC Admin
    </div>

    @else
    {{-- Sudah 3× gagal --}}
    <div class="alert-box" style="background:#fef2f2;border-left:4px solid #dc2626;color:#991b1b">
      <strong>📌 Batas Percobaan Tercapai</strong><br>
      Kamu sudah mengikuti tes sebanyak 3 kali. Hubungi UPA Bahasa untuk konsultasi
      dan kemungkinan pengecualian lebih lanjut.
    </div>

    <div class="btn-wrap">
      <a href="{{ url('/hasil/'.$percobaan->id) }}" class="btn"
         style="background:#64748b;color:#fff">
        📊 Lihat Detail Hasil
      </a>
    </div>
    @endif
  </div>

  <div class="footer">
    Email ini dikirim otomatis oleh sistem TOEFL ITP Polman Babel.<br>
    Semangat terus! — Tim UPA Bahasa 💪<br>
    Hubungi: upa.bahasa@polman-babel.ac.id
  </div>
</div>
</body>
</html>
