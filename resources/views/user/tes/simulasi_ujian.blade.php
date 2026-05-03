<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulasi TOEFL ITP — Section {{ ucfirst($currentSection) }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/audio-player.css') }}">
    <style>
        :root{--bg:#0b1120;--surface:#111827;--surface2:#1a2436;--border:#1f2f46;
            --accent:#3b82f6;--green:#10b981;--gold:#f59e0b;--red:#ef4444;--orange:#f97316;
            --purple:#8b5cf6;--text:#e2e8f0;--muted:#64748b;--sidebar-w:220px}
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;flex-direction:column}

        /* TOPBAR */
        .topbar{background:var(--surface);border-bottom:1px solid var(--border);
            padding:0 20px;height:58px;display:flex;align-items:center;justify-content:space-between;
            position:sticky;top:0;z-index:100;flex-shrink:0}
        .section-badge{padding:5px 14px;border-radius:20px;font-size:12px;font-weight:700;
            text-transform:uppercase;letter-spacing:.8px}
        .sb-listening{background:rgba(249,115,22,.15);color:var(--orange)}
        .sb-structure{background:rgba(245,158,11,.15);color:var(--gold)}
        .sb-reading{background:rgba(59,130,246,.15);color:var(--accent)}
        .timer-wrap{display:flex;align-items:center;gap:8px;padding:6px 14px;
            border-radius:8px;background:var(--surface2);border:1px solid var(--border)}
        .timer-num{font-family:'JetBrains Mono',monospace;font-size:18px;font-weight:500}
        .timer-num.warn{color:var(--gold)} .timer-num.danger{color:var(--red);animation:pulse 1s infinite}
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:.5}}
        .step-indicator{display:flex;align-items:center;gap:8px}
        .step{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;
            font-size:11px;font-weight:700;border:2px solid var(--border);color:var(--muted)}
        .step.done{background:var(--green);border-color:var(--green);color:#fff}
        .step.active{background:var(--accent);border-color:var(--accent);color:#fff}
        .step-line{width:24px;height:2px;background:var(--border)}
        .step-line.done{background:var(--green)}

        /* LAYOUT */
        .tes-layout{display:flex;flex:1;overflow:hidden}
        .nav-soal{width:var(--sidebar-w);background:var(--surface);border-right:1px solid var(--border);
            overflow-y:auto;padding:16px;flex-shrink:0}
        .nav-soal h4{font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;
            letter-spacing:1px;margin-bottom:12px}
        .soal-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:5px}
        .soal-btn{width:100%;aspect-ratio:1;border-radius:6px;border:none;cursor:pointer;
            font-size:12px;font-weight:600;transition:all .15s;background:var(--surface2);color:var(--muted)}
        .soal-btn:hover{border:1px solid var(--accent)}
        .soal-btn.aktif{background:var(--accent);color:#fff}
        .soal-btn.dijawab{background:rgba(16,185,129,.2);color:var(--green);border:1px solid rgba(16,185,129,.3)}
        .soal-btn.ragu{background:rgba(245,158,11,.2);color:var(--gold);border:1px solid rgba(245,158,11,.3)}
        .legend{margin-top:14px;display:flex;flex-direction:column;gap:6px}
        .legend-item{display:flex;align-items:center;gap:7px;font-size:11px;color:var(--muted)}
        .legend-dot{width:12px;height:12px;border-radius:3px}
        .prog-section{margin-top:14px;padding:12px;background:var(--surface2);border-radius:8px}
        .prog-section-label{font-size:11px;color:var(--muted);margin-bottom:6px}
        .prog-bar{height:5px;background:var(--border);border-radius:3px}
        .prog-fill{height:5px;background:var(--green);border-radius:3px;transition:width .3s}

        /* SOAL CONTENT */
        .soal-main{flex:1;display:flex;flex-direction:column;overflow:hidden}
        .soal-scroll{flex:1;overflow-y:auto;padding:22px}
        .soal-inner{max-width:720px;margin:0 auto}
        .soal-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
        .passage-box{background:var(--surface2);border-left:3px solid var(--accent);
            padding:14px;border-radius:0 10px 10px 0;margin-bottom:16px;
            font-size:13.5px;line-height:1.75;color:#94a3b8;max-height:200px;overflow-y:auto}
        .audio-box{background:var(--surface2);border-radius:10px;padding:14px;
            display:flex;align-items:center;gap:12px;margin-bottom:16px;border:1px solid var(--border)}
        .play-btn{width:40px;height:40px;border-radius:9px;background:var(--orange);color:#fff;
            border:none;cursor:pointer;font-size:15px;flex-shrink:0;transition:opacity .15s}
        .play-btn:hover{opacity:.85} .play-btn:disabled{background:var(--surface2);color:var(--muted);cursor:not-allowed}
        .pertanyaan{font-size:15px;font-weight:600;line-height:1.65;margin-bottom:18px}
        .pilihan-list{display:flex;flex-direction:column;gap:9px}
        .pilihan-item{display:flex;align-items:flex-start;gap:11px;padding:13px 15px;
            border-radius:10px;border:1.5px solid var(--border);cursor:pointer;transition:all .15s}
        .pilihan-item:hover{border-color:var(--accent);background:rgba(59,130,246,.04)}
        .pilihan-item.selected{border-color:var(--accent);background:rgba(59,130,246,.1)}
        .pilihan-item input[type=radio]{accent-color:var(--accent);cursor:pointer;margin-top:3px;flex-shrink:0}
        .opt-label{font-weight:700;color:var(--muted);width:17px;flex-shrink:0}
        .opt-text{font-size:14px;line-height:1.5}

        /* FOOTER */
        .soal-footer{padding:14px 22px;background:var(--surface);border-top:1px solid var(--border);
            display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
        .btn-ragu{display:inline-flex;align-items:center;gap:7px;padding:8px 15px;
            border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;border:none;
            font-family:inherit;background:rgba(245,158,11,.1);color:var(--gold);transition:all .15s}
        .btn-ragu.active{background:rgba(245,158,11,.25)}
        .nav-btns{display:flex;gap:10px}
        .btn-nav{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;
            border-radius:8px;font-size:13.5px;font-weight:600;cursor:pointer;border:none;
            font-family:inherit;transition:all .15s}
        .btn-prev{background:var(--surface2);color:var(--muted);border:1px solid var(--border)}
        .btn-prev:hover:not(:disabled){border-color:var(--accent);color:var(--accent)}
        .btn-prev:disabled{opacity:.4;cursor:not-allowed}
        .btn-next{background:var(--accent);color:#fff} .btn-next:hover{background:#2563eb}
        .btn-submit{background:var(--green);color:#fff;display:none} .btn-submit:hover{opacity:.88}

        /* MODAL */
        .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);z-index:200;align-items:center;justify-content:center}
        .modal-overlay.show{display:flex}
        .modal-box{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:26px;max-width:420px;width:90%}
        .modal-box h3{font-size:17px;font-weight:700;margin-bottom:14px}
        .modal-footer{display:flex;gap:10px;justify-content:flex-end;margin-top:18px}
        .btn-cancel{background:transparent;border:1px solid var(--border);border-radius:8px;padding:9px 18px;font-size:13px;font-weight:600;cursor:pointer;color:var(--muted);font-family:inherit}
        ::-webkit-scrollbar{width:4px} ::-webkit-scrollbar-thumb{background:var(--border);border-radius:10px}
    </style>
</head>
<body>

{{-- TOPBAR --}}
<div class="topbar">
    <div style="display:flex;align-items:center;gap:14px">
        <span style="font-size:14px;font-weight:800">Simulasi TOEFL ITP</span>
        <span class="section-badge sb-{{ $currentSection }}">
            Section {{ $sectionNum[$currentSection] }}: {{ ucfirst($currentSection) }}
        </span>
    </div>
    <div style="display:flex;align-items:center;gap:16px">
        {{-- Step indicator --}}
        <div class="step-indicator">
            @foreach(['listening'=>1,'structure'=>2,'reading'=>3] as $s=>$n)
            <div class="step {{ $currentSection === $s ? 'active' : (array_search($s, array_keys(['listening'=>1,'structure'=>2,'reading'=>3])) < array_search($currentSection, array_keys(['listening'=>1,'structure'=>2,'reading'=>3])) ? 'done' : '') }}">
                @if(array_search($s, array_keys(['listening'=>1,'structure'=>2,'reading'=>3])) < array_search($currentSection, array_keys(['listening'=>1,'structure'=>2,'reading'=>3])))
                <i class="fas fa-check" style="font-size:10px"></i>
                @else
                {{ $n }}
                @endif
            </div>
            @if($n < 3) <div class="step-line {{ array_search($s, array_keys(['listening'=>1,'structure'=>2,'reading'=>3])) < array_search($currentSection, array_keys(['listening'=>1,'structure'=>2,'reading'=>3])) ? 'done' : '' }}"></div> @endif
            @endforeach
        </div>
        <div class="timer-wrap">
            <i class="fas fa-clock" style="color:var(--muted);font-size:13px"></i>
            <span class="timer-num" id="timer-display">{{ sprintf('%02d:%02d', intdiv($durasi,60), $durasi%60) }}</span>
        </div>
        {{-- Tombol fullscreen simulasi --}}
        <button onclick="toggleFs()" title="Toggle Layar Penuh"
            style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);
            color:rgba(255,255,255,.65);border-radius:8px;padding:7px 12px;cursor:pointer;
            font-size:12px;display:flex;align-items:center;gap:6px;font-family:inherit;transition:all .15s"
            onmouseover="this.style.background='rgba(255,255,255,.15)'"
            onmouseout="this.style.background='rgba(255,255,255,.08)'">
            <i class="fas fa-expand" id="fs-icon"></i>
            <span id="fs-lbl">Layar Penuh</span>
        </button>
    </div>
</div>

<div class="tes-layout">
    {{-- NAVIGATOR --}}
    <div class="nav-soal">
        <h4>Navigator Soal</h4>
        <div class="soal-grid" id="soal-nav">
            @foreach($soalList as $i => $s)
            <button class="soal-btn {{ $i===0 ? 'aktif':'' }}" data-idx="{{ $i }}" onclick="navigateTo({{ $i }})">
                {{ $i+1 }}
            </button>
            @endforeach
        </div>
        <div class="legend">
            <div class="legend-item"><div class="legend-dot" style="background:var(--surface2);border:1px solid var(--muted)"></div> Belum</div>
            <div class="legend-item"><div class="legend-dot" style="background:rgba(16,185,129,.2);border:1px solid rgba(16,185,129,.3)"></div> Dijawab</div>
            <div class="legend-item"><div class="legend-dot" style="background:rgba(245,158,11,.2);border:1px solid rgba(245,158,11,.3)"></div> Ragu</div>
        </div>
        <div class="prog-section">
            <div class="prog-section-label"><span id="cnt-dijawab" style="color:var(--green)">0</span> / {{ count($soalList) }} dijawab</div>
            <div class="prog-bar"><div class="prog-fill" id="prog-fill" style="width:0%"></div></div>
        </div>
    </div>

    {{-- MAIN --}}
    <form id="sim-form" action="{{ route('user.tes.simulasi.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="section" value="{{ $currentSection }}">
        <input type="hidden" name="percobaan_id" value="{{ $percobaanId }}">

        <div class="soal-main">
            <div class="soal-scroll" id="soal-scroll">
                <div class="soal-inner">
                    @foreach($soalList as $i => $soal)
                    <div class="soal-item" id="item-{{ $i }}" style="display:{{ $i===0?'block':'none' }}">
                        <div class="soal-header">
                            <span style="font-size:13px;color:var(--muted)">Soal <strong style="color:var(--text)">{{ $i+1 }}</strong> dari {{ count($soalList) }}</span>
                            <span class="badge badge-gray">{{ ucfirst($soal->tingkat_kesulitan) }}</span>
                        </div>

                        @if($soal->passage_teks)
                        <div class="passage-box">{{ $soal->passage_teks }}</div>
                        @endif

                        @if($soal->audio_url)
                        @php
                            $aUrl = \App\Services\AudioService::resolveUrl($soal->audio_url);
                            $pid  = 'sim-' . $i;
                        @endphp
                        <div class="toefl-audio-wrap" style="margin-bottom:14px">
                          <div class="tap-label" style="color:#94a3b8">
                            <i class="fas fa-headphones-alt"></i> Audio
                            <span style="font-size:10px;color:#10b981;font-weight:600;margin-left:4px">{{ $currentSection==='listening' ? '1x saja' : 'Dapat diputar ulang' }}</span>
                          </div>
                          <div class="tap-bar">
                            <button type="button" class="tap-play-btn" id="btn-{{ $pid }}" onclick="tapToggle('{{ $pid }}')" aria-label="Play">
                              <span class="tap-play-triangle" id="icon-{{ $pid }}"></span>
                            </button>
                            <div class="tap-track-outer" id="track-{{ $pid }}" onclick="tapSeek(event,'{{ $pid }}')">
                              <div class="tap-track-inner">
                                <div class="tap-track-fill" id="fill-{{ $pid }}" style="width:0%"></div>
                              </div>
                              <div class="tap-thumb" id="thumb-{{ $pid }}" style="left:0%"></div>
                            </div>
                            <span class="tap-time" id="time-{{ $pid }}">00:00</span>
                            <button type="button" class="tap-vol-btn" onclick="tapToggleMute('{{ $pid }}')">
                              <i class="fas fa-volume-up tap-vol-icon" id="volicon-{{ $pid }}"></i>
                            </button>
                            <audio id="aud-{{ $pid }}" preload="auto" src="{{ $aUrl }}"
                              data-mode="practice"
                              oncanplay="tapOnCanPlay('{{ $pid }}')"
                              ontimeupdate="tapOnTimeUpdate('{{ $pid }}')"
                              onended="tapOnEnded('{{ $pid }}')">
                            </audio>
                          </div>
                          <div class="tap-status" id="status-{{ $pid }}">Klik ▶ untuk memutar</div>
                        </div>
                        @endif

                        <p class="pertanyaan">{{ $soal->pertanyaan }}</p>

                        <div class="pilihan-list">
                        @foreach(['a','b','c','d'] as $opt)
                        <label class="pilihan-item" data-idx="{{ $i }}">
                            <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $opt }}"
                                class="jwb-radio" data-idx="{{ $i }}" onchange="onJawab({{ $i }}, '{{ $opt }}', this)">
                            <span class="opt-label">{{ strtoupper($opt) }}</span>
                            <span class="opt-text">{{ $soal->{'pilihan_'.$opt} }}</span>
                        </label>
                        @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="soal-footer">
                <button type="button" class="btn-ragu" id="btn-ragu" onclick="toggleRagu()">
                    <i class="fas fa-flag"></i> Tandai Ragu
                </button>
                <div class="nav-btns">
                    @if($currentSection !== 'listening')
                    <button type="button" class="btn-nav btn-prev" id="btn-prev" onclick="prevSoal()" disabled>
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </button>
                    @endif
                    <button type="button" class="btn-nav btn-next" id="btn-next" onclick="nextSoal()">
                        Berikutnya <i class="fas fa-chevron-right"></i>
                    </button>
                    <button type="button" class="btn-nav btn-submit" id="btn-submit" onclick="confirmSubmit()">
                        @if($currentSection === 'reading')
                        <i class="fas fa-check"></i> Selesaikan Tes
                        @else
                        Lanjut ke Section Berikutnya <i class="fas fa-arrow-right"></i>
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- MODAL KONFIRMASI --}}
<div class="modal-overlay" id="modal-confirm">
    <div class="modal-box">
        <h3>
            @if($currentSection === 'reading')
            <i class="fas fa-check-circle" style="color:var(--green);margin-right:8px"></i>Selesaikan Simulasi?
            @else
            <i class="fas fa-arrow-right" style="color:var(--accent);margin-right:8px"></i>Lanjut ke Section Berikutnya?
            @endif
        </h3>
        <div id="confirm-summary" style="background:var(--surface2);border-radius:8px;padding:14px;font-size:13.5px;line-height:1.8"></div>
        @if($currentSection !== 'reading')
        <div style="margin-top:10px;padding:11px 14px;background:rgba(239,68,68,.07);border-radius:8px;font-size:13px;color:#fca5a5">
            <i class="fas fa-exclamation-circle"></i> Setelah lanjut, Anda tidak bisa kembali ke section ini.
        </div>
        @endif
        <div class="modal-footer">
            <button class="btn-cancel" onclick="document.getElementById('modal-confirm').classList.remove('show')">Batal</button>
            <button class="btn-nav {{ $currentSection === 'reading' ? 'btn-submit':'btn-next' }}" style="display:inline-flex" onclick="doSubmit()">
                <i class="fas fa-check"></i>
                {{ $currentSection === 'reading' ? 'Selesaikan':'Ya, Lanjutkan' }}
            </button>
        </div>
    </div>
</div>

<script>
const TOTAL      = {{ count($soalList) }};
const IS_LISTEN  = '{{ $currentSection }}' === 'listening';
let current      = 0;
let jawaban      = {};
let statusSoal   = {};
let audioPlayed  = {};
let timerSisa    = {{ $durasi }};

function showSoal(idx) {
    document.querySelectorAll('.soal-item').forEach(e => e.style.display='none');
    document.getElementById('item-'+idx).style.display = 'block';
    document.querySelectorAll('.soal-btn').forEach(b => {
        b.classList.remove('aktif');
        if (parseInt(b.dataset.idx)===idx) b.classList.add('aktif');
    });
    const btnPrev=document.getElementById('btn-prev'), btnNext=document.getElementById('btn-next'), btnSub=document.getElementById('btn-submit');
    if (btnPrev) btnPrev.disabled = idx===0;
    if (idx === TOTAL-1) { if(btnNext)btnNext.style.display='none'; if(btnSub)btnSub.style.display='inline-flex'; }
    else { if(btnNext)btnNext.style.display='inline-flex'; if(btnSub)btnSub.style.display='none'; }
    const btnRagu = document.getElementById('btn-ragu');
    if (statusSoal[idx]==='ragu') btnRagu.classList.add('active'); else btnRagu.classList.remove('active');
    current = idx;
    document.getElementById('soal-scroll').scrollTop = 0;
}

function navigateTo(idx) { showSoal(idx); }
function nextSoal() { if(current<TOTAL-1) showSoal(current+1); }
function prevSoal() { if(current>0) showSoal(current-1); }

function onJawab(idx, opt, radio) {
    jawaban[idx] = opt;
    if (statusSoal[idx]!=='ragu') statusSoal[idx]='dijawab';
    updateNavBtn(idx);
    updateProgress();
    document.querySelectorAll(`label[data-idx="${idx}"]`).forEach(l=>l.classList.remove('selected'));
    radio.closest('label').classList.add('selected');
}

function toggleRagu() {
    const btn=document.getElementById('btn-ragu');
    statusSoal[current] = statusSoal[current]==='ragu' ? (jawaban[current]?'dijawab':'belum') : 'ragu';
    if(statusSoal[current]==='ragu') btn.classList.add('active'); else btn.classList.remove('active');
    updateNavBtn(current);
}

function updateNavBtn(idx) {
    const btn=document.querySelector(`.soal-btn[data-idx="${idx}"]`); if(!btn)return;
    btn.classList.remove('dijawab','ragu');
    if(statusSoal[idx]==='ragu') btn.classList.add('ragu');
    else if(jawaban[idx]) btn.classList.add('dijawab');
}

function updateProgress() {
    const d=Object.values(jawaban).filter(v=>v).length;
    document.getElementById('cnt-dijawab').textContent=d;
    document.getElementById('prog-fill').style.width=Math.round((d/TOTAL)*100)+'%';
}

// Simulasi audio: mode=practice (replay allowed), gunakan global engine
// Audio: tapToggle() dari audio-player.js

function startTimer() {
    const iv=setInterval(()=>{
        timerSisa--;
        const m=Math.floor(timerSisa/60),s=timerSisa%60;
        const el=document.getElementById('timer-display');
        el.textContent=String(m).padStart(2,'0')+':'+String(s).padStart(2,'0');
        el.className='timer-num'+(timerSisa<=60?' danger':timerSisa<=180?' warn':'');
        if(timerSisa<=0){clearInterval(iv);document.getElementById('sim-form').submit();}
    },1000);
}

function confirmSubmit() {
    const d=Object.values(jawaban).filter(v=>v).length;
    document.getElementById('confirm-summary').innerHTML=
        `<div>Total soal: <strong>${TOTAL}</strong></div>
         <div>Dijawab: <strong style="color:var(--green)">${d}</strong></div>
         <div>Belum dijawab: <strong style="color:${TOTAL-d>0?'var(--red)':'var(--green)'}">${TOTAL-d}</strong></div>`;
    document.getElementById('modal-confirm').classList.add('show');
}

function doSubmit() { document.getElementById('sim-form').submit(); }

// ── Fullscreen sederhana — Simulasi (TANPA anti-cheat) ──────────
function enterFullscreen() {
    const el = document.documentElement;
    try {
        const p = el.requestFullscreen?.() || el.webkitRequestFullscreen?.();
        if (p instanceof Promise) p.catch(()=>{});
    } catch(e) {}
}
function toggleFs() {
    if (document.fullscreenElement || document.webkitFullscreenElement) {
        (document.exitFullscreen || document.webkitExitFullscreen)?.call(document);
    } else { enterFullscreen(); }
}
document.addEventListener('fullscreenchange', () => {
    const ico = document.getElementById('fs-icon');
    const lbl = document.getElementById('fs-lbl');
    if (!ico) return;
    const isFs = !!(document.fullscreenElement || document.webkitFullscreenElement);
    ico.className = isFs ? 'fas fa-compress' : 'fas fa-expand';
    if (lbl) lbl.textContent = isFs ? 'Keluar Penuh' : 'Layar Penuh';
});

document.addEventListener('DOMContentLoaded', () => {
    showSoal(0);
    updateProgress();
    startTimer();
    // Masuk fullscreen otomatis
    setTimeout(enterFullscreen, 400);
});
</script>
<script src="{{ asset('js/audio-player.js') }}"></script>
</body>
</html>
