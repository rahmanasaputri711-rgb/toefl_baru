<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tes Mini — TOEFL ITP Polman</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root{--bg:#0b1120;--surface:#111827;--surface2:#1a2436;--border:#1f2f46;
            --accent:#3b82f6;--green:#10b981;--gold:#f59e0b;--red:#ef4444;--orange:#f97316;
            --text:#e2e8f0;--muted:#64748b;}
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh}

        /* TOPBAR */
        .topbar{background:var(--surface);border-bottom:1px solid var(--border);
            padding:0 24px;height:58px;display:flex;align-items:center;justify-content:space-between;
            position:sticky;top:0;z-index:100}
        .tb-left{display:flex;align-items:center;gap:12px}
        .tb-badge{background:rgba(59,130,246,.15);color:var(--accent);padding:5px 14px;
            border-radius:20px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.8px}
        .timer-wrap{display:flex;align-items:center;gap:8px;padding:6px 14px;
            border-radius:8px;background:var(--surface2);border:1px solid var(--border)}
        .timer-num{font-family:'JetBrains Mono',monospace;font-size:18px;font-weight:500}
        .timer-num.warn{color:var(--gold)} .timer-num.danger{color:var(--red);animation:pulse 1s infinite}
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:.5}}

        /* CONTENT */
        .content{max-width:800px;margin:0 auto;padding:24px}

        /* SECTION LABEL */
        .section-divider{display:flex;align-items:center;gap:14px;margin:28px 0 16px}
        .section-divider .line{flex:1;height:1px;background:var(--border)}
        .section-tag{padding:6px 16px;border-radius:20px;font-size:12px;font-weight:700;
            text-transform:uppercase;letter-spacing:.8px;white-space:nowrap}
        .tag-listening{background:rgba(249,115,22,.15);color:var(--orange)}
        .tag-structure{background:rgba(245,158,11,.15);color:var(--gold)}
        .tag-reading{background:rgba(59,130,246,.15);color:var(--accent)}

        /* SOAL CARD */
        .soal-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;
            padding:20px;margin-bottom:12px}
        .soal-num{display:flex;align-items:center;gap:10px;margin-bottom:14px}
        .num-badge{width:28px;height:28px;border-radius:7px;background:var(--surface2);
            color:var(--muted);display:flex;align-items:center;justify-content:center;
            font-size:12px;font-weight:700}
        .passage-box{background:var(--surface2);border-left:3px solid var(--accent);
            padding:14px;border-radius:0 8px 8px 0;margin-bottom:14px;
            font-size:13.5px;line-height:1.75;color:#94a3b8}
        /* Audio: gunakan global tap player dari audio-player.css */
        .audio-box{margin-bottom:14px}
        /* Override tap-bar untuk dark theme mini */
        .soal-card .tap-bar{background:#2a3548;border-color:#1f2f46}
        .soal-card .tap-track-inner{background:#1a1a2e}
        .soal-card .tap-time{color:#8899aa}
        .soal-card .tap-play-triangle{border-color:transparent transparent transparent #a0b4c8}
        .pertanyaan{font-size:15px;font-weight:600;line-height:1.6;margin-bottom:14px}
        .pilihan-list{display:flex;flex-direction:column;gap:7px}
        .pilihan-item{display:flex;align-items:flex-start;gap:11px;padding:11px 14px;
            border-radius:8px;border:1.5px solid var(--border);cursor:pointer;transition:all .15s}
        .pilihan-item:hover{border-color:var(--accent);background:rgba(59,130,246,.04)}
        .pilihan-item.selected{border-color:var(--accent);background:rgba(59,130,246,.1)}
        .pilihan-item input[type=radio]{accent-color:var(--accent);cursor:pointer;margin-top:3px;flex-shrink:0}
        .opt-label{font-weight:700;color:var(--muted);width:16px;flex-shrink:0;font-size:13px}
        .opt-text{font-size:14px;line-height:1.5}

        /* FOOTER */
        .footer-bar{background:var(--surface);border-top:1px solid var(--border);
            padding:16px 24px;position:sticky;bottom:0;
            display:flex;align-items:center;justify-content:space-between}
        .prog-info{font-size:13px;color:var(--muted)}
        .prog-bar-wrap{flex:1;max-width:200px;height:6px;background:var(--border);
            border-radius:3px;margin:0 16px}
        .prog-bar-fill{height:6px;background:var(--green);border-radius:3px;transition:width .3s}
        .btn-submit{display:inline-flex;align-items:center;gap:8px;padding:10px 28px;
            border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;border:none;
            background:var(--green);color:#fff;font-family:inherit;transition:opacity .15s}
        .btn-submit:hover{opacity:.88}
        ::-webkit-scrollbar{width:4px} ::-webkit-scrollbar-thumb{background:var(--border);border-radius:10px}
    </style>
</head>
<body>
{{-- Splash fullscreen request --}}
<div id="fs-splash" style="position:fixed;inset:0;z-index:9999;
    background:#0b1120;display:flex;flex-direction:column;
    align-items:center;justify-content:center;gap:20px">
    <i class="fas fa-expand-arrows-alt" style="font-size:48px;color:#3b82f6"></i>
    <div style="font-size:20px;font-weight:700;color:#f1f5f9">Siap Memulai Tes?</div>
    <div style="font-size:14px;color:#64748b;text-align:center">
        Tes akan berjalan dalam mode layar penuh.<br>
        Klik tombol di bawah untuk memulai.
    </div>
    <button onclick="mulaiTes()" style="
        background:#3b82f6;color:#fff;border:none;border-radius:12px;
        padding:14px 36px;font-size:16px;font-weight:700;cursor:pointer;
        font-family:inherit;transition:all .2s"
        onmouseover="this.style.background='#2563eb'"
        onmouseout="this.style.background='#3b82f6'">
        <i class="fas fa-play-circle"></i> Mulai Tes Sekarang
    </button>
    <div style="font-size:12px;color:#475569">
        <i class="fas fa-info-circle"></i>
        Tekan <kbd style="background:#1e293b;padding:2px 7px;border-radius:4px;
        border:1px solid #334155">F11</kbd> atau tombol di atas untuk layar penuh
    </div>
</div>
<div class="topbar">
    <div class="tb-left">
        <span class="tb-badge"><i class="fas fa-bolt"></i> Tes Mini</span>
        <span style="font-size:13px;color:var(--muted)">{{ $soalList->count() }} Soal</span>
    </div>
    <div style="display:flex;align-items:center;gap:10px">
        <div class="timer-wrap">
            <i class="fas fa-clock" style="color:var(--muted);font-size:13px"></i>
            <span class="timer-num" id="timer-display">30:00</span>
        </div>
        <button onclick="toggleFs()" title="Toggle Layar Penuh" type="button"
            style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);
            color:rgba(255,255,255,.65);border-radius:8px;padding:7px 12px;cursor:pointer;
            font-size:12px;display:flex;align-items:center;gap:6px;font-family:inherit">
            <i class="fas fa-expand" id="fs-icon"></i>
            <span id="fs-lbl">Layar Penuh</span>
        </button>
    </div>
</div>

<form action="{{ route('user.tes.mini.submit') }}" method="POST" id="mini-form">
    @csrf
    <div class="content">

        @php $prevKat = null; $no = 0; @endphp
        @foreach($soalList as $soal)
        @php $no++; @endphp

        {{-- Section divider --}}
        @if($soal->kategori !== $prevKat)
        <div class="section-divider">
            <div class="line"></div>
            <span class="section-tag tag-{{ $soal->kategori }}">
                <i class="fas fa-{{ $soal->kategori=='listening' ? 'headphones' : ($soal->kategori=='structure' ? 'pen-nib' : 'book-reader') }}"></i>
                Section: {{ ucfirst($soal->kategori) }}
            </span>
            <div class="line"></div>
        </div>
        @php $prevKat = $soal->kategori; @endphp
        @endif

        <div class="soal-card" id="soal-{{ $soal->id }}">
            <div class="soal-num">
                <div class="num-badge">{{ $no }}</div>
                <span style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px">
                    {{ ucfirst($soal->kategori) }} · {{ ucfirst($soal->tingkat_kesulitan) }}
                </span>
            </div>

            @if($soal->passage_teks)
            <div class="passage-box">{{ $soal->passage_teks }}</div>
            @endif

            @if($soal->audio_url)
            @php
                $aUrl = \App\Services\AudioService::resolveUrl($soal->audio_url);
                $pid  = 'mini-' . $soal->id;
            @endphp
            <div class="toefl-audio-wrap" style="margin-bottom:14px">
              <div class="tap-label" style="color:#94a3b8">
                <i class="fas fa-headphones-alt"></i> Audio
                <span style="font-size:10px;color:#10b981;font-weight:600;margin-left:4px">● Dapat diputar ulang</span>
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
            <label class="pilihan-item" data-soal="{{ $soal->id }}">
                <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $opt }}"
                    class="jawaban-radio" onchange="onJawab('{{ $soal->id }}', this)">
                <span class="opt-label">{{ strtoupper($opt) }}</span>
                <span class="opt-text">{{ $soal->{'pilihan_'.$opt} }}</span>
            </label>
            @endforeach
            </div>
        </div>
        @endforeach

        <div style="height:80px"></div>{{-- ruang untuk footer --}}
    </div>

    <div class="footer-bar">
        <div style="display:flex;align-items:center;gap:12px">
            <span class="prog-info"><span id="count-dijawab" style="color:var(--green)">0</span>/{{ $soalList->count() }} dijawab</span>
            <div class="prog-bar-wrap">
                <div class="prog-bar-fill" id="prog-fill" style="width:0%"></div>
            </div>
        </div>
        <button type="button" class="btn-submit" onclick="confirmSubmit()">
            <i class="fas fa-paper-plane"></i> Kumpulkan Jawaban
        </button>
    </div>
</form>

<script>
const TOTAL = {{ $soalList->count() }};
let dijawab = 0;
let timerSisa = {{ $durasi }};
let audioPlayed = {};

function onJawab(soalId, radio) {
    if (!radio._counted) {
        radio._counted = true;
        // Cek apakah soal ini sudah pernah dijawab
        const existing = document.querySelector(`input[name="jawaban[${soalId}]"].counted`);
        if (!existing) {
            dijawab++;
            radio.classList.add('counted');
        }
    }
    // Style label
    document.querySelectorAll(`label[data-soal="${soalId}"]`).forEach(l => {
        l.classList.remove('selected');
    });
    radio.closest('label').classList.add('selected');

    document.getElementById('count-dijawab').textContent = dijawab;
    document.getElementById('prog-fill').style.width = Math.round((dijawab/TOTAL)*100) + '%';
}

// Hitung ulang dijawab yang benar (supaya tidak double count)
document.querySelectorAll('.jawaban-radio').forEach(r => {
    r.addEventListener('change', function() {
        const name = this.name;
        const counted = document.querySelector(`[name="${name}"].counted`);
        if (!counted) {
            this.classList.add('counted');
            dijawab++;
        }
        document.querySelectorAll(`label[data-soal="${this.closest('label').dataset.soal}"]`)
            .forEach(l => l.classList.remove('selected'));
        this.closest('label').classList.add('selected');
        document.getElementById('count-dijawab').textContent = dijawab;
        document.getElementById('prog-fill').style.width = Math.round((dijawab/TOTAL)*100)+'%';
    });
});

// Hitung ulang berdasarkan yang checked
function recountAnswered() {
    const names = new Set();
    document.querySelectorAll('.jawaban-radio:checked').forEach(r => names.add(r.name));
    dijawab = names.size;
    document.getElementById('count-dijawab').textContent = dijawab;
    document.getElementById('prog-fill').style.width = Math.round((dijawab/TOTAL)*100)+'%';
}

// Audio dihandle oleh tapToggle() dari audio-player.js

// Timer
function startTimer() {
    const interval = setInterval(() => {
        timerSisa--;
        const m = Math.floor(timerSisa/60), s = timerSisa%60;
        const el = document.getElementById('timer-display');
        el.textContent = String(m).padStart(2,'0')+':'+String(s).padStart(2,'0');
        el.className = 'timer-num'+(timerSisa<=60?' danger':timerSisa<=180?' warn':'');
        if (timerSisa <= 0) { clearInterval(interval); document.getElementById('mini-form').submit(); }
    }, 1000);
}

function confirmSubmit() {
    recountAnswered();
    const belum = TOTAL - dijawab;
    const msg = belum > 0
        ? `Masih ada ${belum} soal yang belum dijawab. Kumpulkan tetap?`
        : `Semua ${TOTAL} soal sudah dijawab. Kumpulkan?`;
    if (confirm(msg)) document.getElementById('mini-form').submit();
}

// ── Fullscreen sederhana — Mini Test (TANPA anti-cheat) ─────────
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

startTimer();
// Masuk fullscreen otomatis
function mulaiTes() {
    // Dipanggil dari klik user → browser izinkan fullscreen
    enterFullscreen();
    document.getElementById('fs-splash').style.display = 'none';
    startTimer(); // pindahkan startTimer() ke sini
}

// Hapus startTimer() dari DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
    // Jangan panggil startTimer() di sini
    // Timer baru jalan setelah user klik "Mulai"
});</script>
<script src="{{ asset('js/audio-player.js') }}"></script>
</body>
</html>
