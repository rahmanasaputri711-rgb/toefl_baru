<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat TOEFL ITP — {{ $pendaftaran?->nomor_pendaftaran ?? 'Hasil Tes' }}</title>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display:none !important; }
            @page { margin: 1.5cm; }
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Times New Roman',serif; background:#fff; color:#1a1a1a;
            padding:30px; font-size:14px; max-width:800px; margin:0 auto; }

        /* Print bar */
        .print-bar { background:#003366; color:#fff; padding:12px 20px; border-radius:10px;
            margin-bottom:24px; display:flex; align-items:center; justify-content:space-between; }
        .btn-print { background:rgba(255,255,255,.15); color:#fff; border:1px solid rgba(255,255,255,.3);
            padding:8px 20px; border-radius:7px; cursor:pointer; font-size:13px;
            font-weight:700; font-family:inherit; transition:all .15s; }
        .btn-print:hover { background:rgba(255,255,255,.25); }

        /* Header */
        .cert-header { text-align:center; padding-bottom:20px; margin-bottom:20px;
            border-bottom:3px double #003366; }
        .cert-header .inst { font-size:11px; text-transform:uppercase; letter-spacing:2px;
            color:#666; margin-bottom:6px; }
        .cert-header h1 { font-size:20px; font-weight:900; color:#003366; margin-bottom:4px; }
        .cert-header .sub { font-size:12px; color:#666; }

        /* Percobaan badge */
        .attempt-badge { display:inline-block; background:#f0f4ff; border:1px solid #c3d0f0;
            border-radius:20px; padding:3px 14px; font-size:11px; font-weight:700;
            color:#2952a3; margin-top:8px; }

        /* Section title */
        .sec-title { font-size:10px; font-weight:700; text-transform:uppercase;
            letter-spacing:1.5px; color:#888; margin:16px 0 8px; }

        /* Info grid */
        .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px; }
        .info-item .lbl { font-size:10.5px; color:#888; margin-bottom:2px; }
        .info-item .val { font-size:14px; font-weight:700; border-bottom:1px solid #eee;
            padding-bottom:4px; }

        /* Skor box */
        .skor-box { border:2px solid #003366; border-radius:10px; padding:24px 20px;
            margin-bottom:16px; text-align:center; background:#f8faff; }
        .skor-total { font-size:56px; font-weight:900; color:#003366; line-height:1; }
        .skor-lbl { font-size:11px; color:#888; text-transform:uppercase; letter-spacing:1px; margin-top:4px; }
        .pass-badge { display:inline-block; margin-top:10px; padding:4px 16px; border-radius:20px;
            font-size:12px; font-weight:700; }
        .pass-badge.lulus { background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; }
        .pass-badge.tidak { background:#fee2e2; color:#dc2626; border:1px solid #fecdd3; }

        /* Section scores */
        .sec-scores { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-top:16px; }
        .sec-card { border:1px solid #ddd; border-radius:8px; padding:14px; text-align:center; }
        .sec-card .v { font-size:28px; font-weight:800; color:#003366; }
        .sec-card .l { font-size:10.5px; color:#888; margin-top:3px; }

        /* Stats grid */
        .stats-row { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:16px; }
        .stat-cell { text-align:center; border:1px solid #eee; border-radius:7px; padding:10px; }
        .stat-cell .v { font-size:18px; font-weight:800; color:#003366; }
        .stat-cell .l { font-size:10px; color:#888; margin-top:2px; }

        /* Attempt history */
        .attempt-table { width:100%; border-collapse:collapse; margin-bottom:16px; font-size:12px; }
        .attempt-table th { background:#f1f5f9; padding:7px 10px; text-align:left;
            font-weight:700; color:#444; border-bottom:2px solid #ddd; }
        .attempt-table td { padding:7px 10px; border-bottom:1px solid #eee; }
        .attempt-table tr.current td { background:#f0f4ff; font-weight:700; }

        /* Note */
        .note-box { font-size:11.5px; color:#888; padding:10px 14px;
            border:1px solid #e2e8f0; border-radius:7px; margin-bottom:16px; line-height:1.6; }

        /* Footer */
        .cert-footer { border-top:2px solid #003366; padding-top:16px; margin-top:16px;
            display:flex; justify-content:space-between; align-items:flex-end; }
        .cert-footer .meta { font-size:11px; color:#888; line-height:1.8; }
        .ttd { text-align:center; }
        .ttd .garis { width:160px; border-bottom:1px solid #333; margin:48px auto 5px; }
        .ttd .nama { font-weight:700; font-size:13px; }
        .ttd .jabatan { font-size:10.5px; color:#888; }
    </style>
</head>
<body>

{{-- Print bar --}}
<div class="print-bar no-print">
    <div>
        <strong>Sertifikat Hasil TOEFL ITP</strong>
        <span style="opacity:.7;font-size:12px;margin-left:10px">
            {{ $pendaftaran?->nomor_pendaftaran ?? 'Internal' }}
        </span>
    </div>
    <div style="display:flex;gap:8px">
        <button class="btn-print" onclick="window.print()">
            🖨️ Cetak / Simpan PDF
        </button>
        <button class="btn-print" onclick="history.back()">← Kembali</button>
    </div>
</div>

{{-- SERTIFIKAT --}}
<div class="cert-header">
    <div class="inst">Politeknik Manufaktur Bandung — UPA Bahasa</div>
    <h1>SERTIFIKAT HASIL TES TOEFL ITP</h1>
    <div class="sub">Test of English as a Foreign Language — Institutional Testing Program</div>
    @if($tesKe > 1)
    <div class="attempt-badge">
        <i>Percobaan ke-{{ $tesKe }}</i>
    </div>
    @endif
</div>

<div class="sec-title">Data Peserta</div>
<div class="info-grid">
    <div class="info-item">
        <div class="lbl">Nama Lengkap</div>
        <div class="val">{{ $user->name }}</div>
    </div>
    <div class="info-item">
        <div class="lbl">NIM / NIP</div>
        <div class="val">{{ $pendaftaran?->nim_nip ?? '—' }}</div>
    </div>
    <div class="info-item">
        <div class="lbl">Program Studi</div>
        <div class="val">{{ $pendaftaran?->program_studi ?? '—' }}</div>
    </div>
    <div class="info-item">
        <div class="lbl">Status</div>
        <div class="val">{{ ucfirst($pendaftaran?->status_polman ?? '—') }}</div>
    </div>
    <div class="info-item">
        <div class="lbl">Nomor Pendaftaran</div>
        <div class="val" style="font-family:monospace;color:#003366">
            {{ $pendaftaran?->nomor_pendaftaran ?? '—' }}
        </div>
    </div>
    <div class="info-item">
        <div class="lbl">Tanggal Tes</div>
        <div class="val">{{ \Carbon\Carbon::parse($percobaan->waktu_selesai)->format('d F Y') }}</div>
    </div>
</div>

{{-- SKOR UTAMA --}}
<div class="skor-box">
    <div class="skor-lbl">Skor Total TOEFL ITP</div>
    <div class="skor-total">{{ $percobaan->skor_total }}</div>
    <div>
        @if($percobaan->skor_total >= 500)
        <span class="pass-badge lulus">✓ LULUS (Skor ≥ 500)</span>
        @else
        <span class="pass-badge tidak">Skor di bawah 500</span>
        @endif
    </div>

    <div class="sec-scores">
        <div class="sec-card">
            <div class="v">{{ $percobaan->skor_listening }}</div>
            <div class="l">Section 1: Listening Comprehension</div>
        </div>
        <div class="sec-card">
            <div class="v">{{ $percobaan->skor_structure }}</div>
            <div class="l">Section 2: Structure & Written Expression</div>
        </div>
        <div class="sec-card">
            <div class="v">{{ $percobaan->skor_reading }}</div>
            <div class="l">Section 3: Reading Comprehension</div>
        </div>
    </div>
</div>

{{-- Statistik --}}
<div class="sec-title">Statistik Pengerjaan</div>
<div class="stats-row">
    <div class="stat-cell">
        <div class="v" style="color:#16a34a">{{ $percobaan->jumlah_benar }}</div>
        <div class="l">Jawaban Benar</div>
    </div>
    <div class="stat-cell">
        <div class="v" style="color:#dc2626">{{ $percobaan->jumlah_salah }}</div>
        <div class="l">Jawaban Salah</div>
    </div>
    <div class="stat-cell">
        <div class="v" style="color:#94a3b8">{{ $percobaan->jumlah_tidak_dijawab }}</div>
        <div class="l">Tidak Dijawab</div>
    </div>
</div>

{{-- Riwayat semua percobaan user ini --}}
@php
    $riwayat = \App\Models\PercobaanTes::where('user_id', $user->id)
        ->where('status','selesai')
        ->orderBy('created_at')
        ->get();
@endphp
@if($riwayat->count() > 1)
<div class="sec-title">Riwayat Semua Percobaan</div>
<table class="attempt-table">
    <thead>
        <tr>
            <th>Ke-</th>
            <th>Tanggal</th>
            <th>Listening</th>
            <th>Structure</th>
            <th>Reading</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($riwayat as $r)
        <tr class="{{ $r->id === $percobaan->id ? 'current' : '' }}">
            <td>{{ $r->tes_ke }}
                @if($r->id === $percobaan->id) <strong>(ini)</strong> @endif
            </td>
            <td>{{ \Carbon\Carbon::parse($r->waktu_selesai)->format('d M Y') }}</td>
            <td>{{ $r->skor_listening }}</td>
            <td>{{ $r->skor_structure }}</td>
            <td>{{ $r->skor_reading }}</td>
            <td><strong>{{ $r->skor_total }}</strong></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<div class="note-box">
    Skor ini merupakan hasil tes TOEFL ITP internal Polman yang diselenggarakan oleh UPA Bahasa.
    Dokumen ini dapat diverifikasi dengan nomor pendaftaran di atas.
    Dicetak secara digital oleh sistem pada {{ now()->format('d F Y, H:i') }} WIB.
</div>

<div class="cert-footer">
    <div class="meta">
        <div>Dicetak: {{ now()->format('d F Y, H:i') }} WIB</div>
        @if($pendaftaran?->nomor_pendaftaran)
        <div>No. Pendaftaran: {{ $pendaftaran->nomor_pendaftaran }}</div>
        @endif
        <div>Sesi: {{ $percobaan->sesiTes?->judul ?? '-' }}</div>
    </div>
    <div class="ttd">
        <div>Mengetahui,</div>
        <div class="garis"></div>
        <div class="nama">Kepala UPA Bahasa</div>
        <div class="jabatan">Politeknik Manufaktur Bandung</div>
    </div>
</div>

</body>
</html>
