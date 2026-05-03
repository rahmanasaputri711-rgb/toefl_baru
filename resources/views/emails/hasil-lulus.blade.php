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
  <div class="header" style="background:linear-gradient(135deg,#16a34a,#22c55e)">
    <div class="logo" style="color:#bbf7d0">TOEFL ITP — UPA Bahasa Polman</div>
    <h1 style="color:#fff">🎉 Selamat! Kamu Lulus!</h1>
    <p style="color:#bbf7d0">Hasil Tes TOEFL ITP</p>
  </div>

  <div class="body">
    <div class="greeting">Halo, {{ $percobaan->user->name }}! 🎊</div>
    <p class="text">
      Selamat! Kamu telah berhasil menyelesaikan dan
      <strong style="color:#16a34a">LULUS</strong>
      Tes TOEFL ITP dengan skor yang memenuhi syarat.
    </p>

    {{-- Skor Total --}}
    <div class="nomor-box" style="background:#f0fdf4;border:2px solid #22c55e">
      <div class="lbl" style="color:#16a34a">Skor TOEFL ITP Kamu</div>
      <div class="val" style="color:#15803d;font-size:56px">
        {{ $percobaan->skor_total }}
      </div>
      <div style="margin-top:8px">
        <span style="background:#dcfce7;color:#16a34a;padding:4px 14px;
          border-radius:20px;font-size:13px;font-weight:700">
          ✓ LULUS (Syarat ≥ 500)
        </span>
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

    {{-- Info tes --}}
    <div class="info-box">
      @foreach([
        ['Sesi Tes',   $percobaan->sesiTes?->judul ?? '-'],
        ['Tanggal Tes',\Carbon\Carbon::parse($percobaan->waktu_selesai)->format('d F Y')],
        ['Benar',      $percobaan->jumlah_benar.' soal'],
        ['Salah',      $percobaan->jumlah_salah.' soal'],
        ['Percobaan',  'ke-'.$percobaan->tes_ke],
      ] as [$lbl,$val])
      <div class="info-row">
        <span class="lbl">{{ $lbl }}</span>
        <span class="val">{{ $val }}</span>
      </div>
      @endforeach
    </div>

    <div class="btn-wrap">
      <a href="{{ url('/hasil/'.$percobaan->id) }}" class="btn"
         style="background:#16a34a;color:#fff;margin-right:10px">
        📊 Lihat Detail & Cetak Sertifikat
      </a>
    </div>

    <p class="text" style="text-align:center;margin-top:4px">
      Sertifikat digital tersedia di sistem dan bisa kamu unduh kapan saja.
    </p>
  </div>

  <div class="footer">
    Email ini dikirim otomatis oleh sistem TOEFL ITP Polman Babel.<br>
    Selamat atas pencapaianmu! — Tim UPA Bahasa 🎓
  </div>
</div>
</body>
</html>
