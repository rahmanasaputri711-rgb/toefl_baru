@extends('layouts.user')
@section('title','Hasil Simulasi')
@section('page-title','Hasil Tes Simulasi')
@section('breadcrumb','Home / Simulasi / Hasil')

@section('content')
<div style="max-width:780px">

    {{-- Skor Utama --}}
    <div class="card" style="margin-bottom:20px">
        <div class="card-body" style="padding:32px;text-align:center">
            <div style="font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px">
                Estimasi Skor TOEFL ITP
            </div>
            <div style="font-size:62px;font-weight:900;line-height:1;
                color:{{ $total>=500 ? 'var(--green)':($total>=400?'var(--gold)':'var(--red)') }}">
                {{ $total }}
            </div>
            <div style="font-size:13px;font-weight:600;margin-top:8px;
                color:{{ $total>=500 ? 'var(--green)':($total>=400?'var(--gold)':'var(--red)') }}">
                @if($total >= 550) Sangat Baik
                @elseif($total >= 500) Baik (Lulus standar umum)
                @elseif($total >= 450) Cukup — perlu banyak latihan
                @elseif($total >= 400) Kurang — tingkatkan semua section
                @else Perlu persiapan lebih intensif
                @endif
            </div>

            {{-- Per section --}}
            <div class="grid-3" style="margin-top:22px">
                @foreach([
                    ['Listening', $sL, $benarL, 50, 'var(--orange)'],
                    ['Structure', $sS, $benarS, 40, 'var(--gold)'],
                    ['Reading',   $sR, $benarR, 50, 'var(--accent)'],
                ] as [$lbl,$sk,$benar,$maks,$color])
                <div style="background:var(--surface2);border-radius:12px;padding:18px">
                    <div style="font-size:28px;font-weight:900;color:{{ $color }}">{{ $sk }}</div>
                    <div style="font-size:12px;color:var(--muted);margin-top:4px">{{ $lbl }}</div>
                    <div style="font-size:11px;color:var(--muted);margin-top:4px">{{ $benar }}/{{ $maks }} benar</div>
                </div>
                @endforeach
            </div>

            <div style="display:flex;gap:12px;justify-content:center;margin-top:22px">
                <a href="{{ route('user.tes.simulasi') }}" class="btn btn-primary"><i class="fas fa-redo"></i> Simulasi Lagi</a>
                <a href="{{ route('user.tes.full') }}" class="btn btn-outline"><i class="fas fa-graduation-cap"></i> Coba Tes Full</a>
                <a href="{{ route('user.dashboard') }}" class="btn btn-outline"><i class="fas fa-home"></i> Dashboard</a>
            </div>
        </div>
    </div>

    {{-- Review Jawaban --}}
    <div class="card">
        <div class="card-header">
            <h3>Review Jawaban</h3>
            @php $totalBenar = $benarL + $benarS + $benarR; $totalSoal = count($review); @endphp
            <div style="display:flex;gap:8px">
                <span class="badge badge-green">{{ $totalBenar }} Benar</span>
                <span class="badge badge-red">{{ $totalSoal - $totalBenar }} Salah</span>
            </div>
        </div>

        @php $prevKat = null; $no = 0; @endphp
        @foreach($review as $item)
        @php $no++; $soal = $item['soal']; @endphp

        @if($soal->kategori !== $prevKat)
        <div style="padding:10px 20px;background:var(--surface2);border-bottom:1px solid var(--border);
            font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;
            display:flex;align-items:center;gap:8px">
            <i class="fas fa-{{ $soal->kategori=='listening' ? 'headphones':($soal->kategori=='structure'?'pen-nib':'book-reader') }}"
               style="color:{{ $soal->kategori=='listening' ? 'var(--orange)':($soal->kategori=='structure'?'var(--gold)':'var(--accent)') }}"></i>
            Section: {{ ucfirst($soal->kategori) }}
        </div>
        @php $prevKat = $soal->kategori; @endphp
        @endif

        <div style="padding:15px 20px;border-bottom:1px solid var(--border);
            background:{{ $item['is_benar'] ? 'rgba(16,185,129,.02)':'rgba(239,68,68,.02)' }}">
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

            <div style="display:flex;gap:10px;font-size:13px;flex-wrap:wrap">
                <span style="color:var(--muted)">Jawaban Anda:
                    <strong style="color:{{ $item['is_benar'] ? 'var(--green)':'var(--red)' }}">
                        @if($item['jawaban_user'])
                        {{ strtoupper($item['jawaban_user']) }}. {{ $soal->{'pilihan_'.$item['jawaban_user']} }}
                        @else <em>Tidak dijawab</em> @endif
                    </strong>
                </span>
                @if(!$item['is_benar'])
                <span style="color:var(--muted)">Jawaban Benar:
                    <strong style="color:var(--green)">
                        {{ strtoupper($soal->jawaban_benar) }}. {{ $soal->{'pilihan_'.$soal->jawaban_benar} }}
                    </strong>
                </span>
                @endif
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
