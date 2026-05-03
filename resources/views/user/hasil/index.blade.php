@extends('layouts.user')
@section('title','Riwayat Tes')
@section('page-title','Riwayat & Grafik Tes')
@section('breadcrumb','Home / Riwayat')

@section('content')

{{-- Statistik ringkas --}}
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:22px">
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="fas fa-clipboard-list"></i></div>
        <div>
            <div class="stat-val">{{ $stats['total_tes'] }}</div>
            <div class="stat-label">Total Tes Selesai</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green"><i class="fas fa-arrow-up"></i></div>
        <div>
            <div class="stat-val">{{ $stats['skor_max'] }}</div>
            <div class="stat-label">Skor Tertinggi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-gold"><i class="fas fa-chart-bar"></i></div>
        <div>
            <div class="stat-val">{{ $stats['skor_rata'] }}</div>
            <div class="stat-label">Rata-rata Skor</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-red"><i class="fas fa-arrow-down"></i></div>
        <div>
            <div class="stat-val">{{ $stats['skor_min'] }}</div>
            <div class="stat-label">Skor Terendah</div>
        </div>
    </div>
</div>

{{-- Grafik perkembangan skor --}}
@if($grafik->count() > 1)
<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <h3>
            <i class="fas fa-chart-area" style="color:var(--green);margin-right:8px"></i>
            Grafik Perkembangan Skor TOEFL ITP
        </h3>
    </div>
    <div class="card-body">
        <canvas id="grafikChart" height="70"></canvas>
    </div>
</div>
@endif

{{-- Tabel riwayat --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-history" style="color:var(--accent);margin-right:8px"></i>Riwayat Semua Tes</h3>
        <div style="font-size:12.5px;color:var(--muted)">
            Klik baris untuk detail & unduh sertifikat
        </div>
    </div>

    <table class="tbl">
        <thead>
            <tr>
                <th width="52">#</th>
                <th>Sesi / Tanggal</th>
                <th width="55" style="text-align:center">Ke-</th>
                <th width="90" style="text-align:center">Listening</th>
                <th width="90" style="text-align:center">Structure</th>
                <th width="90" style="text-align:center">Reading</th>
                <th width="110" style="text-align:center">Skor Total</th>
                <th width="100" style="text-align:center">Status</th>
                <th width="160">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($percobaan as $idx => $p)
        @php
            $warna = $p->skor_total >= 500 ? 'var(--green)' : ($p->skor_total >= 400 ? 'var(--gold)' : 'var(--red)');
        @endphp
        <tr style="cursor:pointer" onclick="window.location='{{ route('user.hasil.detail', $p->id) }}'">
            <td>
                <div style="width:28px;height:28px;border-radius:7px;background:rgba(26,86,219,.12);
                    color:var(--accent);display:flex;align-items:center;justify-content:center;
                    font-size:12px;font-weight:700">
                    {{ $percobaan->firstItem() + $idx }}
                </div>
            </td>
            <td>
                <div style="font-weight:600;font-size:13.5px">
                    {{ $p->sesiTes->judul ?? 'Tes' }}
                </div>
                <div style="font-size:11px;color:var(--muted);margin-top:2px">
                    {{ $p->waktu_selesai ? \Carbon\Carbon::parse($p->waktu_selesai)->format('d M Y, H:i') : '-' }}
                    @if($p->jumlah_pelanggaran > 0)
                    &nbsp;·&nbsp;
                    <span style="color:var(--red)">
                        <i class="fas fa-exclamation-triangle" style="font-size:9px"></i>
                        {{ $p->jumlah_pelanggaran }} pelanggaran
                    </span>
                    @endif
                </div>
            </td>
            {{-- Percobaan ke-N --}}
            <td style="text-align:center">
                <span style="display:inline-block;padding:3px 9px;border-radius:20px;
                    font-size:12px;font-weight:700;
                    background:{{ $p->tes_ke === 1 ? 'rgba(26,86,219,.15)' : ($p->tes_ke === 2 ? 'rgba(217,119,6,.15)' : 'rgba(16,185,129,.15)') }};
                    color:{{ $p->tes_ke === 1 ? 'var(--accent)' : ($p->tes_ke === 2 ? 'var(--gold)' : 'var(--green)') }}">
                    {{ $p->tes_ke }}×
                </span>
            </td>
            <td style="text-align:center;font-size:15px;font-weight:700;color:#fdba74">
                {{ $p->skor_listening ?: '—' }}
            </td>
            <td style="text-align:center;font-size:15px;font-weight:700;color:#fde68a">
                {{ $p->skor_structure ?: '—' }}
            </td>
            <td style="text-align:center;font-size:15px;font-weight:700;color:#93c5fd">
                {{ $p->skor_reading ?: '—' }}
            </td>
            <td style="text-align:center">
                <div style="font-size:26px;font-weight:900;color:{{ $warna }};line-height:1">
                    {{ $p->skor_total }}
                </div>
                @if($p->skor_total >= 500)
                <div style="font-size:10px;color:var(--green);margin-top:2px">✓ LULUS</div>
                @endif
            </td>
            <td style="text-align:center">
                @if($p->status === 'selesai')
                <span class="badge badge-green">Selesai</span>
                @elseif($p->status === 'berlangsung')
                <span class="badge badge-gold">Berlangsung</span>
                @elseif($p->status === 'dibatalkan')
                <span class="badge badge-red">Dibatalkan</span>
                @else
                <span class="badge badge-gray">{{ ucfirst($p->status) }}</span>
                @endif
            </td>
            <td onclick="event.stopPropagation()">
                @if($p->status === 'selesai')
                <div style="display:flex;gap:6px">
                    <a href="{{ route('user.hasil.detail', $p->id) }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-eye"></i> Detail
                    </a>
                    <a href="{{ route('user.hasil.cetak', $p->id) }}" class="btn btn-primary btn-sm" target="_blank">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                </div>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9">
                <div class="empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <p>Belum ada tes yang diselesaikan.</p>
                    <a href="{{ route('user.tes.full') }}" class="btn btn-primary btn-sm" style="margin-top:12px">
                        <i class="fas fa-play-circle"></i> Mulai Tes
                    </a>
                </div>
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>

    <div style="padding:14px 20px;display:flex;align-items:center;justify-content:space-between;
        border-top:1px solid var(--border);font-size:13px">
        <div style="color:var(--muted)">{{ $percobaan->total() }} tes</div>
        {{ $percobaan->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
@if($grafik->count() > 1)
const labels  = {!! json_encode($grafik->map(fn($g) => \Carbon\Carbon::parse($g->tanggal)->format('d M Y'))) !!};
const totals  = {!! json_encode($grafik->pluck('skor_toefl_estimasi')) !!};
const listens = {!! json_encode($grafik->pluck('skor_listening')) !!};
const structs = {!! json_encode($grafik->pluck('skor_structure')) !!};
const reads   = {!! json_encode($grafik->pluck('skor_reading')) !!};

new Chart(document.getElementById('grafikChart').getContext('2d'), {
    type: 'line',
    data: {
        labels,
        datasets: [
            {
                label: 'Skor Total',
                data: totals,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,.08)',
                tension: .4, fill: true,
                pointRadius: 5, pointHoverRadius: 7,
                pointBackgroundColor: '#3b82f6',
                borderWidth: 2.5,
            },
            {
                label: 'Listening',
                data: listens,
                borderColor: '#ea580c',
                backgroundColor: 'transparent',
                tension: .4, pointRadius: 4,
                borderWidth: 1.5, borderDash: [4,3],
            },
            {
                label: 'Structure',
                data: structs,
                borderColor: '#d97706',
                backgroundColor: 'transparent',
                tension: .4, pointRadius: 4,
                borderWidth: 1.5, borderDash: [4,3],
            },
            {
                label: 'Reading',
                data: reads,
                borderColor: '#8b5cf6',
                backgroundColor: 'transparent',
                tension: .4, pointRadius: 4,
                borderWidth: 1.5, borderDash: [4,3],
            },
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { labels: { color: '#94a3b8', font: { family: 'Plus Jakarta Sans' } } },
            tooltip: { callbacks: { label: ctx => ` ${ctx.dataset.label}: ${ctx.raw}` } }
        },
        scales: {
            x: { ticks: { color: '#64748b' }, grid: { color: 'rgba(255,255,255,.04)' } },
            y: {
                min: 200, max: 700,
                ticks: { color: '#64748b', stepSize: 50 },
                grid: { color: 'rgba(255,255,255,.04)' }
            }
        }
    }
});
@endif
</script>
@endpush
