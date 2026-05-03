@extends('layouts.admin')
@section('title','Paket Soal')
@section('page-title','Manajemen Paket Soal')
@section('breadcrumb','Admin / Paket Soal')
@section('content')

<div class="alert alert-info" style="margin-bottom:18px">
    <i class="fas fa-info-circle"></i>
    <div>
        <strong>Paket Soal</strong> adalah kumpulan soal yang sudah divalidasi sesuai standar TOEFL ITP:
        <strong>50 Listening · 40 Structure · 50 Reading = 140 soal</strong>.
        Paket yang <span style="color:#4ade80">Valid</span> siap digunakan untuk Sesi Tes Full.
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-boxes" style="color:var(--accent);margin-right:8px"></i>Daftar Paket Soal</h3>
        <a href="{{ route('admin.paket.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Paket Baru</a>
    </div>

    <table class="tbl">
        <thead>
            <tr>
                <th width="50">#</th>
                <th>Nama Paket</th>
                <th width="130">Listening</th>
                <th width="130">Structure</th>
                <th width="130">Reading</th>
                <th width="100">Status</th>
                <th width="70">Aktif</th>
                <th width="160">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($paket as $i => $p)
        <tr>
            <td style="color:var(--text-muted);font-size:12px">{{ $paket->firstItem()+$i }}</td>
            <td>
                <div style="font-size:13.5px;font-weight:700">{{ $p->nama }}</div>
                @if($p->deskripsi)
                <div style="font-size:12px;color:var(--text-muted)">{{ Str::limit($p->deskripsi,55) }}</div>
                @endif
                <div style="font-size:11px;color:var(--text-muted);margin-top:3px">
                    Total: {{ $p->soal_count }} soal · Dibuat {{ $p->created_at->format('d M Y') }}
                </div>
            </td>

            @php
                $tgt = \App\Models\PaketSoal::TARGET;
                function soalCol($v, $t) {
                    $ok  = $v === $t;
                    $clr = $v > $t ? '#f87171' : ($v === $t ? '#4ade80' : 'var(--muted)');
                    $pct = $t > 0 ? min(100,round($v/$t*100)) : 0;
                    return [$ok,$clr,$pct];
                }
                [$lOk,$lClr,$lPct] = soalCol($p->jumlah_listening, $tgt['listening']);
                [$sOk,$sClr,$sPct] = soalCol($p->jumlah_structure,  $tgt['structure']);
                [$rOk,$rClr,$rPct] = soalCol($p->jumlah_reading,    $tgt['reading']);
            @endphp

            @foreach([
                [$p->jumlah_listening,$tgt['listening'],$lClr,$lPct],
                [$p->jumlah_structure,$tgt['structure'],$sClr,$sPct],
                [$p->jumlah_reading,  $tgt['reading'],  $rClr,$rPct],
            ] as [$v,$t,$clr,$pct])
            <td>
                <div style="font-size:13.5px;font-weight:700;color:{{ $clr }}">
                    {{ $v }} <span style="font-size:11px;color:rgba(255,255,255,.3);font-weight:400">/ {{ $t }}</span>
                </div>
                <div style="height:4px;background:rgba(255,255,255,.07);border-radius:2px;margin-top:5px">
                    <div style="height:4px;background:{{ $clr }};border-radius:2px;width:{{ $pct }}%;transition:width .3s"></div>
                </div>
            </td>
            @endforeach

            <td>
                @if($p->status==='valid')
                <span class="badge badge-green" style="font-size:11px"><i class="fas fa-check" style="font-size:9px"></i> Valid</span>
                @elseif($p->status==='invalid')
                <span class="badge badge-red" style="font-size:11px"><i class="fas fa-times" style="font-size:9px"></i> Invalid</span>
                @else
                <span class="badge badge-gray" style="font-size:11px"><i class="fas fa-circle" style="font-size:7px"></i> Draft</span>
                @endif
            </td>
            <td style="text-align:center">
                @if($p->is_aktif)
                <i class="fas fa-check-circle" style="color:#4ade80;font-size:16px"></i>
                @else
                <i class="fas fa-times-circle" style="color:rgba(255,255,255,.15);font-size:16px"></i>
                @endif
            </td>
            <td>
                <div style="display:flex;gap:5px;flex-wrap:wrap">
                    <a href="{{ route('admin.paket.edit', $p->id) }}" class="btn btn-primary btn-sm" title="Edit & kelola soal">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.paket.validate', $p->id) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm" title="Validasi ulang">
                            <i class="fas fa-check-double"></i>
                        </button>
                    </form>
                    <form action="{{ route('admin.paket.destroy', $p->id) }}" method="POST"
                        onsubmit="return confirm('Hapus paket ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="8">
            <div class="empty-state">
                <i class="fas fa-boxes"></i>
                <p>Belum ada paket soal. <a href="{{ route('admin.paket.create') }}" style="color:var(--accent)">Buat paket pertama</a></p>
            </div>
        </td></tr>
        @endforelse
        </tbody>
    </table>

    <div style="padding:14px 20px;display:flex;align-items:center;justify-content:space-between;
        border-top:1px solid var(--border);font-size:13px">
        <div style="color:var(--text-muted)">{{ $paket->total() }} paket</div>
        {{ $paket->links() }}
    </div>
</div>
@endsection
