@extends('layouts.admin')
@section('title', 'Kelola Soal — '.$paket->nama)
@section('page-title', 'Listening: '.$paket->nama)
@section('breadcrumb', 'Admin / Listening / Kelola Soal')

@push('styles')
<style>
/* ─── Layout 2 kolom ─── */
.ls-wrap{display:grid;grid-template-columns:1fr 380px;gap:18px;align-items:start}
@media(max-width:900px){.ls-wrap{grid-template-columns:1fr}}

/* ─── Audio Player Custom ─── */
.audio-panel{background:linear-gradient(135deg,#1a1a3e,#0f0f2e);
    border:1px solid rgba(234,88,12,.3);border-radius:16px;padding:22px;margin-bottom:20px}
.ap-title{font-size:13px;font-weight:700;color:#fb923c;margin-bottom:14px;
    display:flex;align-items:center;gap:8px}
.ap-waveform{height:48px;background:rgba(255,255,255,.05);border-radius:8px;
    position:relative;overflow:hidden;margin-bottom:12px;cursor:pointer}
.ap-waveform-fill{position:absolute;top:0;left:0;height:100%;
    background:linear-gradient(90deg,rgba(234,88,12,.3),rgba(251,146,60,.2));
    width:0%;transition:width .1s linear;pointer-events:none}
.ap-waveform-cursor{position:absolute;top:0;width:2px;height:100%;
    background:#fb923c;left:0%;transition:left .1s linear;pointer-events:none}

/* Marker soal di waveform */
.ap-marker{position:absolute;top:0;width:3px;height:100%;
    background:rgba(34,197,94,.7);cursor:pointer;transition:background .15s}
.ap-marker:hover{background:#4ade80}
.ap-marker-label{position:absolute;top:2px;left:4px;font-size:9px;
    color:#4ade80;font-weight:700;white-space:nowrap}

.ap-controls{display:flex;align-items:center;gap:12px}
.ap-btn{width:38px;height:38px;border-radius:50%;border:none;cursor:pointer;
    display:flex;align-items:center;justify-content:center;transition:all .15s;font-size:14px}
.ap-btn-play{background:#fb923c;color:#fff}
.ap-btn-play:hover{background:#ea580c;transform:scale(1.05)}
.ap-btn-sm{background:rgba(255,255,255,.1);color:rgba(255,255,255,.7);font-size:12px}
.ap-btn-sm:hover{background:rgba(255,255,255,.2)}
.ap-time{font-family:monospace;font-size:14px;color:#fb923c;font-weight:700;margin-left:4px}
.ap-duration{font-family:monospace;font-size:13px;color:rgba(255,255,255,.4)}
.ap-speed{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);
    color:rgba(255,255,255,.7);border-radius:6px;padding:4px 8px;
    font-size:12px;font-family:inherit;cursor:pointer}

/* Tombol capture timestamp */
.btn-capture{background:rgba(234,88,12,.2);color:#fb923c;
    border:1.5px solid rgba(234,88,12,.4);border-radius:8px;
    padding:8px 16px;font-size:13px;font-weight:700;cursor:pointer;
    font-family:inherit;transition:all .15s;display:flex;align-items:center;gap:8px}
.btn-capture:hover{background:rgba(234,88,12,.35)}
.btn-capture.pulse{animation:capturePulse .6s ease}
@keyframes capturePulse{0%,100%{transform:scale(1)}50%{transform:scale(1.05);background:rgba(234,88,12,.5)}}

/* ─── Form tambah soal ─── */
.soal-form-card{background:var(--navy-light);border:1px solid var(--border);
    border-radius:14px;padding:20px}
.soal-form-card h4{font-size:14px;font-weight:700;margin-bottom:14px;
    display:flex;align-items:center;gap:8px}

/* Timestamp display */
.ts-display{background:rgba(234,88,12,.1);border:1.5px solid rgba(234,88,12,.3);
    border-radius:8px;padding:8px 14px;font-family:monospace;font-size:18px;
    font-weight:900;color:#fb923c;text-align:center;letter-spacing:2px}

/* ─── Daftar soal ─── */
.soal-item{border:1px solid var(--border);border-radius:10px;overflow:hidden;
    margin-bottom:8px;transition:border-color .15s}
.soal-item:hover{border-color:var(--accent)}
.soal-item.playing{border-color:#fb923c;background:rgba(234,88,12,.04)}
.soal-header{display:flex;align-items:center;gap:10px;padding:10px 14px;
    background:var(--navy-light);cursor:pointer}
.soal-num{width:26px;height:26px;border-radius:50%;background:var(--blue);
    color:#fff;font-weight:800;font-size:12px;display:flex;align-items:center;
    justify-content:center;flex-shrink:0}
.soal-ts{font-family:monospace;font-size:12px;font-weight:700;
    color:#fb923c;flex-shrink:0;background:rgba(234,88,12,.1);
    padding:2px 8px;border-radius:5px}
.soal-q{font-size:13px;flex:1;min-width:0;
    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:rgba(255,255,255,.8)}
.soal-body{display:none;padding:12px 14px 14px;border-top:1px solid var(--border)}
.soal-body.open{display:block}
.pilihan-row{display:flex;align-items:center;gap:8px;padding:5px 0;font-size:13px}
.pilihan-letter{width:22px;height:22px;border-radius:50%;border:1.5px solid var(--border);
    display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0}
.pilihan-letter.benar{background:var(--green);border-color:var(--green);color:#fff}
</style>
@endpush

@section('content')

{{-- Header info paket --}}
<div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;flex-wrap:wrap">
    <a href="{{ route('admin.listening.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <div style="font-size:18px;font-weight:800">{{ $paket->nama }}</div>
        <div style="font-size:13px;color:var(--muted)">
            <span style="background:rgba(26,86,219,.15);color:var(--accent);
                padding:1px 8px;border-radius:5px;font-size:11px;font-weight:600">
                {{ strtoupper($paket->tipe_paket) }}
            </span>
            &nbsp; {{ $soalList->count() }} soal dari 50
            @if($paket->durasi_detik > 0)
            &nbsp;·&nbsp; {{ $paket->durasi_format }}
            @endif
        </div>
    </div>
</div>

<div class="ls-wrap">

{{-- ════ KOLOM KIRI: Audio + Form Tambah Soal ════ --}}
<div>
    {{-- ── Audio Player ── --}}
    <div class="audio-panel">
        <div class="ap-title">
            <i class="fas fa-headphones"></i> Audio Listening
            <span style="font-size:11px;opacity:.6;margin-left:4px">
                (Admin mode: speed & seek tersedia untuk input soal)
            </span>
        </div>

        {{-- Waveform / progress bar dengan marker soal --}}
        <div class="ap-waveform" id="ap-waveform" onclick="seekAudio(event)">
            <div class="ap-waveform-fill" id="ap-fill"></div>
            <div class="ap-waveform-cursor" id="ap-cursor"></div>
            {{-- Marker soal akan dirender JS --}}
        </div>

        {{-- Controls --}}
        <div class="ap-controls">
            <button class="ap-btn ap-btn-play" id="btn-play" onclick="togglePlay()">
                <i class="fas fa-play" id="play-ico"></i>
            </button>
            <button class="ap-btn ap-btn-sm" onclick="skipAudio(-10)" title="-10 detik">
                <i class="fas fa-undo" style="font-size:11px"></i> 10s
            </button>
            <button class="ap-btn ap-btn-sm" onclick="skipAudio(10)" title="+10 detik">
                10s <i class="fas fa-redo" style="font-size:11px"></i>
            </button>
            <span class="ap-time" id="ap-time">0:00</span>
            <span class="ap-duration" id="ap-dur">/ --:--</span>
            <div style="flex:1"></div>
            <select class="ap-speed" id="ap-speed" onchange="changeSpeed(this.value)">
                <option value="0.5">0.5×</option>
                <option value="0.75">0.75×</option>
                <option value="1" selected>1×</option>
                <option value="1.25">1.25×</option>
                <option value="1.5">1.5×</option>
            </select>
        </div>

        {{-- Audio element (admin bisa pause/seek untuk input soal) --}}
        <audio id="main-audio" src="{{ $paket->audio_url_full }}"
            preload="metadata" style="display:none"
            ontimeupdate="onTick()"
            onloadedmetadata="onAudioReady()">
        </audio>

        {{-- Tombol capture timestamp --}}
        <div style="margin-top:14px;padding-top:14px;border-top:1px solid rgba(255,255,255,.08)">
            <div style="font-size:12px;color:rgba(255,255,255,.4);margin-bottom:8px">
                Putar audio → pause di posisi soal muncul → klik tombol di bawah
            </div>
            <button class="btn-capture" id="btn-capture" onclick="captureTimestamp()">
                <i class="fas fa-crosshairs"></i>
                Ambil Timestamp Sekarang
                <span id="capture-ts" style="font-family:monospace;font-size:15px;
                    background:rgba(0,0,0,.3);padding:2px 8px;border-radius:5px">0:00</span>
            </button>
        </div>
    </div>

    {{-- ── Form Tambah Soal ── --}}
    <div class="soal-form-card" id="form-wrap">
        <h4>
            <i class="fas fa-plus-circle" style="color:var(--green)"></i>
            Tambah Soal Listening
            <span id="form-nomor-badge" style="background:rgba(26,86,219,.15);color:var(--accent);
                padding:2px 10px;border-radius:20px;font-size:12px">
                No. {{ $soalList->count() + 1 }}
            </span>
        </h4>

        {{-- Alert --}}
        <div id="form-alert" style="display:none"></div>

        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:14px">
            {{-- Nomor urut --}}
            <div class="form-group" style="margin:0">
                <label class="form-label" style="font-size:12px">No. Urut</label>
                <input type="number" id="inp-order" class="form-control" style="font-size:14px"
                    min="1" max="50" value="{{ $soalList->count() + 1 }}">
            </div>
            {{-- Part --}}
            <div class="form-group" style="margin:0">
                <label class="form-label" style="font-size:12px">Part</label>
                <select id="inp-part" class="form-control" style="font-size:13px">
                    <option value="A">A — Short Dialogues</option>
                    <option value="B">B — Longer Conv.</option>
                    <option value="C">C — Mini Talks</option>
                </select>
            </div>
            {{-- Group ID --}}
            <div class="form-group" style="margin:0">
                <label class="form-label" style="font-size:12px">Group ID
                    <small style="color:var(--muted)">(Part B/C)</small>
                </label>
                <input type="text" id="inp-group" class="form-control"
                    style="font-size:13px" placeholder="cth: conv-01">
            </div>
        </div>

        {{-- Timestamp --}}
        <div class="form-group">
            <label class="form-label" style="font-size:12px">
                Timestamp (detik soal muncul)
                <span style="color:var(--red)">*</span>
            </label>
            <div style="display:flex;gap:8px;align-items:center">
                <div class="ts-display" id="ts-display">0:00</div>
                <input type="number" id="inp-start-second" class="form-control"
                    style="font-family:monospace;font-size:15px;font-weight:700;width:90px"
                    min="0" placeholder="detik" oninput="updateTsDisplay(this.value)">
                <span style="font-size:12px;color:var(--muted)">detik</span>
            </div>
            <div style="font-size:11.5px;color:var(--muted);margin-top:5px">
                <i class="fas fa-info-circle"></i>
                Putar audio → pause → klik <strong>"Ambil Timestamp"</strong> untuk isi otomatis
            </div>
        </div>

        {{-- Pertanyaan --}}
        <div class="form-group">
            <label class="form-label" style="font-size:12px">
                Pertanyaan <span style="color:var(--red)">*</span>
            </label>
            <textarea id="inp-pertanyaan" class="form-control" rows="2"
                placeholder="cth: What does the woman suggest the man do?"></textarea>
        </div>

        {{-- Script audio --}}
        <div class="form-group">
            <label class="form-label" style="font-size:12px">
                Script Audio
                <small style="color:var(--muted)">— opsional, hanya terlihat admin</small>
            </label>
            <textarea id="inp-script" class="form-control" rows="2"
                placeholder="Transkrip percakapan untuk referensi..."></textarea>
        </div>

        {{-- Pilihan jawaban --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px">
            @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $k=>$l)
            <div class="form-group" style="margin:0">
                <label class="form-label" style="font-size:12px">Pilihan {{ $l }}</label>
                <input type="text" id="inp-pilihan-{{ $k }}" class="form-control"
                    placeholder="Isi pilihan {{ $l }}...">
            </div>
            @endforeach
        </div>

        {{-- Kunci jawaban --}}
        <div class="form-group">
            <label class="form-label" style="font-size:12px">
                Kunci Jawaban <span style="color:var(--red)">*</span>
            </label>
            <div style="display:flex;gap:8px">
                @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $k=>$l)
                <label style="flex:1;display:flex;align-items:center;justify-content:center;
                    gap:6px;padding:9px;border-radius:8px;border:2px solid var(--border);
                    cursor:pointer;font-weight:700;font-size:15px;transition:all .15s"
                    id="kunci-label-{{ $k }}"
                    onclick="pilihKunci('{{ $k }}')">
                    <input type="radio" name="kunci-jawaban" value="{{ $k }}"
                        style="display:none" id="kunci-{{ $k }}">
                    {{ $l }}
                </label>
                @endforeach
            </div>
        </div>

        {{-- Skill / Kesulitan --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:14px">
            <div class="form-group" style="margin:0">
                <label class="form-label" style="font-size:12px">Kesulitan</label>
                <select id="inp-difficulty" class="form-control" style="font-size:13px">
                    <option value="easy">Easy</option>
                    <option value="medium" selected>Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>
            <div class="form-group" style="margin:0">
                <label class="form-label" style="font-size:12px">Skill / Materi</label>
                <input type="text" id="inp-skill" class="form-control"
                    style="font-size:13px" placeholder="cth: Inference, Detail">
            </div>
        </div>

        {{-- Tombol simpan --}}
        <button onclick="simpanSoal()" id="btn-simpan" class="btn btn-primary"
            style="width:100%">
            <i class="fas fa-save"></i> Simpan Soal No. <span id="btn-nomor">{{ $soalList->count() + 1 }}</span>
        </button>
    </div>
</div>

{{-- ════ KOLOM KANAN: Daftar Soal ════ --}}
<div style="position:sticky;top:20px">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list-ol" style="color:var(--accent);margin-right:8px"></i>
                Daftar Soal
            </h3>
            <span id="jumlah-badge" style="font-size:12.5px;color:var(--muted)">
                {{ $soalList->count() }} / 50
            </span>
        </div>

        <div style="max-height:calc(100vh - 200px);overflow-y:auto" id="soal-list-wrap">
            @forelse($soalList as $s)
            <div class="soal-item" id="soal-item-{{ $s->id }}"
                data-second="{{ $s->start_second }}">
                <div class="soal-header" onclick="toggleSoalBody({{ $s->id }})">
                    <div class="soal-num">{{ $s->order_number }}</div>
                    <div class="soal-ts" onclick="event.stopPropagation();seekToSecond({{ $s->start_second }})">
                        {{ sprintf('%d:%02d', intdiv($s->start_second,60), $s->start_second%60) }}
                    </div>
                    <div class="soal-q">{{ $s->pertanyaan }}</div>
                    <button onclick="event.stopPropagation();hapusSoal({{ $s->id }})"
                        style="background:none;border:none;color:var(--muted);cursor:pointer;
                        padding:2px 6px;border-radius:4px;font-size:12px"
                        title="Hapus">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="soal-body" id="body-{{ $s->id }}">
                    @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $k=>$l)
                    <div class="pilihan-row">
                        <div class="pilihan-letter {{ $s->jawaban_benar === $k ? 'benar' : '' }}">
                            {{ $l }}
                        </div>
                        <span style="{{ $s->jawaban_benar === $k ? 'color:var(--green);font-weight:700' : '' }}">
                            {{ $s->{'pilihan_'.$k} }}
                        </span>
                    </div>
                    @endforeach
                    @if($s->audio_script)
                    <div style="margin-top:8px;padding:8px;background:rgba(0,0,0,.2);
                        border-radius:6px;font-size:12px;color:var(--muted);font-style:italic">
                        <i class="fas fa-align-left" style="font-size:10px"></i>
                        {{ $s->audio_script }}
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div id="empty-state" style="padding:32px;text-align:center;color:var(--muted);font-size:13px">
                <i class="fas fa-plus-circle" style="font-size:28px;display:block;margin-bottom:10px"></i>
                Belum ada soal. Tambah soal pertama menggunakan form di sebelah kiri.
            </div>
            @endforelse
        </div>

        {{-- Progress footer --}}
        @php $pct = min(100, ($soalList->count() / 50) * 100); @endphp
        <div style="padding:12px 16px;border-top:1px solid var(--border)">
            <div style="display:flex;justify-content:space-between;
                font-size:12px;color:var(--muted);margin-bottom:5px">
                <span>Progress</span>
                <span id="progress-text">{{ $soalList->count() }} / 50</span>
            </div>
            <div style="height:5px;background:var(--border);border-radius:3px">
                <div id="progress-fill" style="height:5px;border-radius:3px;
                    width:{{ $pct }}%;
                    background:{{ $pct >= 100 ? 'var(--green)' : 'var(--accent)' }};
                    transition:width .4s"></div>
            </div>
        </div>
    </div>
</div>

</div>{{-- end ls-wrap --}}
@endsection

@push('scripts')
<script>
const PAKET_ID   = {{ $paket->id }};
const CSRF_TOKEN = '{{ csrf_token() }}';
let soalCount    = {{ $soalList->count() }};
let selectedKey  = null;

// ═══════════════════════════════════════════
// AUDIO PLAYER
// ═══════════════════════════════════════════
const audio  = document.getElementById('main-audio');
const fill   = document.getElementById('ap-fill');
const cursor = document.getElementById('ap-cursor');
const timeEl = document.getElementById('ap-time');
const durEl  = document.getElementById('ap-dur');

function onAudioReady() {
    const dur = audio.duration;
    durEl.textContent = '/ ' + fmtTime(dur);

    // Update durasi ke server
    fetch(`/admin/listening/${PAKET_ID}/durasi`, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF_TOKEN},
        body: JSON.stringify({durasi_detik: Math.round(dur)}),
    });

    // Render marker soal di waveform
    renderMarkers(dur);
}

function onTick() {
    const cur = audio.currentTime;
    const dur = audio.duration || 1;
    const pct = (cur / dur) * 100;
    fill.style.width   = pct + '%';
    cursor.style.left  = pct + '%';
    timeEl.textContent = fmtTime(cur);

    // Update capture button display
    document.getElementById('capture-ts').textContent = fmtTime(cur);

    // Highlight soal yang sedang aktif
    highlightActiveSoal(cur);
}

function togglePlay() {
    if (audio.paused) {
        audio.play();
        document.getElementById('play-ico').className = 'fas fa-pause';
    } else {
        audio.pause();
        document.getElementById('play-ico').className = 'fas fa-play';
    }
}

function seekAudio(e) {
    const rect = document.getElementById('ap-waveform').getBoundingClientRect();
    const pct  = (e.clientX - rect.left) / rect.width;
    audio.currentTime = pct * audio.duration;
}

function seekToSecond(s) {
    audio.currentTime = s;
    if (audio.paused) audio.play();
    document.getElementById('play-ico').className = 'fas fa-pause';
}

function skipAudio(s) {
    audio.currentTime = Math.max(0, Math.min(audio.duration, audio.currentTime + s));
}

function changeSpeed(v) {
    audio.playbackRate = parseFloat(v);
}

function fmtTime(s) {
    if (!s || isNaN(s)) return '0:00';
    const m = Math.floor(s / 60);
    const sec = Math.floor(s % 60);
    return m + ':' + String(sec).padStart(2,'0');
}

// ═══════════════════════════════════════════
// MARKERS DI WAVEFORM
// ═══════════════════════════════════════════
function renderMarkers(dur) {
    const wf = document.getElementById('ap-waveform');
    // Hapus marker lama
    wf.querySelectorAll('.ap-marker').forEach(m => m.remove());

    document.querySelectorAll('.soal-item').forEach(item => {
        const sec = parseInt(item.dataset.second || 0);
        const pct = (sec / dur) * 100;
        const mk  = document.createElement('div');
        mk.className = 'ap-marker';
        mk.style.left = pct + '%';
        mk.title = 'Soal: ' + fmtTime(sec);
        mk.onclick = (e) => { e.stopPropagation(); seekToSecond(sec); };
        wf.appendChild(mk);
    });
}

function highlightActiveSoal(cur) {
    let activeId = null;
    document.querySelectorAll('.soal-item').forEach(item => {
        const sec = parseInt(item.dataset.second || 0);
        item.classList.remove('playing');
        if (sec <= cur) activeId = item.id;
    });
    if (activeId) {
        const el = document.getElementById(activeId);
        if (el) el.classList.add('playing');
    }
}

// ═══════════════════════════════════════════
// CAPTURE TIMESTAMP
// ═══════════════════════════════════════════
function captureTimestamp() {
    const sec = Math.round(audio.currentTime);
    document.getElementById('inp-start-second').value = sec;
    document.getElementById('ts-display').textContent = fmtTime(sec);

    // Animasi tombol
    const btn = document.getElementById('btn-capture');
    btn.classList.add('pulse');
    setTimeout(() => btn.classList.remove('pulse'), 600);

    // Focus ke field pertanyaan
    document.getElementById('inp-pertanyaan').focus();
}

function updateTsDisplay(val) {
    const sec = parseInt(val) || 0;
    document.getElementById('ts-display').textContent = fmtTime(sec);
}

// ═══════════════════════════════════════════
// KUNCI JAWABAN
// ═══════════════════════════════════════════
function pilihKunci(k) {
    selectedKey = k;
    ['a','b','c','d'].forEach(x => {
        const lbl = document.getElementById('kunci-label-' + x);
        lbl.style.background   = x === k ? 'var(--green)'  : 'transparent';
        lbl.style.borderColor  = x === k ? 'var(--green)'  : 'var(--border)';
        lbl.style.color        = x === k ? '#fff'          : '';
    });
    document.getElementById('kunci-' + k).checked = true;
}

// ═══════════════════════════════════════════
// SIMPAN SOAL
// ═══════════════════════════════════════════
function simpanSoal() {
    // Validasi
    const fields = {
        pertanyaan:    document.getElementById('inp-pertanyaan').value.trim(),
        pilihan_a:     document.getElementById('inp-pilihan-a').value.trim(),
        pilihan_b:     document.getElementById('inp-pilihan-b').value.trim(),
        pilihan_c:     document.getElementById('inp-pilihan-c').value.trim(),
        pilihan_d:     document.getElementById('inp-pilihan-d').value.trim(),
        jawaban_benar: selectedKey,
        start_second:  parseInt(document.getElementById('inp-start-second').value) || 0,
        order_number:  parseInt(document.getElementById('inp-order').value) || (soalCount + 1),
        audio_script:  document.getElementById('inp-script').value.trim(),
        part:          document.getElementById('inp-part').value,
        group_id:      document.getElementById('inp-group').value.trim(),
        tingkat_kesulitan: document.getElementById('inp-difficulty').value,
        skill_materi:  document.getElementById('inp-skill').value.trim(),
    };

    if (!fields.pertanyaan) return showAlert('Pertanyaan wajib diisi.', 'danger');
    if (!fields.pilihan_a || !fields.pilihan_b || !fields.pilihan_c || !fields.pilihan_d)
        return showAlert('Semua pilihan A–D wajib diisi.', 'danger');
    if (!fields.jawaban_benar)
        return showAlert('Pilih kunci jawaban terlebih dahulu.', 'danger');

    const btn = document.getElementById('btn-simpan');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    fetch(`/admin/listening/${PAKET_ID}/soal`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
        body: JSON.stringify(fields),
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            showAlert('Soal No.' + fields.order_number + ' berhasil disimpan!', 'success');
            resetForm();
            // Reload daftar soal
            location.reload();
        } else {
            showAlert(data.msg || 'Gagal menyimpan.', 'danger');
        }
    })
    .catch(() => showAlert('Terjadi kesalahan. Coba lagi.', 'danger'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Soal No. <span id="btn-nomor">' + (soalCount + 1) + '</span>';
    });
}

function resetForm() {
    ['pertanyaan','script','pilihan-a','pilihan-b','pilihan-c','pilihan-d','group','skill'].forEach(id => {
        const el = document.getElementById('inp-' + id);
        if (el) el.value = '';
    });
    document.getElementById('inp-start-second').value = '';
    document.getElementById('ts-display').textContent = '0:00';
    selectedKey = null;
    ['a','b','c','d'].forEach(k => {
        const lbl = document.getElementById('kunci-label-' + k);
        lbl.style.background  = 'transparent';
        lbl.style.borderColor = 'var(--border)';
        lbl.style.color       = '';
    });
}

// ═══════════════════════════════════════════
// HAPUS SOAL
// ═══════════════════════════════════════════
function hapusSoal(id) {
    if (!confirm('Hapus soal ini?')) return;
    fetch(`/admin/listening/soal/${id}`, {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json'},
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            document.getElementById('soal-item-' + id)?.remove();
            soalCount--;
            updateProgress();
            if (audio.duration) renderMarkers(audio.duration);
        }
    });
}

function updateProgress() {
    document.getElementById('jumlah-badge').textContent = soalCount + ' / 50';
    document.getElementById('progress-text').textContent = soalCount + ' / 50';
    const pct = Math.min(100, (soalCount / 50) * 100);
    document.getElementById('progress-fill').style.width = pct + '%';
    document.getElementById('progress-fill').style.background = pct >= 100 ? 'var(--green)' : 'var(--accent)';
}

// ═══════════════════════════════════════════
// TOGGLE SOAL BODY
// ═══════════════════════════════════════════
function toggleSoalBody(id) {
    const body = document.getElementById('body-' + id);
    if (body) body.classList.toggle('open');
}

// ═══════════════════════════════════════════
// ALERT
// ═══════════════════════════════════════════
function showAlert(msg, type) {
    const el = document.getElementById('form-alert');
    const colors = {
        success: ['rgba(22,163,74,.12)','rgba(22,163,74,.3)','#4ade80'],
        danger:  ['rgba(220,38,38,.12)','rgba(220,38,38,.3)','#f87171'],
        warning: ['rgba(217,119,6,.12)','rgba(217,119,6,.3)','#fbbf24'],
    }[type] || colors.danger;

    el.style.cssText = `display:block;background:${colors[0]};border:1px solid ${colors[1]};
        border-radius:8px;padding:10px 14px;font-size:13px;color:${colors[2]};margin-bottom:12px`;
    el.innerHTML = `<i class="fas fa-${type==='success'?'check-circle':type==='warning'?'exclamation-triangle':'times-circle'}"></i> ${msg}`;

    if (type === 'success') setTimeout(() => el.style.display = 'none', 4000);
}
</script>
@endpush
