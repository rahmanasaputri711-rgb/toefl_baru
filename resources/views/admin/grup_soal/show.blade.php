@extends('layouts.admin')
@section('title','Grup Soal #'.$grup->id)
@section('page-title','Detail Grup Soal')
@section('breadcrumb','Admin / Grup Soal / #'.$grup->id)

@push('styles')
<style>
.soal-card-item {
    background:var(--navy-light);border:1px solid rgba(255,255,255,.07);
    border-radius:10px;padding:16px;margin-bottom:10px;display:flex;align-items:flex-start;gap:14px;
    transition:border-color .15s;
}
.soal-card-item:hover { border-color:rgba(26,86,219,.3); }
.soal-num-badge {
    width:32px;height:32px;border-radius:8px;flex-shrink:0;
    background:rgba(26,86,219,.15);color:var(--accent);
    display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;
}
.opt-row { display:flex;gap:8px;flex-wrap:wrap;margin-top:8px; }
.opt-chip {
    display:inline-flex;align-items:center;gap:5px;padding:4px 11px;
    border-radius:7px;font-size:12.5px;border:1px solid rgba(255,255,255,.07);
    background:rgba(255,255,255,.03);color:rgba(255,255,255,.55);
}
.opt-chip.correct {
    background:rgba(22,163,74,.1);border-color:rgba(22,163,74,.25);color:#4ade80;font-weight:600;
}
</style>
@endpush

@section('content')

{{-- Header info grup --}}
<div class="card" style="margin-bottom:18px">
    <div class="card-header">
        <h3>
            <i class="fas fa-{{ $grup->kategori==='listening'?'headphones-alt':'book-open' }}"
                style="color:{{ $grup->kategori==='listening'?'#fdba74':'#93c5fd' }};margin-right:8px"></i>
            {{ $grup->judul ?: 'Grup #'.$grup->id }}
        </h3>
        <div style="display:flex;gap:8px">
            <a href="{{ route('admin.grup.edit', $grup->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i> Edit Grup</a>
            <a href="{{ route('admin.grup.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:16px">
            @foreach([
                ['fas fa-layer-group', 'Kategori', ucfirst($grup->kategori), '#f1f5f9'],
                ['fas fa-bookmark', 'Part', $grup->part ? 'Part '.$grup->part : '—', '#f1f5f9'],
                ['fas fa-question-circle', 'Jumlah Soal', $grup->soal->count().' soal', 'var(--accent)'],
                ['fas fa-circle', 'Status', $grup->is_aktif?'Aktif':'Nonaktif', $grup->is_aktif?'#4ade80':'#9ca3af'],
            ] as [$ico,$lbl,$val,$clr])
            <div style="text-align:center;padding:14px;background:rgba(255,255,255,.03);border-radius:10px;border:1px solid rgba(255,255,255,.06)">
                <i class="{{ $ico }}" style="color:{{ $clr }};font-size:18px;margin-bottom:8px;display:block"></i>
                <div style="font-size:11px;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:.7px;margin-bottom:4px">{{ $lbl }}</div>
                <div style="font-size:14px;font-weight:700;color:{{ $clr }}">{{ $val }}</div>
            </div>
            @endforeach
        </div>

        @if($grup->deskripsi)
        <div style="background:rgba(255,255,255,.03);border-radius:8px;padding:12px 14px;font-size:13.5px;color:rgba(255,255,255,.6)">
            {{ $grup->deskripsi }}
        </div>
        @endif

        {{-- Audio player --}}
        @if($grup->audio_url)
        @php $aUrl = \App\Services\AudioService::resolveUrl($grup->audio_url); @endphp
        <div style="margin-top:14px">
            <div style="font-size:11px;font-weight:700;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px">
                <i class="fas fa-music"></i> Audio
            </div>
            <div class="toefl-audio-wrap">
                <div class="tap-bar">
                    <button type="button" class="tap-play-btn" id="btn-gaud" onclick="tapToggle('gaud')">
                        <span class="tap-play-triangle" id="icon-gaud"></span>
                    </button>
                    <div class="tap-track-outer" id="track-gaud" onclick="tapSeek(event,'gaud')">
                        <div class="tap-track-inner"><div class="tap-track-fill" id="fill-gaud" style="width:0%"></div></div>
                        <div class="tap-thumb" id="thumb-gaud" style="left:0%"></div>
                    </div>
                    <span class="tap-time" id="time-gaud">00:00</span>
                    <button type="button" class="tap-vol-btn" onclick="tapToggleMute('gaud')">
                        <i class="fas fa-volume-up tap-vol-icon" id="volicon-gaud"></i>
                    </button>
                    <audio id="aud-gaud" data-mode="admin" preload="metadata" src="{{ $aUrl }}"
                        oncanplay="tapOnCanPlay('gaud')" ontimeupdate="tapOnTimeUpdate('gaud')" onended="tapOnEnded('gaud')"></audio>
                </div>
                <div class="tap-status" id="status-gaud">Klik ▶ untuk memutar</div>
            </div>
        </div>
        @endif

        {{-- Passage --}}
        @if($grup->passage_teks)
        <div style="margin-top:14px">
            <div style="font-size:11px;font-weight:700;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px">
                <i class="fas fa-align-left"></i> Teks Passage
            </div>
            <div style="background:rgba(26,86,219,.07);border-left:3px solid rgba(99,160,255,.4);
                border-radius:0 10px 10px 0;padding:14px 16px;font-size:13.5px;line-height:1.8;
                color:#94a3b8;max-height:200px;overflow-y:auto">
                {{ $grup->passage_teks }}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Daftar soal + tombol tambah --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list" style="color:var(--accent);margin-right:8px"></i>
            Soal dalam Grup
            <span class="badge badge-blue" style="margin-left:8px">{{ $grup->soal->count() }}</span>
        </h3>
        <a href="{{ route('admin.soal.create') }}?grup={{ $grup->id }}&kategori={{ $grup->kategori }}"
            class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Soal ke Grup</a>
    </div>
    <div class="card-body">

        @if($grup->soal->isEmpty())
        <div class="empty-state">
            <i class="fas fa-question-circle"></i>
            <p>Belum ada soal dalam grup ini.</p>
            <a href="{{ route('admin.soal.create') }}?grup={{ $grup->id }}&kategori={{ $grup->kategori }}"
                class="btn btn-primary btn-sm" style="margin-top:12px">
                <i class="fas fa-plus"></i> Tambah Soal Pertama
            </a>
        </div>
        @else
        @foreach($grup->soal->sortBy('nomor_soal') as $i => $s)
        <div class="soal-card-item">
            <div class="soal-num-badge">{{ $s->nomor_soal ?: $i+1 }}</div>
            <div style="flex:1">
                <div style="font-size:14px;font-weight:600;color:#f1f5f9;line-height:1.6;margin-bottom:8px">
                    {{ $s->pertanyaan }}
                </div>
                <div class="opt-row">
                    @foreach(['a','b','c','d'] as $opt)
                    <div class="opt-chip {{ $s->jawaban_benar===$opt?'correct':'' }}">
                        <span style="font-size:11px;font-weight:800;opacity:.7">{{ strtoupper($opt) }}.</span>
                        {{ Str::limit($s->{'pilihan_'.$opt}, 50) }}
                        @if($s->jawaban_benar===$opt)<i class="fas fa-check" style="font-size:10px"></i>@endif
                    </div>
                    @endforeach
                </div>
                @if($s->pembahasan)
                <div style="margin-top:8px;font-size:12px;color:#818cf8;font-style:italic">
                    <i class="fas fa-lightbulb" style="font-size:10px"></i> {{ Str::limit($s->pembahasan, 100) }}
                </div>
                @endif
            </div>
            <div style="display:flex;flex-direction:column;gap:5px;flex-shrink:0">
                <a href="{{ route('admin.soal.edit', $s->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>
                <form action="{{ route('admin.soal.destroy', $s->id) }}" method="POST"
                    onsubmit="return confirm('Hapus soal ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>
        @endforeach
        @endif

    </div>
</div>
@endsection
