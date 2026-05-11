@extends('layouts.admin')
@section('title','Input Listening — '.$modul->tipe_label)
@section('page-title','Input Soal Listening')
@section('breadcrumb','Admin / Paket Builder / Listening')

@push('styles')
<style>
.tl-row{display:flex;align-items:center;gap:10px;padding:10px 16px;
    border-bottom:1px solid var(--border);transition:background .1s}
.tl-row:last-child{border-bottom:none}
.no-b{width:30px;height:30px;border-radius:8px;background:#fb923c;
    color:#fff;display:flex;align-items:center;justify-content:center;
    font-size:12px;font-weight:800;flex-shrink:0}
.ts-b{font-family:monospace;font-size:12px;font-weight:700;color:#fb923c;
    background:rgba(234,88,12,.1);padding:2px 8px;border-radius:5px;
    cursor:pointer;flex-shrink:0}
.ts-b:hover{background:rgba(234,88,12,.2)}
.kunci-row{display:flex;gap:6px}
.kunci-lbl{flex:1;text-align:center;padding:9px;border-radius:8px;
    border:2px solid var(--border);cursor:pointer;font-weight:800;
    font-size:14px;transition:all .15s;user-select:none}
.kunci-lbl.on{background:var(--green);border-color:var(--green);color:#fff}
</style>
@endpush

@section('content')

<div style="display:flex;align-items:center;gap:12px;margin-bottom:18px">
    <a href="{{ route('admin.paket-builder.paket', $modul->paket_id) }}"
        class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i></a>
    <div style="flex:1">
        <div style="font-size:16px;font-weight:800">
            {{ $modul->tipe_label }} — {{ $modul->rentang }}
        </div>
        <div style="font-size:13px;color:var(--muted)">
            {{ $modul->paket?->nama }}
            &nbsp;·&nbsp; Target: {{ $modul->jumlah_target }} soal
            &nbsp;·&nbsp;
            <span style="color:{{ $modul->soal->count() >= $modul->jumlah_target ? 'var(--green)' : 'var(--muted)' }}">
                {{ $modul->soal->count() }} tersimpan
            </span>
        </div>
    </div>
</div>

<div id="alert-box" style="display:none;margin-bottom:14px"></div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:18px;align-items:start">

{{-- ═══ KIRI: Audio + Form Input ═══ --}}
<div>

{{-- ── STEP 1: Pilih / Info Audio ── --}}
<div class="card" style="margin-bottom:16px">
    <div class="card-header" style="padding:12px 18px">
        <h3 style="font-size:13px;display:flex;align-items:center;gap:8px">
            <span style="background:#fb923c;color:#fff;width:22px;height:22px;border-radius:50%;
                display:flex;align-items:center;justify-content:center;
                font-size:11px;font-weight:900;flex-shrink:0">1</span>
            Audio Full Listening
        </h3>
        @if($modul->audioPaket)
        <span style="font-size:12px;color:var(--green)">
            <i class="fas fa-check-circle"></i> {{ $modul->audioPaket->nama }}
        </span>
        @endif
    </div>
    <div class="card-body" style="padding:16px 18px">

        {{-- Peringatan jika belum ada audio --}}
        @if($audioPaketList->isEmpty())
        <div style="background:rgba(220,38,38,.08);border:1px solid rgba(220,38,38,.2);
            border-radius:8px;padding:12px;font-size:13px;color:var(--red);margin-bottom:12px">
            <i class="fas fa-exclamation-triangle"></i>
            Belum ada audio. <a href="{{ route('admin.listening.create') }}"
            target="_blank" style="color:var(--red);font-weight:700">Upload audio dulu →</a>
        </div>
        @endif

        <div style="display:flex;gap:10px;align-items:flex-end">
            <div class="form-group" style="flex:1;margin:0">
                <label class="form-label" style="font-size:12px">Pilih Audio Full</label>
                <select id="sel-audio" class="form-control" onchange="pilihAudio(this)">
                    <option value="">-- Pilih audio --</option>
                    @foreach($audioPaketList as $ap)
                    <option value="{{ $ap->id }}"
                        data-url="{{ $ap->audio_url_full }}"
                        data-nama="{{ $ap->nama }}"
                        data-durasi="{{ $ap->durasi_detik }}"
                        {{ $modul->audio_paket_id == $ap->id ? 'selected' : '' }}>
                        {{ $ap->nama }} ({{ $ap->durasi_format }})
                    </option>
                    @endforeach
                </select>
            </div>
            <a href="{{ route('admin.listening.create') }}" target="_blank"
                class="btn btn-outline btn-sm" style="flex-shrink:0">
                <i class="fas fa-upload"></i> Upload Baru
            </a>
        </div>

        {{-- Audio player — mode admin (boleh pause/seek/speed) --}}
        <div id="audio-panel" style="margin-top:14px;
            {{ $modul->audioPaket ? '' : 'display:none' }}">
            <div style="background:rgba(234,88,12,.06);border:1px solid rgba(234,88,12,.2);
                border-radius:10px;padding:14px">
                <div style="font-size:11.5px;color:rgba(255,255,255,.4);margin-bottom:8px;
                    display:flex;align-items:center;gap:6px">
                    <i class="fas fa-headphones" style="color:#fb923c"></i>
                    Mode Admin — boleh pause, seek & speed untuk membantu input soal
                </div>
                <audio id="main-audio" controls preload="metadata"
                    style="width:100%;border-radius:6px;height:36px"
                    ontimeupdate="onTick()" onloadedmetadata="onAudioReady()">
                    @if($modul->audioPaket)
                    <source src="{{ $modul->audioPaket->audio_url_full }}">
                    @endif
                </audio>
                <div style="display:flex;justify-content:space-between;align-items:center;
                    margin-top:10px;flex-wrap:wrap;gap:8px">
                    <div style="font-family:monospace;font-size:13px;color:#fb923c">
                        <span id="cur-time">0:00</span>
                        <span style="color:rgba(255,255,255,.3)"> / </span>
                        <span id="dur-time">--:--</span>
                    </div>
                    <button onclick="captureTimestamp()" id="btn-capture"
                        style="background:rgba(234,88,12,.2);color:#fb923c;
                        border:1.5px solid rgba(234,88,12,.4);border-radius:8px;
                        padding:7px 14px;font-size:12.5px;font-weight:700;
                        cursor:pointer;font-family:inherit;display:flex;align-items:center;gap:7px">
                        <i class="fas fa-crosshairs"></i>
                        Ambil Timestamp Sekarang
                        <span id="cap-ts" style="font-family:monospace;
                            background:rgba(0,0,0,.35);padding:1px 8px;border-radius:4px;
                            letter-spacing:1px">0:00</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── STEP 2: Form Tambah Soal ── --}}
<div class="card">
    <div class="card-header" style="padding:12px 18px">
        <h3 style="font-size:13px;display:flex;align-items:center;gap:8px">
            <span style="background:#fb923c;color:#fff;width:22px;height:22px;border-radius:50%;
                display:flex;align-items:center;justify-content:center;
                font-size:11px;font-weight:900;flex-shrink:0">2</span>
            Tambah Soal
        </h3>
        <span style="font-size:12px;color:var(--muted)">
            {{ $modul->soal->count() }} / {{ $modul->jumlah_target }} soal
        </span>
    </div>
    <div class="card-body" style="padding:16px 18px">

        {{-- Nomor + Timestamp --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px">
            <div class="form-group" style="margin:0">
                <label class="form-label" style="font-size:12px">Nomor Soal</label>
                <select id="inp-nomor" class="form-control">
                    @for($n = $modul->nomor_soal_mulai; $n <= $modul->nomor_soal_selesai; $n++)
                    @php $sudahAda = $modul->soal->pluck('nomor_dalam_paket')->contains($n); @endphp
                    <option value="{{ $n }}" {{ $sudahAda ? 'disabled' : '' }}>
                        No.{{ $n }}{{ $sudahAda ? ' ✓' : '' }}
                    </option>
                    @endfor
                </select>
            </div>
            <div class="form-group" style="margin:0">
                <label class="form-label" style="font-size:12px">
                    Start Second <span style="color:var(--red)">*</span>
                    <small style="color:var(--muted)">— detik audio soal muncul</small>
                </label>
                <input type="number" id="inp-second" class="form-control"
                    min="0" placeholder="cth: 80"
                    style="font-family:monospace;font-size:15px;font-weight:700"
                    oninput="updateTsDisplay(this.value)">
                <div style="font-family:monospace;font-size:16px;font-weight:900;
                    color:#fb923c;margin-top:6px;text-align:center;
                    background:rgba(234,88,12,.08);border-radius:6px;padding:4px">
                    <span id="ts-display">0:00</span>
                </div>
            </div>
        </div>

        {{-- Pertanyaan --}}
        <div class="form-group">
            <label class="form-label" style="font-size:12px">
                Pertanyaan <span style="color:var(--red)">*</span>
            </label>
            <textarea id="inp-q" class="form-control" rows="2"
                placeholder="cth: What does the woman suggest the man do?"></textarea>
        </div>

        {{-- Script audio (opsional) --}}
        <div class="form-group">
            <label class="form-label" style="font-size:12px">
                Script Audio
                <small style="color:var(--muted)">(opsional — hanya terlihat admin)</small>
            </label>
            <textarea id="inp-script" class="form-control" rows="2"
                placeholder="Transkrip percakapan untuk referensi admin..."></textarea>
        </div>

        {{-- Pilihan A B C D --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px">
            @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $k=>$l)
            <div class="form-group" style="margin:0">
                <label class="form-label" style="font-size:12px">Pilihan {{ $l }}</label>
                <input type="text" id="inp-p{{ $k }}" class="form-control"
                    placeholder="Pilihan {{ $l }}...">
            </div>
            @endforeach
        </div>

        {{-- Kunci jawaban --}}
        <div class="form-group">
            <label class="form-label" style="font-size:12px">
                Jawaban Benar <span style="color:var(--red)">*</span>
            </label>
            <div class="kunci-row">
                @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $k=>$l)
                <label class="kunci-lbl" id="kl-{{ $k }}" onclick="pilihKunci('{{ $k }}')">
                    <input type="radio" name="kunci" value="{{ $k }}" style="display:none">
                    {{ $l }}
                </label>
                @endforeach
            </div>
        </div>

        <button onclick="simpanSoal()" id="btn-save" class="btn btn-primary"
            style="width:100%;margin-top:4px">
            <i class="fas fa-save"></i> Simpan Soal
        </button>
    </div>
</div>

</div>

{{-- ═══ KANAN: Timeline Soal ═══ --}}
<div style="position:sticky;top:20px">
    <div class="card">
        <div class="card-header" style="padding:12px 16px">
            <h3 style="font-size:13px">
                <i class="fas fa-timeline" style="color:#fb923c;margin-right:6px"></i>
                Timeline Soal
            </h3>
            <a href="{{ route('admin.paket-builder.paket', $modul->paket_id) }}"
                class="btn btn-primary btn-sm" style="font-size:11px">
                Daftar Soal Paket →
            </a>
        </div>

        {{-- Info audio --}}
        @if($modul->audioPaket)
        <div style="padding:8px 16px;background:rgba(234,88,12,.05);
            border-bottom:1px solid var(--border);font-size:12px;color:var(--muted)">
            <i class="fas fa-music" style="color:#fb923c"></i>
            {{ $modul->audioPaket->nama }}
            &nbsp;·&nbsp; {{ $modul->audioPaket->durasi_format }}
        </div>
        @endif

        {{-- Header kolom --}}
        <div style="padding:6px 16px;border-bottom:1px solid var(--border);
            display:flex;gap:10px;font-size:11px;color:var(--muted)">
            <span style="width:30px">No.</span>
            <span style="width:52px">Detik</span>
            <span style="flex:1">Pertanyaan</span>
            <span style="width:48px"></span>
        </div>

        <div id="soal-list" style="max-height:60vh;overflow-y:auto">
            @forelse($modul->soal->sortBy('start_second') as $s)
            <div class="tl-row" id="si-{{ $s->id }}">
                <div class="no-b">{{ $s->nomor_dalam_paket }}</div>
                <div class="ts-b" onclick="seekTo({{ $s->start_second }})"
                    title="Klik untuk putar dari detik ini">
                    {{ sprintf('%d:%02d', intdiv($s->start_second,60), $s->start_second%60) }}
                </div>
                <div style="flex:1;min-width:0;font-size:12.5px;
                    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
                    color:rgba(255,255,255,.8)">
                    {{ mb_strimwidth($s->pertanyaan??'', 0, 40, '...') }}
                </div>
                <div style="display:flex;gap:4px;flex-shrink:0">
                    <button onclick="seekTo({{ $s->start_second }})"
                        style="background:none;border:none;color:#fb923c;
                        cursor:pointer;font-size:12px;padding:2px 4px" title="Putar">
                        <i class="fas fa-play-circle"></i>
                    </button>
                    <button onclick="hapusSoal({{ $s->id }})"
                        style="background:none;border:none;color:var(--muted);
                        cursor:pointer;font-size:12px;padding:2px 4px" title="Hapus">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            @empty
            <div style="padding:28px;text-align:center;color:var(--muted);font-size:13px">
                <i class="fas fa-clock" style="display:block;font-size:24px;margin-bottom:8px"></i>
                Belum ada soal. Isi form di sebelah kiri.
            </div>
            @endforelse
        </div>

        {{-- Progress bar --}}
        @php $pct = min(100, ($modul->soal->count() / max(1,$modul->jumlah_target)) * 100); @endphp
        <div style="padding:10px 16px;border-top:1px solid var(--border)">
            <div style="display:flex;justify-content:space-between;
                font-size:11.5px;color:var(--muted);margin-bottom:4px">
                <span>Progress</span>
                <span>{{ $modul->soal->count() }} / {{ $modul->jumlah_target }}</span>
            </div>
            <div style="height:5px;background:var(--border);border-radius:3px">
                <div style="height:5px;border-radius:3px;width:{{ $pct }}%;
                    background:{{ $pct >= 100 ? 'var(--green)' : '#fb923c' }};
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
const audio     = document.getElementById('main-audio');
let   curKunci  = null;
let   curAudioId= {{ $modul->audio_paket_id ?? 'null' }};

// ── Audio functions ──────────────────────────────────────────
function pilihAudio(sel) {
    const opt = sel.options[sel.selectedIndex];
    curAudioId = sel.value;
    if (!sel.value) return;
    audio.src = opt.dataset.url;
    document.getElementById('audio-panel').style.display = 'block';
    audio.load();
}

function onAudioReady() {
    document.getElementById('dur-time').textContent = fmtTime(audio.duration);
}

function onTick() {
    const cur = audio.currentTime;
    document.getElementById('cur-time').textContent = fmtTime(cur);
    document.getElementById('cap-ts').textContent   = fmtTime(cur);
}

function captureTimestamp() {
    const sec = Math.round(audio.currentTime);
    document.getElementById('inp-second').value = sec;
    updateTsDisplay(sec);
    // Animasi tombol
    const btn = document.getElementById('btn-capture');
    btn.style.background = 'rgba(234,88,12,.4)';
    setTimeout(() => btn.style.background = 'rgba(234,88,12,.2)', 400);
    document.getElementById('inp-q').focus();
}

function updateTsDisplay(val) {
    document.getElementById('ts-display').textContent = fmtTime(parseInt(val)||0);
}

function seekTo(sec) {
    audio.currentTime = sec;
    if (audio.paused) audio.play();
}

function fmtTime(s) {
    if (!s || isNaN(s)) return '0:00';
    return Math.floor(s/60) + ':' + String(Math.floor(s%60)).padStart(2,'0');
}

// ── Kunci jawaban ─────────────────────────────────────────────
function pilihKunci(k) {
    curKunci = k;
    ['a','b','c','d'].forEach(x => {
        const l = document.getElementById('kl-'+x);
        if (l) l.className = 'kunci-lbl' + (x===k?' on':'');
    });
}

// ── Simpan soal ───────────────────────────────────────────────
function simpanSoal() {
    const nomor = parseInt(document.getElementById('inp-nomor').value);
    const sec   = parseInt(document.getElementById('inp-second').value) || 0;
    const q     = document.getElementById('inp-q').value.trim();

    if (!q)        return showAlert('Pertanyaan tidak boleh kosong.', 'danger');
    if (!curKunci) return showAlert('Pilih jawaban benar (A/B/C/D).', 'danger');

    const btn = document.getElementById('btn-save');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    fetch(`/admin/paket-builder/modul/${MODUL_ID}/soal-listening`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept':       'application/json',
        },
        body: JSON.stringify({
            audio_paket_id:    curAudioId,
            nomor_dalam_paket: nomor,
            start_second:      sec,
            pertanyaan:        q,
            audio_script:      document.getElementById('inp-script').value,
            pilihan_a: document.getElementById('inp-pa').value || '-',
            pilihan_b: document.getElementById('inp-pb').value || '-',
            pilihan_c: document.getElementById('inp-pc').value || '-',
            pilihan_d: document.getElementById('inp-pd').value || '-',
            jawaban_benar: curKunci,
        }),
    })
    .then(r => r.json())
    .then(d => {
        if (d.ok) {
            showAlert(d.msg, 'success');
            resetForm();
            location.reload();
        } else {
            showAlert(d.msg, 'danger');
        }
    })
    .catch(e => showAlert('Error: ' + e.message, 'danger'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Soal';
    });
}

function resetForm() {
    document.getElementById('inp-q').value      = '';
    document.getElementById('inp-script').value = '';
    document.getElementById('inp-second').value = '';
    document.getElementById('ts-display').textContent = '0:00';
    ['a','b','c','d'].forEach(k => {
        const el = document.getElementById('inp-p'+k);
        if (el) el.value = '';
        const lbl = document.getElementById('kl-'+k);
        if (lbl) lbl.className = 'kunci-lbl';
    });
    curKunci = null;
}

// ── Hapus soal ────────────────────────────────────────────────
function hapusSoal(id) {
    if (!confirm('Hapus soal ini?')) return;
    fetch(`/admin/paket-builder/soal/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    }).then(r => r.json()).then(d => {
        if (d.ok) document.getElementById('si-'+id)?.remove();
    });
}

// ── Alert ─────────────────────────────────────────────────────
function showAlert(msg, type) {
    const el = document.getElementById('alert-box');
    const colors = {
        success: ['rgba(22,163,74,.1)',  'rgba(22,163,74,.3)',  '#4ade80'],
        danger:  ['rgba(220,38,38,.1)', 'rgba(220,38,38,.3)', '#f87171'],
    }[type] || ['rgba(26,86,219,.1)', 'rgba(26,86,219,.3)', '#93c5fd'];
    el.style.cssText = `display:block;background:${colors[0]};border:1px solid ${colors[1]};
        border-radius:8px;padding:11px 14px;color:${colors[2]};font-size:13px`;
    el.textContent = msg;
    if (type === 'success') setTimeout(() => el.style.display = 'none', 4000);
}
</script>
@endpush
