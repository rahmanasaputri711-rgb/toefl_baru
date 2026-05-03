@extends('layouts.user')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('breadcrumb','Home / Dashboard')
@section('content')

@if(!auth()->user()->is_active)
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i>
    <div>
        <strong>Akun belum terverifikasi untuk Tes Full.</strong>
        Daftar ke sesi Tes Full dan upload KTM — admin akan memverifikasi status warga Polman Anda.
        <a href="{{ route('user.tes.full') }}" style="color:var(--gold);font-weight:700">Daftar Tes Full →</a>
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
            &nbsp;·&nbsp; No. Daftar: <strong style="font-family:'JetBrains Mono',monospace">{{ $pendaftaranAktif->nomor_pendaftaran }}</strong>
        @else
            <strong>Pendaftaran ditolak.</strong> {{ $pendaftaranAktif->catatan_admin }}
        @endif
    </div>
</div>
@endif

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="fas fa-graduation-cap"></i></div>
        <div><div class="stat-val">{{ $totalTes }}</div><div class="stat-label">Tes Selesai</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-gold"><i class="fas fa-star"></i></div>
        <div><div class="stat-val">{{ $skorTerbaik ?? '-' }}</div><div class="stat-label">Skor Terbaik</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green"><i class="fas fa-chart-line"></i></div>
        <div><div class="stat-val">{{ $skorRata ?? '-' }}</div><div class="stat-label">Rata-rata Skor</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-purple"><i class="fas fa-book-open"></i></div>
        <div><div class="stat-val">{{ $totalMateri }}</div><div class="stat-label">Materi Tersedia</div></div>
    </div>
</div>

<div class="grid-2" style="gap:20px;margin-bottom:20px">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-history" style="color:var(--accent);margin-right:8px"></i>Tes Terakhir</h3>
            <a href="{{ route('user.hasil.index') }}" class="btn btn-outline btn-sm">Semua</a>
        </div>
        @if($tesTerakhir)
        <div class="card-body">
            <div class="grid-3" style="margin-bottom:14px">
                @foreach(['Listening'=>$tesTerakhir->skor_listening,'Structure'=>$tesTerakhir->skor_structure,'Reading'=>$tesTerakhir->skor_reading] as $lbl=>$sk)
                <div style="background:var(--surface2);border-radius:10px;padding:14px;text-align:center">
                    <div style="font-size:22px;font-weight:800;color:var(--accent2)">{{ $sk }}</div>
                    <div style="font-size:11px;color:var(--muted);margin-top:3px">{{ $lbl }}</div>
                </div>
                @endforeach
            </div>
            <div style="text-align:center;background:var(--surface2);border-radius:10px;padding:16px">
                <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:4px">Skor TOEFL ITP</div>
                <div style="font-size:40px;font-weight:900;color:{{ $tesTerakhir->skor_total>=500 ? 'var(--green)':($tesTerakhir->skor_total>=400?'var(--gold)':'var(--red)') }}">
                    {{ $tesTerakhir->skor_total }}
                </div>
                <div style="font-size:12px;color:var(--muted);margin-top:4px">{{ \Carbon\Carbon::parse($tesTerakhir->waktu_selesai)->format('d M Y, H:i') }}</div>
                <a href="{{ route('user.hasil.detail',$tesTerakhir->id) }}" class="btn btn-outline btn-sm" style="margin-top:12px">
                    <i class="fas fa-eye"></i> Lihat Detail
                </a>
            </div>
        </div>
        @else
        <div class="empty-state"><i class="fas fa-clipboard-list"></i><p>Belum ada tes yang diselesaikan</p></div>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-bullhorn" style="color:var(--gold);margin-right:8px"></i>Pengumuman</h3>
        </div>
        @forelse($pengumuman as $p)
        <div style="padding:14px 18px;border-bottom:1px solid var(--border)">
            @if($p->is_pinned)
            <div style="font-size:10px;color:var(--gold);font-weight:700;text-transform:uppercase;margin-bottom:4px">
                <i class="fas fa-thumbtack"></i> Pinned
            </div>
            @endif
            <div style="font-weight:600;font-size:13.5px">{{ $p->judul }}</div>
            <div style="font-size:12px;color:var(--muted);margin-top:3px">{{ Str::limit($p->konten,100) }}</div>
            <div style="font-size:11px;color:var(--muted);margin-top:5px">{{ $p->published_at?->diffForHumans() }}</div>
        </div>
        @empty
        <div class="empty-state" style="padding:30px"><i class="fas fa-bullhorn"></i><p>Tidak ada pengumuman</p></div>
        @endforelse
    </div>
</div>

@if($grafikData->count() > 0)
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-chart-area" style="color:var(--green);margin-right:8px"></i>Grafik Perkembangan Skor</h3>
    </div>
    <div class="card-body"><canvas id="grafikChart" height="80"></canvas></div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
@if($grafikData->count() > 0)
new Chart(document.getElementById('grafikChart').getContext('2d'), {
    type:'line',
    data:{
        labels:{!! json_encode($grafikData->pluck('tanggal')) !!},
        datasets:[
            {label:'Skor Total',data:{!! json_encode($grafikData->pluck('skor_toefl_estimasi')) !!},borderColor:'#3b82f6',backgroundColor:'rgba(59,130,246,.08)',tension:.4,fill:true,pointRadius:4},
            {label:'Listening', data:{!! json_encode($grafikData->pluck('skor_listening')) !!},borderColor:'#10b981',backgroundColor:'transparent',tension:.4,borderDash:[4,3],pointRadius:3},
            {label:'Structure', data:{!! json_encode($grafikData->pluck('skor_structure')) !!},borderColor:'#f59e0b',backgroundColor:'transparent',tension:.4,borderDash:[4,3],pointRadius:3},
            {label:'Reading',   data:{!! json_encode($grafikData->pluck('skor_reading')) !!},borderColor:'#8b5cf6',backgroundColor:'transparent',tension:.4,borderDash:[4,3],pointRadius:3},
        ]
    },
    options:{responsive:true,plugins:{legend:{labels:{color:'#94a3b8',font:{family:'Plus Jakarta Sans'}}}},
        scales:{x:{ticks:{color:'#64748b'},grid:{color:'rgba(255,255,255,.04)'}},
                y:{min:200,max:700,ticks:{color:'#64748b'},grid:{color:'rgba(255,255,255,.04)'}}}}
});
@endif
</script>
@endpush
