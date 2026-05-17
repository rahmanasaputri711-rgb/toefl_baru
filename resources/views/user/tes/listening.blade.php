@extends('layouts.user')
@section('title','Listening Section')

@push('styles')
<style>
/* ─── Fullscreen Layout ─── */
*{box-sizing:border-box}
body{background:#0a0f1e;color:#e2e8f0;font-family:'Inter',sans-serif}
.lt-wrap{display:grid;grid-template-rows:auto 1fr auto;height:100vh;overflow:hidden}

/* ─── Top Bar ─── */
.top-bar{background:#0d1426;border-bottom:1px solid rgba(255,255,255,.06);
    padding:0 24px;height:56px;display:flex;align-items:center;gap:16px;z-index:100}
.tb-badge{padding:4px 12px;border-radius:6px;font-size:12px;font-weight:700}
.timer-box{font-family:monospace;font-size:16px;font-weight:900;color:#fb923c;
    background:rgba(234,88,12,.1);padding:6px 14px;border-radius:8px;
    border:1px solid rgba(234,88,12,.2)}

/* ─── Main content ─── */
.lt-main{display:grid;grid-template-columns:1fr 340px;overflow:hidden}

/* ─── Audio + Question Area ─── */
.aq-area{padding:24px;overflow-y:auto;display:flex;flex-direction:column;gap:16px}

/* Audio panel */
.audio-panel{background:linear-gradient(135deg,#0f1a2e,#0a1628);
    border:1px solid rgba(234,88,12,.2);border-radius:16px;padding:20px}
.audio-status{font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;
    margin-bottom:12px;display:inline-flex;align-items:center;gap:6px}
.status-playing{background:rgba(34,197,94,.1);color:#4ade80;border:1px solid rgba(34,197,94,.2)}
.status-paused{background:rgba(234,88,12,.1);color:#fb923c;border:1px solid rgba(234,88,12,.2)}
.status-answering{background:rgba(139,92,246,.15);color:#a78bfa;border:1px solid rgba(139,92,246,.3)}

/* Timeline visual */
#timeline-bar{height:8px;background:rgba(255,255,255,.06);border-radius:4px;
    overflow:hidden;margin-bottom:10px;cursor:default;position:relative}
#timeline-progress{height:100%;border-radius:4px;background:linear-gradient(90deg,#3b82f6,#fb923c);
    transition:width .2s linear}
/* Answer time indicator */
#answer-progress-wrap{height:4px;background:rgba(255,255,255,.05);border-radius:2px;
    margin-top:6px;overflow:hidden}
#answer-progress{height:100%;border-radius:2px;background:#a78bfa;
    transition:width .1s linear}

/* Audio controls — user hanya lihat info, tidak ada kontrol */
.audio-info{display:flex;align-items:center;gap:16px;flex-wrap:wrap}
.ai-time{font-family:monospace;font-size:22px;font-weight:900;color:#fb923c}
.ai-total{font-family:monospace;font-size:14px;color:rgba(255,255,255,.3)}

/* Play once button */
#btn-play-once{background:linear-gradient(135deg,#ea580c,#fb923c);
    border:none;border-radius:10px;padding:12px 28px;color:#fff;
    font-size:15px;font-weight:700;cursor:pointer;font-family:inherit;
    display:flex;align-items:center;gap:8px;transition:all .15s}
#btn-play-once:hover{transform:scale(1.02);box-shadow:0 4px 20px rgba(234,88,12,.4)}

/* Countdown ring */
.countdown-wrap{display:flex;flex-direction:column;align-items:center;
    justify-content:center;padding:20px;gap:10px}
.countdown-ring{width:80px;height:80px;position:relative}
.countdown-ring svg{transform:rotate(-90deg)}
.cr-bg{fill:none;stroke:rgba(255,255,255,.06);stroke-width:6}
.cr-fill{fill:none;stroke:#a78bfa;stroke-width:6;stroke-linecap:round;
    stroke-dasharray:220;stroke-dashoffset:0;transition:stroke-dashoffset .1s linear}
.countdown-num{position:absolute;inset:0;display:flex;align-items:center;
    justify-content:center;font-family:monospace;font-size:22px;font-weight:900;color:#a78bfa}
.countdown-label{font-size:12px;color:var(--muted);text-align:center;line-height:1.5}

/* Question card */
.q-card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);
    border-radius:14px;padding:20px;display:none}
.q-card.show{display:block}
.q-number{font-size:12px;font-weight:700;color:#fb923c;text-transform:uppercase;
    letter-spacing:.5px;margin-bottom:8px}
.q-text{font-size:16px;font-weight:600;line-height:1.6;color:#e2e8f0;margin-bottom:16px}
.q-options{display:flex;flex-direction:column;gap:8px}
.q-opt{display:flex;align-items:flex-start;gap:12px;padding:12px 16px;
    border-radius:10px;border:1.5px solid rgba(255,255,255,.1);
    cursor:pointer;transition:all .15s;background:rgba(255,255,255,.02)}
.q-opt:hover{border-color:rgba(26,86,219,.5);background:rgba(26,86,219,.06)}
.q-opt.selected{border-color:var(--blue);background:rgba(26,86,219,.12)}
.q-opt.correct{border-color:rgba(22,163,74,.5);background:rgba(22,163,74,.08)}
.q-opt.wrong{border-color:rgba(220,38,38,.4);background:rgba(220,38,38,.06)}
.opt-letter{width:28px;height:28px;border-radius:50%;border:1.5px solid rgba(255,255,255,.2);
    display:flex;align-items:center;justify-content:center;font-size:12px;
    font-weight:700;flex-shrink:0;transition:all .15s}
.q-opt.selected .opt-letter{background:var(--blue);border-color:var(--blue);color:#fff}
.opt-text{font-size:14px;line-height:1.5;color:#e2e8f0}

/* ─── Nav soal ─── */
.nav-area{background:#0d1426;border-left:1px solid rgba(255,255,255,.06);
    padding:20px;overflow-y:auto}
.nav-title{font-size:12px;font-weight:700;text-transform:uppercase;
    letter-spacing:.5px;color:var(--muted);margin-bottom:14px}
.nav-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:6px;margin-bottom:16px}
.nav-btn{aspect-ratio:1;border-radius:8px;border:1.5px solid rgba(255,255,255,.1);
    background:rgba(255,255,255,.03);font-size:12px;font-weight:700;
    cursor:pointer;transition:all .15s;color:rgba(255,255,255,.6);
    display:flex;align-items:center;justify-content:center;flex-direction:column;gap:1px;
    font-family:inherit;position:relative}
.nav-btn:hover{border-color:rgba(255,255,255,.25)}
.nav-btn.current{border-color:#fb923c;background:rgba(234,88,12,.15);color:#fb923c}
.nav-btn.answered{border-color:rgba(22,163,74,.4);background:rgba(22,163,74,.08);color:#4ade80}
.nav-btn.audio-live::after{content:'';position:absolute;top:3px;right:3px;
    width:6px;height:6px;border-radius:50%;background:#fb923c;
    animation:pulse 1s infinite}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(.8)}}
.nav-btn .nb-time{font-size:9px;color:var(--muted);font-family:monospace}

/* ─── Bottom bar ─── */
.bottom-bar{background:#0d1426;border-top:1px solid rgba(255,255,255,.06);
    padding:12px 24px;display:flex;align-items:center;gap:12px}
</style>
@endpush

@section('content')

@php
    // Data soal diurutkan berdasarkan start_second
    $soalData = $soalList->sortBy('start_second')->values();
    $audioUrl  = $audioPaket?->audio_url_full ?? '';
    $totalDur  = $audioPaket?->durasi_detik ?? 0;
@endphp

<div class="lt-wrap">

{{-- ── TOP BAR ── --}}
<div class="top-bar">
    <span class="tb-badge" style="background:rgba(234,88,12,.15);color:#fb923c;
        border:1px solid rgba(234,88,12,.2)">
        🎧 LISTENING
    </span>
    <span style="font-size:13px;color:var(--muted)">
        Soal <span id="cur-soal-no">1</span> dari {{ $soalData->count() }}
    </span>
    <div style="flex:1"></div>
    <div class="timer-box" id="session-timer">00:00</div>
    <span style="font-size:12px;color:var(--muted)">Sisa Waktu</span>
    @if(isset($waktuBerakhir))
    <div class="timer-box" id="remaining-timer" style="color:#4ade80;border-color:rgba(22,163,74,.2);background:rgba(22,163,74,.07)">
        --:--
    </div>
    @endif
</div>

{{-- ── MAIN ── --}}
<div class="lt-main">

{{-- Kiri: Audio + Soal --}}
<div class="aq-area">

    {{-- Audio Panel --}}
    <div class="audio-panel">
        <div id="audio-status" class="audio-status status-paused">
            <span id="status-dot" style="width:8px;height:8px;border-radius:50%;background:currentColor;animation:pulse 1s infinite"></span>
            <span id="status-text">Tekan Play untuk mulai</span>
        </div>

        {{-- Timeline bar --}}
        <div id="timeline-bar">
            <div id="timeline-progress" style="width:0%"></div>
        </div>
        {{-- Answer time progress --}}
        <div id="answer-progress-wrap" style="display:none">
            <div id="answer-progress" style="width:100%"></div>
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;
            margin-top:10px;flex-wrap:wrap;gap:12px">
            <div class="audio-info">
                <div class="ai-time" id="vt-time">0:00</div>
                <div class="ai-total">/ {{ sprintf('%d:%02d', intdiv($totalDur,60), $totalDur%60) }}</div>
            </div>

            {{-- Play once button (hilang setelah play) --}}
            <button id="btn-play-once" onclick="startListening()">
                <i class="fas fa-play"></i> Mulai Listening
            </button>
        </div>

        {{-- Countdown answering (muncul saat pause) --}}
        <div id="countdown-area" style="display:none;margin-top:16px;
            background:rgba(139,92,246,.07);border:1px solid rgba(139,92,246,.15);
            border-radius:10px;padding:16px;display:none;
            flex-direction:row;align-items:center;gap:20px">
            <div class="countdown-wrap">
                <div class="countdown-ring">
                    <svg viewBox="0 0 76 76" width="76" height="76">
                        <circle class="cr-bg" cx="38" cy="38" r="34"/>
                        <circle class="cr-fill" id="cr-circle" cx="38" cy="38" r="34"/>
                    </svg>
                    <div class="countdown-num" id="countdown-num">15</div>
                </div>
            </div>
            <div>
                <div style="font-size:14px;font-weight:700;color:#a78bfa;margin-bottom:6px">
                    ⏸ Waktu Menjawab
                </div>
                <div style="font-size:13px;color:var(--muted);line-height:1.6">
                    Audio akan lanjut otomatis setelah waktu habis.<br>
                    Pastikan sudah memilih jawaban.
                </div>
            </div>
        </div>
    </div>

    {{-- Question cards --}}
    @foreach($soalData as $i => $s)
    <div class="q-card" id="qcard-{{ $i }}" data-idx="{{ $i }}">
        <div class="q-number">Soal {{ $s->order_number }}</div>
        <div class="q-text">{{ $s->pertanyaan }}</div>
        <div class="q-options">
            @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $k=>$l)
            @if($s->{'pilihan_'.$k} && $s->{'pilihan_'.$k} !== '-')
            <div class="q-opt" id="opt-{{ $i }}-{{ $k }}"
                onclick="pilihJawaban({{ $i }},'{{ $k }}')">
                <div class="opt-letter">{{ $l }}</div>
                <div class="opt-text">{{ $s->{'pilihan_'.$k} }}</div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @endforeach

</div>

{{-- Kanan: Navigator --}}
<div class="nav-area">
    <div class="nav-title">Navigator Soal</div>
    <div class="nav-grid">
        @foreach($soalData as $i => $s)
        <button class="nav-btn {{ $i===0?'current':'' }}"
            id="nb-{{ $i }}"
            data-idx="{{ $i }}"
            data-start="{{ $s->start_second }}"
            data-end="{{ $s->audio_end ?? 0 }}"
            data-pause="{{ $s->pause_duration ?? 15 }}"
            data-resume="{{ $s->session_resume_time ?? 0 }}"
            onclick="jumpToSoal({{ $i }})">
            {{ $s->order_number }}
            <div class="nb-time">
                {{ sprintf('%d:%02d', intdiv($s->start_second,60), $s->start_second%60) }}
            </div>
        </button>
        @endforeach
    </div>

    {{-- Legend --}}
    <div style="font-size:11.5px;color:var(--muted);display:flex;flex-direction:column;gap:6px;
        padding-top:14px;border-top:1px solid var(--border)">
        <div style="display:flex;align-items:center;gap:8px">
            <div style="width:14px;height:14px;border-radius:3px;
                border:1.5px solid rgba(234,88,12,.4);background:rgba(234,88,12,.15)"></div>
            <span>Soal aktif sekarang</span>
        </div>
        <div style="display:flex;align-items:center;gap:8px">
            <div style="width:14px;height:14px;border-radius:3px;
                border:1.5px solid rgba(22,163,74,.4);background:rgba(22,163,74,.08)"></div>
            <span>Sudah dijawab</span>
        </div>
        <div style="display:flex;align-items:center;gap:8px">
            <div style="position:relative;width:14px;height:14px">
                <div style="width:14px;height:14px;border-radius:3px;
                    border:1.5px solid rgba(255,255,255,.1);background:rgba(255,255,255,.03)"></div>
                <div style="position:absolute;top:0;right:0;width:6px;height:6px;
                    border-radius:50%;background:#fb923c"></div>
            </div>
            <span>Soal berjalan di audio</span>
        </div>
    </div>
</div>

</div>

{{-- ── BOTTOM BAR ── --}}
<div class="bottom-bar">
    <div style="font-size:13px;color:var(--muted)">
        Jawaban tersimpan otomatis
    </div>
    <div style="flex:1"></div>
    <button class="btn btn-outline btn-sm" id="btn-prev"
        onclick="navSoal(-1)" disabled>
        ← Sebelumnya
    </button>
    <button class="btn btn-outline btn-sm" id="btn-next"
        onclick="navSoal(1)">
        Berikutnya →
    </button>
    @if(isset($percobaan))
    <button class="btn btn-primary btn-sm"
        onclick="if(confirm('Selesaikan section listening?')) submitSection()">
        Selesai Listening →
    </button>
    @endif
</div>

</div>

{{-- Hidden audio --}}
<audio id="main-audio" src="{{ $audioUrl }}" preload="auto"
    ontimeupdate="onAudioTick()"
    onloadedmetadata="onAudioReady()"
    onended="onAudioEnded()"
    style="display:none">
</audio>

@endsection

@push('scripts')
<script>
// ══ Data soal dari server ══════════════════════════════════════
const SOAL_DATA = @json($soalData->map(fn($s) => [
    'id'           => $s->id,
    'order'        => $s->order_number,
    'start'        => $s->start_second,
    'end'          => $s->audio_end ?? 0,
    'pause'        => $s->pause_duration ?? 15,
    'resume'       => $s->session_resume_time ?? 0,
    'pertanyaan'   => $s->pertanyaan,
    'jawaban_benar'=> $s->jawaban_benar,
])->values());

const TOTAL_DUR     = {{ $totalDur }};
const IS_FULL_TES   = {{ isset($tipeTes) && $tipeTes === 'full' ? 'true' : 'false' }};
const CSRF          = '{{ csrf_token() }}';

const audio = document.getElementById('main-audio');

// ══ State ═════════════════════════════════════════════════════
let virtualTime     = 0;      // virtual timeline (detik), termasuk waktu pause
let audioOffset     = 0;      // selisih virtualTime vs audio.currentTime
let isAnsweringPause= false;  // sedang pause untuk jawab?
let currentSoalIdx  = 0;
let answers         = {};     // {idx: 'a'|'b'|'c'|'d'}
let pauseTimer      = null;
let pauseRemaining  = 0;
let audioStarted    = false;
let sessionStart    = null;   // Date waktu mulai
let sessionTimer    = null;

// ══ Mulai listening ══════════════════════════════════════════
function startListening() {
    audio.play().then(() => {
        document.getElementById('btn-play-once').style.display = 'none';
        audioStarted = true;
        sessionStart = Date.now();
        sessionTimer = setInterval(updateSessionTimer, 1000);
        setStatus('playing', '🎧 Audio berjalan...');

        // Blokir semua kontrol audio di tes full
        if (IS_FULL_TES) {
            lockAudioFull();
        }
    }).catch(e => console.warn('Audio play failed:', e));
}

function lockAudioFull() {
    audio.addEventListener('pause', () => {
        if (!isAnsweringPause && audioStarted)
            setTimeout(() => { if (audio.paused && !isAnsweringPause) audio.play(); }, 100);
    });
    audio.addEventListener('seeking', function() {
        if (this.currentTime < (this._lastTime||0) - 1)
            this.currentTime = this._lastTime || 0;
    });
    audio.addEventListener('timeupdate', function() { this._lastTime = this.currentTime; });
    if ('mediaSession' in navigator) {
        ['pause','stop','seekbackward','seekforward'].forEach(a => {
            try { navigator.mediaSession.setActionHandler(a,()=>{}); } catch(e){}
        });
    }
}

// ══ Audio tick ════════════════════════════════════════════════
function onAudioReady() {
    document.getElementById('vt-time').textContent = fmtTime(audio.duration || TOTAL_DUR);
}

function onAudioTick() {
    if (isAnsweringPause) return; // pause untuk jawab — jangan update

    const cur = audio.currentTime;
    virtualTime = cur + audioOffset;

    // Update timeline progress
    const total = TOTAL_DUR || audio.duration || 1;
    const pct   = Math.min(100, (virtualTime / total) * 100);
    document.getElementById('timeline-progress').style.width = pct + '%';
    document.getElementById('vt-time').textContent = fmtTime(virtualTime);

    // Highlight soal yang sedang aktif berdasarkan audio.currentTime
    updateActiveSoal(cur);

    // Cek apakah mencapai audio_end soal manapun
    for (let i = 0; i < SOAL_DATA.length; i++) {
        const s = SOAL_DATA[i];
        if (!s._paused && cur >= s.end && cur < s.end + 0.5) {
            triggerAnswerPause(i);
            break;
        }
    }
}

function updateActiveSoal(curSec) {
    // Temukan soal yang start_second-nya paling besar tapi <= curSec
    let liveIdx = -1;
    for (let i = 0; i < SOAL_DATA.length; i++) {
        if (curSec >= SOAL_DATA[i].start) liveIdx = i;
    }

    // Tandai audio-live di navigator
    document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('audio-live'));
    if (liveIdx >= 0) {
        document.getElementById('nb-'+liveIdx)?.classList.add('audio-live');
    }
}

// ══ Pause otomatis untuk menjawab ════════════════════════════
function triggerAnswerPause(soalIdx) {
    if (SOAL_DATA[soalIdx]._paused) return;
    SOAL_DATA[soalIdx]._paused = true;

    audio.pause();
    isAnsweringPause = true;

    // Tampilkan soal
    showSoal(soalIdx);
    currentSoalIdx = soalIdx;

    const pauseDur = SOAL_DATA[soalIdx].pause;

    // Update audioOffset: setiap pause menambah offset
    // Sehingga virtualTime = audio.currentTime + total_pause_so_far
    audioOffset += pauseDur;

    // Tampilkan countdown
    showCountdown(pauseDur, soalIdx);
    setStatus('answering', '⏸ Waktu menjawab...');
}

function showCountdown(duration, soalIdx) {
    const area = document.getElementById('countdown-area');
    area.style.display = 'flex';
    const progWrap = document.getElementById('answer-progress-wrap');
    progWrap.style.display = 'block';

    const circle     = document.getElementById('cr-circle');
    const numEl      = document.getElementById('countdown-num');
    const circumf    = 2 * Math.PI * 34; // r=34
    let remaining    = duration;

    numEl.textContent = remaining;
    circle.style.strokeDashoffset = 0;

    pauseRemaining = remaining;
    clearInterval(pauseTimer);
    pauseTimer = setInterval(() => {
        remaining--;
        pauseRemaining = remaining;
        numEl.textContent = remaining;

        // Ring progress
        const offset = circumf * (1 - remaining/duration);
        circle.style.strokeDashoffset = offset;

        // Answer progress bar
        const pct = (remaining/duration)*100;
        document.getElementById('answer-progress').style.width = pct + '%';

        if (remaining <= 0) {
            clearInterval(pauseTimer);
            resumeAudio(soalIdx);
        }
    }, 1000);
}

function resumeAudio(soalIdx) {
    clearInterval(pauseTimer);
    document.getElementById('countdown-area').style.display = 'none';
    document.getElementById('answer-progress-wrap').style.display = 'none';
    isAnsweringPause = false;
    audio.play();
    setStatus('playing', '🎧 Audio berjalan...');
}

function onAudioEnded() {
    setStatus('paused', '✅ Audio selesai');
    clearInterval(sessionTimer);
}

// ══ Tampilkan soal ═══════════════════════════════════════════
function showSoal(idx) {
    document.querySelectorAll('.q-card').forEach(c => c.classList.remove('show'));
    document.getElementById('qcard-'+idx)?.classList.add('show');
    document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('current'));
    document.getElementById('nb-'+idx)?.classList.add('current');
    document.getElementById('cur-soal-no').textContent = SOAL_DATA[idx]?.order || (idx+1);

    // Update prev/next buttons
    document.getElementById('btn-prev').disabled = idx <= 0;
    document.getElementById('btn-next').disabled = idx >= SOAL_DATA.length - 1;
}

function jumpToSoal(idx) {
    currentSoalIdx = idx;
    showSoal(idx);
}

function navSoal(dir) {
    const newIdx = currentSoalIdx + dir;
    if (newIdx >= 0 && newIdx < SOAL_DATA.length) {
        jumpToSoal(newIdx);
    }
}

// ══ Pilih jawaban ════════════════════════════════════════════
function pilihJawaban(idx, opt) {
    answers[idx] = opt;

    // Update UI
    document.querySelectorAll(`#qcard-${idx} .q-opt`).forEach(o => {
        o.classList.remove('selected');
    });
    document.getElementById(`opt-${idx}-${opt}`)?.classList.add('selected');

    // Tandai navigator sudah dijawab
    const nb = document.getElementById('nb-'+idx);
    if (nb) {
        nb.classList.add('answered');
    }

    // Simpan ke server
    saveAnswer(SOAL_DATA[idx].id, opt);
}

function saveAnswer(soalId, jawaban) {
    fetch('/user/tes/jawaban', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
        body: JSON.stringify({
            soal_id: soalId,
            jawaban_dipilih: jawaban,
            percobaan_id: {{ $percobaan->id ?? 'null' }},
        }),
    }).catch(e => console.warn('Save answer error:', e));
}

// ══ Session timer ═════════════════════════════════════════════
function updateSessionTimer() {
    if (!sessionStart) return;
    const elapsed = Math.floor((Date.now() - sessionStart) / 1000);
    document.getElementById('session-timer').textContent = fmtTime(elapsed);
}

// ══ Status display ═══════════════════════════════════════════
function setStatus(type, text) {
    const el = document.getElementById('audio-status');
    el.className = 'audio-status';
    if (type === 'playing')    el.classList.add('status-playing');
    if (type === 'paused')     el.classList.add('status-paused');
    if (type === 'answering')  el.classList.add('status-answering');
    document.getElementById('status-text').textContent = text;
}

// ══ Utils ════════════════════════════════════════════════════
function fmtTime(s) {
    if (!s || isNaN(s)) return '0:00';
    return Math.floor(s/60) + ':' + String(Math.floor(s%60)).padStart(2,'0');
}

// Init
showSoal(0);
</script>
@endpush
