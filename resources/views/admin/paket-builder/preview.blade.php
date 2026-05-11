@extends('layouts.admin')
@section('title','Preview — '.$paket->nama)
@section('page-title','Preview Soal: '.$paket->nama)
@section('breadcrumb','Admin / Paket Builder / Preview')

@push('styles')
<style>
/* Kartu soal preview */
.sq-card{border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:16px}
.sq-head{padding:10px 16px;display:flex;align-items:center;gap:10px;
    background:var(--navy-light);border-bottom:1px solid var(--border)}
.sq-no{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;
    justify-content:center;font-weight:900;font-size:13px;color:#fff;flex-shrink:0}
.sq-body{padding:16px 18px}
/* Missing letters inline */
.ml-input{display:inline-block;border:none;border-bottom:2px solid #34d399;
    background:rgba(52,211,153,.1);border-radius:3px 3px 0 0;padding:1px 4px;
    text-align:center;font-size:14px;color:#34d399;font-weight:600;vertical-align:baseline}
/* Pilihan jawaban */
.opt-row{display:flex;align-items:flex-start;gap:10px;padding:8px 12px;
    border-radius:8px;margin-bottom:5px;background:rgba(255,255,255,.03);
    border:1.5px solid var(--border)}
.opt-circle{width:24px;height:24px;border-radius:50%;border:2px solid var(--border);
    display:flex;align-items:center;justify-content:center;
    font-size:11px;font-weight:700;flex-shrink:0;margin-top:1px}
.opt-row.correct{background:rgba(22,163,74,.08);border-color:rgba(22,163,74,.3)}
.opt-row.correct .opt-circle{background:var(--green);border-color:var(--green);color:#fff}
/* Passage split */
.passage-wrap{display:grid;grid-template-columns:1fr 1fr;gap:0;border-radius:10px;
    overflow:hidden;border:1px solid var(--border)}
.passage-teks{padding:16px;background:rgba(0,0,0,.15);font-size:14px;
    line-height:1.9;border-right:1px solid var(--border);max-height:400px;overflow-y:auto}
.passage-soal{padding:16px}
/* Section divider */
.section-div{background:rgba(26,86,219,.08);border:1px solid rgba(26,86,219,.18);
    border-radius:10px;padding:10px 16px;margin-bottom:14px;
    display:flex;align-items:center;gap:10px;font-weight:700;font-size:13.5px}
</style>
@endpush

@section('content')

<div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
    <a href="{{ route('admin.paket-builder.paket', $paket->id) }}"
        class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <div>
        <div style="font-size:18px;font-weight:800">Preview: {{ $paket->nama }}</div>
        <div style="font-size:13px;color:var(--muted)">
            {{ \App\Models\ModulSoal::where('paket_id', $paket->id)->max('nomor_soal_selesai') }} soal &nbsp;·&nbsp;
            Tampilan seperti yang dilihat mahasiswa
        </div>
    </div>
    @if($paket->status !== 'valid')
    <form action="{{ route('admin.paket-builder.selesaikan', $paket->id) }}"
        method="POST" style="margin-left:auto">
        @csrf
        <button type="submit" class="btn btn-primary"
            onclick="return confirm('Jadikan paket ini aktif/final?')">
            <i class="fas fa-flag-checkered"></i> Jadikan Paket Resmi
        </button>
    </form>
    @else
    <span style="margin-left:auto;background:rgba(22,163,74,.15);color:var(--green);
        padding:6px 16px;border-radius:8px;font-weight:700;font-size:13px">
        ✓ Paket Aktif
    </span>
    @endif
</div>

@php
    // Kelompokkan soal per modul
    $byModul = $soalGlobal->groupBy('modul_id');
@endphp

@foreach($byModul as $modulId => $soalModul)
@php
    $modul      = $soalModul->first()->modul;
    $tipeMeta   = \App\Models\ModulSoal::TIPE[$modul?->tipe_modul ?? 'passage'] ?? [];
    $tipeColor  = $tipeMeta['color'] ?? '#3b82f6';
    $firstSoal  = $soalModul->first();
    $lastSoal   = $soalModul->last();
    $nomorMulai = $modul?->nomor_soal_mulai ?? $firstSoal->nomor_dalam_paket;
    $nomorAkhir = $modul?->nomor_soal_selesai ?? $lastSoal->nomor_dalam_paket;
@endphp

{{-- Section divider --}}
<div class="section-div">
    <div style="width:10px;height:10px;border-radius:50%;background:{{ $tipeColor }}"></div>
    <span>{{ $modul?->judul ?: ($tipeMeta['label'] ?? '—') }}</span>
    <span style="font-size:12px;color:var(--muted);font-weight:400">
        Soal No.{{ $nomorMulai }}–{{ $nomorAkhir }}
    </span>
</div>

{{-- ── MISSING LETTERS ── --}}
@if($modul?->tipe_modul === 'missing_letters')
@php $s = $firstSoal; @endphp
<div class="sq-card">
    <div class="sq-head">
        <div class="sq-no" style="background:#10b981;border-radius:6px;width:auto;
            padding:0 10px;font-size:11px">
            No.{{ $nomorMulai }}–{{ $nomorAkhir }}
        </div>
        <div style="font-size:13.5px;font-weight:700">Fill in the missing letters in the paragraph</div>
        <span style="margin-left:auto;background:rgba(16,185,129,.15);color:#34d399;
            padding:2px 10px;border-radius:6px;font-size:11px;font-weight:700">
            @php preg_match_all('/\[([^\]]+)\]/', $s->fill_text??'', $mx); @endphp
            {{ count($mx[1]) }} blank
        </span>
    </div>
    <div class="sq-body">
        <div style="font-size:14.5px;line-height:2.2;color:#e2e8f0">
            @php
                $parts = preg_split('/(\[[^\]]+\])/', $s->fill_text??'', -1, PREG_SPLIT_DELIM_CAPTURE);
                $blankNo = $nomorMulai;
            @endphp
            @foreach($parts as $part)
                @if(preg_match('/^\[([^\]]+)\]$/', $part, $mm))
                    @php $ans = $mm[1]; $w = max(28, strlen($ans)*11); @endphp
                    <span style="position:relative;display:inline-block">
                        <input class="ml-input" disabled placeholder="{{ str_repeat('_',strlen($ans)) }}"
                            style="width:{{ $w }}px" title="Blank {{ $blankNo }}">
                        <sup style="position:absolute;top:-4px;left:2px;font-size:9px;
                            color:#34d399;font-weight:700">{{ $blankNo++ }}</sup>
                    </span>
                @else
                    {!! nl2br(htmlspecialchars($part)) !!}
                @endif
            @endforeach
        </div>
    </div>
</div>

{{-- ── PASSAGE (Multiple Choice / Vocabulary / Click Sentence) ── --}}
@elseif($modul?->tipe_modul === 'passage')
@php $passage = $soalModul->first()->passage; @endphp
@if($passage)
<div class="passage-wrap" style="margin-bottom:16px">
    {{-- Kiri: teks --}}
    <div class="passage-teks">
        <div style="font-weight:800;font-size:15px;margin-bottom:12px;color:#e2e8f0">
            {{ $passage->judul }}
        </div>
        <div style="color:#cbd5e1;line-height:1.9">
            {!! $passage->renderTeks() !!}
        </div>
    </div>
    {{-- Kanan: soal-soal --}}
    <div class="passage-soal">
        @foreach($soalModul as $s)
        <div style="margin-bottom:18px;padding-bottom:18px;
            {{ !$loop->last ? 'border-bottom:1px solid var(--border)' : '' }}">
            <div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:10px">
                <div class="sq-no" style="background:#3b82f6;font-size:12px;
                    width:28px;height:28px;flex-shrink:0">{{ $s->nomor_dalam_paket }}</div>
                <div style="font-size:13.5px;font-weight:600;color:#e2e8f0;line-height:1.5">
                    @if($s->tipe_soal === 'vocabulary' && $s->highlight_kata)
                        The word
                        <span style="background:rgba(59,130,246,.25);color:#93c5fd;
                            padding:1px 6px;border-radius:4px;font-style:italic">
                            "{{ $s->highlight_kata }}"</span>
                        is closest in meaning to
                    @else
                        {{ $s->pertanyaan }}
                    @endif
                </div>
            </div>
            @if($s->tipe_soal === 'click_sentence')
            <div style="background:rgba(139,92,246,.08);border:1px solid rgba(139,92,246,.2);
                border-radius:8px;padding:10px 14px;font-size:13px;color:#a78bfa">
                <i class="fas fa-mouse-pointer"></i>
                Klik kalimat yang tepat di teks bacaan sebelah kiri.
            </div>
            @else
            @foreach(['a','b','c','d'] as $k)
            @php $val = $s->{'pilihan_'.$k}; if($val === '-' || !$val) continue; @endphp
            <div class="opt-row {{ $s->jawaban_benar === $k ? 'correct' : '' }}">
                <div class="opt-circle">{{ strtoupper($k) }}</div>
                <div style="font-size:13.5px;line-height:1.5;color:#e2e8f0">{{ $val }}</div>
            </div>
            @endforeach
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ── IMAGE / EMAIL ── --}}
@elseif($modul?->tipe_modul === 'image_email')
@php $passage = $soalModul->first()->passage; @endphp
<div class="sq-card">
    <div class="sq-body">
        @if($passage?->image_url)
        <img src="{{ asset('storage/'.$passage->image_url) }}"
            style="max-width:100%;border-radius:8px;margin-bottom:16px">
        @else
        <div style="background:rgba(245,158,11,.08);border:2px dashed rgba(245,158,11,.3);
            border-radius:8px;padding:24px;text-align:center;margin-bottom:16px;color:#fbbf24">
            <i class="fas fa-image" style="font-size:28px;display:block;margin-bottom:8px"></i>
            Belum ada gambar diupload
        </div>
        @endif
        @foreach($soalModul as $s)
        <div style="margin-bottom:16px;padding-bottom:16px;
            {{ !$loop->last ? 'border-bottom:1px solid var(--border)' : '' }}">
            <div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:10px">
                <div class="sq-no" style="background:#f59e0b;font-size:12px;
                    width:28px;height:28px;flex-shrink:0">{{ $s->nomor_dalam_paket }}</div>
                <div style="font-size:13.5px;font-weight:600;color:#e2e8f0">{{ $s->pertanyaan }}</div>
            </div>
            @foreach(['a','b','c','d'] as $k)
            @php $val = $s->{'pilihan_'.$k}; if($val==='-'||!$val) continue; @endphp
            <div class="opt-row {{ $s->jawaban_benar===$k ? 'correct' : '' }}">
                <div class="opt-circle">{{ strtoupper($k) }}</div>
                <div style="font-size:13.5px;color:#e2e8f0">{{ $val }}</div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>
@endif

@endforeach

@if($soalGlobal->isEmpty())
<div style="text-align:center;padding:60px;color:var(--muted)">
    <i class="fas fa-inbox" style="font-size:40px;display:block;margin-bottom:14px"></i>
    Belum ada soal. Kembali ke halaman paket untuk menambahkan soal.
</div>
@endif

@endsection
