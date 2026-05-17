@extends('layouts.user')
@section('title','Praktik Latihan')
@section('page-title','Praktik Latihan Soal')
@section('breadcrumb','Home / Praktik')
@section('content')

<p style="font-size:14px;color:var(--muted);line-height:1.7;margin-bottom:22px;max-width:560px">
    Pilih kategori untuk memulai latihan. Soal ditampilkan satu per satu — kamu bisa navigasi maju mundur kapan saja.
</p>

{{-- ── CATEGORY CARDS ── --}}
<div class="grid-3" style="gap:16px;margin-bottom:24px">
    @foreach([
        ['listening', 'Listening',  'headphones-alt', '#2563EB', '#EFF6FF', '#DBEAFE', 'Latihan memahami percakapan & kuliah. Dilengkapi audio interaktif.'],
        ['structure', 'Structure',  'pen-nib',        '#7C3AED', '#F3E8FF', '#EDE9FE', 'Latihan tata bahasa & ekspresi tertulis sesuai standar TOEFL ITP.'],
        ['reading',   'Reading',    'book-open',      '#0891B2', '#ECFEFF', '#CFFAFE', 'Latihan membaca & memahami teks akademik berbahasa Inggris.'],
    ] as [$kat, $nama, $icon, $warna, $bg, $bgDeep, $desc])
    <div style="
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(15,23,42,.05);
        transition: all .2s;
        display: flex; flex-direction: column;"
        onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(15,23,42,.09)'"
        onmouseout="this.style.transform='';this.style.boxShadow='0 1px 4px rgba(15,23,42,.05)'">

        {{-- Top accent strip --}}
        <div style="height:4px;background:{{ $warna }};border-radius:0"></div>

        <div style="padding:24px 22px;flex:1;display:flex;flex-direction:column">
            {{-- Icon --}}
            <div style="
                width:52px; height:52px; border-radius:13px;
                background:{{ $bg }}; color:{{ $warna }};
                display:flex; align-items:center; justify-content:center;
                font-size:22px; margin-bottom:14px;
                border: 1px solid {{ $bgDeep }};">
                <i class="fas fa-{{ $icon }}"></i>
            </div>

            {{-- Title & desc --}}
            <div style="font-size:17px;font-weight:800;color:var(--navy);margin-bottom:6px">{{ $nama }}</div>
            <p style="font-size:13px;color:var(--muted);line-height:1.65;margin-bottom:16px;flex:1">{{ $desc }}</p>

            {{-- Soal count pill --}}
            <div style="
                display:inline-flex; align-items:center; gap:6px;
                background:{{ $bg }}; border:1px solid {{ $bgDeep }};
                border-radius:8px; padding:8px 12px;
                margin-bottom:16px; align-self:flex-start;">
                <i class="fas fa-list-ul" style="font-size:11px;color:{{ $warna }}"></i>
                <span style="font-size:13px;font-weight:700;color:{{ $warna }}">{{ $stats[$kat] }}</span>
                <span style="font-size:13px;color:var(--muted)">soal tersedia</span>
            </div>

            {{-- CTA --}}
            @if($stats[$kat] > 0)
            <a href="{{ route('user.latihan.kerjakan', $kat) }}"
               style="
                   display:flex; align-items:center; justify-content:center; gap:8px;
                   background:{{ $warna }}; color:#fff;
                   padding:11px; border-radius:9px;
                   font-size:13.5px; font-weight:700; text-decoration:none;
                   transition:all .15s;
                   box-shadow: 0 2px 8px {{ $warna }}40;"
               onmouseover="this.style.opacity='.9';this.style.transform='translateY(-1px)'"
               onmouseout="this.style.opacity='1';this.style.transform=''">
                <i class="fas fa-play-circle" style="font-size:14px"></i> Mulai Latihan
            </a>
            @else
            <div style="
                padding:11px; border:1.5px dashed var(--border);
                border-radius:9px; font-size:13px;
                color:var(--muted); text-align:center;">
                <i class="fas fa-inbox" style="margin-right:6px;opacity:.5"></i>Belum Ada Soal
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- ── TIPS CARD ── --}}
<div class="card">
    <div class="card-header">
        <h3>
            <i class="fas fa-lightbulb" style="color:var(--amber);margin-right:8px;font-size:13px"></i>
            Tips Latihan Efektif
        </h3>
    </div>
    <div class="card-body">
        <div class="grid-3" style="gap:20px">
            @foreach([
                ['fas fa-headphones-alt', '#2563EB', '#EFF6FF', 'Listening',
                 'Dengarkan audio seksama. Saat latihan audio bisa diputar ulang — berbeda dengan Tes Full yang hanya 1x putar.'],
                ['fas fa-pen-nib', '#7C3AED', '#F3E8FF', 'Structure',
                 'Perhatikan subject-verb agreement dan identifikasi error pada bagian yang digaris bawahi.'],
                ['fas fa-book-open', '#0891B2', '#ECFEFF', 'Reading',
                 'Baca passage sebelum menjawab. Fokus pada main idea dan detail spesifik yang ditanyakan.'],
            ] as [$ico, $col, $bg, $tit, $tip])
            <div style="
                display:flex; gap:13px; align-items:flex-start;
                background:var(--bg); border:1px solid var(--border);
                border-radius:12px; padding:16px;">
                <div style="
                    width:38px; height:38px; border-radius:10px; flex-shrink:0;
                    background:{{ $bg }}; color:{{ $col }};
                    display:flex; align-items:center; justify-content:center;
                    font-size:15px; border:1px solid {{ $col }}20;">
                    <i class="{{ $ico }}"></i>
                </div>
                <div>
                    <div style="font-size:13px;font-weight:700;color:var(--navy);margin-bottom:5px">{{ $tit }}</div>
                    <div style="font-size:12.5px;color:var(--muted);line-height:1.65">{{ $tip }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
