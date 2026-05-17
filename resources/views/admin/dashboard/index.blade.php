@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Admin / Dashboard')

@section('content')

{{-- ══ STAT GRID ══ --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-val">{{ $totalUser }}</div>
            <div class="stat-label">Total User Aktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-gold"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-val">{{ $pendingVerif }}</div>
            <div class="stat-label">Menunggu Verifikasi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green"><i class="fas fa-question-circle"></i></div>
        <div>
            <div class="stat-val">{{ $totalSoal }}</div>
            <div class="stat-label">Total Soal</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-orange"><i class="fas fa-calendar-check"></i></div>
        <div>
            <div class="stat-val">{{ $sesiAktif }}</div>
            <div class="stat-label">Sesi Tes Aktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-red"><i class="fas fa-exclamation-triangle"></i></div>
        <div>
            <div class="stat-val">{{ $pelanggaran }}</div>
            <div class="stat-label">Pelanggaran Hari Ini</div>
        </div>
    </div>
</div>

{{-- ══ PENDAFTARAN + SESI ══ --}}
<div class="grid-2" style="gap:18px;margin-bottom:20px">

    {{-- Pendaftaran Terbaru --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-clipboard-list" style="color:var(--accent);margin-right:8px;font-size:13px"></i>Pendaftaran Terbaru</h3>
            <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <table class="tbl">
            <thead><tr>
                <th>Nama</th><th>Sesi</th><th>Status</th>
            </tr></thead>
            <tbody>
            @forelse($pendaftaranTerbaru as $p)
            <tr>
                <td>
                    <div style="font-weight:600;font-size:13px;color:var(--text)">{{ $p->user->name ?? '-' }}</div>
                    <div style="font-size:11px;color:var(--text-muted)">{{ $p->nim_nip }}</div>
                </td>
                <td style="font-size:12.5px;color:var(--text-dim)">{{ $p->sesiTes->judul ?? '-' }}</td>
                <td>
                    @if($p->status_pendaftaran == 'menunggu')
                        <span class="badge badge-gold"><i class="fas fa-circle" style="font-size:5px"></i> Menunggu</span>
                    @elseif($p->status_pendaftaran == 'dikonfirmasi')
                        <span class="badge badge-green"><i class="fas fa-circle" style="font-size:5px"></i> Dikonfirmasi</span>
                    @else
                        <span class="badge badge-red"><i class="fas fa-circle" style="font-size:5px"></i> Ditolak</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="3">
                <div class="empty-state" style="padding:32px">
                    <div class="empty-icon" style="color:var(--text-muted)"><i class="fas fa-clipboard-list"></i></div>
                    <h4>Belum Ada Pendaftaran</h4>
                    <p>Pendaftaran baru akan muncul di sini.</p>
                </div>
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Sesi Mendatang --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-calendar-alt" style="color:var(--gold);margin-right:8px;font-size:13px"></i>Sesi Tes Mendatang</h3>
            <a href="{{ route('admin.sesi.index') }}" class="btn btn-outline btn-sm">Kelola</a>
        </div>
        <table class="tbl">
            <thead><tr>
                <th>Judul</th><th>Tanggal</th><th>Status</th>
            </tr></thead>
            <tbody>
            @forelse($sesiMendatang as $s)
            <tr>
                <td>
                    <div style="font-weight:600;font-size:13px;color:var(--text)">{{ $s->judul }}</div>
                    <div style="font-size:11px;color:var(--text-muted)">{{ $s->peserta_terdaftar }}/{{ $s->kuota_peserta }} peserta</div>
                </td>
                <td style="font-size:12.5px;color:var(--text-dim)">{{ \Carbon\Carbon::parse($s->waktu_mulai)->format('d M Y') }}</td>
                <td>
                    @if($s->is_aktif)
                        <span class="badge badge-green">Aktif</span>
                    @else
                        <span class="badge badge-gray">Nonaktif</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="3">
                <div class="empty-state" style="padding:32px">
                    <div class="empty-icon" style="color:var(--text-muted)"><i class="fas fa-calendar-alt"></i></div>
                    <h4>Tidak Ada Sesi</h4>
                    <p>Buat sesi tes baru untuk mulai.</p>
                </div>
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ══ PELANGGARAN TERAKHIR ══ --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-shield-exclamation" style="color:var(--red);margin-right:8px;font-size:13px"></i>Pelanggaran Terakhir</h3>
        <a href="{{ route('admin.monitoring.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-desktop"></i> Monitor
        </a>
    </div>
    <table class="tbl">
        <thead><tr>
            <th>User</th><th>Tipe Aksi</th><th>Detail</th><th>Ke-</th><th>Waktu</th>
        </tr></thead>
        <tbody>
        @forelse($aktivitasLog as $log)
        <tr>
            <td style="font-weight:600;font-size:13px;color:var(--text)">{{ $log->user->name ?? '-' }}</td>
            <td><span class="badge badge-red">{{ $log->tipe_aksi }}</span></td>
            <td style="font-size:12px;color:var(--text-muted);max-width:220px">{{ $log->detail }}</td>
            <td style="text-align:center">
                <span class="badge {{ $log->pelanggaran_ke >= 3 ? 'badge-red' : ($log->pelanggaran_ke == 2 ? 'badge-orange' : 'badge-gold') }}">
                    {{ $log->pelanggaran_ke }}x
                </span>
            </td>
            <td style="font-size:12px;color:var(--text-muted)">
                {{ $log->created_at ? $log->created_at->diffForHumans() : '-' }}
            </td>
        </tr>
        @empty
        <tr><td colspan="5">
            <div class="empty-state" style="padding:40px">
                <div class="empty-icon" style="color:var(--green)"><i class="fas fa-shield-check"></i></div>
                <h4 style="color:var(--green)">Tidak Ada Pelanggaran</h4>
                <p>Semua sesi berjalan dengan baik hari ini.</p>
            </div>
        </td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection
