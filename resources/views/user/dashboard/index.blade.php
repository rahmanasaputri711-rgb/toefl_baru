@extends('layouts.user')
@section('title','Dashboard')
@section('page-title','Halo, {{ auth()->user()->name ?? "User" }}! 👋')
@section('breadcrumb','Semangat belajar hari ini! Kamu bisa lebih baik dari kemarin.')

@push('styles')
<style>
/* ── Progress Ring ── */
.ring-wrap  { position:relative; width:106px; height:106px; flex-shrink:0; }
.ring-svg   { transform:rotate(-90deg); }
.ring-bg    { fill:none; stroke:rgba(255,255,255,.2); stroke-width:8; }
.ring-fill  { fill:none; stroke:rgba(255,255,255,.9); stroke-width:8; stroke-linecap:round;
              filter:drop-shadow(0 0 6px rgba(255,255,255,.3));
         
             transition:stroke-dashoffset 1.2s cubic-bezier(.4,0,.2,1); }
.ring-inner { position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; }
.ring-pct   { font-size:22px; font-weight:800; color:#fff; line-height:1; }
.ring-lbl   { font-size:10px; color:rgba(255,255,255,.7); margin-top:3px; font-weight:500; }

/* ── Quick Action Cards ── */
.qa-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:18px; }
.qa-card {
    background:var(--white); border:1px solid var(--border);
    border-radius:14px; padding:18px 16px 15px;
    text-decoration:none; display:block;
    transition:all .2s; position:relative;
    box-shadow:0 1px 3px rgba(15,23,42,.04);
}
.qa-card:hover {
    transform:translateY(-2px);
    box-shadow:0 6px 20px rgba(15,23,42,.08);
    border-color:rgba(37,99,235,.2);
}
.qa-card-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:13px; }
.qa-icon     { width:42px; height:42px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:17px; }
.qa-arrow    {
    width:26px; height:26px; border-radius:7px;
    background:var(--bg); border:1px solid var(--border);
    display:flex; align-items:center; justify-content:center;
    color:var(--muted-lt); font-size:10px;
    transition:all .15s;
}
.qa-card:hover .qa-arrow { background:var(--blue-lt); border-color:var(--blue-pale); color:var(--blue); }
.qa-title    { font-size:13.5px; font-weight:700; color:var(--navy); margin-bottom:5px; }
.qa-desc     { font-size:12px; color:var(--muted); line-height:1.55; margin-bottom:12px; min-height:36px; }
.qa-link     { font-size:12.5px; font-weight:700; display:inline-flex; align-items:center; gap:5px; text-decoration:none; }

/* ── Progress Bars ── */
.prog-item   { margin-bottom:15px; }
.prog-item:last-child { margin-bottom:0; }
.prog-top    { display:flex; justify-content:space-between; align-items:center; margin-bottom:7px; }
.prog-name   { font-size:13px; font-weight:600; color:var(--text); }
.prog-pct    { font-size:12.5px; font-weight:700; }
.prog-track  { height:7px; background:var(--bg); border-radius:6px; overflow:hidden; border:1px solid var(--border); }
.prog-fill   { height:100%; border-radius:6px; transition:width 1.2s cubic-bezier(.4,0,.2,1); }

/* ── Score Ring ── */
.sc-ring-wrap { position:relative; width:96px; height:96px; flex-shrink:0; }
.sc-ring-wrap svg { transform:rotate(-90deg); }
.sc-ring-bg   { fill:none; stroke:var(--border); stroke-width:7; }
.sc-ring-fill { fill:none; stroke-linecap:round; stroke-width:7; transition:stroke-dashoffset 1s ease; }
.sc-ring-text { position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; }
</style>
@endpush

@section('content')

@if(!auth()->user()->is_active)
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i>
    <div>
        <strong>Akun belum terverifikasi untuk Tes Full.</strong>
        Daftar ke sesi Tes Full dan upload KTM — admin akan memverifikasi.
        <a href="{{ route('user.tes.full') }}" style="color:var(--amber);font-weight:700;margin-left:4px">Daftar Sekarang →</a>
    </div>
</div>
@endif

@if($pendaftaranAktif)
<div class="alert {{ $pendaftaranAktif->status_pendaftaran=='dikonfirmasi' ? 'alert-success' : ($pendaftaranAktif->status_pendaftaran=='menunggu' ? 'alert-warning' : 'alert-danger') }}">
    <i class="fas fa-{{ $pendaftaranAktif->status_pendaftaran=='dikonfirmasi' ? 'check-circle' : ($pendaftaranAktif->status_pendaftaran=='menunggu' ? 'clock' : 'times-circle') }}"></i>
    <div>
        @if($pendaftaranAktif->status_pendaftaran=='menunggu')
            <strong>Pendaftaran Tes Full sedang diproses.</strong> Tunggu konfirmasi admin.
        @elseif($pendaftaranAktif->status_pendaftaran=='dikonfirmasi')
            <strong>Pendaftaran dikonfirmasi!</strong>
            Jadwal: <strong>{{ $pendaftaranAktif->sesiTes ? \Carbon\Carbon::parse($pendaftaranAktif->sesiTes->waktu_mulai)->format('d M Y, H:i') : '-' }}</strong>
            · No: <strong style="font-family:monospace">{{ $pendaftaranAktif->nomor_pendaftaran }}</strong>
        @else
            <strong>Pendaftaran ditolak.</strong> {{ $pendaftaranAktif->catatan_admin }}
        @endif
    </div>
</div>
@endif

{{-- ══════════════════════════════
     HERO CARD — softer, calmer
══════════════════════════════ --}}
@php
    $pct    = $totalTes > 0 ? min(100, round(($totalTes / 10) * 100)) : 0;
    $circ   = 2 * M_PI * 43;
    $offset = $circ - ($pct / 100) * $circ;
@endphp
<div style="
    background: linear-gradient(135deg, #1E40AF 0%, #2563EB 60%, #3B82F6 100%);
    border-radius: 16px; padding: 26px 28px 0;
    margin-bottom: 18px; display: flex;
    align-items: flex-end; gap: 20px;
    position: relative; overflow: hidden;
    box-shadow: 0 4px 24px rgba(37,99,235,.2), 0 1px 4px rgba(37,99,235,.1);">

    {{-- Subtle bg circles --}}
    <div style="position:absolute;right:260px;top:-50px;width:240px;height:240px;
        border-radius:50%;background:rgba(255,255,255,.04);pointer-events:none"></div>
    <div style="position:absolute;right:-30px;bottom:-30px;width:200px;height:200px;
        border-radius:50%;background:rgba(255,255,255,.04);pointer-events:none"></div>
    <div style="position:absolute;top:0;left:0;right:0;height:1px;
        background:rgba(255,255,255,.12);pointer-events:none"></div>

    {{-- Progress Ring --}}
    <div class="ring-wrap" style="position:relative;z-index:1;padding-bottom:26px">
        <svg class="ring-svg" width="106" height="106" viewBox="0 0 106 106">
            <circle class="ring-bg" cx="53" cy="53" r="43"/>
            <circle class="ring-fill" cx="53" cy="53" r="43"
                stroke-dasharray="{{ $circ }}"
                stroke-dashoffset="{{ $offset }}"/>
        </svg>
        <div class="ring-inner">
            <div class="ring-pct">{{ $pct }}%</div>
            <div class="ring-lbl">Progress</div>
        </div>
    </div>

    {{-- Text + CTA --}}
    <div style="flex:1;position:relative;z-index:1;padding-bottom:26px">
        <div style="font-size:10.5px;font-weight:700;color:rgba(255,255,255,.6);
            text-transform:uppercase;letter-spacing:1.4px;margin-bottom:6px">
            Persiapan TOEFL-mu
        </div>
        @if($skorTerbaik)
        <div style="display:inline-flex;align-items:center;gap:6px;
            background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);
            color:#fff;padding:3px 11px;border-radius:20px;
            font-size:11.5px;font-weight:600;margin-bottom:9px">
            <i class="fas fa-bullseye" style="font-size:10px"></i>
            Skor Target: 550+
        </div>
        @endif
        <h2 style="font-size:20px;font-weight:800;color:#fff;line-height:1.3;margin-bottom:5px">
            Semangat belajar hari ini! 💪
        </h2>
        <p style="font-size:13px;color:rgba(255,255,255,.72);line-height:1.65;margin-bottom:16px;max-width:340px">
            "Konsistensi hari ini adalah hasil yang kamu banggakan nanti."
        </p>
        <div style="display:flex;gap:9px;flex-wrap:wrap">
            <a href="{{ route('user.latihan.index') }}"
               style="display:inline-flex;align-items:center;gap:7px;
                   background:#fff;color:#1E40AF;
                   padding:9px 20px;border-radius:8px;
                   font-size:13px;font-weight:700;text-decoration:none;
                   box-shadow:0 2px 10px rgba(0,0,0,.12);
                   transition:all .15s"
               onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 16px rgba(0,0,0,.15)'"
               onmouseout="this.style.transform='';this.style.boxShadow='0 2px 10px rgba(0,0,0,.12)'">
                <i class="fas fa-pen-to-square"></i> Lanjut Belajar
            </a>
            <a href="{{ route('user.tes.simulasi') }}"
               style="display:inline-flex;align-items:center;gap:7px;
                   background:rgba(255,255,255,.15);color:#fff;
                   border:1px solid rgba(255,255,255,.25);
                   padding:9px 18px;border-radius:8px;
                   font-size:13px;font-weight:600;text-decoration:none;
                   transition:all .15s"
               onmouseover="this.style.background='rgba(255,255,255,.22)'"
               onmouseout="this.style.background='rgba(255,255,255,.15)'">
                <i class="fas fa-flask"></i> Simulasi
            </a>
        </div>
    </div>

    {{-- Illustration --}}
    <div style="flex-shrink:0;position:relative;z-index:1;align-self:flex-end">
        <img src="{{ asset('images/hero-vector.png') }}" alt="TOEFL"
             style="width:350px;height:auto;display:block;
                    filter:drop-shadow(0 8px 20px rgba(0,0,0,.15));
                    mix-blend-mode:multiply;">
    </div>
</div>

{{-- ══════════════════════════════
     QUICK ACTION CARDS
══════════════════════════════ --}}
<div class="qa-grid">
    <a href="{{ route('user.materi.index') }}" class="qa-card">
        <div class="qa-card-top">
            <div class="qa-icon" style="background:#EFF6FF"><i class="fas fa-book-open" style="color:#2563EB"></i></div>
            <div class="qa-arrow"><i class="fas fa-chevron-right"></i></div>
        </div>
        <div class="qa-title">Materi Belajar</div>
        <div class="qa-desc">Akses materi lengkap Reading, Listening, Structure.</div>
        <div class="qa-link" style="color:#2563EB">Mulai <i class="fas fa-arrow-right" style="font-size:10px"></i></div>
    </a>
    <a href="{{ route('user.latihan.index') }}" class="qa-card">
        <div class="qa-card-top">
            <div class="qa-icon" style="background:#F0FDF4"><i class="fas fa-pen-nib" style="color:#16A34A"></i></div>
            <div class="qa-arrow"><i class="fas fa-chevron-right"></i></div>
        </div>
        <div class="qa-title">Latihan Soal</div>
        <div class="qa-desc">Latihan soal untuk mengasah kemampuan mu.</div>
        <div class="qa-link" style="color:#16A34A">Mulai <i class="fas fa-arrow-right" style="font-size:10px"></i></div>
    </a>
    <a href="{{ route('user.tes.simulasi') }}" class="qa-card">
        <div class="qa-card-top">
            <div class="qa-icon" style="background:#F3E8FF"><i class="fas fa-flask" style="color:#7C3AED"></i></div>
            <div class="qa-arrow"><i class="fas fa-chevron-right"></i></div>
        </div>
        <div class="qa-title">Tes Simulasi</div>
        <div class="qa-desc">Kerjakan simulasi tes seperti ujian asli TOEFL.</div>
        <div class="qa-link" style="color:#7C3AED">Mulai <i class="fas fa-arrow-right" style="font-size:10px"></i></div>
    </a>
    <a href="{{ route('user.hasil.index') }}" class="qa-card">
        <div class="qa-card-top">
            <div class="qa-icon" style="background:#FFF7ED"><i class="fas fa-chart-bar" style="color:#EA580C"></i></div>
            <div class="qa-arrow"><i class="fas fa-chevron-right"></i></div>
        </div>
        <div class="qa-title">Riwayat Tes</div>
        <div class="qa-desc">Lihat hasil tes dan perkembangan skor kamu.</div>
        <div class="qa-link" style="color:#EA580C">Lihat <i class="fas fa-arrow-right" style="font-size:10px"></i></div>
    </a>
</div>

{{-- ══════════════════════════════
     BOTTOM ROW
══════════════════════════════ --}}
<div class="grid-2" style="gap:16px">

    {{-- Progress per Section --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-chart-line" style="color:var(--blue);margin-right:8px;font-size:13px"></i>Progress per Section</h3>
            <a href="{{ route('user.hasil.index') }}" class="btn btn-outline btn-sm">Detail</a>
        </div>
        <div class="card-body">
            @php
                $ls = $tesTerakhir->skor_listening  ?? 0;
                $rd = $tesTerakhir->skor_reading     ?? 0;
                $st = $tesTerakhir->skor_structure   ?? 0;
                $mx = 68;
                $pL = $ls ? min(100, round(($ls/$mx)*100)) : 0;
                $pR = $rd ? min(100, round(($rd/$mx)*100)) : 0;
                $pS = $st ? min(100, round(($st/$mx)*100)) : 0;
            @endphp

            <div class="prog-item">
                <div class="prog-top">
                    <span class="prog-name">Listening</span>
                    <span class="prog-pct" style="color:#2563EB">{{ $pL }}%</span>
                </div>
                <div class="prog-track">
                    <div class="prog-fill" style="width:{{ $pL }}%;background:linear-gradient(90deg,#3B82F6,#93C5FD)"></div>
                </div>
            </div>
            <div class="prog-item">
                <div class="prog-top">
                    <span class="prog-name">Reading</span>
                    <span class="prog-pct" style="color:#F59E0B">{{ $pR }}%</span>
                </div>
                <div class="prog-track">
                    <div class="prog-fill" style="width:{{ $pR }}%;background:linear-gradient(90deg,#F59E0B,#FCD34D)"></div>
                </div>
            </div>
            <div class="prog-item">
                <div class="prog-top">
                    <span class="prog-name">Structure</span>
                    <span class="prog-pct" style="color:#8B5CF6">{{ $pS }}%</span>
                </div>
                <div class="prog-track">
                    <div class="prog-fill" style="width:{{ $pS }}%;background:linear-gradient(90deg,#8B5CF6,#C4B5FD)"></div>
                </div>
            </div>

            @if(!$tesTerakhir)
            <p style="font-size:12.5px;color:var(--muted);text-align:center;padding:10px 0;margin-top:8px">
                <i class="fas fa-info-circle" style="margin-right:5px;color:var(--muted-lt)"></i>
                Selesaikan tes pertama untuk melihat progress
            </p>
            @endif

            <div style="text-align:center;padding-top:14px;border-top:1px solid var(--border);margin-top:14px">
                <a href="{{ route('user.hasil.index') }}"
                   style="font-size:13px;font-weight:700;color:var(--blue);text-decoration:none">
                    Lihat Detail Progress
                    <i class="fas fa-arrow-right" style="font-size:10px;margin-left:4px"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Skor Terakhir --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-trophy" style="color:var(--amber);margin-right:8px;font-size:13px"></i>Skor Terakhir</h3>
            <a href="{{ route('user.hasil.index') }}" class="btn btn-outline btn-sm">Riwayat</a>
        </div>
        <div class="card-body">
            @if($tesTerakhir)
            @php
                $skor  = $tesTerakhir->skor_total;
                $pSkor = min(100, round(($skor / 677) * 100));
                $sc    = 2 * M_PI * 38;
                $so    = $sc - ($pSkor / 100) * $sc;
                $clr   = $skor >= 500 ? '#22C55E' : ($skor >= 400 ? '#F59E0B' : '#EF4444');
            @endphp
            <div style="display:flex;align-items:center;gap:20px">
                <div class="sc-ring-wrap">
                    <svg width="96" height="96" viewBox="0 0 96 96">
                        <circle class="sc-ring-bg" cx="48" cy="48" r="38"/>
                        <circle class="sc-ring-fill" cx="48" cy="48" r="38"
                            stroke="{{ $clr }}"
                            stroke-dasharray="{{ $sc }}"
                            stroke-dashoffset="{{ $so }}"/>
                    </svg>
                    <div class="sc-ring-text">
                        <div style="font-size:26px;font-weight:900;color:{{ $clr }};line-height:1">{{ $skor }}</div>
                        <div style="font-size:9.5px;color:var(--muted);margin-top:2px">Total Skor</div>
                    </div>
                </div>
                <div style="flex:1">
                    <div style="font-size:14px;font-weight:700;color:var(--navy);margin-bottom:3px">
                        {{ $tesTerakhir->sesiTes->judul ?? 'Tes Terakhir' }}
                    </div>
                    <div style="font-size:12px;color:var(--muted);margin-bottom:10px">
                        {{ \Carbon\Carbon::parse($tesTerakhir->waktu_selesai)->format('d M Y') }}
                    </div>
                    @if($grafikData->count() >= 2)
                    @php
                        $vals = $grafikData->sortBy('tanggal')->values();
                        $diff = $vals->count() >= 2
                            ? $vals->last()->skor_toefl_estimasi - $vals->get($vals->count()-2)->skor_toefl_estimasi
                            : 0;
                    @endphp
                    <div style="display:inline-flex;align-items:center;gap:5px;
                        padding:4px 11px;border-radius:20px;font-size:12px;font-weight:700;
                        background:{{ $diff >= 0 ? '#F0FDF4' : '#FEF2F2' }};
                        color:{{ $diff >= 0 ? '#16A34A' : '#EF4444' }}">
                        <i class="fas fa-{{ $diff >= 0 ? 'arrow-up' : 'arrow-down' }}" style="font-size:9px"></i>
                        {{ $diff >= 0 ? '+' : '' }}{{ $diff }} poin
                    </div>
                    <div style="font-size:11px;color:var(--muted);margin-top:5px">Dari tes sebelumnya</div>
                    @endif
                </div>
            </div>
            <div style="text-align:center;padding-top:14px;border-top:1px solid var(--border);margin-top:14px">
                <a href="{{ route('user.hasil.index') }}"
                   style="font-size:13px;font-weight:700;color:var(--blue);text-decoration:none">
                    Lihat Riwayat
                    <i class="fas fa-arrow-right" style="font-size:10px;margin-left:4px"></i>
                </a>
            </div>
            @else
            <div class="empty-state" style="padding:28px 16px">
                <div style="width:52px;height:52px;border-radius:13px;background:var(--amber-lt);
                    color:var(--amber);display:flex;align-items:center;justify-content:center;
                    font-size:20px;margin:0 auto 12px">
                    <i class="fas fa-trophy"></i>
                </div>
                <p style="font-weight:700;color:var(--navy);margin-bottom:5px">Belum Ada Skor</p>
                <small>Selesaikan tes pertama untuk melihat skormu 🚀</small>
                <div style="margin-top:14px">
                    <a href="{{ route('user.tes.mini') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-bolt"></i> Mulai Mini Test
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Grafik --}}
@if($grafikData->count() > 0)
<div class="card" style="margin-top:2px">
    <div class="card-header">
        <h3><i class="fas fa-chart-area" style="color:#22C55E;margin-right:8px;font-size:13px"></i>Grafik Perkembangan Skor</h3>
        <span style="font-size:12px;color:var(--muted)">{{ $grafikData->count() }} sesi terakhir</span>
    </div>
    <div class="card-body"><canvas id="grafikChart" height="70"></canvas></div>
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
@if($grafikData->count() > 0)
new Chart(document.getElementById('grafikChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: {!! json_encode($grafikData->pluck('tanggal')) !!},
        datasets: [
            { label:'Skor Total', data:{!! json_encode($grafikData->pluck('skor_toefl_estimasi')) !!},
              borderColor:'#2563EB', backgroundColor:'rgba(37,99,235,.06)',
              tension:.4, fill:true, pointRadius:4, pointBackgroundColor:'#2563EB',
              pointBorderColor:'#fff', pointBorderWidth:2, borderWidth:2.5 },
            { label:'Listening', data:{!! json_encode($grafikData->pluck('skor_listening')) !!},
              borderColor:'#3B82F6', backgroundColor:'transparent',
              tension:.4, borderDash:[4,3], pointRadius:3, borderWidth:1.5 },
            { label:'Structure', data:{!! json_encode($grafikData->pluck('skor_structure')) !!},
              borderColor:'#F59E0B', backgroundColor:'transparent',
              tension:.4, borderDash:[4,3], pointRadius:3, borderWidth:1.5 },
            { label:'Reading', data:{!! json_encode($grafikData->pluck('skor_reading')) !!},
              borderColor:'#8B5CF6', backgroundColor:'transparent',
              tension:.4, borderDash:[4,3], pointRadius:3, borderWidth:1.5 },
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { labels:{ color:'#64748B', font:{ family:'Plus Jakarta Sans', size:12 }, boxWidth:12 } },
            tooltip: { backgroundColor:'#fff', titleColor:'#1E293B', bodyColor:'#64748B',
                       borderColor:'#E2E8F0', borderWidth:1,
                       callbacks:{ label: ctx => ` ${ctx.dataset.label}: ${ctx.raw}` } }
        },
        scales: {
            x: { ticks:{ color:'#94A3B8', font:{size:11} }, grid:{ color:'rgba(0,0,0,.04)', drawBorder:false } },
            y: { min:0, max:700,
                 ticks:{ color:'#94A3B8', stepSize:100, font:{size:11} },
                 grid:{ color:'rgba(0,0,0,.04)', drawBorder:false } }
        }
    }
});
@endif
</script>
@endpush
