@extends('layouts.user')
@section('title','Detail Hasil Tes')
@section('page-title','Detail Hasil Tes')
@section('breadcrumb','Home / Riwayat / Detail')

@section('content')
<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start">

    {{-- ── KIRI: Skor + Review ── --}}
    <div>
        {{-- Skor Card --}}
        <div class="card" style="margin-bottom:18px">
            <div class="card-body" style="padding:28px">
                <div style="text-align:center;margin-bottom:24px">
                    <div style="font-size:11px;color:var(--muted);text-transform:uppercase;
                        letter-spacing:1.2px;margin-bottom:6px">
                        Skor TOEFL ITP
                    </div>
                    <div style="font-size:68px;font-weight:900;line-height:1;
                        color:{{ $percobaan->skor_total >= 500 ? 'var(--green)' : ($percobaan->skor_total >= 400 ? 'var(--gold)' : 'var(--red)') }}">
                        {{ $percobaan->skor_total }}
                    </div>
                    <div style="font-size:13px;color:var(--muted);margin-top:6px">
                        {{ $percobaan->sesiTes->judul ?? '' }}
                        &nbsp;·&nbsp;
                        {{ \Carbon\Carbon::parse($percobaan->waktu_selesai)->format('d M Y, H:i') }}
                    </div>
                    @if($percobaan->tes_ke > 1)
                    <div style="margin-top:8px">
                        <span style="display:inline-block;padding:4px 14px;border-radius:20px;
                            font-size:12px;font-weight:700;
                            background:rgba(217,119,6,.12);color:var(--gold);border:1px solid rgba(217,119,6,.25)">
                            <i class="fas fa-redo" style="font-size:10px"></i>
                            Percobaan ke-{{ $percobaan->tes_ke }}
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Skor per section --}}
                <div class="grid-3" style="margin-bottom:20px">
                    @foreach([
                        ['Listening', $percobaan->skor_listening, '#fdba74', '#fff7ed'],
                        ['Structure', $percobaan->skor_structure, '#fde68a', '#fffbeb'],
                        ['Reading',   $percobaan->skor_reading,   '#93c5fd', '#eff6ff'],
                    ] as [$lbl,$sk,$clr,$bg])
                    <div style="background:{{ $bg }};border-radius:12px;padding:16px;text-align:center">
                        <div style="font-size:28px;font-weight:800;color:{{ $clr }}">{{ $sk }}</div>
                        <div style="font-size:11.5px;color:var(--muted);margin-top:4px">{{ $lbl }}</div>
                    </div>
                    @endforeach
                </div>

                {{-- Badge lulus --}}
                @if($percobaan->skor_total >= 500)
                <div style="text-align:center;padding:12px;background:rgba(16,185,129,.08);
                    border:1px solid rgba(16,185,129,.25);border-radius:10px;margin-bottom:16px">
                    <i class="fas fa-check-circle" style="color:var(--green);margin-right:6px"></i>
                    <span style="font-size:14px;font-weight:700;color:var(--green)">
                        Selamat! Skor ≥ 500 (Lulus)
                    </span>
                </div>
                @endif

                <div style="display:flex;gap:10px;justify-content:center">
                    <a href="{{ route('user.hasil.index') }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-arrow-left"></i> Riwayat
                    </a>
                    <a href="{{ route('user.hasil.cetak', $percobaan->id) }}"
                        class="btn btn-primary btn-sm" target="_blank">
                        <i class="fas fa-print"></i> Cetak / Unduh PDF
                    </a>
                </div>
            </div>
        </div>

        {{-- Review Jawaban --}}
        @if($jawaban->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3>Review Jawaban</h3>
                <div style="display:flex;gap:7px">
                    <span class="badge badge-green">
                        <i class="fas fa-check" style="font-size:9px"></i>
                        {{ $jawaban->where('is_benar',1)->count() }} Benar
                    </span>
                    <span class="badge badge-red">
                        <i class="fas fa-times" style="font-size:9px"></i>
                        {{ $jawaban->where('is_benar',0)->count() }} Salah
                    </span>
                    @if($jawaban->whereNull('jawaban_dipilih')->count() > 0)
                    <span class="badge badge-gray">
                        {{ $jawaban->whereNull('jawaban_dipilih')->count() }} Tidak Dijawab
                    </span>
                    @endif
                </div>
            </div>

            @php $currentKat = null; @endphp
            @foreach($jawaban->sortBy(fn($j) => ['listening'=>1,'structure'=>2,'reading'=>3][$j->soal?->kategori ?? 'reading'])->values() as $i => $j)

            @if($j->soal?->kategori !== $currentKat)
            @php $currentKat = $j->soal?->kategori; @endphp
            <div style="padding:10px 20px;background:var(--bg);border-bottom:1px solid var(--border);
                font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;
                color:{{ $currentKat==='listening' ? '#fdba74' : ($currentKat==='structure' ? '#fde68a' : '#93c5fd') }}">
                <i class="fas fa-{{ $currentKat==='listening' ? 'headphones-alt' : ($currentKat==='structure' ? 'pen-nib' : 'book-open') }}"></i>
                Section: {{ ucfirst($currentKat) }}
            </div>
            @endif

            <div style="padding:14px 20px;border-bottom:1px solid var(--border);
                background:{{ $j->is_benar ? 'rgba(16,185,129,.025)' : ($j->jawaban_dipilih ? 'rgba(239,68,68,.025)' : 'rgba(100,116,139,.03)') }}">

                <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                    <div style="width:24px;height:24px;border-radius:6px;flex-shrink:0;
                        background:{{ $j->is_benar ? 'rgba(16,185,129,.15)' : ($j->jawaban_dipilih ? 'rgba(239,68,68,.15)' : 'rgba(100,116,139,.1)') }};
                        color:{{ $j->is_benar ? 'var(--green)' : ($j->jawaban_dipilih ? 'var(--red)' : 'var(--muted)') }};
                        display:flex;align-items:center;justify-content:center;font-size:11px">
                        <i class="fas fa-{{ $j->is_benar ? 'check' : ($j->jawaban_dipilih ? 'times' : 'minus') }}"></i>
                    </div>
                    <span style="font-size:12.5px;color:var(--muted)">No. {{ $i + 1 }}</span>
                    <span class="badge {{ $j->is_benar ? 'badge-green' : ($j->jawaban_dipilih ? 'badge-red' : 'badge-gray') }}" style="font-size:11px">
                        {{ $j->is_benar ? 'Benar' : ($j->jawaban_dipilih ? 'Salah' : 'Tidak Dijawab') }}
                    </span>
                </div>

                <div style="font-size:13.5px;font-weight:500;color:var(--text);margin-bottom:8px;line-height:1.6">
                    {{ $j->soal?->pertanyaan ?? '—' }}
                </div>

                {{-- Pilihan jawaban --}}
                <div style="display:flex;flex-direction:column;gap:5px;margin-bottom:8px">
                @foreach(['a','b','c','d'] as $opt)
                @php
                    $isJawabBenar = ($j->soal?->jawaban_benar === $opt);
                    $isDipilih    = ($j->jawaban_dipilih === $opt);
                    $bg = $isJawabBenar ? 'rgba(16,185,129,.12)' : ($isDipilih && !$isJawabBenar ? 'rgba(239,68,68,.1)' : 'transparent');
                    $bd = $isJawabBenar ? '#6ee7b7' : ($isDipilih && !$isJawabBenar ? '#fca5a5' : 'var(--border)');
                    $clr= $isJawabBenar ? 'var(--green)' : ($isDipilih && !$isJawabBenar ? 'var(--red)' : 'var(--muted)');
                @endphp
                <div style="display:flex;align-items:center;gap:8px;padding:7px 10px;
                    border-radius:7px;background:{{ $bg }};border:1px solid {{ $bd }}">
                    <span style="font-size:11px;font-weight:800;color:{{ $clr }};width:16px;flex-shrink:0">
                        {{ strtoupper($opt) }}
                    </span>
                    <span style="font-size:13px;color:{{ $isJawabBenar ? 'var(--green)' : ($isDipilih ? 'var(--red)' : 'var(--text)') }}">
                        {{ $j->soal?->{'pilihan_'.$opt} ?? '—' }}
                    </span>
                    @if($isJawabBenar)
                    <i class="fas fa-check-circle" style="color:var(--green);margin-left:auto;font-size:13px"></i>
                    @elseif($isDipilih)
                    <i class="fas fa-times-circle" style="color:var(--red);margin-left:auto;font-size:13px"></i>
                    @endif
                </div>
                @endforeach
                </div>

                {{-- Pembahasan (jika sesi mengijinkan) --}}
                @if($tampilPembahasan && $j->soal?->pembahasan)
                <div style="padding:10px 13px;background:rgba(99,102,241,.08);
                    border-left:3px solid #818cf8;border-radius:0 8px 8px 0;
                    font-size:13px;color:#a5b4fc;margin-top:6px;line-height:1.65">
                    <strong><i class="fas fa-lightbulb" style="font-size:11px"></i> Pembahasan:</strong>
                    {{ $j->soal->pembahasan }}
                </div>
                @endif

            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ── KANAN: Info + Evaluasi ── --}}
    <div style="display:flex;flex-direction:column;gap:14px;position:sticky;top:20px">

        {{-- Info Tes --}}
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-info-circle" style="color:var(--accent);margin-right:6px"></i>Info Tes</h3>
            </div>
            <div class="card-body" style="padding:16px">
                @php
                    $durasi = $percobaan->waktu_mulai && $percobaan->waktu_selesai
                        ? \Carbon\Carbon::parse($percobaan->waktu_mulai)
                            ->diffInMinutes($percobaan->waktu_selesai)
                        : null;
                    $rows = [
                        ['Sesi',         $percobaan->sesiTes?->judul ?? '-'],
                        ['Percobaan ke', 'ke-'.($percobaan->tes_ke ?? 1).' dari 3 kesempatan'],
                        ['Mulai',        $percobaan->waktu_mulai ? \Carbon\Carbon::parse($percobaan->waktu_mulai)->format('H:i, d M Y') : '-'],
                        ['Selesai',      $percobaan->waktu_selesai ? \Carbon\Carbon::parse($percobaan->waktu_selesai)->format('H:i, d M Y') : '-'],
                        ['Durasi',       $durasi ? $durasi . ' menit' : '-'],
                        ['Total Benar',  $percobaan->jumlah_benar . ' soal'],
                        ['Total Salah',  $percobaan->jumlah_salah . ' soal'],
                        ['Tidak Dijawab',$percobaan->jumlah_tidak_dijawab . ' soal'],
                    ];
                @endphp
                @foreach($rows as [$lbl,$val])
                <div style="display:flex;justify-content:space-between;padding:7px 0;
                    border-bottom:1px solid var(--border);font-size:13px">
                    <span style="color:var(--muted)">{{ $lbl }}</span>
                    <span style="font-weight:600;text-align:right;max-width:170px">{{ $val }}</span>
                </div>
                @endforeach

                {{-- Pelanggaran --}}
                @if($percobaan->jumlah_pelanggaran > 0)
                <div style="margin-top:10px;padding:10px 12px;background:rgba(239,68,68,.08);
                    border:1px solid rgba(239,68,68,.2);border-radius:8px;font-size:12.5px;color:var(--red)">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>{{ $percobaan->jumlah_pelanggaran }} pelanggaran</strong> terdeteksi selama tes.
                </div>
                @endif
            </div>
        </div>

        {{-- Evaluasi dari Admin --}}
        @if($evaluasi)
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-star" style="color:var(--gold);margin-right:6px"></i>Evaluasi Admin</h3>
            </div>
            <div class="card-body" style="padding:16px">
                <div style="font-weight:600;font-size:14px;margin-bottom:8px">{{ $evaluasi->judul }}</div>
                <div style="font-size:13px;color:var(--muted);line-height:1.7;margin-bottom:12px">
                    {{ $evaluasi->catatan }}
                </div>
                @if($evaluasi->rekomendasi)
                <div style="background:rgba(59,130,246,.07);border-left:3px solid var(--accent);
                    padding:10px 12px;border-radius:0 8px 8px 0;font-size:13px;color:#93c5fd">
                    <strong>Rekomendasi:</strong> {{ $evaluasi->rekomendasi }}
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Tombol aksi --}}
        <div style="display:flex;flex-direction:column;gap:8px">
            <a href="{{ route('user.hasil.cetak', $percobaan->id) }}"
                class="btn btn-primary btn-block" target="_blank" style="justify-content:center;padding:12px">
                <i class="fas fa-print"></i> Cetak / Unduh PDF
            </a>
            <a href="{{ route('user.hasil.index') }}"
                class="btn btn-outline btn-block" style="justify-content:center;padding:12px">
                <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
            </a>
        </div>
    </div>

</div>
@endsection
