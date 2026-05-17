@extends('layouts.admin')
@section('title', 'Listening — '.$paket->nama)
@section('page-title', $paket->nama)
@section('breadcrumb', 'Admin / Listening / Kelola Soal')

@push('styles')
<style>
/* ─── Layout ─── */
.ls-wrap{display:grid;grid-template-columns:1fr 360px;gap:18px;align-items:start}

/* ─── Audio Panel ─── */
.audio-panel{background:linear-gradient(135deg,#0f1a2e,#0a1628);
    border:1px solid rgba(234,88,12,.25);border-radius:16px;padding:20px;margin-bottom:16px}
.ap-title{font-size:12px;font-weight:700;color:#fb923c;margin-bottom:14px;
    display:flex;align-items:center;gap:8px;text-transform:uppercase;letter-spacing:.5px}

/* Waveform container */
#waveform-container{position:relative;height:64px;background:rgba(255,255,255,.03);
    border-radius:8px;overflow:hidden;cursor:pointer;margin-bottom:12px;
    border:1px solid rgba(255,255,255,.06)}
#waveform-bg{position:absolute;inset:0;display:flex;align-items:center;
    gap:1px;padding:4px 2px}
.wv-bar{flex:1;background:rgba(251,146,60,.25);border-radius:1px;
    transition:background .1s;min-width:2px}
#waveform-progress{position:absolute;top:0;left:0;height:100%;
    background:rgba(234,88,12,.2);transition:width .1s linear;pointer-events:none}
#waveform-cursor{position:absolute;top:0;width:2px;height:100%;
    background:#fb923c;pointer-events:none;transition:left .1s linear}
.wv-marker{position:absolute;top:0;height:100%;width:2px;cursor:pointer}
.wv-marker-start{background:rgba(34,197,94,.8)}
.wv-marker-end{background:rgba(239,68,68,.8)}
.wv-marker-label{position:absolute;top:2px;left:4px;font-size:9px;
    font-weight:700;white-space:nowrap}

/* Controls */
.ap-controls{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.ap-btn{width:36px;height:36px;border-radius:50%;border:none;cursor:pointer;
    display:flex;align-items:center;justify-content:center;font-size:13px;transition:all .15s}
.ap-play{background:#fb923c;color:#fff}
.ap-play:hover{background:#ea580c;transform:scale(1.05)}
.ap-sm{background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);font-size:11px}
.ap-sm:hover{background:rgba(255,255,255,.14)}
.ap-time{font-family:monospace;font-size:14px;font-weight:700;color:#fb923c}
.ap-dur{font-family:monospace;font-size:12px;color:rgba(255,255,255,.3)}
.ap-speed{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);
    color:rgba(255,255,255,.6);border-radius:6px;padding:4px 8px;
    font-size:12px;font-family:inherit;cursor:pointer}

/* Set marker buttons */
.btn-set{padding:7px 14px;border-radius:7px;border:none;cursor:pointer;
    font-family:inherit;font-size:12px;font-weight:700;transition:all .15s;
    display:flex;align-items:center;gap:6px}
.btn-start{background:rgba(34,197,94,.2);color:#4ade80;border:1.5px solid rgba(34,197,94,.35)}
.btn-start:hover{background:rgba(34,197,94,.3)}
.btn-end{background:rgba(239,68,68,.2);color:#f87171;border:1.5px solid rgba(239,68,68,.35)}
.btn-end:hover{background:rgba(239,68,68,.3)}

/* Marker display */
.marker-row{display:flex;gap:10px;margin-top:10px}
.marker-box{flex:1;background:rgba(255,255,255,.04);border-radius:8px;padding:8px 12px;
    border:1px solid rgba(255,255,255,.08)}
.marker-lbl{font-size:10px;text-transform:uppercase;letter-spacing:.5px;
    color:var(--muted);margin-bottom:3px}
.marker-val{font-family:monospace;font-size:16px;font-weight:900}

/* ─── Form soal ─── */
.soal-form{background:var(--navy-light);border:1px solid var(--border);
    border-radius:14px;padding:20px}
.kunci-row{display:flex;gap:6px}
.kunci-lbl{flex:1;text-align:center;padding:9px;border-radius:8px;
    border:2px solid var(--border);cursor:pointer;font-weight:800;
    font-size:14px;transition:all .15s;user-select:none;background:transparent}
.kunci-lbl.on{background:var(--green);border-color:var(--green);color:#fff}

/* ─── Soal list ─── */
.soal-item{display:flex;align-items:flex-start;gap:10px;padding:12px 16px;
    border-bottom:1px solid var(--border);cursor:pointer;transition:background .1s}
.soal-item:hover{background:rgba(255,255,255,.02)}
.soal-item.active{background:rgba(234,88,12,.06);border-left:3px solid #fb923c}
.soal-item:last-child{border-bottom:none}
.si-no{width:28px;height:28px;border-radius:7px;background:#fb923c;color:#fff;
    display:flex;align-items:center;justify-content:center;
    font-size:11px;font-weight:800;flex-shrink:0;margin-top:1px}
.timeline-bar{height:4px;border-radius:2px;margin-top:6px;
    background:rgba(255,255,255,.1);overflow:hidden}
.timeline-fill{height:100%;border-radius:2px;
    background:linear-gradient(90deg,#10b981,#fb923c,#ef4444)}
</style>
@endpush

@section('content')

<div style="display:flex;align-items:center;gap:12px;margin-bottom:18px">
    <a href="{{ route('admin.listening.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <div style="font-size:17px;font-weight:800">{{ $paket->nama }}</div>
        <div style="font-size:13px;color:var(--muted)">
            <span style="background:rgba(26,86,219,.15);color:var(--accent);
                padding:1px 8px;border-radius:4px;font-size:11px;font-weight:600">
                {{ strtoupper($paket->tipe_paket) }}
            </span>
            &nbsp; {{ $soalList->count() }} soal
            @if($paket->durasi_detik > 0)
            &nbsp;·&nbsp; {{ $paket->durasi_format }}
            @endif
        </div>
    </div>
</div>

<div id="alert-box" style="display:none;margin-bottom:14px"></div>

<div class="ls-wrap">

{{-- ═══ KIRI ═══ --}}
<div>

{{-- Audio Player --}}
<div class="audio-panel">
    <div class="ap-title">
        <i class="fas fa-waveform-path"></i>
        Audio Listening — Mode Admin
        <span style="font-size:10px;opacity:.5;margin-left:4px;text-transform:none">
            (speed & seek tersedia untuk kemudahan input)
        </span>
    </div>

    {{-- Waveform --}}
    <div id="waveform-container" onclick="seekByClick(event)">
        <div id="waveform-bg"></div>
        <div id="waveform-progress" style="width:0%"></div>
        <div id="waveform-cursor" style="left:0%"></div>
        {{-- Markers dirender JS --}}
    </div>

    {{-- Controls --}}
    <div class="ap-controls">
        <button class="ap-btn ap-play" id="btn-play" onclick="togglePlay()">
            <i class="fas fa-play" id="play-ico"></i>
        </button>
        <button class="ap-btn ap-sm" onclick="skipAudio(-5)" title="-5s">
            <i class="fas fa-backward-step"></i>5s
        </button>
        <button class="ap-btn ap-sm" onclick="skipAudio(5)" title="+5s">
            5s<i class="fas fa-forward-step"></i>
        </button>
        <span class="ap-time" id="ap-time">0:00</span>
        <span class="ap-dur" id="ap-dur">/ --:--</span>
        <div style="flex:1"></div>
        <select class="ap-speed" onchange="audio.playbackRate=parseFloat(this.value)">
            <option value=".5">0.5×</option>
            <option value=".75">0.75×</option>
            <option value="1" selected>1×</option>
            <option value="1.25">1.25×</option>
        </select>
    </div>

    <audio id="main-audio" src="{{ $paket->audio_url_full }}"
        preload="metadata" style="display:none"
        onloadedmetadata="onAudioReady()"
        ontimeupdate="onTick()">
    </audio>

    {{-- Set Marker buttons --}}
    <div style="margin-top:14px;padding-top:12px;border-top:1px solid rgba(255,255,255,.06)">
        <div style="font-size:11.5px;color:rgba(255,255,255,.4);margin-bottom:8px">
            Putar audio → pause → klik tombol untuk set marker percakapan
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <button class="btn-set btn-start" onclick="setMarker('start')">
                <i class="fas fa-play"></i> Set Start
                <span id="lbl-start" style="font-family:monospace;
                    background:rgba(0,0,0,.3);padding:1px 7px;border-radius:4px">
                    --:--
                </span>
            </button>
            <button class="btn-set btn-end" onclick="setMarker('end')">
                <i class="fas fa-stop"></i> Set End
                <span id="lbl-end" style="font-family:monospace;
                    background:rgba(0,0,0,.3);padding:1px 7px;border-radius:4px">
                    --:--
                </span>
            </button>
        </div>

        <div class="marker-row">
            <div class="marker-box">
                <div class="marker-lbl" style="color:#4ade80">▶ Conversation Start</div>
                <div class="marker-val" id="disp-start" style="color:#4ade80">--:--</div>
                <div style="font-size:11px;color:var(--muted)" id="disp-start-s">0 detik</div>
            </div>
            <div class="marker-box">
                <div class="marker-lbl" style="color:#f87171">■ Conversation End</div>
                <div class="marker-val" id="disp-end" style="color:#f87171">--:--</div>
                <div style="font-size:11px;color:var(--muted)" id="disp-end-s">0 detik</div>
            </div>
            <div class="marker-box">
                <div class="marker-lbl" style="color:#fb923c">⏸ Pause Duration</div>
                <div style="display:flex;align-items:center;gap:6px;margin-top:2px">
                    <input type="number" id="inp-pause" value="15" min="5" max="60"
                        style="width:56px;font-family:monospace;font-size:15px;font-weight:900;
                        background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);
                        border-radius:6px;padding:3px 6px;color:#fb923c;text-align:center"
                        oninput="updateResumeCalc()">
                    <span style="font-size:12px;color:var(--muted)">detik</span>
                </div>
            </div>
        </div>

        {{-- Session resume preview --}}
        <div id="resume-preview" style="display:none;margin-top:10px;
            background:rgba(139,92,246,.1);border:1px solid rgba(139,92,246,.2);
            border-radius:8px;padding:10px 14px;font-size:12.5px;line-height:1.9">
            <div style="color:#a78bfa;font-weight:700;margin-bottom:4px">
                📊 Virtual Timeline Preview
            </div>
            <div style="display:flex;gap:20px;flex-wrap:wrap">
                <span>▶ Audio start:
                    <strong id="prev-start" style="color:#4ade80;font-family:monospace">--:--</strong>
                </span>
                <span>■ Audio end:
                    <strong id="prev-end" style="color:#f87171;font-family:monospace">--:--</strong>
                </span>
                <span>⏸ Pause:
                    <strong id="prev-pause" style="color:#fb923c;font-family:monospace">15s</strong>
                </span>
                <span>▶ Resume at:
                    <strong id="prev-resume" style="color:#a78bfa;font-family:monospace">--:--</strong>
                </span>
            </div>
        </div>
    </div>
</div>

{{-- Form tambah soal --}}
<div class="soal-form">
    <div style="font-size:14px;font-weight:700;margin-bottom:16px;
        display:flex;align-items:center;gap:8px">
        <i class="fas fa-plus-circle" style="color:var(--green)"></i>
        Tambah Soal
        <span id="soal-nomor-badge" style="background:rgba(26,86,219,.15);
            color:var(--accent);padding:2px 10px;border-radius:20px;font-size:12px">
            No. {{ $soalList->count() + 1 }}
        </span>
    </div>

    <div id="form-alert" style="display:none;margin-bottom:12px"></div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px">
        <div class="form-group" style="margin:0">
            <label class="form-label" style="font-size:11.5px">No. Urut</label>
            <input type="number" id="inp-order" class="form-control"
                style="font-size:14px" min="1"
                value="{{ $soalList->count() + 1 }}">
        </div>
        <div class="form-group" style="margin:0">
            <label class="form-label" style="font-size:11.5px">Part</label>
            <select id="inp-part" class="form-control" style="font-size:13px">
                <option value="A">A — Short Dialogues</option>
                <option value="B">B — Longer Conv.</option>
                <option value="C">C — Mini Talks</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" style="font-size:11.5px">Pertanyaan *</label>
        <textarea id="inp-q" class="form-control" rows="2"
            placeholder="cth: What does the woman suggest?"></textarea>
    </div>

    <div class="form-group">
        <label class="form-label" style="font-size:11.5px">
            Script Audio
            <small style="color:var(--muted)">(opsional, hanya admin)</small>
        </label>
        <textarea id="inp-script" class="form-control" rows="2"
            placeholder="Transkrip percakapan..."></textarea>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px">
        @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $k=>$l)
        <div class="form-group" style="margin:0">
            <label class="form-label" style="font-size:11.5px">Pilihan {{ $l }}</label>
            <input type="text" id="inp-p{{ $k }}" class="form-control"
                placeholder="Pilihan {{ $l }}">
        </div>
        @endforeach
    </div>

    <div class="form-group">
        <label class="form-label" style="font-size:11.5px">Jawaban Benar *</label>
        <div class="kunci-row">
            @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $k=>$l)
            <label class="kunci-lbl" id="kl-{{ $k }}"
                onclick="pilihKunci('{{ $k }}')">{{ $l }}</label>
            @endforeach
        </div>
    </div>

    <button onclick="simpan()" id="btn-save" class="btn btn-primary" style="width:100%">
        <i class="fas fa-save"></i> Simpan Soal
    </button>
</div>

</div>

{{-- ═══ KANAN: Daftar Soal ═══ --}}
<div style="position:sticky;top:20px">
    <div class="card">
        <div class="card-header" style="padding:12px 16px">
            <h3 style="font-size:13px">
                <i class="fas fa-list-ol" style="color:#fb923c;margin-right:6px"></i>
                Timeline Soal
            </h3>
            <span style="font-size:12px;color:var(--muted)">
                {{ $soalList->count() }} soal
            </span>
        </div>

        {{-- Info header --}}
        <div style="padding:8px 16px;background:rgba(234,88,12,.05);
            border-bottom:1px solid var(--border);
            font-size:11px;color:var(--muted);
            display:grid;grid-template-columns:28px 52px 52px 52px 1fr;gap:4px">
            <span>#</span>
            <span style="color:#4ade80">▶ Start</span>
            <span style="color:#f87171">■ End</span>
            <span style="color:#a78bfa">↩ Resume</span>
            <span>Pertanyaan</span>
        </div>

        <div id="soal-list" style="max-height:70vh;overflow-y:auto">
            @forelse($soalList->sortBy('order_number') as $s)
            @php
                $dur = $paket->durasi_detik ?: 1;
                $widthPct = $dur > 0
                    ? (($s->audio_end - $s->start_second) / $dur * 100)
                    : 0;
                $startPct = $dur > 0 ? ($s->start_second / $dur * 100) : 0;
            @endphp
            <div class="soal-item" id="si-{{ $s->id }}"
                onclick="seekTo({{ $s->start_second }})">
                <div class="si-no">{{ $s->order_number }}</div>
                <div style="flex:1;min-width:0">
                    <div style="display:grid;grid-template-columns:52px 52px 52px 1fr;
                        gap:4px;font-size:11.5px;margin-bottom:5px">
                        <span style="color:#4ade80;font-family:monospace">
                            {{ sprintf('%d:%02d', intdiv($s->start_second,60), $s->start_second%60) }}
                        </span>
                        <span style="color:#f87171;font-family:monospace">
                            {{ sprintf('%d:%02d', intdiv($s->audio_end??0,60), ($s->audio_end??0)%60) }}
                        </span>
                        <span style="color:#a78bfa;font-family:monospace">
                            {{ sprintf('%d:%02d', intdiv($s->session_resume_time??0,60), ($s->session_resume_time??0)%60) }}
                        </span>
                        <span style="color:rgba(255,255,255,.7);white-space:nowrap;
                            overflow:hidden;text-overflow:ellipsis">
                            {{ mb_strimwidth($s->pertanyaan??'',0,30,'...') }}
                        </span>
                    </div>
                    {{-- Timeline bar --}}
                    <div class="timeline-bar" style="position:relative">
                        <div class="timeline-fill"
                            style="margin-left:{{ $startPct }}%;width:{{ $widthPct }}%"></div>
                    </div>
                    <div style="font-size:10.5px;color:var(--muted);margin-top:3px">
                        Pause {{ $s->pause_duration??15 }}s
                        &nbsp;·&nbsp; Jwb: {{ strtoupper($s->jawaban_benar??'-') }}
                    </div>
                </div>
                <button onclick="event.stopPropagation();hapusSoal({{ $s->id }})"
                    style="background:none;border:none;color:var(--muted);
                    cursor:pointer;font-size:11px;padding:2px 4px;flex-shrink:0">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @empty
            <div style="padding:28px;text-align:center;color:var(--muted);font-size:13px">
                <i class="fas fa-clock" style="font-size:26px;display:block;margin-bottom:8px;opacity:.3"></i>
                Belum ada soal. Set marker lalu tambah soal.
            </div>
            @endforelse
        </div>

        {{-- Progress --}}
        @php $pct = min(100,($soalList->count()/50)*100) @endphp
        <div style="padding:10px 16px;border-top:1px solid var(--border)">
            <div style="display:flex;justify-content:space-between;
                font-size:11.5px;color:var(--muted);margin-bottom:4px">
                <span>Progress</span>
                <span>{{ $soalList->count() }} / 50</span>
            </div>
            <div style="height:4px;background:var(--border);border-radius:2px">
                <div style="height:4px;border-radius:2px;width:{{ $pct }}%;
                    background:{{ $pct>=100?'var(--green)':'#fb923c' }}"></div>
            </div>
        </div>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script>
const PAKET_ID  = {{ $paket->id }};
const CSRF      = '{{ csrf_token() }}';
const audio     = document.getElementById('main-audio');
let   curKunci  = null;
let   markerStart = null;
let   markerEnd   = null;
let   audioDur    = 0;

// ══ Audio player ══════════════════════════════════════════════
function onAudioReady() {
    audioDur = audio.duration;
    document.getElementById('ap-dur').textContent = '/ ' + fmtTime(audioDur);
    generateWaveform();
    renderMarkers();

    // Update durasi ke server
    fetch(`/admin/listening/${PAKET_ID}/durasi`, {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({durasi_detik: Math.round(audioDur)}),
    });
}

function onTick() {
    const cur = audio.currentTime;
    document.getElementById('ap-time').textContent  = fmtTime(cur);
    document.getElementById('ap-dur').textContent   = '/ ' + fmtTime(audioDur);
    document.getElementById('lbl-start').textContent = fmtTime(cur);
    document.getElementById('lbl-end').textContent   = fmtTime(cur);

    // Update progress
    const pct = audioDur > 0 ? (cur/audioDur)*100 : 0;
    document.getElementById('waveform-progress').style.width = pct + '%';
    document.getElementById('waveform-cursor').style.left    = pct + '%';
    document.getElementById('ap-time').textContent = fmtTime(cur);
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

function seekByClick(e) {
    if (!audioDur) return;
    const rect = document.getElementById('waveform-container').getBoundingClientRect();
    audio.currentTime = ((e.clientX - rect.left) / rect.width) * audioDur;
}

function seekTo(sec) {
    audio.currentTime = sec;
    if (audio.paused) audio.play();
    document.getElementById('play-ico').className = 'fas fa-pause';
}

function skipAudio(s) {
    audio.currentTime = Math.max(0, Math.min(audioDur, audio.currentTime + s));
}

function fmtTime(s) {
    if (!s || isNaN(s)) return '0:00';
    return Math.floor(s/60) + ':' + String(Math.floor(s%60)).padStart(2,'0');
}

// ══ Waveform ══════════════════════════════════════════════════
function generateWaveform() {
    const container = document.getElementById('waveform-bg');
    container.innerHTML = '';
    const bars = Math.min(200, Math.floor(audioDur));
    for (let i = 0; i < bars; i++) {
        const h = 20 + Math.sin(i * 0.4) * 8 + Math.random() * 24;
        const bar = document.createElement('div');
        bar.className = 'wv-bar';
        bar.style.height = h + '%';
        container.appendChild(bar);
    }
}

function renderMarkers() {
    // Hapus marker lama
    document.querySelectorAll('.wv-marker').forEach(m => m.remove());
    if (!audioDur) return;

    const wf = document.getElementById('waveform-container');

    // Marker dari soal tersimpan
    @foreach($soalList as $s)
    addMarkerEl(wf, {{ $s->start_second }}, 'wv-marker-start', 'No.{{ $s->order_number }}');
    addMarkerEl(wf, {{ $s->audio_end ?? 0 }}, 'wv-marker-end', '');
    @endforeach

    // Marker aktif
    if (markerStart !== null) addMarkerEl(wf, markerStart, 'wv-marker-start', '▶');
    if (markerEnd   !== null) addMarkerEl(wf, markerEnd,   'wv-marker-end',   '■');
}

function addMarkerEl(container, sec, cls, label) {
    const pct = (sec / audioDur) * 100;
    const el  = document.createElement('div');
    el.className = 'wv-marker ' + cls;
    el.style.left = pct + '%';
    el.title = fmtTime(sec);
    if (label) {
        const lbl = document.createElement('div');
        lbl.className = 'wv-marker-label';
        lbl.textContent = label;
        lbl.style.color = cls.includes('start') ? '#4ade80' : '#f87171';
        el.appendChild(lbl);
    }
    el.onclick = (e) => { e.stopPropagation(); seekTo(sec); };
    container.appendChild(el);
}

// ══ Marker ════════════════════════════════════════════════════
function setMarker(type) {
    const sec = Math.round(audio.currentTime * 10) / 10;
    if (type === 'start') {
        markerStart = sec;
        document.getElementById('disp-start').textContent   = fmtTime(sec);
        document.getElementById('disp-start-s').textContent = sec + ' detik';
    } else {
        markerEnd = sec;
        document.getElementById('disp-end').textContent   = fmtTime(sec);
        document.getElementById('disp-end-s').textContent = sec + ' detik';
    }
    updateResumeCalc();
    renderMarkers();
}

function updateResumeCalc() {
    if (markerStart === null || markerEnd === null) return;
    const pause   = parseInt(document.getElementById('inp-pause').value) || 15;
    const resume  = markerEnd + pause;
    document.getElementById('prev-start').textContent  = fmtTime(markerStart);
    document.getElementById('prev-end').textContent    = fmtTime(markerEnd);
    document.getElementById('prev-pause').textContent  = pause + 's';
    document.getElementById('prev-resume').textContent = fmtTime(resume);
    document.getElementById('resume-preview').style.display = 'block';
}

// ══ Kunci jawaban ══════════════════════════════════════════════
function pilihKunci(k) {
    curKunci = k;
    ['a','b','c','d'].forEach(x => {
        document.getElementById('kl-'+x).className = 'kunci-lbl'+(x===k?' on':'');
    });
}

// ══ Simpan soal ════════════════════════════════════════════════
function simpan() {
    const q = document.getElementById('inp-q').value.trim();
    if (!q)            return showFormAlert('Pertanyaan tidak boleh kosong.','danger');
    if (!curKunci)     return showFormAlert('Pilih jawaban benar.','danger');
    if (markerStart === null) return showFormAlert('Set Start marker terlebih dahulu.','danger');
    if (markerEnd === null)   return showFormAlert('Set End marker terlebih dahulu.','danger');
    if (markerEnd <= markerStart) return showFormAlert('End harus setelah Start.','danger');

    const btn = document.getElementById('btn-save');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    fetch(`/admin/listening/${PAKET_ID}/soal`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept':       'application/json',
        },
        body: JSON.stringify({
            order_number:  parseInt(document.getElementById('inp-order').value),
            part:          document.getElementById('inp-part').value,
            start_second:  markerStart,
            audio_end:     markerEnd,
            pause_duration: parseInt(document.getElementById('inp-pause').value) || 15,
            pertanyaan:    q,
            audio_script:  document.getElementById('inp-script').value,
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
            showFormAlert(d.msg || 'Gagal menyimpan.', 'danger');
        }
    })
    .catch(e => showFormAlert('Error: '+e.message,'danger'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Soal';
    });
}

function resetForm() {
    document.getElementById('inp-q').value     = '';
    document.getElementById('inp-script').value = '';
    ['a','b','c','d'].forEach(k => {
        const el = document.getElementById('inp-p'+k);
        if (el) el.value = '';
        document.getElementById('kl-'+k).className = 'kunci-lbl';
    });
    curKunci = null;
    markerStart = null;
    markerEnd   = null;
    document.getElementById('disp-start').textContent = '--:--';
    document.getElementById('disp-end').textContent   = '--:--';
    document.getElementById('resume-preview').style.display = 'none';
}

function hapusSoal(id) {
    if (!confirm('Hapus soal ini?')) return;
    fetch(`/admin/listening/soal/${id}`, {
        method:'DELETE',
        headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
    }).then(r=>r.json()).then(d=>{
        if(d.ok) { document.getElementById('si-'+id)?.remove(); renderMarkers(); }
    });
}

function showAlert(msg, type) {
    const el = document.getElementById('alert-box');
    const c  = type==='success'
        ? ['rgba(22,163,74,.1)','rgba(22,163,74,.3)','#4ade80']
        : ['rgba(220,38,38,.1)','rgba(220,38,38,.3)','#f87171'];
    el.style.cssText = `display:block;background:${c[0]};border:1px solid ${c[1]};
        border-radius:8px;padding:11px 14px;color:${c[2]};font-size:13px`;
    el.textContent = msg;
    if(type==='success') setTimeout(()=>el.style.display='none', 4000);
}
function showFormAlert(msg, type) {
    const el = document.getElementById('form-alert');
    const c  = type==='success'
        ? ['rgba(22,163,74,.1)','rgba(22,163,74,.3)','#4ade80']
        : ['rgba(220,38,38,.1)','rgba(220,38,38,.3)','#f87171'];
    el.style.cssText = `display:block;background:${c[0]};border:1px solid ${c[1]};
        border-radius:8px;padding:10px 14px;color:${c[2]};font-size:13px`;
    el.textContent = msg;
}
</script>
@endpush
