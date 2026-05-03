@extends('layouts.user')
@section('title','Praktik Latihan')
@section('page-title','Praktik Latihan Soal')
@section('breadcrumb','Home / Praktik')
@section('content')
<div style="margin-bottom:24px">
    <p style="font-size:15px;color:var(--muted);line-height:1.6">
        Pilih kategori untuk memulai latihan. Soal ditampilkan satu per satu — Anda bisa navigasi maju mundur.
    </p>
</div>

<div class="grid-3" style="gap:20px;margin-bottom:28px">
    @foreach([
        ['listening','Listening','headphones-alt','#ea580c','#fff7ed','Latihan memahami percakapan & kuliah. Dilengkapi audio interaktif.'],
        ['structure','Structure','pen-nib','#d97706','#fffbeb','Latihan tata bahasa & ekspresi tertulis sesuai standar TOEFL ITP.'],
        ['reading','Reading','book-open','#1a56db','#eff6ff','Latihan membaca & memahami teks akademik berbahasa Inggris.'],
    ] as [$kat,$nama,$icon,$warna,$bg,$desc])
    <div class="card" style="border-top:4px solid {{ $warna }}">
        <div class="card-body" style="padding:28px;text-align:center">
            <div style="width:64px;height:64px;border-radius:16px;background:{{ $bg }};
                color:{{ $warna }};display:flex;align-items:center;justify-content:center;
                font-size:28px;margin:0 auto 16px">
                <i class="fas fa-{{ $icon }}"></i>
            </div>
            <h3 style="font-size:18px;font-weight:800;color:var(--navy);margin-bottom:6px">{{ $nama }}</h3>
            <p style="font-size:13px;color:var(--muted);margin-bottom:16px;line-height:1.6">{{ $desc }}</p>
            <div style="background:{{ $bg }};border-radius:8px;padding:10px;margin-bottom:18px;font-size:13px">
                <span style="font-weight:700;color:{{ $warna }}">{{ $stats[$kat] }}</span>
                <span style="color:var(--muted)"> soal tersedia</span>
            </div>
            @if($stats[$kat] > 0)
            <a href="{{ route('user.latihan.kerjakan', $kat) }}"
               class="btn btn-block" style="background:{{ $warna }};color:#fff;padding:11px;justify-content:center">
                <i class="fas fa-play-circle"></i> Mulai Latihan
            </a>
            @else
            <div style="padding:11px;border:1.5px solid var(--border);border-radius:9px;
                font-size:13.5px;color:var(--muted);text-align:center">Belum Ada Soal</div>
            @endif
        </div>
    </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header"><h3><i class="fas fa-lightbulb" style="color:var(--gold);margin-right:8px"></i>Tips Latihan</h3></div>
    <div class="card-body">
        <div class="grid-3">
            @foreach([
                ['fas fa-headphones','#ea580c','Listening','Dengarkan audio seksama. Saat latihan audio bisa diputar ulang. Saat Tes Full hanya 1x putar.'],
                ['fas fa-pen-nib','#d97706','Structure','Perhatikan subject-verb agreement dan identifikasi error pada bagian yang digaris bawahi.'],
                ['fas fa-book-open','#1a56db','Reading','Baca passage sebelum menjawab. Fokus pada main idea dan detail spesifik yang ditanyakan.'],
            ] as [$ico,$col,$tit,$tip])
            <div style="display:flex;gap:12px">
                <div style="width:36px;height:36px;border-radius:9px;flex-shrink:0;
                    background:{{ $col=='#1a56db'?'#eff6ff':($col=='#ea580c'?'#fff7ed':'#fffbeb') }};
                    color:{{ $col }};display:flex;align-items:center;justify-content:center;font-size:15px">
                    <i class="{{ $ico }}"></i>
                </div>
                <div>
                    <div style="font-size:13px;font-weight:700;color:var(--navy);margin-bottom:4px">{{ $tit }}</div>
                    <div style="font-size:12.5px;color:var(--muted);line-height:1.6">{{ $tip }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
