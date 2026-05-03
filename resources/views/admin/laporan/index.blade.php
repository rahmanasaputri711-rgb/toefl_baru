@extends('layouts.admin')
@section('title','Laporan')
@section('page-title','Laporan Per Jadwal')
@section('breadcrumb','Admin / Laporan')

@section('content')

{{-- Filter sesi --}}
<div class="card" style="margin-bottom:18px">
    <div class="card-body" style="padding:16px 20px">
        <form method="GET" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
            <div style="flex:1;min-width:200px">
                <label class="form-label" style="font-size:11.5px">Pilih Jadwal Tes</label>
                <select name="sesi_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Pilih Jadwal --</option>
                    @foreach($sesiList as $s)
                    <option value="{{ $s->id }}" {{ $sesi_id==$s->id?'selected':'' }}>
                        {{ $s->judul }} — {{ $s->waktu_mulai?->format('d M Y') }}
                    </option>
                    @endforeach
                </select>
            </div>
            @if($sesi_id)
            <a href="{{ route('admin.laporan.export', $sesi_id) }}" class="btn btn-outline btn-sm">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
            @endif
        </form>
    </div>
</div>

@if($sesi)

{{-- ── Statistik Utama ── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:18px">
    @php
    $cards = [
        ['label'=>'Peserta Selesai',  'val'=>$stats['total_selesai'], 'ico'=>'flag-checkered','c'=>'si-blue'],
        ['label'=>'Rata-rata Skor',   'val'=>$stats['rata_total'],    'ico'=>'chart-bar',     'c'=>'si-blue'],
        ['label'=>'Lulus (≥500)',      'val'=>$stats['lulus'],         'ico'=>'check-circle',  'c'=>'si-green'],
        ['label'=>'Tidak Lulus',       'val'=>$stats['tidak_lulus'],   'ico'=>'times-circle',  'c'=>'si-red'],
        ['label'=>'Skor Tertinggi',   'val'=>$stats['tertinggi']??0,  'ico'=>'arrow-up',      'c'=>'si-green'],
        ['label'=>'Skor Terendah',    'val'=>$stats['terendah']??0,   'ico'=>'arrow-down',    'c'=>'si-red'],
        ['label'=>'Terindikasi Curang','val'=>$stats['curang'],        'ico'=>'exclamation-triangle','c'=>'si-gold'],
        ['label'=>'Total Hadir',      'val'=>$stats['hadir']??0,      'ico'=>'user-check',    'c'=>'si-blue'],
    ];
    @endphp
    @foreach($cards as $card)
    <div class="stat-card">
        <div class="stat-icon {{ $card['c'] }}"><i class="fas fa-{{ $card['ico'] }}"></i></div>
        <div>
            <div class="stat-val">{{ $card['val'] }}</div>
            <div class="stat-label">{{ $card['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Rata-rata per Seksi ── --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:18px">
    @foreach([['Listening','rata_listening','#fdba74','#fff7ed'],['Structure','rata_structure','#fde68a','#fffbeb'],['Reading','rata_reading','#93c5fd','#eff6ff']] as [$lbl,$key,$clr,$bg])
    <div style="background:{{ $bg }};border-radius:12px;padding:18px;text-align:center">
        <div style="font-size:28px;font-weight:800;color:{{ $clr }}">{{ $stats[$key] ?? 0 }}</div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px">Rata-rata {{ $lbl }}</div>
    </div>
    @endforeach
</div>

{{-- ── Distribusi Skor ── --}}
@if(!empty($distribusi) && array_sum($distribusi) > 0)
<div class="card" style="margin-bottom:18px">
    <div class="card-header">
        <h3><i class="fas fa-chart-bar" style="color:var(--accent);margin-right:8px"></i>Distribusi Skor</h3>
        <span style="font-size:12.5px;color:var(--muted)">Rentang skor TOEFL ITP (310–677)</span>
    </div>
    <div class="card-body">
        @php $maxVal = max(array_values($distribusi)) ?: 1; @endphp
        <div style="display:flex;align-items:flex-end;gap:10px;height:120px;padding-bottom:24px;
            border-bottom:1px solid var(--border)">
            @foreach($distribusi as $range => $count)
            @php $h = max(4, round(($count/$maxVal)*100)); @endphp
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;gap:4px">
                <div style="font-size:11.5px;font-weight:700;color:var(--text)">{{ $count }}</div>
                <div style="width:100%;border-radius:4px 4px 0 0;height:{{ $h }}%;
                    background:{{ str_contains($range,'5') && intval(explode('-',$range)[0])>=500 ? 'var(--green)' : 'var(--blue)' }};
                    opacity:{{ $count>0?1:.2 }};transition:all .3s;min-height:4px"></div>
            </div>
            @endforeach
        </div>
        <div style="display:flex;gap:10px;margin-top:6px">
            @foreach($distribusi as $range => $count)
            <div style="flex:1;text-align:center;font-size:10.5px;color:var(--muted)">{{ $range }}</div>
            @endforeach
        </div>
        <div style="display:flex;gap:14px;justify-content:center;margin-top:14px;font-size:12px">
            <span><span style="display:inline-block;width:12px;height:12px;background:var(--blue);border-radius:2px;margin-right:4px"></span>Di bawah 500</span>
            <span><span style="display:inline-block;width:12px;height:12px;background:var(--green);border-radius:2px;margin-right:4px"></span>Lulus ≥500</span>
        </div>
    </div>
</div>
@endif

{{-- ── Tabel Peserta ── --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list" style="color:var(--accent);margin-right:8px"></i>
            Daftar Peserta — {{ $sesi->judul }}
        </h3>
        <div style="font-size:12.5px;color:var(--muted)">{{ $percobaan->total() }} peserta</div>
    </div>
    <table class="tbl">
        <thead>
            <tr>
                <th width="40">#</th>
                <th>Nama Peserta</th>
                <th width="90" style="text-align:center">Listen</th>
                <th width="90" style="text-align:center">Structure</th>
                <th width="90" style="text-align:center">Reading</th>
                <th width="110" style="text-align:center">Skor Total</th>
                <th width="100" style="text-align:center">Status</th>
                <th width="80" style="text-align:center">Curang</th>
            </tr>
        </thead>
        <tbody>
        @forelse($percobaan as $i => $p)
        <tr>
            <td style="color:var(--muted)">{{ $percobaan->firstItem() + $i }}</td>
            <td>
                <div style="font-weight:600;font-size:13.5px">{{ $p->user?->name ?? '—' }}</div>
                <div style="font-size:11.5px;color:var(--muted)">{{ $p->user?->email }}</div>
            </td>
            <td style="text-align:center;font-weight:700;color:#d97706">{{ $p->skor_listening ?: '—' }}</td>
            <td style="text-align:center;font-weight:700;color:#b45309">{{ $p->skor_structure ?: '—' }}</td>
            <td style="text-align:center;font-weight:700;color:var(--blue)">{{ $p->skor_reading ?: '—' }}</td>
            <td style="text-align:center">
                @if($p->skor_total)
                <span style="font-size:18px;font-weight:800;
                    color:{{ $p->skor_total>=500?'var(--green)':'var(--red)' }}">
                    {{ $p->skor_total }}
                </span>
                @if($p->skor_total>=500)
                <div style="font-size:10px;color:var(--green);margin-top:1px">✓ LULUS</div>
                @endif
                @else
                <span style="color:var(--muted)">—</span>
                @endif
            </td>
            <td style="text-align:center">
                @if($p->status==='selesai')
                    <span class="badge badge-green" style="font-size:11px">Selesai</span>
                @elseif($p->status==='dibatalkan')
                    <span class="badge badge-red" style="font-size:11px">Dibatalkan</span>
                @endif
            </td>
            <td style="text-align:center">
                @if($p->status_curang)
                <span class="badge badge-red" style="font-size:11px">
                    <i class="fas fa-exclamation-triangle" style="font-size:9px"></i>
                    {{ $p->pelanggaran?->count() ?? $p->jumlah_pelanggaran }}×
                </span>
                @else
                <i class="fas fa-check" style="color:var(--muted);font-size:12px"></i>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8">
                <div class="empty-state"><i class="fas fa-inbox"></i><p>Belum ada peserta yang menyelesaikan tes.</p></div>
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:14px 20px;border-top:1px solid var(--border)">
        {{ $percobaan->withQueryString()->links() }}
    </div>
</div>

@else
<div class="card">
    <div class="card-body" style="text-align:center;padding:60px">
        <i class="fas fa-chart-bar" style="font-size:40px;color:var(--muted);display:block;margin-bottom:14px"></i>
        <div style="font-size:16px;font-weight:700;margin-bottom:6px">Pilih Jadwal Tes</div>
        <p style="color:var(--muted)">Pilih jadwal di atas untuk melihat laporan dan statistik.</p>
    </div>
</div>
@endif
@endsection
