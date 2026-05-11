@extends('layouts.admin')
@section('title','Missing Letters')
@section('page-title','Missing Letters — '.$modul->rentang)
@section('breadcrumb','Admin / Paket Builder / Missing Letters')

@section('content')
<div style="max-width:860px">
<div style="display:flex;align-items:center;gap:12px;margin-bottom:18px">
    <a href="{{ route('admin.paket-builder.paket', $modul->paket_id) }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <div style="font-size:16px;font-weight:800">🔤 Missing Letters</div>
        <div style="font-size:13px;color:var(--muted)">
            {{ $modul->paket?->nama }} · {{ $modul->rentang }}
            <strong style="color:var(--accent)">({{ $modul->jumlah_target }} blank dibutuhkan)</strong>
        </div>
    </div>
</div>

<div id="alert" style="display:none;margin-bottom:14px"></div>

@php $soalAda = $modul->soal->first(); @endphp
@if($soalAda)
<div style="background:rgba(22,163,74,.08);border:1px solid rgba(22,163,74,.25);
    border-radius:10px;padding:14px 18px;margin-bottom:18px;
    display:flex;align-items:center;justify-content:space-between">
    <div>
        <div style="font-weight:700;color:var(--green)">✓ Sudah diisi</div>
        @php preg_match_all('/\[([^\]]+)\]/', $soalAda->fill_text??'', $mx); @endphp
        <div style="font-size:13px;color:var(--muted)">
            {{ count($mx[1]) }} blank · {{ $modul->rentang }}
        </div>
    </div>
    <div style="display:flex;gap:8px">
        <button onclick="document.getElementById('main-form').style.display='block';this.parentElement.parentElement.style.display='none'"
            class="btn btn-outline btn-sm"><i class="fas fa-edit"></i> Edit</button>
        <a href="{{ route('admin.paket-builder.paket', $modul->paket_id) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-list"></i> Daftar Soal Paket
        </a>
    </div>
</div>
@endif

<div id="main-form" {{ $soalAda?'style=display:none':'' }}>
<div class="card">
    <div class="card-header">
        <h3 style="font-size:14px">Input Teks dengan Blank</h3>
    </div>
    <div class="card-body" style="padding:22px">
        <div style="background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);
            border-radius:10px;padding:13px 16px;margin-bottom:18px;font-size:13px;line-height:1.9">
            <strong style="color:#34d399">Format:</strong>
            Tulis teks, tandai blank dengan <code>[huruf/kata]</code>:<br>
            <code style="background:#1e293b;padding:3px 10px;border-radius:4px;color:#34d399;display:inline-block;margin:4px 0">
                Built b[y] tiny ani[mals] called coral polyps, th[ese] reefs gr[ow]...
            </code><br>
            Modul ini butuh tepat <strong style="color:#fff">{{ $modul->jumlah_target }} blank</strong>
            untuk soal {{ $modul->rentang }}.
        </div>

        <div class="form-group">
            <label class="form-label">
                Teks dengan Blank
                <span id="blank-count" style="background:rgba(16,185,129,.15);color:#34d399;
                    padding:2px 10px;border-radius:20px;font-size:11.5px;font-weight:700;margin-left:8px">
                    0 blank
                </span>
                <span style="font-size:12px;color:var(--muted)">
                    / {{ $modul->jumlah_target }} dibutuhkan
                </span>
            </label>
            <textarea id="fill-input" class="form-control" rows="12"
                style="resize:vertical;line-height:1.8" oninput="updatePreview(this.value)"
                placeholder="Tulis teks di sini...">{{ $soalAda?->fill_text }}</textarea>
        </div>

        <div style="margin-bottom:18px">
            <label class="form-label">Preview Tampilan User</label>
            <div id="preview" style="background:var(--bg);border:1px solid var(--border);
                border-radius:10px;padding:16px;font-size:14.5px;line-height:2;
                min-height:60px;color:#e2e8f0">
                <span style="color:var(--muted);font-style:italic">Ketik teks di atas...</span>
            </div>
        </div>

        <div style="background:rgba(0,0,0,.2);border-radius:8px;padding:11px 14px;margin-bottom:16px">
            <div style="font-size:12px;color:var(--muted);margin-bottom:5px">Jawaban yang disimpan:</div>
            <div id="answers" style="font-size:12.5px;color:#34d399;font-family:monospace">—</div>
        </div>

        <div style="display:flex;gap:10px">
            <button onclick="simpan()" id="btn-simpan" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah ke Daftar Soal Paket
            </button>
            <a href="{{ route('admin.paket-builder.paket', $modul->paket_id) }}" class="btn btn-outline">
                Batal
            </a>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
const MODUL_ID = {{ $modul->id }};
const TARGET   = {{ $modul->jumlah_target }};
const CSRF     = '{{ csrf_token() }}';

function updatePreview(val) {
    const answers = [...val.matchAll(/\[([^\]]+)\]/g)].map(m=>m[1]);
    const el = document.getElementById('blank-count');
    el.textContent = answers.length + ' blank';
    el.style.color = answers.length===TARGET?'#4ade80':answers.length>TARGET?'#f87171':'#34d399';

    const parts = val.split(/(\[[^\]]+\])/);
    let html = '';
    parts.forEach(p => {
        const m = p.match(/^\[([^\]]+)\]$/);
        if (m) {
            const w = Math.max(28, m[1].length*11);
            html += `<input disabled placeholder="${'_'.repeat(m[1].length)}" style="display:inline-block;width:${w}px;border:none;border-bottom:2px solid #34d399;background:rgba(52,211,153,.1);border-radius:3px 3px 0 0;padding:1px 4px;text-align:center;font-size:13px;color:#34d399;font-weight:600;vertical-align:baseline">`;
        } else {
            html += p.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
        }
    });
    document.getElementById('preview').innerHTML = html || '<span style="color:var(--muted);font-style:italic">Ketik teks di atas...</span>';
    document.getElementById('answers').innerHTML = answers.length
        ? answers.map((a,i)=>`<span style="background:rgba(52,211,153,.1);padding:2px 7px;border-radius:4px;margin:2px;display:inline-block">blank ${i+1}: "${a}"</span>`).join('')
        : '—';
}

function simpan() {
    const val = document.getElementById('fill-input').value.trim();
    if (!val) return alert('Teks tidak boleh kosong.');
    const blanks = [...val.matchAll(/\[[^\]]+\]/g)].length;
    if (!blanks) return showAlert('Tidak ada blank [...] ditemukan.','danger');
    if (blanks!==TARGET) return showAlert(`Jumlah blank (${blanks}) harus tepat ${TARGET}.`,'danger');

    const btn = document.getElementById('btn-simpan');
    btn.disabled=true; btn.textContent='Menyimpan...';

    fetch(`/admin/paket-builder/modul/${MODUL_ID}/missing-letters`,{
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
        body: JSON.stringify({fill_text:val}),
    }).then(r=>r.json()).then(d=>{
        if (d.ok) { showAlert(d.msg,'success'); setTimeout(()=>window.location.href=d.redirect,1200); }
        else showAlert(d.msg,'danger');
    }).finally(()=>{ btn.disabled=false; btn.innerHTML='<i class="fas fa-plus"></i> Tambah ke Daftar Soal Paket'; });
}

function showAlert(msg,type) {
    const el=document.getElementById('alert');
    const c={success:['rgba(22,163,74,.1)','rgba(22,163,74,.3)','#4ade80'],danger:['rgba(220,38,38,.1)','rgba(220,38,38,.3)','#f87171']}[type];
    el.style.cssText=`display:block;background:${c[0]};border:1px solid ${c[1]};border-radius:8px;padding:11px 14px;color:${c[2]};font-size:13px`;
    el.textContent=msg;
}
const ex = document.getElementById('fill-input').value;
if (ex) updatePreview(ex);
</script>
@endpush
