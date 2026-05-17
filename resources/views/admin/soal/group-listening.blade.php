@extends('layouts.admin')
@section('title','Group Listening')
@section('page-title','Bank Soal Listening')
@section('breadcrumb','Admin / Bank Soal / Listening')

@push('styles')
<style>
.stat-strip{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:22px}
.stat-c{background:var(--navy-light);border:1px solid var(--border);border-radius:12px;
    padding:16px 18px;display:flex;align-items:center;gap:14px}
.stat-ico{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;
    justify-content:center;font-size:20px;flex-shrink:0}
.stat-val{font-size:22px;font-weight:800;line-height:1}
.stat-lbl{font-size:12px;color:var(--muted);margin-top:3px}
.audio-card{border:1px solid var(--border);border-radius:14px;overflow:hidden;
    margin-bottom:10px;transition:border-color .15s}
.audio-card:hover{border-color:rgba(234,88,12,.4)}
.audio-head{background:var(--navy-light);padding:14px 18px;
    display:flex;align-items:center;gap:14px;flex-wrap:wrap}
.audio-ico{width:44px;height:44px;border-radius:10px;flex-shrink:0;
    background:rgba(234,88,12,.15);border:1px solid rgba(234,88,12,.25);
    display:flex;align-items:center;justify-content:center}
.audio-prog{padding:7px 18px 10px;background:rgba(0,0,0,.12)}
</style>
@endpush

@section('content')
<div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
    <a href="{{ route('admin.soal.group') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <div style="font-size:18px;font-weight:800">🎧 Bank Soal Listening</div>
        <div style="font-size:13px;color:var(--muted)">Kelola paket audio dan soal listening</div>
    </div>
    <a href="{{ route('admin.listening.create') }}" class="btn btn-primary btn-sm"
        style="margin-left:auto">
        <i class="fas fa-upload"></i> Upload Audio
    </a>
</div>

{{-- Stat ─ --}}
@php
    $totalAudio = \App\Models\ListeningAudioPaket::count();
    $totalSoal  = \App\Models\BankSoal::where('kategori','listening')->count();
    $totalFull  = \App\Models\ListeningAudioPaket::where('tipe_paket','full')->count();
    $totalSim   = \App\Models\ListeningAudioPaket::where('tipe_paket','simulasi')->count();
@endphp
<div class="stat-strip">
    <div class="stat-c">
        <div class="stat-ico" style="background:rgba(234,88,12,.15)">🎵</div>
        <div><div class="stat-val">{{ $totalAudio }}</div><div class="stat-lbl">Paket Audio</div></div>
    </div>
    <div class="stat-c">
        <div class="stat-ico" style="background:rgba(22,163,74,.12)">❓</div>
        <div><div class="stat-val">{{ $totalSoal }}</div><div class="stat-lbl">Total Soal Listening</div></div>
    </div>
    <div class="stat-c">
        <div class="stat-ico" style="background:rgba(245,158,11,.12)">🏆</div>
        <div><div class="stat-val">{{ $totalFull }}</div><div class="stat-lbl">Paket Full</div></div>
    </div>
    <div class="stat-c">
        <div class="stat-ico" style="background:rgba(139,92,246,.12)">🎯</div>
        <div><div class="stat-val">{{ $totalSim }}</div><div class="stat-lbl">Paket Simulasi</div></div>
    </div>
</div>

{{-- Info konsep ─ --}}
<div style="background:rgba(234,88,12,.07);border:1px solid rgba(234,88,12,.2);
    border-radius:12px;padding:14px 18px;margin-bottom:18px;font-size:13px;line-height:1.8">
    <strong style="color:#fb923c">🎧 Konsep Listening:</strong>
    1 audio full ±35 menit untuk seluruh sesi. Soal muncul otomatis berdasarkan timestamp.
    Mahasiswa hanya klik <strong>Play sekali</strong> — tidak ada pause/rewind/speed di Tes Full.
</div>

{{-- Daftar paket audio ─ --}}
<div class="card">
    <div class="card-header">
        <h3 style="font-size:14px">
            <i class="fas fa-headphones" style="color:#fb923c;margin-right:7px"></i>
            Paket Audio Listening
        </h3>
    </div>
    <div class="card-body" style="padding:16px 20px">
        @php
            $audioList = \App\Models\ListeningAudioPaket::withCount('soalList')
                ->orderByDesc('created_at')->get();
        @endphp
        @forelse($audioList as $a)
        <div class="audio-card">
            <div class="audio-head">
                <div class="audio-ico">
                    <i class="fas fa-music" style="color:#fb923c;font-size:18px"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:14px;font-weight:700">{{ $a->nama }}</div>
                    <div style="font-size:12px;color:var(--muted);
                        display:flex;gap:10px;flex-wrap:wrap;margin-top:3px">
                        <span style="background:rgba(26,86,219,.12);color:var(--accent);
                            padding:1px 7px;border-radius:4px;font-size:11px;font-weight:600">
                            {{ strtoupper($a->tipe_paket) }}
                        </span>
                        <span>
                            <i class="fas fa-question-circle" style="font-size:10px"></i>
                            {{ $a->soal_list_count }} soal
                        </span>
                        @if($a->durasi_detik > 0)
                        <span>
                            <i class="fas fa-clock" style="font-size:10px"></i>
                            {{ $a->durasi_format }}
                        </span>
                        @endif
                    </div>
                </div>
                <div style="display:flex;gap:8px;flex-shrink:0">
                    <a href="{{ route('admin.listening.show', $a->id) }}"
                        class="btn btn-primary btn-sm">
                        <i class="fas fa-headphones"></i> Kelola Soal
                    </a>
                    <form action="{{ route('admin.listening.destroy', $a->id) }}" method="POST"
                        onsubmit="return confirm('Hapus paket ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @php $pct = min(100,($a->soal_list_count/50)*100) @endphp
            <div class="audio-prog">
                <div style="display:flex;justify-content:space-between;
                    font-size:11px;color:var(--muted);margin-bottom:4px">
                    <span>Progress soal</span>
                    <span>{{ $a->soal_list_count }}/50</span>
                </div>
                <div style="height:4px;background:var(--border);border-radius:2px">
                    <div style="height:4px;border-radius:2px;width:{{ $pct }}%;
                        background:{{ $pct>=100?'var(--green)':'#fb923c' }}"></div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-headphones"></i>
            <p>Belum ada paket audio.
                <a href="{{ route('admin.listening.create') }}">Upload sekarang</a>.
            </p>
        </div>
        @endforelse
    </div>
</div>
@endsection
