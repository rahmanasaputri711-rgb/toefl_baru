<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TOEFL ITP — {{ $percobaan->sesiTes->judul ?? 'Tes' }} | Section {{ $sectionNum[$currentSection] }}</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/audio-player.css') }}">
<style>
:root{
  --blue:#1a56db;--navy:#0f2456;--navy2:#162244;
  --blue-l:#eff6ff;--blue-p:#dbeafe;
  --green:#16a34a;--gold:#d97706;--red:#dc2626;--orange:#ea580c;
  --text:#1e293b;--muted:#64748b;--border:#e2e8f0;--bg:#f8fafc;--white:#fff;
}
*{margin:0;padding:0;box-sizing:border-box}
html,body{height:100%;overflow:hidden}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text);
  display:flex;flex-direction:column;user-select:none;-webkit-user-select:none}

/* ── TOPBAR ── */
.topbar{background:var(--navy);height:56px;display:flex;align-items:center;
  justify-content:space-between;padding:0 18px;flex-shrink:0;z-index:10}
.tb-left{display:flex;align-items:center;gap:12px}
.tb-dot{width:8px;height:8px;border-radius:50%;background:#4ade80;box-shadow:0 0 6px #4ade80}
.tb-title{font-size:14px;font-weight:800;color:#fff}
.tb-subtitle{font-size:11.5px;color:rgba(255,255,255,.45);margin-left:4px}
.step-wrap{display:flex;align-items:center;gap:6px}
.step-item{display:flex;align-items:center;gap:5px}
.step-circle{width:24px;height:24px;border-radius:50%;display:flex;align-items:center;
  justify-content:center;font-size:10.5px;font-weight:700}
.step-circle.done{background:#4ade80;color:#0f2456}
.step-circle.current{background:var(--blue);color:#fff}
.step-circle.waiting{background:rgba(255,255,255,.12);color:rgba(255,255,255,.4)}
.step-label{font-size:11.5px;font-weight:600}
.step-div{width:18px;height:1px;background:rgba(255,255,255,.2)}
.section-chip{padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;
  text-transform:uppercase;letter-spacing:.8px;margin-left:8px}
.sc-listening{background:rgba(234,88,12,.25);color:#fb923c}
.sc-structure {background:rgba(217,119,6,.25);color:#fbbf24}
.sc-reading   {background:rgba(26,86,219,.3);color:#93c5fd}
.timer-box{display:flex;align-items:center;gap:7px;padding:5px 14px;border-radius:8px;
  background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15)}
.timer-val{font-family:'JetBrains Mono',monospace;font-size:19px;font-weight:500;color:#fff}
.timer-val.warn  {color:#fbbf24}
.timer-val.danger{color:#f87171;animation:blink 1s infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.4}}
.tb-nodftr{font-family:'JetBrains Mono',monospace;font-size:10.5px;color:rgba(255,255,255,.35)}

/* ── LISTEN BAR (countdown per soal) ── */
.listen-bar{height:32px;background:var(--navy2);border-bottom:1px solid rgba(255,255,255,.08);
  display:none;align-items:center;gap:12px;padding:0 18px;flex-shrink:0}
.listen-bar.show{display:flex}
.lb-label{font-size:11px;font-weight:700;color:rgba(255,255,255,.6);white-space:nowrap;letter-spacing:.5px;text-transform:uppercase}
.lb-track{flex:1;height:5px;background:rgba(255,255,255,.12);border-radius:3px;overflow:hidden}
.lb-fill{height:5px;border-radius:3px;transition:width .4s linear;width:100%}
.lb-fill.ok   {background:#4ade80}
.lb-fill.warn {background:#fbbf24}
.lb-fill.danger{background:#f87171}
.lb-sisa{font-family:'JetBrains Mono',monospace;font-size:12px;font-weight:500;
  color:rgba(255,255,255,.7);min-width:32px;text-align:right}

/* ── LAYOUT ── */
.main-wrap{display:flex;flex:1;overflow:hidden}

/* ── NAVIGATOR SIDEBAR ── */
.nav-side{width:210px;background:#fff;border-right:1px solid var(--border);
  display:flex;flex-direction:column;flex-shrink:0;overflow:hidden}
.ns-head{padding:12px 14px;border-bottom:1px solid var(--border);flex-shrink:0}
.ns-head-title{font-size:10.5px;font-weight:700;color:var(--muted);text-transform:uppercase;
  letter-spacing:1px;margin-bottom:10px}
.ns-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:4px;margin-bottom:10px}
.nv.audio-live{background:rgba(234,88,12,.3)!important;color:#fb923c!important;border-color:#ea580c!important}
.nv{width:100%;aspect-ratio:1;border-radius:5px;border:1.5px solid var(--border);
  cursor:pointer;font-size:11px;font-weight:600;font-family:inherit;
  background:#fff;color:var(--muted);transition:all .12s;line-height:1}
.nv:hover{border-color:var(--blue);color:var(--blue)}
.nv.active  {background:var(--blue);border-color:var(--blue);color:#fff;box-shadow:0 0 0 2px rgba(26,86,219,.3)}
.nv.answered{background:#dcfce7;border-color:#86efac;color:var(--green)}
.nv.doubt   {background:#fef9c3;border-color:#fde047;color:#854d0e}
.nv.listen-lock{cursor:default;opacity:.35;pointer-events:none}
.ns-legend{display:flex;flex-direction:column;gap:4px;margin-bottom:10px}
.leg{display:flex;align-items:center;gap:6px;font-size:10.5px;color:var(--muted)}
.leg-dot{width:11px;height:11px;border-radius:3px;flex-shrink:0}
.ns-prog{background:var(--bg);border-radius:7px;padding:10px}
.ns-prog-label{display:flex;justify-content:space-between;font-size:11.5px;margin-bottom:6px}
.ns-prog-bar{height:5px;background:var(--border);border-radius:3px;overflow:hidden}
.ns-prog-fill{height:5px;background:var(--green);border-radius:3px;transition:width .3s}
.ns-listen-info{margin:8px 0;padding:9px 10px;background:#fff7ed;border-radius:7px;
  border:1px solid #fed7aa;font-size:11px;color:#9a3412;line-height:1.5}

/* ── SOAL AREA ── */
.soal-area{flex:1;display:flex;flex-direction:column;overflow:hidden}
.soal-scroll{flex:1;overflow-y:auto;padding:22px 28px}
.soal-wrap{max-width:720px;margin:0 auto}
.soal-no{font-size:12.5px;color:var(--muted);margin-bottom:14px}
.soal-no b{color:var(--navy);font-size:14.5px}

/* ── PASSAGE ── */
.passage{background:#f8fafc;border-left:4px solid var(--blue);padding:14px 16px;
  border-radius:0 10px 10px 0;margin-bottom:16px;font-size:13.5px;line-height:1.8;
  color:#374151;max-height:200px;overflow-y:auto}

/* ── AUDIO PLAYER (seperti gambar referensi) ── */
.audio-player-wrap{background:#4a4a4a;border-radius:6px;padding:8px 12px;
  display:flex;align-items:center;gap:10px;margin-bottom:16px;
  border:1px solid #333;box-shadow:inset 0 1px 3px rgba(0,0,0,.4)}
.ap-play-btn{width:32px;height:32px;border-radius:3px;border:none;cursor:pointer;
  background:transparent;display:flex;align-items:center;justify-content:center;
  transition:opacity .15s;flex-shrink:0}
.ap-play-btn:hover:not(:disabled){opacity:.85}
.ap-play-btn:disabled{cursor:not-allowed;opacity:.4}
.ap-play-icon{width:0;height:0;border-style:solid;border-width:8px 0 8px 14px;
  border-color:transparent transparent transparent #ccc}
.ap-play-icon.playing{border:none;display:flex;gap:3px;align-items:center}
.ap-play-icon.playing::before,.ap-play-icon.playing::after{
  content:'';display:block;width:3px;height:14px;background:#ccc;border-radius:1px}
.ap-track-wrap{flex:1;display:flex;flex-direction:column;gap:4px}
.ap-progress{position:relative;height:14px;cursor:pointer}
.ap-progress-bg{position:absolute;inset:5px 0;background:#2a2a2a;border-radius:3px;overflow:hidden}
.ap-progress-fill{height:100%;background:linear-gradient(90deg,#4a90e2,#2a70c2);border-radius:3px;
  transition:width .3s linear;min-width:2px}
.ap-progress-thumb{position:absolute;top:50%;transform:translateY(-50%);
  width:12px;height:12px;background:#ccc;border-radius:50%;margin-left:-6px;
  box-shadow:0 1px 3px rgba(0,0,0,.5)}
.ap-time{font-family:'JetBrains Mono',monospace;font-size:11.5px;color:#aaa;
  white-space:nowrap;align-self:center}
.ap-vol-btn{width:24px;height:24px;border:none;background:transparent;cursor:pointer;
  display:flex;align-items:center;justify-content:center;flex-shrink:0}
.ap-vol-icon{font-size:14px;color:#aaa}
/* Label status audio */
.audio-status-text{font-size:11px;margin-top:5px;font-style:italic}
.ast-played{color:#f87171}
.ast-playing{color:#4ade80}
.ast-ready{color:#94a3b8}

/* ── PERTANYAAN & PILIHAN ── */
.pertanyaan{font-size:15px;font-weight:600;color:var(--navy);line-height:1.65;margin-bottom:18px}
.pilihan-list{display:flex;flex-direction:column;gap:8px}
.pilihan{display:flex;align-items:flex-start;gap:11px;padding:12px 15px;
  border-radius:9px;border:1.5px solid var(--border);cursor:pointer;
  transition:all .13s;background:#fff}
.pilihan:hover{border-color:var(--blue);background:var(--blue-l)}
.pilihan.selected{border-color:var(--blue);background:var(--blue-l)}
.pilihan.doubt{border-color:var(--gold);background:#fffbeb}
.pilihan input[type=radio]{display:none}
.opt-badge{width:26px;height:26px;border-radius:6px;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;
  background:var(--bg);color:var(--muted);border:1px solid var(--border)}
.pilihan.selected .opt-badge{background:var(--blue);color:#fff;border-color:var(--blue)}
.pilihan.doubt   .opt-badge{background:var(--gold);color:#fff;border-color:var(--gold)}
.opt-text{font-size:14px;line-height:1.5;flex:1}

/* ── FOOTER NAV ── */
.soal-footer{padding:12px 22px;background:#fff;border-top:1px solid var(--border);
  display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.btn-doubt{display:inline-flex;align-items:center;gap:7px;padding:8px 15px;border-radius:8px;
  font-size:13px;font-weight:600;cursor:pointer;border:1px solid #fde68a;
  background:#fef9c3;color:#854d0e;font-family:inherit;transition:all .13s}
.btn-doubt.active{background:#fde68a;border-color:var(--gold)}
.footer-btns{display:flex;gap:8px}
.btn-nav{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:8px;
  font-size:13.5px;font-weight:600;cursor:pointer;border:none;font-family:inherit;transition:all .13s}
.btn-prev{background:var(--bg);color:var(--muted);border:1.5px solid var(--border)}
.btn-prev:hover:not(:disabled){border-color:var(--blue);color:var(--blue)}
.btn-prev:disabled{opacity:.35;cursor:not-allowed}
.btn-next{background:var(--blue);color:#fff}
.btn-next:hover{background:var(--navy)}
.btn-done{background:var(--green);color:#fff;display:none}
.btn-done:hover{opacity:.9}

/* ── MODAL ── */
.modal-bg{display:none;position:fixed;inset:0;background:rgba(15,36,86,.55);
  z-index:500;align-items:center;justify-content:center}
.modal-bg.open{display:flex}
.modal{background:#fff;border-radius:14px;padding:26px;max-width:420px;width:90%;
  box-shadow:0 20px 60px rgba(0,0,0,.2)}
.modal h3{font-size:16px;font-weight:700;color:var(--navy);margin-bottom:14px}
.modal-sum{background:var(--bg);border-radius:8px;padding:14px;font-size:13.5px;line-height:1.9;margin-bottom:14px}
.modal-warn{padding:11px 13px;background:#fff7ed;border-radius:8px;border:1px solid #fed7aa;
  font-size:12.5px;color:#9a3412;margin-bottom:14px}
.modal-actions{display:flex;gap:10px;justify-content:flex-end}
.btn-cancel{background:var(--bg);border:1.5px solid var(--border);border-radius:8px;
  padding:8px 18px;font-size:13px;font-weight:600;cursor:pointer;color:var(--muted);font-family:inherit}

/* ── VIOLATION OVERLAY ── */
.vio-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.75);
  z-index:900;align-items:center;justify-content:center}
.vio-overlay.open{display:flex}
.vio-box{background:#fff;border-radius:14px;padding:28px;max-width:380px;width:90%;
  border-top:4px solid var(--red);text-align:center}
.vio-icon{font-size:40px;color:var(--red);margin-bottom:12px}
.vio-title{font-size:17px;font-weight:800;color:var(--navy);margin-bottom:8px}
.vio-msg{font-size:13.5px;color:var(--muted);margin-bottom:16px;line-height:1.6}
.vio-count{background:var(--bg);border-radius:8px;padding:12px;margin-bottom:16px;font-size:14px}
.vio-count b{font-size:22px;color:var(--red)}

::-webkit-scrollbar{width:5px;height:5px}
::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:10px}
</style>
</head>
<body>

{{-- ════════════════════════════════════════════════════════════
     SPLASH SCREEN — Muncul sebelum tes, user harus klik
     z-index: 99999 — di atas semua elemen termasuk topbar
     ════════════════════════════════════════════════════════════ --}}
<div id="fs-splash" style="
    position: fixed;
    inset: 0;
    z-index: 99999;
    background: #0b1120;
    display: {{ $currentSection === "listening" ? "flex" : "none" }};
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 20px;
    padding: 24px;
    pointer-events: all;
">
    <i class="fas fa-shield-alt" style="font-size:52px;color:#3b82f6"></i>
    <div style="font-size:24px;font-weight:800;color:#f1f5f9;text-align:center">
        Tes Full TOEFL ITP
    </div>
    <div style="background:#1e2d40;border:1px solid rgba(245,158,11,.3);border-radius:14px;
        padding:20px 28px;max-width:460px;width:100%;text-align:center">
        <div style="color:#fbbf24;font-weight:700;margin-bottom:12px;font-size:15px">
            ⚠️ Peraturan Tes
        </div>
        <div style="font-size:13.5px;color:#94a3b8;line-height:2;text-align:left">
            • Tes berlangsung dalam <strong style="color:#f1f5f9">mode layar penuh</strong><br>
            • Keluar layar penuh = <strong style="color:#f87171">pelanggaran</strong><br>
            • 3× pelanggaran = tes otomatis dikumpulkan<br>
            • Dilarang pindah tab, screenshot, atau buka aplikasi lain
        </div>
    </div>
    <button
        id="btn-mulai-tes"
        type="button"
        onclick="mulaiTes()"
        style="
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 16px 44px;
            font-size: 17px;
            font-weight: 800;
            cursor: pointer;
            font-family: inherit;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background .2s, transform .1s;
            pointer-events: all;
            position: relative;
            z-index: 100000;
        "
        onmouseover="this.style.background='#2563eb';this.style.transform='scale(1.03)'"
        onmouseout="this.style.background='#3b82f6';this.style.transform='scale(1)'"
        onmousedown="this.style.transform='scale(.97)'"
        onmouseup="this.style.transform='scale(1.03)'"
    >
        <i class="fas fa-play-circle"></i> Saya Siap — Mulai Tes
    </button>
    <div style="font-size:12px;color:#475569;text-align:center">
        Pastikan koneksi internet stabil sebelum memulai
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════ TOPBAR ═══ --}}
<div class="topbar">
  <div class="tb-left">
    <div class="tb-dot"></div>
    <span class="tb-title">TOEFL ITP</span>
    <span class="tb-subtitle">{{ $percobaan->sesiTes->judul ?? '' }}</span>
    {{-- Step indicator --}}
    <div class="step-wrap" style="margin-left:14px">
      @foreach(['listening'=>1,'structure'=>2,'reading'=>3] as $sec=>$n)
      @php
        $secKeys = array_keys(['listening'=>1,'structure'=>2,'reading'=>3]);
        $curIdx  = array_search($currentSection, $secKeys);
        $thisIdx = array_search($sec, $secKeys);
        $isDone  = $thisIdx < $curIdx;
        $isCur   = $sec === $currentSection;
      @endphp
      <div class="step-item">
        <div class="step-circle {{ $isDone ? 'done' : ($isCur ? 'current' : 'waiting') }}">
          @if($isDone)<i class="fas fa-check" style="font-size:9px"></i>@else{{ $n }}@endif
        </div>
        <span class="step-label" style="color:{{ $isCur ? '#fff' : ($isDone ? '#4ade80' : 'rgba(255,255,255,.3)') }}">
          {{ ucfirst($sec) }}
        </span>
      </div>
      @if($n < 3)<div class="step-div"></div>@endif
      @endforeach
    </div>
    <span class="section-chip sc-{{ $currentSection }}">Section {{ $sectionNum[$currentSection] }}</span>
  </div>
  <div class="timer-box">
    <i class="fas fa-clock" style="color:rgba(255,255,255,.5);font-size:12px"></i>
    <span class="timer-val" id="timer">{{ sprintf('%02d:%02d',intdiv($durasiDetik,60),$durasiDetik%60) }}</span>
  </div>
  {{-- Indikator fullscreen tes full — hanya tampilkan status, tidak bisa keluar --}}
  <div id="fs-indicator" style="display:flex;align-items:center;gap:6px;
    background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.25);
    border-radius:7px;padding:5px 11px;font-size:11.5px;color:#4ade80">
    <i class="fas fa-expand-arrows-alt" style="font-size:11px" id="fs-ind-ico"></i>
    <span id="fs-ind-lbl">Full Screen</span>
  </div>
  <div class="tb-nodftr">{{ $pendaftaran->nomor_pendaftaran ?? '' }}</div>
</div>

{{-- ═══ AUDIO GLOBAL LISTENING — 1 file utuh ±35 menit ═══ --}}
@if($currentSection === 'listening')
<div id="global-audio-bar" style="background:#1a1a2e;border-bottom:1px solid rgba(255,255,255,.1);
    padding:10px 20px;display:flex;align-items:center;gap:14px">

    {{-- Status icon --}}
    <div style="width:34px;height:34px;border-radius:50%;flex-shrink:0;
        background:rgba(234,88,12,.2);border:2px solid #ea580c;
        display:flex;align-items:center;justify-content:center" id="g-status-ico">
        <i class="fas fa-headphones" style="color:#ea580c;font-size:13px"></i>
    </div>

    {{-- Label --}}
    <div style="flex-shrink:0">
        <div style="font-size:12px;font-weight:700;color:#fdba74">
            🎧 Audio Listening — Section 1
        </div>
        <div style="font-size:11px;color:rgba(255,255,255,.45)" id="g-status-txt">
            Menunggu audio dimuat...
        </div>
    </div>

    {{-- Progress bar --}}
    <div style="flex:1;height:5px;background:rgba(255,255,255,.1);border-radius:3px;overflow:hidden">
        <div id="g-progress" style="height:5px;background:#ea580c;border-radius:3px;width:0%;
            transition:width .8s linear"></div>
    </div>

    {{-- Waktu --}}
    <div style="font-size:12px;font-family:monospace;color:#fdba74;flex-shrink:0" id="g-time">
        00:00 / 35:00
    </div>

    {{-- Kontrol audio berdasarkan tipe tes --}}
    @if(($tipeTes ?? 'full') === 'full')
    {{-- TES FULL: hanya 1 tombol Play, setelah play tidak bisa apa-apa --}}
    <button id="g-play-once" onclick="startAudioOnce()"
        style="background:rgba(234,88,12,.9);border:none;border-radius:8px;
        padding:6px 16px;color:#fff;font-size:12.5px;font-weight:700;
        cursor:pointer;font-family:inherit;flex-shrink:0;
        display:flex;align-items:center;gap:7px"
        title="Audio hanya bisa diputar 1 kali">
        <i class="fas fa-play" style="font-size:10px"></i> Mulai Audio
    </button>
    <span style="font-size:11px;color:rgba(239,68,68,.6);flex-shrink:0;
        background:rgba(239,68,68,.08);padding:3px 10px;border-radius:5px;
        border:1px solid rgba(239,68,68,.2)">
        <i class="fas fa-lock" style="font-size:9px"></i> 1× putar · no pause · no rewind
    </span>
    @else
    {{-- SIMULASI / MINI: pause + speed tersedia --}}
    <div style="display:flex;gap:6px;align-items:center;flex-shrink:0">
        <button id="g-pause-btn" onclick="toggleGlobalPause()"
            style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);
            color:rgba(255,255,255,.8);border-radius:6px;padding:4px 12px;
            cursor:pointer;font-size:12px;font-family:inherit">
            <i class="fas fa-play" id="g-pause-ico"></i>
        </button>
        <select id="g-speed-sel" onchange="setGlobalSpeed(this.value)"
            style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);
            color:rgba(255,255,255,.7);border-radius:6px;padding:3px 7px;
            font-size:12px;font-family:inherit;cursor:pointer">
            <option value="0.75">0.75×</option>
            <option value="1" selected>1×</option>
            <option value="1.25">1.25×</option>
            <option value="1.5">1.5×</option>
        </select>
    </div>
    <span style="font-size:11px;color:rgba(34,197,94,.6);flex-shrink:0;
        background:rgba(34,197,94,.08);padding:3px 8px;border-radius:5px;
        border:1px solid rgba(34,197,94,.2)">
        <i class="fas fa-play" style="font-size:9px"></i> Bebas diputar
    </span>
    @endif

    {{-- Audio element tersembunyi — TIDAK ADA kontrol langsung untuk user --}}
    @if(!empty($audioGlobal))
    <audio id="global-audio" src="{{ $audioGlobal }}" preload="auto" style="display:none"
        oncanplay="onGlobalReady()"
        ontimeupdate="onGlobalTick()"
        onended="onGlobalEnded()">
    </audio>
    @else
    <span style="color:#f87171;font-size:11px">
        <i class="fas fa-exclamation-triangle"></i> Audio belum diupload ke sesi ini
    </span>
    @endif
</div>
@endif

{{-- Listening answer countdown bar --}}
<div class="listen-bar {{ $currentSection==='listening' ? 'show':'' }}" id="lb">
  <span class="lb-label">Waktu Jawab</span>
  <div class="lb-track"><div class="lb-fill ok" id="lb-fill"></div></div>
  <span class="lb-sisa" id="lb-sisa">12s</span>
</div>

{{-- ═══════════════════════════════════════════════════════ MAIN ══════ --}}
<div class="main-wrap">

  {{-- ─── NAVIGATOR SIDEBAR ─── --}}
  <div class="nav-side">
    <div class="ns-head" style="overflow-y:auto;flex:1">
      <div class="ns-head-title">Navigator Soal</div>
      <div class="ns-grid" id="nav-grid">
        {{-- Listening: semua soal BISA diklik (audio tetap jalan terus) --}}
        @foreach($soalList as $i=>$s)
        <button class="nv {{ $i===0?'active':'' }}"
            data-idx="{{ $i }}"
            id="nav-btn-{{ $i }}"
            onclick="goSoal({{ $i }})"
            title="Soal {{ $i+1 }}">
          {{ $i+1 }}
        </button>
        @endforeach
      </div>
      <div class="ns-legend">
        <div class="leg"><div class="leg-dot" style="background:var(--blue)"></div> Aktif</div>
        <div class="leg"><div class="leg-dot" style="background:#dcfce7;border:1px solid #86efac"></div> Dijawab</div>
        <div class="leg"><div class="leg-dot" style="background:#fef9c3;border:1px solid #fde047"></div> Ragu-ragu</div>
        <div class="leg"><div class="leg-dot" style="background:var(--bg);border:1px solid var(--border)"></div> Belum</div>
      </div>
      <div class="ns-prog">
        <div class="ns-prog-label">
          <span id="cnt-ans" style="color:var(--green);font-weight:700">0 dijawab</span>
          <span style="color:var(--muted)">dari {{ count($soalList) }}</span>
        </div>
        <div class="ns-prog-bar"><div class="ns-prog-fill" id="prog-fill" style="width:0%"></div></div>
      </div>
      @if($currentSection==='listening')
      <div class="ns-listen-info">
        <i class="fas fa-info-circle"></i>
        <strong>Listening:</strong> Audio 1x putar, soal otomatis. Kamu bisa klik nomor manapun.
      </div>
      <button id="btn-kembali-aktif"
        onclick="kembaliKeSoalAktif()"
        style="display:none;width:100%;margin-top:10px;padding:8px;
        background:rgba(234,88,12,.2);color:#fb923c;border:1.5px solid rgba(234,88,12,.4);
        border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;
        font-family:inherit;animation:pulse-orange 1.5s infinite">
        <i class="fas fa-headphones"></i> Kembali ke Soal Aktif
      </button>
      @endif
    </div>
  </div>

  {{-- ─── SOAL AREA ─── --}}
  <form id="tes-form" action="{{ route('user.tes.submit') }}" method="POST">
    @csrf
    <input type="hidden" name="percobaan_id" value="{{ $percobaan->id }}">
    <input type="hidden" name="section"       value="{{ $currentSection }}">

    <div class="soal-area">
      <div class="soal-scroll" id="soal-scroll">
        <div class="soal-wrap">

          @foreach($soalList as $i=>$s)
          @php
            $soal = $s->soal ?? $s;
            $audioUrl = $soal->audio_url_resolved ?? \App\Services\AudioService::resolveUrl($soal->audio_url ?? null);
            $jawabanUser = $jawabanTersimpan[$soal->id] ?? null;
          @endphp
          @if(!$soal || !$soal->pertanyaan)
              @continue
          @endif

          <div class="soal-item" id="item-{{ $i }}" style="display:{{ $i===0?'block':'none' }}">

            <div class="soal-no">Soal <b>{{ $i+1 }}</b> dari {{ count($soalList) }}</div>

            {{-- Passage --}}
            @if($soal->passage_teks)
            <div class="passage">{{ $soal->passage_teks }}</div>
            @endif

            {{-- Audio per soal: TERSEMBUNYI di Listening (pakai audio global)
                 TAMPIL di Structure/Reading jika ada audio --}}
            @if($audioUrl)
            @php $pid = 'soal-' . $i; @endphp
            <div class="toefl-audio-wrap" id="wrap-{{ $pid }}"
                style="{{ $currentSection==='listening' ? 'display:none' : '' }}">
              <div class="tap-label">
                <i class="fas fa-headphones-alt"></i> Audio Listening
                @if($currentSection==='listening')
                  <span class="tap-once-badge">1× saja</span>
                @endif
              </div>
              <div class="tap-bar" id="bar-{{ $pid }}">
                <button type="button" class="tap-play-btn" id="btn-{{ $pid }}"
                  onclick="tapToggle('{{ $pid }}')" aria-label="Play">
                  <span class="tap-play-triangle" id="icon-{{ $pid }}"></span>
                </button>
                <div class="tap-track-outer" id="track-{{ $pid }}"
                  onclick="tapSeek(event,'{{ $pid }}')">
                  <div class="tap-track-inner">
                    <div class="tap-track-fill" id="fill-{{ $pid }}" style="width:0%"></div>
                  </div>
                  <div class="tap-thumb" id="thumb-{{ $pid }}" style="left:0%"></div>
                </div>
                <span class="tap-time" id="time-{{ $pid }}">00:00</span>
                <button type="button" class="tap-vol-btn" onclick="tapToggleMute('{{ $pid }}')">
                  <i class="fas fa-volume-up tap-vol-icon" id="volicon-{{ $pid }}"></i>
                </button>
                <audio id="aud-{{ $pid }}" preload="auto" src="{{ $audioUrl }}"
                  data-mode="{{ $currentSection==='listening' ? 'full' : 'practice' }}"
                  oncanplay="tapOnCanPlay('{{ $pid }}')"
                  ontimeupdate="tapOnTimeUpdate('{{ $pid }}')"
                  onended="tapOnEnded('{{ $pid }}'); onAudioEnded({{ $i }})">
                </audio>
              </div>
              <div class="tap-status" id="status-{{ $pid }}">
                @if($currentSection==='listening') Diputar otomatis — 1× saja
                @else Klik ▶ untuk memutar @endif
              </div>
            </div>
            @endif

            {{-- Pertanyaan --}}
            <p class="pertanyaan">{{ $soal->pertanyaan }}</p>

            {{-- Pilihan --}}
            <div class="pilihan-list">
              @foreach(['a','b','c','d'] as $opt)
              <label class="pilihan {{ $jawabanUser===$opt ? 'selected':'' }}"
                id="lbl-{{ $i }}-{{ $opt }}" data-idx="{{ $i }}" data-opt="{{ $opt }}">
                <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $opt }}"
                  class="jwb" data-idx="{{ $i }}" data-soal="{{ $soal->id }}"
                  {{ $jawabanUser===$opt?'checked':'' }}
                  onchange="onJawab({{ $i }},'{{ $soal->id }}','{{ $opt }}')">
                <div class="opt-badge">{{ strtoupper($opt) }}</div>
                <span class="opt-text">{{ $soal->{'pilihan_'.$opt} }}</span>
              </label>
              @endforeach
            </div>

          </div>
          @endforeach

        </div>
      </div>

      {{-- Footer Navigation --}}
      <div class="soal-footer">
        <button type="button" class="btn-doubt" id="btn-doubt" onclick="toggleDoubt()">
          <i class="fas fa-flag"></i> Ragu-ragu
        </button>
        <div class="footer-btns">
          @if($currentSection!=='listening')
          <button type="button" class="btn-nav btn-prev" id="btn-prev" onclick="prevSoal()" disabled>
            <i class="fas fa-chevron-left"></i> Sebelumnya
          </button>
          @endif
          <button type="button" class="btn-nav btn-next" id="btn-next" onclick="nextSoal()">
            Berikutnya <i class="fas fa-chevron-right"></i>
          </button>
          <button type="button" class="btn-nav btn-done" id="btn-done" onclick="openFinish()">
            @if($currentSection==='reading')
            <i class="fas fa-flag-checkered"></i> Selesaikan Tes
            @else Lanjut Section <i class="fas fa-arrow-right"></i>
            @endif
          </button>
        </div>
      </div>
    </div>
  </form>

</div><!-- /main-wrap -->

{{-- ═══════════════════════════════════════════ MODAL FINISH ══════════ --}}
<div class="modal-bg" id="modal-fin">
  <div class="modal">
    <h3>
      @if($currentSection==='reading')
      <i class="fas fa-flag-checkered" style="color:var(--green);margin-right:8px"></i>Selesaikan Tes?
      @else
      <i class="fas fa-arrow-right" style="color:var(--blue);margin-right:8px"></i>Lanjut Section Berikutnya?
      @endif
    </h3>
    <div class="modal-sum" id="fin-sum"></div>
    @if($currentSection!=='reading')
    <div class="modal-warn"><i class="fas fa-exclamation-circle"></i> Setelah lanjut, Anda tidak bisa kembali ke section ini.</div>
    @endif
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeModal('modal-fin')">Batal</button>
      <button class="btn-nav btn-done" id="btn-final-submit"
        style="display:inline-flex;min-width:160px;justify-content:center"
        onclick="this.disabled=true;
                 this.innerHTML='<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;Menyimpan...';
                 doSubmit()">
        <i class="fas fa-check"></i>
        {{ $currentSection==='reading' ? 'Ya, Selesaikan Tes' : 'Ya, Lanjut Section Berikutnya' }}
      </button>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════════ VIOLATION OVERLAY ═════ --}}
<div class="vio-overlay" id="vio-overlay">
  <div class="vio-box">
    <div class="vio-icon"><i class="fas fa-exclamation-triangle"></i></div>
    <div class="vio-title">Pelanggaran Terdeteksi!</div>
    <div class="vio-msg" id="vio-msg"></div>
    <div class="vio-count">Pelanggaran ke <b id="vio-n">0</b> dari 3</div>
    <button class="btn-nav btn-next" id="btn-close-vio"
    style="width:100%;justify-content:center"
    onclick="closeVio()">Mengerti, Lanjutkan</button>
  </div>
</div>

<script src="{{ asset('js/audio-player.js') }}"></script>

<script>
// ═══════════════════════════════════════════════════════════════════
// mulaiTes() — PERTAMA agar onclick tombol splash langsung bekerja
// ═══════════════════════════════════════════════════════════════════
function mulaiTes() {
    // Sembunyikan splash
    var splash = document.getElementById('fs-splash');
    if (splash) splash.style.display = 'none';

    // Fullscreen — dipanggil dari onclick, browser pasti izinkan
    try {
        var el = document.documentElement;
        if      (el.requestFullscreen)            el.requestFullscreen();
        else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
        else if (el.mozRequestFullScreen)    el.mozRequestFullScreen();
    } catch(e) { console.warn('Fullscreen gagal:', e); }

    // Aktifkan anti-cheat setelah fullscreen stabil
    setTimeout(function(){ if(typeof _fsReady!=='undefined') _fsReady = true; }, 1500);
    setTimeout(function(){ if(typeof _acReady!=='undefined') _acReady = true; }, 3000);

    // Mulai timer tes
    if (typeof startTimer === 'function') startTimer();
    // Set soal pertama sebagai live audio untuk Listening
    if (SECTION === 'listening') setTimeout(() => updateNavAudioLive(0), 500);
}

// ═══════════════════════════════════════════════════════════════════
// KONSTANTA
// ═══════════════════════════════════════════════════════════════════
const TOTAL        = {{ count($soalList) }};
const SECTION      = '{{ $currentSection }}';
const TIPE_TES     = '{{ $tipeTes ?? "full" }}'; // full | simulasi | mini | praktik
const IS_FULL_TES  = TIPE_TES === 'full';         // full = aturan ketat audio
const IS_LISTEN    = SECTION === 'listening';
const DURASI_INIT  = {{ $durasiDetik }};
const PERCOBAAN_ID = {{ $percobaan->id }};
const CSRF         = '{{ csrf_token() }}';
const LISTEN_WAIT  = 12; // detik untuk menjawab setelah audio selesai

// ═══════════════════════════════════════════════════════════════════
// STATE
// ═══════════════════════════════════════════════════════════════════
let cur          = 0;
let jawaban      = {};   // {idx: opt}
let statusSoal   = {};   // {idx: 'belum'|'answered'|'doubt'}
let audioPlayed  = {};   // {idx: true} — untuk 1x play di Tes Full
let timerSisa    = DURASI_INIT;
let timerIv      = null;
let lbIv         = null; // listening countdown interval
// FIX: ambil jumlah pelanggaran aktual dari DB percobaan ini
// bukan dari counter yang mungkin sudah corrupt
let pelanggaranN = {{ (int) $percobaan->jumlah_pelanggaran }};
let lastVioTime  = 0;

// Jawaban tersimpan dari server (resume)
const jawabanTersimpan = {!! json_encode($jawabanTersimpan) !!};

// ═══════════════════════════════════════════════════════════════════
// INIT: restore jawaban tersimpan ke UI
// ═══════════════════════════════════════════════════════════════════
function initRestore() {
    let soalItems = document.querySelectorAll('.soal-item');
    soalItems.forEach((item, idx) => {
        const radios = item.querySelectorAll('.jwb');
        radios.forEach(r => {
            const soalId = r.dataset.soal;
            const saved  = jawabanTersimpan[soalId];
            if (saved && r.value === saved) {
                r.checked = true;
                jawaban[idx] = saved;
                statusSoal[idx] = 'answered';
                updateNavBtn(idx);
            }
        });
    });
    updateProgress();
}

// ═══════════════════════════════════════════════════════════════════
// TIMER UTAMA
// ═══════════════════════════════════════════════════════════════════
function startTimer() {
    timerIv = setInterval(() => {
        timerSisa--;
        const m = Math.floor(timerSisa / 60);
        const s = timerSisa % 60;
        const el = document.getElementById('timer');
        el.textContent = String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
        el.className = 'timer-val' + (timerSisa <= 60 ? ' danger' : timerSisa <= 300 ? ' warn' : '');
        if (timerSisa <= 0) { clearInterval(timerIv); doSubmit(true); }
        // Keepalive autosave setiap 30 detik
        if (timerSisa % 30 === 0) keepAlive();
    }, 1000);
}

// ═══════════════════════════════════════════════════════════════════
// NAVIGASI
// ═══════════════════════════════════════════════════════════════════
function showSoal(idx) {
    document.querySelectorAll('.soal-item').forEach(el => el.style.display = 'none');
    const item = document.getElementById('item-'+idx);
    if (item) item.style.display = 'block';

    document.querySelectorAll('.nv').forEach(btn => {
        btn.classList.remove('active');
        if (parseInt(btn.dataset.idx) === idx) btn.classList.add('active');
    });

    const bPrev = document.getElementById('btn-prev');
    const bNext = document.getElementById('btn-next');
    const bDone = document.getElementById('btn-done');
    if (bPrev) bPrev.disabled = idx === 0;
    if (idx === TOTAL - 1) {
        if (bNext) bNext.style.display = 'none';
        if (bDone) bDone.style.display = 'inline-flex';
    } else {
        if (bNext) bNext.style.display = 'inline-flex';
        if (bDone) bDone.style.display = 'none';
    }

    // Doubt button state
    const bDoubt = document.getElementById('btn-doubt');
    if (statusSoal[idx] === 'doubt') bDoubt.classList.add('active');
    else bDoubt.classList.remove('active');

    cur = idx;
    document.getElementById('soal-scroll').scrollTop = 0;

    // Listening: trigger audio otomatis
    if (IS_LISTEN) {
        clearLbTimer();
        const aud = document.getElementById('aud-'+idx);
        if (aud && !audioPlayed[idx]) {
            setTimeout(() => triggerListenAudio(idx), 500);
        } else if (audioPlayed[idx]) {
            // Audio sudah diputar, langsung mulai countdown
            startLbTimer(idx);
        }
    }
}

function goSoal(idx) { if (!IS_LISTEN) showSoal(idx); }
function nextSoal()  { if (cur < TOTAL-1) showSoal(cur+1); }
function prevSoal()  { if (cur > 0 && !IS_LISTEN) showSoal(cur-1); }

// ═══════════════════════════════════════════════════════════════════
// JAWABAN
// ═══════════════════════════════════════════════════════════════════
function onJawab(idx, soalId, opt) {
    jawaban[idx] = opt;
    if (statusSoal[idx] !== 'doubt') statusSoal[idx] = 'answered';
    updateNavBtn(idx);
    updateProgress();

    // Style labels
    document.querySelectorAll(`label[data-idx="${idx}"]`).forEach(l => {
        l.classList.remove('selected','doubt');
    });
    const lbl = document.querySelector(`label[data-idx="${idx}"][data-opt="${opt}"]`);
    if (lbl) {
        lbl.classList.add(statusSoal[idx] === 'doubt' ? 'doubt' : 'selected');
    }

    // Autosave ke server
    saveJawaban(soalId, opt, idx);
}

function toggleDoubt() {
    const btn = document.getElementById('btn-doubt');
    if (statusSoal[cur] === 'doubt') {
        statusSoal[cur] = jawaban[cur] ? 'answered' : 'belum';
        btn.classList.remove('active');
    } else {
        statusSoal[cur] = 'doubt';
        btn.classList.add('active');
    }
    updateNavBtn(cur);
    // Re-style pilihan
    if (jawaban[cur]) {
        const lbl = document.querySelector(`label[data-idx="${cur}"][data-opt="${jawaban[cur]}"]`);
        if (lbl) {
            lbl.classList.remove('selected','doubt');
            lbl.classList.add(statusSoal[cur] === 'doubt' ? 'doubt' : 'selected');
        }
    }
}

function updateNavBtn(idx) {
    const btn = document.querySelector(`.nv[data-idx="${idx}"]`);
    if (!btn) return;
    btn.classList.remove('answered','doubt');
    if (statusSoal[idx] === 'doubt')    btn.classList.add('doubt');
    else if (jawaban[idx])              btn.classList.add('answered');
}

function updateProgress() {
    const d = Object.values(jawaban).filter(v=>v).length;
    document.getElementById('cnt-ans').textContent = d + ' dijawab';
    document.getElementById('prog-fill').style.width = Math.round((d/TOTAL)*100) + '%';
}

// ═══════════════════════════════════════════════════════════════════
// AUDIO — delegated to /js/audio-player.js (global engine)
// Mode 'full' = 1x play, no replay, no seek after play
// tapToggle(), tapTriggerAutoPlay() dipanggil dari listener di bawah
// ═══════════════════════════════════════════════════════════════════

function triggerListenAudio(idx) {
    const pid = 'soal-' + idx;
    const audEl = document.getElementById('aud-' + pid);
    if (!audEl) {
        // Soal ini tidak punya audio, langsung countdown
        onAudioEnded(idx);
        return;
    }
    // Gunakan engine audio-player.js untuk auto-play
    tapTriggerAutoPlay(pid);
    // Fallback: jika 4 detik audio belum mulai play (autoplay diblokir browser)
    setTimeout(() => {
        if (!tapIsPlayed(pid)) {
            const statusEl = document.getElementById('status-' + pid);
            if (statusEl) statusEl.textContent = '⚠ Klik ▶ untuk memutar audio';
        }
    }, 4000);
}

// ═══════════════════════════════════════════════════════════════════
// LISTENING: COUNTDOWN 12 DETIK SETELAH AUDIO SELESAI
// ═══════════════════════════════════════════════════════════════════
function onAudioEnded(idx) {
    // Update indikator bahwa soal berikutnya yang "live"
    if (idx + 1 < soalList.length) updateNavAudioLive(idx + 1);
    if (!IS_LISTEN) return;
    startLbTimer(idx);
}

function startLbTimer(idx) {
    clearLbTimer();
    let sisa  = LISTEN_WAIT;
    const fill = document.getElementById('lb-fill');
    const sEl  = document.getElementById('lb-sisa');

    lbIv = setInterval(() => {
        sisa--;
        const pct = (sisa / LISTEN_WAIT) * 100;
        if (fill) {
            fill.style.width = pct + '%';
            fill.className = 'lb-fill ' + (sisa <= 3 ? 'danger' : sisa <= 6 ? 'warn' : 'ok');
        }
        if (sEl) { sEl.textContent = sisa + 's'; sEl.style.color = sisa <= 3 ? '#f87171' : 'rgba(255,255,255,.7)'; }

        if (sisa <= 0) {
            clearLbTimer();
            if (idx < TOTAL - 1) showSoal(idx + 1);
            else doSubmit();
        }
    }, 1000);
}

function clearLbTimer() {
    if (lbIv) { clearInterval(lbIv); lbIv = null; }
    const fill = document.getElementById('lb-fill');
    const sEl  = document.getElementById('lb-sisa');
    if (fill) { fill.style.width = '100%'; fill.className = 'lb-fill ok'; }
    if (sEl)  { sEl.textContent = LISTEN_WAIT + 's'; sEl.style.color = 'rgba(255,255,255,.7)'; }
}

// ═══════════════════════════════════════════════════════════════════
// AUTOSAVE & SERVER COMMUNICATION
// ═══════════════════════════════════════════════════════════════════
function saveJawaban(soalId, opt, nomorSoal) {
    const fd = new FormData();
    fd.append('_token', CSRF);
    fd.append('percobaan_id', PERCOBAAN_ID);
    fd.append('soal_id', soalId);
    fd.append('jawaban', opt);
    fd.append('nomor_soal', nomorSoal + 1);
    fd.append('status_soal', statusSoal[nomorSoal] || 'dijawab');
    fetch('/tes/save-jawaban', { method:'POST', body:fd }).catch(()=>{});
}

function keepAlive() {
    const fd = new FormData();
    fd.append('_token', CSRF);
    fd.append('percobaan_id', PERCOBAAN_ID);
    fetch('/tes/autosave', { method:'POST', body:fd }).catch(()=>{});
}

// ═══════════════════════════════════════════════════════════════════
// FINISH
// ═══════════════════════════════════════════════════════════════════
function openFinish() {
    const d = Object.values(jawaban).filter(v=>v).length;
    document.getElementById('fin-sum').innerHTML =
        `<div>Total soal: <strong>${TOTAL}</strong></div>
         <div>Sudah dijawab: <strong style="color:var(--green)">${d}</strong></div>
         <div>Belum dijawab: <strong style="color:${TOTAL-d>0?'var(--red)':'var(--green)'}">${TOTAL-d}</strong></div>`;
    document.getElementById('modal-fin').classList.add('open');
}

function doSubmit(forceFinish) {
    if (_submitting) return;  // hindari double submit
    _submitting = true;

    clearInterval(timerIv);
    clearLbTimer();

    // Nonaktifkan beforeunload agar tidak muncul konfirmasi saat submit
    window.onbeforeunload = null;
    window.removeEventListener('beforeunload', function(){});

    // Jika force finish (pelanggaran/waktu habis) — set hidden field
    // agar server langsung panggil selesaikan() tanpa cek section berikutnya
    if (forceFinish) {
        var inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = 'force_finish';
        inp.value = '1';
        document.getElementById('tes-form').appendChild(inp);
    }

    document.getElementById('tes-form').submit();
}

function closeModal(id) { document.getElementById(id).classList.remove('open'); }

// ═══════════════════════════════════════════════════════════════════
// ANTI-CHEAT — TES FULL (keamanan ketat)
// ═══════════════════════════════════════════════════════════════════

// Flag: listener aktif baru 3 detik setelah load (hindari false positive)
let _acReady    = false;
let _fsReady    = false;  // flag fullscreen sudah stabil
let _submitting = false;  // flag: sedang proses submit (hindari double)

function catatPelanggaran(tipe) {
    if (!_acReady) return;           // jangan proses sebelum halaman siap
    if (_submitting) return;         // sudah dalam proses submit, abaikan

    const now = Date.now();
    if (now - lastVioTime < 4000) return;  // debounce 4 detik (lebih ketat)
    lastVioTime = now;
    pelanggaranN++;

    document.getElementById('vio-n').textContent = pelanggaranN;
    const msgs = {
        pindah_tab:        'Anda berpindah tab/window saat tes berlangsung!',
        keluar_halaman:    'Fokus berpindah dari jendela tes!',
        keluar_fullscreen: 'Anda keluar dari mode layar penuh!',
        screenshot:        'Percobaan screenshot/DevTools terdeteksi!',
    };
    document.getElementById('vio-msg').textContent = msgs[tipe] || 'Pelanggaran terdeteksi!';
    document.getElementById('vio-overlay').classList.add('open');

    const fd = new FormData();
    fd.append('_token',        CSRF);
    fd.append('percobaan_id',  PERCOBAAN_ID);
    fd.append('jenis',         tipe);
    fd.append('tipe_aksi',     tipe);
    fd.append('pelanggaran_ke',pelanggaranN);
    fetch('/tes/catat-pelanggaran', { method:'POST', body:fd }).catch(()=>{});

    // Force submit saat pelanggaran ke-3
    if (pelanggaranN >= 3) {
        // Update pesan overlay
        document.getElementById('vio-msg').textContent =
            'Tes dihentikan! 3 pelanggaran terdeteksi. Jawaban dikumpulkan dalam 3 detik...';

        // Ganti tombol agar tidak bisa diklik
        var btn = document.getElementById('btn-close-vio');
        if (btn) {
            btn.disabled = true;
            btn.style.opacity = '0.4';
            btn.style.cursor  = 'not-allowed';
            btn.textContent   = 'Mengumpulkan jawaban...';
        }

        // Countdown 3 detik lalu submit
        var sisa = 3;
        var iv = setInterval(function() {
            sisa--;
            if (btn) btn.textContent = 'Mengumpulkan dalam ' + sisa + ' detik...';
            if (sisa <= 0) {
                clearInterval(iv);
                doSubmit(true);  // forceFinish=true → langsung ke hasil
            }
        }, 1000);
    }
}

function closeVio() {
    // Jika sudah 3 pelanggaran, tombol ini tidak boleh menutup overlay
    if (pelanggaranN >= 3) {
        doSubmit(true);
        return;
    }
    document.getElementById('vio-overlay').classList.remove('open');
    enterFullscreen();
}

// Visibility change: pindah tab
document.addEventListener('visibilitychange', () => {
    if (!_acReady) return;
    if (document.hidden) catatPelanggaran('pindah_tab');
});

// Window blur: pindah aplikasi — beri grace period 500ms
window.addEventListener('blur', () => {
    if (!_acReady) return;
    setTimeout(() => {
        if (!document.hidden && _acReady) catatPelanggaran('keluar_halaman');
    }, 500);
});

// contextmenu sudah dihandle di keydown block

// Blokir shortcut berbahaya
document.addEventListener('keydown', e => {
    // Blokir ESC (keluar fullscreen)
    if (e.key === 'Escape') {
        e.preventDefault();
        e.stopPropagation();
        return false;
    }

    // Blokir F5 (refresh)
    if (e.key === 'F5') {
        e.preventDefault();
        return false;
    }

    // Blokir F12 (DevTools)
    if (e.key === 'F12') {
        e.preventDefault();
        return false;
    }

    // Blokir shortcut berbahaya
    const ctrlBlocked = e.ctrlKey && ['p','P','s','S','u','U','r','R','w','W'].includes(e.key);
    const altBlocked  = e.altKey  && ['Tab','F4'].includes(e.key);
    const printScreen = e.key === 'PrintScreen';

    if (ctrlBlocked || altBlocked || printScreen) {
        e.preventDefault();
        if (_acReady) catatPelanggaran('screenshot');
        return false;
    }
});

// Blokir klik kanan
document.addEventListener('contextmenu', e => {
    e.preventDefault();
    return false;
}, true);

// Blokir select text
document.addEventListener('selectstart', e => e.preventDefault());

// Blokir copy
document.addEventListener('copy', e => e.preventDefault());

// Blokir drag
document.addEventListener('dragstart', e => e.preventDefault());

// Cegah navigasi keluar halaman (back button)
window.history.pushState(null, '', window.location.href);
window.addEventListener('popstate', () => {
    window.history.pushState(null, '', window.location.href);
});

// Peringatan jika coba tutup tab
window.addEventListener('beforeunload', (e) => {
    e.preventDefault();
    e.returnValue = 'Tes sedang berlangsung! Yakin ingin keluar?';
    return e.returnValue;
});

// Blokir screen recording (browser tidak bisa 100% blokir, tapi bisa deteksi)
if (navigator.mediaDevices && navigator.mediaDevices.getDisplayMedia) {
    const origGetDisplayMedia = navigator.mediaDevices.getDisplayMedia.bind(navigator.mediaDevices);
    navigator.mediaDevices.getDisplayMedia = function() {
        if (_acReady) catatPelanggaran('screenshot');
        return Promise.reject(new Error('Dilarang saat tes'));
    };
}

// ═══════════════════════════════════════════════════════════════════
// FULLSCREEN KETAT — TES FULL
// Keluar fullscreen = pelanggaran, langsung masuk lagi
// ═══════════════════════════════════════════════════════════════════
function enterFullscreen() {
    const el = document.documentElement;
    const p  = el.requestFullscreen?.()
            || el.webkitRequestFullscreen?.()
            || el.mozRequestFullScreen?.();
    return p instanceof Promise ? p.catch(()=>{}) : undefined;
}

// Deteksi keluar fullscreen — tapi beri jeda 5 detik di awal agar
// animasi masuk fullscreen tidak langsung trigger pelanggaran
document.addEventListener('fullscreenchange',       onFsChange);
document.addEventListener('webkitfullscreenchange', onFsChange);

function onFsChange() {
    if (!_fsReady) return;
    const isFullscreen = !!(document.fullscreenElement || document.webkitFullscreenElement);
    if (!isFullscreen) {
        // Catat pelanggaran
        if (_acReady) catatPelanggaran('keluar_fullscreen');
        // Paksa masuk fullscreen SEGERA (tidak bisa menolak)
        setTimeout(() => {
            enterFullscreen();
        }, 300);
    }
}

// ═══════════════════════════════════════════════════════════════════
// INIT
// ═══════════════════════════════════════════════════════════════════

// Update indikator fullscreen di topbar tes full
document.addEventListener('fullscreenchange', () => {
    const ind = document.getElementById('fs-indicator');
    const ico = document.getElementById('fs-ind-ico');
    const lbl = document.getElementById('fs-ind-lbl');
    if (!ind) return;
    if (document.fullscreenElement) {
        ind.style.background = 'rgba(34,197,94,.12)';
        ind.style.borderColor = 'rgba(34,197,94,.25)';
        ind.style.color = '#4ade80';
        if(ico) ico.className = 'fas fa-expand-arrows-alt';
        if(lbl) lbl.textContent = 'Full Screen';
    } else {
        ind.style.background = 'rgba(239,68,68,.12)';
        ind.style.borderColor = 'rgba(239,68,68,.25)';
        ind.style.color = '#f87171';
        if(ico) ico.className = 'fas fa-compress-arrows-alt';
        if(lbl) lbl.textContent = 'Keluar Layar!';
    }
});

// ══════════════════════════════════════════════════════════════
// GLOBAL AUDIO LISTENING — 1 file utuh, tanpa kontrol user
// ══════════════════════════════════════════════════════════════
const globalAudio = document.getElementById('global-audio');

// ── Tes Full: 1 tombol play, setelah itu dikunci total ──────────
function startAudioOnce() {
    if (!globalAudio) return;
    const btn = document.getElementById('g-play-once');
    if (!btn) return;

    globalAudio.play().then(() => {
        // Sembunyikan tombol setelah play — tidak bisa play lagi
        btn.style.display = 'none';
        _globalStarted = true;

        // Kunci semua kontrol
        globalAudio.addEventListener('pause', function() {
            if (!window._submitting && _globalStarted)
                setTimeout(() => { if (this.paused && !window._submitting) this.play().catch(()=>{}); }, 200);
        });

        // Blokir seek/rewind
        globalAudio.addEventListener('seeking', function() {
            if (this.currentTime < (this._lastTime || 0) - 1)
                this.currentTime = this._lastTime || 0;
        });
        globalAudio.addEventListener('timeupdate', function() {
            this._lastTime = this.currentTime;
        });

        // Blokir media session (tombol HP)
        if ('mediaSession' in navigator) {
            ['pause','stop','seekbackward','seekforward','previoustrack','nexttrack']
                .forEach(a => { try { navigator.mediaSession.setActionHandler(a, () => {}); } catch(e){} });
        }
    }).catch(e => console.warn('Audio play error:', e));
}
let _globalStarted = false;

function onGlobalReady() {
    document.getElementById('g-status-txt').textContent = 'Klik "Saya Siap" untuk mulai...';
}

// Dipanggil dari mulaiTes() saat user klik tombol splash
function startGlobalAudio() {
    if (!globalAudio || _globalStarted) return;
    _globalStarted = true;
    globalAudio.play().catch(e => console.warn('Audio play:', e));
    document.getElementById('g-status-txt').textContent = 'Sedang diputar...';
    document.getElementById('g-status-ico').style.borderColor = '#4ade80';
    document.querySelector('#g-status-ico i').style.color = '#4ade80';

    // Tes Full: blokir pause dari tombol keyboard/media session
    if (IS_FULL_TES) {
        // Cegah pause dari luar (tombol keyboard media)
        globalAudio.addEventListener('pause', function() {
            if (_globalStarted && !_submitting) {
                // Auto-resume jika di-pause paksa (bukan karena submit)
                setTimeout(() => {
                    if (globalAudio.paused && !_submitting) {
                        globalAudio.play().catch(()=>{});
                    }
                }, 200);
            }
        });
    }
}

function onGlobalTick() {
    if (!globalAudio) return;
    const cur = globalAudio.currentTime;
    const dur = globalAudio.duration || 2100;
    const pct = (cur / dur) * 100;

    // Update progress bar
    const prog = document.getElementById('g-progress');
    if (prog) prog.style.width = pct + '%';

    // Update waktu
    const timeEl = document.getElementById('g-time');
    if (timeEl) {
        const fmt = s => String(Math.floor(s/60)).padStart(2,'0') + ':' + String(Math.floor(s%60)).padStart(2,'0');
        timeEl.textContent = fmt(cur) + ' / ' + fmt(dur);
    }
}

function toggleGlobalPause() {
    // Di Tes Full: tombol tidak tersedia (disembunyikan di blade)
    if (!globalAudio || IS_FULL_TES) return;
    if (globalAudio.paused) {
        globalAudio.play();
        document.getElementById('g-pause-ico').className = 'fas fa-pause';
    } else {
        globalAudio.pause();
        document.getElementById('g-pause-ico').className = 'fas fa-play';
    }
}

function setGlobalSpeed(v) {
    // Di Tes Full: speed dikunci 1x
    if (!globalAudio || IS_FULL_TES) return;
    globalAudio.playbackRate = parseFloat(v);
}

function onGlobalEnded() {
    const txt = document.getElementById('g-status-txt');
    if (txt) txt.textContent = 'Audio selesai. Kerjakan soal yang tersisa.';
    const prog = document.getElementById('g-progress');
    if (prog) { prog.style.width = '100%'; prog.style.background = '#4ade80'; }
}

// ─────────────────────────────────────────────────────────────
// ATURAN AUDIO BERDASARKAN TIPE TES
// Tes Full  : 1× putar, NO pause, NO seek, NO speed
// Simulasi  : boleh pause & speed, tidak bisa rewind
// Mini Test : bebas pause & speed
// ─────────────────────────────────────────────────────────────
if (globalAudio) {
    // Simpan posisi terakhir untuk deteksi rewind
    globalAudio.addEventListener('timeupdate', function() {
        this._lastTime = this.currentTime;
    });

    if (IS_FULL_TES) {
        // ── TES FULL: semua kontrol dikunci ──

        // Blokir rewind
        globalAudio.addEventListener('seeking', function() {
            if (this.currentTime < (this._lastTime || 0) - 1)
                this.currentTime = this._lastTime || 0;
        });

        // Blokir pause paksa — putar ulang otomatis
        globalAudio.addEventListener('pause', function() {
            if (!window._submitting && _globalStarted)
                setTimeout(() => { if (this.paused && !window._submitting) this.play(); }, 200);
        });

        // Blokir speed change (paksa 1×)
        const _origSetRate = Object.getOwnPropertyDescriptor(HTMLMediaElement.prototype, 'playbackRate')?.set;
        if (_origSetRate) {
            Object.defineProperty(globalAudio, 'playbackRate', {
                get: function() { return 1; },
                set: function() { _origSetRate.call(this, 1); },
                configurable: true,
            });
        }

        // Blokir media session (tombol HP / notifikasi)
        if ('mediaSession' in navigator) {
            ['pause','stop','seekbackward','seekforward','previoustrack','nexttrack']
                .forEach(a => { try { navigator.mediaSession.setActionHandler(a, () => {}); } catch(e){} });
        }

    } else {
        // ── SIMULASI / MINI: blokir rewind signifikan saja ──
        globalAudio.addEventListener('seeking', function() {
            if (this.currentTime < (this._lastTime || 0) - 3)
                this.currentTime = this._lastTime || 0;
        });
    }
}

// Pause/resume — HANYA tersedia untuk Simulasi & Mini
function toggleGlobalPause() {
    if (!globalAudio || IS_FULL_TES) return;
    if (globalAudio.paused) {
        globalAudio.play();
        document.getElementById('g-pause-ico').className = 'fas fa-pause';
        document.getElementById('g-status-txt').textContent = 'Sedang diputar...';
    } else {
        globalAudio.pause();
        document.getElementById('g-pause-ico').className = 'fas fa-play';
        document.getElementById('g-status-txt').textContent = 'Dijeda';
    }
}

// Speed control — HANYA tersedia untuk Simulasi & Mini
function setGlobalSpeed(val) {
    if (!globalAudio || IS_FULL_TES) return;
    globalAudio.playbackRate = parseFloat(val);
}

// ── BLOKIR SEMUA CARA KELUAR ─────────────────────────────────────
// Blokir tombol back browser
history.pushState(null, '', location.href);
window.addEventListener('popstate', function() {
    history.pushState(null, '', location.href);
});

// Blokir keyboard shortcuts berbahaya
document.addEventListener('keydown', function(e) {
    // Blokir ESC
    if (e.key === 'Escape') { e.preventDefault(); return false; }
    // Blokir F5 (refresh)
    if (e.key === 'F5') { e.preventDefault(); return false; }
    // Blokir Ctrl+R (refresh)
    if (e.ctrlKey && (e.key === 'r' || e.key === 'R')) { e.preventDefault(); return false; }
    // Blokir Ctrl+W (tutup tab)
    if (e.ctrlKey && (e.key === 'w' || e.key === 'W')) { e.preventDefault(); return false; }
    // Blokir Ctrl+T (tab baru)
    if (e.ctrlKey && (e.key === 't' || e.key === 'T')) { e.preventDefault(); return false; }
    // Blokir Alt+F4
    if (e.altKey && e.key === 'F4') { e.preventDefault(); return false; }
    // Blokir F11 (toggle fullscreen manual)
    if (e.key === 'F11') { e.preventDefault(); return false; }
    // Blokir Windows key
    if (e.key === 'Meta') { e.preventDefault(); return false; }
    // Blokir Ctrl+P (print/screenshot)
    if (e.ctrlKey && (e.key === 'p' || e.key === 'P')) { e.preventDefault(); return false; }
    // Blokir F12 (DevTools)
    if (e.key === 'F12') { e.preventDefault(); return false; }
    // Blokir Ctrl+U (view source)
    if (e.ctrlKey && (e.key === 'u' || e.key === 'U')) { e.preventDefault(); return false; }
    // Blokir PrintScreen
    if (e.key === 'PrintScreen') { e.preventDefault(); return false; }
}, true);

// Blokir klik kanan
document.addEventListener('contextmenu', function(e) {
    e.preventDefault(); return false;
}, true);

// Blokir select all
document.addEventListener('selectstart', function(e) {
    e.preventDefault(); return false;
});

// Konfirmasi sebelum tutup/refresh tab (sebagai lapisan tambahan)
window.addEventListener('beforeunload', function(e) {
    e.preventDefault();
    e.returnValue = 'Tes sedang berlangsung! Yakin ingin keluar?';
    return e.returnValue;
});

document.addEventListener('DOMContentLoaded', () => {
    initRestore();
    showSoal(0);
    updateProgress();

    const isFirstStart = {{ $isFirstStart ? 'true' : 'false' }};
    const splash = document.getElementById('fs-splash');

    if (isFirstStart) {
        // PERTAMA KALI: tampilkan splash, tunggu user klik
        if (splash) splash.style.display = 'flex';
    } else {
        // PINDAH SECTION atau RESUME: langsung jalan TANPA splash
        if (splash) splash.style.display = 'none';
        enterFullscreen();
        startTimer();
        setTimeout(function() { _fsReady = true; }, 800);
        setTimeout(function() { _acReady = true; }, 1500);
    }
});
</script>
</body>
</html>