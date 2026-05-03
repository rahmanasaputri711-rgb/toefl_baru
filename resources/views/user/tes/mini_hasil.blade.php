@extends('layouts.user')
@section('title','Hasil Tes Mini')
@section('page-title','Hasil Tes Mini')
@section('breadcrumb','Home / Tes Mini / Hasil')

@section('content')
<div style="max-width:760px">

    {{-- Skor Summary --}}
    <div class="card" style="margin-bottom:20px">
        <div class="card-body" style="padding:30px;text-align:center">
            <div style="font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px">
                Hasil Tes Mini
            </div>
            <div style="font-size:58px;font-weight:900;line-height:1;
                color:{{ $persentase>=70 ? 'var(--green)':($persentase>=50?'var(--gold)':'var(--red)') }}">
                {{ $persentase }}%
            </div>
            <div style="font-size:15px;color:var(--muted);margin-top:8px">
                {{ $benar }} dari {{ $totalSoal }} soal benar
            </div>

            {{-- Per-kategori breakdown --}}
            @php
                $byKat = collect($review)->groupBy(fn($r) => $r['soal']->kategori);
            @endphp
            <div class="grid-3" style="margin-top:20px">
                @foreach(['listening'=>'var(--orange)','structure'=>'var(--gold)','reading'=>'var(--accent)'] as $kat=>$color)
                @php
                    $items    = $byKat->get($kat, collect());
                    $benarKat = $items->where('is_benar', true)->count();
                    $totalKat = $items->count();
                    $pctKat   = $totalKat > 0 ? round($benarKat/$totalKat*100) : 0;
                @endphp
                <div style="background:var(--surface2);border-radius:10px;padding:16px">
                    <div style="font-size:22px;font-weight:800;color:{{ $color }}">{{ $pctKat }}%</div>
                    <div style="font-size:11px;color:var(--muted);margin-top:4px;text-transform:uppercase;letter-spacing:.5px">{{ ucfirst($kat) }}</div>
                    <div style="font-size:12px;color:var(--muted);margin-top:3px">{{ $benarKat }}/{{ $totalKat }}</div>
                </div>
                @endforeach
            </div>

            <div style="display:flex;gap:12px;justify-content:center;margin-top:22px">
                <a href="{{ route('user.tes.mini') }}" class="btn btn-primary"><i class="fas fa-redo"></i> Tes Mini Lagi</a>
                <a href="{{ route('user.dashboard') }}" class="btn btn-outline"><i class="fas fa-home"></i> Dashboard</a>
            </div>
        </div>
    </div>

    {{-- Review Jawaban --}}
    <div class="card">
        <div class="card-header">
            <h3>Review Jawaban</h3>
            <div style="display:flex;gap:8px">
                <span class="badge badge-green">{{ $benar }} Benar</span>
                <span class="badge badge-red">{{ $totalSoal - $benar }} Salah</span>
            </div>
        </div>
        @php $prevKat = null; $no = 0; @endphp
        @foreach($review as $item)
        @php $no++; $soal = $item['soal']; @endphp

        @if($soal->kategori !== $prevKat)
        <div style="padding:10px 20px;background:var(--surface2);border-bottom:1px solid var(--border);
            font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.8px">
            Section: {{ ucfirst($soal->kategori) }}
        </div>
        @php $prevKat = $soal->kategori; @endphp
        @endif

        <div style="padding:16px 20px;border-bottom:1px solid var(--border);
            background:{{ $item['is_benar'] ? 'rgba(16,185,129,.025)':'rgba(239,68,68,.025)' }}">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                <div style="width:26px;height:26px;border-radius:6px;flex-shrink:0;
                    background:{{ $item['is_benar'] ? 'rgba(16,185,129,.15)':'rgba(239,68,68,.15)' }};
                    color:{{ $item['is_benar'] ? 'var(--green)':'var(--red)' }};
                    display:flex;align-items:center;justify-content:center;font-size:12px">
                    <i class="fas fa-{{ $item['is_benar'] ? 'check':'times' }}"></i>
                </div>
                <span style="font-weight:600;font-size:13px;color:var(--muted)">No. {{ $no }}</span>
                <span class="badge {{ $item['is_benar'] ? 'badge-green':'badge-red' }}">
                    {{ $item['is_benar'] ? 'Benar':'Salah' }}
                </span>
            </div>

            <p style="font-size:14px;font-weight:600;margin-bottom:10px;line-height:1.6">{{ $soal->pertanyaan }}</p>

            <div style="display:flex;flex-direction:column;gap:6px">
            @foreach(['a','b','c','d'] as $opt)
            <div style="display:flex;align-items:flex-start;gap:10px;padding:9px 12px;border-radius:7px;font-size:13px;
                background:{{ $soal->jawaban_benar==$opt ? 'rgba(16,185,129,.1)':($item['jawaban_user']==$opt && !$item['is_benar'] ? 'rgba(239,68,68,.08)':'var(--surface2)') }};
                border:1px solid {{ $soal->jawaban_benar==$opt ? 'rgba(16,185,129,.3)':($item['jawaban_user']==$opt && !$item['is_benar'] ? 'rgba(239,68,68,.3)':'var(--border)') }}">
                <span style="font-weight:700;color:var(--muted);width:16px;flex-shrink:0">{{ strtoupper($opt) }}.</span>
                <span style="flex:1">{{ $soal->{'pilihan_'.$opt} }}</span>
                @if($soal->jawaban_benar == $opt)
                <i class="fas fa-check-circle" style="color:var(--green);font-size:13px;margin-top:2px"></i>
                @elseif($item['jawaban_user'] == $opt && !$item['is_benar'])
                <i class="fas fa-times-circle" style="color:var(--red);font-size:13px;margin-top:2px"></i>
                @endif
            </div>
            @endforeach
            </div>

            @if($soal->pembahasan)
            <div style="margin-top:10px;padding:10px 14px;background:rgba(59,130,246,.07);
                 border-left:3px solid var(--accent);border-radius:0 7px 7px 0;font-size:13px;color:#93c5fd">
                <strong><i class="fas fa-lightbulb"></i> Pembahasan:</strong> {{ $soal->pembahasan }}
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection
