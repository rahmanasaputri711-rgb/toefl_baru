@extends('layouts.admin')
@section('title','Edit Paket #'.$paket->id)
@section('page-title','Builder Paket Soal')
@section('breadcrumb','Admin / Paket Soal / Edit')

@push('styles')
<style>
/* ── Progress bar validasi ── */
.val-bar { height:8px; background:rgba(255,255,255,.07); border-radius:4px; overflow:hidden; }
.val-fill { height:8px; border-radius:4px; transition:width .4s; }

/* ── Soal list item ── */
.soal-li {
    display:flex; align-items:flex-start; gap:10px;
    padding:11px 14px; border-radius:9px;
    border:1px solid rgba(255,255,255,.06);
    background:rgba(255,255,255,.025);
    margin-bottom:7px; transition:all .15s;
}
.soal-li:hover { border-color:rgba(26,86,219,.3); background:rgba(26,86,219,.04); }
.soal-li input[type=checkbox] { margin-top:2px; flex-shrink:0; accent-color:var(--accent); width:15px; height:15px; }
.soal-li-text { font-size:13px; line-height:1.5; flex:1; color:#cbd5e1; }
.soal-li-meta { display:flex; gap:6px; flex-wrap:wrap; margin-top:5px; }
.soal-meta-chip {
    font-size:10.5px; padding:2px 8px; border-radius:5px;
    background:rgba(255,255,255,.05); color:rgba(255,255,255,.4);
    border:1px solid rgba(255,255,255,.06);
}

/* ── Soal dalam paket ── */
.in-paket-item {
    display:flex; align-items:center; gap:10px;
    padding:10px 14px; border-radius:9px;
    border:1px solid rgba(255,255,255,.06);
    background:rgba(255,255,255,.02);
    margin-bottom:7px;
}
.in-paket-item:hover { border-color:rgba(239,68,68,.25); }

/* ── Tab panel ── */
.tab-btns { display:flex; gap:4px; border-bottom:1px solid var(--border); padding-bottom:0; margin-bottom:0; }
.tab-btn {
    padding:9px 18px; font-size:13px; font-weight:600;
    border:none; background:none; cursor:pointer; color:rgba(255,255,255,.4);
    border-bottom:2px solid transparent; margin-bottom:-1px; font-family:inherit;
    transition:all .15s;
}
.tab-btn:hover { color:rgba(255,255,255,.7); }
.tab-btn.active { color:var(--accent); border-bottom-color:var(--accent); }
.tab-panel { display:none; }
.tab-panel.active { display:block; }

/* ── Validasi badge ── */
.vld-row { display:flex; align-items:center; gap:10px; margin-bottom:10px; }
.vld-lbl { font-size:12px; color:rgba(255,255,255,.45); width:90px; }
.vld-count { font-size:14px; font-weight:700; min-width:50px; }
.vld-target { font-size:11px; color:rgba(255,255,255,.3); }
</style>
@endpush

@section('content')
<div class="grid-2" style="gap:18px;align-items:start">

    {{-- ── KOLOM KIRI: Info paket + soal dalam paket ── --}}
    <div>

        {{-- Kartu validasi --}}
        <div class="card" style="margin-bottom:18px">
            <div class="card-header">
                <h3>
                    <i class="fas fa-boxes" style="color:var(--accent);margin-right:8px"></i>
                    {{ $paket->nama }}
                </h3>
                @if($paket->status==='valid')
                <span class="badge badge-green"><i class="fas fa-check" style="font-size:9px"></i> Valid</span>
                @elseif($paket->status==='invalid')
                <span class="badge badge-red"><i class="fas fa-times" style="font-size:9px"></i> Invalid</span>
                @else
                <span class="badge badge-gray">Draft</span>
                @endif
            </div>
            <div class="card-body">
                @php
                    $tgt = \App\Models\PaketSoal::TARGET;
                    $sections = [
                        ['listening', '🎧 Listening',  $paket->jumlah_listening, $tgt['listening'], '#fdba74', 'rgba(234,88,12,.6)'],
                        ['structure', '✏️ Structure',  $paket->jumlah_structure,  $tgt['structure'],  '#fde68a', 'rgba(217,119,6,.6)'],
                        ['reading',   '📖 Reading',    $paket->jumlah_reading,    $tgt['reading'],    '#93c5fd', 'rgba(26,86,219,.6)'],
                    ];
                @endphp

                @foreach($sections as [$kat,$lbl,$jumlah,$target,$clr,$bgClr])
                @php
                    $pct    = $target > 0 ? min(100, round($jumlah/$target*100)) : 0;
                    $isOk   = $jumlah === $target;
                    $barClr = $jumlah > $target ? '#f87171' : ($isOk ? '#4ade80' : $clr);
                @endphp
                <div class="vld-row">
                    <div class="vld-lbl">{{ $lbl }}</div>
                    <div style="flex:1">
                        <div class="val-bar">
                            <div class="val-fill" style="background:{{ $barClr }};width:{{ $pct }}%"></div>
                        </div>
                    </div>
                    <div class="vld-count" style="color:{{ $barClr }}">{{ $jumlah }}</div>
                    <div class="vld-target">/ {{ $target }}</div>
                    @if($isOk)<i class="fas fa-check-circle" style="color:#4ade80;font-size:14px"></i>
                    @else<i class="fas fa-circle" style="color:rgba(255,255,255,.15);font-size:14px"></i>@endif
                </div>
                @endforeach

                <div style="display:flex;gap:8px;margin-top:14px">
                    <form action="{{ route('admin.paket.validate', $paket->id) }}" method="POST" style="flex:1">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-block btn-sm">
                            <i class="fas fa-check-double"></i> Validasi Ulang
                        </button>
                    </form>
                    <a href="{{ route('admin.paket.index') }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Soal yang sudah masuk paket --}}
        <div class="card">
            <div class="card-header">
                <h3>Soal Dalam Paket
                    <span class="badge badge-blue" style="margin-left:6px">{{ $paket->soal->count() }}</span>
                </h3>
            </div>
            <div class="card-body" style="padding:14px">

                {{-- Tab per kategori --}}
                <div class="tab-btns">
                    @foreach([
                        ['L','listening','🎧 Listening','badge-orange'],
                        ['S','structure','✏️ Structure','badge-gold'],
                        ['R','reading',  '📖 Reading',  'badge-blue'],
                    ] as [$code,$kat,$lbl,$badgeClass])
                    <button class="tab-btn {{ $loop->first?'active':'' }}" onclick="switchTab('{{ $kat }}')">
                        {{ $lbl }}
                        <span class="badge {{ $badgeClass }}" style="font-size:10px;margin-left:5px">
                            {{ $paket->soal->where('kategori',$kat)->count() }}
                        </span>
                    </button>
                    @endforeach
                </div>

                @foreach(['listening','structure','reading'] as $kat)
                <div class="tab-panel {{ $loop->first?'active':'' }}" id="panel-in-{{ $kat }}"
                    style="padding-top:12px;max-height:380px;overflow-y:auto">
                    @php $soalInKat = $paket->soal->where('kategori',$kat)->sortBy('pivot.urutan'); @endphp
                    @forelse($soalInKat as $s)
                    <div class="in-paket-item">
                        <div style="font-size:12.5px;flex:1;line-height:1.5;color:#cbd5e1">
                            {{ Str::limit($s->pertanyaan, 70) }}
                            <div class="soal-li-meta">
                                @if($s->part)<span class="soal-meta-chip">Part {{ $s->part }}</span>@endif
                                <span class="soal-meta-chip">{{ ucfirst($s->tingkat_kesulitan) }}</span>
                            </div>
                        </div>
                        <form action="{{ route('admin.paket.removeSoal', $paket->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="soal_id" value="{{ $s->id }}">
                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus dari paket"
                                onclick="return confirm('Hapus soal ini dari paket?')">
                                <i class="fas fa-minus"></i>
                            </button>
                        </form>
                    </div>
                    @empty
                    <div style="text-align:center;padding:28px;color:rgba(255,255,255,.25);font-size:13px">
                        <i class="fas fa-inbox" style="font-size:24px;display:block;margin-bottom:8px"></i>
                        Belum ada soal {{ $kat }} dalam paket
                    </div>
                    @endforelse
                </div>
                @endforeach

            </div>
        </div>
    </div>

    {{-- ── KOLOM KANAN: Browser bank soal ── --}}
    <div>
        <div class="card" style="position:sticky;top:20px">
            <div class="card-header">
                <h3><i class="fas fa-plus-circle" style="color:var(--accent);margin-right:8px"></i>Tambah Soal dari Bank</h3>
            </div>
            <div class="card-body" style="padding:14px">

                {{-- Filter bank soal --}}
                <form method="GET" action="{{ route('admin.paket.edit', $paket->id) }}"
                    style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px">
                    <select name="f_kat" class="form-control" style="width:140px" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        <option value="listening" {{ request('f_kat')==='listening'?'selected':'' }}>🎧 Listening</option>
                        <option value="structure" {{ request('f_kat')==='structure'?'selected':'' }}>✏️ Structure</option>
                        <option value="reading"   {{ request('f_kat')==='reading'  ?'selected':'' }}>📖 Reading</option>
                    </select>
                    <select name="f_part" class="form-control" style="width:120px" onchange="this.form.submit()">
                        <option value="">Semua Part</option>
                        <option value="A" {{ request('f_part')==='A'?'selected':'' }}>Part A</option>
                        <option value="B" {{ request('f_part')==='B'?'selected':'' }}>Part B</option>
                        <option value="C" {{ request('f_part')==='C'?'selected':'' }}>Part C</option>
                    </select>
                    <select name="f_lvl" class="form-control" style="width:120px" onchange="this.form.submit()">
                        <option value="">Semua Level</option>
                        <option value="easy"   {{ request('f_lvl')==='easy'  ?'selected':'' }}>🟢 Easy</option>
                        <option value="medium" {{ request('f_lvl')==='medium'?'selected':'' }}>🟡 Medium</option>
                        <option value="hard"   {{ request('f_lvl')==='hard'  ?'selected':'' }}>🔴 Hard</option>
                    </select>
                    <select name="f_pakai" class="form-control" style="width:140px" onchange="this.form.submit()">
                        <option value="">Semua Soal</option>
                        <option value="0" {{ request('f_pakai')==='0'?'selected':'' }}>Belum Dipakai</option>
                        <option value="1" {{ request('f_pakai')==='1'?'selected':'' }}>Sudah Dipakai</option>
                    </select>
                </form>

                {{-- Form tambah soal --}}
                <form action="{{ route('admin.paket.addSoal', $paket->id) }}" method="POST" id="add-form">
                    @csrf
                    <div style="max-height:460px;overflow-y:auto;margin-bottom:12px;
                        border:1px solid rgba(255,255,255,.06);border-radius:9px;padding:10px">

                        {{-- Listening --}}
                        @if($bankListening->isNotEmpty() && (!request('f_kat') || request('f_kat')==='listening'))
                        <div style="font-size:10.5px;font-weight:700;color:#fdba74;text-transform:uppercase;
                            letter-spacing:.8px;padding:6px 4px;margin-bottom:6px">🎧 Listening</div>
                        @foreach($bankListening as $s)
                        <label class="soal-li">
                            <input type="checkbox" name="soal_ids[]" value="{{ $s->id }}">
                            <div>
                                <div class="soal-li-text">{{ Str::limit($s->pertanyaan, 80) }}</div>
                                <div class="soal-li-meta">
                                    @if($s->part)<span class="soal-meta-chip">Part {{ $s->part }}</span>@endif
                                    <span class="soal-meta-chip">{{ ucfirst($s->tingkat_kesulitan) }}</span>
                                    @if($s->pakai_count > 0)
                                    <span class="soal-meta-chip" style="color:#fbbf24">
                                        <i class="fas fa-redo" style="font-size:9px"></i> {{ $s->pakai_count }}x dipakai
                                    </span>
                                    @endif
                                    @if($s->audio_url)<span class="soal-meta-chip" style="color:#fdba74"><i class="fas fa-volume-up" style="font-size:9px"></i></span>@endif
                                </div>
                            </div>
                        </label>
                        @endforeach
                        @endif

                        {{-- Structure --}}
                        @if($bankStructure->isNotEmpty() && (!request('f_kat') || request('f_kat')==='structure'))
                        <div style="font-size:10.5px;font-weight:700;color:#fde68a;text-transform:uppercase;
                            letter-spacing:.8px;padding:6px 4px;margin-bottom:6px;margin-top:6px">✏️ Structure</div>
                        @foreach($bankStructure as $s)
                        <label class="soal-li">
                            <input type="checkbox" name="soal_ids[]" value="{{ $s->id }}">
                            <div>
                                <div class="soal-li-text">{{ Str::limit($s->pertanyaan, 80) }}</div>
                                <div class="soal-li-meta">
                                    @if($s->part)<span class="soal-meta-chip">Part {{ $s->part }}</span>@endif
                                    <span class="soal-meta-chip">{{ ucfirst($s->tingkat_kesulitan) }}</span>
                                    @if($s->pakai_count > 0)
                                    <span class="soal-meta-chip" style="color:#fbbf24">{{ $s->pakai_count }}x dipakai</span>
                                    @endif
                                </div>
                            </div>
                        </label>
                        @endforeach
                        @endif

                        {{-- Reading --}}
                        @if($bankReading->isNotEmpty() && (!request('f_kat') || request('f_kat')==='reading'))
                        <div style="font-size:10.5px;font-weight:700;color:#93c5fd;text-transform:uppercase;
                            letter-spacing:.8px;padding:6px 4px;margin-bottom:6px;margin-top:6px">📖 Reading</div>
                        @foreach($bankReading as $s)
                        <label class="soal-li">
                            <input type="checkbox" name="soal_ids[]" value="{{ $s->id }}">
                            <div>
                                <div class="soal-li-text">{{ Str::limit($s->pertanyaan, 80) }}</div>
                                <div class="soal-li-meta">
                                    @if($s->passage_teks)<span class="soal-meta-chip" style="color:#93c5fd"><i class="fas fa-align-left" style="font-size:9px"></i> Passage</span>@endif
                                    <span class="soal-meta-chip">{{ ucfirst($s->tingkat_kesulitan) }}</span>
                                    @if($s->pakai_count > 0)
                                    <span class="soal-meta-chip" style="color:#fbbf24">{{ $s->pakai_count }}x dipakai</span>
                                    @endif
                                </div>
                            </div>
                        </label>
                        @endforeach
                        @endif

                        @if($bankListening->isEmpty() && $bankStructure->isEmpty() && $bankReading->isEmpty())
                        <div style="text-align:center;padding:28px;color:rgba(255,255,255,.25);font-size:13px">
                            <i class="fas fa-check-circle" style="font-size:24px;color:#4ade80;display:block;margin-bottom:8px"></i>
                            Semua soal tersedia sudah masuk paket atau tidak ada soal yang sesuai filter.
                        </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary btn-block"
                        onclick="if(!document.querySelectorAll('#add-form input[type=checkbox]:checked').length){event.preventDefault();alert('Pilih minimal 1 soal.');}">
                        <i class="fas fa-plus"></i> Tambahkan Soal Terpilih
                    </button>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function switchTab(kat) {
    document.querySelectorAll('.tab-btn').forEach((b,i) => b.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    const tabs = ['listening','structure','reading'];
    const idx  = tabs.indexOf(kat);
    document.querySelectorAll('.tab-btn')[idx].classList.add('active');
    document.getElementById('panel-in-'+kat).classList.add('active');
}
</script>
@endpush
