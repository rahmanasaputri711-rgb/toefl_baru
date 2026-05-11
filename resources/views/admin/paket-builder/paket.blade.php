@extends('layouts.admin')
@section('title', $paket->nama)
@section('page-title', 'Paket: '.$paket->nama)
@section('breadcrumb', 'Admin / Paket Builder / '.$paket->nama)

@push('styles')
<style>
.grup-card{border:1px solid var(--border);border-radius:14px;overflow:hidden;margin-bottom:16px}
.grup-head{padding:14px 18px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px}
.modul-row{padding:11px 18px 11px 28px;border-top:1px solid rgba(255,255,255,.05);
    display:flex;align-items:center;gap:12px;flex-wrap:wrap}
.modul-tipe{padding:3px 10px;border-radius:6px;font-size:11.5px;font-weight:700}
.soal-row-g{display:flex;align-items:center;gap:10px;padding:9px 16px;
    border-bottom:1px solid var(--border);font-size:13px}
.soal-row-g:last-child{border-bottom:none}
.no-badge{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;
    justify-content:center;font-size:12px;font-weight:800;color:#fff;flex-shrink:0}
</style>
@endpush

@section('content')

{{-- Flash --}}
@if(session('success'))<div class="alert alert-success" style="margin-bottom:14px">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger" style="margin-bottom:14px">{{ session('error') }}</div>@endif

{{-- Header --}}
<div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;flex-wrap:wrap">
    <a href="{{ route('admin.paket-builder.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div style="flex:1">
        <div style="font-size:19px;font-weight:800">{{ $paket->nama }}</div>
        <div style="font-size:13px;color:var(--muted)">
            {{ $soalGlobal->count() }} soal tersimpan &nbsp;·&nbsp;
            <span style="color:{{ $paket->status==='valid'?'var(--green)':'var(--muted)' }};font-weight:700">
                {{ strtoupper($paket->status) }}
            </span>
        </div>
    </div>
    @if($soalGlobal->count() > 0)
    <form action="{{ route('admin.paket-builder.selesaikan', $paket->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline"
            style="border-color:var(--green);color:var(--green)"
            onclick="return confirm('Tandai paket ini selesai?')">
            <i class="fas fa-flag-checkered"></i> Paket Selesai
        </button>
    </form>
    @endif
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:18px;align-items:start">

{{-- ═══ KIRI: Group & Modul ═══ --}}
<div>

{{-- Tambah Group --}}
@php $kategoriAda = $grupList->pluck('kategori')->toArray(); @endphp
@if(count($kategoriAda) < 3)
<div class="card" style="margin-bottom:16px">
    <div class="card-body" style="padding:16px 20px">
        <form action="{{ route('admin.paket-builder.grup.store', $paket->id) }}"
            method="POST" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            @csrf
            <div style="font-size:13px;font-weight:700;color:var(--muted);white-space:nowrap">
                + Tambah Group:
            </div>
            @foreach(['reading','listening','structure'] as $kat)
            @if(!in_array($kat, $kategoriAda))
            <button type="submit" name="kategori" value="{{ $kat }}"
                class="btn btn-outline btn-sm">
                {{ ['reading'=>'📖 Reading','listening'=>'🎧 Listening','structure'=>'✏️ Structure'][$kat] }}
            </button>
            @endif
            @endforeach
        </form>
    </div>
</div>
@endif

{{-- Daftar Group --}}
@forelse($grupList as $grup)
@php
    $katMeta = \App\Models\GrupSoal::KATEGORI[$grup->kategori];
@endphp
<div class="grup-card">
    {{-- Group header --}}
    <div class="grup-head" style="background:rgba(255,255,255,.03)">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="font-size:20px">
                {{ ['reading'=>'📖','listening'=>'🎧','structure'=>'✏️'][$grup->kategori] }}
            </div>
            <div>
                <div style="font-weight:800;font-size:14px">Group {{ ucfirst($grup->kategori) }}</div>
                <div style="font-size:12px;color:var(--muted)">
                    {{ $grup->modul->count() }} modul &nbsp;·&nbsp;
                    {{ $grup->modul->sum(fn($m) => $m->soal->count()) }} soal
                </div>
            </div>
        </div>
        <a href="{{ route('admin.paket-builder.modul.create', [$paket->id, $grup->id]) }}"
            class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Modul
        </a>
    </div>

    {{-- Modul list --}}
    @foreach($grup->modul as $modul)
    @php
        $tipeMeta = \App\Models\ModulSoal::TIPE[$modul->tipe_modul];
        $soalCount = $modul->soal->count();
    @endphp
    <div class="modul-row">
        <div style="width:4px;height:36px;border-radius:2px;flex-shrink:0;
            background:{{ $tipeMeta['color'] }}"></div>
        <div style="flex:1;min-width:0">
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                <span style="font-size:13px;font-weight:700">{{ $modul->judul ?: $tipeMeta['label'] }}</span>
                <span class="modul-tipe"
                    style="background:{{ $tipeMeta['color'] }}22;color:{{ $tipeMeta['color'] }}">
                    {{ $modul->rentang }}
                </span>
                @if($modul->is_selesai)
                <span style="font-size:11px;color:var(--green)">✓ Selesai</span>
                @else
                <span style="font-size:11px;color:var(--muted)">{{ $soalCount }}/{{ $modul->jumlah_target }} soal</span>
                @endif
            </div>
        </div>
        <div style="display:flex;gap:6px;flex-shrink:0">
            <a href="{{ route('admin.paket-builder.modul.input', $modul->id) }}"
                class="btn btn-primary btn-sm" style="font-size:12px;padding:5px 12px">
                <i class="fas fa-edit"></i> Input
            </a>
            <form action="{{ route('admin.paket-builder.modul.destroy', $modul->id) }}"
                method="POST"
                onsubmit="return confirm('Hapus modul + semua soalnya?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm"
                    style="font-size:12px;padding:5px 10px">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
    @endforeach

    @if($grup->modul->count() === 0)
    <div style="padding:16px 28px;font-size:13px;color:var(--muted)">
        Belum ada modul. Klik <strong>"Tambah Modul"</strong> untuk mulai.
    </div>
    @endif
</div>
@empty
<div style="text-align:center;padding:48px;color:var(--muted);
    border:2px dashed var(--border);border-radius:14px">
    <i class="fas fa-layer-group" style="font-size:32px;display:block;margin-bottom:12px"></i>
    Belum ada group. Klik tombol di atas untuk menambah Group Reading, Listening, atau Structure.
</div>
@endforelse
</div>

{{-- ═══ KANAN: Daftar Soal Global ═══ --}}
<div style="position:sticky;top:20px">
    <div class="card">
        <div class="card-header" style="padding:14px 16px">
            <h3 style="font-size:13px;margin:0">
                <i class="fas fa-list-ol" style="color:var(--accent);margin-right:6px"></i>
                Daftar Soal Paket
            </h3>
            <div style="display:flex;align-items:center;gap:8px">
                <span style="font-size:12px;color:var(--muted)">{{ $soalGlobal->count() }} soal</span>
                @if($soalGlobal->count() > 0)
                <a href="{{ route('admin.paket-builder.preview', $paket->id) }}"
                    class="btn btn-primary btn-sm" style="font-size:11px;padding:4px 10px">
                    <i class="fas fa-eye"></i> Preview
                </a>
                @endif
            </div>
        </div>
        <div style="max-height:72vh;overflow-y:auto">
            @forelse($soalGlobal as $s)
            @php
                $color = match($s->tipe_soal) {
                    'fill_missing_letters' => '#10b981',
                    'vocabulary'           => '#f59e0b',
                    'click_sentence'       => '#8b5cf6',
                    'email_reading'        => '#f59e0b',
                    default                => '#3b82f6',
                };
                $label = match($s->tipe_soal) {
                    'fill_missing_letters' => 'ML',
                    'vocabulary'           => 'VO',
                    'click_sentence'       => 'CS',
                    'email_reading'        => 'EM',
                    default                => 'MC',
                };
                // Untuk ML: tampilkan rentang nomor
                $isMissingLetters = $s->tipe_soal === 'fill_missing_letters';
                $modul = $s->modul;
                $nomorLabel = $isMissingLetters && $modul
                    ? $modul->nomor_soal_mulai.'–'.$modul->nomor_soal_selesai
                    : $s->nomor_dalam_paket;
            @endphp
            <div class="soal-row-g" id="soal-{{ $s->id }}">
                {{-- Nomor badge --}}
                <div class="no-badge" style="background:{{ $color }};
                    {{ $isMissingLetters ? 'border-radius:8px;width:auto;padding:0 8px;font-size:11px;min-width:30px' : '' }}">
                    {{ $nomorLabel }}
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:12.5px;white-space:nowrap;overflow:hidden;
                        text-overflow:ellipsis;color:rgba(255,255,255,.8)">
                        @if($isMissingLetters)
                            Missing Letters
                            @php preg_match_all('/\[([^\]]+)\]/', $s->fill_text??'', $mx); @endphp
                            <span style="color:#10b981;font-size:11px">({{ count($mx[1]) }} blank)</span>
                        @else
                            {{ mb_strimwidth($s->pertanyaan??'', 0, 45, '...') }}
                        @endif
                    </div>
                    <div style="font-size:11px;color:var(--muted)">
                        {{ $s->modul?->judul ?: ($s->modul?->tipe_modul??'—') }}
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:5px;flex-shrink:0">
                    <span style="background:{{ $color }}22;color:{{ $color }};
                        padding:2px 6px;border-radius:4px;font-size:10.5px;font-weight:700">
                        {{ $label }}
                    </span>
                    <button onclick="hapusSoal({{ $s->id }})"
                        style="background:none;border:none;color:var(--muted);
                        cursor:pointer;padding:3px 6px;font-size:12px" title="Hapus">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            @empty
            <div style="padding:28px;text-align:center;color:var(--muted);font-size:13px">
                Belum ada soal. Tambah modul dan input soal.
            </div>
            @endforelse
        </div>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script>
const CSRF = '{{ csrf_token() }}';
function hapusSoal(id) {
    if (!confirm('Hapus soal ini dari paket?')) return;
    fetch(`/admin/paket-builder/soal/${id}`, {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
    }).then(r=>r.json()).then(d=>{
        if (d.ok) document.getElementById('soal-'+id)?.remove();
    });
}
</script>
@endpush
