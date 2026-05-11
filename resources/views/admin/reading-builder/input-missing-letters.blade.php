@extends('layouts.admin')
@section('title', 'Missing Letters — '.$modul->rentang)
@section('page-title', 'Input Missing Letters')
@section('breadcrumb', 'Admin / Reading Builder / Missing Letters')

@section('content')
<div style="max-width:900px">

<div style="display:flex;align-items:center;gap:12px;margin-bottom:18px">
    <a href="{{ route('admin.reading-builder.paket', $paket->id) }}"
        class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <div style="font-size:16px;font-weight:800">🔤 Missing Letters</div>
        <div style="font-size:13px;color:var(--muted)">
            {{ $paket->nama }} &nbsp;·&nbsp; {{ $modul->rentang }}
            <span style="color:var(--accent);font-weight:700">
                ({{ $modul->nomor_soal_selesai - $modul->nomor_soal_mulai + 1 }} blank diperlukan)
            </span>
        </div>
    </div>
</div>

{{-- Soal yang sudah ada --}}
@php $soalAda = $modul->soal->first(); @endphp

@if($soalAda)
<div style="background:rgba(22,163,74,.08);border:1px solid rgba(22,163,74,.25);
    border-radius:10px;padding:14px 18px;margin-bottom:18px;
    display:flex;align-items:center;justify-content:space-between">
    <div>
        <div style="font-weight:700;color:var(--green)">✓ Modul Sudah Diisi</div>
        <div style="font-size:13px;color:var(--muted);margin-top:3px">
            @php preg_match_all('/\[([^\]]+)\]/', $soalAda->fill_text ?? '', $m); @endphp
            {{ count($m[1]) }} blank tersimpan — {{ $modul->rentang }}
        </div>
    </div>
    <button onclick="document.getElementById('form-edit').style.display='block';this.style.display='none'"
        class="btn btn-outline btn-sm">
        <i class="fas fa-edit"></i> Edit
    </button>
</div>
@endif

<div id="form-edit" {{ $soalAda ? 'style=display:none' : '' }}>
<div class="card">
    <div class="card-header">
        <h3 style="font-size:14px">
            <i class="fas fa-pen" style="color:var(--green);margin-right:8px"></i>
            Input Teks dengan Blank
        </h3>
    </div>
    <div class="card-body" style="padding:22px">

        <div style="background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.25);
            border-radius:10px;padding:14px 16px;margin-bottom:18px;font-size:13px;line-height:1.9">
            <strong style="color:#34d399">🔤 Format Input:</strong><br>
            Tulis teks lengkap, tandai bagian yang disembunyikan dengan kurung siku:<br>
            <code style="background:#1e293b;padding:3px 10px;border-radius:4px;
                color:#34d399;font-size:13px;display:inline-block;margin:4px 0">
                Built b[y] tiny ani[mals] called coral polyps, th[ese] reefs gr[ow]...
            </code><br>
            Modul ini membutuhkan tepat
            <strong style="color:#fff">{{ $modul->nomor_soal_selesai - $modul->nomor_soal_mulai + 1 }} blank</strong>
            untuk soal No.{{ $modul->nomor_soal_mulai }}–{{ $modul->nomor_soal_selesai }}.
        </div>

        <div id="alert-msg" style="display:none;margin-bottom:14px"></div>

        <div class="form-group">
            <label class="form-label">
                Teks dengan Blank
                <span id="blank-counter"
                    style="background:rgba(16,185,129,.15);color:#34d399;
                    padding:2px 10px;border-radius:20px;font-size:11.5px;
                    font-weight:700;margin-left:8px">0 blank</span>
                <span id="blank-target"
                    style="color:var(--muted);font-size:12px;margin-left:6px">
                    / {{ $modul->nomor_soal_selesai - $modul->nomor_soal_mulai + 1 }} dibutuhkan
                </span>
            </label>
            <textarea id="fill-input" class="form-control" rows="12"
                style="resize:vertical;font-family:inherit;line-height:1.8"
                oninput="updatePreview(this.value)"
                placeholder="Tulis teks di sini... Gunakan [huruf/kata] untuk setiap blank.

Contoh:
We know from drawings that early humans performed dances as a group activity. We mi[ght] th[ink] th[at] prehistoric peo[ple] concentrated on[ly] on basic survival...">{{ $soalAda?->fill_text }}</textarea>
        </div>

        {{-- Preview real-time --}}
        <div style="margin-bottom:18px">
            <label class="form-label" style="margin-bottom:8px">
                Preview Tampilan User
            </label>
            <div id="preview-wrap" style="background:var(--bg);border:1px solid var(--border);
                border-radius:10px;padding:18px;font-size:14.5px;line-height:2;
                color:#e2e8f0;min-height:60px">
                <span style="color:var(--muted);font-style:italic">
                    Ketik teks di atas untuk melihat preview...
                </span>
            </div>
        </div>

        {{-- Jawaban yang akan disimpan --}}
        <div style="background:rgba(0,0,0,.2);border-radius:8px;padding:12px 14px;margin-bottom:16px">
            <div style="font-size:12px;color:var(--muted);margin-bottom:6px">
                📋 Jawaban yang akan disimpan:
            </div>
            <div id="answers-list" style="font-size:13px;color:#34d399;font-family:monospace">
                —
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Kesulitan</label>
            <select id="difficulty" class="form-control" style="max-width:200px">
                <option value="easy">Easy</option>
                <option value="medium" selected>Medium</option>
                <option value="hard">Hard</option>
            </select>
        </div>

        <button id="btn-simpan" onclick="simpan()" class="btn btn-primary"
            style="min-width:200px">
            <i class="fas fa-save"></i> Simpan ke Paket
        </button>
    </div>
</div>
</div>

</div>
@endsection

@push('scripts')
<script>
const MODUL_ID    = {{ $modul->id }};
const CSRF        = '{{ csrf_token() }}';
const TARGET      = {{ $modul->nomor_soal_selesai - $modul->nomor_soal_mulai + 1 }};

function updatePreview(val) {
    const answers = [];
    const regex   = /\[([^\]]+)\]/g;
    let m;
    while ((m = regex.exec(val)) !== null) answers.push(m[1]);

    // Update counter
    const counter = document.getElementById('blank-counter');
    counter.textContent = answers.length + ' blank';
    counter.style.background = answers.length === TARGET
        ? 'rgba(22,163,74,.2)' : answers.length > TARGET
        ? 'rgba(220,38,38,.2)' : 'rgba(16,185,129,.15)';
    counter.style.color = answers.length === TARGET ? 'var(--green)'
        : answers.length > TARGET ? 'var(--red)' : '#34d399';

    // Render preview
    const parts = val.split(/(\[[^\]]+\])/);
    let html = '';
    parts.forEach(part => {
        const blankMatch = part.match(/^\[([^\]]+)\]$/);
        if (blankMatch) {
            const ans = blankMatch[1];
            const w   = Math.max(28, ans.length * 11);
            html += `<input type="text" disabled placeholder="${'_'.repeat(ans.length)}"
                style="display:inline-block;width:${w}px;border:none;
                border-bottom:2px solid #34d399;background:rgba(52,211,153,.1);
                border-radius:3px 3px 0 0;padding:1px 4px;text-align:center;
                font-size:13px;color:#34d399;font-weight:600;vertical-align:baseline">`;
        } else {
            html += part.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
                        .replace(/\n/g,'<br>');
        }
    });
    document.getElementById('preview-wrap').innerHTML = html || '<span style="color:var(--muted);font-style:italic">Ketik teks di atas untuk melihat preview...</span>';

    // Jawaban list
    document.getElementById('answers-list').innerHTML = answers.length
        ? answers.map((a,i) =>
            `<span style="background:rgba(52,211,153,.1);padding:2px 7px;
            border-radius:4px;margin:2px;display:inline-block">blank ${i+1}: "${a}"</span>`
          ).join('')
        : '—';
}

function simpan() {
    const val = document.getElementById('fill-input').value.trim();
    if (!val) return showAlert('Teks tidak boleh kosong.', 'danger');

    const answers = (val.match(/\[[^\]]+\]/g) || []);
    if (answers.length === 0) return showAlert('Tidak ada blank [...] ditemukan.', 'danger');
    if (answers.length !== TARGET)
        return showAlert(`Jumlah blank (${answers.length}) harus tepat ${TARGET} sesuai rentang soal.`, 'danger');

    const btn = document.getElementById('btn-simpan');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    fetch(`/admin/reading-builder/modul/${MODUL_ID}/missing-letters`, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
        body: JSON.stringify({
            fill_text: val,
            tingkat_kesulitan: document.getElementById('difficulty').value,
        }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            showAlert(data.msg, 'success');
            setTimeout(() => window.location.href = '{{ route("admin.reading-builder.paket", $paket->id) }}', 1500);
        } else {
            showAlert(data.msg, 'danger');
        }
    })
    .catch(() => showAlert('Gagal menyimpan. Coba lagi.', 'danger'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan ke Paket';
    });
}

function showAlert(msg, type) {
    const el = document.getElementById('alert-msg');
    const colors = {success:['rgba(22,163,74,.1)','rgba(22,163,74,.3)','#4ade80'],
                    danger:['rgba(220,38,38,.1)','rgba(220,38,38,.3)','#f87171']};
    const [bg,bd,tc] = colors[type] || colors.danger;
    el.style.cssText = `display:block;background:${bg};border:1px solid ${bd};
        border-radius:8px;padding:10px 14px;color:${tc};font-size:13px`;
    el.innerHTML = msg;
    if (type==='success') setTimeout(()=>el.style.display='none', 5000);
}

// Init preview jika ada nilai lama
const existing = document.getElementById('fill-input').value;
if (existing) updatePreview(existing);
</script>
@endpush
