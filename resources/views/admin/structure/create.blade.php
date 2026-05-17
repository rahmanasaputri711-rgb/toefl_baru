@extends('layouts.admin')
@section('title','Input Soal Structure')
@section('page-title','Input Soal Structure')
@section('breadcrumb','Admin / Bank Soal / Structure / Input')

@push('styles')
<style>
.jenis-toggle{display:flex;background:var(--bg);border:1px solid var(--border);
    border-radius:10px;padding:4px;gap:4px;margin-bottom:20px}
.jenis-opt{flex:1;padding:10px;border-radius:7px;border:none;cursor:pointer;
    font-family:inherit;font-size:13px;font-weight:600;transition:all .18s;
    background:transparent;color:var(--muted)}
.jenis-opt.on{background:var(--blue);color:#fff;box-shadow:0 2px 8px rgba(26,86,219,.35)}
.kunci-row{display:flex;gap:8px}
.kunci-lbl{flex:1;text-align:center;padding:10px;border-radius:8px;
    border:2px solid var(--border);cursor:pointer;font-weight:800;
    font-size:15px;transition:all .15s;user-select:none;background:transparent}
.kunci-lbl.on{background:var(--green);border-color:var(--green);color:#fff}
.preview-box{border:1px solid var(--border);border-radius:10px;
    padding:16px 18px;background:var(--bg);margin-bottom:16px}
.preview-kalimat{font-size:15px;line-height:1.9;color:#e2e8f0;margin-bottom:12px}
.blank-box{display:inline-block;min-width:80px;border-bottom:2px solid var(--accent);
    text-align:center;color:var(--accent);font-weight:700;padding:0 4px;vertical-align:baseline}
.preview-opts{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.preview-opt{display:flex;align-items:flex-start;gap:8px;padding:8px 12px;
    border-radius:8px;border:1.5px solid var(--border);background:rgba(255,255,255,.02)}
.preview-opt.correct{border-color:rgba(22,163,74,.4);background:rgba(22,163,74,.07)}
.opt-letter{width:24px;height:24px;border-radius:50%;border:1.5px solid var(--border);
    display:flex;align-items:center;justify-content:center;
    font-size:11px;font-weight:700;flex-shrink:0}
.preview-opt.correct .opt-letter{background:var(--green);border-color:var(--green);color:#fff}
.soal-row{display:flex;align-items:center;gap:10px;padding:10px 14px;
    border-bottom:1px solid var(--border)}
.soal-row:last-child{border-bottom:none}
.badge-co{background:rgba(26,86,219,.12);color:var(--accent);
    padding:2px 7px;border-radius:4px;font-size:10.5px;font-weight:600}
.badge-we{background:rgba(220,38,38,.1);color:#f87171;
    padding:2px 7px;border-radius:4px;font-size:10.5px;font-weight:600}
</style>
@endpush

@section('content')

<div style="display:flex;align-items:center;gap:12px;margin-bottom:18px">
    <a href="{{ route('admin.soal.group.structure') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div style="flex:1">
        <div style="font-size:16px;font-weight:800">✏️ Input Soal Structure</div>
        <div style="font-size:13px;color:var(--muted)">
            Structure Completion &amp; Written Expression
        </div>
    </div>
</div>

<div id="alert-box" style="display:none;margin-bottom:14px"></div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:18px;align-items:start">

{{-- ═══ KIRI: Form ═══ --}}
<div>
<div class="card">
    <div class="card-header" style="padding:14px 18px">
        <h3 style="font-size:14px">
            <i class="fas fa-pen" style="color:var(--accent);margin-right:7px"></i>
            Tambah Soal
        </h3>
        <span style="font-size:12px;color:var(--muted)">
            {{ $soalList->count() }} soal tersimpan
        </span>
    </div>
    <div class="card-body" style="padding:20px">

        {{-- Toggle jenis --}}
        <div class="jenis-toggle">
            <button type="button" class="jenis-opt on" id="opt-completion"
                onclick="pilihJenis('completion')">
                📝 Structure Completion
            </button>
            <button type="button" class="jenis-opt" id="opt-written_expression"
                onclick="pilihJenis('written_expression')">
                🔍 Written Expression
            </button>
        </div>

        {{-- Info --}}
        <div id="info-completion" style="background:rgba(26,86,219,.07);
            border:1px solid rgba(26,86,219,.18);border-radius:8px;
            padding:10px 14px;margin-bottom:14px;font-size:12.5px;line-height:1.7">
            <strong style="color:var(--accent)">📝 Completion:</strong>
            Kalimat rumpang, mahasiswa pilih kata/frasa yang tepat mengisi blank (____).
        </div>
        <div id="info-written_expression" style="display:none;background:rgba(220,38,38,.07);
            border:1px solid rgba(220,38,38,.18);border-radius:8px;
            padding:10px 14px;margin-bottom:14px;font-size:12.5px;line-height:1.7">
            <strong style="color:#f87171">🔍 Written Expression:</strong>
            Kalimat lengkap + 4 kata bergaris bawah. Mahasiswa pilih kata yang <em>salah</em>.
        </div>

        {{-- Metadata --}}
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:14px">
            <div class="form-group" style="margin:0">
                <label class="form-label" style="font-size:12px">No. Soal</label>
                <input type="number" id="inp-nomor" class="form-control"
                    value="{{ $nextNomor }}" min="1">
            </div>
            <div class="form-group" style="margin:0">
                <label class="form-label" style="font-size:12px">Tipe Paket</label>
                <select id="inp-tipe-paket" class="form-control" style="font-size:13px">
                    <option value="full">🏆 Full</option>
                    <option value="simulasi">🎯 Simulasi</option>
                    <option value="mini">⚡ Mini</option>
                    <option value="praktik">📚 Praktik</option>
                </select>
            </div>
            <div class="form-group" style="margin:0">
                <label class="form-label" style="font-size:12px">Kesulitan</label>
                <select id="inp-diff" class="form-control" style="font-size:13px">
                    <option value="easy">Easy</option>
                    <option value="medium" selected>Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>
        </div>

        {{-- Kalimat soal --}}
        <div class="form-group">
            <label class="form-label" style="font-size:12px">
                <span id="lbl-q">Kalimat Soal</span>
                <span style="color:var(--red)">*</span>
                <small id="hint-q" style="color:var(--muted)">— gunakan ____ untuk blank</small>
            </label>
            <textarea id="inp-q" class="form-control" rows="2"
                oninput="updatePreview()"
                placeholder="The students ____ studying in the library."></textarea>
        </div>

        {{-- Pilihan A-D --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:14px">
            @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $k=>$l)
            <div style="display:flex;align-items:center;gap:8px">
                <div style="width:28px;height:28px;border-radius:50%;flex-shrink:0;
                    background:rgba(255,255,255,.05);border:1px solid var(--border);
                    display:flex;align-items:center;justify-content:center;
                    font-size:12px;font-weight:700;color:var(--muted)">{{ $l }}</div>
                <input type="text" id="inp-p{{ $k }}" class="form-control"
                    placeholder="Pilihan {{ $l }}" oninput="updatePreview()">
            </div>
            @endforeach
        </div>

        {{-- Kunci --}}
        <div class="form-group">
            <label class="form-label" style="font-size:12px">
                Jawaban Benar <span style="color:var(--red)">*</span>
            </label>
            <div class="kunci-row">
                @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $k=>$l)
                <label class="kunci-lbl" id="kl-{{ $k }}"
                    onclick="pilihKunci('{{ $k }}')">{{ $l }}</label>
                @endforeach
            </div>
        </div>

        {{-- Preview --}}
        <div class="preview-box" id="preview-wrap" style="display:none">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;
                letter-spacing:1px;color:var(--muted);margin-bottom:10px;
                display:flex;align-items:center;gap:6px">
                <i class="fas fa-eye"></i> Preview
            </div>
            <div class="preview-kalimat" id="preview-kalimat"></div>
            <div class="preview-opts" id="preview-opts"></div>
        </div>

        {{-- Pembahasan --}}
        <div class="form-group">
            <label class="form-label" style="font-size:12px">
                Pembahasan <small style="color:var(--muted)">(opsional)</small>
            </label>
            <textarea id="inp-pem" class="form-control" rows="2"
                placeholder="Jelaskan mengapa jawaban ini benar..."></textarea>
        </div>

        <button onclick="simpan()" id="btn-save" class="btn btn-primary" style="width:100%">
            <i class="fas fa-save"></i> Simpan Soal
        </button>

    </div>
</div>
</div>

{{-- ═══ KANAN: Daftar Soal ═══ --}}
<div style="position:sticky;top:20px">
    <div class="card">
        <div class="card-header" style="padding:12px 16px">
            <h3 style="font-size:13px">
                <i class="fas fa-list-ol" style="color:var(--accent);margin-right:6px"></i>
                Soal Tersimpan
            </h3>
            <span style="font-size:12px;color:var(--muted)">{{ $soalList->count() }}</span>
        </div>
        <div id="soal-list" style="max-height:72vh;overflow-y:auto">
            @forelse($soalList as $s)
            <div class="soal-row" id="si-{{ $s->id }}">
                <div style="width:30px;height:30px;border-radius:8px;flex-shrink:0;
                    background:{{ $s->sub_bagian==='written_expression'?'rgba(220,38,38,.15)':'rgba(26,86,219,.12)' }};
                    display:flex;align-items:center;justify-content:center;
                    font-size:12px;font-weight:800;
                    color:{{ $s->sub_bagian==='written_expression'?'#f87171':'var(--accent)' }}">
                    {{ $s->nomor_soal ?: '—' }}
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:12.5px;white-space:nowrap;overflow:hidden;
                        text-overflow:ellipsis;color:rgba(255,255,255,.85)">
                        {{ mb_strimwidth($s->pertanyaan??'',0,42,'...') }}
                    </div>
                    <div style="display:flex;gap:6px;margin-top:3px;align-items:center">
                        @if($s->sub_bagian==='written_expression')
                        <span class="badge-we">WE</span>
                        @else
                        <span class="badge-co">CO</span>
                        @endif
                        <span style="font-size:11px;color:var(--muted)">
                            Jwb: {{ strtoupper($s->jawaban_benar??'-') }}
                        </span>
                    </div>
                </div>
                <button onclick="hapusSoal({{ $s->id }})"
                    style="background:none;border:none;color:var(--muted);
                    cursor:pointer;font-size:12px;padding:2px 5px;flex-shrink:0">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @empty
            <div style="padding:28px;text-align:center;color:var(--muted);font-size:13px">
                <i class="fas fa-pen" style="font-size:24px;display:block;margin-bottom:8px;opacity:.3"></i>
                Belum ada soal
            </div>
            @endforelse
        </div>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script>
const CSRF     = '{{ csrf_token() }}';
let curJenis   = 'completion';
let curKunci   = null;

function pilihJenis(j) {
    curJenis = j;
    ['completion','written_expression'].forEach(x => {
        document.getElementById('opt-'+x).classList.toggle('on', x===j);
        document.getElementById('info-'+x).style.display = x===j?'block':'none';
    });
    const isWE = j === 'written_expression';
    document.getElementById('lbl-q').textContent = isWE ? 'Kalimat dengan Error' : 'Kalimat Soal';
    document.getElementById('hint-q').textContent = isWE
        ? '— kalimat lengkap yang mengandung 1 kesalahan gramatikal'
        : '— gunakan ____ untuk bagian yang kosong';
    document.getElementById('inp-q').placeholder = isWE
        ? 'The students in the class was studying hard.'
        : 'The students ____ studying in the library.';
    updatePreview();
}

function pilihKunci(k) {
    curKunci = k;
    ['a','b','c','d'].forEach(x => {
        document.getElementById('kl-'+x).className = 'kunci-lbl'+(x===k?' on':'');
    });
    updatePreview();
}

function updatePreview() {
    const q   = document.getElementById('inp-q').value.trim();
    const opts = {
        a: document.getElementById('inp-pa').value.trim(),
        b: document.getElementById('inp-pb').value.trim(),
        c: document.getElementById('inp-pc').value.trim(),
        d: document.getElementById('inp-pd').value.trim(),
    };
    const wrap = document.getElementById('preview-wrap');
    if (!q && !Object.values(opts).some(v=>v)) { wrap.style.display='none'; return; }
    wrap.style.display = 'block';

    const isWE = curJenis === 'written_expression';
    const pEl  = document.getElementById('preview-kalimat');
    const oEl  = document.getElementById('preview-opts');

    if (isWE) {
        let html = esc(q);
        Object.entries(opts).forEach(([k,v]) => {
            if (!v) return;
            const cls = curKunci===k ? 'underline-word correct' : 'underline-word';
            html = html.replace(
                new RegExp(escReg(esc(v)),'i'),
                `<span style="border-bottom:2px solid ${curKunci===k?'#f87171':'#94a3b8'};
                    font-weight:600;padding:0 2px;color:${curKunci===k?'#f87171':'inherit'}">${esc(v)}</span>`
            );
        });
        pEl.innerHTML = html;
    } else {
        pEl.innerHTML = esc(q).replace(/_{3,}/g,
            `<span style="display:inline-block;min-width:70px;border-bottom:2px solid var(--accent);
            text-align:center;color:var(--accent);font-weight:700;padding:0 4px;vertical-align:baseline">
            ${curKunci && opts[curKunci] ? esc(opts[curKunci]) : '____'}</span>`
        );
    }

    oEl.innerHTML = Object.entries(opts).filter(([,v])=>v).map(([k,v])=>`
        <div style="display:flex;align-items:flex-start;gap:8px;padding:8px 12px;
            border-radius:8px;border:1.5px solid ${curKunci===k?'rgba(22,163,74,.4)':'var(--border)'};
            background:${curKunci===k?'rgba(22,163,74,.07)':'rgba(255,255,255,.02)'}">
            <div style="width:24px;height:24px;border-radius:50%;border:1.5px solid ${curKunci===k?'var(--green)':'var(--border)'};
                background:${curKunci===k?'var(--green)':'transparent'};flex-shrink:0;
                display:flex;align-items:center;justify-content:center;
                font-size:11px;font-weight:700;color:${curKunci===k?'#fff':'var(--muted)'}">
                ${k.toUpperCase()}
            </div>
            <div style="font-size:13.5px;color:#e2e8f0">${esc(v)}</div>
        </div>`).join('');
}

function esc(s){ return (s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
function escReg(s){ return s.replace(/[.*+?^${}()|[\]\\]/g,'\\$&'); }

function simpan() {
    const q  = document.getElementById('inp-q').value.trim();
    const pa = document.getElementById('inp-pa').value.trim();
    const pb = document.getElementById('inp-pb').value.trim();
    const pc = document.getElementById('inp-pc').value.trim();
    const pd = document.getElementById('inp-pd').value.trim();
    if (!q)               return showAlert('Kalimat soal tidak boleh kosong.','danger');
    if (!pa||!pb||!pc||!pd) return showAlert('Semua pilihan A–D harus diisi.','danger');
    if (!curKunci)        return showAlert('Pilih jawaban benar.','danger');

    const btn = document.getElementById('btn-save');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    fetch('/admin/structure/store', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
        body: JSON.stringify({
            sub_bagian:  curJenis,
            nomor_soal:  parseInt(document.getElementById('inp-nomor').value)||0,
            tipe_paket:  document.getElementById('inp-tipe-paket').value,
            tingkat_kesulitan: document.getElementById('inp-diff').value,
            pertanyaan:  q,
            pilihan_a: pa, pilihan_b: pb, pilihan_c: pc, pilihan_d: pd,
            jawaban_benar: curKunci,
            pembahasan: document.getElementById('inp-pem').value,
        }),
    })
    .then(r=>r.json())
    .then(d => {
        if (d.ok) { showAlert(d.msg,'success'); location.reload(); }
        else        showAlert(d.msg,'danger');
    })
    .catch(e => showAlert('Error: '+e.message,'danger'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Soal';
    });
}

function hapusSoal(id) {
    if (!confirm('Hapus soal ini?')) return;
    fetch('/admin/structure/'+id, {
        method:'DELETE',
        headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
    }).then(r=>r.json()).then(d=>{
        if (d.ok) document.getElementById('si-'+id)?.remove();
    });
}

function showAlert(msg,type) {
    const el = document.getElementById('alert-box');
    const c={success:['rgba(22,163,74,.1)','rgba(22,163,74,.3)','#4ade80'],
             danger:['rgba(220,38,38,.1)','rgba(220,38,38,.3)','#f87171']}[type];
    el.style.cssText=`display:block;background:${c[0]};border:1px solid ${c[1]};
        border-radius:8px;padding:11px 14px;color:${c[2]};font-size:13px`;
    el.textContent=msg;
    if(type==='success') setTimeout(()=>el.style.display='none',4000);
}
</script>
@endpush