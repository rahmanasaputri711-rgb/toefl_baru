@extends('layouts.user')
@section('title','Tes Mini')
@section('page-title','Tes Mini')
@section('breadcrumb','Home / Tes Mini')

@push('styles')
<style>
.screen{display:none!important}
.screen.active{display:block!important}

/* Timer */
.timer-wrap{display:inline-flex;align-items:center;gap:7px;
  padding:6px 14px;border-radius:8px;font-family:monospace;
  font-size:15px;font-weight:800;
  background:rgba(37,99,235,.08);border:1px solid rgba(37,99,235,.15);color:var(--blue)}
.timer-wrap.warn{background:rgba(245,158,11,.1);border-color:rgba(245,158,11,.3);color:#B45309}
.timer-wrap.danger{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.3);color:#DC2626;
  animation:tpulse .8s ease-in-out infinite}
@keyframes tpulse{0%,100%{opacity:1}50%{opacity:.6}}

/* Progress steps */
.steps{display:flex;align-items:center;gap:0;margin-bottom:24px}
.step{display:flex;align-items:center;gap:8px;flex:1;position:relative}
.step:not(:last-child)::after{content:'';position:absolute;left:calc(50% + 16px);
  right:calc(-50% + 16px);top:15px;height:2px;background:var(--border)}
.step.done::after{background:var(--blue)}
.step-circle{width:30px;height:30px;border-radius:50%;border:2px solid var(--border);
  display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;
  background:var(--white);color:var(--muted);z-index:1;flex-shrink:0}
.step.done .step-circle{background:var(--blue);border-color:var(--blue);color:#fff}
.step.active .step-circle{background:var(--white);border-color:var(--blue);color:var(--blue);
  box-shadow:0 0 0 3px rgba(37,99,235,.15)}
.step-label{font-size:11px;font-weight:600;color:var(--muted);white-space:nowrap}
.step.active .step-label,.step.done .step-label{color:var(--blue)}

/* Intro section card */
.intro-card{background:var(--white);border:1px solid var(--border);border-radius:16px;
  padding:40px 32px;text-align:center;max-width:480px;margin:0 auto}
.intro-icon{width:72px;height:72px;border-radius:20px;display:flex;align-items:center;
  justify-content:center;font-size:32px;margin:0 auto 20px}
.intro-title{font-size:22px;font-weight:800;color:var(--navy);margin-bottom:6px}
.intro-sub{font-size:13px;color:var(--muted);line-height:1.7;margin-bottom:20px}
.intro-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;
  background:var(--blue-lt);border:1px solid var(--blue-pale);
  border-radius:20px;font-size:13px;font-weight:700;color:var(--blue);margin-bottom:24px}
.btn-mulai{width:100%;padding:13px;border-radius:10px;border:none;cursor:pointer;
  background:var(--blue);color:#fff;font-size:15px;font-weight:700;
  font-family:inherit;display:flex;align-items:center;justify-content:center;gap:9px;
  box-shadow:0 4px 14px rgba(37,99,235,.25);transition:all .15s}
.btn-mulai:hover{background:var(--blue-h);transform:translateY(-1px)}

/* Soal area */
.soal-layout{display:grid;grid-template-columns:160px 1fr;gap:16px;min-height:460px}

/* Nav soal */
.soal-nav{background:var(--white);border:1px solid var(--border);border-radius:14px;
  padding:16px;display:flex;flex-direction:column;gap:8px}
.nav-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;
  color:var(--muted);margin-bottom:4px}
.nav-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:6px}
.nav-btn{aspect-ratio:1;border-radius:10px;border:1.5px solid var(--border);
  background:var(--white);font-size:13px;font-weight:700;color:var(--muted);
  cursor:pointer;transition:all .15s;font-family:inherit}
.nav-btn:hover{border-color:var(--blue);color:var(--blue)}
.nav-btn.active{background:var(--blue);border-color:var(--blue);color:#fff;
  box-shadow:0 3px 8px rgba(37,99,235,.25)}
.nav-btn.answered{background:var(--green-lt);border-color:#86EFAC;color:#16A34A}
.nav-btn.active.answered{background:var(--green);border-color:var(--green);color:#fff}

/* Soal card */
.soal-card{background:var(--white);border:1px solid var(--border);border-radius:14px;
  padding:24px;display:flex;flex-direction:column;gap:16px}
.soal-header{display:flex;align-items:center;justify-content:space-between;
  padding-bottom:12px;border-bottom:1px solid var(--border)}
.soal-num{font-size:12px;font-weight:700;color:var(--muted)}
.soal-kat{font-size:11px;padding:3px 10px;border-radius:20px;font-weight:700}
.soal-q{font-size:15px;font-weight:600;color:var(--navy);line-height:1.6}
.passage{background:var(--blue-lt);border:1px solid var(--blue-pale);border-radius:10px;
  padding:14px 16px;font-size:13.5px;line-height:1.8;color:var(--text);
  max-height:180px;overflow-y:auto;margin-bottom:4px}
.passage mark{background:#FEF9C3;padding:0 2px;border-radius:3px}

/* Options */
.opts{display:flex;flex-direction:column;gap:8px}
.opt{display:flex;align-items:center;gap:12px;padding:11px 16px;
  border:1.5px solid var(--border);border-radius:10px;background:var(--white);
  cursor:pointer;transition:all .18s;text-align:left;font-family:inherit;width:100%}
.opt:hover:not(.disabled){border-color:#93C5FD;background:#F0F7FF;transform:translateX(3px)}
.opt.picked{border-color:var(--blue);background:var(--blue-lt)}
.opt.correct{border-color:var(--green);background:var(--green-lt)}
.opt.wrong{border-color:var(--red);background:var(--red-lt)}
.opt.disabled{cursor:default}
.opt-c{width:28px;height:28px;border-radius:50%;border:1.5px solid var(--border);
  display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;
  flex-shrink:0;color:var(--muted);transition:all .15s}
.opt.picked .opt-c{background:var(--blue);border-color:var(--blue);color:#fff}
.opt.correct .opt-c{background:var(--green);border-color:var(--green);color:#fff}
.opt.wrong .opt-c{background:var(--red);border-color:var(--red);color:#fff}
.opt-t{font-size:13.5px;color:var(--text);line-height:1.4}
.struct-blank{display:inline-block;min-width:70px;border-bottom:2px solid var(--blue);
  text-align:center;color:var(--blue);font-weight:800;padding:0 4px}

/* Audio player */
.audio-pl{background:linear-gradient(135deg,var(--blue-lt),var(--blue-pale));
  border:1px solid #BFDBFE;border-radius:12px;padding:12px 14px;
  display:flex;align-items:center;gap:12px}
.ap-btn{width:38px;height:38px;border-radius:50%;background:var(--blue);border:none;
  color:#fff;font-size:13px;cursor:pointer;flex-shrink:0;font-family:inherit;
  display:flex;align-items:center;justify-content:center;transition:all .15s}
.ap-btn:hover{background:var(--blue-h)}
.ap-btn.playing{background:#10B981}
.wv{flex:1;height:32px;background:rgba(255,255,255,.5);border-radius:6px;
  overflow:hidden;cursor:pointer}
.wv-inner{display:flex;align-items:center;gap:1.5px;height:100%;padding:3px 4px}
.wv-bar{flex:1;border-radius:1px;background:rgba(37,99,235,.25);transition:background .08s}
.wv-bar.played{background:var(--blue)}
.ap-time{font-family:monospace;font-size:11.5px;font-weight:700;color:var(--blue);white-space:nowrap}

/* Soal footer nav */
.soal-footer{display:flex;align-items:center;justify-content:space-between;
  padding-top:12px;border-top:1px solid var(--border);margin-top:auto}
.btn-nav{display:inline-flex;align-items:center;gap:7px;padding:9px 20px;
  border-radius:9px;font-size:13.5px;font-weight:600;cursor:pointer;
  transition:all .15s;font-family:inherit}
.btn-prev-nav{border:1.5px solid var(--border);background:var(--white);color:var(--muted)}
.btn-prev-nav:hover{border-color:var(--blue);color:var(--blue)}
.btn-prev-nav:disabled{opacity:.4;cursor:default}
.btn-next-nav{border:none;background:var(--blue);color:#fff;
  box-shadow:0 3px 10px rgba(37,99,235,.2)}
.btn-next-nav:hover{background:var(--blue-h);transform:translateY(-1px)}

/* Hasil */
.hasil-card{background:var(--white);border:1px solid var(--border);
  border-radius:16px;padding:28px;text-align:center}
.score-ring{display:inline-flex;align-items:center;justify-content:center;
  width:110px;height:110px;border-radius:50%;
  background:linear-gradient(135deg,var(--blue),var(--blue-h));
  font-size:28px;font-weight:900;color:#fff;
  box-shadow:0 8px 24px rgba(37,99,235,.3);margin-bottom:20px}
.kat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin:20px 0}
.kat-item{background:var(--bg);border-radius:12px;padding:14px 10px;text-align:center}
.kat-num{font-size:22px;font-weight:800;margin-bottom:2px}
.kat-lbl{font-size:11px;color:var(--muted);font-weight:600}

/* Review */
.review-tabs{display:flex;gap:6px;margin-bottom:16px;flex-wrap:wrap}
.rtab{padding:6px 14px;border-radius:20px;border:1.5px solid var(--border);
  font-size:12px;font-weight:700;color:var(--muted);background:var(--white);
  cursor:pointer;transition:all .15s}
.rtab.active{background:var(--blue);border-color:var(--blue);color:#fff}
.review-item{background:var(--white);border:1px solid var(--border);border-radius:12px;
  margin-bottom:10px;overflow:hidden}
.ri-head{padding:12px 16px;display:flex;align-items:center;gap:12px;
  border-bottom:1px solid var(--border);background:var(--bg)}
.ri-num{width:28px;height:28px;border-radius:7px;display:flex;align-items:center;
  justify-content:center;font-size:12px;font-weight:800;color:#fff;flex-shrink:0}
.ri-q{font-size:13.5px;font-weight:600;color:var(--navy);flex:1;line-height:1.4}
.ri-body{padding:14px 16px;font-size:13px}
.ri-opts{display:flex;flex-direction:column;gap:6px;margin-top:8px}
.ri-opt{display:flex;align-items:center;gap:8px;padding:7px 12px;border-radius:8px;
  border:1px solid var(--border)}
.ri-opt.correct{border-color:#86EFAC;background:var(--green-lt);color:#15803D}
.ri-opt.wrong{border-color:#FCA5A5;background:var(--red-lt);color:#DC2626}
.ri-explanation{margin-top:10px;padding:10px 12px;background:var(--blue-lt);
  border-radius:8px;font-size:12.5px;color:var(--navy);line-height:1.6;
  border-left:3px solid var(--blue)}
</style>
@endpush

@section('content')

{{-- ── GLOBAL TIMER BAR (hidden until test starts) ── --}}
<div id="timer-bar" style="display:none;background:var(--white);border:1px solid var(--border);
    border-radius:12px;padding:12px 16px;margin-bottom:16px;
    display:none;align-items:center;justify-content:space-between;gap:12px">
    <div style="display:flex;align-items:center;gap:10px">
        <div id="step-indicators" style="display:flex;gap:8px">
            <span id="si-1" style="font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;
                background:var(--blue);color:#fff">🎧 Listening</span>
            <span id="si-2" style="font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;
                background:var(--border);color:var(--muted)">📖 Reading</span>
            <span id="si-3" style="font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;
                background:var(--border);color:var(--muted)">✏️ Structure</span>
        </div>
    </div>
    <div style="display:flex;align-items:center;gap:12px">
        <div style="font-size:12px;color:var(--muted)">Soal <span id="prog-cur">1</span>/30</div>
        <div class="timer-wrap" id="timer-display">
            <i class="fas fa-clock"></i><span id="timer-text">25:00</span>
        </div>
    </div>
</div>

{{-- ═══ SCREEN 1: DASHBOARD ═══ --}}
<div class="screen active" id="sc-dashboard">

    {{-- Hero --}}
    <div style="background:linear-gradient(135deg,#1E40AF,#2563EB,#3B82F6);
        border-radius:16px;padding:24px 28px;margin-bottom:18px;
        display:flex;align-items:center;justify-content:space-between;gap:20px;
        box-shadow:0 4px 20px rgba(37,99,235,.2);position:relative;overflow:hidden">
        <div style="position:absolute;right:-20px;top:-30px;width:180px;height:180px;
            border-radius:50%;background:rgba(255,255,255,.05);pointer-events:none"></div>
        <div style="position:relative;z-index:1">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px">
                <div style="width:44px;height:44px;border-radius:12px;
                    background:rgba(255,255,255,.18);display:flex;align-items:center;
                    justify-content:center;font-size:20px;color:#fff">
                    <i class="fas fa-bolt"></i>
                </div>
                <div>
                    <h2 style="font-size:18px;font-weight:800;color:#fff">Tes Mini</h2>
                    <p style="font-size:12px;color:rgba(255,255,255,.7)">Uji kemampuan cepat dalam 3 kategori TOEFL</p>
                </div>
            </div>
            <div style="display:flex;gap:8px;flex-wrap:wrap">
                @foreach([['fas fa-list-ul','30 Soal'],['fas fa-clock','25 Menit'],['fas fa-chart-bar','3 Kategori']] as [$ic,$tx])
                <span style="display:inline-flex;align-items:center;gap:5px;
                    background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);
                    color:rgba(255,255,255,.9);padding:4px 11px;border-radius:20px;
                    font-size:11.5px;font-weight:600">
                    <i class="{{ $ic }}" style="font-size:10px"></i> {{ $tx }}
                </span>
                @endforeach
            </div>
        </div>
        <div style="flex-shrink:0;background:rgba(255,255,255,.12);backdrop-filter:blur(8px);
            border:1px solid rgba(255,255,255,.18);border-radius:14px;
            padding:14px 20px;text-align:center;position:relative;z-index:1">
            <div style="font-size:28px;font-weight:900;color:#fff">30</div>
            <div style="font-size:10.5px;color:rgba(255,255,255,.65);margin-top:3px">Total Soal</div>
            <div style="font-size:10px;color:rgba(255,255,255,.5);margin-top:2px">3 kategori</div>
        </div>
    </div>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px">
        @foreach([
            ['fas fa-headphones-alt','#2563EB','#EFF6FF','#DBEAFE','10','Listening'],
            ['fas fa-book-open','#0891B2','#ECFEFF','#CFFAFE','10','Reading'],
            ['fas fa-pen-nib','#7C3AED','#F3E8FF','#EDE9FE','10','Structure'],
        ] as [$ic,$clr,$bg,$bgd,$n,$lbl])
        <div style="background:var(--white);border:1px solid var(--border);border-radius:12px;
            padding:14px 16px;display:flex;align-items:center;gap:12px;
            box-shadow:0 1px 4px rgba(15,23,42,.04)">
            <div style="width:38px;height:38px;border-radius:10px;background:{{ $bg }};
                border:1px solid {{ $bgd }};color:{{ $clr }};display:flex;align-items:center;
                justify-content:center;font-size:15px;flex-shrink:0">
                <i class="{{ $ic }}"></i>
            </div>
            <div>
                <div style="font-size:18px;font-weight:800;color:var(--navy)">{{ $n }}</div>
                <div style="font-size:11.5px;color:var(--muted)">{{ $lbl }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Ketentuan --}}
    <div style="background:var(--white);border:1px solid var(--border);border-radius:14px;
        margin-bottom:16px;box-shadow:0 2px 12px rgba(15,23,42,.04)">
        <div style="padding:14px 20px;border-bottom:1px solid var(--border);
            display:flex;align-items:center;gap:8px">
            <i class="fas fa-clipboard-list" style="color:var(--blue);font-size:13px"></i>
            <span style="font-size:13.5px;font-weight:700;color:var(--navy)">Ketentuan Tes</span>
        </div>
        <div style="padding:16px 20px;display:flex;flex-direction:column;gap:10px">
            @foreach([
                ['fas fa-check-circle','#22C55E','Tidak perlu mendaftar, langsung mulai'],
                ['fas fa-layer-group','#2563EB','3 section: Listening → Reading → Structure'],
                ['fas fa-clock','#F59E0B','25 menit total — 1 timer global untuk semua section'],
                ['fas fa-forward','#94A3B8','Tiap section dimulai dengan halaman intro'],
                ['fas fa-chart-bar','#2563EB','Hasil + pembahasan lengkap setelah selesai'],
            ] as [$ic,$col,$tx])
            <div style="display:flex;align-items:center;gap:10px;font-size:13.5px;color:var(--text)">
                <i class="{{ $ic }}" style="color:{{ $col }};font-size:13px;width:16px;flex-shrink:0"></i>
                {{ $tx }}
            </div>
            @endforeach
        </div>
    </div>

    <button onclick="showScreen('sc-intro-1')" class="btn-mulai">
        <i class="fas fa-bolt"></i> Mulai Tes Mini Sekarang
    </button>
</div>

{{-- ═══ SCREEN 2: INTRO LISTENING ═══ --}}
<div class="screen" id="sc-intro-1">
    <div id="timer-bar-1" style="display:none"></div>
    <div style="display:flex;justify-content:flex-end;margin-bottom:16px" id="tb-slot-1"></div>
    <div class="intro-card">
        <div class="intro-icon" style="background:#EFF6FF;border:1px solid #DBEAFE">🎧</div>
        <div style="font-size:11px;font-weight:700;color:var(--blue);text-transform:uppercase;
            letter-spacing:1.5px;margin-bottom:8px">Section 1 of 3</div>
        <div class="intro-title">LISTENING</div>
        <div class="intro-sub">
            Pada bagian ini, Anda akan mendengarkan percakapan pendek.
            Dengarkan dengan seksama lalu pilih jawaban terbaik.
        </div>
        <div class="intro-badge"><i class="fas fa-list-ul"></i> Jumlah Soal: 10 Soal</div>
        <div style="font-size:12px;color:var(--muted);margin-bottom:20px">
            <i class="fas fa-clock" style="color:var(--amber)"></i>
            Timer akan terus berjalan selama tes berlangsung.
        </div>
        <button class="btn-mulai" onclick="startSection('listening')">
            <i class="fas fa-headphones"></i> Mulai Listening
        </button>
    </div>
    {{-- Section progress --}}
    <div style="display:flex;justify-content:center;gap:16px;margin-top:20px">
        <div style="display:flex;align-items:center;gap:6px">
            <div style="width:10px;height:10px;border-radius:50%;background:var(--blue)"></div>
            <span style="font-size:12px;font-weight:600;color:var(--blue)">Listening</span>
        </div>
        <div style="display:flex;align-items:center;gap:6px">
            <div style="width:10px;height:10px;border-radius:50%;background:var(--border)"></div>
            <span style="font-size:12px;color:var(--muted)">Reading</span>
        </div>
        <div style="display:flex;align-items:center;gap:6px">
            <div style="width:10px;height:10px;border-radius:50%;background:var(--border)"></div>
            <span style="font-size:12px;color:var(--muted)">Structure</span>
        </div>
    </div>
</div>

{{-- ═══ SCREEN 3: SOAL LISTENING ═══ --}}
<div class="screen" id="sc-soal-listening">
    <div class="soal-layout">
        <div class="soal-nav">
            <div class="nav-label">🎧 Listening</div>
            <div class="nav-grid" id="nav-listening"></div>
        </div>
        <div class="soal-card" id="qcard-listening"></div>
    </div>
</div>

{{-- ═══ SCREEN 4: INTRO READING ═══ --}}
<div class="screen" id="sc-intro-2">
    <div class="intro-card">
        <div class="intro-icon" style="background:#ECFEFF;border:1px solid #CFFAFE">📖</div>
        <div style="font-size:11px;font-weight:700;color:#0891B2;text-transform:uppercase;
            letter-spacing:1.5px;margin-bottom:8px">Section 2 of 3</div>
        <div class="intro-title" style="color:#0E7490">READING</div>
        <div class="intro-sub">
            Pada bagian ini, Anda akan membaca beberapa passage dan menjawab
            pertanyaan berdasarkan teks yang tersedia.
        </div>
        <div class="intro-badge" style="background:#ECFEFF;border-color:#CFFAFE;color:#0891B2">
            <i class="fas fa-list-ul"></i> Jumlah Soal: 10 Soal
        </div>
        <div style="font-size:12px;color:var(--muted);margin-bottom:20px">
            <i class="fas fa-clock" style="color:var(--amber)"></i>
            Timer akan terus berjalan selama tes berlangsung.
        </div>
        <button class="btn-mulai" style="background:#0891B2"
            onmouseover="this.style.background='#0E7490'"
            onmouseout="this.style.background='#0891B2'"
            onclick="startSection('reading')">
            <i class="fas fa-book-open"></i> Mulai Reading
        </button>
    </div>
    <div style="display:flex;justify-content:center;gap:16px;margin-top:20px">
        <div style="display:flex;align-items:center;gap:6px">
            <div style="width:10px;height:10px;border-radius:50%;background:var(--green)"></div>
            <span style="font-size:12px;color:var(--green)">✓ Listening</span>
        </div>
        <div style="display:flex;align-items:center;gap:6px">
            <div style="width:10px;height:10px;border-radius:50%;background:#0891B2"></div>
            <span style="font-size:12px;font-weight:600;color:#0891B2">Reading</span>
        </div>
        <div style="display:flex;align-items:center;gap:6px">
            <div style="width:10px;height:10px;border-radius:50%;background:var(--border)"></div>
            <span style="font-size:12px;color:var(--muted)">Structure</span>
        </div>
    </div>
</div>

{{-- ═══ SCREEN 5: SOAL READING ═══ --}}
<div class="screen" id="sc-soal-reading">
    <div class="soal-layout">
        <div class="soal-nav">
            <div class="nav-label">📖 Reading</div>
            <div class="nav-grid" id="nav-reading"></div>
        </div>
        <div class="soal-card" id="qcard-reading"></div>
    </div>
</div>

{{-- ═══ SCREEN 6: INTRO STRUCTURE ═══ --}}
<div class="screen" id="sc-intro-3">
    <div class="intro-card">
        <div class="intro-icon" style="background:#F3E8FF;border:1px solid #EDE9FE">✏️</div>
        <div style="font-size:11px;font-weight:700;color:#7C3AED;text-transform:uppercase;
            letter-spacing:1.5px;margin-bottom:8px">Section 3 of 3</div>
        <div class="intro-title" style="color:#6D28D9">STRUCTURE</div>
        <div class="intro-sub">
            Pada bagian ini, Anda akan melengkapi kalimat dengan memilih jawaban
            yang tepat secara tata bahasa.
        </div>
        <div class="intro-badge" style="background:#F3E8FF;border-color:#EDE9FE;color:#7C3AED">
            <i class="fas fa-list-ul"></i> Jumlah Soal: 10 Soal
        </div>
        <div style="font-size:12px;color:var(--muted);margin-bottom:20px">
            <i class="fas fa-clock" style="color:var(--amber)"></i>
            Timer akan terus berjalan selama tes berlangsung.
        </div>
        <button class="btn-mulai" style="background:#7C3AED"
            onmouseover="this.style.background='#6D28D9'"
            onmouseout="this.style.background='#7C3AED'"
            onclick="startSection('structure')">
            <i class="fas fa-pen-nib"></i> Mulai Structure
        </button>
    </div>
    <div style="display:flex;justify-content:center;gap:16px;margin-top:20px">
        @foreach([['var(--green)','✓ Listening'],['#0891B2','✓ Reading'],['#7C3AED','Structure']] as [$clr,$lbl])
        <div style="display:flex;align-items:center;gap:6px">
            <div style="width:10px;height:10px;border-radius:50%;background:{{ $clr }}"></div>
            <span style="font-size:12px;font-weight:600;color:{{ $clr }}">{{ $lbl }}</span>
        </div>
        @endforeach
    </div>
</div>

{{-- ═══ SCREEN 7: SOAL STRUCTURE ═══ --}}
<div class="screen" id="sc-soal-structure">
    <div class="soal-layout">
        <div class="soal-nav">
            <div class="nav-label">✏️ Structure</div>
            <div class="nav-grid" id="nav-structure"></div>
        </div>
        <div class="soal-card" id="qcard-structure"></div>
    </div>
</div>

{{-- ═══ SCREEN 8: HASIL ═══ --}}
<div class="screen" id="sc-hasil">
    <div class="hasil-card">
        <div style="font-size:36px;margin-bottom:8px" id="hasil-emoji">🏆</div>
        <div style="font-size:20px;font-weight:800;color:var(--navy);margin-bottom:4px">Tes Selesai!</div>
        <div style="font-size:13px;color:var(--muted);margin-bottom:20px">Berikut hasil pencapaian Anda</div>
        <div class="score-ring" id="hasil-score">-</div>
        <div class="kat-grid">
            <div class="kat-item">
                <div class="kat-num" style="color:#2563EB" id="h-listening">-</div>
                <div class="kat-lbl">🎧 Listening</div>
            </div>
            <div class="kat-item">
                <div class="kat-num" style="color:#0891B2" id="h-reading">-</div>
                <div class="kat-lbl">📖 Reading</div>
            </div>
            <div class="kat-item">
                <div class="kat-num" style="color:#7C3AED" id="h-structure">-</div>
                <div class="kat-lbl">✏️ Structure</div>
            </div>
        </div>
        <div style="background:var(--bg);border-radius:12px;padding:14px 20px;
            display:flex;justify-content:space-between;align-items:center;
            margin-bottom:20px">
            <div>
                <div style="font-size:11px;color:var(--muted);font-weight:600">Total Benar</div>
                <div style="font-size:28px;font-weight:900;color:var(--navy)" id="h-total">-</div>
            </div>
            <div style="text-align:right">
                <div style="font-size:11px;color:var(--muted);font-weight:600">Persentase</div>
                <div style="font-size:28px;font-weight:900;color:var(--blue)" id="h-pct">-</div>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
            <button onclick="showScreen('sc-review')" class="btn-mulai">
                <i class="fas fa-book-open"></i> Lihat Pembahasan
            </button>
            <button onclick="resetMini()"
                style="padding:13px;border-radius:10px;border:1.5px solid var(--border);
                background:var(--white);color:var(--text);font-size:14px;font-weight:600;
                cursor:pointer;font-family:inherit;display:flex;align-items:center;
                justify-content:center;gap:8px;transition:all .15s"
                onmouseover="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'"
                onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text)'">
                <i class="fas fa-redo"></i> Kembali ke Dashboard
            </button>
        </div>
    </div>
</div>

{{-- ═══ SCREEN 9: PEMBAHASAN ═══ --}}
<div class="screen" id="sc-review">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
        <button onclick="showScreen('sc-hasil')"
            style="padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);
            background:var(--white);color:var(--muted);font-size:12.5px;font-weight:600;
            cursor:pointer;font-family:inherit;display:inline-flex;align-items:center;gap:6px">
            <i class="fas fa-arrow-left"></i> Kembali ke Hasil
        </button>
        <div style="font-size:16px;font-weight:800;color:var(--navy)">Pembahasan Seluruh Soal</div>
    </div>
    <div class="review-tabs">
        <div class="rtab active" onclick="filterReview('all',this)">Semua</div>
        <div class="rtab" onclick="filterReview('listening',this)">🎧 Listening (1-10)</div>
        <div class="rtab" onclick="filterReview('reading',this)">📖 Reading (11-20)</div>
        <div class="rtab" onclick="filterReview('structure',this)">✏️ Structure (21-30)</div>
    </div>
    <div id="review-list"></div>
</div>

@endsection

@push('scripts')
<script>
// ══════════════════════════════════════════════════════════
// DATA SOAL — 30 soal TOEFL Mini (berdiri sendiri)
// ══════════════════════════════════════════════════════════
const SOAL = {
  listening: [
    {id:1,q:'What is the main purpose of the conversation?',
     opts:['To book a hotel room','To change a reservation','To cancel a flight','To confirm a payment'],
     ans:1, exp:'Percakapan tersebut membahas pembatalan penerbangan. "Cancel" berarti membatalkan, bukan mengubah atau memesan.'},
    {id:2,q:'What does the man suggest the woman do?',
     opts:['Call the airline directly','Wait for a refund email','Visit the airport counter','Book a new ticket online'],
     ans:0, exp:'Pria menyarankan wanita untuk menelepon maskapai langsung karena masalah tidak bisa diselesaikan secara online.'},
    {id:3,q:'What will the woman most likely do next?',
     opts:['Buy a new ticket','Request a refund','Check her email','Call customer service'],
     ans:3, exp:'Berdasarkan konteks percakapan, wanita memutuskan untuk menghubungi layanan pelanggan maskapai.'},
    {id:4,q:'What problem does the student have?',
     opts:['He lost his textbook','He missed the deadline','He forgot the assignment','He cannot access the library'],
     ans:1, exp:'Mahasiswa menjelaskan bahwa ia melewatkan batas waktu pengumpulan tugas.'},
    {id:5,q:'What does the professor offer to do?',
     opts:['Give an extension','Reduce the grade','Accept late work','Cancel the assignment'],
     ans:0, exp:'Profesor menawarkan perpanjangan waktu (extension) sebagai solusi bagi mahasiswa yang telat.'},
    {id:6,q:'What is the lecture mainly about?',
     opts:['Climate change effects','Ocean exploration methods','Marine biology research','Underwater photography'],
     ans:2, exp:'Kuliah berfokus pada penelitian biologi laut dan temuan-temuan terbaru di bidang tersebut.'},
    {id:7,q:'According to the speaker, what is the most important factor?',
     opts:['Temperature','Salinity','Depth','Light availability'],
     ans:3, exp:'Pembicara menekankan bahwa ketersediaan cahaya adalah faktor terpenting dalam ekosistem laut dangkal.'},
    {id:8,q:'What does the woman imply about the project?',
     opts:['It will be delayed','It needs more funding','It is almost complete','It has a design flaw'],
     ans:2, exp:'Wanita mengisyaratkan bahwa proyek hampir selesai berdasarkan kalimat "we\'re nearly there."'},
    {id:9,q:'What can be inferred about the new policy?',
     opts:['It will be implemented soon','It needs more review','It has been rejected','It is already in effect'],
     ans:0, exp:'Berdasarkan diskusi, kebijakan baru akan segera diterapkan dalam waktu dekat.'},
    {id:10,q:'What does the man ask the woman to do?',
     opts:['Prepare a report','Schedule a meeting','Send an email','Review the documents'],
     ans:3, exp:'Pria meminta wanita untuk meninjau dokumen sebelum pertemuan besok.'},
  ],
  reading: [
    {id:11,
     passage:'The rapid development of technology has brought many benefits to our daily lives. It has made communication easier, improved access to information, and increased productivity in many fields. However, it has also created challenges such as privacy concerns and the digital divide.',
     q:'What is the main idea of the passage?',
     opts:['The benefits of technology','The challenges of technology','The development of technology','Both benefits and challenges of technology'],
     ans:3, exp:'Paragraf membahas KEDUA manfaat DAN tantangan teknologi. Kata "However" menunjukkan kontras, sehingga jawaban D paling tepat.'},
    {id:12,
     passage:'Sleep plays a crucial role in memory consolidation. During sleep, the brain processes and stores information gathered during the day. Studies show that students who sleep 7-8 hours after studying retain information significantly better.',
     q:'According to the passage, what happens during sleep?',
     opts:['The brain shuts down','The brain processes information','Students learn faster','Memory is erased'],
     ans:1, exp:'Teks menyatakan secara eksplisit bahwa otak memproses dan menyimpan informasi selama tidur.'},
    {id:13,
     passage:'Urban green spaces such as parks provide psychological benefits to residents. Research shows that exposure to natural environments reduces stress hormones and lowers blood pressure. Cities with more green spaces have lower rates of depression.',
     q:'The word "psychological" is closest in meaning to:',
     opts:['Physical','Environmental','Mental','Social'],
     ans:2, exp:'"Psychological" berasal dari "psychology" = ilmu tentang pikiran/mental. Sinonim paling tepat adalah "mental."'},
    {id:14,
     passage:'Coral reefs are among the most diverse ecosystems on Earth. They cover less than 1% of the ocean floor but support about 25% of all marine species. Climate change and pollution are major threats to their survival.',
     q:'What percentage of marine species do coral reefs support?',
     opts:['1%','10%','25%','50%'],
     ans:2, exp:'Teks secara eksplisit menyebutkan "about 25% of all marine species." Ini adalah pertanyaan faktual langsung dari bacaan.'},
    {id:15,
     passage:'Renewable energy sources such as solar and wind power are becoming increasingly important. Unlike fossil fuels, they do not produce harmful greenhouse gases. Many countries are now investing heavily in renewable energy infrastructure.',
     q:'What is a key advantage of renewable energy mentioned in the passage?',
     opts:['It is cheaper than fossil fuels','It does not produce greenhouse gases','It is available everywhere','It requires no infrastructure'],
     ans:1, exp:'Teks menyebutkan "they do not produce harmful greenhouse gases" sebagai keunggulan utama energi terbarukan.'},
    {id:16,
     passage:'The Amazon rainforest produces approximately 20% of the world\'s oxygen and is home to millions of species. Deforestation threatens both the biodiversity and the global climate. Conservation efforts are critical to preserving this vital ecosystem.',
     q:'Why is the Amazon rainforest important according to the passage?',
     opts:['It produces all of the world\'s oxygen','It is home to humans only','It produces 20% of oxygen and hosts millions of species','It controls global weather patterns'],
     ans:2, exp:'Teks menyebutkan dua alasan: produksi oksigen (20%) dan keanekaragaman hayati (jutaan spesies).'},
    {id:17,
     passage:'The invention of the printing press in 1440 by Johannes Gutenberg revolutionized the spread of information. Before this invention, books were hand-copied and available only to the wealthy. The printing press made books accessible to ordinary people.',
     q:'What was a major impact of the printing press?',
     opts:['Books became more expensive','Information spread became slower','Books became accessible to ordinary people','Only wealthy people could read'],
     ans:2, exp:'Teks menyatakan mesin cetak membuat buku "accessible to ordinary people" — dapat diakses masyarakat umum.'},
    {id:18,
     passage:'Emotional intelligence refers to the ability to recognize and manage one\'s own emotions while also understanding the emotions of others. Research suggests that people with high emotional intelligence tend to have better interpersonal relationships and professional success.',
     q:'According to the passage, people with high emotional intelligence tend to:',
     opts:['Suppress their emotions','Have better relationships','Avoid difficult situations','Focus only on work'],
     ans:1, exp:'Teks menyatakan mereka cenderung memiliki "better interpersonal relationships" — hubungan antarpersonal yang lebih baik.'},
    {id:19,
     passage:'Artificial intelligence is transforming industries worldwide. From healthcare to finance, AI systems can analyze vast amounts of data and make predictions with remarkable accuracy. However, concerns about job displacement and ethical issues remain.',
     q:'What concern about AI is mentioned in the passage?',
     opts:['High implementation cost','Job displacement','Slow processing speed','Lack of accuracy'],
     ans:1, exp:'Teks menyebutkan "concerns about job displacement" — kekhawatiran tentang pengurangan lapangan pekerjaan.'},
    {id:20,
     passage:'The Mediterranean diet, rich in fruits, vegetables, whole grains, and olive oil, has been associated with numerous health benefits. Studies indicate that it reduces the risk of heart disease, improves cognitive function, and may even extend lifespan.',
     q:'What benefit of the Mediterranean diet is NOT mentioned?',
     opts:['Reduces heart disease risk','Improves cognitive function','Extends lifespan','Increases muscle strength'],
     ans:3, exp:'"Increases muscle strength" tidak disebutkan. Tiga manfaat yang disebutkan adalah: mengurangi risiko penyakit jantung, meningkatkan fungsi kognitif, dan memperpanjang umur.'},
  ],
  structure: [
    {id:21,q:'If I ___ enough money, I will buy a new laptop.',
     opts:['have','has','had','having'],ans:0,
     exp:'"If + present simple, will + base verb" adalah pola conditional type 1. Subjek "I" menggunakan "have."'},
    {id:22,q:'Neither the students nor the teacher ___ prepared for the sudden change.',
     opts:['were','was','are','is'],ans:1,
     exp:'Dalam "neither...nor", kata kerja menyesuaikan subjek terdekat. "Teacher" = singular → "was."'},
    {id:23,q:'The report ___ by the committee before the meeting started.',
     opts:['reviewed','was reviewed','has reviewed','is reviewing'],ans:1,
     exp:'Kalimat ini membutuhkan passive voice past tense. "Was reviewed" = laporan ditinjau (oleh komite).'},
    {id:24,q:'She is one of the most talented musicians who ___ ever performed here.',
     opts:['has','have','had','having'],ans:1,
     exp:'"Who" mengacu pada "musicians" (jamak), sehingga kata kerja harus "have."'},
    {id:25,q:'By the time we arrived, the show ___ already.',
     opts:['started','has started','had started','was starting'],ans:2,
     exp:'"By the time" + past simple → past perfect. "Had started" menunjukkan aksi selesai sebelum aksi lain di masa lalu.'},
    {id:26,q:'The new policy requires that every employee ___ the training.',
     opts:['completes','complete','completed','completing'],ans:1,
     exp:'Setelah "requires that", gunakan subjunctive mood: base form tanpa -s. "Complete" (bukan "completes").'},
    {id:27,q:'___ he studied harder, he might have passed the exam.',
     opts:['If','Unless','Although','Since'],ans:0,
     exp:'"If + past perfect, might have + V3" adalah pola conditional type 3 untuk situasi hipotetis di masa lalu.'},
    {id:28,q:'The students were told to hand in their assignments ___ Friday.',
     opts:['in','on','at','by'],ans:3,
     exp:'"By Friday" berarti paling lambat hari Jumat (deadline). "On Friday" hanya berarti tepat pada hari Jumat.'},
    {id:29,q:'Not only ___ the exam, but she also received a scholarship.',
     opts:['she passed','did she pass','she did pass','passed she'],ans:1,
     exp:'Setelah "not only" di awal kalimat, gunakan inverted subject-verb: "did she pass."'},
    {id:30,q:'The children, ___ were playing in the park, came home at sunset.',
     opts:['which','who','whose','whom'],ans:1,
     exp:'"Who" digunakan untuk orang (the children). "Which" untuk benda, "whose" untuk kepemilikan.'},
  ]
};

// ══════════════════════════════════════════════════════════
// STATE
// ══════════════════════════════════════════════════════════
const TOTAL_SEC = 25 * 60;
let timerInterval = null;
let timeLeft = TOTAL_SEC;
let answers = {listening:{}, reading:{}, structure:{}};
let curIdx = {listening:0, reading:0, structure:0};
let timerStarted = false;
let audioEls = {};

// ══════════════════════════════════════════════════════════
// SCREEN MANAGER
// ══════════════════════════════════════════════════════════
function showScreen(id) {
    document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    window.scrollTo({top:0, behavior:'smooth'});
}

// ══════════════════════════════════════════════════════════
// TIMER
// ══════════════════════════════════════════════════════════
function startTimer() {
    if (timerStarted) return;
    timerStarted = true;
    const bar = document.getElementById('timer-bar');
    bar.style.display = 'flex';

    timerInterval = setInterval(() => {
        timeLeft--;
        updateTimerDisplay();
        if (timeLeft <= 0) { clearInterval(timerInterval); autoSubmit(); }
    }, 1000);
}

function updateTimerDisplay() {
    const m   = Math.floor(timeLeft / 60);
    const s   = timeLeft % 60;
    const txt = String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
    const el  = document.getElementById('timer-text');
    const wrap= document.getElementById('timer-display');
    if (el) el.textContent = txt;
    if (wrap) {
        wrap.className = 'timer-wrap';
        if (timeLeft <= 60)  wrap.classList.add('danger');
        else if (timeLeft <= 300) wrap.classList.add('warn');
    }
}

function autoSubmit() {
    alert('Waktu habis! Tes akan diselesaikan otomatis.');
    finishTest();
}

// ══════════════════════════════════════════════════════════
// SECTION STARTER
// ══════════════════════════════════════════════════════════
function startSection(section) {
    startTimer();

    // Update step indicators
    const map = {listening:1, reading:2, structure:3};
    const idx = map[section];
    document.querySelectorAll('#step-indicators span').forEach((s,i) => {
        s.style.background = i < idx ? 'var(--green)' : i === idx-1 ? 'var(--blue)' : 'var(--border)';
        s.style.color = i <= idx-1 ? '#fff' : 'var(--muted)';
    });

    buildNav(section);
    renderSoal(section, 0);
    showScreen('sc-soal-' + section);
}

// ══════════════════════════════════════════════════════════
// NAVIGATOR
// ══════════════════════════════════════════════════════════
function buildNav(section) {
    const grid = document.getElementById('nav-' + section);
    grid.innerHTML = '';
    SOAL[section].forEach((s, i) => {
        const btn = document.createElement('button');
        btn.className = 'nav-btn' + (i === 0 ? ' active' : '');
        btn.id = 'nb-' + section + '-' + i;
        btn.textContent = s.id;
        btn.onclick = () => goTo(section, i);
        grid.appendChild(btn);
    });
}

function updateNav(section, idx) {
    document.querySelectorAll('[id^="nb-'+section+'-"]').forEach((b,i) => {
        b.className = 'nav-btn';
        if (answers[section][i] !== undefined) b.classList.add('answered');
        if (i === idx) b.classList.add('active');
    });
}

// ══════════════════════════════════════════════════════════
// RENDER SOAL
// ══════════════════════════════════════════════════════════
function renderSoal(section, idx) {
    curIdx[section] = idx;
    const s   = SOAL[section][idx];
    const card = document.getElementById('qcard-' + section);
    const totalGlobal = section === 'listening' ? idx :
                        section === 'reading'   ? 10 + idx : 20 + idx;
    document.getElementById('prog-cur').textContent = totalGlobal + 1;
    updateNav(section, idx);

    // Build HTML
    let html = `
    <div class="soal-header">
        <span class="soal-num">Soal ${s.id} dari 30</span>
        <span class="soal-kat" style="${sectionStyle(section)}">${sectionLabel(section)}</span>
    </div>`;

    // Passage for reading
    if (section === 'reading' && s.passage) {
        html += `<div class="passage">${s.passage}</div>`;
    }

    // Audio for listening (demo waveform)
    if (section === 'listening') {
        html += `
        <div class="audio-pl">
            <button class="ap-btn" id="apb-${idx}" onclick="toggleAudio(${idx})">
                <i class="fas fa-play" id="api-${idx}"></i>
            </button>
            <div class="wv"><div class="wv-inner" id="wv-${idx}"></div></div>
            <span class="ap-time" id="apt-${idx}">0:00</span>
        </div>`;
    }

    // Question
    if (section === 'structure') {
        const parts = s.q.split('___');
        const picked = answers[section][idx];
        const filled = picked !== undefined ? `<span class="struct-blank" style="${picked!==undefined?'border-color:var(--blue);color:var(--blue)':''}">${s.opts[picked]}</span>` : '<span class="struct-blank">____</span>';
        html += `<div class="soal-q">${parts[0]}${filled}${parts[1] || ''}</div>`;
    } else {
        html += `<div class="soal-q">${s.q}</div>`;
    }

    // Options
    html += '<div class="opts">';
    const letters = ['A','B','C','D'];
    const picked = answers[section][idx];
    s.opts.forEach((opt, oi) => {
        let cls = 'opt';
        if (picked !== undefined) {
            cls += ' disabled';
            if (oi === s.ans)  cls += ' correct';
            else if (oi === picked) cls += ' wrong';
        }
        html += `<button class="${cls}" onclick="pickAnswer('${section}',${idx},${oi})">
            <div class="opt-c">${letters[oi]}</div>
            <div class="opt-t">${opt}</div>
        </button>`;
    });
    html += '</div>';

    // Explanation (if answered)
    if (picked !== undefined) {
        const ok = picked === s.ans;
        html += `<div class="ri-explanation">
            <strong>${ok ? '✅ Benar!' : '❌ Salah'}</strong><br>${s.exp}
        </div>`;
    }

    // Footer
    const isFirst = idx === 0;
    const isLast  = idx === SOAL[section].length - 1;
    const isLastSection = section === 'structure';

    html += `<div class="soal-footer">
        <button class="btn-nav btn-prev-nav" onclick="goTo('${section}',${idx-1})"
            ${isFirst?'disabled':''}>
            <i class="fas fa-chevron-left"></i> Sebelumnya
        </button>`;

    if (isLast) {
        if (!isLastSection) {
            const next = {listening:'sc-intro-2', reading:'sc-intro-3'};
            html += `<button class="btn-nav btn-next-nav" onclick="showScreen('${next[section]}')">
                Lanjut ke Section Berikutnya <i class="fas fa-chevron-right"></i>
            </button>`;
        } else {
            html += `<button class="btn-nav btn-next-nav" style="background:var(--green)"
                onclick="finishTest()">
                <i class="fas fa-check"></i> Selesaikan Tes
            </button>`;
        }
    } else {
        html += `<button class="btn-nav btn-next-nav" onclick="goTo('${section}',${idx+1})">
            Selanjutnya <i class="fas fa-chevron-right"></i>
        </button>`;
    }
    html += '</div>';

    card.innerHTML = html;

    // Generate waveform bars for listening
    if (section === 'listening') {
        const wvEl = document.getElementById('wv-'+idx);
        if (wvEl && wvEl.children.length === 0) {
            for (let i=0;i<50;i++) {
                const h = 15 + Math.sin(i*.4+idx)*8 + Math.random()*28;
                const b = document.createElement('div');
                b.className = 'wv-bar'; b.style.height = Math.max(6,h)+'%'; wvEl.appendChild(b);
            }
        }
    }
}

function goTo(section, idx) {
    if (idx < 0 || idx >= SOAL[section].length) return;
    renderSoal(section, idx);
}

function sectionStyle(s) {
    const m = {
        listening: 'background:#EFF6FF;border:1px solid #BFDBFE;color:#1D4ED8',
        reading:   'background:#ECFEFF;border:1px solid #CFFAFE;color:#0891B2',
        structure: 'background:#F3E8FF;border:1px solid #EDE9FE;color:#7C3AED',
    };
    return m[s] || '';
}
function sectionLabel(s) {
    return {listening:'🎧 Listening',reading:'📖 Reading',structure:'✏️ Structure'}[s] || s;
}

// ══════════════════════════════════════════════════════════
// ANSWER
// ══════════════════════════════════════════════════════════
function pickAnswer(section, idx, optIdx) {
    if (answers[section][idx] !== undefined) return; // sudah dijawab
    answers[section][idx] = optIdx;
    renderSoal(section, idx);

    // Auto advance setelah 1.2 detik jika ada soal berikutnya
    const isLast = idx === SOAL[section].length - 1;
    if (!isLast) {
        setTimeout(() => goTo(section, idx + 1), 1200);
    }
}

// ══════════════════════════════════════════════════════════
// AUDIO (visual demo)
// ══════════════════════════════════════════════════════════
function toggleAudio(idx) {
    if (!audioEls[idx]) {
        audioEls[idx] = {running:false,progress:0,interval:null};
    }
    const a   = audioEls[idx];
    const btn = document.getElementById('apb-'+idx);
    const ico = document.getElementById('api-'+idx);
    if (!btn || !ico) return;

    if (a.running) {
        clearInterval(a.interval); a.running = false;
        btn.classList.remove('playing'); ico.className = 'fas fa-play'; return;
    }
    a.running = true;
    btn.classList.add('playing'); ico.className = 'fas fa-pause';
    const dur = 18 + Math.floor(Math.random()*12);

    a.interval = setInterval(() => {
        a.progress += 0.5;
        if (a.progress >= dur) {
            a.progress = dur; clearInterval(a.interval); a.running = false;
            btn.classList.remove('playing'); ico.className = 'fas fa-play';
        }
        const t = document.getElementById('apt-'+idx);
        if (t) {
            const c = Math.floor(a.progress);
            t.textContent = Math.floor(c/60)+':'+String(c%60).padStart(2,'0');
        }
        const bars = document.querySelectorAll('#wv-'+idx+' .wv-bar');
        const played = Math.floor((a.progress/dur)*bars.length);
        bars.forEach((b,i) => b.classList.toggle('played', i<=played));
    }, 500);
}

// ══════════════════════════════════════════════════════════
// FINISH & RESULT
// ══════════════════════════════════════════════════════════
function finishTest() {
    clearInterval(timerInterval);

    let benar = {listening:0, reading:0, structure:0};
    ['listening','reading','structure'].forEach(sec => {
        SOAL[sec].forEach((s,i) => {
            if (answers[sec][i] === s.ans) benar[sec]++;
        });
    });

    const total = benar.listening + benar.reading + benar.structure;
    const pct   = Math.round((total/30)*100);
    const emoji = pct>=80?'🏆':pct>=60?'🎯':'💪';

    document.getElementById('hasil-emoji').textContent  = emoji;
    document.getElementById('h-listening').textContent  = benar.listening+'/10';
    document.getElementById('h-reading').textContent    = benar.reading+'/10';
    document.getElementById('h-structure').textContent  = benar.structure+'/10';
    document.getElementById('h-total').textContent      = total+'/30';
    document.getElementById('h-pct').textContent        = pct+'%';
    document.getElementById('hasil-score').textContent  = pct+'%';

    buildReview();
    showScreen('sc-hasil');
}

// ══════════════════════════════════════════════════════════
// REVIEW / PEMBAHASAN
// ══════════════════════════════════════════════════════════
function buildReview() {
    const container = document.getElementById('review-list');
    const letters   = ['A','B','C','D'];
    let html = '';

    ['listening','reading','structure'].forEach(sec => {
        SOAL[sec].forEach((s,i) => {
            const picked  = answers[sec][i];
            const correct = s.ans;
            const isOk    = picked === correct;
            const color   = isOk ? 'var(--green)' : 'var(--red)';

            html += `<div class="review-item" data-section="${sec}">
                <div class="ri-head">
                    <div class="ri-num" style="background:${color}">${s.id}</div>
                    <div class="ri-q">${s.q.length > 60 ? s.q.slice(0,60)+'...' : s.q}</div>
                    <span style="font-size:12px;font-weight:700;color:${color};flex-shrink:0">
                        ${isOk?'✓ Benar':'✗ Salah'}
                    </span>
                </div>
                <div class="ri-body">
                    ${s.passage ? `<div class="passage" style="max-height:100px;margin-bottom:8px;font-size:12.5px">${s.passage}</div>` : ''}
                    <div class="ri-opts">
                        ${s.opts.map((opt,oi) => {
                            let cls = 'ri-opt';
                            if (oi === correct) cls += ' correct';
                            else if (oi === picked) cls += ' wrong';
                            return `<div class="${cls}">
                                <strong>${letters[oi]}.</strong> ${opt}
                                ${oi===correct?' <span style="margin-left:4px;font-size:11px">← Benar</span>':''}
                                ${oi===picked&&oi!==correct?' <span style="margin-left:4px;font-size:11px">← Jawaban Anda</span>':''}
                            </div>`;
                        }).join('')}
                    </div>
                    <div class="ri-explanation"><strong>Pembahasan:</strong> ${s.exp}</div>
                </div>
            </div>`;
        });
    });

    container.innerHTML = html;
}

function filterReview(section, tab) {
    document.querySelectorAll('.rtab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    document.querySelectorAll('.review-item').forEach(item => {
        item.style.display = (section === 'all' || item.dataset.section === section) ? 'block' : 'none';
    });
}

// ══════════════════════════════════════════════════════════
// RESET
// ══════════════════════════════════════════════════════════
function resetMini() {
    clearInterval(timerInterval);
    timerInterval = null; timerStarted = false;
    timeLeft = TOTAL_SEC;
    answers = {listening:{}, reading:{}, structure:{}};
    curIdx  = {listening:0, reading:0, structure:0};
    audioEls = {};
    document.getElementById('timer-bar').style.display = 'none';
    updateTimerDisplay();
    showScreen('sc-dashboard');
}
</script>
@endpush
