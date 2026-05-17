@extends('layouts.admin')
@section('title','Listening — Bank Soal')
@section('page-title','Bank Soal Listening')
@section('breadcrumb','Admin / Listening')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-headphones" style="color:var(--accent);margin-right:8px"></i>
            Paket Audio Listening
        </h3>
        <a href="{{ route('admin.listening.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-upload"></i> Upload Audio
        </a>
    </div>

    {{-- ── AUDIO FULL PAKET ── --}}
    <div class="card-body" style="padding:16px 20px">
        <div style="font-size:12px;font-weight:700;text-transform:uppercase;
            letter-spacing:1px;color:var(--muted);margin-bottom:10px">
            📦 1 Audio Full (Upload sekali untuk seluruh listening)
        </div>
        @forelse($audioFullList as $a)
        <div style="border:1px solid var(--border);border-radius:12px;margin-bottom:10px;
            overflow:hidden">
            <div style="background:var(--navy-light);padding:14px 18px;
                display:flex;align-items:center;gap:14px;flex-wrap:wrap">
                <div style="width:42px;height:42px;border-radius:10px;flex-shrink:0;
                    background:rgba(234,88,12,.15);border:1px solid rgba(234,88,12,.3);
                    display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-music" style="color:#fb923c;font-size:18px"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:14px;font-weight:700">{{ $a->nama }}</div>
                    <div style="font-size:12px;color:var(--muted);margin-top:3px;
                        display:flex;gap:10px;flex-wrap:wrap">
                        <span style="background:rgba(26,86,219,.15);color:var(--accent);
                            padding:1px 8px;border-radius:5px;font-size:11px;font-weight:600">
                            {{ strtoupper($a->tipe_paket) }}
                        </span>
                        <span><i class="fas fa-question-circle" style="font-size:10px"></i>
                            {{ $a->soal_list_count }} soal</span>
                        @if($a->durasi_detik > 0)
                        <span><i class="fas fa-clock" style="font-size:10px"></i>
                            {{ $a->durasi_format }}</span>
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
            {{-- Progress --}}
            @php $pct = min(100,($a->soal_list_count/50)*100) @endphp
            <div style="padding:6px 18px 8px;background:rgba(0,0,0,.12)">
                <div style="display:flex;justify-content:space-between;
                    font-size:11px;color:var(--muted);margin-bottom:3px">
                    <span>Progress soal</span><span>{{ $a->soal_list_count }}/50</span>
                </div>
                <div style="height:4px;background:var(--border);border-radius:2px">
                    <div style="height:4px;border-radius:2px;width:{{ $pct }}%;
                        background:{{ $pct>=100?'var(--green)':'var(--accent)' }}"></div>
                </div>
            </div>
        </div>
        @empty
        <div style="padding:20px;text-align:center;color:var(--muted);font-size:13px;
            border:2px dashed var(--border);border-radius:10px">
            Belum ada audio full. <a href="{{ route('admin.listening.create') }}">Upload sekarang</a>.
        </div>
        @endforelse
    </div>

    {{-- ── AUDIO PER MODUL ── --}}
    @if($audioModulList->count())
    <div class="card-body" style="padding:0 20px 16px">
        <div style="font-size:12px;font-weight:700;text-transform:uppercase;
            letter-spacing:1px;color:var(--muted);margin-bottom:10px;padding-top:16px;
            border-top:1px solid var(--border)">
            🧩 Audio Per Modul (Part A, B, C — digabung otomatis)
        </div>
        @foreach($audioModulList as $paketId => $audioList)
        @php $paketNama = $audioList->first()->paketSoal?->nama ?? 'Paket #'.$paketId; @endphp
        <div style="border:1px solid var(--border);border-radius:12px;
            margin-bottom:10px;overflow:hidden">
            <div style="background:rgba(139,92,246,.08);padding:10px 16px;
                border-bottom:1px solid var(--border);font-size:13px;font-weight:700;
                display:flex;align-items:center;justify-content:space-between">
                <span>📦 {{ $paketNama }}</span>
                <span style="font-size:12px;color:var(--muted);font-weight:400">
                    Total: {{ $audioList->sum('durasi_detik') }}
                    detik ({{ sprintf('%d:%02d',intdiv($audioList->sum('durasi_detik'),60),$audioList->sum('durasi_detik')%60) }})
                </span>
            </div>
            @foreach($audioList->sortBy('urutan_modul') as $a)
            <div style="padding:10px 16px 10px 28px;border-bottom:1px solid var(--border);
                display:flex;align-items:center;gap:12px;flex-wrap:wrap">
                <div style="background:rgba(139,92,246,.15);color:#a78bfa;
                    padding:2px 8px;border-radius:5px;font-size:11px;font-weight:700;flex-shrink:0">
                    Part {{ $a->urutan_modul }}
                </div>
                <div style="flex:1;font-size:13px">{{ $a->nama }}</div>
                <div style="font-size:12px;color:var(--muted)">
                    Offset: {{ $a->offset_detik }}s
                    &nbsp;·&nbsp; Durasi: {{ $a->durasi_format }}
                    &nbsp;·&nbsp; {{ $a->soalList->count() }} soal
                </div>
                <a href="{{ route('admin.listening.show', $a->id) }}"
                    class="btn btn-primary btn-sm" style="font-size:11px">
                    Kelola Soal
                </a>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
