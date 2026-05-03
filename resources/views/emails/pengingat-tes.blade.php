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
  <div class="header" style="background:linear-gradient(135deg,#d97706,#f59e0b)">
    <div class="logo" style="color:#fef3c7">TOEFL ITP — UPA Bahasa Polman</div>
    <h1 style="color:#fff">⏰ Tes TOEFL Besok!</h1>
    <p style="color:#fef3c7">Pengingat H-1 Tes TOEFL ITP</p>
  </div>

  <div class="body">
    <div class="greeting">Halo, {{ $pendaftaran->user->name }}! 👋</div>
    <p class="text">
      Mengingatkan bahwa kamu terdaftar untuk mengikuti
      <strong>Tes Full TOEFL ITP</strong> yang akan dilaksanakan
      <strong style="color:#d97706">besok</strong>.
      Pastikan kamu sudah siap!
    </p>

    {{-- Nomor Peserta --}}
    <div class="nomor-box" style="background:#fffbeb;border:2px solid #f59e0b">
      <div class="lbl" style="color:#d97706">Nomor Peserta Kamu</div>
      <div class="val" style="color:#92400e">{{ $pendaftaran->nomor_pendaftaran }}</div>
      <div style="font-size:12px;color:#d97706;margin-top:5px">
        Tunjukkan nomor ini kepada pengawas
      </div>
    </div>

    {{-- Detail tes --}}
    <div class="info-box">
      @php
        $mulai   = \Carbon\Carbon::parse($pendaftaran->sesiTes->waktu_mulai);
        $selesai = \Carbon\Carbon::parse($pendaftaran->sesiTes->waktu_selesai);
      @endphp
      @foreach([
        ['Sesi',      $pendaftaran->sesiTes->judul],
        ['Hari',      $mulai->translatedFormat('l, d F Y')],
        ['Jam Mulai', $mulai->format('H:i').' WIB'],
        ['Jam Selesai',$selesai->format('H:i').' WIB'],
        ['Durasi',    $pendaftaran->sesiTes->durasi_menit.' menit'],
        ['Tempat',    'Ruang UPA Bahasa, Gedung Utama Polman'],
      ] as [$lbl,$val])
      <div class="info-row">
        <span class="lbl">{{ $lbl }}</span>
        <span class="val">{{ $val }}</span>
      </div>
      @endforeach
    </div>

    {{-- Tips persiapan --}}
    <div class="tips-list">
      <strong style="color:#166534;display:block;margin-bottom:8px">
        ✅ Checklist Persiapan:
      </strong>
      <ul style="list-style:none;padding:0">
        <li>✔ Hadir <strong>15 menit sebelum</strong> tes dimulai</li>
        <li>✔ Bawa <strong>kartu identitas</strong> (KTM/KTP)</li>
        <li>✔ Pastikan <strong>laptop/HP terisi penuh</strong> (jika tes online)</li>
        <li>✔ <strong>Jangan buka tab lain</strong> saat tes berlangsung</li>
        <li>✔ Dilarang membawa catatan atau alat bantu apapun</li>
        <li>✔ Pastikan <strong>koneksi internet stabil</strong></li>
      </ul>
    </div>

    {{-- Aturan penting --}}
    <div class="alert-box" style="background:#fef3c7;border-left:4px solid #f59e0b;color:#92400e">
      <strong>⚠️ Peraturan Tes:</strong><br>
      Pindah tab = pelanggaran. 3× pelanggaran = tes otomatis dikumpulkan.
      Kecurangan akan mempengaruhi catatan akademik.
    </div>

    <div class="btn-wrap">
      <a href="{{ url('/pendaftaran/status') }}" class="btn"
         style="background:#1d4ed8;color:#fff">
        📋 Lihat Detail Pendaftaran
      </a>
    </div>

    <p style="font-size:13px;color:#94a3b8;text-align:center;margin-top:10px">
      Semangat! Tim UPA Bahasa mendoakan yang terbaik untuk tesmu. 🎓
    </p>
  </div>

  <div class="footer">
    Email ini dikirim otomatis H-1 sebelum jadwal tes.<br>
    Hubungi UPA Bahasa: upa.bahasa@polman-babel.ac.id<br>
    Jangan balas email ini langsung.
  </div>
</div>
</body>
</html>
