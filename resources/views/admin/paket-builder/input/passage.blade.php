@extends('layouts.admin')
@section('title','Input Passage')
@section('page-title','Input Passage — '.$modul->rentang)
@section('breadcrumb','Admin / Paket Builder / Passage')

@push('styles')
<style>
.soal-form{border:1px solid var(--border);border-radius:12px;padding:18px;margin-bottom:14px;background:var(--bg)}
.soal-saved{display:flex;align-items:center;gap:10px;padding:10px 16px;
    border-bottom:1px solid var(--border);font-size:13px}
.tipe-btn{padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);
    background:transparent;color:var(--muted);cursor:pointer;font-family:inherit;
    font-size:12.5px;font-weight:600;transition:all .15s}
.tipe-btn.on{border-color:var(--blue);color:var(--blue);background:rgba(26,86,219,.08)}
</style>
@endpush

@section('content')

<div style="display:flex;align-items:center;gap:12px;margin-bottom:18px">
    <a href="{{ route('admin.paket-builder.paket', $modul->paket_id) }}"
        class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i></a>
    <div>
        <div style="font-size:16px;font-weight:800">📄 Passage Module</div>
        <div style="font-size:13px;color:var(--muted)">
            {{ $modul->paket?->nama }} &nbsp;·&nbsp; {{ $modul->rentang }}
        </div>
    </div>
</div>

<div id="alert" style="display:none;margin-bottom:14px"></div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start">

{{-- KIRI: Form --}}
<div>

{{-- STEP 1: Teks Passage --}}
@php $passage = $modul->passages->first(); @endphp
<div class="card" style="margin-bottom:16px">
    <div class="card-header" style="padding:12px 18px">
        <h3 style="font-size:13px;display:flex;align-items:center;gap:8px">
            <span style="background:var(--blue);color:#fff;width:22px;height:22px;
                border-radius:50%;display:flex;align-items:center;justify-content:center;
                font-size:11px;font-weight:900;flex-shrink:0">1</span>
            Teks Passage
        </h3>
        @if($passage)
        <span style="font-size:12px;color:var(--green)">✓ Tersimpan</span>
        @endif
    </div>
    <div class="card-body" style="padding:18px">
        <div style="font-size:12.5px;color:var(--muted);margin-bottom:12px;line-height:1.7">
            Gunakan <code>**kata**</code> untuk <strong style="color:#fbbf24">bold</strong> kata di passage.
            Ini berguna untuk soal tipe Vocabulary.
        </div>
        <div class="form-group">
            <label class="form-label">Judul Passage</label>
            <input type="text" id="p-judul" class="form-control"
                value="{{ $passage?->judul }}" placeholder="cth: The Ocean's Living Lights">
        </div>
        <div class="form-group" style="margin-bottom:10px">
            <label class="form-label">Teks Bacaan <span style="color:var(--red)">*</span></label>
            <textarea id="p-teks" class="form-control" rows="10"
                style="resize:vertical;line-height:1.8"
                placeholder="Tulis atau paste teks di sini...">{{ $passage?->teks }}</textarea>
        </div>
        <div style="display:flex;gap:8px;align-items:center">
            <button onclick="simpanPassage()" id="btn-passage" class="btn btn-primary btn-sm">
                <i class="fas fa-save"></i>
                {{ $passage ? 'Update Teks' : 'Simpan Teks' }}
            </button>
            @if($passage)
            <span style="font-size:12px;color:var(--green)">
                ✓ Teks tersimpan — sekarang tambah soal di bawah
            </span>
            @endif
        </div>
    </div>
</div>

{{-- STEP 2: Tambah Soal --}}
<div class="card">
    <div class="card-header" style="padding:12px 18px">
        <h3 style="font-size:13px;display:flex;align-items:center;gap:8px">
            <span style="background:var(--blue);color:#fff;width:22px;height:22px;
                border-radius:50%;display:flex;align-items:center;justify-content:center;
                font-size:11px;font-weight:900;flex-shrink:0">2</span>
            Tambah Soal
        </h3>
        <span style="font-size:12px;color:var(--muted)">
            {{ $modul->soal->count() }} / {{ $modul->jumlah_target }} soal
        </span>
    </div>
    <div class="card-body" style="padding:18px">

        @if(!$passage)
        <div style="text-align:center;padding:24px;color:var(--muted)">
            Simpan teks passage terlebih dahulu (Step 1).
        </div>
        @else

        {{-- Pilih tipe soal --}}
        <div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap">
            <button class="tipe-btn on" id="tb-mc"      onclick="pilihTipe('multiple_choice')">🔵 Pilihan Ganda</button>
            <button class="tipe-btn"    id="tb-vo"      onclick="pilihTipe('vocabulary')">🟡 Vocabulary</button>
            <button class="tipe-btn"    id="tb-cs"      onclick="pilihTipe('click_sentence')">🟣 Klik Kalimat</button>
        </div>

        {{-- Form soal --}}
        <div class="form-group">
            <label class="form-label">Nomor Soal <span style="color:var(--red)">*</span></label>
            <div style="display:flex;gap:8px;align-items:center">
                <select id="inp-nomor" class="form-control" style="width:100px">
                    @for($n=$modul->nomor_soal_mulai; $n<=$modul->nomor_soal_selesai; $n++)
                    <option value="{{ $n }}"
                        {{ $modul->soal->pluck('nomor_dalam_paket')->contains($n) ? 'disabled' : '' }}>
                        No.{{ $n }}
                        {{ $modul->soal->pluck('nomor_dalam_paket')->contains($n) ? '✓' : '' }}
                    </option>
                    @endfor
                </select>
                <span style="font-size:12px;color:var(--muted)">
                    Rentang modul: {{ $modul->rentang }}
                </span>
            </div>
        </div>

        {{-- Info vocabulary --}}
        <div id="info-vo" style="display:none;background:rgba(217,119,6,.08);
            border:1px solid rgba(217,119,6,.2);border-radius:8px;padding:10px 13px;
            font-size:12.5px;margin-bottom:12px;line-height:1.7">
            🟡 <strong>Vocabulary:</strong> Masukkan kata yang ingin di-highlight di passage
            (gunakan <code>**kata**</code> saat menulis teks di atas).
        </div>

        {{-- Vocabulary fields --}}
        <div id="form-vo" style="display:none">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px">
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12px">Kata yang Di-highlight</label>
                    <input type="text" id="inp-kata" class="form-control"
                        placeholder="cth: pervasive">
                </div>
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12px">Di Paragraf ke-</label>
                    <input type="number" id="inp-paragraf" class="form-control" min="1" value="1">
                </div>
            </div>
        </div>

        {{-- Pertanyaan --}}
        <div class="form-group" id="form-pertanyaan">
            <label class="form-label" style="font-size:12px">Pertanyaan <span style="color:var(--red)">*</span></label>
            <textarea id="inp-pertanyaan" class="form-control" rows="2"
                placeholder="cth: What does the word 'pervasive' mean?"></textarea>
        </div>

        {{-- Pilihan (disembunyikan untuk click_sentence) --}}
        <div id="form-pilihan">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px">
                @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $k=>$l)
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12px">Pilihan {{ $l }}</label>
                    <input type="text" id="inp-p{{ $k }}" class="form-control"
                        placeholder="Pilihan {{ $l }}...">
                </div>
                @endforeach
            </div>
            <div class="form-group">
                <label class="form-label" style="font-size:12px">Jawaban Benar</label>
                <div style="display:flex;gap:6px">
                    @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $k=>$l)
                    <label style="flex:1;text-align:center;padding:8px;border-radius:8px;
                        border:2px solid var(--border);cursor:pointer;font-weight:800;
                        font-size:14px;transition:all .15s" id="jlbl-{{ $k }}"
                        onclick="pilihJawaban('{{ $k }}')">
                        <input type="radio" name="jawaban" id="j-{{ $k }}"
                            value="{{ $k }}" style="display:none">{{ $l }}
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Click sentence: jawaban = kalimat yang diklik --}}
        <div id="form-cs" style="display:none">
            <div class="form-group">
                <label class="form-label" style="font-size:12px">
                    Kalimat Jawaban Benar
                    <small style="color:var(--muted)">(harus sama persis dengan di teks)</small>
                </label>
                <textarea id="inp-cs-jawaban" class="form-control" rows="2"
                    placeholder="Salin kalimat yang benar dari teks passage..."></textarea>
            </div>
        </div>

        <button onclick="simpanSoal()" id="btn-soal" class="btn btn-primary" style="width:100%">
            <i class="fas fa-plus"></i> Tambah Soal
        </button>

        @endif
    </div>
</div>

</div>

{{-- KANAN: Soal yang sudah ada --}}
<div style="position:sticky;top:20px">
    <div class="card">
        <div class="card-header" style="padding:12px 16px">
            <h3 style="font-size:13px">Soal Tersimpan</h3>
            <a href="{{ route('admin.paket-builder.paket', $modul->paket_id) }}"
                class="btn btn-primary btn-sm" style="font-size:12px">
                <i class="fas fa-list"></i> Daftar Soal Paket
            </a>
        </div>
        <div id="soal-list">
            @forelse($modul->soal as $s)
            <div class="soal-saved" id="si-{{ $s->id }}">
                <div style="width:28px;height:28px;border-radius:8px;background:var(--blue);
                    color:#fff;display:flex;align-items:center;justify-content:center;
                    font-size:12px;font-weight:800;flex-shrink:0">{{ $s->nomor_dalam_paket }}</div>
                <div style="flex:1;min-width:0;font-size:12.5px;white-space:nowrap;
                    overflow:hidden;text-overflow:ellipsis">
                    {{ mb_strimwidth($s->pertanyaan??'',0,45,'...') }}
                </div>
                <button onclick="hapusSoal({{ $s->id }})"
                    style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:12px">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @empty
            <div id="empty-list" style="padding:20px;text-align:center;color:var(--muted);font-size:13px">
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
const MODUL_ID   = {{ $modul->id }};
const PASSAGE_ID = {{ $passage?->id ?? 'null' }};
const CSRF       = '{{ csrf_token() }}';
let curTipe      = 'multiple_choice';
let curJawaban   = null;
let curPassageId = PASSAGE_ID;

function pilihTipe(t) {
    curTipe = t;
    ['mc','vo','cs'].forEach(k => document.getElementById('tb-'+k)?.classList.remove('on'));
    document.getElementById('tb-'+(t==='multiple_choice'?'mc':t==='vocabulary'?'vo':'cs'))?.classList.add('on');
    document.getElementById('info-vo').style.display    = t==='vocabulary'?'block':'none';
    document.getElementById('form-vo').style.display    = t==='vocabulary'?'block':'none';
    document.getElementById('form-pilihan').style.display = t==='click_sentence'?'none':'block';
    document.getElementById('form-cs').style.display    = t==='click_sentence'?'block':'none';
}

function pilihJawaban(k) {
    curJawaban = k;
    ['a','b','c','d'].forEach(x => {
        const l = document.getElementById('jlbl-'+x);
        l.style.background   = x===k?'var(--green)':'transparent';
        l.style.borderColor  = x===k?'var(--green)':'var(--border)';
        l.style.color        = x===k?'#fff':'';
    });
}

function simpanPassage() {
    const judul = document.getElementById('p-judul').value.trim();
    const teks  = document.getElementById('p-teks').value.trim();
    if (!teks) return showAlert('Teks passage tidak boleh kosong.','danger');

    const btn = document.getElementById('btn-passage');
    btn.disabled = true; btn.textContent = 'Menyimpan...';

    fetch(`/admin/paket-builder/modul/${MODUL_ID}/passage`, {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
        body: JSON.stringify({judul, teks}),
    }).then(r=>r.json()).then(d=>{
        if (d.ok) { curPassageId=d.id; showAlert('Teks passage disimpan! Sekarang tambah soal.','success'); location.reload(); }
        else showAlert(d.msg,'danger');
    }).finally(()=>{ btn.disabled=false; btn.innerHTML='<i class="fas fa-save"></i> Simpan Teks'; });
}

function simpanSoal() {
    if (!curPassageId) return showAlert('Simpan teks passage dulu.','danger');
    const pertanyaan = document.getElementById('inp-pertanyaan').value.trim();
    if (!pertanyaan) return showAlert('Pertanyaan tidak boleh kosong.','danger');
    if (curTipe !== 'click_sentence' && !curJawaban) return showAlert('Pilih jawaban benar.','danger');

    const body = {
        passage_id:        curPassageId,
        nomor_dalam_paket: parseInt(document.getElementById('inp-nomor').value),
        tipe_soal:         curTipe,
        pertanyaan,
        pilihan_a: document.getElementById('inp-pa')?.value||'-',
        pilihan_b: document.getElementById('inp-pb')?.value||'-',
        pilihan_c: document.getElementById('inp-pc')?.value||'-',
        pilihan_d: document.getElementById('inp-pd')?.value||'-',
        jawaban_benar: curTipe==='click_sentence'
            ? document.getElementById('inp-cs-jawaban').value.trim()
            : curJawaban,
        highlight_kata:     document.getElementById('inp-kata')?.value||null,
        highlight_paragraf: document.getElementById('inp-paragraf')?.value||null,
    };
    if (!body.nomor_dalam_paket) return showAlert('Pilih nomor soal.','danger');

    const btn = document.getElementById('btn-soal');
    btn.disabled=true; btn.textContent='Menyimpan...';

    fetch(`/admin/paket-builder/modul/${MODUL_ID}/soal-passage`, {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
        body: JSON.stringify(body),
    }).then(r=>r.json()).then(d=>{
        if (d.ok) { showAlert(d.msg,'success'); location.reload(); }
        else showAlert(d.msg,'danger');
    }).finally(()=>{ btn.disabled=false; btn.innerHTML='<i class="fas fa-plus"></i> Tambah Soal'; });
}

function hapusSoal(id) {
    if (!confirm('Hapus soal ini?')) return;
    fetch(`/admin/paket-builder/soal/${id}`,{
        method:'DELETE',
        headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
    }).then(r=>r.json()).then(d=>{ if(d.ok) document.getElementById('si-'+id)?.remove(); });
}

function showAlert(msg,type) {
    const el = document.getElementById('alert');
    const colors={success:['rgba(22,163,74,.1)','rgba(22,163,74,.3)','#4ade80'],
                  danger:['rgba(220,38,38,.1)','rgba(220,38,38,.3)','#f87171']};
    const [bg,bd,tc]=colors[type]||colors.danger;
    el.style.cssText=`display:block;background:${bg};border:1px solid ${bd};border-radius:8px;padding:11px 14px;color:${tc};font-size:13px`;
    el.textContent = msg;
    if (type==='success') setTimeout(()=>el.style.display='none',4000);
}
</script>
@endpush
