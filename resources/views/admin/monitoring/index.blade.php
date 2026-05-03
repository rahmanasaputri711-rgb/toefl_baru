@extends('layouts.admin')
@section('title','Monitoring Tes')
@section('page-title','Monitoring Real-time')
@section('breadcrumb','Admin / Monitoring')

@push('styles')
<style>
.monitor-card{background:var(--navy-light);border:1px solid var(--border);border-radius:12px;
    padding:16px;transition:border-color .2s}
.monitor-card.online{border-color:rgba(22,163,74,.3)}
.monitor-card.curang{border-color:rgba(220,38,38,.4);background:rgba(220,38,38,.04)}
.status-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
.status-dot.online{background:var(--green);animation:pulse 2s infinite}
.status-dot.offline{background:var(--muted)}
.status-dot.curang{background:var(--red)}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.6;transform:scale(.85)}}
.timer-bar{height:5px;border-radius:3px;background:var(--border);overflow:hidden;margin-top:8px}
.timer-fill{height:5px;border-radius:3px;transition:width 1s linear}
.pelanggaran-chip{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;
    border-radius:10px;font-size:11px;font-weight:700;background:rgba(220,38,38,.1);
    color:var(--red);border:1px solid rgba(220,38,38,.2)}
</style>
@endpush

@section('content')

{{-- Pilih Sesi --}}
<div style="display:flex;align-items:center;gap:14px;margin-bottom:18px;flex-wrap:wrap">
    <form method="GET" style="display:flex;gap:10px;align-items:center;flex:1;min-width:200px">
        <select name="sesi_id" class="form-control" style="max-width:320px" onchange="this.form.submit()">
            <option value="">-- Semua Sesi Aktif --</option>
            @foreach($sesiAktif as $s)
            <option value="{{ $s->id }}" {{ $sesi_id==$s->id?'selected':'' }}>{{ $s->judul }}</option>
            @endforeach
        </select>
    </form>
    <div style="display:flex;align-items:center;gap:8px;font-size:12.5px;color:var(--muted)">
        <i class="fas fa-sync-alt" id="refresh-icon"></i>
        Auto-refresh: <span id="refresh-countdown" style="font-weight:700;color:var(--text)">30</span>s
        <button onclick="doRefresh()" class="btn btn-outline btn-sm" style="padding:4px 10px">
            <i class="fas fa-sync-alt"></i> Sekarang
        </button>
    </div>
</div>

{{-- Statistik Ringkas --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:18px">
    @foreach([
        ['Sedang Tes',  $statsAktif['sedang'],  'desktop',   'si-blue'],
        ['Online',      $statsAktif['online'],  'signal',    'si-green'],
        ['Offline/Idle',$statsAktif['offline'], 'wifi-slash','si-gold'],
        ['Terindikasi', $statsAktif['curang'],  'user-secret','si-red'],
    ] as [$lbl,$val,$ico,$cls])
    <div class="stat-card">
        <div class="stat-icon {{ $cls }}"><i class="fas fa-{{ $ico }}"></i></div>
        <div><div class="stat-val">{{ $val }}</div><div class="stat-label">{{ $lbl }}</div></div>
    </div>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:1fr 380px;gap:18px;align-items:start">

    {{-- ── Daftar Peserta ── --}}
    <div>
        <div style="font-size:12.5px;font-weight:700;color:var(--muted);text-transform:uppercase;
            letter-spacing:.8px;margin-bottom:12px">Peserta Sedang Mengerjakan</div>

        @forelse($pesertaAktif as $p)
        @php
            $pctWaktu = $p->sisa_detik !== null && $p->waktu_berakhir
                ? min(100, max(0, ($p->sisa_detik / (isset($p->waktu_mulai) ? max(1, $p->waktu_berakhir->diffInSeconds($p->waktu_mulai)) : 6900)) * 100))
                : 50;
            $warnaCls = $p->status_curang ? 'curang' : ($p->online ? 'online' : '');
        @endphp
        <div class="monitor-card {{ $warnaCls }}" style="margin-bottom:10px">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px">
                <div style="display:flex;align-items:center;gap:10px;min-width:0;flex:1">
                    <div class="status-dot {{ $p->status_curang ? 'curang' : ($p->online ? 'online' : 'offline') }}"></div>
                    <div style="min-width:0">
                        <div style="font-size:14px;font-weight:700;
                            white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                            {{ $p->user?->name ?? 'User #'.$p->user_id }}
                        </div>
                        <div style="font-size:11.5px;color:var(--muted)">
                            {{ $p->user?->email }}
                            @if($p->total_percobaan > 1)
                            <span style="color:var(--gold)"> · Percobaan ke-{{ $p->tes_ke }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
                    @if($p->status_curang)
                    <span class="pelanggaran-chip">
                        <i class="fas fa-exclamation-triangle" style="font-size:9px"></i>
                        {{ $p->jumlah_pelanggaran }}× pelanggaran
                    </span>
                    @elseif($p->jumlah_pelanggaran > 0)
                    <span class="pelanggaran-chip" style="background:rgba(217,119,6,.1);color:var(--gold);border-color:rgba(217,119,6,.2)">
                        <i class="fas fa-exclamation" style="font-size:9px"></i>
                        {{ $p->jumlah_pelanggaran }}/3
                    </span>
                    @endif

                    {{-- Dropdown aksi --}}
                    <div style="position:relative" x-data="{open:false}">
                        <button onclick="toggleMenu({{ $p->id }})" class="btn btn-outline btn-sm"
                            style="padding:5px 10px;font-size:12px">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div id="menu-{{ $p->id }}" style="display:none;position:absolute;right:0;top:100%;
                            background:#fff;border:1px solid var(--border);border-radius:10px;
                            box-shadow:0 10px 30px rgba(0,0,0,.12);min-width:180px;z-index:50;overflow:hidden">
                            <form action="{{ route('admin.percobaan.reset-akses', $p->id) }}" method="POST">
                                @csrf
                                <button type="submit" style="width:100%;padding:10px 14px;text-align:left;
                                    font-size:13px;border:none;background:none;cursor:pointer;color:var(--blue);
                                    display:flex;align-items:center;gap:8px">
                                    <i class="fas fa-unlock-alt" style="width:14px"></i> Reset Akses (+15 mnt)
                                </button>
                            </form>
                            <div style="height:1px;background:var(--border)"></div>
                            <form action="{{ route('admin.monitoring.diskualifikasi', $p->id) }}" method="POST"
                                onsubmit="return confirm('Diskualifikasi {{ $p->user?->name }}? Aksi tidak bisa dibatalkan.')">
                                @csrf
                                <button type="submit" style="width:100%;padding:10px 14px;text-align:left;
                                    font-size:13px;border:none;background:none;cursor:pointer;color:var(--red);
                                    display:flex;align-items:center;gap:8px">
                                    <i class="fas fa-ban" style="width:14px"></i> Diskualifikasi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Timer sisa --}}
            @if($p->sisa_detik !== null)
            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:8px;font-size:12px">
                <span style="color:var(--muted)">Sisa waktu</span>
                <span style="font-family:'JetBrains Mono',monospace;font-weight:700;
                    color:{{ $p->sisa_detik < 300 ? 'var(--red)' : 'var(--text)' }}"
                    data-sisa="{{ $p->sisa_detik }}" class="sisa-timer">
                    {{ gmdate('H:i:s', $p->sisa_detik) }}
                </span>
            </div>
            <div class="timer-bar">
                <div class="timer-fill"
                    style="width:{{ min(100,max(0,$pctWaktu)) }}%;
                    background:{{ $p->sisa_detik < 300 ? 'var(--red)' : ($p->sisa_detik < 900 ? 'var(--gold)' : 'var(--green)') }}">
                </div>
            </div>
            @endif

            @if(!$p->online)
            <div style="margin-top:8px;font-size:11.5px;color:var(--muted)">
                <i class="fas fa-clock"></i>
                Terakhir aktif: {{ $p->last_autosave_at ? $p->last_autosave_at->diffForHumans() : 'tidak diketahui' }}
            </div>
            @endif
        </div>
        @empty
        <div class="card">
            <div class="card-body" style="text-align:center;padding:50px">
                <i class="fas fa-desktop" style="font-size:36px;color:var(--muted);display:block;margin-bottom:12px"></i>
                <div style="font-size:15px;font-weight:600">Tidak Ada Peserta Aktif</div>
                <div style="font-size:13px;color:var(--muted);margin-top:6px">
                    Peserta yang sedang mengerjakan tes akan muncul di sini.
                </div>
            </div>
        </div>
        @endforelse
    </div>

    {{-- ── Log Pelanggaran Real-time ── --}}
    <div style="position:sticky;top:20px">
        <div style="font-size:12.5px;font-weight:700;color:var(--muted);text-transform:uppercase;
            letter-spacing:.8px;margin-bottom:12px">
            <i class="fas fa-exclamation-triangle" style="color:var(--red)"></i>
            Log Pelanggaran
        </div>
        <div style="background:var(--navy-light);border:1px solid var(--border);border-radius:12px;
            max-height:calc(100vh - 200px);overflow-y:auto">
            @forelse($logPelanggaran as $l)
            @php
                $jenisColor = match($l->jenis) {
                    'tab_switch'       => ['#fef3c7','#92400e','Pindah Tab'],
                    'copy_paste'       => ['#fee2e2','#991b1b','Copy-Paste'],
                    'klik_kanan'       => ['#e0f2fe','#0c4a6e','Klik Kanan'],
                    'keluar_fullscreen'=> ['#fce7f3','#9d174d','Keluar Fullscreen'],
                    'screenshot'       => ['#ede9fe','#4c1d95','Screenshot'],
                    default            => ['#f1f5f9','#475569','Lainnya'],
                };
            @endphp
            <div style="padding:11px 14px;border-bottom:1px solid var(--border);display:flex;gap:10px">
                <div style="flex-shrink:0;margin-top:2px">
                    <span style="display:inline-block;background:{{ $jenisColor[0] }};
                        color:{{ $jenisColor[1] }};padding:2px 8px;border-radius:8px;
                        font-size:10.5px;font-weight:700;white-space:nowrap">
                        {{ $jenisColor[2] }}
                    </span>
                </div>
                <div style="min-width:0;flex:1">
                    <div style="font-size:12.5px;font-weight:600;
                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $l->user?->name ?? 'User #'.$l->user_id }}
                    </div>
                    <div style="font-size:11px;color:var(--muted)">
                        ke-{{ $l->pelanggaran_ke }} ·
                        {{ $l->waktu_pelanggaran?->format('H:i:s') }}
                    </div>
                </div>
            </div>
            @empty
            <div style="padding:30px;text-align:center;color:var(--muted);font-size:13px">
                <i class="fas fa-shield-alt" style="display:block;font-size:24px;margin-bottom:8px;color:var(--green)"></i>
                Tidak ada pelanggaran
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// ── Auto refresh countdown ──
let countdown = 30;
const cdEl    = document.getElementById('refresh-countdown');
const riEl    = document.getElementById('refresh-icon');

function doRefresh() {
    riEl.style.animation = 'spin .5s linear infinite';
    setTimeout(() => location.reload(), 300);
}

setInterval(() => {
    countdown--;
    cdEl.textContent = countdown;
    if (countdown <= 0) doRefresh();
}, 1000);

// ── Countdown timer per peserta ──
document.querySelectorAll('.sisa-timer').forEach(el => {
    let sisa = parseInt(el.dataset.sisa) || 0;
    setInterval(() => {
        sisa = Math.max(0, sisa - 1);
        const h = Math.floor(sisa/3600);
        const m = Math.floor((sisa%3600)/60);
        const s = sisa%60;
        el.textContent = [h,m,s].map(n=>String(n).padStart(2,'0')).join(':');
        if (sisa < 300) el.style.color = 'var(--red)';
        if (sisa < 60)  el.style.fontWeight = '900';
    }, 1000);
});

// ── Dropdown menu toggle ──
function toggleMenu(id) {
    document.querySelectorAll('[id^="menu-"]').forEach(m => {
        if (m.id !== 'menu-' + id) m.style.display = 'none';
    });
    const menu = document.getElementById('menu-' + id);
    menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
}

document.addEventListener('click', e => {
    if (!e.target.closest('[id^="menu-"]') && !e.target.closest('button[onclick^="toggleMenu"]')) {
        document.querySelectorAll('[id^="menu-"]').forEach(m => m.style.display = 'none');
    }
});

// spin animation
const style = document.createElement('style');
style.textContent = '@keyframes spin{to{transform:rotate(360deg)}}';
document.head.appendChild(style);
</script>
@endpush
