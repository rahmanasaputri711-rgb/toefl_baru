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
  <div class="header" style="background:linear-gradient(135deg,#dc2626,#ef4444)">
    <div class="logo" style="color:#fecaca">TOEFL ITP — UPA Bahasa Polman</div>
    <h1 style="color:#fff">❌ Pendaftaran Ditolak</h1>
    <p style="color:#fecaca">Politeknik Manufaktur Negeri Bangka Belitung</p>
  </div>

  {{-- Body --}}
  <div class="body">
    <div class="greeting">Halo, {{ $pendaftaran->user->name }},</div>
    <p class="text">
      Mohon maaf, pendaftaran Tes TOEFL ITP Anda untuk sesi
      <strong>{{ $pendaftaran->sesiTes?->judul }}</strong>
      tidak dapat kami konfirmasi saat ini.
    </p>

    {{-- Alasan --}}
    <div class="alert-box" style="background:#fef2f2;border-left:4px solid #dc2626;color:#991b1b">
      <strong>⚠️ Alasan Penolakan:</strong><br>
      {{ $pendaftaran->catatan_admin ?? 'Berkas tidak memenuhi persyaratan.' }}
    </div>

    {{-- Info pendaftaran --}}
    <div class="info-box">
      @foreach([
        ['Nama',          $pendaftaran->user->name],
        ['NIM/NIP',       $pendaftaran->nim_nip],
        ['Sesi Diminta',  $pendaftaran->sesiTes?->judul ?? '-'],
        ['Tanggal Daftar',$pendaftaran->created_at->format('d F Y')],
      ] as [$lbl,$val])
      <div class="info-row">
        <span class="lbl">{{ $lbl }}</span>
        <span class="val">{{ $val }}</span>
      </div>
      @endforeach
    </div>

    {{-- Saran --}}
    <div class="tips-list">
      <strong style="color:#166534;display:block;margin-bottom:8px">
        💡 Yang Bisa Kamu Lakukan:
      </strong>
      <ul style="list-style:none;padding:0">
        <li>✔ Pastikan berkas KTM/KTP terlihat jelas dan tidak buram</li>
        <li>✔ Pastikan kamu terdaftar sebagai warga Polman aktif</li>
        <li>✔ Daftarkan kembali di jadwal sesi berikutnya</li>
        <li>✔ Hubungi UPA Bahasa jika ada pertanyaan lebih lanjut</li>
      </ul>
    </div>

    <div class="btn-wrap">
      <a href="{{ url('/tes/full') }}" class="btn"
         style="background:#1d4ed8;color:#fff">
        📅 Lihat Jadwal Tes Berikutnya
      </a>
    </div>
  </div>

  <div class="footer">
    Email ini dikirim otomatis oleh sistem TOEFL ITP Polman Babel.<br>
    Hubungi UPA Bahasa: upa.bahasa@polman-babel.ac.id<br>
    Jangan balas email ini langsung.
  </div>
</div>
</body>
</html>
