@extends('layouts.user')
@section('title','Tes Simulasi')
@section('page-title','Tes Simulasi')
@section('breadcrumb','Home / Tes Simulasi')

@push('styles')
<style>
/* ─ SCREENS ─────────────────────────────── */
.sc{display:none!important}.sc.on{display:block!important}
/* ─ FULLSCREEN ──────────────────────────── */
#fsMode{position:fixed;inset:0;z-index:9000;background:#fff;overflow:hidden;
  display:none;flex-direction:column}
#fsMode.on{display:flex}
.fs-top{height:52px;border-bottom:1px solid #E2E8F0;padding:0 20px;
  display:flex;align-items:center;justify-content:space-between;flex-shrink:0;
  background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.05);z-index:10}
.fs-body{flex:1;display:grid;grid-template-columns:200px 1fr 260px;overflow:hidden}
.fs-pane{overflow-y:auto;padding:16px}
.fs-pane.border-r{border-right:1px solid #E2E8F0}
.fs-pane.border-l{border-left:1px solid #E2E8F0}
/* ─ DASHBOARD LAYOUT ────────────────────── */
.dsh-wrap{border:1px solid var(--border);border-radius:14px;overflow:hidden;
  display:grid;grid-template-columns:200px 1fr 260px;min-height:520px}
.dsh-pane{padding:16px;overflow-y:auto}
.dsh-pane.br{border-right:1px solid var(--border);background:#FAFBFF}
.dsh-pane.bl{border-left:1px solid var(--border);background:#FAFBFF}
/* ─ TIMER ───────────────────────────────── */
.tmr{display:inline-flex;align-items:center;gap:7px;padding:6px 14px;
  border-radius:8px;font-family:monospace;font-size:15px;font-weight:800;
  background:rgba(37,99,235,.08);border:1px solid rgba(37,99,235,.15);color:var(--blue)}
.tmr.warn{background:rgba(245,158,11,.1);border-color:rgba(245,158,11,.3);color:#B45309}
.tmr.danger{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.3);color:#DC2626;
  animation:tpls .8s infinite}
@keyframes tpls{0%,100%{opacity:1}50%{opacity:.5}}
/* ─ NAV GRID (soal kiri) ────────────────── */
.nav-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:5px}
.nb{width:100%;aspect-ratio:1;border-radius:8px;border:1.5px solid #E2E8F0;
  background:#fff;font-size:12px;font-weight:700;color:#94A3B8;
  cursor:pointer;transition:all .15s;font-family:inherit}
.nb:hover{border-color:var(--blue);color:var(--blue)}
.nb.done{background:#DCFCE7;border-color:#86EFAC;color:#15803D}
.nb.ragu{background:#FEF9C3;border-color:#FDE047;color:#854D0E}
.nb.cur{background:var(--blue);border-color:var(--blue);color:#fff;
  box-shadow:0 3px 8px rgba(37,99,235,.3)}
/* ─ OPTIONS ─────────────────────────────── */
.opts{display:flex;flex-direction:column;gap:8px}
.opt{display:flex;align-items:center;gap:12px;padding:11px 16px;
  border:1.5px solid #E2E8F0;border-radius:10px;background:#fff;
  cursor:pointer;transition:all .18s;text-align:left;font-family:inherit;width:100%}
.opt:hover:not(.dis){border-color:#93C5FD;background:#F0F7FF;transform:translateX(2px)}
.opt.picked{border-color:#2563EB;background:#EFF6FF}.opt.picked .opt-c{background:#2563EB;border-color:#2563EB;color:#fff}
.opt.ok{border-color:#22C55E;background:#F0FDF4}
.opt.bad{border-color:#EF4444;background:#FEF2F2}
.opt.dis{cursor:default}
.opt-c{width:28px;height:28px;border-radius:50%;border:1.5px solid #E2E8F0;
  display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;
  flex-shrink:0;color:#94A3B8;transition:all .15s}
.opt.picked .opt-c{background:var(--blue);border-color:var(--blue);color:#fff}
.opt.ok  .opt-c{background:#22C55E;border-color:#22C55E;color:#fff}
.opt.bad .opt-c{background:#EF4444;border-color:#EF4444;color:#fff}
.opt-t{font-size:13.5px;color:#1E293B;line-height:1.4}
/* ─ AUDIO ───────────────────────────────── */
.audio-bar{background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border:1px solid #BFDBFE;
  border-radius:12px;padding:12px 14px;display:flex;align-items:center;gap:12px;margin-bottom:14px}
.ap-vol{width:80px;accent-color:var(--blue)}
.wv-wrap{flex:1;height:32px;background:rgba(255,255,255,.6);border-radius:6px;
  overflow:hidden;position:relative}
.wv-inner{display:flex;align-items:center;gap:1.5px;height:100%;padding:2px 4px}
.wv-b{flex:1;border-radius:1px;background:rgba(37,99,235,.2)}
.wv-b.pl{background:var(--blue)}
.ap-time{font-family:monospace;font-size:11px;font-weight:700;color:var(--blue);white-space:nowrap}
/* Countdown bar */
.cd-bar-wrap{height:5px;background:#E2E8F0;border-radius:3px;overflow:hidden;margin-bottom:10px}
.cd-bar-fill{height:100%;background:#8B5CF6;border-radius:3px;transition:width .1s linear}
.cd-label{font-size:11.5px;color:#8B5CF6;font-weight:700;margin-bottom:6px;display:flex;align-items:center;gap:6px}
/* ─ PASSAGE ─────────────────────────────── */
.passage{background:#F8FAFF;border:1px solid #DBEAFE;border-radius:10px;
  padding:14px 16px;font-size:13.5px;line-height:1.85;color:#1E293B;overflow-y:auto}
/* ─ FILL INLINE ─────────────────────────── */
.fill-text{font-size:15px;line-height:2.2;color:#0F172A}
.fill-blank{display:inline-block;width:90px;border:none;border-bottom:2.5px solid var(--blue);
  background:transparent;text-align:center;font-size:14px;font-weight:700;color:var(--blue);
  font-family:inherit;outline:none;padding:0 4px;vertical-align:baseline;
  transition:border-color .15s}
.fill-blank:focus{border-color:#8B5CF6}
.fill-blank.ok{border-color:#22C55E;color:#16A34A}
.fill-blank.bad{border-color:#EF4444;color:#DC2626}
.fill-blank:disabled{opacity:1}
/* ─ NOTICE CARD ─────────────────────────── */
.notice-card{background:linear-gradient(145deg,#1E3A8A,#2563EB);
  border-radius:14px;padding:22px;color:#fff;min-height:180px;
  box-shadow:0 4px 20px rgba(37,99,235,.25)}
.notice-card h3{font-size:18px;font-weight:800;margin-bottom:10px}
.notice-card p{font-size:12.5px;line-height:1.8;opacity:.9;white-space:pre-line}
/* ─ SOCIAL POST ─────────────────────────── */
.social-post{border:1.5px solid #E2E8F0;border-radius:16px;overflow:hidden;
  background:#fff;box-shadow:0 2px 12px rgba(0,0,0,.06)}
.sp-head{padding:12px 14px;border-bottom:1px solid #F1F5F9;
  display:flex;align-items:center;gap:10px}
.sp-avatar{width:36px;height:36px;border-radius:50%;
  background:linear-gradient(135deg,#8B5CF6,#6D28D9);
  display:flex;align-items:center;justify-content:center;
  color:#fff;font-size:13px;font-weight:800;flex-shrink:0}
.sp-body{padding:14px;font-size:12.5px;line-height:1.8;color:#334155;
  max-height:220px;overflow-y:auto;white-space:pre-line}
.sp-foot{padding:10px 14px;border-top:1px solid #F1F5F9;display:flex;gap:16px;
  font-size:11.5px;color:#64748B}
/* ─ SPLIT PASSAGE ───────────────────────── */
.split-grid{display:grid;grid-template-columns:1fr 1fr;gap:0;
  border:1px solid #E2E8F0;border-radius:12px;overflow:hidden;
  max-height:440px;margin-bottom:12px}
.split-l{padding:16px;overflow-y:auto;max-height:440px;
  border-right:1px solid #E2E8F0;font-size:13px;line-height:1.9;color:#1E293B}
.split-r{padding:16px;overflow-y:auto;max-height:440px;
  display:flex;flex-direction:column;gap:10px}
/* ─ FOOTER BUTTONS ──────────────────────── */
.soal-footer{display:flex;align-items:center;justify-content:space-between;
  padding-top:14px;border-top:1px solid #E2E8F0;margin-top:auto;gap:8px}
.btn-sblm{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;
  border-radius:9px;border:1.5px solid #E2E8F0;background:#fff;
  font-size:13px;font-weight:600;color:#64748B;cursor:pointer;transition:all .15s;font-family:inherit}
.btn-sblm:hover:not(:disabled){border-color:var(--blue);color:var(--blue)}
.btn-sblm:disabled{opacity:.4;cursor:default}
.btn-ragu{display:inline-flex;align-items:center;gap:7px;padding:9px 14px;
  border-radius:9px;border:1.5px solid #EAB308;background:#FEF9C3;
  font-size:13px;font-weight:600;color:#854D0E;cursor:pointer;
  transition:all .15s;font-family:inherit}
.btn-ragu:hover{background:#FDE68A}
.btn-next{display:inline-flex;align-items:center;gap:7px;padding:9px 20px;
  border-radius:9px;border:none;background:var(--blue);color:#fff;
  font-size:13px;font-weight:700;cursor:pointer;
  box-shadow:0 3px 10px rgba(37,99,235,.2);transition:all .15s;font-family:inherit}
.btn-next:hover{background:var(--blue-h);transform:translateY(-1px)}
/* ─ EXPLANATION ─────────────────────────── */
.exp-box{border-radius:10px;padding:10px 14px;font-size:12.5px;line-height:1.65;
  border-left:3px solid var(--blue);background:#EFF6FF;color:#0F172A;margin-top:8px}
/* ─ HASIL ───────────────────────────────── */
.kat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin:20px 0}
.kat-c{background:#F1F5F9;border-radius:12px;padding:16px;text-align:center}
.kat-n{font-size:24px;font-weight:900;margin-bottom:3px}
.kat-l{font-size:11px;color:#64748B;font-weight:600}
/* ─ REVIEW ──────────────────────────────── */
.rtabs{display:flex;gap:6px;margin-bottom:14px;flex-wrap:wrap}
.rtab{padding:5px 14px;border-radius:20px;border:1.5px solid #E2E8F0;
  font-size:12px;font-weight:700;color:#64748B;background:#fff;cursor:pointer;transition:all .15s}
.rtab.on{background:var(--blue);border-color:var(--blue);color:#fff}
.rev-item{background:#fff;border:1px solid #E2E8F0;border-radius:12px;margin-bottom:8px;overflow:hidden}
.rev-head{padding:10px 14px;display:flex;align-items:center;gap:10px;
  background:#F8FAFF;border-bottom:1px solid #E2E8F0}
.rev-body{padding:12px 14px;font-size:12.5px}
.rev-opt{display:flex;align-items:center;gap:7px;padding:6px 10px;
  border-radius:7px;border:1px solid #E2E8F0;margin-bottom:4px}
.rev-opt.ok{border-color:#86EFAC;background:#F0FDF4;color:#15803D}
.rev-opt.bad{border-color:#FCA5A5;background:#FEF2F2;color:#DC2626}
.prog-track{height:6px;background:#E2E8F0;border-radius:3px;overflow:hidden}
.prog-fill{height:100%;background:var(--blue);border-radius:3px;transition:width .3s}
@keyframes navpulse{0%,100%{opacity:1}50%{opacity:.4}}
.intro-card{background:#fff;border:1px solid var(--border);border-radius:16px;
  padding:40px 32px;text-align:center;max-width:520px;margin:0 auto}
.btn-mulai{width:100%;padding:13px;border-radius:10px;border:none;cursor:pointer;
  color:#fff;font-size:15px;font-weight:700;font-family:inherit;
  display:flex;align-items:center;justify-content:center;gap:9px;
  box-shadow:0 4px 14px rgba(37,99,235,.2);transition:all .15s}
.btn-mulai:hover{transform:translateY(-1px)}
</style>
@endpush

@section('content')
{{-- ══════════════ FULLSCREEN OVERLAY ══════════════ --}}
<div id="fsMode">
  <div class="fs-top">
    <div style="display:flex;align-items:center;gap:12px;min-width:0">
      <div style="font-size:13px;font-weight:800;color:#0F172A">Tes Simulasi TOEFL</div>
      <div id="fs-sec-name" style="font-size:12px;font-weight:600;color:var(--blue)">—</div>
      <div id="fs-soal-num" style="font-size:12px;color:#64748B">—</div>
    </div>
    <div style="display:flex;align-items:center;gap:10px">
      <div class="tmr" id="fs-tmr"><i class="fas fa-clock"></i><span id="fs-tmr-txt">00:00</span></div>
      <div id="fs-sisa-label" style="font-size:11px;color:#64748B">Sisa Waktu</div>
      <button onclick="exitFS()"
        style="padding:6px 14px;border-radius:8px;border:1.5px solid #E2E8F0;background:#fff;
        font-size:12px;font-weight:600;cursor:pointer;font-family:inherit;
        display:flex;align-items:center;gap:6px;color:#64748B">
        <i class="fas fa-compress"></i> Keluar <kbd style="background:#F1F5F9;padding:1px 5px;
        border-radius:3px;font-size:10px">ESC</kbd>
      </button>
    </div>
  </div>
  <div class="fs-body">
    <div class="fs-pane border-r" id="fs-left"></div>
    <div class="fs-pane" id="fs-center" style="padding:20px 28px"></div>
    <div class="fs-pane border-l" id="fs-right"></div>
  </div>
</div>

{{-- ══════════════ SC: INTRO ══════════════ --}}
<div class="sc on" id="sc-intro">
  <div style="background:linear-gradient(135deg,#1E40AF,#2563EB,#3B82F6);
      border-radius:16px;padding:24px 28px;margin-bottom:18px;
      display:flex;align-items:center;justify-content:space-between;
      box-shadow:0 4px 20px rgba(37,99,235,.2);position:relative;overflow:hidden">
    <div style="position:absolute;right:-20px;top:-30px;width:180px;height:180px;
        border-radius:50%;background:rgba(255,255,255,.05);pointer-events:none"></div>
    <div style="position:relative;z-index:1">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px">
        <div style="width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,.18);
            display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff">
          <i class="fas fa-flask"></i></div>
        <div>
          <h2 style="font-size:18px;font-weight:800;color:#fff">Tes Simulasi TOEFL</h2>
          <p style="font-size:12px;color:rgba(255,255,255,.7)">Simulasi menyerupai TOEFL ITP sesungguhnya</p>
        </div>
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap">
        @foreach([['fas fa-list-ul','70 Soal'],['fas fa-clock','±55–58 Menit'],['fas fa-chart-bar','3 Kategori']] as [$ic,$tx])
        <span style="display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,.15);
            border:1px solid rgba(255,255,255,.2);color:rgba(255,255,255,.9);
            padding:4px 11px;border-radius:20px;font-size:11.5px;font-weight:600">
          <i class="{{ $ic }}" style="font-size:10px"></i> {{ $tx }}</span>
        @endforeach
      </div>
    </div>
    <div style="flex-shrink:0;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);
        border-radius:14px;padding:14px 20px;text-align:center;position:relative;z-index:1">
      <div style="font-size:28px;font-weight:900;color:#fff">70</div>
      <div style="font-size:10.5px;color:rgba(255,255,255,.65)">Total Soal</div>
    </div>
  </div>
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px">
    @foreach([
      ['fas fa-headphones-alt','#2563EB','#EFF6FF','#DBEAFE','25 Soal','Listening','±18 Menit'],
      ['fas fa-pen-nib','#7C3AED','#F3E8FF','#EDE9FE','20 Soal','Structure','±13 Menit'],
      ['fas fa-book-open','#0891B2','#ECFEFF','#CFFAFE','25 Soal','Reading','±27 Menit'],
    ] as [$ic,$clr,$bg,$bd,$n,$lbl,$dur])
    <div style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:14px 16px">
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px">
        <div style="width:36px;height:36px;border-radius:9px;background:{{ $bg }};
            border:1px solid {{ $bd }};color:{{ $clr }};display:flex;align-items:center;
            justify-content:center;font-size:14px"><i class="{{ $ic }}"></i></div>
        <div>
          <div style="font-size:14px;font-weight:800;color:#0F172A">{{ $n }}</div>
          <div style="font-size:11.5px;color:#64748B">{{ $lbl }}</div>
        </div>
      </div>
      <div style="font-size:11px;color:{{ $clr }};font-weight:600"><i class="fas fa-clock" style="font-size:10px"></i> {{ $dur }}</div>
    </div>
    @endforeach
  </div>
  <div style="background:#fff;border:1px solid var(--border);border-radius:14px;margin-bottom:16px">
    <div style="padding:14px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px">
      <i class="fas fa-clipboard-list" style="color:var(--blue);font-size:13px"></i>
      <span style="font-size:13.5px;font-weight:700;color:#0F172A">Aturan Simulasi</span>
    </div>
    <div style="padding:16px 20px;display:flex;flex-direction:column;gap:10px">
      @foreach([
        ['fas fa-check-circle','#22C55E','Timer terpisah per section — Listening 18m, Structure 13m, Reading 27m'],
        ['fas fa-lock','#2563EB','Tidak bisa kembali ke section sebelumnya (TOEFL rules)'],
        ['fas fa-expand','#7C3AED','Otomatis fullscreen — bisa keluar kapan saja, tes tetap berjalan'],
        ['fas fa-volume-up','#F59E0B','Listening: 1 audio full 18 menit, sinkron otomatis dengan nomor soal'],
        ['fas fa-book-open','#0891B2','Pembahasan opsional setelah tes selesai'],
      ] as [$ic,$col,$tx])
      <div style="display:flex;align-items:center;gap:10px;font-size:13.5px;color:#1E293B">
        <i class="{{ $ic }}" style="color:{{ $col }};font-size:12px;width:16px;flex-shrink:0"></i>{{ $tx }}
      </div>
      @endforeach
    </div>
  </div>
  <button onclick="mulaiSimulasi()" class="btn-mulai" style="background:var(--blue)">
    <i class="fas fa-flask"></i> Mulai Simulasi
  </button>
</div>

{{-- ══════════════ SC: DASHBOARD (keluar fullscreen) ══════════════ --}}
<div class="sc" id="sc-dash">
  <div style="background:#fff;border:1px solid var(--border);border-radius:12px;
      padding:12px 16px;margin-bottom:14px;display:flex;align-items:center;
      justify-content:space-between;gap:12px">
    <div style="display:flex;align-items:center;gap:10px">
      <div style="font-size:13px;font-weight:800;color:#0F172A">Tes Simulasi TOEFL</div>
      <div id="dsh-sec-name" style="font-size:12px;font-weight:600;color:var(--blue)"></div>
    </div>
    <div style="display:flex;align-items:center;gap:10px">
      <div class="tmr" id="dsh-tmr"><i class="fas fa-clock"></i><span id="dsh-tmr-txt">00:00</span></div>
      <button onclick="enterFS()"
        style="padding:6px 14px;border-radius:8px;background:var(--blue);color:#fff;
        border:none;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit;
        display:flex;align-items:center;gap:6px">
        <i class="fas fa-expand"></i> Full Screen
      </button>
    </div>
  </div>
  <div class="dsh-wrap" id="dsh-grid">
    <div class="dsh-pane br" id="dsh-left"></div>
    <div class="dsh-pane" id="dsh-center" style="padding:20px 28px"></div>
    <div class="dsh-pane bl" id="dsh-right"></div>
  </div>
</div>

{{-- ══════════════ SC: HASIL ══════════════ --}}
<div class="sc" id="sc-hasil">
  <div style="background:#fff;border:1px solid var(--border);border-radius:16px;padding:28px;text-align:center">
    <div style="font-size:40px;margin-bottom:8px" id="hasil-emoji">🏆</div>
    <div style="font-size:22px;font-weight:800;color:#0F172A;margin-bottom:4px">Simulasi Selesai!</div>
    <div style="font-size:13px;color:#64748B;margin-bottom:20px">Berikut hasil pengerjaan Anda.</div>
    <div class="kat-grid">
      <div class="kat-c"><div style="font-size:24px;margin-bottom:4px">🎧</div>
        <div class="kat-n" style="color:#2563EB" id="h-l">-</div>
        <div class="kat-l">Listening Comprehension</div></div>
      <div class="kat-c"><div style="font-size:24px;margin-bottom:4px">✏️</div>
        <div class="kat-n" style="color:#7C3AED" id="h-s">-</div>
        <div class="kat-l">Structure & Written Expr.</div></div>
      <div class="kat-c"><div style="font-size:24px;margin-bottom:4px">📖</div>
        <div class="kat-n" style="color:#0891B2" id="h-r">-</div>
        <div class="kat-l">Reading Comprehension</div></div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:24px">
      <div style="background:#EFF6FF;border:1px solid #DBEAFE;border-radius:12px;padding:16px">
        <div style="font-size:11px;color:#64748B;font-weight:600;margin-bottom:4px">Total Benar</div>
        <div style="font-size:28px;font-weight:900;color:#0F172A" id="h-total">-</div>
      </div>
      <div style="background:#EFF6FF;border:1px solid #BFDBFE;border-radius:12px;padding:16px">
        <div style="font-size:11px;color:#64748B;font-weight:600;margin-bottom:4px">Persentase</div>
        <div style="font-size:28px;font-weight:900;color:var(--blue)" id="h-pct">-</div>
      </div>
    </div>
    <div style="background:#F8FAFF;border-radius:10px;padding:12px 16px;margin-bottom:20px;
        font-size:12.5px;color:#64748B;line-height:1.6">
      <i class="fas fa-info-circle" style="color:var(--blue)"></i>
      Pembahasan bersifat opsional. Klik tombol di bawah untuk melihat seluruh pembahasan soal.
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
      <button onclick="resetSim()" class="btn-mulai"
        style="background:#fff;color:#1E293B;border:1.5px solid #E2E8F0;box-shadow:none">
        <i class="fas fa-home"></i> Kembali ke Dashboard
      </button>
      <button onclick="showPembahasan()" class="btn-mulai" style="background:var(--blue)">
        <i class="fas fa-book-open"></i> Lihat Pembahasan (Opsional)
      </button>
    </div>
  </div>
</div>

{{-- ══════════════ SC: PEMBAHASAN ══════════════ --}}
<div class="sc" id="sc-bhs">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
    <button onclick="showSc('sc-hasil')"
      style="padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);
      background:#fff;color:#64748B;font-size:12.5px;font-weight:600;cursor:pointer;
      font-family:inherit;display:inline-flex;align-items:center;gap:6px">
      <i class="fas fa-arrow-left"></i> Kembali ke Hasil
    </button>
    <div style="font-size:16px;font-weight:800;color:#0F172A">Pembahasan Seluruh Soal</div>
  </div>
  <div class="rtabs">
    <div class="rtab on" onclick="filterRev('all',this)">Semua</div>
    <div class="rtab" onclick="filterRev('listening',this)">🎧 Listening (1–25)</div>
    <div class="rtab" onclick="filterRev('structure',this)">✏️ Structure (26–45)</div>
    <div class="rtab" onclick="filterRev('reading',this)">📖 Reading (46–70)</div>
  </div>
  <div id="rev-list"></div>
</div>

{{-- ══════════════ AUDIO ELEMENT ══════════════ --}}
<audio id="mainAudio" preload="auto" style="display:none">
  <source src="/audio/simulasi/listening_full.mp3" type="audio/mpeg">
</audio>
@endsection

@push('scripts')
<script>
// ════════════════════════════════════════════════════════
// DATA SOAL — 70 soal (berdiri sendiri, tidak dari bank soal)
// ════════════════════════════════════════════════════════
const SOAL = {
listening:[
  {id:1,q:'What is the main purpose of the conversation?',opts:['To book a hotel room','To change a reservation','To cancel a flight','To confirm a payment'],ans:2,exp:'Percakapan membahas pembatalan penerbangan. "Cancel" = membatalkan, jawaban C.'},
  {id:2,q:'What will the man do next?',opts:['Check the schedule','Buy a ticket','Call his friend','Change the plan'],ans:0,exp:'Pria menyebutkan akan memeriksa jadwal terlebih dahulu. Jawaban A.'},
  {id:3,q:'What are the speakers mainly discussing?',opts:['A new restaurant in town','A change in work schedule','A weekend travel plan','A meeting next Monday'],ans:2,exp:'Percakapan berpusat pada rencana perjalanan akhir pekan. Jawaban C.'},
  {id:4,q:'What problem does the woman have?',opts:['She lost her keys','She missed the bus','She forgot her homework','She cannot find her wallet'],ans:1,exp:'Wanita menjelaskan ia terlambat karena melewatkan bus pagi. Jawaban B.'},
  {id:5,q:'What does the professor suggest students do?',opts:['Read more textbooks','Practice speaking daily','Join a study group','Submit assignments early'],ans:1,exp:'Profesor menekankan latihan berbicara setiap hari. Jawaban B.'},
  {id:6,q:'What is the lecture mainly about?',opts:['Effects of climate change','Methods of ocean exploration','History of marine biology','Coral reef ecosystems'],ans:3,exp:'Kuliah berfokus pada ekosistem terumbu karang. Jawaban D.'},
  {id:7,q:'According to the woman, what is the most important thing?',opts:['Saving money','Managing time','Building connections','Staying healthy'],ans:1,exp:'Wanita menekankan manajemen waktu sebagai kunci keberhasilan. Jawaban B.'},
  {id:8,q:'What does the man imply about the exam?',opts:['It will be easy','It was canceled','It covers new material','It is postponed'],ans:2,exp:'Pria menyebutkan ujian mencakup materi baru. Jawaban C.'},
  {id:9,q:'Why is the student visiting the professor?',opts:['To get extra credit','To discuss a grade','To ask about a topic','To submit late work'],ans:1,exp:'Mahasiswa datang untuk mendiskusikan nilai yang menurutnya tidak adil. Jawaban B.'},
  {id:10,q:'What can be inferred about the new policy?',opts:['It benefits students','It is temporary','It has been rejected','It will be announced soon'],ans:3,exp:'Kebijakan baru belum diumumkan dan akan segera dipublikasikan. Jawaban D.'},
  {id:11,q:'What does the woman ask the man to do?',opts:['Fix the computer','Send an email','Call the office','Print the document'],ans:1,exp:'Wanita meminta pria mengirimkan email konfirmasi kepada klien. Jawaban B.'},
  {id:12,q:'What is the main topic of the announcement?',opts:['Library hours change','New cafeteria menu','Campus event schedule','Parking regulations'],ans:0,exp:'Pengumuman membahas perubahan jam operasional perpustakaan. Jawaban A.'},
  {id:13,q:'What does the speaker say about the research?',opts:['It is incomplete','It was published','It needs more funding','It proved the hypothesis'],ans:3,exp:'Pembicara menyebutkan penelitian berhasil membuktikan hipotesis. Jawaban D.'},
  {id:14,q:'What does the man decide to do?',opts:['Take a break','Study all night','Ask for help','Submit the work'],ans:2,exp:'Pria memutuskan untuk meminta bantuan teman yang lebih ahli. Jawaban C.'},
  {id:15,q:'Why does the woman seem surprised?',opts:['The price changed','The store was closed','The order was wrong','The item was sold out'],ans:3,exp:'Wanita terkejut mengetahui barang yang ingin dibelinya sudah habis. Jawaban D.'},
  {id:16,q:'What is the purpose of the meeting?',opts:['To review a project','To hire new staff','To discuss budget','To plan an event'],ans:0,exp:'Rapat diadakan untuk meninjau kemajuan proyek yang sedang berjalan. Jawaban A.'},
  {id:17,q:'What does the professor emphasize?',opts:['Attendance','Reading speed','Critical thinking','Memorization'],ans:2,exp:'Profesor menekankan pentingnya berpikir kritis daripada menghafal. Jawaban C.'},
  {id:18,q:'What will happen to the old building?',opts:['It will be sold','It will be renovated','It will be demolished','It will be preserved'],ans:1,exp:'Gedung lama akan direnovasi menjadi pusat kegiatan mahasiswa. Jawaban B.'},
  {id:19,q:'What does the student want to change?',opts:['Her major','Her advisor','Her schedule','Her dormitory'],ans:2,exp:'Mahasiswa ingin mengubah jadwal kuliah yang bertabrakan. Jawaban C.'},
  {id:20,q:'What is mentioned about the new transportation system?',opts:['It is more expensive','It reduces travel time','It is not yet available','It needs improvement'],ans:1,exp:'Sistem transportasi baru diklaim mengurangi waktu perjalanan. Jawaban B.'},
  {id:21,q:'What does the man think about the proposal?',opts:['It is too risky','It is well-planned','It needs revision','It will fail'],ans:1,exp:'Pria menyatakan proposal sudah direncanakan dengan sangat baik. Jawaban B.'},
  {id:22,q:'What is the woman\'s problem with the assignment?',opts:['She doesn\'t understand it','She lost her notes','She ran out of time','She forgot the topic'],ans:2,exp:'Wanita menjelaskan ia kehabisan waktu untuk menyelesaikan tugas. Jawaban C.'},
  {id:23,q:'What will the university do about the parking issue?',opts:['Build more spaces','Raise parking fees','Close some lots','Start a shuttle service'],ans:3,exp:'Universitas berencana menyediakan layanan shuttle. Jawaban D.'},
  {id:24,q:'What does the speaker mean by "cutting corners"?',opts:['Taking shortcuts','Being careful','Saving money','Working overtime'],ans:0,exp:'"Cutting corners" = mengambil jalan pintas, melakukan sesuatu tidak benar demi efisiensi. Jawaban A.'},
  {id:25,q:'What is the likely outcome of the experiment?',opts:['It will be repeated','It confirms the theory','It disproves the hypothesis','It needs more data'],ans:1,exp:'Hasil eksperimen berhasil mengkonfirmasi teori yang ada. Jawaban B.'},
],
structure:[
  {id:26,q:'If I ___ enough money, I will buy a new laptop.',opts:['have','has','had','having'],ans:0,exp:'"If + present simple → will" = conditional type 1. Subjek "I" → "have."'},
  {id:27,q:'Neither the students nor the teacher ___ prepared.',opts:['were','was','are','is'],ans:1,exp:'"Neither...nor" — kata kerja ikuti subjek terdekat "teacher" (singular) → "was."'},
  {id:28,q:'The report ___ by the committee last week.',opts:['reviewed','was reviewed','has reviewed','is reviewing'],ans:1,exp:'Passive voice past tense: "was reviewed."'},
  {id:29,q:'She is one of the best students who ___ ever studied here.',opts:['has','have','had','having'],ans:1,exp:'"Who" mengacu "students" (jamak) → "have."'},
  {id:30,q:'By the time we arrived, they ___ already left.',opts:['have','has','had','were'],ans:2,exp:'"By the time + past simple" → past perfect: "had."'},
  {id:31,q:'The policy requires that every employee ___ the training.',opts:['completes','complete','completed','completing'],ans:1,exp:'Subjunctive setelah "requires that": base form "complete."'},
  {id:32,q:'Not only ___ the exam, but she also got a scholarship.',opts:['she passed','did she pass','she did pass','passed she'],ans:1,exp:'Inverted subject-verb setelah "not only": "did she pass."'},
  {id:33,q:'The children ___ were playing outside came home at sunset.',opts:['which','who','whose','whom'],ans:1,exp:'"Who" untuk orang (the children).'},
  {id:34,q:'He suggested that she ___ the doctor immediately.',opts:['sees','see','saw','to see'],ans:1,exp:'Subjunctive setelah "suggested that": base form "see."'},
  {id:35,q:'The book ___ on the table belongs to my sister.',opts:['lay','lies','laying','lain'],ans:1,exp:'"Lies" (present simple of "lie" = berada/terletak) untuk benda tidak bergerak.'},
  {id:36,q:'Hardly ___ entered the room when the phone rang.',opts:['he had','had he','he has','has he'],ans:1,exp:'"Hardly" di awal → inversi: "had he" (past perfect inverted).'},
  {id:37,q:'The students handed in their work ___ the deadline.',opts:['in','on','at','by'],ans:3,exp:'"By the deadline" = paling lambat pada deadline.'},
  {id:38,q:'___ the bad weather, we decided to cancel the trip.',opts:['Because','Although','Despite','Since'],ans:2,exp:'"Despite" + noun phrase (tidak butuh subject + verb).'},
  {id:39,q:'The more you practice, ___ you will become.',opts:['better','the better','more better','most better'],ans:1,exp:'"The more...the more/better..." = struktur perbandingan progresif.'},
  {id:40,q:'This is the most interesting book ___ I have ever read.',opts:['which','that','what','whom'],ans:1,exp:'Setelah superlative → gunakan "that" sebagai relative pronoun.'},
  {id:41,q:'She ___ been working here for five years when she got promoted.',opts:['has','had','have','was'],ans:1,exp:'Past perfect continuous: "had been working."'},
  {id:42,q:'The new regulation ___ all employees wear ID badges.',opts:['requires','require','requiring','required'],ans:0,exp:'"The new regulation" (singular) → "requires."'},
  {id:43,q:'It was not until midnight ___ they finished the project.',opts:['when','that','which','then'],ans:1,exp:'Struktur emphatik "It was not until...that..."'},
  {id:44,q:'___ he had studied harder, he would have passed the exam.',opts:['If','Unless','Although','When'],ans:0,exp:'"If + past perfect, would have + V3" = conditional type 3.'},
  {id:45,q:'The committee decided to ___ the meeting until next week.',opts:['post','postpone','post-date','postulate'],ans:1,exp:'"Postpone" = menunda/mengundurkan jadwal.'},
],
reading:[
  // PART 1: Fill Missing Letters (Q46–55) — inline fill
  {id:46,type:'fill',group:'fill',
   paragraph:'We know from drawings that have been preserved in caves for over 10,000 years that early humans performed dances as a group activity. We [mi___] think [th___] prehistoric [peo____] concentrated [on___] on [ba____] survival. [How____], it [i__] clear [fr___] the [rec____] that [dan_____] was important to them.',
   blanks:[
     {key:'mi___',ans:'might'},
     {key:'th___',ans:'those'},
     {key:'peo____',ans:'people'},
     {key:'on___',ans:'only'},
     {key:'ba____',ans:'basic'},
     {key:'How____',ans:'however'},
     {key:'i__',ans:'is'},
     {key:'fr___',ans:'from'},
     {key:'rec____',ans:'records'},
     {key:'dan_____',ans:'dancing'},
   ],
   questions:[
     {q:'Complete: "We [mi___] think"',blankKey:'mi___',ans:'might',exp:'"might" = modal verb expressing possibility. "We might think."'},
     {q:'Complete: "think [th___] prehistoric"',blankKey:'th___',ans:'those',exp:'"those" = demonstrative pronoun referring to early humans.'},
     {q:'Complete: "[peo____] concentrated"',blankKey:'peo____',ans:'people',exp:'"people" = the subject of the clause.'},
     {q:'Complete: "concentrated [on___] on basic"',blankKey:'on___',ans:'only',exp:'"only" = adverb limiting the focus to basic survival.'},
     {q:'Complete: "[ba____] survival"',blankKey:'ba____',ans:'basic',exp:'"basic" = adjective meaning fundamental or essential.'},
     {q:'Complete: "[How____], it is clear"',blankKey:'How____',ans:'however',exp:'"However" = contrast connector introducing an opposing idea.'},
     {q:'Complete: "it [i__] clear"',blankKey:'i__',ans:'is',exp:'"is" = present simple main verb of the clause.'},
     {q:'Complete: "clear [fr___] the records"',blankKey:'fr___',ans:'from',exp:'"from" = preposition showing source of evidence.'},
     {q:'Complete: "the [rec____]"',blankKey:'rec____',ans:'records',exp:'"records" = archaeological or historical evidence.'},
     {q:'Complete: "that [dan_____] was important"',blankKey:'dan_____',ans:'dancing',exp:'"dancing" = gerund as the subject of the clause.'},
   ]
  },
  // PART 2: Notice (Q56–57)
  {id:56,type:'notice',group:'notice',
   notice_title:'Municipal Charter',
   notice_subtitle:'OFFICIAL BANKING NOTICE',
   notice_icon:'🏦',
   notice_color:'#1E3A8A',
   notice_body:'Sign up for paperless billing statements today.\n\nSafe, convenient, easy. Enroll in paperless billing to receive monthly savings account statements in an electronic PDF document.\n\nAccess your Municipal Charter account through the mobile app and select account preferences in the upper right-hand corner to enroll.',
   questions:[
     {q:'What type of business issued the notice?',opts:['An Internet provider','A computer company','A paper company','A bank'],ans:3,exp:'The notice mentions savings account statements and billing — these are banking services. Answer: D.'},
     {q:'How can customers enroll in paperless billing?',opts:['By visiting a Municipal Charter office','By accessing the Municipal Charter website','By using the Municipal Charter app','By calling customer service'],ans:2,exp:'The notice says "Access your Municipal Charter account through the mobile app." Answer: C.'},
   ]
  },
  // PART 3: Social Media Post (Q58–60)
  {id:58,type:'social',group:'social',
   post_author:'Sofia Baker',
   post_handle:'@sofiabaker',
   post_avatar:'SB',
   post_time:'Saturday 8:30 AM',
   post_body:"Every Saturday, our local farmer's market is the place to be! Fresh fruits, veggies, homemade goodies, and unique crafts await you.\n\nThe Thompson family's organic produce is a must-try, known for its quality and cordial service. Their stall is always bustling with customers eager to buy fresh, pesticide-free vegetables.\n\nDon't miss the bakery stall—get there early for the best bread and pastries, including gluten-free and vegan options. These treats sell out fast!\n\nPlus, enjoy live music while you shop. See you there! 🌿🍞🎵",
   questions:[
     {q:"What reason is given for the popularity of the Thompson family's stall?",opts:['They offer cooking tips and recipes.','They offer the lowest prices.','They provide friendly service and excellent products.','They have a beautifully decorated stall.'],ans:2,exp:'The post mentions "quality and cordial service" — cordial = friendly. Answer: C.'},
     {q:'What is the main purpose of the post?',opts:['To explain the benefits of organic farming','To describe the variety of products at the market','To compare different markets','To offer advice on starting a stall'],ans:1,exp:'The post describes foods, crafts, and music — its purpose is to showcase variety. Answer: B.'},
     {q:'Why do customers go to the bakery stall early?',opts:['To get free samples','To get bread and pastries before they run out','To meet the famous baker','To get early morning discounts'],ans:1,exp:'The post says "these treats sell out fast!" Answer: B.'},
   ]
  },
  // PART 4: Long Passage — Mirror Test (Q61–65)
  {id:61,type:'passage',group:'mirror',
   passage:'Very young children cannot recognize themselves in a mirror; they usually achieve this milestone around 18 months of age. The ability to recognize oneself in the mirror is considered to be a key component of self-awareness and consciousness for humans. But what about animals?\n\nFor many years, scientists have known that members of the great ape family could recognize themselves in mirrors. They measured this by the "mirror test," which involved putting a colored mark on an ape\'s body, and then showing the ape its reflection in a mirror. If the ape tried to remove the mark on its own body, the scientists knew that the ape was recognizing its reflection.\n\nApes are close relatives of humans, but in recent years, scientists have discovered that other animals also pass the "mirror test." Elephants and dolphins have shown signs of self-recognition. These, like apes, are highly intelligent animals. But in a more recent experiment, a type of fish called the cleaner fish tried to scrape a mark off its body when it saw itself in the mirror. This suggests that even less intelligent animals may possess more self-awareness than previously suspected.',
   questions:[
     {q:'What is the passage mainly about?',opts:['Stages of early childhood development','Research on animal cognition','Differences between apes and dolphins','Recent experiments on fish'],ans:1,exp:'The passage discusses the mirror test applied to various animals — research on animal cognition. Answer: B.'},
     {q:'The word "milestone" is closest in meaning to:',opts:['accomplishment','distance','weight','discovery'],ans:0,exp:'"Milestone" = a significant achievement or stage of development. Answer: A.'},
     {q:"Why did scientists put colored marks on animals' bodies?",opts:["To track animals' movements","To determine whether animals recognized themselves","To tell the animals apart","To test color detection"],ans:1,exp:'The mark tested self-recognition — if the animal touched where the mark was on its own body. Answer: B.'},
     {q:'According to the passage, all of the following are true about elephants EXCEPT:',opts:['They can recognize themselves in mirrors.','They are highly intelligent.','They share qualities with apes.','They understand signs from other animals.'],ans:3,exp:'The passage does NOT mention elephants understanding signs from other animals. Answer: D.'},
     {q:'Why does the author mention cleaner fish?',opts:['To suggest a wide range of animals may have self-awareness','To imply ocean animals are highly intelligent','To demonstrate a flaw in an experiment','To give an example of an animal that does not recognize itself'],ans:0,exp:'The cleaner fish result suggests self-awareness may be more widespread than thought. Answer: A.'},
   ]
  },
  // PART 5: Long Passage — Frank Lloyd Wright (Q66–70)
  {id:66,type:'passage',group:'wright',
   passage:'A distinctively American architecture began with Frank Lloyd Wright, who had taken to heart the admonition that form should follow function and who thought of buildings not as separate architectural entities but as parts of an organic whole that included the land, the community, and the society. In a very real way the houses of colonial New England and some of the southern plantations had been functional, but Wright was the first architect to make functionalism the authoritative principle for public as well as for domestic building.\n\nAs early as 1906 he built the Unity Temple in Oak Park, Illinois, the first of those churches that did so much to revolutionize ecclesiastical architecture in the United States. Thereafter he turned his genius to such miscellaneous structures as houses, schools, office buildings, and factories, among them the famous Larkin Building in Buffalo, New York, and the Johnson Wax Company building in Racine, Wisconsin.',
   questions:[
     {q:'The phrase "taken to heart" is closest in meaning to:',opts:['Taken seriously','Criticized','Memorized','Taken offense'],ans:0,exp:'"Taken to heart" = to accept or consider something seriously. Answer: A.'},
     {q:"In what way did Wright's public buildings differ from earlier architects'?",opts:['They were built on a larger scale','Their materials came from the south','They looked more like private homes','Their designs were based on how they would be used'],ans:3,exp:'Wright applied functionalism ("form should follow function"). Answer: D.'},
     {q:'The author mentions the Unity Temple because it ...',opts:["Was Wright's first building",'Influenced the architecture of subsequent churches','Demonstrated traditional ecclesiastical architecture','Was the largest church Wright ever designed'],ans:1,exp:'The passage says it "did so much to revolutionize ecclesiastical architecture." Answer: B.'},
     {q:'The passage mentions all of the following structures were built by Wright EXCEPT:',opts:['Factories','Public buildings','Offices','Southern plantations'],ans:3,exp:'Southern plantations are mentioned as earlier examples, NOT Wright\'s works. Answer: D.'},
     {q:"Which statement best reflects one of Wright's architectural principles?",opts:['Beautiful design is more important than utility','Ecclesiastical architecture should be traditional','A building should fit into its surroundings','Public buildings need not be revolutionary'],ans:2,exp:'Wright believed buildings should be part of an "organic whole" connected to land, community, and society. Answer: C.'},
   ]
  },
]
};

// ════════════════════════════════════════════════════════
// LISTENING TIMELINE — virtual timeline for 1 full audio
// Each soal: {start, end, resume} dalam detik
// start = when narrator begins this question
// end   = when narrator finishes reading question
// resume= end + 12 detik jeda menjawab
// ════════════════════════════════════════════════════════
const LT = (()=>{
  const tl = [];
  let t = 30; // 30 detik intro
  for(let i=0;i<25;i++){
    const dur = 18 + Math.floor(Math.random()*8); // durasi baca soal ~18-25 detik
    tl.push({start:t, end:t+dur, resume:t+dur+12});
    t += dur + 12 + 5; // 5 detik transisi
  }
  return tl;
})();
const LISTEN_TOTAL = 18*60; // 18 menit

// ════════════════════════════════════════════════════════
// STATE
// ════════════════════════════════════════════════════════
let curSection = null;
let curIdx     = {listening:0,structure:0,reading:0};
let answers    = {listening:{},structure:{},reading:{}};
let raguSet    = {listening:{},structure:{},reading:{}};
let fillInputs = {}; // {idx: {blankKey: value}}
let isFS       = false;
let secTimer   = 0;
let secTmrInt  = null;
let listenSec  = 0;
let listenInt  = null;
let listenQ    = -1;  // soal listening aktif
let listenPhase= 'intro'; // intro | question | answering | done
let cdRemain   = 0;
let cdInt      = null;
let audioSims  = {};

// Flat reading question list untuk navigasi
// Kita flatten semua soal reading menjadi array datar
const R_FLAT = [];
SOAL.reading.forEach(grp=>{
  if(grp.type==='fill'){
    grp.questions.forEach((q,qi)=> R_FLAT.push({grpIdx:SOAL.reading.indexOf(grp),qIdx:qi,id:46+R_FLAT.length,grp}));
  } else if(grp.type==='notice'){
    grp.questions.forEach((q,qi)=> R_FLAT.push({grpIdx:SOAL.reading.indexOf(grp),qIdx:qi,id:56+qi,grp}));
  } else if(grp.type==='social'){
    grp.questions.forEach((q,qi)=> R_FLAT.push({grpIdx:SOAL.reading.indexOf(grp),qIdx:qi,id:58+qi,grp}));
  } else if(grp.type==='passage'){
    grp.questions.forEach((q,qi)=> R_FLAT.push({grpIdx:SOAL.reading.indexOf(grp),qIdx:qi,id:grp.id+qi,grp}));
  }
});

// ════════════════════════════════════════════════════════
// SCREEN MANAGER
// ════════════════════════════════════════════════════════
function showSc(id){
  document.querySelectorAll('.sc').forEach(s=>s.classList.remove('on'));
  const el=document.getElementById(id);
  if(el) el.classList.add('on');
  window.scrollTo({top:0,behavior:'smooth'});
}

function syncUI(){
  if(!curSection) return;
  if(curSection==='listening'){
    // Listening: hanya update nav (soal bisa pindah bebas oleh user)
    updateListenNav();
    updateAudioStatusBar();
    return;
  }
  if(isFS) renderFS();
  else renderDash();
}

// ════════════════════════════════════════════════════════
// FULLSCREEN
// ════════════════════════════════════════════════════════
function enterFS(){
  isFS=true;
  document.getElementById('fsMode').classList.add('on');
  document.documentElement.requestFullscreen?.().catch(()=>{});
  document.addEventListener('fullscreenchange',onFSChange);
  if(curSection) renderFS();
  else renderFSIntro('listening');
}
function exitFS(){
  isFS=false;
  document.getElementById('fsMode').classList.remove('on');
  document.exitFullscreen?.().catch(()=>{});
  document.removeEventListener('fullscreenchange',onFSChange);
  if(curSection){ showSc('sc-dash'); renderDash(); }
}
function onFSChange(){ if(!document.fullscreenElement && isFS) exitFS(); }
document.addEventListener('keydown',e=>{ if(e.key==='Escape'&&isFS) exitFS(); });

// ════════════════════════════════════════════════════════
// MULAI
// ════════════════════════════════════════════════════════
function mulaiSimulasi(){
  showSc('sc-dash'); // temporarily show dash
  renderFSIntro('listening');
  enterFS();
}

function renderFSIntro(sec){
  const ICONS ={listening:'🎧',structure:'✏️',reading:'📖'};
  const LABELS={listening:'LISTENING COMPREHENSION',structure:'STRUCTURE & WRITTEN EXPRESSION',reading:'READING COMPREHENSION'};
  const CLRS  ={listening:'#2563EB',structure:'#7C3AED',reading:'#0891B2'};
  const SECTS ={listening:'Section 1 of 3',structure:'Section 2 of 3',reading:'Section 3 of 3'};
  const CNT   ={listening:25,structure:20,reading:25};
  const DUR   ={listening:'18 Menit',structure:'13 Menit',reading:'27 Menit'};
  const FN    ={listening:'startListening()',structure:"startSection('structure')",reading:"startSection('reading')"};
  document.getElementById('fs-sec-name').textContent='Intro Section';
  document.getElementById('fs-soal-num').textContent='—';
  document.getElementById('fs-left').innerHTML='';
  document.getElementById('fs-right').innerHTML=`<div style="padding:8px">
    <div style="font-size:11px;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.8px;margin-bottom:10px">Info Section</div>
    <div style="font-size:13px;font-weight:800;color:#0F172A;margin-bottom:6px">${LABELS[sec]}</div>
    <div style="font-size:12px;color:#64748B">${CNT[sec]} Soal | ±${DUR[sec]}</div>
    </div>`;
  document.getElementById('fs-center').innerHTML=`
    <div style="max-width:460px;margin:40px auto;text-align:center">
      <div style="font-size:56px;margin-bottom:16px">${ICONS[sec]}</div>
      <div style="font-size:11px;font-weight:700;color:${CLRS[sec]};text-transform:uppercase;letter-spacing:1.5px;margin-bottom:8px">${SECTS[sec]}</div>
      <div style="font-size:22px;font-weight:800;color:#0F172A;margin-bottom:12px">${LABELS[sec]}</div>
      <div style="font-size:13px;color:#64748B;line-height:1.7;margin-bottom:20px">
        ${sec==='listening'?'Audio 1 track penuh (±18 menit) akan berjalan otomatis. Soal muncul sesuai timeline audio.':
          sec==='structure'?'Pilih jawaban yang paling tepat untuk melengkapi kalimat secara tata bahasa.':
          'Baca setiap teks dan jawab pertanyaan berdasarkan informasi dalam teks.'}
      </div>
      <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;
          padding:12px;margin-bottom:24px;display:flex;justify-content:space-around">
        <div style="text-align:center"><div style="font-size:20px;font-weight:800;color:${CLRS[sec]}">${CNT[sec]}</div><div style="font-size:11px;color:#64748B">Soal</div></div>
        <div style="text-align:center"><div style="font-size:20px;font-weight:800;color:${CLRS[sec]}">±${DUR[sec]}</div><div style="font-size:11px;color:#64748B">Estimasi</div></div>
      </div>
      <button onclick="${FN[sec]}"
        style="width:100%;padding:13px;border-radius:10px;border:none;background:${CLRS[sec]};
        color:#fff;font-size:15px;font-weight:700;font-family:inherit;cursor:pointer;
        display:flex;align-items:center;justify-content:center;gap:9px;
        box-shadow:0 4px 14px rgba(0,0,0,.12)">
        ${FN[sec].includes('listen')?'▶ Mulai Listening':FN[sec].includes('struct')?'Mulai Structure':'Mulai Reading'}
      </button>
    </div>`;
  document.getElementById('fs-tmr-txt').textContent='--:--';
}

// ════════════════════════════════════════════════════════
// TIMER PER SECTION
// ════════════════════════════════════════════════════════
const SEC_TIME={listening:18*60,structure:13*60,reading:27*60};
function startSecTimer(sec){
  clearInterval(secTmrInt);
  secTimer=SEC_TIME[sec];
  updTmr();
  secTmrInt=setInterval(()=>{
    secTimer--;
    updTmr();
    if(secTimer<=0){ clearInterval(secTmrInt); onSecEnd(sec); }
  },1000);
}
function updTmr(){
  const m=Math.floor(secTimer/60),s=secTimer%60;
  const txt=String(m).padStart(2,'0')+':'+String(s).padStart(2,'0');
  ['fs-tmr-txt','dsh-tmr-txt'].forEach(id=>{const e=document.getElementById(id);if(e)e.textContent=txt;});
  ['fs-tmr','dsh-tmr'].forEach(id=>{
    const e=document.getElementById(id);if(!e)return;
    e.className='tmr';
    if(secTimer<=60)e.classList.add('danger');
    else if(secTimer<=180)e.classList.add('warn');
  });
}
function onSecEnd(sec){
  const nextMap={listening:'structure',structure:'reading',reading:'hasil'};
  const next=nextMap[sec];
  if(next==='hasil'){ finishSim(); return; }
  alert(`Waktu ${sec} habis. Lanjut ke section berikutnya.`);
  curSection=null;
  if(isFS) renderFSIntro(next);
  else showSc('sc-dash');
}

// ════════════════════════════════════════════════════════
// LISTENING — 1 audio virtual sinkron
// ════════════════════════════════════════════════════════
function startListening(){
  curSection   = 'listening';
  curIdx.listening = 0;
  listenSec    = 0;
  listenQ      = -1;
  listenPhase  = 'intro';   // Mulai dari intro, bukan soal
  startSecTimer('listening');
  updateListenNav();
  if (isFS) renderFSListening();
  else { showSc('sc-dash'); renderDashListening(); }

  // Start virtual audio timeline
  clearInterval(listenInt);
  listenInt=setInterval(()=>{
    listenSec+=0.5;
    updListenProgress();
    checkListenSync();
  },500);
}

function updListenProgress(){
  const pct=Math.min(100,(listenSec/LISTEN_TOTAL)*100);
  // Update waveform played bars
  ['fs-wv','dsh-wv'].forEach(id=>{
    const bars=document.querySelectorAll(`#${id} .wv-b`);
    const pl=Math.floor(pct/100*bars.length);
    bars.forEach((b,i)=>b.classList.toggle('pl',i<=pl));
  });
  // Update time display
  const m=Math.floor(listenSec/60),s=Math.floor(listenSec%60);
  const txt=`${m}:${String(s).padStart(2,'0')} / 18:00`;
  ['fs-ap-time','dsh-ap-time'].forEach(id=>{const e=document.getElementById(id);if(e)e.textContent=txt;});
  if(listenSec>=LISTEN_TOTAL){ clearInterval(listenInt); listenPhase='done'; }
}

function checkListenSync(){
  // ── ANSWERING: jeda 12 detik setelah audio baca soal ────────────
  for (let i = 0; i < 25; i++) {
    if (listenSec >= LT[i].end && listenSec < LT[i].resume) {
      if (listenPhase !== 'answering' || listenQ !== i) {
        listenQ     = i;
        listenPhase = 'answering';
        startCountdown(LT[i].resume - listenSec, i);
        // Arahkan tampilan ke soal yang sedang dijeda
        curIdx.listening = i;
        const center = document.getElementById(isFS ? 'fs-center' : 'dsh-center');
        if (center) { center.innerHTML = buildListeningCenter(); genWaveformIfNeeded(); }
        updateListenNav();
        updateAudioStatusBar();
      }
      return;
    }
  }
  // ── QUESTION: audio sedang membaca soal ─────────────────────────
  for (let i = 0; i < 25; i++) {
    if (listenSec >= LT[i].start && listenSec < LT[i].end) {
      if (listenQ !== i || listenPhase !== 'question') {
        const wasIntro  = (listenPhase === 'intro');
        listenQ         = i;
        listenPhase     = 'question';
        // Dari intro → tampilkan soal pertama
        if (wasIntro) {
          curIdx.listening = 0;
          const center = document.getElementById(isFS ? 'fs-center' : 'dsh-center');
          if (center) { center.innerHTML = buildListeningCenter(); genWaveformIfNeeded(); }
        } else {
          // User bebas pindah — hanya update status bar + nav
          updateAudioStatusBar();
        }
        updateListenNav();
      }
      return;
    }
  }
  // ── INTRO: sebelum soal pertama ─────────────────────────────────
  if (listenSec < LT[0].start && listenPhase !== 'intro') {
    listenPhase = 'intro';
    const center = document.getElementById(isFS ? 'fs-center' : 'dsh-center');
    if (center) { center.innerHTML = buildListeningCenter(); genWaveformIfNeeded(); }
  }
}


function startCountdown(remaining, qIdx){
  clearInterval(cdInt);
  cdRemain=Math.ceil(remaining);
  updateCdUI();
  cdInt=setInterval(()=>{
    cdRemain--;
    updateCdUI();
    if(cdRemain<=0){ clearInterval(cdInt); listenPhase='question'; }
  },1000);
}
function updateCdUI(){
  const pct=(cdRemain/12)*100;
  ['fs-cd-fill','dsh-cd-fill'].forEach(id=>{const e=document.getElementById(id);if(e)e.style.width=pct+'%';});
  ['fs-cd-num','dsh-cd-num'].forEach(id=>{const e=document.getElementById(id);if(e)e.textContent=cdRemain+'s';});
  ['fs-cd-wrap','dsh-cd-wrap'].forEach(id=>{const e=document.getElementById(id);if(e)e.style.display=cdRemain>0?'block':'none';});
}

// ════════════════════════════════════════════════════════
// BUILD NAV HELPERS
// ════════════════════════════════════════════════════════
// buildListenNav -> lihat updateListenNav di bawah
function buildNavHTML(sec, count){
  const labels = {listening:'🎧 LISTENING', structure:'✏️ STRUCTURE', reading:'📖 READING'};
  let grid = `<div style="font-size:10.5px;font-weight:700;color:#64748B;text-transform:uppercase;
    letter-spacing:.7px;margin-bottom:8px">${labels[sec]} (${count} SOAL)</div>`;
  grid += '<div class="nav-grid">';

  const ids = sec==='listening' ? SOAL.listening.map(s=>s.id) :
              sec==='structure' ? SOAL.structure.map(s=>s.id) :
              R_FLAT.map(r=>r.id);
  const ci  = sec==='reading' ? curIdx.reading : curIdx[sec];

  for (let i = 0; i < count; i++) {
    const isDone = sec==='reading' ? answers.reading[i]!==undefined : answers[sec][i]!==undefined;
    const isRagu = sec==='reading' ? raguSet.reading[i] : raguSet[sec][i];
    const isCur  = i === ci;

    // Listening-only: indicator audio sedang di nomor mana
    const isAudioPlaying  = sec==='listening' && i===listenQ && listenPhase==='question';
    const isAudioAnswering= sec==='listening' && i===listenQ && listenPhase==='answering';

    let cls = 'nb';
    if      (isCur)           cls += ' cur';
    else if (isRagu)          cls += ' ragu';
    else if (isDone)          cls += ' done';

    // Style tambahan untuk audio indicator (outline berdenyut)
    let extraStyle = '';
    if (isAudioPlaying)
      extraStyle = 'outline:2.5px solid #F59E0B;outline-offset:2px;animation:navpulse .7s ease-in-out infinite;';
    else if (isAudioAnswering)
      extraStyle = 'outline:2.5px solid #8B5CF6;outline-offset:2px;';

    const fn = sec==='listening'
      ? `goToListen('listening',${i})`
      : sec==='structure'
        ? `goTo('structure',${i})`
        : `goTo('reading',${i})`;

    grid += `<button class="${cls}" style="${extraStyle}" onclick="${fn}">${ids[i]||i+1}</button>`;
  }
  grid += '</div>';

  // Legend
  grid += `<div style="margin-top:12px;display:flex;flex-direction:column;gap:5px">
    <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:#64748B">
      <div style="width:10px;height:10px;border-radius:50%;background:#22C55E"></div>Sudah dijawab</div>
    <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:#64748B">
      <div style="width:10px;height:10px;border-radius:50%;background:#EAB308"></div>Ragu-ragu</div>
    <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:#64748B">
      <div style="width:10px;height:10px;border-radius:50%;background:#94A3B8"></div>Belum dijawab</div>
    ${sec==='listening' ? `
    <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:#64748B">
      <div style="width:10px;height:10px;border-radius:50%;background:#F59E0B;
        animation:navpulse .7s ease-in-out infinite"></div>🔊 Audio di sini</div>
    <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:#64748B">
      <div style="width:10px;height:10px;border-radius:50%;background:#8B5CF6"></div>⏰ Jeda menjawab</div>
    ` : ''}
  </div>`;
  return grid;
}
function buildRightInfo(sec,idx,total){
  const done=sec==='reading'?Object.keys(answers.reading).length:Object.keys(answers[sec]).length;
  const LABELS={listening:'Listening Comprehension',structure:'Structure & Written Expression',reading:'Reading Comprehension'};
  return `<div style="padding:0 0 8px">
    <div style="font-size:11px;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.7px;margin-bottom:8px">Info Section</div>
    <div style="font-size:13px;font-weight:800;color:#0F172A;margin-bottom:4px">${LABELS[sec]}</div>
    <div style="font-size:11px;color:#64748B;margin-bottom:12px">${total} Soal</div>
    <div style="font-size:11px;font-weight:600;color:#64748B;margin-bottom:4px">Progress</div>
    <div class="prog-track"><div class="prog-fill" style="width:${(done/total*100).toFixed(0)}%"></div></div>
    <div style="font-size:11px;color:#64748B;margin-top:4px">${done} / ${total}</div>
  </div>`;
}

// ════════════════════════════════════════════════════════
// RENDER LISTENING
// ════════════════════════════════════════════════════════


function buildListeningCenter(){
  const displayIdx  = curIdx.listening;
  const audioIdx    = listenQ;
  const isAudioHere = displayIdx === audioIdx;
  const letters     = ['A','B','C','D'];

  // ── Audio bar (selalu tampil) ──────────────────────────────────────
  let html = `
  <div class="audio-bar" style="margin-bottom:12px">
    <div style="display:flex;flex-direction:column;gap:2px;flex-shrink:0">
      <div style="font-size:11px;font-weight:700;color:#2563EB">🎙 Audio Listening</div>
      <div style="font-size:10px;color:#64748B">Volume only · No seek · No skip</div>
    </div>
    <input type="range" min="0" max="1" step="0.1" value="0.8"
      oninput="document.getElementById('mainAudio').volume=this.value"
      style="width:70px;accent-color:#2563EB;flex-shrink:0">
    <div class="wv-wrap"><div class="wv-inner" id="fs-wv"></div></div>
    <span class="ap-time" id="fs-ap-time">0:00 / 18:00</span>
  </div>

  <!-- Countdown bar — muncul hanya saat jeda 12 detik -->
  <div id="fs-cd-wrap" style="margin-bottom:10px;display:none">
    <div class="cd-label" style="margin-bottom:4px">
      <i class="fas fa-pencil-alt"></i> Waktu menjawab:
      <strong id="fs-cd-num" style="color:#8B5CF6">12s</strong>
    </div>
    <div class="cd-bar-wrap"><div class="cd-bar-fill" id="fs-cd-fill" style="width:100%"></div></div>
  </div>`;

  // ════════════════════════════════════════════════════
  // PHASE: INTRO — tampilkan teks intro, BUKAN soal
  // ════════════════════════════════════════════════════
  if (listenPhase === 'intro') {
    html += `
    <div style="text-align:center;padding:32px 20px 20px">
      <div style="font-size:52px;margin-bottom:16px">🎧</div>
      <div style="font-size:11px;font-weight:700;color:#2563EB;text-transform:uppercase;
        letter-spacing:1.5px;margin-bottom:8px">Listening Comprehension</div>
      <div style="font-size:19px;font-weight:800;color:#0F172A;margin-bottom:12px">
        Section 1 of 3 — Listening
      </div>
      <div style="max-width:420px;margin:0 auto;background:#EFF6FF;border:1px solid #BFDBFE;
        border-radius:12px;padding:16px 20px;text-align:left;margin-bottom:20px">
        <div style="font-size:13px;font-weight:700;color:#1D4ED8;margin-bottom:10px">
          <i class="fas fa-info-circle"></i> Petunjuk
        </div>
        <div style="font-size:13px;color:#1E3A8A;line-height:1.8">
          • Audio akan berjalan otomatis selama ±18 menit<br>
          • Dengarkan percakapan dengan seksama<br>
          • Soal akan muncul <strong>otomatis</strong> saat audio mencapai bagian soal<br>
          • Setiap soal diberi jeda <strong>12 detik</strong> untuk menjawab<br>
          • Anda bisa pindah nomor bebas kapan saja<br>
          • Tidak ada rewind, skip, atau pause
        </div>
      </div>
      <div style="display:flex;justify-content:center;gap:16px;flex-wrap:wrap;margin-bottom:16px">
        <div style="text-align:center;background:#fff;border:1px solid #E2E8F0;
          border-radius:10px;padding:12px 20px">
          <div style="font-size:22px;font-weight:900;color:#2563EB">25</div>
          <div style="font-size:11px;color:#64748B">Soal</div>
        </div>
        <div style="text-align:center;background:#fff;border:1px solid #E2E8F0;
          border-radius:10px;padding:12px 20px">
          <div style="font-size:22px;font-weight:900;color:#2563EB">18</div>
          <div style="font-size:11px;color:#64748B">Menit</div>
        </div>
        <div style="text-align:center;background:#fff;border:1px solid #E2E8F0;
          border-radius:10px;padding:12px 20px">
          <div style="font-size:22px;font-weight:900;color:#8B5CF6">12s</div>
          <div style="font-size:11px;color:#64748B">Jeda/soal</div>
        </div>
      </div>
      <div style="background:#FFF7ED;border:1px solid #FED7AA;border-radius:10px;
        padding:12px 16px;display:inline-flex;align-items:center;gap:8px;
        font-size:13px;color:#92400E;font-weight:600">
        <i class="fas fa-volume-up"></i>
        Audio sedang berjalan... Soal muncul otomatis saat waktunya.
      </div>
    </div>`;
    return html;
  }

  // ════════════════════════════════════════════════════
  // PHASE: QUESTION / ANSWERING / DONE — tampilkan soal
  // ════════════════════════════════════════════════════
  const q   = SOAL.listening[displayIdx];
  const ans = answers.listening[displayIdx];

  // Status audio bar di atas soal
  let audioStatus = '';
  if (listenPhase === 'question')
    audioStatus = `<span style="background:#FFF7ED;border:1px solid #FED7AA;color:#EA580C;
      padding:3px 12px;border-radius:20px;font-size:12px;font-weight:700;
      animation:navpulse .7s ease-in-out infinite">
      🔊 Audio membaca Soal ${audioIdx + 1}</span>`;
  else if (listenPhase === 'answering')
    audioStatus = `<span style="background:#F3E8FF;border:1px solid #DDD6FE;color:#7C3AED;
      padding:3px 12px;border-radius:20px;font-size:12px;font-weight:700">
      ⏰ Jeda menjawab Soal ${audioIdx + 1}</span>`;
  else if (listenPhase === 'done')
    audioStatus = `<span style="color:#22C55E;font-weight:700;font-size:12px">
      ✓ Audio selesai — Lanjut ke section berikutnya</span>`;

  html += `
  <!-- Status audio + posisi user -->
  <div style="margin-bottom:12px;display:flex;align-items:center;
    justify-content:space-between;gap:8px;flex-wrap:wrap">
    <div id="fs-audio-status">${audioStatus}</div>
    <span style="font-size:11px;color:#94A3B8">
      ${audioIdx >= 0 && displayIdx !== audioIdx
        ? `Anda di Soal ${displayIdx+1} | Audio di Soal ${audioIdx+1}`
        : `Soal ${displayIdx+1} dari 25`}
    </span>
  </div>

  <!-- Header soal -->
  <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px">
    <span style="font-size:13px;font-weight:800;color:#0F172A">Soal ${q.id}</span>
    ${isAudioHere && listenPhase === 'question'
      ? `<span style="background:#FFF7ED;border:1px solid #FED7AA;color:#EA580C;
          padding:1px 8px;border-radius:12px;font-size:10.5px;font-weight:700">
          🔊 Sedang dibacakan</span>` : ''}
    ${isAudioHere && listenPhase === 'answering'
      ? `<span style="background:#F3E8FF;border:1px solid #DDD6FE;color:#7C3AED;
          padding:1px 8px;border-radius:12px;font-size:10.5px;font-weight:700">
          ⏰ Waktu menjawab</span>` : ''}
  </div>

  <!-- Pertanyaan -->
  <div class="soal-q">${q.q}</div>

  <!-- Pilihan -->
  <div class="opts" style="margin-top:12px">`;

  q.opts.forEach((opt, oi) => {
    let cls = 'opt';
    if (ans !== undefined) {
      cls += ' dis';
      if (oi === ans) cls += ' picked';
    }
    html += `<button class="${cls}" onclick="pickListenAns(${displayIdx},${oi})">
      <div class="opt-c">${letters[oi]}</div>
      <div class="opt-t">${opt}</div>
    </button>`;
  });

  html += '</div>';

  if (ans !== undefined) {
    html += `<div style="font-size:12px;color:#64748B;margin-top:10px;padding:8px 12px;
      background:#F8FAFF;border-radius:8px;border:1px solid #E2E8F0">
      ✓ Jawaban tersimpan. Benar/salah tampil di pembahasan akhir.
    </div>`;
  }

  // Footer — prev/next bebas pindah soal
  const isFirst = displayIdx === 0;
  const isLast  = displayIdx === 24;
  html += `<div class="soal-footer">
    <button class="btn-sblm" ${isFirst ? 'disabled' : ''}
      onclick="goToListen('listening', ${displayIdx - 1})">
      <i class="fas fa-chevron-left"></i> Sebelumnya
    </button>
    <button class="btn-ragu" onclick="toggleRagu('listening', ${displayIdx})">
      <i class="fas fa-question"></i>
      ${raguSet.listening[displayIdx] ? 'Batal Ragu' : 'Tandai Ragu'}
    </button>
    ${isLast
      ? `<button class="btn-next" style="background:#22C55E"
          onclick="goNextSection('structure')">
          Selesai Listening <i class="fas fa-chevron-right"></i>
        </button>`
      : `<button class="btn-next"
          onclick="goToListen('listening', ${displayIdx + 1})">
          Selanjutnya <i class="fas fa-chevron-right"></i>
        </button>`}
  </div>`;

  return html;
}


function pickListenAns(qIdx, optIdx) {
  if (answers.listening[qIdx] !== undefined) return; // sudah dijawab
  answers.listening[qIdx] = optIdx;
  delete raguSet.listening[qIdx];
  updateListenNav();
  // Rebuild soal yang ditampilkan
  const center = document.getElementById(isFS ? 'fs-center' : 'dsh-center');
  if (center) { center.innerHTML = buildListeningCenter(); genWaveformIfNeeded(); }
}

// ════════════════════════════════════════════════════════
// START SECTION (structure / reading)
// ════════════════════════════════════════════════════════
function startSection(sec){
  curSection=sec;
  curIdx[sec]=0;
  startSecTimer(sec);
  if(sec==='structure'){
    const html=buildNavHTML('structure',20);
    ['fs-left','dsh-left'].forEach(id=>{const e=document.getElementById(id);if(e)e.innerHTML=html;});
    document.getElementById('fs-sec-name').textContent='Structure & Written Expression';
    document.getElementById('dsh-sec-name').textContent='Structure & Written Expression';
    if(isFS){renderFS();}else{showSc('sc-dash');renderDash();}
  } else {
    const html=buildNavHTML('reading',25);
    ['fs-left','dsh-left'].forEach(id=>{const e=document.getElementById(id);if(e)e.innerHTML=html;});
    document.getElementById('fs-sec-name').textContent='Reading Comprehension';
    document.getElementById('dsh-sec-name').textContent='Reading Comprehension';
    if(isFS){renderFS();}else{showSc('sc-dash');renderDash();}
  }
}

// ════════════════════════════════════════════════════════
// RENDER FS / DASH
// ════════════════════════════════════════════════════════
function renderFS(){
  if(!curSection) return;
  if(curSection==='listening'){ renderFSListening(); return; }
  const idx=curIdx[curSection];
  const total=curSection==='structure'?20:25;
  document.getElementById('fs-soal-num').textContent=`Soal ${idx+1} dari ${total}`;
  document.getElementById('fs-right').innerHTML=buildRightInfo(curSection,idx,total);
  document.getElementById('fs-center').innerHTML=buildSoalHTML(curSection,idx);
  refreshNavHL(curSection,idx);
}
function renderDash(){
  if(!curSection) return;
  if(curSection==='listening'){ renderDashListening(); return; }
  const idx=curIdx[curSection];
  const total=curSection==='structure'?20:25;
  const html=buildNavHTML(curSection,total);
  ['fs-left','dsh-left'].forEach(id=>{const e=document.getElementById(id);if(e)e.innerHTML=html;});
  document.getElementById('dsh-right').innerHTML=buildRightInfo(curSection,idx,total);
  document.getElementById('dsh-center').innerHTML=buildSoalHTML(curSection,idx);
}
function refreshNavHL(sec,idx){
  const html=buildNavHTML(sec,sec==='structure'?20:25);
  document.getElementById('fs-left').innerHTML=html;
}

// ════════════════════════════════════════════════════════
// BUILD SOAL HTML
// ════════════════════════════════════════════════════════
function buildSoalHTML(sec, idx){
  if(sec==='structure') return buildStructureHTML(idx);
  if(sec==='reading')   return buildReadingHTML(idx);
  return '';
}

function buildStructureHTML(idx){
  const s=SOAL.structure[idx];
  const ans=answers.structure[idx];
  const letters=['A','B','C','D'];
  let html=`<div style="font-size:11px;color:#64748B;margin-bottom:12px;display:flex;align-items:center;gap:6px">
    <span style="background:#F3E8FF;border:1px solid #EDE9FE;color:#7C3AED;padding:2px 8px;
      border-radius:20px;font-size:11px;font-weight:700">Soal ${idx+1} dari 20</span>
  </div>
  <div style="background:#F8FAFF;border:1px solid #DBEAFE;border-radius:12px;padding:16px 18px;
    font-size:16px;font-weight:600;color:#0F172A;line-height:1.8;margin-bottom:16px">`;
  // Render kalimat dengan blank
  const q=s.q;
  const blankIdx=q.indexOf('___');
  if(blankIdx>=0){
    const picked=ans!==undefined?s.opts[ans]:null;
    const isOk=ans===s.ans;
    const blankStyle=ans===undefined?'color:var(--blue);border-color:var(--blue)':
                     isOk?'color:#16A34A;border-color:#22C55E':'color:#DC2626;border-color:#EF4444';
    const blankText=picked||'_____';
    html+=q.replace('___',`<span style="display:inline-block;min-width:70px;border-bottom:2.5px solid;
      text-align:center;padding:0 6px;${blankStyle};font-weight:800;font-family:inherit">${blankText}</span>`);
  } else { html+=q; }
  html+=`</div><div class="opts">`;
  s.opts.forEach((opt,oi)=>{
    let cls='opt';
    if(ans!==undefined){cls+=' dis';if(oi===ans)cls+=' picked';}
    html+=`<button class="${cls}" onclick="pickAns('structure',${idx},${oi})">
      <div class="opt-c">${letters[oi]}</div><div class="opt-t">${opt}</div>
    </button>`;
  });
  html+='</div>';
  if(ans!==undefined) html+=`<div style="font-size:12px;color:#64748B;margin-top:10px;
  padding:8px 12px;background:#F8FAFF;border-radius:8px;border:1px solid #E2E8F0">
  ✓ Jawaban tersimpan. Pembahasan tampil di akhir tes.</div>`;
  html+=buildFooter('structure',idx,20);
  return html;
}

function buildReadingHTML(flatIdx){
  const item = R_FLAT[flatIdx];
  if (!item) return '';
  const grp  = item.grp;
  const qIdx = item.qIdx;
  const letters = ['A','B','C','D'];
  const partLabels = {
    fill:'Part 1 — Fill Missing Letters',
    notice:'Part 2 — Read a Notice',
    social:'Part 3 — Social Media Post',
    passage: grp.group==='mirror' ? 'Part 4 — Long Passage' : 'Part 5 — Long Passage'
  };

  let html = `<div style="font-size:11px;color:#64748B;margin-bottom:12px;
    display:flex;align-items:center;gap:6px">
    <span style="background:#ECFEFF;border:1px solid #CFFAFE;color:#0891B2;
      padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700">
      Soal ${item.id} dari 70
    </span>
    <span style="font-size:11px;color:#94A3B8">${partLabels[grp.type]||''}</span>
  </div>`;

  // ══ FILL MISSING LETTERS ════════════════════════════════════════════
  if (grp.type === 'fill') {
    // Selalu tampilkan paragraf penuh dengan semua blank sebagai input inline
    // Setiap blank: input hanya untuk huruf yang KOSONG (bukan seluruh kata)

    // Render paragraf: ganti [key] jadi input inline
    let para = grp.paragraph;
    grp.blanks.forEach((bl, bi) => {
      const globalFlat = flatIdx - qIdx + bi; // flat index soal ini dalam reading
      const savedAns   = answers.reading[globalFlat];
      const isActive   = bi === qIdx; // blank yang sedang dikerjakan

      // Hitung berapa huruf yang perlu diisi
      // key misal: mi___ → prefix=mi, blanks=3 → user isi 3 huruf
      const underscores = (bl.key.match(/_/g)||[]).length;
      const prefix = bl.key.replace(/_/g,'');          // huruf yang sudah ada
      const correctSuffix = bl.ans.slice(prefix.length); // huruf yang perlu diisi

      // Warna border: active=biru, answered=hijau/merah, inactive=abu
      let borderColor = isActive ? '#2563EB' : '#94A3B8';
      let bgColor     = isActive ? '#EFF6FF' : 'transparent';
      let inputVal    = '';
      let isDisabled  = false;

      if (savedAns !== undefined) {
        inputVal  = savedAns;
        isDisabled = true;
        // Tidak tampilkan benar/salah saat tes — hanya abu/filled
        borderColor = '#22C55E'; // hijau = sudah diisi (benar/salah nanti di akhir)
        bgColor     = '#F0FDF4';
      }

      // Buat input dengan lebar sesuai jumlah huruf yang diisi
      const inputWidth = Math.max(40, underscores * 14 + 12);
      const inputEl = `<span style="display:inline-flex;align-items:baseline;
        font-weight:700;color:#0F172A">
        <span style="color:${isActive?'#2563EB':'#374151'}">${prefix}</span><input
          id="bl-${globalFlat}"
          type="text"
          maxlength="${underscores}"
          value="${inputVal}"
          ${isDisabled ? 'disabled' : ''}
          placeholder="${'_'.repeat(underscores)}"
          style="width:${inputWidth}px;border:none;border-bottom:2.5px solid ${borderColor};
            background:${bgColor};text-align:center;font-size:15px;font-weight:800;
            color:${isActive?'#2563EB':'#374151'};font-family:inherit;outline:none;
            padding:0 2px;vertical-align:baseline;display:inline-block;
            ${isActive?'box-shadow:0 1px 0 #2563EB;':''}"
          oninput="onFillInput(event,${globalFlat},'${bl.ans}',${underscores})"
          onkeydown="onFillKey(event,${globalFlat},'${bl.ans}',${bi},${grp.blanks.length},'${flatIdx-qIdx}')">
      </span>`;
      para = para.replace(`[${bl.key}]`, inputEl);
    });

    html += `<div style="font-size:15px;line-height:2.4;color:#0F172A;
        padding:16px;background:#FAFBFF;border:1px solid #DBEAFE;
        border-radius:12px;margin-bottom:12px">
      ${para}
    </div>`;

    // Highlight soal aktif
    const bl = grp.blanks[qIdx];
    html += `<div style="background:#EFF6FF;border:1px solid #BFDBFE;border-radius:8px;
      padding:10px 14px;margin-bottom:8px;font-size:13px;color:#1D4ED8;
      display:flex;align-items:center;gap:8px">
      <i class="fas fa-edit"></i>
      <span>Isi huruf yang kosong pada kata <strong>${bl.key.replace(/_/g,'_')}</strong>
        — ketik <strong>${(bl.ans.match(/_/g)||[]).length || bl.ans.slice(bl.key.replace(/_/g,'').length).length}</strong>
        huruf, tekan <kbd style="background:#1D4ED8;color:#fff;padding:1px 5px;border-radius:3px;font-size:11px">Enter</kbd>
      </span>
    </div>`;

    html += `<div style="font-size:12px;color:#64748B;margin-bottom:12px">
      <i class="fas fa-info-circle"></i>
      Jawaban akan disimpan. Benar/salah tampil setelah semua section selesai.
    </div>`;

    html += buildFooter('reading', flatIdx, 25);
    return html;
  }

  // ══ NOTICE ══════════════════════════════════════════════════════════
  if (grp.type === 'notice') {
    const qi = grp.questions[qIdx];
    const ans = answers.reading[flatIdx];
    html += `<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:12px">
      <div class="notice-card">
        <div style="font-size:10px;opacity:.7;font-weight:600;margin-bottom:6px;letter-spacing:1px">
          ${grp.notice_subtitle||'OFFICIAL NOTICE'}</div>
        <div style="font-size:22px;margin-bottom:6px">${grp.notice_icon||'📋'}</div>
        <h3 style="font-size:17px;font-weight:800;margin-bottom:10px">${grp.notice_title}</h3>
        <p style="font-size:12.5px;line-height:1.8;opacity:.9;white-space:pre-line">${grp.notice_body}</p>
      </div>
      <div style="display:flex;flex-direction:column;gap:12px">
        <div class="soal-q">${qi.q}</div>
        <div class="opts">
          ${qi.opts.map((opt,oi) => {
            let cls = 'opt';
            if (ans !== undefined){ cls += ' dis'; if(oi===ans) cls += ' picked'; }
            return `<button class="${cls}" onclick="pickAns('reading',${flatIdx},${oi})">
              <div class="opt-c">${letters[oi]}</div><div class="opt-t">${opt}</div>
            </button>`;
          }).join('')}
        </div>
        ${ans!==undefined ? `<div style="font-size:12px;color:#64748B;padding:8px 12px;
          background:#F8FAFF;border-radius:8px;border:1px solid #E2E8F0">
          ✓ Jawaban tersimpan. Pembahasan tampil di akhir tes.
        </div>`:''}
      </div>
    </div>`;
    html += buildFooter('reading', flatIdx, 25);
    return html;
  }

  // ══ SOCIAL ══════════════════════════════════════════════════════════
  if (grp.type === 'social') {
    const qi  = grp.questions[qIdx];
    const ans = answers.reading[flatIdx];
    const pb  = grp.post_body;
    html += `<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:12px">
      <div class="social-post">
        <div class="sp-head">
          <div class="sp-avatar">${grp.post_avatar}</div>
          <div>
            <div style="font-size:13px;font-weight:700;color:#0F172A">${grp.post_author}</div>
            <div style="font-size:11px;color:#94A3B8">${grp.post_handle||''} · ${grp.post_time||''}</div>
          </div>
          <div style="margin-left:auto;background:#1DA1F2;color:#fff;font-size:10px;
            font-weight:700;padding:3px 8px;border-radius:12px">Follow</div>
        </div>
        <div class="sp-body">${pb}</div>
        <div class="sp-foot"><span>❤️ 128</span><span>💬 24</span><span>🔁 Share</span></div>
      </div>
      <div style="display:flex;flex-direction:column;gap:12px">
        <div class="soal-q">${qi.q}</div>
        <div class="opts">
          ${qi.opts.map((opt,oi) => {
            let cls = 'opt';
            if (ans !== undefined){ cls += ' dis'; if(oi===ans) cls += ' picked'; }
            return `<button class="${cls}" onclick="pickAns('reading',${flatIdx},${oi})">
              <div class="opt-c">${letters[oi]}</div><div class="opt-t">${opt}</div>
            </button>`;
          }).join('')}
        </div>
        ${ans!==undefined ? `<div style="font-size:12px;color:#64748B;padding:8px 12px;
          background:#F8FAFF;border-radius:8px;border:1px solid #E2E8F0">
          ✓ Jawaban tersimpan. Pembahasan tampil di akhir tes.
        </div>`:''}
      </div>
    </div>`;
    html += buildFooter('reading', flatIdx, 25);
    return html;
  }

  // ══ PASSAGE (split screen) ══════════════════════════════════════════
  if (grp.type === 'passage') {
    const qi  = grp.questions[qIdx];
    const ans = answers.reading[flatIdx];
    html += `<div class="split-grid">
      <div class="split-l">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;
          letter-spacing:1px;color:#94A3B8;margin-bottom:10px">Passage</div>
        ${grp.passage.split('\n\n').map(p=>`<p style="margin-bottom:12px">${p}</p>`).join('')}
      </div>
      <div class="split-r">
        <div class="soal-q">${qi.q}</div>
        <div class="opts">
          ${qi.opts.map((opt,oi) => {
            let cls = 'opt';
            if (ans !== undefined){ cls += ' dis'; if(oi===ans) cls += ' picked'; }
            return `<button class="${cls}" onclick="pickAns('reading',${flatIdx},${oi})">
              <div class="opt-c">${letters[oi]}</div><div class="opt-t">${opt}</div>
            </button>`;
          }).join('')}
        </div>
        ${ans!==undefined ? `<div style="font-size:12px;color:#64748B;padding:8px 10px;
          background:#F8FAFF;border-radius:8px;border:1px solid #E2E8F0;margin-top:4px">
          ✓ Tersimpan. Pembahasan di akhir tes.
        </div>`:''}
      </div>
    </div>`;
    html += buildFooter('reading', flatIdx, 25);
    return html;
  }
  return html;
}

function buildFooter(sec, idx, total){
  const isFirst=idx===0;
  const isLast=idx===total-1;
  const isLastSec=sec==='reading';
  const nextSecMap={listening:'structure',structure:'reading'};
  const nextIntroMap={listening:'listening',structure:'structure',reading:''};
  let html=`<div class="soal-footer">
    <button class="btn-sblm" onclick="goTo('${sec}',${idx-1})" ${isFirst?'disabled':''}>
      <i class="fas fa-chevron-left"></i> Sebelumnya
    </button>
    <button class="btn-ragu" onclick="toggleRagu('${sec}',${idx})">
      <i class="fas fa-question"></i> Ragu-ragu
    </button>`;
  if(isLast){
    if(isLastSec){
      html+=`<button class="btn-next" style="background:#22C55E" onclick="finishSim()">
        <i class="fas fa-check"></i> Selesaikan Simulasi
      </button>`;
    } else {
      const next=nextSecMap[sec];
      html+=`<button class="btn-next" onclick="goNextSection('${next}')">
        Lanjut ke ${next==='structure'?'Structure':'Reading'} <i class="fas fa-chevron-right"></i>
      </button>`;
    }
  } else {
    html+=`<button class="btn-next" onclick="goTo('${sec}',${idx+1})">
      Selanjutnya <i class="fas fa-chevron-right"></i>
    </button>`;
  }
  html+='</div>';
  return html;
}

// ════════════════════════════════════════════════════════
// NAVIGATION
// ════════════════════════════════════════════════════════
function goTo(sec, idx){
  if(sec==='listening'){ goToListen('listening',idx); return; }
  const total=sec==='structure'?20:25;
  if(idx<0||idx>=total) return;
  curIdx[sec]=idx;
  syncUI();
}
function goNextSection(next){
  curSection=null;
  clearInterval(secTmrInt);
  if(isFS) renderFSIntro(next);
  else showSc('sc-dash');
}
function toggleRagu(sec, idx){
  raguSet[sec][idx]=!raguSet[sec][idx];
  syncUI();
}

// ════════════════════════════════════════════════════════
// ANSWER
// ════════════════════════════════════════════════════════
function pickAns(sec, idx, optIdx){
  if (sec === 'structure') {
    if (answers.structure[idx] !== undefined) return;
    answers.structure[idx] = optIdx;
    delete raguSet.structure[idx];
    // Rebuild soal (tampilkan "tersimpan", tidak benar/salah)
    const center = document.getElementById(isFS ? 'fs-center' : 'dsh-center');
    if (center) center.innerHTML = buildSoalHTML('structure', idx);
    const navHtml = buildNavHTML('structure', 20);
    ['fs-left','dsh-left'].forEach(id=>{const e=document.getElementById(id);if(e)e.innerHTML=navHtml;});
    if (idx < 19) setTimeout(() => goTo('structure', idx + 1), 900);
  } else if (sec === 'reading') {
    if (answers.reading[idx] !== undefined) return;
    answers.reading[idx] = optIdx;
    delete raguSet.reading[idx];
    const center = document.getElementById(isFS ? 'fs-center' : 'dsh-center');
    if (center) center.innerHTML = buildSoalHTML('reading', idx);
    const navHtml = buildNavHTML('reading', 25);
    ['fs-left','dsh-left'].forEach(id=>{const e=document.getElementById(id);if(e)e.innerHTML=navHtml;});
    if (idx < 24) setTimeout(() => goTo('reading', idx + 1), 900);
  }
}

// ════════════════════════════════════════════════════════
// FILL ANSWER HANDLERS
// ════════════════════════════════════════════════════════

// User mengetik di input fill — simpan langsung saat Enter atau maxlength tercapai
function onFillInput(event, flatIdx, correctAns, maxLen) {
  const el = event.target;
  const val = el.value;
  // Auto submit saat panjang sudah cukup
  if (val.length >= maxLen) {
    submitFillBlank(el, flatIdx, correctAns, maxLen);
  }
}

function onFillKey(event, flatIdx, correctAns, blankIdx, totalBlanks, baseFlat) {
  if (event.key === 'Enter') {
    event.preventDefault();
    const el = event.target;
    submitFillBlank(el, flatIdx, correctAns, el.maxLength);
  }
}

function submitFillBlank(el, flatIdx, correctAns, maxLen) {
  const suffix = el.value.trim();
  if (!suffix) { el.focus(); return; }
  if (answers.reading[flatIdx] !== undefined) return; // sudah dijawab

  // Simpan hanya huruf yang diketik user (suffix)
  answers.reading[flatIdx] = suffix;
  el.disabled = true;
  // Simpan tanpa tanda benar/salah — warna hijau = sudah diisi
  el.style.borderColor = '#22C55E';
  el.style.background  = '#F0FDF4';
  el.style.color       = '#15803D';

  delete raguSet.reading[flatIdx];

  // Update nav
  const navHtml = buildNavHTML('reading', 25);
  ['fs-left','dsh-left'].forEach(id=>{const e=document.getElementById(id);if(e)e.innerHTML=navHtml;});

  // Auto advance ke blank berikutnya (dalam grup fill)
  const nextFlat = flatIdx + 1;
  if (nextFlat < 25 && R_FLAT[nextFlat] && R_FLAT[nextFlat].grp.type === 'fill') {
    setTimeout(() => {
      curIdx.reading = nextFlat;
      // Rebuild reading HTML (paragraf tetap tampil, highlight pindah ke blank berikutnya)
      const center = document.getElementById(isFS ? 'fs-center' : 'dsh-center');
      if (center) center.innerHTML = buildSoalHTML('reading', nextFlat);
      // Focus ke input blank berikutnya
      setTimeout(() => {
        const nextInput = document.getElementById(`bl-${nextFlat}`);
        if (nextInput) nextInput.focus();
      }, 50);
      // Update nav
      const navHtml2 = buildNavHTML('reading', 25);
      ['fs-left','dsh-left'].forEach(id=>{const e=document.getElementById(id);if(e)e.innerHTML=navHtml2;});
    }, 400);
  }
}

// ════════════════════════════════════════════════════════
// FINISH
// ════════════════════════════════════════════════════════
function finishSim(){
  clearInterval(secTmrInt);
  clearInterval(listenInt);
  clearInterval(cdInt);
  if(isFS){ isFS=false; document.getElementById('fsMode').classList.remove('on'); document.exitFullscreen?.().catch(()=>{}); }

  let bl={listening:0,structure:0,reading:0};
  SOAL.listening.forEach((s,i)=>{ if(answers.listening[i]===s.ans) bl.listening++; });
  SOAL.structure.forEach((s,i)=>{ if(answers.structure[i]===s.ans) bl.structure++; });
  R_FLAT.forEach((item,i)=>{
    const grp   = item.grp;
    const qIdx  = item.qIdx;
    const ans   = answers.reading[i];
    const q     = grp.type==='fill' ? grp.blanks[qIdx] : grp.questions[qIdx];
    const correct = grp.type==='fill' ? q.ans : q.ans;
    let isOk = false;
    if (grp.type === 'fill') {
      if (ans !== undefined) {
        // User hanya mengetik suffix (huruf yang kurang)
        // correct = kata penuh misal "might", prefix = "mi", suffix = "ght"
        const prefix = q.key.replace(/_/g,'');
        const correctSuffix = correct.slice(prefix.length);
        isOk = ans.toLowerCase().trim() === correctSuffix.toLowerCase().trim();
      }
    } else {
      isOk = (ans === correct);
    }
    if (isOk) bl.reading++;
  });

  const total=bl.listening+bl.structure+bl.reading;
  const pct=Math.round((total/70)*100);
  document.getElementById('h-l').textContent=bl.listening+'/25';
  document.getElementById('h-s').textContent=bl.structure+'/20';
  document.getElementById('h-r').textContent=bl.reading+'/25';
  document.getElementById('h-total').textContent=total+'/70';
  document.getElementById('h-pct').textContent=pct+'%';
  document.getElementById('hasil-emoji').textContent=pct>=80?'🏆':pct>=60?'🎯':'💪';
  buildReview();
  showSc('sc-hasil');
}

function showPembahasan(){ showSc('sc-bhs'); }

// ════════════════════════════════════════════════════════
// REVIEW
// ════════════════════════════════════════════════════════
function buildReview(){
  const letters=['A','B','C','D'];
  let html='';

  // Listening — tampil benar/salah lengkap di pembahasan
  SOAL.listening.forEach((s,i)=>{
    const ans=answers.listening[i];
    const isOk=ans===s.ans;
    const noAns=ans===undefined;
    html+=`<div class="rev-item" data-section="listening">
      <div class="rev-head">
        <div style="width:26px;height:26px;border-radius:6px;
          background:${noAns?'#94A3B8':isOk?'#22C55E':'#EF4444'};
          display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#fff;flex-shrink:0">${s.id}</div>
        <div style="font-size:13px;font-weight:600;color:#0F172A;flex:1;line-height:1.4">${s.q}</div>
        <span style="font-size:12px;font-weight:700;
          color:${noAns?'#94A3B8':isOk?'#22C55E':'#EF4444'};flex-shrink:0">
          ${noAns?'—':isOk?'✓ Benar':'✗ Salah'}
        </span>
      </div>
      <div class="rev-body">
        ${s.opts.map((opt,oi)=>{
          let cls='rev-opt';
          if(oi===s.ans) cls+=' ok';
          else if(oi===ans&&!isOk) cls+=' bad';
          return `<div class="${cls}">
            <strong>${letters[oi]}.</strong> ${opt}
            ${oi===s.ans?' <em style="font-size:10px;font-weight:700">← Jawaban benar</em>':''}
            ${oi===ans&&!isOk?' <em style="font-size:10px;font-weight:700">← Jawaban Anda</em>':''}
          </div>`;
        }).join('')}
        ${noAns?`<div style="font-size:12px;color:#94A3B8;margin-top:6px;font-style:italic">Tidak dijawab</div>`:''}
        <div class="exp-box" style="margin-top:6px">${s.exp}</div>
      </div>
    </div>`;
  });

  // Structure
  SOAL.structure.forEach((s,i)=>{
    const ans=answers.structure[i]; const isOk=ans===s.ans;
    html+=`<div class="rev-item" data-section="structure">
      <div class="rev-head">
        <div style="width:26px;height:26px;border-radius:6px;background:${isOk?'#22C55E':'#EF4444'};
          display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#fff;flex-shrink:0">${s.id}</div>
        <div style="font-size:13px;font-weight:600;color:#0F172A;flex:1">${s.q.replace('___','[blank]')}</div>
        <span style="font-size:12px;font-weight:700;color:${isOk?'#22C55E':'#EF4444'};flex-shrink:0">${isOk?'✓':'✗'}</span>
      </div>
      <div class="rev-body">
        ${s.opts.map((opt,oi)=>{let cls='rev-opt';if(oi===s.ans)cls+=' ok';else if(oi===ans)cls+=' bad';
          return `<div class="${cls}"><strong>${letters[oi]}.</strong> ${opt}</div>`;}).join('')}
        <div class="exp-box" style="margin-top:6px">${s.exp}</div>
      </div>
    </div>`;
  });

  // Reading
  R_FLAT.forEach((item,i)=>{
    const grp=item.grp; const qIdx=item.qIdx; const ans=answers.reading[i];
    const q=grp.type==='fill'?grp.blanks[qIdx]:grp.questions[qIdx];
    const correct=grp.type==='fill'?q.ans:q.ans;
    const exp=grp.type==='fill'?grp.questions[qIdx].exp:q.exp;
    const isOk=grp.type==='fill'
      ?(ans!==undefined&&ans.toLowerCase().trim()===correct.toLowerCase().trim())
      :(ans===correct);
    html+=`<div class="rev-item" data-section="reading">
      <div class="rev-head">
        <div style="width:26px;height:26px;border-radius:6px;background:${isOk?'#22C55E':'#EF4444'};
          display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#fff;flex-shrink:0">${item.id}</div>
        <div style="font-size:13px;font-weight:600;color:#0F172A;flex:1">${grp.type==='fill'?grp.questions[qIdx].q:q.q}</div>
        <span style="font-size:12px;font-weight:700;color:${isOk?'#22C55E':'#EF4444'};flex-shrink:0">${isOk?'✓':'✗'}</span>
      </div>
      <div class="rev-body">
        ${grp.type==='fill'?
          `<div>Jawaban Anda: <strong>${ans||'—'}</strong> | Benar: <strong>${correct}</strong></div>`:
          q.opts.map((opt,oi)=>{let cls='rev-opt';if(oi===q.ans)cls+=' ok';else if(oi===ans)cls+=' bad';
            return `<div class="${cls}"><strong>${letters[oi]}.</strong> ${opt}</div>`;}).join('')}
        <div class="exp-box" style="margin-top:6px">${exp}</div>
      </div>
    </div>`;
  });

  document.getElementById('rev-list').innerHTML=html;
}

function filterRev(sec, tab){
  document.querySelectorAll('.rtab').forEach(t=>t.classList.remove('on'));
  tab.classList.add('on');
  document.querySelectorAll('.rev-item').forEach(item=>{
    item.style.display=(sec==='all'||item.dataset.section===sec)?'block':'none';
  });
}

function resetSim(){
  clearInterval(secTmrInt);clearInterval(listenInt);clearInterval(cdInt);
  curSection=null;answers={listening:{},structure:{},reading:{}};
  raguSet={listening:{},structure:{},reading:{}};fillInputs={};
  listenSec=0;listenQ=-1;listenPhase='intro';secTimer=0;
  showSc('sc-intro');
}

// ════════════════════════════════════════════════════════
// LISTENING HELPERS — inject
// ════════════════════════════════════════════════════════

// Pindah soal listening bebas (tidak ikuti audio)
function goToListen(sec, idx) {
  if (idx < 0 || idx >= 25) return;
  curIdx.listening = idx;
  // Hanya update nav + rebuild soal yang ditampilkan
  updateListenNav();
  const center = document.getElementById(isFS ? 'fs-center' : 'dsh-center');
  if (center) { center.innerHTML = buildListeningCenter(); genWaveformIfNeeded(); }
}

// Update navigator kiri saja (ringan, tanpa rebuild seluruh soal)
function updateListenNav() {
  const html = buildNavHTML('listening', 25);
  ['fs-left','dsh-left'].forEach(id => {
    const e = document.getElementById(id);
    if (e) e.innerHTML = html;
  });
}

// Update status bar audio di atas soal (tanpa rebuild soal)
function updateAudioStatusBar() {
  ['fs-audio-status','dsh-audio-status'].forEach(id => {
    const el = document.getElementById(id);
    if (!el) return;
    let html = '';
    if (listenPhase === 'intro')
      html = `<span style="color:#94A3B8;font-size:12px">🎙 Intro audio...</span>`;
    else if (listenPhase === 'question')
      html = `<span style="background:#FFF7ED;border:1px solid #FED7AA;color:#EA580C;
        padding:2px 10px;border-radius:20px;font-size:11.5px;font-weight:700;
        animation:navpulse .8s infinite">🔊 Audio membaca Soal ${listenQ + 1}</span>`;
    else if (listenPhase === 'answering')
      html = `<span style="background:#F3E8FF;border:1px solid #DDD6FE;color:#7C3AED;
        padding:2px 10px;border-radius:20px;font-size:11.5px;font-weight:700">
        ⏰ Jeda menjawab Soal ${listenQ + 1}</span>`;
    else if (listenPhase === 'done')
      html = `<span style="color:#22C55E;font-weight:700;font-size:12px">✓ Audio selesai</span>`;
    el.innerHTML = html;
  });
  // Update juga nomor soal di topbar
  const sn = document.getElementById(isFS ? 'fs-soal-num' : '');
  if (sn) sn.textContent = `Soal ${curIdx.listening + 1} dari 25`;
}

// Generate waveform bars
function genWaveformIfNeeded() {
  setTimeout(() => {
    ['fs-wv','dsh-wv'].forEach(id => {
      const el = document.getElementById(id);
      if (!el || el.children.length > 0) return;
      for (let i = 0; i < 60; i++) {
        const h = 12 + Math.sin(i * .4) * 8 + Math.random() * 24;
        const b = document.createElement('div');
        b.className = 'wv-b'; b.style.height = Math.max(5, h) + '%'; el.appendChild(b);
      }
    });
  }, 50);
}

// Right info panel untuk listening
function buildListenRightPanel() {
  const done = Object.keys(answers.listening).length;
  return `<div style="padding:0 0 8px">
    <div style="font-size:11px;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.7px;margin-bottom:8px">Info Section</div>
    <div style="font-size:13px;font-weight:800;color:#0F172A;margin-bottom:4px">Listening Comprehension</div>
    <div style="font-size:11px;color:#64748B;margin-bottom:12px">25 Soal | ±18 Menit</div>
    <div style="font-size:11px;font-weight:600;color:#64748B;margin-bottom:4px">Dijawab</div>
    <div class="prog-track"><div class="prog-fill" style="width:${(done/25*100).toFixed(0)}%"></div></div>
    <div style="font-size:11px;color:#64748B;margin-top:4px">${done} / 25</div>
    <div style="margin-top:14px;padding:10px 12px;background:#FFF7ED;border:1px solid #FED7AA;
      border-radius:8px;font-size:12px;color:#92400E;line-height:1.6">
      💡 Pindah soal bebas.<br>
      Nomor <strong style="color:#EA580C">🔊 oranye</strong> = audio sedang di sini.
    </div>
  </div>`;
}

// Override renderFSListening & renderDashListening pakai fungsi yang benar
function renderFSListening() {
  document.getElementById('fs-sec-name').textContent = 'Listening Comprehension';
  document.getElementById('fs-soal-num').textContent = `Soal ${curIdx.listening + 1} dari 25`;
  document.getElementById('fs-right').innerHTML = buildListenRightPanel();
  document.getElementById('fs-center').innerHTML = buildListeningCenter();
  genWaveformIfNeeded();
}
function renderDashListening() {
  document.getElementById('dsh-sec-name').textContent = 'Listening Comprehension';
  document.getElementById('dsh-left').innerHTML = buildNavHTML('listening', 25);
  document.getElementById('dsh-right').innerHTML = buildListenRightPanel();
  document.getElementById('dsh-center').innerHTML = buildListeningCenter();
  genWaveformIfNeeded();
}

</script>
@endpush