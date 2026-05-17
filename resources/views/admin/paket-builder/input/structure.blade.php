@extends('layouts.admin')
@section('title','Input Structure — '.$modul->rentang)
@section('page-title','Input Soal Structure')
@section('breadcrumb','Admin / Paket Builder / Structure')

@push('styles')
<style>
/* ─── Jenis toggle ─── */
.jenis-toggle{display:flex;background:var(--bg);border:1px solid var(--border);
    border-radius:10px;padding:4px;gap:4px;margin-bottom:20px}
.jenis-opt{flex:1;padding:10px;border-radius:7px;border:none;cursor:pointer;
    font-family:inherit;font-size:13px;font-weight:600;transition:all .18s;
    background:transparent;color:var(--muted)}
.jenis-opt.on{background:var(--blue);color:#fff;box-shadow:0 2px 8px rgba(26,86,219,.4)}

/* ─── Preview soal ─── */
.preview-box{border:1px solid var(--border);border-radius:10px;
    padding:16px 18px;background:var(--bg);margin-bottom:16px;min-height:60px}
.preview-label{font-size:11px;font-weight:700;text-transform:uppercase;
    letter-spacing:1px;color:var(--muted);margin-bottom:10px;
    display:flex;align-items:center;gap:6px}
.preview-kalimat{font-size:15px;line-height:1.8;color:#e2e8f0}
.blank-box{display:inline-block;min-width:80px;border-bottom:2px solid var(--accent);
    text-align:center;color:var(--accent);font-weight:700;padding:0 4px;
    vertical-align:baseline}
.underline-word{border-bottom:2px solid var(--red);cursor:pointer;
    padding:0 2px;font-weight:600;transition:background .15s}
.underline-word:hover{background:rgba(220,38,38,.1);border-radius:3px}
.underline-word.correct{background:rgba(220,38,38,.12);color:var(--red)}
.preview-opts{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:12px}
.preview-opt{display:flex;align-items:flex-start;gap:8px;padding:8px 12px;
    border-radius:8px;border:1.5px solid var(--border);background:rgba(255,255,255,.02)}
.preview-opt.correct{border-color:rgba(22,163,74,.4);background:rgba(22,163,74,.07)}
.opt-letter{width:24px;height:24px;border-radius:50%;border:1.5px solid var(--border);
    display:flex;align-items:center;justify-content:center;
    font-size:11px;font-weight:700;flex-shrink:0}
.preview-opt.correct .opt-letter{background:var(--green);border-color:var(--green);color:#fff}

/* ─── Kunci jawaban ─── */
.kunci-row{display:flex;gap:8px}
.kunci-lbl{flex:1;text-align:center;padding:10px;border-radius:8px;
    border:2px solid var(--border);cursor:pointer;font-weight:800;
    font-size:15px;transition:all .15s;user-select:none;background:transparent}
.kunci-lbl.on{background:var(--green);border-color:var(--green);color:#fff}

/* ─── Soal list ─── */
.soal-row{display:flex;align-items:center;gap:10px;padding:10px 14px;
    border-bottom:1px solid var(--border);font-size:13px}
.soal-row:last-child{border-bottom:none}
.no-chip{width:30px;height:30px;border-radius:8px;color:#fff;
    display:flex;align-items:center;justify-content:center;
    font-size:12px;font-weight:800;flex-shrink:0}

/* ─── Badge jenis ─── */
.badge-co{background:rgba(26,86,219,.15);color:var(--accent);
    padding:2px 8px;border-radius:5px;font-size:10.5px;font-weight:700}
.badge-we{background:rgba(220,38,38,.12);color:#f87171;
    padding:2px 8px;border-radius:5px;font-size:10.5px;font-weight:700}
</style>
@endpush

@section('content')

<div style="display:flex;align-items:center;gap:12px;margin-bottom:18px">
    <a href="{{ route('admin.paket-builder.paket', $modul->paket_id) }}"
        class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i></a>
    <div style="flex:1">
        <div style="font-size:16px;font-weight:800">✏️ Structure — {{ $modul->rentang }}</div>
        <div style="font-size:13px;color:var(--muted)">
            {{ $modul->paket?->nama }} &nbsp;·&nbsp;
            Target {{ $modul->jumlah_target }} soal &nbsp;·&nbsp;
            <span style="color:{{ $modul->soal->count()>=$modul->jumlah_target?'var(--green)':'var(--muted)' }}">
                {{ $modul->soal->count() }} tersimpan
            </span>
        </div>
    </div>
</div>

<div id="alert-box" style="display:none;margin-bottom:14px"></div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:18px;align-items:start">

{{-- ═══ KIRI: Form Input ═══ --}}
<div>
<div class="card">
    <div class="card-header" style="padding:14px 18px">
        <h3 style="font-size:14px">
            <i class="fas fa-pen" style="color:var(--accent);margin-right:7px"></i>
            Tambah Soal Structure
        </h3>
        <span style="font-size:12px;color:var(--muted)">
            {{ $modul->soal->count() }} / {{ $modul->jumlah_target }}
        </span>
    </div>
    <div class="card-body" style="padding:20px">

        {{-- ─ Toggle Jenis Soal ─ --}}
        <div style="margin-bottom:4px">
            <label class="form-label" style="font-size:11.5px;font-weight:700;
                text-transform:uppercase;letter-spacing:1px;color:var(--muted)">
                Jenis Soal
            </label>
        </div>
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

        {{-- ─ Info kontekstual ─ --}}
        <div id="info-completion" style="background:rgba(26,86,219,.07);
            border:1px solid rgba(26,86,219,.18);border-radius:8px;
            padding:11px 14px;margin-bottom:16px;font-size:13px;line-height:1.7">
            <strong style="color:var(--accent)">📝 Structure Completion</strong>
            — Kalimat rumpang, mahasiswa pilih kata/frasa yang tepat untuk mengisi blank.<br>
            <span style="color:var(--muted)">Contoh: <em>The students ____ studying in the library.</em></span>
        </div>
        <div id="info-written_expression" style="display:none;background:rgba(220,38,38,.07);
            border:1px solid rgba(220,38,38,.18);border-radius:8px;
            padding:11px 14px;margin-bottom:16px;font-size:13px;line-height:1.7">
            <strong style="color:#f87171">🔍 Written Expression</strong>
            — Kalimat lengkap dengan 4 kata bergaris bawah. Mahasiswa pilih kata yang <em>salah</em>.<br>
            <span style="color:var(--muted)">Contoh: <em>The students in the class <u>was</u> studying hard.</em></span>
        </div>

        {{-- ─ Nomor soal ─ --}}
        <div class="form-group">
            <label class="form-label" style="font-size:12px">Nomor Soal</label>
            <select id="inp-nomor" class="form-control" style="max-width:120px">
                @for($n = $modul->nomor_soal_mulai; $n <= $modul->nomor_soal_selesai; $n++)
                @php $ada = $modul->soal->pluck('nomor_dalam_paket')->contains($n); @endphp
                <option value="{{ $n }}" {{ $ada?'disabled':'' }}>
                    No.{{ $n }}{{ $ada?' ✓':'' }}
                </option>
                @endfor
            </select>
        </div>

        {{-- ─ Pertanyaan / Kalimat ─ --}}
        <div class="form-group">
            <label class="form-label" style="font-size:12px">
                <span id="lbl-pertanyaan">Kalimat Soal</span>
                <span style="color:var(--red)">*</span>
                &nbsp;
                <small id="hint-pertanyaan" style="color:var(--muted)">
                    — gunakan ____ untuk bagian yang kosong
                </small>
            </label>
            <textarea id="inp-pertanyaan" class="form-control" rows="2"
                oninput="updatePreview()"
                placeholder="The students ____ studying in the library."></textarea>
        </div>

        {{-- ─ Pilihan A B C D ─ --}}
        <div style="margin-bottom:4px">
            <label class="form-label" style="font-size:12px">
                Pilihan Jawaban <span style="color:var(--red)">*</span>
                <small id="hint-pilihan" style="color:var(--muted)">
                    — tulis kata/frasa pengisinya
                </small>
            </label>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:14px">
            @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $k=>$l)
            <div style="display:flex;align-items:center;gap:8px">
                <div style="width:28px;height:28px;border-radius:50%;flex-shrink:0;
                    background:rgba(255,255,255,.06);border:1px solid var(--border);
                    display:flex;align-items:center;justify-content:center;
                    font-size:12px;font-weight:700;color:var(--muted)">{{ $l }}</div>
                <input type="text" id="inp-p{{ $k }}" class="form-control"
                    placeholder="Pilihan {{ $l }}" oninput="updatePreview()">
            </div>
            @endforeach
        </div>

        {{-- ─ Kunci Jawaban ─ --}}
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

        {{-- ─ Preview soal ─ --}}
        <div class="preview-box" id="preview-wrap" style="display:none">
            <div class="preview-label">
                <i class="fas fa-eye"></i> Preview Tampilan Mahasiswa
            </div>
            <div class="preview-kalimat" id="preview-kalimat"></div>
            <div class="preview-opts" id="preview-opts"></div>
        </div>

        {{-- ─ Pembahasan ─ --}}
        <div class="form-group">
            <label class="form-label" style="font-size:12px">
                Pembahasan
                <small style="color:var(--muted)">(opsional — hanya admin)</small>
            </label>
            <textarea id="inp-pembahasan" class="form-control" rows="2"
                placeholder="Jelaskan mengapa jawaban ini benar..."></textarea>
        </div>

        {{-- ─ Tombol Simpan ─ --}}
        <button onclick="simpan()" id="btn-save" class="btn btn-primary"
            style="width:100%;padding:11px">
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
            <a href="{{ route('admin.paket-builder.paket', $modul->paket_id) }}"
                class="btn btn-primary btn-sm" style="font-size:11px">
                Daftar Soal Paket →
            </a>
        </div>

        <div id="soal-list" style="max-height:68vh;overflow-y:auto">
            @forelse($modul->soal->sortBy('nomor_dalam_paket') as $s)
            @php
                $isWE = $s->sub_bagian === 'written_expression';
            @endphp
            <div class="soal-row" id="si-{{ $s->id }}">
                <div class="no-chip"
                    style="background:{{ $isWE ? '#dc2626' : 'var(--blue)' }}">
                    {{ $s->nomor_dalam_paket }}
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:12.5px;white-space:nowrap;overflow:hidden;
                        text-overflow:ellipsis;color:rgba(255,255,255,.85)">
                        {{ mb_strimwidth($s->pertanyaan??'',0,42,'...') }}
                    </div>
                    <div style="margin-top:3px">
                        @if($isWE)
                        <span class="badge-we">Written Expression</span>
                        @else
                        <span class="badge-co">Completion</span>
                        @endif
                        <span style="font-size:11px;color:var(--muted);margin-left:6px">
                            Jwb: {{ strtoupper($s->jawaban_benar ?? '-') }}
                        </span>
                    </div>
                </div>
                <button onclick="hapusSoal({{ $s->id }})"
                    style="background:none;border:none;color:var(--muted);
                    cursor:pointer;font-size:12px;padding:3px 6px;flex-shrink:0">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @empty
            <div style="padding:30px;text-align:center;color:var(--muted);font-size:13px">
                <i class="fas fa-pen" style="display:block;font-size:26px;margin-bottom:8px;opacity:.4"></i>
                Belum ada soal
            </div>
            @endforelse
        </div>

        {{-- Progress ─ --}}
        @php $pct = min(100, ($modul->soal->count() / max(1,$modul->jumlah_target)) * 100); @endphp
        <div style="padding:10px 16px;border-top:1px solid var(--border)">
            <div style="display:flex;justify-content:space-between;
                font-size:11.5px;color:var(--muted);margin-bottom:5px">
                <span>Progress</span>
                <span>{{ $modul->soal->count() }} / {{ $modul->jumlah_target }}</span>
            </div>
            <div style="height:5px;background:var(--border);border-radius:3px">
                <div style="height:5px;border-radius:3px;width:{{ $pct }}%;
                    background:{{ $pct>=100?'var(--green)':'var(--blue)' }};
                    transition:width .4s"></div>
            </div>
        </div>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script>
const MODUL_ID  = {{ $modul->id }};
const CSRF      = '{{ csrf_token() }}';
let   curJenis  = 'completion';
let   curKunci  = null;

// ══ Pilih jenis soal ══════════════════════════════════════════
function pilihJenis(jenis) {
    curJenis = jenis;
    ['completion','written_expression'].forEach(j => {
        document.getElementById('opt-'+j).classList.toggle('on', j===jenis);
        document.getElementById('info-'+j).style.display = j===jenis ? 'block' : 'none';
    });

    // Update label dan hint
    const isWE = jenis === 'written_expression';
    document.getElementById('lbl-pertanyaan').textContent =
        isWE ? 'Kalimat dengan Error' : 'Kalimat Soal';
    document.getElementById('hint-pertanyaan').textContent =
        isWE ? '— kalimat lengkap yang mengandung 1 kesalahan gramatikal'
             : '— gunakan ____ untuk bagian yang kosong';
    document.getElementById('hint-pilihan').textContent =
        isWE ? '— bagian kalimat yang bergaris bawah (A/B/C/D adalah kata dalam kalimat)'
             : '— tulis kata/frasa pengisi blank';

    // Ubah placeholder
    document.getElementById('inp-pertanyaan').placeholder = isWE
        ? 'The students in the class was studying hard.'
        : 'The students ____ studying in the library.';

    // Reset kunci
    curKunci = null;
    ['a','b','c','d'].forEach(k => {
        document.getElementById('kl-'+k).className = 'kunci-lbl';
    });

    updatePreview();
}

// ══ Kunci jawaban ═════════════════════════════════════════════
function pilihKunci(k) {
    curKunci = k;
    ['a','b','c','d'].forEach(x => {
        document.getElementById('kl-'+x).className = 'kunci-lbl'+(x===k?' on':'');
    });
    updatePreview();
}

// ══ Preview real-time ═════════════════════════════════════════
function updatePreview() {
    const q    = document.getElementById('inp-pertanyaan').value.trim();
    const pa   = document.getElementById('inp-pa').value.trim();
    const pb   = document.getElementById('inp-pb').value.trim();
    const pc   = document.getElementById('inp-pc').value.trim();
    const pd   = document.getElementById('inp-pd').value.trim();
    const wrap = document.getElementById('preview-wrap');

    if (!q && !pa && !pb && !pc && !pd) {
        wrap.style.display = 'none';
        return;
    }
    wrap.style.display = 'block';

    const pEl   = document.getElementById('preview-kalimat');
    const oEl   = document.getElementById('preview-opts');
    const opts  = {a:pa, b:pb, c:pc, d:pd};
    const isWE  = curJenis === 'written_expression';

    // Render kalimat
    if (isWE) {
        // Written expression: tandai kata-kata pilihan di kalimat dengan garis bawah
        let rendered = esc(q);
        Object.entries(opts).forEach(([k, v]) => {
            if (!v) return;
            const esc_v = esc(v);
            const cls   = curKunci===k ? 'underline-word correct' : 'underline-word';
            // Highlight kemunculan pertama kata tsb di kalimat
            rendered = rendered.replace(
                new RegExp(escapeRegex(esc_v), 'i'),
                `<span class="${cls}" title="Pilihan ${k.toUpperCase()}">${esc_v}</span>`
            );
        });
        pEl.innerHTML = rendered;
    } else {
        // Completion: ganti ____ dengan box
        pEl.innerHTML = esc(q).replace(/_{3,}/g,
            `<span class="blank-box">${curKunci ? esc(opts[curKunci])||'____' : '____'}</span>`
        );
    }

    // Render pilihan
    oEl.innerHTML = Object.entries(opts)
        .filter(([, v]) => v)
        .map(([k, v]) => `
        <div class="preview-opt ${curKunci===k?'correct':''}">
            <div class="opt-letter">${k.toUpperCase()}</div>
            <div style="font-size:13.5px;color:#e2e8f0;line-height:1.4">${esc(v)}</div>
        </div>`).join('');
}

function esc(s) {
    return (s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
function escapeRegex(s) {
    return s.replace(/[.*+?^${}()|[\]\\]/g,'\\$&');
}

// ══ Simpan soal ═══════════════════════════════════════════════
function simpan() {
    const nomor = parseInt(document.getElementById('inp-nomor').value);
    const q     = document.getElementById('inp-pertanyaan').value.trim();
    const pa    = document.getElementById('inp-pa').value.trim();
    const pb    = document.getElementById('inp-pb').value.trim();
    const pc    = document.getElementById('inp-pc').value.trim();
    const pd    = document.getElementById('inp-pd').value.trim();

    if (!q)               return showAlert('Kalimat soal tidak boleh kosong.', 'danger');
    if (!pa||!pb||!pc||!pd) return showAlert('Semua pilihan A–D harus diisi.', 'danger');
    if (!curKunci)        return showAlert('Pilih jawaban benar (A/B/C/D).', 'danger');

    const btn = document.getElementById('btn-save');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    fetch(`/admin/paket-builder/modul/${MODUL_ID}/soal-structure`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept':       'application/json',
        },
        body: JSON.stringify({
            nomor_dalam_paket: nomor,
            tipe_soal:         'multiple_choice',
            sub_bagian:        curJenis,          // completion | written_expression
            pertanyaan:        q,
            pilihan_a: pa, pilihan_b: pb,
            pilihan_c: pc, pilihan_d: pd,
            jawaban_benar: curKunci,
            pembahasan: document.getElementById('inp-pembahasan').value,
        }),
    })
    .then(r => r.json())
    .then(d => {
        if (d.ok) { showAlert(d.msg, 'success'); location.reload(); }
        else        showAlert(d.msg || 'Gagal menyimpan.', 'danger');
    })
    .catch(e => showAlert('Error: '+e.message, 'danger'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Soal';
    });
}

// ══ Hapus soal ════════════════════════════════════════════════
function hapusSoal(id) {
    if (!confirm('Hapus soal ini?')) return;
    fetch(`/admin/paket-builder/soal/${id}`, {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
    }).then(r=>r.json()).then(d=>{
        if (d.ok) document.getElementById('si-'+id)?.remove();
    });
}

// ══ Alert ════════════════════════════════════════════════════
function showAlert(msg, type) {
    const el = document.getElementById('alert-box');
    const c  = {
        success:['rgba(22,163,74,.1)','rgba(22,163,74,.3)','#4ade80'],
        danger: ['rgba(220,38,38,.1)','rgba(220,38,38,.3)','#f87171'],
    }[type];
    el.style.cssText = `display:block;background:${c[0]};border:1px solid ${c[1]};
        border-radius:8px;padding:11px 14px;color:${c[2]};font-size:13px`;
    el.textContent = msg;
    if (type==='success') setTimeout(()=>el.style.display='none',4000);
}
</script>
@endpush
