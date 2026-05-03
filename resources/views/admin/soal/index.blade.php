@extends('layouts.admin')
@section('title','Bank Soal')
@section('page-title','Bank Soal')
@section('breadcrumb','Admin / Bank Soal')

@push('styles')
<style>
/* Preview panel */
.pv-backdrop{display:none;position:fixed;inset:0;z-index:950;background:rgba(5,12,30,.65);backdrop-filter:blur(6px);align-items:flex-start;justify-content:flex-end;padding:12px 12px 12px 0}
.pv-backdrop.open{display:flex}
.pv-panel{width:480px;max-width:92vw;height:calc(100vh - 24px);background:#0d1e3a;border:1px solid rgba(99,160,255,.15);border-radius:16px;display:flex;flex-direction:column;overflow:hidden;box-shadow:-24px 0 80px rgba(0,0,0,.5),0 0 0 1px rgba(99,160,255,.08);animation:pvIn .25s cubic-bezier(.22,1,.36,1)}
@keyframes pvIn{from{opacity:0;transform:translateX(36px) scale(.97)}to{opacity:1;transform:none}}
.pv-head{padding:13px 16px 11px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:10px;flex-shrink:0;background:rgba(255,255,255,.02)}
.pv-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:20px;font-size:11.5px;font-weight:700;white-space:nowrap;flex-shrink:0}
.pv-close{width:28px;height:28px;border-radius:7px;flex-shrink:0;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.09);color:rgba(255,255,255,.4);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;transition:all .15s;margin-left:auto}
.pv-close:hover{background:rgba(239,68,68,.18);color:#f87171;border-color:rgba(239,68,68,.3)}
.pv-body{flex:1;overflow-y:auto;padding:18px;scroll-behavior:smooth}
.pv-body::-webkit-scrollbar{width:4px}
.pv-body::-webkit-scrollbar-thumb{background:rgba(99,160,255,.2);border-radius:10px}
.pv-passage{background:linear-gradient(135deg,rgba(26,86,219,.12),rgba(59,130,246,.06));border-left:3px solid rgba(99,160,255,.45);border-radius:0 10px 10px 0;padding:13px 15px;margin-bottom:16px;font-size:13px;line-height:1.8;color:#94a3b8;max-height:160px;overflow-y:auto}
.pv-q{font-size:15px;font-weight:700;color:#f1f5f9;line-height:1.65;margin-bottom:16px;padding:14px 15px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:11px}
.pv-opts{display:flex;flex-direction:column;gap:7px;margin-bottom:14px}
.pv-opt{display:flex;align-items:flex-start;gap:10px;padding:10px 13px;border-radius:9px;border:1.5px solid rgba(255,255,255,.07);background:rgba(255,255,255,.02)}
.pv-opt.correct{border-color:rgba(34,197,94,.35);background:linear-gradient(135deg,rgba(22,163,74,.1),rgba(16,185,129,.06))}
.pv-opt-badge{width:26px;height:26px;border-radius:6px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:11.5px;font-weight:800;background:rgba(255,255,255,.07);color:rgba(255,255,255,.35)}
.pv-opt.correct .pv-opt-badge{background:#16a34a;color:#fff}
.pv-opt-txt{font-size:13.5px;color:#cbd5e1;flex:1;line-height:1.45;padding-top:2px}
.pv-opt.correct .pv-opt-txt{color:#dcfce7;font-weight:500}
.pv-explain{background:rgba(99,102,241,.09);border:1px solid rgba(99,102,241,.2);border-radius:10px;padding:13px 14px;margin-bottom:14px}
.pv-meta{display:flex;gap:7px;flex-wrap:wrap;padding-top:13px;border-top:1px solid rgba(255,255,255,.07)}
.pv-chip{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:6px;font-size:11px;color:rgba(255,255,255,.4);background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.07)}
.pv-skeleton{display:flex;flex-direction:column;gap:10px}
.pv-skel{border-radius:7px;background:linear-gradient(90deg,rgba(255,255,255,.04) 0%,rgba(255,255,255,.09) 50%,rgba(255,255,255,.04) 100%);background-size:200% 100%;animation:sk .9s ease-in-out infinite}
@keyframes sk{to{background-position:-200% 0}}
.pv-foot{padding:11px 16px;border-top:1px solid rgba(255,255,255,.07);display:flex;align-items:center;justify-content:space-between;gap:10px;flex-shrink:0;background:rgba(0,0,0,.18)}
.pv-nav{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;font-size:12.5px;font-weight:600;cursor:pointer;border:1.5px solid rgba(255,255,255,.1);background:rgba(255,255,255,.05);color:rgba(255,255,255,.6);transition:all .15s;font-family:inherit}
.pv-nav:hover:not(:disabled){background:rgba(255,255,255,.1);color:#fff;border-color:rgba(255,255,255,.22)}
.pv-nav:disabled{opacity:.28;cursor:not-allowed}
.pv-edit{display:inline-flex;align-items:center;gap:6px;padding:7px 15px;border-radius:8px;font-size:12.5px;font-weight:600;text-decoration:none;background:rgba(245,158,11,.14);border:1.5px solid rgba(245,158,11,.3);color:#fbbf24;transition:all .15s}
.pv-edit:hover{background:rgba(245,158,11,.25);color:#fde68a}
.soal-row{cursor:pointer;transition:background .1s}
.soal-row:hover>td{background:rgba(26,86,219,.07)!important}
.soal-row.pv-active>td{background:rgba(26,86,219,.14)!important;border-left:3px solid #3b82f6}
.hint-bar{background:rgba(26,86,219,.06);border-bottom:1px solid rgba(26,86,219,.1);padding:6px 18px;font-size:11.5px;color:rgba(255,255,255,.35);display:flex;align-items:center;gap:8px}
.kb{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);border-radius:4px;padding:1px 5px;font-size:10px;font-family:monospace}
/* Stats bar */
.stat-strip{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:18px}
.stat-pill{display:flex;align-items:center;gap:8px;padding:10px 16px;background:var(--card-bg);border:1px solid var(--border);border-radius:10px;flex:1;min-width:140px}
.stat-pill .sp-num{font-size:22px;font-weight:800;color:var(--text);line-height:1}
.stat-pill .sp-lbl{font-size:11px;color:var(--text-muted)}
</style>
@endpush

@section('content')

{{-- Stats strip --}}
<div class="stat-strip">
    @foreach([
        ['fas fa-database','si-blue',$total,'Total Soal'],
        ['fas fa-headphones-alt','si-orange',$listening,'Listening'],
        ['fas fa-pen-nib','si-gold',$structure,'Structure'],
        ['fas fa-book-open','si-blue',$reading,'Reading'],
        ['fas fa-circle','si-green',$aktif,'Aktif'],
        ['fas fa-box-open','si-purple',$belum_pakai,'Belum Dipakai'],
    ] as [$ico,$cls,$num,$lbl])
    <div class="stat-card" style="flex:1;min-width:120px">
        <div class="stat-icon {{ $cls }}"><i class="{{ $ico }}"></i></div>
        <div><div class="stat-val">{{ $num }}</div><div class="stat-label">{{ $lbl }}</div></div>
    </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-database" style="color:var(--accent);margin-right:8px"></i>Bank Soal</h3>
        <div style="display:flex;gap:8px">
            <a href="{{ route('admin.grup.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-object-group"></i> Grup Soal
            </a>
            <a href="{{ route('admin.paket.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-box"></i> Paket Soal
            </a>
            <a href="{{ route('admin.soal.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Soal
            </a>
        </div>
    </div>

    {{-- Filter bar --}}
    <div class="card-body" style="padding-bottom:0;border-bottom:1px solid var(--border)">
        <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;padding-bottom:14px">
            <div style="position:relative;flex:1;min-width:180px">
                <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:12px"></i>
                <input type="text" name="search" class="form-control" style="padding-left:36px"
                    placeholder="Cari pertanyaan..." value="{{ request('search') }}">
            </div>

            {{-- Kategori/Section --}}
            <select name="kategori" class="form-control" style="width:155px" onchange="syncPartOptions(this.value)">
                <option value="">Semua Section</option>
                <option value="listening" {{ request('kategori')=='listening'?'selected':'' }}>🎧 Listening</option>
                <option value="structure" {{ request('kategori')=='structure'?'selected':'' }}>✏️ Structure</option>
                <option value="reading"   {{ request('kategori')=='reading'  ?'selected':'' }}>📖 Reading</option>
            </select>

            {{-- Part --}}
            <select name="part" class="form-control" style="width:130px" id="part-filter">
                <option value="">Semua Part</option>
                <option value="A" {{ request('part')=='A'?'selected':'' }}>Part A</option>
                <option value="B" {{ request('part')=='B'?'selected':'' }}>Part B</option>
                <option value="C" {{ request('part')=='C'?'selected':'' }}>Part C</option>
            </select>

            {{-- Kesulitan --}}
            <select name="kesulitan" class="form-control" style="width:140px">
                <option value="">Semua Level</option>
                <option value="easy"   {{ request('kesulitan')=='easy'  ?'selected':'' }}>🟢 Easy</option>
                <option value="medium" {{ request('kesulitan')=='medium'?'selected':'' }}>🟡 Medium</option>
                <option value="hard"   {{ request('kesulitan')=='hard'  ?'selected':'' }}>🔴 Hard</option>
            </select>

            {{-- Penggunaan --}}
            <select name="pakai" class="form-control" style="width:155px">
                <option value="">Semua Penggunaan</option>
                <option value="belum" {{ request('pakai')=='belum'?'selected':'' }}>📦 Belum Dipakai</option>
                <option value="sudah" {{ request('pakai')=='sudah'?'selected':'' }}>✅ Sudah Dipakai</option>
            </select>

            {{-- Status --}}
            <select name="aktif" class="form-control" style="width:135px">
                <option value="">Semua Status</option>
                <option value="1" {{ request('aktif')==='1'?'selected':'' }}>● Aktif</option>
                <option value="0" {{ request('aktif')==='0'?'selected':'' }}>● Nonaktif</option>
            </select>

            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('admin.soal.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-times"></i> Reset</a>
        </form>
    </div>

    {{-- Hint bar --}}
    <div class="hint-bar">
        <i class="fas fa-hand-pointer" style="color:rgba(99,160,255,.5)"></i>
        Klik baris untuk preview · <span class="kb">←</span><span class="kb">→</span> navigasi · <span class="kb">Esc</span> tutup
    </div>

    {{-- Tabel --}}
    <table class="tbl">
        <thead>
            <tr>
                <th width="50">#</th>
                <th>Pertanyaan</th>
                <th width="115">Section</th>
                <th width="90">Part</th>
                <th width="88">Level</th>
                <th width="68">Audio</th>
                <th width="80">Pakai</th>
                <th width="76">Part</th>
                <th width="88">Status</th>
                <th width="108">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($soal as $i => $s)
        @php
            $nomor = $soal->firstItem() + $i;
            $kClr  = $s->kategori=='reading'?'#1a56db':($s->kategori=='listening'?'#ea580c':'#d97706');
            $kBg   = $s->kategori=='reading'?'#eff6ff':($s->kategori=='listening'?'#fff7ed':'#fffbeb');
            $kIco  = $s->kategori=='reading'?'book-open':($s->kategori=='listening'?'headphones-alt':'pen-nib');
            $lvClr = $s->tingkat_kesulitan=='easy'?'#16a34a':($s->tingkat_kesulitan=='hard'?'#dc2626':'#d97706');
        @endphp
        <tr class="soal-row" id="row-{{ $s->id }}"
            data-id="{{ $s->id }}" data-nomor="{{ $nomor }}"
            onclick="openPreview({{ $s->id }}, {{ $nomor }})">
            <td>
                <div style="width:30px;height:30px;border-radius:8px;background:rgba(26,86,219,.12);
                    color:var(--accent);display:flex;align-items:center;justify-content:center;
                    font-size:12px;font-weight:700">{{ $nomor }}</div>
            </td>
            <td>
                <div style="font-size:13.5px;max-width:320px;overflow:hidden;text-overflow:ellipsis;
                    white-space:nowrap" title="{{ $s->pertanyaan }}">{{ Str::limit($s->pertanyaan,65) }}</div>
                @if($s->grupSoal)
                <span style="font-size:10px;color:#a5b4fc;margin-top:2px;display:inline-flex;align-items:center;gap:3px">
                    <i class="fas fa-object-group" style="font-size:9px"></i>
                    {{ Str::limit($s->grupSoal->judul ?? 'Grup #'.$s->grup_soal_id, 30) }}
                </span>
                @elseif($s->passage_teks)
                <span style="font-size:10px;color:var(--accent);margin-top:2px;display:inline-block">
                    <i class="fas fa-align-left" style="font-size:9px"></i> Passage
                </span>
                @endif
            </td>
            <td>
                <span style="background:{{ $kBg }};color:{{ $kClr }};padding:3px 10px;
                    border-radius:20px;font-size:11.5px;font-weight:600;white-space:nowrap;
                    display:inline-flex;align-items:center;gap:4px">
                    <i class="fas fa-{{ $kIco }}" style="font-size:10px"></i>{{ ucfirst($s->kategori) }}
                </span>
            </td>
            <td>
                @if($s->part)
                <span style="font-size:12px;font-weight:600;color:var(--text-muted)">Part {{ $s->part }}</span>
                @else
                <span style="color:var(--border);font-size:12px">—</span>
                @endif
            </td>
            <td>
                <span style="font-size:12.5px;font-weight:600;color:{{ $lvClr }}">
                    {{ ucfirst($s->tingkat_kesulitan) }}
                </span>
            </td>
            <td style="text-align:center">
                @if($s->audio_url)
                <i class="fas fa-volume-up" style="color:#16a34a;font-size:17px" title="{{ basename($s->audio_url) }}"></i>
                @else
                <i class="fas fa-volume-off" style="color:var(--border);font-size:15px"></i>
                @endif
            </td>
            <td style="text-align:center">
                @if($s->pakai_count > 0)
                <span class="badge badge-blue" style="font-size:11px" title="{{ $s->pakai_count }}x dipakai">
                    ✓ {{ $s->pakai_count }}x
                </span>
                @else
                <span class="badge badge-gray" style="font-size:11px">Belum</span>
                @endif
            </td>
            <td onclick="event.stopPropagation()">
                <form action="{{ route('admin.soal.toggle', $s->id) }}" method="POST" style="display:inline">
                    @csrf @method('PATCH')
                    <button type="submit" style="background:none;border:none;cursor:pointer;padding:0"
                        title="{{ $s->is_aktif?'Nonaktifkan':'Aktifkan' }}">
                        @if($s->is_aktif)
                        <span class="badge badge-green" style="font-size:11px"><i class="fas fa-circle" style="font-size:7px"></i> Aktif</span>
                        @else
                        <span class="badge badge-gray" style="font-size:11px"><i class="fas fa-circle" style="font-size:7px"></i> Nonaktif</span>
                        @endif
                    </button>
                </form>
            </td>
            <td onclick="event.stopPropagation()">
                <div style="display:flex;gap:5px">
                    <a href="{{ route('admin.soal.edit', $s->id) }}" class="btn btn-warning btn-sm" title="Edit">
                        <i class="fas fa-pen"></i>
                    </a>
                    <form action="{{ route('admin.soal.destroy', $s->id) }}" method="POST"
                        onsubmit="return confirm('Hapus soal #{{ $nomor }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="9">
            <div class="empty-state"><i class="fas fa-database"></i>
            <p>Tidak ada soal ditemukan. <a href="{{ route('admin.soal.create') }}" style="color:var(--accent)">Tambah soal</a></p>
            </div>
        </td></tr>
        @endforelse
        </tbody>
    </table>

    <div style="padding:14px 20px;display:flex;align-items:center;justify-content:space-between;border-top:1px solid var(--border);font-size:13px">
        <div style="color:var(--text-muted)">{{ $soal->total() }} soal</div>
        {{ $soal->withQueryString()->links() }}
    </div>
</div>

{{-- ═══ FLOATING PREVIEW PANEL ════════════════════════════ --}}
<div class="pv-backdrop" id="pv-backdrop">
<div class="pv-panel">
    <div class="pv-head">
        <div class="pv-badge" id="pv-badge"></div>
        <div style="flex:1;min-width:0">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.25)">Preview Soal</div>
        </div>
        <span style="font-family:monospace;font-size:11px;color:rgba(255,255,255,.25)" id="pv-id">#—</span>
        <button class="pv-close" onclick="pvClose()" title="Esc"><i class="fas fa-times"></i></button>
    </div>
    <div class="pv-body" id="pv-body">
        <div class="pv-skeleton" id="pv-loading">
            <div class="pv-skel" style="height:11px;width:32%"></div>
            <div class="pv-skel" style="height:80px"></div>
            <div class="pv-skel" style="height:52px"></div>
            <div class="pv-skel" style="height:38px"></div>
            <div class="pv-skel" style="height:38px"></div>
            <div class="pv-skel" style="height:38px"></div>
            <div class="pv-skel" style="height:38px"></div>
        </div>
        <div id="pv-content" style="display:none"></div>
        <div id="pv-error" style="display:none;text-align:center;padding:40px 20px">
            <i class="fas fa-exclamation-circle" style="font-size:32px;color:#f87171;display:block;margin-bottom:12px"></i>
            <div style="color:#f87171;margin-bottom:14px">Gagal memuat soal.</div>
            <button onclick="pvRetry()" class="pv-nav"><i class="fas fa-redo"></i> Coba Lagi</button>
        </div>
    </div>
    <div class="pv-foot">
        <div style="display:flex;align-items:center;gap:8px">
            <button class="pv-nav" id="pv-prev" onclick="pvGo(-1)" disabled><i class="fas fa-chevron-left"></i> Prev</button>
            <button class="pv-nav" id="pv-next" onclick="pvGo(1)">Next <i class="fas fa-chevron-right"></i></button>
            <span style="font-size:11px;color:rgba(255,255,255,.25);font-family:monospace" id="pv-counter"></span>
        </div>
        <div style="display:flex;align-items:center;gap:8px">
            <div style="font-size:10px;color:rgba(255,255,255,.18)"><span class="kb">←</span><span class="kb">→</span><span class="kb">Esc</span></div>
            <a href="#" id="pv-edit" class="pv-edit" target="_blank"><i class="fas fa-pen"></i> Edit</a>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
const PV_IDS   = @json($soal->pluck('id')->values());
const PV_API   = '{{ url("admin/soal") }}';
const PV_CSRF  = '{{ csrf_token() }}';
let pvIdx = 0, pvCurId = null, pvAudio = null;

const KAT = {
    reading:   {bg:'rgba(26,86,219,.22)',color:'#93c5fd',icon:'book-open'},
    listening: {bg:'rgba(234,88,12,.22)', color:'#fdba74',icon:'headphones-alt'},
    structure: {bg:'rgba(217,119,6,.22)', color:'#fde68a',icon:'pen-nib'},
};

function openPreview(id, nomor) {
    pvIdx  = PV_IDS.indexOf(id); if(pvIdx<0) pvIdx=0;
    pvCurId = id;
    pvKillAudio(); pvReset();
    document.getElementById('pv-backdrop').classList.add('open');
    pvHighlight(id); pvFetch(id);
}
function pvClose() {
    pvKillAudio();
    document.getElementById('pv-backdrop').classList.remove('open');
    document.querySelectorAll('.soal-row').forEach(r=>r.classList.remove('pv-active'));
    pvCurId=null;
}
function pvGo(d) {
    const ni=pvIdx+d; if(ni<0||ni>=PV_IDS.length) return;
    pvIdx=ni; pvCurId=PV_IDS[pvIdx]; pvKillAudio(); pvReset();
    pvHighlight(pvCurId); pvFetch(pvCurId);
}
function pvRetry() { document.getElementById('pv-error').style.display='none'; document.getElementById('pv-loading').style.display='flex'; pvFetch(pvCurId); }

async function pvFetch(id) {
    document.getElementById('pv-id').textContent     = '#'+id;
    document.getElementById('pv-counter').textContent = (pvIdx+1)+' / '+PV_IDS.length;
    document.getElementById('pv-prev').disabled       = pvIdx<=0;
    document.getElementById('pv-next').disabled       = pvIdx>=PV_IDS.length-1;
    document.getElementById('pv-edit').href            = PV_API+'/'+id+'/edit';
    try {
        const r = await fetch(PV_API+'/'+id+'/preview',{headers:{'Accept':'application/json','X-CSRF-TOKEN':PV_CSRF}});
        if(!r.ok) throw new Error('HTTP '+r.status);
        pvRender(await r.json());
    } catch(e) {
        document.getElementById('pv-loading').style.display='none';
        document.getElementById('pv-error').style.display='block';
    }
}

function pvRender(d) {
    const kc = KAT[d.kategori]||KAT.reading;
    const bdg = document.getElementById('pv-badge');
    bdg.style.cssText=`background:${kc.bg};color:${kc.color}`;
    bdg.innerHTML=`<i class="fas fa-${kc.icon} fa-fw" style="font-size:10px"></i>${cap(d.kategori)}${d.part_label&&d.part_label!=='—'?' · '+d.part_label:''}`;

    let h='';
    if(d.passage_teks) h+=`<div style="font-size:10px;font-weight:700;color:rgba(99,160,255,.55);margin-bottom:6px;text-transform:uppercase;letter-spacing:.8px"><i class="fas fa-align-left"></i> Passage</div><div class="pv-passage">${esc(d.passage_teks)}</div>`;
    if(d.audio_url) h+=`<div class="toefl-audio-wrap" style="margin-bottom:14px"><div class="tap-label" style="color:rgba(251,146,60,.7);font-size:10.5px;margin-bottom:8px"><i class="fas fa-headphones-alt"></i> Audio Listening — preview bebas</div><div class="tap-bar" style="max-width:100%"><button type="button" class="tap-play-btn" id="btn-pvaud" onclick="tapToggle('pvaud')"><span class="tap-play-triangle" id="icon-pvaud"></span></button><div class="tap-track-outer" id="track-pvaud" onclick="tapSeek(event,'pvaud')"><div class="tap-track-inner"><div class="tap-track-fill" id="fill-pvaud" style="width:0%"></div></div><div class="tap-thumb" id="thumb-pvaud" style="left:0%"></div></div><span class="tap-time" id="time-pvaud">00:00</span><button type="button" class="tap-vol-btn" onclick="tapToggleMute('pvaud')"><i class="fas fa-volume-up tap-vol-icon" id="volicon-pvaud"></i></button><audio id="aud-pvaud" data-mode="admin" preload="metadata" src="${d.audio_url}" oncanplay="tapOnCanPlay('pvaud')" ontimeupdate="tapOnTimeUpdate('pvaud')" onended="tapOnEnded('pvaud')"></audio></div><div class="tap-status" id="status-pvaud">Klik ▶ untuk memutar</div></div>`;
    h+=`<div class="pv-q">${esc(d.pertanyaan)}</div>`;
    h+=`<div class="pv-opts">`;
    ['a','b','c','d'].forEach(o=>{
        const ok=d.jawaban_benar===o;
        h+=`<div class="pv-opt${ok?' correct':''}"><div class="pv-opt-badge">${o.toUpperCase()}</div><span class="pv-opt-txt">${esc(d['pilihan_'+o]||'—')}</span>${ok?'<i class="fas fa-check-circle" style="color:#4ade80;font-size:14px;flex-shrink:0;margin-top:3px"></i>':''}</div>`;
    });
    h+=`</div>`;
    if(d.pembahasan) h+=`<div class="pv-explain"><div style="font-size:10px;font-weight:700;color:#818cf8;text-transform:uppercase;letter-spacing:.8px;margin-bottom:6px"><i class="fas fa-lightbulb"></i> Pembahasan</div><div style="font-size:13.5px;color:#a5b4fc;line-height:1.65">${esc(d.pembahasan)}</div></div>`;
    h+=`<div class="pv-meta">`;
    h+=pvChip(kc.icon,cap(d.kategori),kc.color);
    if(d.part_label&&d.part_label!=='—') h+=pvChip('bookmark',d.part_label,'#c4b5fd');
    const lc={'easy':'#4ade80','medium':'#fbbf24','hard':'#f87171'};
    h+=pvChip('signal',cap(d.tingkat_kesulitan),lc[d.tingkat_kesulitan]||'#fff');
    if(d.untuk_tes_full) h+=pvChip('graduation-cap','Tes Full','#93c5fd');
    if(d.pakai_count>0)  h+=pvChip('check-circle',d.pakai_count+'x dipakai','#4ade80');
    else                 h+=pvChip('box-open','Belum dipakai','#9ca3af');
    if(d.audio_url)      h+=pvChip('headphones-alt','Audio','#fdba74');
    if(d.passage_teks)   h+=pvChip('align-left','Passage','#a5b4fc');
    if(d.grup)           h+=pvChip('object-group',d.grup,'#c4b5fd');
    h+=`</div>`;

    document.getElementById('pv-content').innerHTML=h;
    const av=document.getElementById('aud-pvaud'); if(av) pvAudio=av;
    document.getElementById('pv-loading').style.display='none';
    document.getElementById('pv-content').style.display='block';
    document.getElementById('pv-body').scrollTop=0;
}

function pvReset() {
    document.getElementById('pv-loading').style.display='flex';
    document.getElementById('pv-content').style.display='none';
    document.getElementById('pv-content').innerHTML='';
    document.getElementById('pv-error').style.display='none';
}
function pvHighlight(id) {
    document.querySelectorAll('.soal-row').forEach(r=>r.classList.remove('pv-active'));
    const row=document.getElementById('row-'+id);
    if(row){row.classList.add('pv-active');row.scrollIntoView({block:'nearest',behavior:'smooth'});}
}
function pvKillAudio() { if(pvAudio){try{pvAudio.pause();pvAudio.currentTime=0;}catch(e){}pvAudio=null;} }
function cap(s){return s?s[0].toUpperCase()+s.slice(1):'';}
function esc(s){return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/\n/g,'<br>');}
function pvChip(ico,txt,color){return `<div class="pv-chip" style="color:${color};border-color:${color}28"><i class="fas fa-${ico}" style="font-size:9px"></i>${txt}</div>`;}

document.addEventListener('keydown',e=>{
    if(!document.getElementById('pv-backdrop').classList.contains('open')) return;
    if(e.key==='Escape') pvClose();
    if(e.key==='ArrowLeft') pvGo(-1);
    if(e.key==='ArrowRight') pvGo(1);
    if(e.key===' '){e.preventDefault();try{tapToggle('pvaud');}catch(ex){}}
});

function syncPartOptions(kat) {
    const sel=document.getElementById('part-filter');
    const cOpt=sel.querySelector('option[value="C"]');
    // Listening: A, B, C — Structure: A, B — Reading: tidak pakai part
    if(kat==='reading') { sel.value=''; sel.style.display='none'; }
    else { sel.style.display=''; if(cOpt) cOpt.style.display=kat==='structure'?'none':''; }
}
document.addEventListener('DOMContentLoaded',()=>syncPartOptions('{{ request("kategori") }}'));
</script>
@endpush
