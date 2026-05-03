@extends('layouts.admin')
@section('title','Buat Sesi')
@section('page-title','Buat Sesi Tes Baru')
@section('breadcrumb','Admin / Sesi Tes / Buat')

@push('styles')
<style>
/* ─── Tipe Cards ─────────────────────────────────────────── */
.tipe-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 20px;
}
.tipe-card {
    border: 2px solid rgba(255,255,255,.08);
    border-radius: 12px;
    padding: 18px 14px;
    cursor: pointer;
    transition: all .18s;
    text-align: center;
    position: relative;
    user-select: none;
}
.tipe-card:hover {
    border-color: rgba(26,86,219,.45);
    background: rgba(26,86,219,.06);
}
.tipe-card.selected {
    border-color: var(--accent);
    background: rgba(26,86,219,.11);
}
.tipe-card.selected::after {
    content: '\f00c';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    position: absolute;
    top: 9px; right: 11px;
    font-size: 10px;
    color: var(--accent);
}
.tipe-card .tc-emoji  { font-size: 28px; display: block; margin-bottom: 8px; line-height: 1; }
.tipe-card .tc-name   { font-size: 14px; font-weight: 700; color: #f1f5f9; margin-bottom: 5px; }
.tipe-card .tc-spec   { font-size: 11px; color: rgba(255,255,255,.38); line-height: 1.6; }
.tipe-card .tc-badge  {
    display: inline-block;
    margin-top: 10px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 10.5px;
    font-weight: 700;
}

/* ─── Config summary box ─────────────────────────────────── */
.cfg-box {
    background: rgba(26,86,219,.07);
    border: 1px solid rgba(26,86,219,.18);
    border-radius: 10px;
    padding: 14px 16px;
    transition: all .2s;
}
.cfg-box-title {
    font-size: 12px; font-weight: 700;
    color: rgba(255,255,255,.45);
    text-transform: uppercase; letter-spacing: .8px;
    margin-bottom: 5px;
    display: flex; align-items: center; gap: 7px;
}
.cfg-box-desc { font-size: 13px; color: rgba(255,255,255,.6); line-height: 1.6; }

/* ─── Lock tag ───────────────────────────────────────────── */
.lock-tag {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 2px 8px; border-radius: 20px;
    font-size: 10px; font-weight: 700;
    background: rgba(34,197,94,.1);
    border: 1px solid rgba(34,197,94,.22);
    color: #4ade80;
}

/* ─── Soal config grid cards ─────────────────────────────── */
.soal-cfg-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 12px;
}
.soal-cfg-card {
    border-radius: 10px; padding: 14px;
    border: 1px solid rgba(255,255,255,.07);
    text-align: center;
}
.soal-cfg-card .scc-lbl {
    font-size: 10.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .6px; margin-bottom: 6px;
}
.soal-cfg-card .scc-num  { font-size: 30px; font-weight: 900; line-height: 1; margin-bottom: 3px; }
.soal-cfg-card .scc-unit { font-size: 11px; color: rgba(255,255,255,.35); }
.soal-cfg-card .scc-avail {
    margin-top: 7px; font-size: 10.5px;
    padding: 2px 8px; border-radius: 10px; display: inline-block;
}
.scc-warn {
    font-size: 11px; color: #f87171; margin-top: 5px;
    display: none; align-items: center; justify-content: center; gap: 4px;
}
.scc-warn.show { display: flex; }

/* ─── Durasi breakdown chips ─────────────────────────────── */
.dur-chips { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 7px; }
.dur-chip {
    font-size: 11px; padding: 2px 9px; border-radius: 5px;
    background: rgba(255,255,255,.06); color: rgba(255,255,255,.45);
}

/* ─── Checkbox row ───────────────────────────────────────── */
.cb-row {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 14px; border-radius: 8px;
    background: rgba(255,255,255,.03);
    border: 1px solid rgba(255,255,255,.06);
    flex: 1; min-width: 200px;
}
.cb-row .cb-text { font-size: 13px; color: rgba(255,255,255,.65); flex: 1; }
.cb-row .cb-lock { font-size: 10px; color: rgba(255,255,255,.2); }

/* ─── Readonly style ─────────────────────────────────────── */
.form-control[readonly] {
    opacity: .65;
    cursor: not-allowed;
    background: rgba(255,255,255,.025) !important;
    border-color: rgba(255,255,255,.05) !important;
}
</style>
@endpush

@section('content')
<div style="max-width:820px">

@if($errors->any())
<div class="alert alert-danger" style="margin-bottom:16px">
    <i class="fas fa-exclamation-circle"></i>
    <div>@foreach($errors->all() as $e)<div>{{ "• $e" }}</div>@endforeach</div>
</div>
@endif

<form action="{{ route('admin.sesi.store') }}" method="POST" id="sesi-form">
@csrf

{{-- ══ 1. TIPE TES ═══════════════════════════════════════ --}}
<div class="card" style="margin-bottom:16px">
    <div class="card-header">
        <h3>
            <i class="fas fa-layer-group" style="color:var(--accent);margin-right:8px"></i>
            1. Pilih Tipe Tes
        </h3>
        <a href="{{ route('admin.sesi.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">

        <input type="hidden" name="tipe_tes" id="tipe-val" value="{{ old('tipe_tes', 'full') }}">

        <div class="tipe-cards">
            <div class="tipe-card {{ old('tipe_tes','full')==='full' ? 'selected':'' }}"
                id="tc-full" onclick="selectTipe('full')">
                <span class="tc-emoji">🎓</span>
                <div class="tc-name">Full (TOEFL ITP)</div>
                <div class="tc-spec">Tes resmi standar internasional<br>50 + 40 + 50 = 140 soal</div>
                <span class="tc-badge" style="background:rgba(26,86,219,.2);color:#93c5fd">115 menit</span>
            </div>
            <div class="tipe-card {{ old('tipe_tes','full')==='simulasi' ? 'selected':'' }}"
                id="tc-simulasi" onclick="selectTipe('simulasi')">
                <span class="tc-emoji">🧪</span>
                <div class="tc-name">Simulasi</div>
                <div class="tc-spec">Prediction test mirip TOEFL<br>30 + 20 + 30 = 80 soal</div>
                <span class="tc-badge" style="background:rgba(99,102,241,.2);color:#a5b4fc">65 menit</span>
            </div>
            <div class="tipe-card {{ old('tipe_tes','full')==='mini' ? 'selected':'' }}"
                id="tc-mini" onclick="selectTipe('mini')">
                <span class="tc-emoji">⚡</span>
                <div class="tc-name">Mini Test</div>
                <div class="tc-spec">Latihan singkat harian<br>15 + 10 + 15 = 40 soal</div>
                <span class="tc-badge" style="background:rgba(16,185,129,.15);color:#6ee7b7">30 menit</span>
            </div>
        </div>

        <div class="cfg-box" id="cfg-summary">
            <div class="cfg-box-title">
                <i class="fas fa-check-circle" style="color:#4ade80;font-size:11px"></i>
                <span id="cfg-sum-title">Konfigurasi: Full (TOEFL ITP)</span>
            </div>
            <div class="cfg-box-desc" id="cfg-sum-desc">
                Standar resmi TOEFL ITP — 50 Listening · 40 Structure · 50 Reading.
                Soal diacak otomatis dengan Fisher-Yates Shuffle.
            </div>
        </div>
    </div>
</div>

{{-- ══ 2. IDENTITAS ═══════════════════════════════════════ --}}
<div class="card" style="margin-bottom:16px">
    <div class="card-header">
        <h3><i class="fas fa-id-card" style="color:var(--accent);margin-right:8px"></i>2. Identitas Sesi</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label class="form-label">Judul Sesi <span style="color:var(--red)">*</span></label>
            <input type="text" name="judul" class="form-control" required
                value="{{ old('judul') }}" placeholder="cth: TOEFL ITP Full Test — Mei 2026">
        </div>
        <div class="form-group" style="margin-bottom:0">
            <label class="form-label">Deskripsi <span style="font-weight:400;color:rgba(255,255,255,.3)">(opsional)</span></label>
            <textarea name="deskripsi" class="form-control" rows="2"
                placeholder="Catatan tambahan untuk sesi ini...">{{ old('deskripsi') }}</textarea>
        </div>
    </div>
</div>

{{-- ══ 3. WAKTU & KUOTA ════════════════════════════════════ --}}
<div class="card" style="margin-bottom:16px">
    <div class="card-header">
        <h3><i class="fas fa-clock" style="color:var(--accent);margin-right:8px"></i>3. Waktu & Kuota</h3>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">

            <div class="form-group" style="margin-bottom:0">
                <label class="form-label" style="display:flex;align-items:center;gap:8px">
                    Durasi Total
                    <span class="lock-tag"><i class="fas fa-lock" style="font-size:8px"></i> Auto</span>
                </label>
                <div style="position:relative">
                    <input type="number" name="durasi_menit" id="durasi-menit"
                        class="form-control" readonly value="{{ old('durasi_menit', 115) }}">
                    <span style="position:absolute;right:13px;top:50%;transform:translateY(-50%);
                        font-size:12px;color:rgba(255,255,255,.28)">menit</span>
                </div>
                <div class="dur-chips" id="dur-chips">
                    <span class="dur-chip">🎧 35 mnt</span>
                    <span class="dur-chip">✏️ 25 mnt</span>
                    <span class="dur-chip">📖 55 mnt</span>
                </div>
            </div>

            <div class="form-group" style="margin-bottom:0">
                <label class="form-label" style="display:flex;align-items:center;gap:8px">
                    Kuota Peserta
                    <span style="font-size:10.5px;color:rgba(255,255,255,.35);font-weight:400"
                        id="kuota-note">(bisa diubah)</span>
                </label>
                <div style="position:relative">
                    <input type="number" name="kuota_peserta" id="kuota-peserta"
                        class="form-control" value="{{ old('kuota_peserta', 50) }}"
                        min="1" placeholder="50">
                    <span style="position:absolute;right:13px;top:50%;transform:translateY(-50%);
                        font-size:12px;color:rgba(255,255,255,.28)">orang</span>
                </div>
                <div style="font-size:11.5px;color:rgba(255,255,255,.28);margin-top:5px"
                    id="kuota-hint">Sesuaikan dengan kapasitas ruangan</div>
            </div>

        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">Waktu Mulai <span style="color:var(--red)">*</span></label>
                <input type="datetime-local" name="waktu_mulai" id="waktu-mulai"
                    class="form-control" required
                    value="{{ old('waktu_mulai') }}" onchange="autoSetSelesai()">
            </div>

            <div class="form-group" style="margin-bottom:0">
                <label class="form-label" style="display:flex;align-items:center;gap:8px">
                    Waktu Selesai
                    <span class="lock-tag">
                        <i class="fas fa-bolt" style="font-size:8px"></i> Auto-generate
                    </span>
                </label>
                <input type="datetime-local" name="waktu_selesai" id="waktu-selesai"
                    class="form-control" readonly value="{{ old('waktu_selesai') }}">
                <div style="font-size:11px;color:rgba(255,255,255,.25);margin-top:5px">
                    = Waktu mulai + durasi (otomatis)
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ══ 4. KONFIGURASI SOAL (auto, locked) ═════════════════ --}}
<div style="background:var(--navy-light);border:1px solid rgba(255,255,255,.07);
    border-radius:12px;overflow:hidden;margin-bottom:16px">

    <div style="padding:13px 18px;border-bottom:1px solid rgba(255,255,255,.07);
        display:flex;align-items:center;gap:12px">
        <div id="cfg-icon" style="width:32px;height:32px;border-radius:8px;
            display:flex;align-items:center;justify-content:center;font-size:14px;
            background:rgba(26,86,219,.18);color:#93c5fd;flex-shrink:0">
            <i class="fas fa-layer-group"></i>
        </div>
        <div>
            <div style="font-size:13px;font-weight:700;color:#f1f5f9" id="cfg-panel-title">
                Konfigurasi Soal — Full (TOEFL ITP)
            </div>
            <div style="font-size:11px;color:rgba(255,255,255,.32)">
                Fisher-Yates Shuffle aktif untuk Tes Full
            </div>
        </div>
        <span class="lock-tag" style="margin-left:auto">
            <i class="fas fa-lock" style="font-size:8px"></i> Standar TOEFL
        </span>
    </div>

    {{-- Hidden inputs dikirim ke server --}}
    <input type="hidden" name="jumlah_soal_listening" id="h-listening" value="50">
    <input type="hidden" name="jumlah_soal_structure"  id="h-structure"  value="40">
    <input type="hidden" name="jumlah_soal_reading"    id="h-reading"    value="50">

    <div style="padding:16px 18px">
        <div class="soal-cfg-grid">

            <div class="soal-cfg-card"
                style="background:rgba(234,88,12,.07);border-color:rgba(234,88,12,.2)">
                <div class="scc-lbl" style="color:#fdba74">
                    <i class="fas fa-headphones-alt" style="font-size:10px"></i> Listening
                </div>
                <div class="scc-num" id="disp-listening" style="color:#fdba74">50</div>
                <div class="scc-unit">soal</div>
                <div class="scc-avail" id="avail-listening"
                    style="background:rgba(234,88,12,.1);color:rgba(251,146,60,.65)">
                    {{ $totalListening }} tersedia
                </div>
                <div class="scc-warn" id="warn-listening">
                    <i class="fas fa-exclamation-triangle" style="font-size:10px"></i>
                    Soal tidak cukup!
                </div>
            </div>

            <div class="soal-cfg-card"
                style="background:rgba(217,119,6,.07);border-color:rgba(217,119,6,.2)">
                <div class="scc-lbl" style="color:#fde68a">
                    <i class="fas fa-pen-nib" style="font-size:10px"></i> Structure
                </div>
                <div class="scc-num" id="disp-structure" style="color:#fde68a">40</div>
                <div class="scc-unit">soal</div>
                <div class="scc-avail" id="avail-structure"
                    style="background:rgba(217,119,6,.1);color:rgba(251,191,36,.65)">
                    {{ $totalStructure }} tersedia
                </div>
                <div class="scc-warn" id="warn-structure">
                    <i class="fas fa-exclamation-triangle" style="font-size:10px"></i>
                    Soal tidak cukup!
                </div>
            </div>

            <div class="soal-cfg-card"
                style="background:rgba(26,86,219,.07);border-color:rgba(26,86,219,.2)">
                <div class="scc-lbl" style="color:#93c5fd">
                    <i class="fas fa-book-open" style="font-size:10px"></i> Reading
                </div>
                <div class="scc-num" id="disp-reading" style="color:#93c5fd">50</div>
                <div class="scc-unit">soal</div>
                <div class="scc-avail" id="avail-reading"
                    style="background:rgba(26,86,219,.1);color:rgba(147,197,253,.65)">
                    {{ $totalReading }} tersedia
                </div>
                <div class="scc-warn" id="warn-reading">
                    <i class="fas fa-exclamation-triangle" style="font-size:10px"></i>
                    Soal tidak cukup!
                </div>
            </div>

        </div>

        <div style="padding:10px 14px;background:rgba(255,255,255,.03);border-radius:8px;
            display:flex;align-items:center;justify-content:space-between;font-size:13px">
            <span style="color:rgba(255,255,255,.4)">Total Soal</span>
            <span style="font-weight:800;color:#f1f5f9" id="disp-total">140 soal</span>
        </div>
    </div>
</div>

{{-- ══ 5. PENGATURAN TAMPILAN ══════════════════════════════ --}}
<div class="card" style="margin-bottom:16px">
    <div class="card-header">
        <h3><i class="fas fa-eye" style="color:var(--accent);margin-right:8px"></i>4. Pengaturan Tampilan</h3>
        <span class="lock-tag"><i class="fas fa-lock" style="font-size:8px"></i> Auto per tipe</span>
    </div>
    <div class="card-body">
        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:10px">

            <div class="cb-row">
                <input type="checkbox" name="tampilkan_hasil" id="cb-hasil"
                    value="1" checked
                    style="width:15px;height:15px;accent-color:var(--accent);pointer-events:none">
                <span class="cb-text">Tampilkan hasil ke user</span>
                <i class="fas fa-lock cb-lock"></i>
            </div>

            <div class="cb-row">
                <input type="checkbox" name="tampilkan_pembahasan" id="cb-pembahasan"
                    value="1"
                    style="width:15px;height:15px;accent-color:var(--accent)">
                <span class="cb-text">Tampilkan pembahasan</span>
                <i class="fas fa-lock cb-lock" id="lock-pembahasan"></i>
            </div>

        </div>
        <div style="font-size:11.5px;color:rgba(255,255,255,.28)" id="cb-note">
            <i class="fas fa-info-circle"></i>
            Full Test: pembahasan tidak ditampilkan ke user sesuai standar TOEFL ITP.
        </div>
    </div>
</div>

{{-- Notifikasi nonaktif --}}
<div style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.22);
    border-radius:10px;padding:12px 16px;margin-bottom:18px;
    display:flex;align-items:center;gap:10px;font-size:13px;color:var(--gold)">
    <i class="fas fa-moon"></i>
    <div>
        Sesi dibuat dalam status <strong>Nonaktif</strong>.
        Aktifkan secara manual tepat saat hari tes berlangsung.
    </div>
</div>

<div style="display:flex;gap:12px;align-items:center">
    <button type="submit" class="btn btn-primary" style="padding:11px 30px;font-size:15px">
        <i class="fas fa-save"></i> Buat Sesi
    </button>
    <a href="{{ route('admin.sesi.index') }}" class="btn btn-outline" style="padding:11px 22px">Batal</a>
</div>

</form>
</div>
@endsection

@push('scripts')
<script>
// ════════════════════════════════════════════════════════════════════
// PRESET STANDAR TOEFL — sumber kebenaran tunggal di sisi client
// Cerminan dari TIPE_PRESET di SesiTesController.php
// ════════════════════════════════════════════════════════════════════
const PRESET = {
    full: {
        label      : 'Full (TOEFL ITP)',
        durasi     : 115,
        listening  : 50,
        structure  : 40,
        reading    : 50,
        kuota      : 50,
        hasil      : true,
        pembahasan : false,
        breakdown  : ['🎧 35 mnt', '✏️ 25 mnt', '📖 55 mnt'],
        desc       : 'Standar resmi TOEFL ITP — 50 Listening · 40 Structure · 50 Reading. ' +
                     'Fisher-Yates Shuffle mengacak soal otomatis saat sesi dibuat.',
        color      : '#93c5fd',
        iconBg     : 'rgba(26,86,219,.18)',
    },
    simulasi: {
        label      : 'Simulasi',
        durasi     : 65,
        listening  : 30,
        structure  : 20,
        reading    : 30,
        kuota      : 50,
        hasil      : true,
        pembahasan : true,
        breakdown  : ['🎧 20 mnt', '✏️ 15 mnt', '📖 30 mnt'],
        desc       : 'Prediction test mirip TOEFL ITP — 30 Listening · 20 Structure · 30 Reading ' +
                     'dalam 65 menit. Pembahasan ditampilkan setelah selesai.',
        color      : '#a5b4fc',
        iconBg     : 'rgba(99,102,241,.18)',
    },
    mini: {
        label      : 'Mini Test',
        durasi     : 30,
        listening  : 15,
        structure  : 10,
        reading    : 15,
        kuota      : 0,
        hasil      : true,
        pembahasan : true,
        breakdown  : ['🎧 10 mnt', '✏️ 8 mnt', '📖 12 mnt'],
        desc       : 'Latihan singkat harian — 15 Listening · 10 Structure · 15 Reading ' +
                     'dalam 30 menit. Cocok untuk persiapan rutin.',
        color      : '#6ee7b7',
        iconBg     : 'rgba(16,185,129,.18)',
    },
};

// Ketersediaan soal aktif dari server (hanya baca, tidak bisa dimanipulasi)
const AVAIL = {
    listening : {{ $totalListening }},
    structure : {{ $totalStructure }},
    reading   : {{ $totalReading }},
};

let activeTipe = '{{ old("tipe_tes", "full") }}';

// ── Pilih tipe ─────────────────────────────────────────────
function selectTipe(tipe) {
    activeTipe = tipe;
    document.getElementById('tipe-val').value = tipe;
    ['full', 'simulasi', 'mini'].forEach(t =>
        document.getElementById('tc-' + t).classList.toggle('selected', t === tipe)
    );
    applyPreset(tipe);
}

// ── Apply semua konfigurasi sesuai preset ──────────────────
function applyPreset(tipe) {
    const p = PRESET[tipe];
    if (!p) return;

    // Durasi (readonly)
    document.getElementById('durasi-menit').value = p.durasi;
    document.getElementById('dur-chips').innerHTML =
        p.breakdown.map(b => '<span class="dur-chip">' + b + '</span>').join('');

    // Waktu selesai (auto)
    autoSetSelesai();

    // Kuota
    const kuotaEl = document.getElementById('kuota-peserta');
    if (p.kuota > 0) {
        kuotaEl.value = p.kuota;
        kuotaEl.setAttribute('required', '');
        document.getElementById('kuota-hint').textContent = 'Sesuaikan dengan kapasitas ruangan';
    } else {
        kuotaEl.value       = '';
        kuotaEl.placeholder = 'Opsional';
        kuotaEl.removeAttribute('required');
        document.getElementById('kuota-hint').textContent = 'Mini Test tidak memerlukan kuota tetap';
    }

    // Jumlah soal — hidden fields + display cards
    document.getElementById('h-listening').value        = p.listening;
    document.getElementById('h-structure').value         = p.structure;
    document.getElementById('h-reading').value           = p.reading;
    document.getElementById('disp-listening').textContent = p.listening;
    document.getElementById('disp-structure').textContent  = p.structure;
    document.getElementById('disp-reading').textContent    = p.reading;
    document.getElementById('disp-total').textContent =
        (p.listening + p.structure + p.reading) + ' soal';

    // Warning jika soal kurang
    ['listening', 'structure', 'reading'].forEach(function(k) {
        const el = document.getElementById('warn-' + k);
        if (AVAIL[k] < p[k]) el.classList.add('show');
        else                  el.classList.remove('show');
    });

    // Checkbox tampilan
    const cbHasil  = document.getElementById('cb-hasil');
    const cbPemb   = document.getElementById('cb-pembahasan');
    const lockPemb = document.getElementById('lock-pembahasan');
    const cbNote   = document.getElementById('cb-note');

    cbHasil.checked = p.hasil;
    cbPemb.checked  = p.pembahasan;

    if (tipe === 'full') {
        cbPemb.setAttribute('disabled', '');
        lockPemb.style.display = 'inline';
        cbNote.innerHTML = '<i class="fas fa-info-circle"></i> ' +
            'Full Test: pembahasan tidak ditampilkan ke user sesuai standar TOEFL ITP.';
    } else {
        cbPemb.removeAttribute('disabled');
        lockPemb.style.display = 'none';
        cbNote.innerHTML = '<i class="fas fa-info-circle"></i> ' +
            'Simulasi & Mini: pembahasan ditampilkan sebagai bahan evaluasi.';
    }

    // Summary & panel header
    document.getElementById('cfg-sum-title').textContent  = 'Konfigurasi: ' + p.label;
    document.getElementById('cfg-sum-desc').textContent   = p.desc;
    document.getElementById('cfg-panel-title').textContent = 'Konfigurasi Soal — ' + p.label;
    const iconEl              = document.getElementById('cfg-icon');
    iconEl.style.background   = p.iconBg;
    iconEl.style.color        = p.color;
}

// ── Auto-generate waktu selesai = waktu mulai + durasi ─────
function autoSetSelesai() {
    const mulaiVal = document.getElementById('waktu-mulai').value;
    const durasi   = parseInt(document.getElementById('durasi-menit').value) || 0;
    if (!mulaiVal || !durasi) return;

    const mulai   = new Date(mulaiVal);
    const selesai = new Date(mulai.getTime() + durasi * 60000);
    const pad     = function(n) { return String(n).padStart(2, '0'); };

    document.getElementById('waktu-selesai').value =
        selesai.getFullYear() + '-' +
        pad(selesai.getMonth() + 1) + '-' +
        pad(selesai.getDate()) + 'T' +
        pad(selesai.getHours()) + ':' +
        pad(selesai.getMinutes());
}

// ── Validasi submit ────────────────────────────────────────
document.getElementById('sesi-form').addEventListener('submit', function(e) {
    if (!document.getElementById('waktu-mulai').value) {
        e.preventDefault();
        alert('Waktu mulai wajib diisi.');
        document.getElementById('waktu-mulai').focus();
        return;
    }
    const p     = PRESET[activeTipe];
    const short = ['listening', 'structure', 'reading']
        .filter(function(k) { return AVAIL[k] < p[k]; })
        .map(function(k) {
            return k.charAt(0).toUpperCase() + k.slice(1) +
                ': butuh ' + p[k] + ', tersedia ' + AVAIL[k];
        });
    if (short.length) {
        if (!confirm('Peringatan — soal di bank tidak mencukupi:\n\n' +
            short.join('\n') + '\n\nLanjutkan membuat sesi?')) {
            e.preventDefault();
        }
    }
});

// ── Init ───────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() { applyPreset(activeTipe); });
</script>
@endpush